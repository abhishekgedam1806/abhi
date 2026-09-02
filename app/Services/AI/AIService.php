<?php

namespace App\Services\AI;

use App\AIProvider;
use App\AIUsageLog;
use App\AICache;
use App\Services\AI\Contracts\AIDriverInterface;
use App\Services\AI\Drivers\GeminiDriver;
use App\Services\AI\Drivers\OpenAIDriver;
use App\Services\AI\Drivers\ClaudeDriver;
use App\Services\AI\Drivers\GrokDriver;
use App\Services\AI\Drivers\GLMDriver;
use App\Services\AI\Drivers\AzureOpenAIDriver;
use Carbon\Carbon;
use Exception;
use Log;

class AIService
{
    /**
     * USD to INR conversion rate for real cost calculations
     */
    const USD_TO_INR = 87.0;

    /**
     * Daily AI request safety quota to prevent runaway billing on Free/Tier 1 keys
     */
    const DEFAULT_DAILY_SAFETY_LIMIT = 500;

    /**
     * Get the current active/default provider
     *
     * @return AIProvider|null
     */
    public function getActiveProvider()
    {
        $provider = AIProvider::default()->first();
        if (!$provider) {
            $provider = AIProvider::active()->first();
        }
        return $provider;
    }

    /**
     * Get all active providers ordered by priority (default first, then active backups)
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailableProviders()
    {
        return AIProvider::where('is_active', 1)
            ->whereNotNull('api_key')
            ->orderBy('is_default', 'desc')
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * Resolve Driver instance from AIProvider
     *
     * @param AIProvider $provider
     * @return AIDriverInterface
     * @throws Exception
     */
    public function getDriver(AIProvider $provider): AIDriverInterface
    {
        switch ($provider->provider_type) {
            case 'gemini':
                return new GeminiDriver($provider);
            case 'openai':
                return new OpenAIDriver($provider);
            case 'claude':
                return new ClaudeDriver($provider);
            case 'grok':
                return new GrokDriver($provider);
            case 'glm':
                return new GLMDriver($provider);
            case 'azure_openai':
                return new AzureOpenAIDriver($provider);
            default:
                throw new Exception("Unsupported AI Provider type: {$provider->provider_type}");
        }
    }

