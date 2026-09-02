<?php

namespace App\Services\AI\Drivers;

use App\Services\AI\Contracts\AIDriverInterface;
use App\AIProvider;
use Exception;

class OpenAIDriver implements AIDriverInterface
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
        $this->model = $provider->model ?: 'gpt-4o-mini';
        $this->baseUrl = rtrim($provider->base_url ?: 'https://api.openai.com/v1', '/');
        $this->timeout = $provider->timeout_sec ?: 30;
    }

    public function generateText(string $prompt, array $options = []): array
    {
        $url = "{$this->baseUrl}/chat/completions";

        $messages = [];
        if (!empty($options['system_instruction'])) {
            $messages[] = [
                'role' => 'system',
                'content' => $options['system_instruction'],
            ];
        }
        $messages[] = [
            'role' => 'user',
            'content' => $prompt,
        ];

        $payload = [
            'model' => $this->model,
            'messages' => $messages,
            'temperature' => $options['temperature'] ?? 0.7,
            'max_tokens' => $options['max_tokens'] ?? 2048,
        ];

        $startTime = microtime(true);
        $response = $this->sendHttpRequest($url, $payload);
        $responseTimeMs = (int) round((microtime(true) - $startTime) * 1000);

        if (isset($response['error'])) {
            throw new Exception($response['error']['message'] ?? 'OpenAI API returned an error.');
        }

        $text = $response['choices'][0]['message']['content'] ?? '';
        $inputTokens = $response['usage']['prompt_tokens'] ?? (int) ceil(strlen($prompt) / 4);
        $outputTokens = $response['usage']['completion_tokens'] ?? (int) ceil(strlen($text) / 4);
        $totalTokens = $response['usage']['total_tokens'] ?? ($inputTokens + $outputTokens);

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
            'Authorization: Bearer ' . $this->apiKey,
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
