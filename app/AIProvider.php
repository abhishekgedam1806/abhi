<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Exception;

class AIProvider extends Model
{
    protected $table = 'ai_providers';

    protected $fillable = [
        'name',
        'provider_type',
        'model',
        'api_key',
        'base_url',
        'timeout_sec',
        'is_active',
        'is_default',
        'status',
        'last_tested_at',
        'last_test_response_ms',
        'last_test_error',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'last_tested_at' => 'datetime',
        'timeout_sec' => 'integer',
        'last_test_response_ms' => 'integer',
    ];

    /**
     * Provider Types Definition
     */
    public static function getSupportedProviders()
    {
        return [
            'gemini' => [
                'name' => 'Google Gemini',
                'models' => [
                    'gemini-1.5-flash' => 'Gemini 1.5 Flash (Fast, Low Cost / Free Tier)',
                    'gemini-1.5-pro' => 'Gemini 1.5 Pro (High Intelligence, Deep Context)',
                    'gemini-2.0-flash' => 'Gemini 2.0 Flash (Next-Gen High Speed)',
                    'gemini-1.0-pro' => 'Gemini 1.0 Pro',
                ],
                'default_model' => 'gemini-1.5-flash',
                'default_timeout' => 30,
                'icon' => 'fa-google',
                'color' => '#1A73E8',
            ],
            'openai' => [
                'name' => 'OpenAI',
                'models' => [
                    'gpt-4o-mini' => 'GPT-4o Mini (Fast & Cost Effective)',
                    'gpt-4o' => 'GPT-4o (Omni Flagship Model)',
                    'gpt-4-turbo' => 'GPT-4 Turbo',
                    'gpt-3.5-turbo' => 'GPT-3.5 Turbo',
                    'o3-mini' => 'o3-mini (Reasoning Model)',
                ],
                'default_model' => 'gpt-4o-mini',
                'default_timeout' => 30,
                'icon' => 'fa-cube',
                'color' => '#10A37F',
            ],
            'azure_openai' => [
                'name' => 'Azure OpenAI',
                'models' => [
                    'gpt-4o' => 'Azure GPT-4o Deployment',
                    'gpt-4o-mini' => 'Azure GPT-4o-mini Deployment',
                    'gpt-35-turbo' => 'Azure GPT-3.5 Deployment',
                ],
                'default_model' => 'gpt-4o-mini',
                'default_timeout' => 45,
                'icon' => 'fa-windows',
                'color' => '#0078D4',
            ],
            'claude' => [
                'name' => 'Anthropic Claude',
                'models' => [
                    'claude-3-5-sonnet-20241022' => 'Claude 3.5 Sonnet (State-of-the-art)',
                    'claude-3-5-haiku-20241022' => 'Claude 3.5 Haiku (Lightning Fast)',
                    'claude-3-opus-20240229' => 'Claude 3 Opus (Deep Reasoning)',
                ],
                'default_model' => 'claude-3-5-haiku-20241022',
                'default_timeout' => 35,
                'icon' => 'fa-bolt',
                'color' => '#D97706',
            ],
            'grok' => [
                'name' => 'xAI Grok',
                'models' => [
                    'grok-beta' => 'Grok Beta (xAI Frontier)',
                    'grok-2-latest' => 'Grok 2 Latest',
                    'grok-2-vision-latest' => 'Grok 2 Vision',
                ],
                'default_model' => 'grok-beta',
                'default_timeout' => 30,
                'icon' => 'fa-rocket',
                'color' => '#000000',
            ],
            'glm' => [
                'name' => 'Zhipu GLM',
                'models' => [
                    'glm-4-flash' => 'GLM-4 Flash (Ultra Fast & Free/Cheap)',
                    'glm-4-plus' => 'GLM-4 Plus (Flagship)',
                    'glm-4-air' => 'GLM-4 Air (Balanced)',
                ],
                'default_model' => 'glm-4-flash',
                'default_timeout' => 30,
                'icon' => 'fa-shield',
                'color' => '#7C3AED',
            ],
        ];
    }

    /**
     * Mutator to encrypt API key on set
     */
    public function setApiKeyAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['api_key'] = Crypt::encryptString(trim($value));
        }
    }

    /**
     * Accessor to decrypt API key on get
     */
    public function getDecryptedApiKey()
    {
        try {
            return !empty($this->attributes['api_key']) ? Crypt::decryptString($this->attributes['api_key']) : '';
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * Masked API key for safe UI display (e.g. sk-...a1B2)
     */
    public function getMaskedApiKeyAttribute()
    {
        $raw = $this->getDecryptedApiKey();
        if (empty($raw)) {
            return '••••••••••••••••';
        }
        $len = strlen($raw);
        if ($len <= 8) {
            return '••••••••';
        }
        $start = substr($raw, 0, 4);
        $end = substr($raw, -4);
        return $start . '••••••••••••' . $end;
    }

    /**
     * Scope for active providers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Scope for default active provider
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', 1)->where('is_active', 1);
    }

    /**
     * Get Provider Info details
     */
    public function getProviderInfoAttribute()
    {
        $providers = self::getSupportedProviders();
        return $providers[$this->provider_type] ?? [
            'name' => ucfirst($this->provider_type),
            'models' => [],
            'icon' => 'fa-microchip',
            'color' => '#2563EB',
        ];
    }

    /**
     * Relationship to usage logs
     */
    public function usageLogs()
    {
        return $this->hasMany(AIUsageLog::class, 'provider_id');
    }
}
