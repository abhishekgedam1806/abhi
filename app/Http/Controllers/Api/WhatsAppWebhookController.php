<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\WhatsAppSetting;
use App\WhatsAppNotification;
use App\Services\WhatsApp\WhatsAppNotificationService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class WhatsAppWebhookController extends Controller
{
    /**
     * Handle Webhook Verification Challenge (GET) for Meta / Webhooks
     */
    public function verify(Request $request)
    {
        $setting = WhatsAppSetting::getSettings();
        $verifyToken = $setting->webhook_verify_token;

        $mode = $request->query('hub_mode', $request->query('hub.mode'));
        $token = $request->query('hub_verify_token', $request->query('hub.verify_token'));
        $challenge = $request->query('hub_challenge', $request->query('hub.challenge'));

        if ($mode === 'subscribe' && $token === $verifyToken) {
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        // Generic token check if query parameter ?token=...
        if ($request->query('token') === $verifyToken) {
            return response()->json(['status' => 'verified', 'timestamp' => Carbon::now()->toIso8601String()]);
        }

        return response()->json(['error' => 'Invalid verification token'], 403);
    }

    /**
     * Handle Inbound Delivery & Read Status Updates (POST)
     */
    public function handle(Request $request, WhatsAppNotificationService $service)
    {
        $payload = $request->all();

        try {
            $driver = $service->getDriver();
            $events = $driver->parseWebhook($payload);

            foreach ($events as $ev) {
                $msgId = $ev['message_id'] ?? null;
                $status = $ev['status'] ?? null; // sent, delivered, read, failed

                if (empty($msgId) || empty($status)) {
                    continue;
                }

                $notification = WhatsAppNotification::where('provider_message_id', $msgId)->first();

                if ($notification) {
                    if ($status === 'delivered') {
                        $notification->markAsDelivered();
                    } elseif ($status === 'read') {
                        $notification->markAsRead();
                    } elseif ($status === 'failed') {
                        $notification->markAsFailed($ev['error'] ?? 'Provider reported delivery failure');
                    } elseif ($status === 'sent') {
                        $notification->markAsSent();
                    }
                }
            }

            return response()->json(['success' => true, 'processed_events' => count($events)]);
        } catch (Exception $e) {
            Log::warning("WhatsApp Webhook processing error: " . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
