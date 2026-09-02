<?php

namespace App\Services\AI\Contracts;

interface AIDriverInterface
{
    /**
     * Generate text from prompt
     *
     * @param string $prompt
     * @param array $options
     * @return array ['text' => string, 'input_tokens' => int, 'output_tokens' => int, 'total_tokens' => int, 'raw' => mixed]
     */
    public function generateText(string $prompt, array $options = []): array;

    /**
     * Run a quick test connection
     *
     * @return array ['success' => bool, 'response_time_ms' => int, 'message' => string, 'model' => string]
     */
    public function testConnection(): array;
}
