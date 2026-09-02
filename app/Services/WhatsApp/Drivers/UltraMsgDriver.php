<?php

namespace App\Services\WhatsApp\Drivers;

use App\Services\WhatsApp\Contracts\WhatsAppProviderInterface;
use App\WhatsAppSetting;
use App\WhatsAppTemplate;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class UltraMsgDriver implements WhatsAppProviderInterface
{
    protected $setting;
    protected $instanceId;
    protected $token;

    public function __construct(WhatsAppSetting $setting)
    {
        $this->setting = $setting;
        $this->instanceId = $setting->phone_number_id; // UltraMsg Instance ID e.g. "instance12345"
        $this->token = $setting->getDecryptedApiKey();
    }

    public function sendTemplate(string $to, string $templateKey, array $variables = [], array $buttons = []): array
    {
        $template = WhatsAppTemplate::where('template_key', $templateKey)->first();
        $messageBody = $template ? $template->renderFullMessage($variables) : "Template [{$templateKey}]";

        return $this->sendDirectMessage($to, $messageBody);
    }

    public function sendDirectMessage(string $to, string $message): array
    {
        $cleanPhone = $this->sanitizePhone($to);
        $url = "https://api.ultramsg.com/{$this->instanceId}/messages/chat";

        $payload = [
            'token' => $this->token,
            'to' => $cleanPhone,
            'body' => $message,
            'priority' => 10,
        ];

        try {
            $response = $this->sendHttpRequest($url, $payload);
            $messageId = $response['id'] ?? null;

            return [
                'success' => !empty($messageId) && ($response['sent'] ?? '') === 'true',
                'message_id' => $messageId ? (string)$messageId : null,
                'rendered_message' => $message,
                'error' => $response['error'] ?? null,
                'raw' => $response,
            ];
        } catch (Exception $e) {
            Log::error("UltraMsg WhatsApp API Error: " . $e->getMessage());
            return [
                'success' => false,
                'message_id' => null,
                'rendered_message' => $message,
                'error' => $e->getMessage(),
                'raw' => null,
            ];
        }
    }

    public function testConnection(): array
    {
        if (empty($this->instanceId) || empty($this->token)) {
            return [
                'success' => false,
                'latency_ms' => 0,
                'message' => 'Missing UltraMsg Instance ID or Token in WhatsApp Settings.',
            ];
        }

        $url = "https://api.ultramsg.com/{$this->instanceId}/instance/status?token=" . urlencode($this->token);

        $startTime = microtime(true);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr = curl_error($ch);
        curl_close($ch);

        $latency = (int)round((microtime(true) - $startTime) * 1000);

        if ($curlErr) {
            return [
                'success' => false,
                'latency_ms' => $latency,
                'message' => "cURL Error: {$curlErr}",
            ];
        }

        $data = json_decode($response, true);
        if ($httpCode === 200 && !empty($data['status'])) {
            return [
                'success' => true,
                'latency_ms' => $latency,
                'message' => "UltraMsg Instance connected. Status: " . ($data['status']['account_status'] ?? 'Authenticated'),
            ];
        }

        return [
            'success' => false,
            'latency_ms' => $latency,
            'message' => "UltraMsg Error: HTTP {$httpCode} - " . ($data['error'] ?? $response),
        ];
    }

    public function parseWebhook(array $payload): array
    {
        $events = [];
        $data = $payload['data'] ?? [];
        $event = $payload['event_type'] ?? '';

        if ($event === 'message_ack' && !empty($data['id'])) {
            $ack = $data['ack'] ?? ''; // sent, delivered, read, server
            $status = 'sent';
            if ($ack === 'delivered') $status = 'delivered';
            if ($ack === 'read') $status = 'read';

            $events[] = [
                'message_id' => (string)$data['id'],
                'status' => $status,
                'timestamp' => Carbon::now(),
                'error' => null,
            ];
        }

        return $events;
    }

    protected function sanitizePhone(string $phone): string
    {
        $clean = preg_replace('/[^\d]/', '', $phone);
        if (strlen($clean) === 10) {
            $clean = '91' . $clean;
        }
        return $clean;
    }

    protected function sendHttpRequest(string $url, array $payload): array
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $responseBody = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr = curl_error($ch);
        curl_close($ch);

        if ($curlErr) {
            throw new Exception("cURL Error: {$curlErr}");
        }

        $decoded = json_decode($responseBody, true);
        if ($httpCode >= 400 || (isset($decoded['error']) && !empty($decoded['error']))) {
            $errMsg = $decoded['error'] ?? "HTTP {$httpCode}: {$responseBody}";
            throw new Exception("UltraMsg API Error: {$errMsg}");
        }

        return is_array($decoded) ? $decoded : ['raw' => $responseBody];
    }
}
