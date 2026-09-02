<?php

namespace App\Services\WhatsApp\Contracts;

interface WhatsAppProviderInterface
{
    /**
     * Send a templated message to recipient
     *
     * @param string $to Recipient phone number in E.164 / international format
     * @param string $templateKey Template identifier (e.g. 'job_match', 'application_confirmation')
     * @param array $variables Associative key-value array of template variables
     * @param array $buttons Optional interactive quick-reply or CTA URL buttons
     * @return array Standardized result ['success' => bool, 'message_id' => ?string, 'error' => ?string, 'raw' => mixed]
     */
    public function sendTemplate(string $to, string $templateKey, array $variables = [], array $buttons = []): array;

    /**
     * Send a direct plain text message (Within 24-hour customer care window or development)
     *
     * @param string $to Recipient phone number
     * @param string $message Text content
     * @return array
     */
    public function sendDirectMessage(string $to, string $message): array;

    /**
     * Ping the provider API to test connectivity and credential validity
     *
     * @return array ['success' => bool, 'latency_ms' => int, 'message' => string]
     */
    public function testConnection(): array;

    /**
     * Parse and extract delivery / read receipts from provider webhook payloads
     *
     * @param array $payload Inbound webhook request JSON payload
     * @return array Standardized events array of [['message_id' => string, 'status' => 'sent'|'delivered'|'read'|'failed', 'timestamp' => Carbon, 'error' => ?string]]
     */
    public function parseWebhook(array $payload): array;
}
