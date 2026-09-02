<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\WhatsAppSetting;
use App\WhatsAppTemplate;
use App\WhatsAppNotification;
use App\UserWhatsAppPreference;
use App\Services\WhatsApp\WhatsAppNotificationService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
{
    protected $service;

    public function __construct(WhatsAppNotificationService $service)
    {
        $this->service = $service;
    }

    /**
     * WhatsApp Analytics & Dashboard Overview
     */
    public function index()
    {
        $setting = WhatsAppSetting::getSettings();

        // High level KPI metrics
        $totalNotifications = WhatsAppNotification::count();
        $totalSent = WhatsAppNotification::whereIn('status', ['sent', 'delivered', 'read'])->count();
        $totalDelivered = WhatsAppNotification::whereIn('status', ['delivered', 'read'])->count();
        $totalRead = WhatsAppNotification::where('status', 'read')->count();
        $totalFailed = WhatsAppNotification::where('status', 'failed')->count();
        $totalQueued = WhatsAppNotification::where('status', 'queued')->count();

        // Today's metrics
        $todaySent = WhatsAppNotification::whereDate('created_at', Carbon::today())->whereIn('status', ['sent', 'delivered', 'read'])->count();
        $todayFailed = WhatsAppNotification::whereDate('created_at', Carbon::today())->where('status', 'failed')->count();

        // Delivery Rate %
        $deliveryRate = $totalSent > 0 ? round(($totalDelivered / $totalSent) * 100, 1) : 100.0;
        $readRate = $totalDelivered > 0 ? round(($totalRead / $totalDelivered) * 100, 1) : 0.0;

        // Recent Notifications
        $recentNotifications = WhatsAppNotification::latest()->take(10)->get();

        // Event Distribution
        $eventBreakdown = WhatsAppNotification::selectRaw('event_type, count(*) as count')
            ->groupBy('event_type')
            ->orderByDesc('count')
            ->take(6)
            ->get();

        return view('admin.whatsapp.index', compact(
            'setting',
            'totalNotifications',
            'totalSent',
            'totalDelivered',
            'totalRead',
            'totalFailed',
            'totalQueued',
            'todaySent',
            'todayFailed',
            'deliveryRate',
            'readRate',
            'recentNotifications',
            'eventBreakdown'
        ));
    }

    /**
     * WhatsApp Configuration & Provider Settings
     */
    public function settings()
    {
        $setting = WhatsAppSetting::getSettings();
        $webhookUrl = url('/api/whatsapp/webhook');

        return view('admin.whatsapp.settings', compact('setting', 'webhookUrl'));
    }

    /**
     * Update Provider Configuration
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'provider' => 'required|in:meta,gupshup,twilio,ultramsg,log',
            'phone_number_id' => 'nullable|string|max:191',
            'business_account_id' => 'nullable|string|max:191',
            'sender_number' => 'nullable|string|max:50',
            'api_endpoint' => 'nullable|string|max:255',
            'daily_limit' => 'required|integer|min:10|max:100000',
        ]);

        $setting = WhatsAppSetting::getSettings();
        $setting->provider = $request->input('provider');
        $setting->is_enabled = $request->has('is_enabled');
        $setting->test_mode = $request->has('test_mode');
        $setting->phone_number_id = $request->input('phone_number_id');
        $setting->business_account_id = $request->input('business_account_id');
        $setting->sender_number = $request->input('sender_number');
        $setting->api_endpoint = $request->input('api_endpoint');
        $setting->daily_limit = (int)$request->input('daily_limit', 500);

        // Feature toggles
        $setting->enable_candidate_notifications = $request->has('enable_candidate_notifications');
        $setting->enable_employer_notifications = $request->has('enable_employer_notifications');
        $setting->enable_matching_alerts = $request->has('enable_matching_alerts');
        $setting->enable_application_alerts = $request->has('enable_application_alerts');
        $setting->enable_status_alerts = $request->has('enable_status_alerts');
        $setting->enable_message_alerts = $request->has('enable_message_alerts');
        $setting->enable_payment_alerts = $request->has('enable_payment_alerts');

        // Only update API keys if provided
        if ($request->filled('api_key')) {
            $setting->api_key = $request->input('api_key');
        }
        if ($request->filled('api_secret')) {
            $setting->api_secret = $request->input('api_secret');
        }
        if ($request->filled('webhook_verify_token')) {
            $setting->webhook_verify_token = $request->input('webhook_verify_token');
        }

        $setting->save();

        flash(__('WhatsApp configuration updated successfully.'))->success();
        return redirect()->route('admin.whatsapp.settings');
    }

    /**
     * Test Provider Connection & Send Test Message
     */
    public function testConnection(Request $request)
    {
        $setting = WhatsAppSetting::getSettings();
        $driver = $this->service->getDriver();

        $testRes = $driver->testConnection();

        $setting->last_tested_at = Carbon::now();
        $setting->last_test_status = $testRes['success'] ? 'success' : 'failed';
        $setting->last_test_message = $testRes['message'] ?? ($testRes['error'] ?? 'No response');
        $setting->save();

        // Optional test message to custom phone
        $testPhone = $request->input('test_phone');
        if ($testPhone && $testRes['success']) {
            $sendRes = $driver->sendDirectMessage(
                $testPhone,
                "✅ *SolocomDigi WhatsApp Notification Test*\n\nYour WhatsApp Notification System is connected and working perfectly!\n\nTimestamp: " . Carbon::now()->format('d M Y, h:i A')
            );

            if ($sendRes['success']) {
                $testRes['message'] .= " • Test message successfully sent to {$testPhone}!";
            } else {
                $testRes['message'] .= " • Could not send test message: " . ($sendRes['error'] ?? 'Unknown error');
            }
        }

        return response()->json($testRes);
    }

    /**
     * Templates Registry & Editor
     */
    public function templates()
    {
        $templates = WhatsAppTemplate::orderBy('category')->orderBy('title')->get();
        return view('admin.whatsapp.templates', compact('templates'));
    }

    /**
     * Update an individual template
     */
    public function updateTemplate(Request $request, $id)
    {
        $template = WhatsAppTemplate::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:191',
            'provider_template_name' => 'nullable|string|max:191',
            'header_text' => 'nullable|string|max:191',
            'body_text' => 'required|string',
            'footer_text' => 'nullable|string|max:191',
        ]);

        $template->title = $request->input('title');
        $template->provider_template_name = $request->input('provider_template_name');
        $template->header_text = $request->input('header_text');
        $template->body_text = $request->input('body_text');
        $template->footer_text = $request->input('footer_text');
        $template->is_active = $request->has('is_active');
        $template->save();

        flash(__('Template updated successfully.'))->success();
        return redirect()->route('admin.whatsapp.templates');
    }

    /**
     * Audit Logs & Telemetry
     */
    public function logs(Request $request)
    {
        $query = WhatsAppNotification::query();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->input('event_type'));
        }

        if ($request->filled('template_key')) {
            $query->where('template_key', $request->input('template_key'));
        }

        if ($request->filled('recipient_type')) {
            $query->where('notifiable_type', $request->input('recipient_type'));
        }

        if ($request->filled('search')) {
            $s = trim($request->input('search'));
            $query->where(function ($q) use ($s) {
                $q->where('recipient_phone', 'like', "%{$s}%")
                  ->orWhere('provider_message_id', 'like', "%{$s}%")
                  ->orWhere('rendered_message', 'like', "%{$s}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $logs = $query->latest()->paginate(25)->appends($request->all());

        $templates = WhatsAppTemplate::pluck('title', 'template_key');
        $eventTypes = WhatsAppNotification::select('event_type')->distinct()->pluck('event_type');

        return view('admin.whatsapp.logs', compact('logs', 'templates', 'eventTypes'));
    }

    /**
     * Resend / Retry a failed notification
     */
    public function resendNotification($id)
    {
        $notification = WhatsAppNotification::findOrFail($id);
        $notification->status = 'queued';
        $notification->error_message = null;
        $notification->save();

        dispatch(new \App\Jobs\SendWhatsAppNotificationJob($notification->id));

        flash(__('Notification re-queued for delivery.'))->success();
        return back();
    }
}
