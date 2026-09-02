<?php

namespace App\Services\AI\Drivers;

use App\Services\AI\Contracts\AIDriverInterface;
use App\AIProvider;
use Exception;

class GeminiDriver implements AIDriverInterface
{
    protected $provider;
    protected $apiKey;
    protected $model;
    protected $baseUrl;
    protected $timeout;

    public function __construct(AIProvider $provider)
    {
        $this->provider = $provider;
        $this->apiKey = $provider->getDecryptedApiKey();
        $this->model = $provider->model ?: 'gemini-1.5-flash';
        $this->baseUrl = rtrim($provider->base_url ?: 'https://generativelanguage.googleapis.com', '/');
        // Default 30s for text, but vision/image calls may override to 120s
        $this->timeout = $provider->timeout_sec ?: 30;
    }

    public function generateText(string $prompt, array $options = []): array
    {
        $url = "{$this->baseUrl}/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";

        $parts = [
            ['text' => $prompt]
        ];

        // Multimodal Image / Document vision support
        if (!empty($options['images']) && is_array($options['images'])) {
            foreach ($options['images'] as $img) {
                if (!empty($img['data']) && !empty($img['mime_type'])) {
                    $parts[] = [
                        'inline_data' => [
                            'mime_type' => $img['mime_type'],
                            'data' => $img['data']
                        ]
                    ];
                }
            }
        }

        $generationConfig = [
            'temperature' => $options['temperature'] ?? 0.7,
            'maxOutputTokens' => $options['max_tokens'] ?? 2048,
        ];

        if (!empty($options['response_mime_type'])) {
            $generationConfig['responseMimeType'] = $options['response_mime_type'];
        }

        $payload = [
            'contents' => [
                [
                    'parts' => $parts
                ]
            ],
            'generationConfig' => $generationConfig
        ];

        if (!empty($options['system_instruction'])) {
            $payload['system_instruction'] = [
                'parts' => [
                    ['text' => $options['system_instruction']]
                ]
            ];
        }

        $startTime = microtime(true);

        // Vision/image calls get extra time — override timeout if images present
        $effectiveTimeout = !empty($options['images']) ? max($this->timeout ?: 45, 120) : ($this->timeout ?: 45);

        // Also raise PHP execution time for vision calls
        if (!empty($options['images'])) {
            @set_time_limit(150);
        }

        $response = $this->sendHttpRequest($url, $payload, $effectiveTimeout);
        $responseTimeMs = (int) round((microtime(true) - $startTime) * 1000);

        if (isset($response['error'])) {
            throw new Exception($response['error']['message'] ?? 'Gemini API returned an error.');
        }

        $text = $response['candidates'][0]['content']['parts'][0]['text'] ?? '';
        $usageMetadata = $response['usageMetadata'] ?? [];

        $inputTokens = $usageMetadata['promptTokenCount'] ?? $this->estimateTokens($prompt);
        $outputTokens = $usageMetadata['candidatesTokenCount'] ?? $this->estimateTokens($text);

        return [
            'text' => $text,
            'input_tokens' => $inputTokens,
            'output_tokens' => $outputTokens,
            'total_tokens' => $inputTokens + $outputTokens,
            'response_time_ms' => $responseTimeMs,
            'raw_response' => $response,
        ];
    }

    public function testConnection(): array
    {
        try {
            $result = $this->generateText("Ping: respond strictly with the word 'PONG'.", [
                'max_tokens' => 10,
                'temperature' => 0.1,
            ]);

            return [
                'success' => true,
                'latency_ms' => $result['response_time_ms'],
                'message' => 'Connection verified successfully. Received: ' . trim($result['text']),
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'latency_ms' => 0,
                'message' => 'Connection test failed: ' . $e->getMessage(),
            ];
        }
    }

    protected function sendHttpRequest(string $url, array $payload, int $timeout = null): array
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $actualTimeout = $timeout ?: ($this->timeout ?: 45);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_TIMEOUT, $actualTimeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $responseBody = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            throw new Exception("cURL Error: {$curlError}");
        }

        $decoded = json_decode($responseBody, true);
        if ($httpCode >= 400) {
            $errMsg = $decoded['error']['message'] ?? "HTTP {$httpCode}: {$responseBody}";
            throw new Exception("Gemini API Error: {$errMsg}");
        }

        return is_array($decoded) ? $decoded : ['raw' => $responseBody];
    }

    protected function estimateTokens(string $text): int
    {
        return (int) ceil(mb_strlen($text) / 4);
    }
}
