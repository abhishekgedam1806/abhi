<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserWhatsAppPreference;
use App\LoginOtp;
use App\Services\WhatsApp\WhatsAppNotificationService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class WhatsAppPreferenceController extends Controller
{
    protected $service;

    public function __construct(WhatsAppNotificationService $service)
    {
        $this->service = $service;
    }

    /**
     * Get Preferences for the currently authenticated User or Company
     */
    public function getPreferences(Request $request)
    {
        $entity = $this->resolveCurrentAuth();
        if (!$entity) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $pref = UserWhatsAppPreference::getPreferenceFor(
            $entity['type'],
            $entity['id'],
            $entity['phone']
        );

        return response()->json([
            'success' => true,
            'preferences' => $pref,
            'phone' => $pref->whatsapp_number ?: $entity['phone'],
            'is_verified' => (bool)$pref->is_verified,
        ]);
    }

    /**
     * Update Notification Preferences & WhatsApp Number
     */
    public function updatePreferences(Request $request)
    {
        $entity = $this->resolveCurrentAuth();
        if (!$entity) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $pref = UserWhatsAppPreference::getPreferenceFor(
            $entity['type'],
            $entity['id'],
            $entity['phone']
        );

        $newNumber = trim($request->input('whatsapp_number', ''));

        // If number changed, reset verification
        if (!empty($newNumber) && $newNumber !== $pref->whatsapp_number) {
            $pref->whatsapp_number = $newNumber;
            $pref->is_verified = false;
            $pref->verified_at = null;
        }

        if ($request->has('allow_matching_jobs')) {
            $pref->allow_matching_jobs = (bool)$request->input('allow_matching_jobs');
        }
        if ($request->has('allow_application_updates')) {
            $pref->allow_application_updates = (bool)$request->input('allow_application_updates');
        }
        if ($request->has('allow_messages')) {
            $pref->allow_messages = (bool)$request->input('allow_messages');
        }
        if ($request->has('allow_job_status')) {
            $pref->allow_job_status = (bool)$request->input('allow_job_status');
        }
        if ($request->has('allow_candidate_matches')) {
            $pref->allow_candidate_matches = (bool)$request->input('allow_candidate_matches');
        }
        if ($request->has('allow_account_payments')) {
            $pref->allow_account_payments = (bool)$request->input('allow_account_payments');
        }
        if ($request->has('allow_promotional')) {
            $pref->allow_promotional = (bool)$request->input('allow_promotional');
        }

        $pref->save();

        return response()->json([
            'success' => true,
            'message' => 'WhatsApp notification preferences saved successfully.',
            'preferences' => $pref,
        ]);
    }

    /**
     * Send OTP on WhatsApp to verify the phone number
     */
    public function sendVerificationOtp(Request $request)
    {
        $entity = $this->resolveCurrentAuth();
        if (!$entity) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $phone = trim($request->input('whatsapp_number', ''));
        if (empty($phone)) {
            $phone = $entity['phone'];
        }

        if (empty($phone)) {
            return response()->json(['success' => false, 'message' => 'Please provide a valid WhatsApp phone number.'], 422);
        }

        // Generate 6-digit OTP
        $otp = (string)random_int(100000, 999999);

        // Store OTP in login_otps table
        LoginOtp::create([
            'identifier' => $phone,
            'otp_code' => $otp,
            'expires_at' => Carbon::now()->addMinutes(10),
            'ip_address' => $request->ip(),
            'user_type' => $entity['type'],
        ]);

        // Dispatch OTP via WhatsApp
        $this->service->send(
            'otp_verification',
            $entity['type'],
            $entity['id'],
            [
                'name' => $entity['name'],
                'code' => $otp,
                'action_url' => url('/'),
            ],
            $phone,
            "otp_verify_{$phone}_" . time()
        );

        return response()->json([
            'success' => true,
            'message' => "Verification code sent to WhatsApp number {$phone}.",
        ]);
    }

    /**
     * Verify the entered OTP
     */
    public function verifyOtp(Request $request)
    {
        $entity = $this->resolveCurrentAuth();
        if (!$entity) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'otp' => 'required|string|size:6',
            'whatsapp_number' => 'required|string',
        ]);

        $phone = trim($request->input('whatsapp_number'));
        $otp = trim($request->input('otp'));

        $record = LoginOtp::where('identifier', $phone)
            ->where('otp_code', $otp)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if (!$record) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired verification code.'], 400);
        }

        $record->is_used = true;
        $record->used_at = Carbon::now();
        $record->save();

        $pref = UserWhatsAppPreference::getPreferenceFor($entity['type'], $entity['id'], $phone);
        $pref->whatsapp_number = $phone;
        $pref->is_verified = true;
        $pref->verified_at = Carbon::now();
        $pref->save();

        return response()->json([
            'success' => true,
            'message' => '✓ WhatsApp number verified successfully!',
            'preferences' => $pref,
        ]);
    }

    /**
     * Helper to resolve current authenticated user or company
     */
    protected function resolveCurrentAuth(): ?array
    {
        if (Auth::guard('company')->check()) {
            $c = Auth::guard('company')->user();
            return [
                'type' => 'company',
                'id' => $c->id,
                'name' => $c->name,
                'phone' => $c->whatsapp_number ?: $c->phone,
            ];
        }

        if (Auth::check()) {
            $u = Auth::user();
            return [
                'type' => 'user',
                'id' => $u->id,
                'name' => $u->getName(),
                'phone' => $u->mobile_num ?: $u->phone,
            ];
        }

        return null;
    }
}
