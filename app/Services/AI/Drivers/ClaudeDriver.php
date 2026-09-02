<?php

namespace App\Services\AI\Drivers;

use App\Services\AI\Contracts\AIDriverInterface;
use App\AIProvider;
use Exception;

class ClaudeDriver implements AIDriverInterface
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
        $this->model = $provider->model ?: 'claude-3-5-haiku-20241022';
        $this->baseUrl = rtrim($provider->base_url ?: 'https://api.anthropic.com/v1', '/');
        $this->timeout = max(45, (int)($provider->timeout_sec ?: 45));
    }

    public function generateText(string $prompt, array $options = []): array
    {
        $url = "{$this->baseUrl}/messages";

        $payload = [
            'model' => $this->model,
            'max_tokens' => $options['max_tokens'] ?? 2048,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
        ];

        if (!empty($options['system_instruction'])) {
            $payload['system'] = $options['system_instruction'];
        }

        $startTime = microtime(true);
        $response = $this->sendHttpRequest($url, $payload);
        $responseTimeMs = (int) round((microtime(true) - $startTime) * 1000);

        if (isset($response['error'])) {
            throw new Exception($response['error']['message'] ?? 'Claude API returned an error.');
        }

        $text = '';
        if (isset($response['content']) && is_array($response['content'])) {
            foreach ($response['content'] as $block) {
                if (($block['type'] ?? '') === 'text') {
                    $text .= $block['text'];
                }
            }
        }

        $inputTokens = $response['usage']['input_tokens'] ?? (int) ceil(strlen($prompt) / 4);
        $outputTokens = $response['usage']['output_tokens'] ?? (int) ceil(strlen($text) / 4);
        $totalTokens = $inputTokens + $outputTokens;

        return [
            'text' => trim($text),
            'input_tokens' => $inputTokens,
            'output_tokens' => $outputTokens,
            'total_tokens' => $totalTokens,
            'response_time_ms' => $responseTimeMs,
            'raw' => $response,
        ];
    }

    public function testConnection(): array
    {
        $startTime = microtime(true);
        try {
            $result = $this->generateText('Respond with "OK" in exactly one word.', ['max_tokens' => 10]);
            $elapsedMs = (int) round((microtime(true) - $startTime) * 1000);
            return [
                'success' => true,
                'response_time_ms' => $elapsedMs,
                'message' => 'Connection Successful. Model: ' . $this->model . ' (' . $elapsedMs . 'ms)',
                'model' => $this->model,
                'sample_output' => $result['text'] ?? '',
            ];
        } catch (Exception $e) {
            $elapsedMs = (int) round((microtime(true) - $startTime) * 1000);
            return [
                'success' => false,
                'response_time_ms' => $elapsedMs,
                'message' => $e->getMessage(),
                'model' => $this->model,
            ];
        }
    }

    protected function sendHttpRequest(string $url, array $payload): array
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'x-api-key: ' . $this->apiKey,
            'anthropic-version: 2023-06-01',
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout ?: 45);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            throw new Exception("cURL Error: " . $curlError);
        }

        $decoded = json_decode($result, true);
        if ($httpCode >= 400) {
            $msg = $decoded['error']['message'] ?? "HTTP error {$httpCode}";
            throw new Exception($msg);
        }

        return $decoded ?: [];
    }
}
