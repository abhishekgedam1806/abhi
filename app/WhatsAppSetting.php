<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Exception;

class WhatsAppSetting extends Model
{
    protected $table = 'whatsapp_settings';

    protected $fillable = [
        'provider',
        'is_enabled',
        'test_mode',
        'phone_number_id',
        'business_account_id',
        'sender_number',
        'api_key',
        'api_secret',
        'api_endpoint',
        'webhook_verify_token',
        'daily_limit',
        'enable_candidate_notifications',
        'enable_employer_notifications',
        'enable_matching_alerts',
        'enable_application_alerts',
        'enable_status_alerts',
        'enable_message_alerts',
        'enable_payment_alerts',
        'last_tested_at',
        'last_test_status',
        'last_test_message',
        'settings',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'test_mode' => 'boolean',
        'enable_candidate_notifications' => 'boolean',
        'enable_employer_notifications' => 'boolean',
        'enable_matching_alerts' => 'boolean',
        'enable_application_alerts' => 'boolean',
        'enable_status_alerts' => 'boolean',
        'enable_message_alerts' => 'boolean',
        'enable_payment_alerts' => 'boolean',
        'last_tested_at' => 'datetime',
        'settings' => 'array',
    ];

    /**
     * Get or create singleton instance of WhatsApp settings
     */
    public static function getSettings(): self
    {
        $setting = self::first();
        if (!$setting) {
            $setting = self::create([
                'provider' => 'log',
                'is_enabled' => true,
                'test_mode' => false,
                'sender_number' => '+919999999999',
                'webhook_verify_token' => bin2hex(random_bytes(16)),
                'daily_limit' => 500,
                'enable_candidate_notifications' => true,
                'enable_employer_notifications' => true,
                'enable_matching_alerts' => true,
                'enable_application_alerts' => true,
                'enable_status_alerts' => true,
                'enable_message_alerts' => true,
                'enable_payment_alerts' => true,
            ]);
        }
        return $setting;
    }

    /**
     * Encrypt API Key when saving
     */
    public function setApiKeyAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['api_key'] = null;
            return;
        }

        try {
            // Check if already encrypted
            Crypt::decryptString($value);
            $this->attributes['api_key'] = $value;
        } catch (Exception $e) {
            $this->attributes['api_key'] = Crypt::encryptString($value);
        }
    }

    /**
     * Decrypt API Key
     */
    public function getDecryptedApiKey(): ?string
    {
        if (empty($this->api_key)) {
            return null;
        }

        try {
            return Crypt::decryptString($this->api_key);
        } catch (Exception $e) {
            return $this->api_key;
        }
    }

    /**
     * Encrypt API Secret
     */
    public function setApiSecretAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['api_secret'] = null;
            return;
        }

        try {
            Crypt::decryptString($value);
            $this->attributes['api_secret'] = $value;
        } catch (Exception $e) {
            $this->attributes['api_secret'] = Crypt::encryptString($value);
        }
    }

    /**
     * Decrypt API Secret
     */
    public function getDecryptedApiSecret(): ?string
    {
        if (empty($this->api_secret)) {
            return null;
        }

        try {
            return Crypt::decryptString($this->api_secret);
        } catch (Exception $e) {
            return $this->api_secret;
        }
    }
}
