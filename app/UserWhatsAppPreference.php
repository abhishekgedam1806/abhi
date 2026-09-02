<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserWhatsAppPreference extends Model
{
    protected $table = 'user_whatsapp_preferences';

    protected $fillable = [
        'notifiable_type',
        'notifiable_id',
        'whatsapp_number',
        'is_verified',
        'verified_at',
        'allow_matching_jobs',
        'allow_application_updates',
        'allow_messages',
        'allow_job_status',
        'allow_candidate_matches',
        'allow_account_payments',
        'allow_promotional',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'allow_matching_jobs' => 'boolean',
        'allow_application_updates' => 'boolean',
        'allow_messages' => 'boolean',
        'allow_job_status' => 'boolean',
        'allow_candidate_matches' => 'boolean',
        'allow_account_payments' => 'boolean',
        'allow_promotional' => 'boolean',
    ];

    /**
     * Get or create preference record for an entity
     */
    public static function getPreferenceFor(string $notifiableType, int $notifiableId, ?string $fallbackPhone = null): self
    {
        $pref = self::where('notifiable_type', $notifiableType)
                    ->where('notifiable_id', $notifiableId)
                    ->first();

        if (!$pref) {
            $pref = self::create([
                'notifiable_type' => $notifiableType,
                'notifiable_id' => $notifiableId,
                'whatsapp_number' => $fallbackPhone,
                'is_verified' => false,
                'allow_matching_jobs' => true,
                'allow_application_updates' => true,
                'allow_messages' => true,
                'allow_job_status' => true,
                'allow_candidate_matches' => true,
                'allow_account_payments' => true,
                'allow_promotional' => false,
            ]);
        }

        return $pref;
    }

    /**
     * Check if a specific event type is allowed by user's preference settings
     */
    public function isEventAllowed(string $eventType): bool
    {
        switch ($eventType) {
            case 'job_match':
                return (bool)$this->allow_matching_jobs;
            case 'application_confirmation':
            case 'application_status':
            case 'job_applied':
                return (bool)$this->allow_application_updates;
            case 'job_published':
            case 'job_rejected':
            case 'job_posted':
                return (bool)$this->allow_job_status;
            case 'candidate_match':
                return (bool)$this->allow_candidate_matches;
            case 'new_message':
            case 'contact_approved':
                return (bool)$this->allow_messages;
            case 'payment_confirmation':
            case 'package_expiry':
            case 'package_purchased':
                return (bool)$this->allow_account_payments;
            case 'promotional':
                return (bool)$this->allow_promotional;
            default:
                return true;
        }
    }
}