    /**
     * Test a provider's connection and update its health status
     *
     * @param AIProvider $provider
     * @return array
     */
    public function testProvider(AIProvider $provider): array
    {
        $startTime = microtime(true);
        try {
            $driver = $this->getDriver($provider);
            $result = $driver->testConnection();

            $elapsedMs = $result['response_time_ms'] ?? (int) round((microtime(true) - $startTime) * 1000);

            if ($result['success']) {
                $provider->update([
                    'status' => 'active',
                    'last_tested_at' => now(),
                    'last_test_response_ms' => $elapsedMs,
                    'last_test_error' => null,
                ]);

                // Log test usage
                $this->logUsage([
                    'provider_id' => $provider->id,
                    'provider_type' => $provider->provider_type,
                    'model' => $provider->model,
                    'feature' => 'system_connection_test',
                    'feature_group' => 'system',
                    'input_tokens' => 10,
                    'output_tokens' => 2,
                    'total_tokens' => 12,
                    'response_time_ms' => $elapsedMs,
                    'estimated_cost_inr' => 0.0001,
                    'is_success' => 1,
                    'user_type' => 'admin',
                ]);
            } else {
                $provider->update([
                    'status' => 'connection_error',
                    'last_tested_at' => now(),
                    'last_test_response_ms' => $elapsedMs,
                    'last_test_error' => $result['message'] ?? 'Connection test failed',
                ]);

                $this->logUsage([
                    'provider_id' => $provider->id,
                    'provider_type' => $provider->provider_type,
                    'model' => $provider->model,
                    'feature' => 'system_connection_test',
                    'feature_group' => 'system',
                    'input_tokens' => 10,
                    'output_tokens' => 0,
                    'total_tokens' => 10,
                    'response_time_ms' => $elapsedMs,
                    'estimated_cost_inr' => 0.0,
                    'is_success' => 0,
                    'error_message' => $result['message'] ?? 'Connection test failed',
                    'user_type' => 'admin',
                ]);
            }

            return $result;
        } catch (Exception $e) {
            $elapsedMs = (int) round((microtime(true) - $startTime) * 1000);
            $provider->update([
                'status' => 'config_error',
                'last_tested_at' => now(),
                'last_test_response_ms' => $elapsedMs,
                'last_test_error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'response_time_ms' => $elapsedMs,
                'message' => $e->getMessage(),
                'model' => $provider->model,
            ];
        }
    }

    /**
     * Unified generation method for all portal features with caching, daily safety limits & zero-downtime fallback
     *
     * @param string $prompt
     * @param array $options ['feature', 'feature_group', 'user_type', 'user_id', 'max_tokens', 'temperature', 'prompt_version', 'bypass_cache']
     * @return array ['success' => bool, 'text' => string, 'cost_inr' => float, 'tokens' => int, 'response_time_ms' => int, 'cached' => bool, 'error' => string|null]
     */
    public function generate(string $prompt, array $options = []): array
    {
        $feature = $options['feature'] ?? 'general_ai_task';
        $featureGroup = $options['feature_group'] ?? $this->resolveFeatureGroup($feature);
        $userType = $options['user_type'] ?? 'system';
        $userId = $options['user_id'] ?? null;
        $promptVersion = $options['prompt_version'] ?? 'v1';
        $bypassCache = $options['bypass_cache'] ?? false;

        $providers = $this->getAvailableProviders();

        if ($providers->isEmpty()) {
            return [
                'success' => false,
                'text' => '',
                'cost_inr' => 0.0,
                'tokens' => 0,
                'response_time_ms' => 0,
                'cached' => false,
                'error' => 'No active AI Provider configured in Admin Dashboard.',
            ];
        }

        // ==========================================
        // 1. AI CACHING LAYER (₹0 Cost & 0ms Latency)
        // ==========================================
        $primaryProvider = $providers->first();
        $cacheInput = $prompt . (!empty($options['images_hash']) ? '|' . $options['images_hash'] : '');
        $inputHash = AICache::generateHash($feature, $cacheInput, $promptVersion, $primaryProvider->model);

        if (!$bypassCache) {
            $cachedEntry = AICache::where('input_hash', $inputHash)->first();
            if ($cachedEntry) {
                // Cache Hit: Increment access count & update timestamp
                $cachedEntry->increment('hit_count');
                $cachedEntry->update(['last_accessed_at' => now()]);

                // Log cache hit at ₹0 cost
                $this->logUsage([
                    'provider_id' => $primaryProvider->id,
                    'provider_type' => $primaryProvider->provider_type,
                    'model' => $primaryProvider->model,
                    'feature' => $feature,
                    'feature_group' => $featureGroup,
                    'input_tokens' => $cachedEntry->input_tokens,
                    'output_tokens' => $cachedEntry->output_tokens,
                    'total_tokens' => $cachedEntry->input_tokens + $cachedEntry->output_tokens,
                    'response_time_ms' => 1,
                    'estimated_cost_inr' => 0.0,
                    'is_success' => 1,
                    'user_type' => $userType,
                    'user_id' => $userId,
                    'metadata' => json_encode(['cache_hit' => true, 'prompt_version' => $promptVersion]),
                ]);

                return [
                    'success' => true,
                    'text' => $cachedEntry->response_text,
                    'cost_inr' => 0.0,
                    'input_tokens' => $cachedEntry->input_tokens,
                    'output_tokens' => $cachedEntry->output_tokens,
                    'tokens' => $cachedEntry->input_tokens + $cachedEntry->output_tokens,
                    'response_time_ms' => 1,
                    'provider' => $primaryProvider->name,
                    'model' => $primaryProvider->model,
                    'cached' => true,
                    'error' => null,
                ];
            }
        }

        // ==========================================
        // 2. DAILY SAFETY LIMIT CHECK (Quota Guard)
        // ==========================================
        $todayCalls = AIUsageLog::where('created_at', '>=', Carbon::today()->startOfDay())->count();
        if ($todayCalls >= self::DEFAULT_DAILY_SAFETY_LIMIT) {
            Log::warning("AI Daily Safety Limit reached ({$todayCalls}/" . self::DEFAULT_DAILY_SAFETY_LIMIT . "). Using deterministic fallback.");
            return [
                'success' => false,
                'text' => '',
                'cost_inr' => 0.0,
                'tokens' => 0,
                'response_time_ms' => 0,
                'cached' => false,
                'limit_reached' => true,
                'error' => 'Daily AI safety limit reached. Fallback triggered.',
            ];
        }

        // ==========================================
        // 3. EXECUTE AI WITH SMART AUTO-FAILOVER
        // ==========================================
        $lastError = null;
        $lastElapsedMs = 0;
        $attemptCount = count($providers);

        foreach ($providers as $index => $provider) {
            $apiKey = $provider->getDecryptedApiKey();
            if (empty($apiKey)) {
                continue;
            }

            $startTime = microtime(true);
            try {
                $driver = $this->getDriver($provider);
                $response = $driver->generateText($prompt, $options);
                $elapsedMs = $response['response_time_ms'] ?? (int) round((microtime(true) - $startTime) * 1000);

                $inputTokens = $response['input_tokens'] ?? 0;
                $outputTokens = $response['output_tokens'] ?? 0;
                $totalTokens = $response['total_tokens'] ?? ($inputTokens + $outputTokens);

                $costInr = $this->calculateCostInr(
                    $provider->provider_type,
                    $provider->model,
                    $inputTokens,
                    $outputTokens
                );

                // Save into AICache so future identical calls cost ₹0
                if (!empty($response['text'])) {
                    try {
                        AICache::create([
                            'input_hash' => $inputHash,
                            'feature' => $feature,
                            'prompt_version' => $promptVersion,
                            'provider' => $provider->provider_type,
                            'model' => $provider->model,
                            'response_text' => $response['text'],
                            'input_tokens' => $inputTokens,
                            'output_tokens' => $outputTokens,
                            'hit_count' => 1,
                            'last_accessed_at' => now(),
                        ]);
                    } catch (Exception $ce) {
                        // Ignore duplicate key collision
                    }
                }

                // Log real usage with failover metadata if switched
                $meta = isset($options['metadata']) ? $options['metadata'] : [];
                if ($index > 0) {
                    $meta['auto_failover_triggered'] = true;
                    $meta['failover_from_index'] = 0;
                }

                $this->logUsage([
                    'provider_id' => $provider->id,
                    'provider_type' => $provider->provider_type,
                    'model' => $provider->model,
                    'feature' => $feature,
                    'feature_group' => $featureGroup,
                    'input_tokens' => $inputTokens,
                    'output_tokens' => $outputTokens,
                    'total_tokens' => $totalTokens,
                    'response_time_ms' => $elapsedMs,
                    'estimated_cost_inr' => $costInr,
                    'is_success' => 1,
                    'user_type' => $userType,
                    'user_id' => $userId,
                    'metadata' => !empty($meta) ? json_encode($meta) : null,
                ]);

                return [
                    'success' => true,
                    'text' => $response['text'] ?? '',
                    'cost_inr' => $costInr,
                    'input_tokens' => $inputTokens,
                    'output_tokens' => $outputTokens,
                    'tokens' => $totalTokens,
                    'response_time_ms' => $elapsedMs,
                    'provider' => $provider->name,
                    'model' => $provider->model,
                    'cached' => false,
                    'auto_failover' => ($index > 0),
                    'error' => null,
                ];
            } catch (Exception $e) {
                $lastElapsedMs = (int) round((microtime(true) - $startTime) * 1000);
                $lastError = $e->getMessage();

                // Log failure attempt
                $this->logUsage([
                    'provider_id' => $provider->id,
                    'provider_type' => $provider->provider_type,
                    'model' => $provider->model,
                    'feature' => $feature,
                    'feature_group' => $featureGroup,
                    'input_tokens' => (int) ceil(strlen($prompt) / 4),
                    'output_tokens' => 0,
                    'total_tokens' => (int) ceil(strlen($prompt) / 4),
                    'response_time_ms' => $lastElapsedMs,
                    'estimated_cost_inr' => 0.0,
                    'is_success' => 0,
                    'error_message' => $lastError,
                    'user_type' => $userType,
                    'user_id' => $userId,
                ]);

                // Classify error: Only failover for infrastructure, timeout, quota, or auth errors
                $isEligibleForFailover = $this->isFailoverEligibleError($e);

                if (!$isEligibleForFailover) {
                    Log::error("AI Provider [{$provider->name} ({$provider->model})] failed with non-failover client error: {$lastError}. Stopping provider chain.");
                    break; // Do not switch to paid backup providers on client-side / malformed request errors
                }

                Log::warning("AI Provider [{$provider->name} ({$provider->model})] failed: {$lastError}. " . ($index < $attemptCount - 1 ? "Auto-switching to next configured backup provider..." : "All AI providers exhausted."));

                // Continue to next available provider in loop!
            }
        }

        return [
            'success' => false,
            'text' => '',
            'cost_inr' => 0.0,
            'tokens' => 0,
            'response_time_ms' => $lastElapsedMs,
            'provider' => 'AI Engine',
            'model' => 'All providers exhausted',
            'cached' => false,
            'error' => $lastError ?? 'All configured AI providers failed to respond.',
        ];
    }

    /**
     * Determine if an error is eligible for auto-failover (Quota, Rate Limit, Timeout, Auth, Server Down)
     */
    protected function isFailoverEligibleError(Exception $e): bool
    {
        $msg = strtolower($e->getMessage());
        $failoverKeywords = [
            '429', 'quota', 'rate limit', 'resource exhausted', 'too many requests', 'overloaded',
            '500', '502', '503', '504', 'service unavailable', 'internal server error', 'bad gateway',
            'timeout', 'timed out', 'curl error 28', 'curl error 7', 'connection refused',
            'couldn\'t connect', 'failed to connect', 'unauthorized', '401', '403',
            'api_key_invalid', 'api key not valid', 'invalid api key', 'permission denied'
        ];

        foreach ($failoverKeywords as $kw) {
            if (strpos($msg, $kw) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Calculate cost in INR for input/output tokens based on model rate cards
     */
    public function calculateCostInr(string $providerType, string $model, int $inputTokens, int $outputTokens): float
    {
        // Pricing in USD per 1 Million Tokens: [input_per_1M, output_per_1M]
        $rateCards = [
            'gemini-1.5-flash' => [0.075, 0.30],
            'gemini-2.0-flash' => [0.10, 0.40],
            'gemini-1.5-pro' => [1.25, 5.00],
            'gemini-1.0-pro' => [0.50, 1.50],
            'gpt-4o-mini' => [0.15, 0.60],
            'gpt-4o' => [2.50, 10.00],
            'gpt-4-turbo' => [10.00, 30.00],
            'gpt-3.5-turbo' => [0.50, 1.50],
            'o3-mini' => [1.10, 4.40],
            'claude-3-5-haiku-20241022' => [0.80, 4.00],
            'claude-3-5-sonnet-20241022' => [3.00, 15.00],
            'claude-3-opus-20240229' => [15.00, 75.00],
            'grok-beta' => [5.00, 15.00],
            'grok-2-latest' => [2.00, 10.00],
            'glm-4-flash' => [0.01, 0.01],
            'glm-4-plus' => [1.00, 1.00],
        ];

        $rate = $rateCards[$model] ?? [0.20, 0.80];

        $inputCostUsd = ($inputTokens / 1000000.0) * $rate[0];
        $outputCostUsd = ($outputTokens / 1000000.0) * $rate[1];
        $totalCostUsd = $inputCostUsd + $outputCostUsd;

        $costInr = $totalCostUsd * self::USD_TO_INR;

        return (float) round($costInr, 4);
    }

    /**
     * Resolve feature group from feature name
     */
    protected function resolveFeatureGroup(string $feature): string
    {
        if (strpos($feature, 'candidate') === 0) {
            return 'candidate';
        }
        if (strpos($feature, 'employer') === 0) {
            return 'employer';
        }
        if (strpos($feature, 'automated') === 0) {
            return 'automated_jobs';
        }
        return 'system';
    }

    /**
     * Safely record an AI usage entry into the database
     */
    protected function logUsage(array $data)
    {
        try {
            AIUsageLog::create($data);
        } catch (Exception $e) {
            Log::warning("Failed to record AI Usage Log: " . $e->getMessage());
        }
    }
}
