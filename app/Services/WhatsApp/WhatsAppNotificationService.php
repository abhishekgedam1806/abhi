<?php

namespace App\Services\WhatsApp;

use App\WhatsAppSetting;
use App\WhatsAppTemplate;
use App\WhatsAppNotification;
use App\UserWhatsAppPreference;
use App\User;
use App\Company;
use App\Services\WhatsApp\Contracts\WhatsAppProviderInterface;
use App\Services\WhatsApp\Drivers\LogDriver;
use App\Services\WhatsApp\Drivers\MetaCloudDriver;
use App\Services\WhatsApp\Drivers\GupshupDriver;
use App\Services\WhatsApp\Drivers\TwilioWhatsAppDriver;
use App\Services\WhatsApp\Drivers\UltraMsgDriver;
use App\Jobs\SendWhatsAppNotificationJob;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class WhatsAppNotificationService
{
    protected $setting;

    public function __construct()
    {
        $this->setting = WhatsAppSetting::getSettings();
    }

    /**
     * Get active driver instance based on configuration
     */
    public function getDriver(): WhatsAppProviderInterface
    {
        $provider = $this->setting->provider ?: 'log';

        switch (strtolower($provider)) {
            case 'meta':
                return new MetaCloudDriver($this->setting);
            case 'gupshup':
                return new GupshupDriver($this->setting);
            case 'twilio':
                return new TwilioWhatsAppDriver($this->setting);
            case 'ultramsg':
                return new UltraMsgDriver($this->setting);
            case 'log':
            default:
                return new LogDriver($this->setting);
        }
    }

    /**
     * Safe Dispatch entrypoint for any WhatsApp notification
     *
     * @param string $eventType e.g. 'job_match', 'application_confirmation', 'job_posted', 'candidate_match'
     * @param string $notifiableType 'user' or 'company'
     * @param int $notifiableId ID of user or company
     * @param array $variables Key-value data to bind into template
     * @param string|null $customPhone Override phone number (optional)
     * @param string|null $idempotencyKey Unique hash to prevent duplicate messages
     * @return WhatsAppNotification|null
     */
    public function send(
        string $eventType,
        string $notifiableType,
        int $notifiableId,
        array $variables = [],
        ?string $customPhone = null,
        ?string $idempotencyKey = null
    ): ?WhatsAppNotification {
        try {
            // 1. Global Master Switch Check
            if (!$this->setting->is_enabled) {
                return null;
            }

            // 2. Feature-specific switches check
            if (!$this->isFeatureEnabled($eventType, $notifiableType)) {
                return null;
            }

            // 3. Resolve Recipient Phone Number
            $recipientPhone = $customPhone ?: $this->resolveRecipientPhone($notifiableType, $notifiableId);
            if (empty($recipientPhone)) {
                return null; // No phone number available
            }

            // 4. User / Company Preference Opt-In & Verification Check
            $preference = UserWhatsAppPreference::getPreferenceFor($notifiableType, $notifiableId, $recipientPhone);
            if (!$preference->isEventAllowed($eventType)) {
                return null; // User explicitly disabled this category
            }

            // 5. Idempotency / Deduplication Check
            if ($idempotencyKey) {
                $existing = WhatsAppNotification::where('idempotency_key', $idempotencyKey)
                    ->where('created_at', '>=', Carbon::now()->subHours(24))
                    ->first();

                if ($existing) {
                    return $existing; // Skip duplicate notification at ₹0 cost
                }
            }

            // 6. Resolve Template
            $templateKey = $eventType;
            $template = WhatsAppTemplate::where('template_key', $templateKey)->where('is_active', true)->first();
            $renderedMessage = $template ? $template->renderFullMessage($variables) : "Notification: " . json_encode($variables);

            // 7. Create Audit Log Record in DB (Status: queued)
            $notification = WhatsAppNotification::create([
                'notifiable_type' => $notifiableType,
                'notifiable_id' => $notifiableId,
                'recipient_phone' => $recipientPhone,
                'event_type' => $eventType,
                'template_key' => $templateKey,
                'idempotency_key' => $idempotencyKey,
                'provider' => $this->setting->provider ?: 'log',
                'status' => 'queued',
                'payload' => $variables,
                'rendered_message' => $renderedMessage,
                'attempts' => 0,
                'max_attempts' => 3,
            ]);

            // 8. Dispatch Async Queue Job
            dispatch(new SendWhatsAppNotificationJob($notification->id));

            return $notification;
        } catch (Exception $e) {
            // NEVER let notification failures break the core application
            Log::warning("WhatsApp Notification Service error for {$eventType}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if specific notification category is enabled in admin settings
     */
    protected function isFeatureEnabled(string $eventType, string $notifiableType): bool
    {
        if ($notifiableType === 'user' && !$this->setting->enable_candidate_notifications) {
            return false;
        }

        if ($notifiableType === 'company' && !$this->setting->enable_employer_notifications) {
            return false;
        }

        switch ($eventType) {
            case 'job_match':
            case 'candidate_match':
                return (bool)$this->setting->enable_matching_alerts;
            case 'application_confirmation':
            case 'job_applied':
                return (bool)$this->setting->enable_application_alerts;
            case 'job_published':
            case 'job_rejected':
            case 'job_posted':
            case 'application_status':
                return (bool)$this->setting->enable_status_alerts;
            case 'new_message':
            case 'contact_approved':
                return (bool)$this->setting->enable_message_alerts;
            case 'payment_confirmation':
            case 'package_expiry':
            case 'package_purchased':
                return (bool)$this->setting->enable_payment_alerts;
            default:
                return true;
        }
    }

    /**
     * Resolve recipient phone number from database models
     */
    public function resolveRecipientPhone(string $notifiableType, int $notifiableId): ?string
    {
        if ($notifiableType === 'user') {
            $user = User::find($notifiableId);
            if ($user) {
                // Check preferences table first, then user's mobile_num/phone
                $pref = UserWhatsAppPreference::where('notifiable_type', 'user')->where('notifiable_id', $user->id)->first();
                if ($pref && !empty($pref->whatsapp_number)) {
                    return $pref->whatsapp_number;
                }
                return $user->mobile_num ?: $user->phone;
            }
        } elseif ($notifiableType === 'company') {
            $company = Company::find($notifiableId);
            if ($company) {
                $pref = UserWhatsAppPreference::where('notifiable_type', 'company')->where('notifiable_id', $company->id)->first();
                if ($pref && !empty($pref->whatsapp_number)) {
                    return $pref->whatsapp_number;
                }
                return $company->whatsapp_number ?: $company->phone;
            }
        }

        return null;
    }
}
