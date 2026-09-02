<?php

namespace App\Services\WhatsApp\Drivers;

use App\Services\WhatsApp\Contracts\WhatsAppProviderInterface;
use App\WhatsAppSetting;
use App\WhatsAppTemplate;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class GupshupDriver implements WhatsAppProviderInterface
{
    protected $setting;
    protected $apiKey;
    protected $appName;
    protected $sourceNumber;

    public function __construct(WhatsAppSetting $setting)
    {
        $this->setting = $setting;
        $this->apiKey = $setting->getDecryptedApiKey();
        $this->appName = $setting->business_account_id;
        $this->sourceNumber = $setting->sender_number;
    }

    public function sendTemplate(string $to, string $templateKey, array $variables = [], array $buttons = []): array
    {
        $template = WhatsAppTemplate::where('template_key', $templateKey)->first();
        $cleanPhone = $this->sanitizePhone($to);
        $cleanSource = $this->sanitizePhone($this->sourceNumber ?: '919999999999');

        $templateId = $template && !empty($template->provider_template_name)
            ? $template->provider_template_name
            : $templateKey;

        // Gupshup template message payload
        $templateData = [
            'id' => $templateId,
            'params' => array_values($variables),
        ];

        $payload = [
            'channel' => 'whatsapp',
            'source' => $cleanSource,
            'destination' => $cleanPhone,
            'src.name' => $this->appName,
            'template' => json_encode($templateData),
        ];

        $url = 'https://api.gupshup.io/wa/api/v1/template/msg';

        try {
            $response = $this->sendHttpRequest($url, $payload);
            $messageId = $response['messageId'] ?? null;
            $rendered = $template ? $template->renderFullMessage($variables) : "Template: {$templateKey}";

            return [
                'success' => !empty($messageId) && ($response['status'] ?? '') !== 'error',
                'message_id' => $messageId,
                'rendered_message' => $rendered,
                'error' => null,
                'raw' => $response,
            ];
        } catch (Exception $e) {
            Log::error("Gupshup WhatsApp API Error: " . $e->getMessage());
            return [
                'success' => false,
                'message_id' => null,
                'rendered_message' => null,
                'error' => $e->getMessage(),
                'raw' => null,
            ];
        }
    }

    public function sendDirectMessage(string $to, string $message): array
    {
        $cleanPhone = $this->sanitizePhone($to);
        $cleanSource = $this->sanitizePhone($this->sourceNumber ?: '919999999999');

        $payload = [
            'channel' => 'whatsapp',
            'source' => $cleanSource,
            'destination' => $cleanPhone,
            'src.name' => $this->appName,
            'message' => json_encode([
                'type' => 'text',
                'text' => $message,
            ]),
        ];

        $url = 'https://api.gupshup.io/wa/api/v1/msg';

        try {
            $response = $this->sendHttpRequest($url, $payload);
            $messageId = $response['messageId'] ?? null;

            return [
                'success' => !empty($messageId),
                'message_id' => $messageId,
                'rendered_message' => $message,
                'error' => null,
                'raw' => $response,
            ];
        } catch (Exception $e) {
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
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'latency_ms' => 0,
                'message' => 'Missing Gupshup API Key in WhatsApp Settings.',
            ];
        }

        $url = 'https://api.gupshup.io/wa/api/v1/users/list';

        $startTime = microtime(true);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'apikey: ' . $this->apiKey,
        ]);

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

        if ($httpCode === 200) {
            return [
                'success' => true,
                'latency_ms' => $latency,
                'message' => 'Gupshup API connectivity verified successfully.',
            ];
        }

        return [
            'success' => false,
            'latency_ms' => $latency,
            'message' => "Gupshup API responded with HTTP {$httpCode}: {$response}",
        ];
    }

    public function parseWebhook(array $payload): array
    {
        $events = [];

        if (!empty($payload['type']) && in_array($payload['type'], ['message-event', 'user-event'])) {
            $payloadType = $payload['payload']['type'] ?? '';
            $msgId = $payload['payload']['id'] ?? ($payload['payload']['gsId'] ?? null);

            $status = null;
            if ($payloadType === 'delivered') $status = 'delivered';
            elseif ($payloadType === 'read') $status = 'read';
            elseif ($payloadType === 'enqueued' || $payloadType === 'sent') $status = 'sent';
            elseif ($payloadType === 'failed') $status = 'failed';

            if ($msgId && $status) {
                $events[] = [
                    'message_id' => $msgId,
                    'status' => $status,
                    'timestamp' => Carbon::now(),
                    'error' => $payload['payload']['error'] ?? null,
                ];
            }
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
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'apikey: ' . $this->apiKey,
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: application/json',
        ]);

        $responseBody = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr = curl_error($ch);
        curl_close($ch);

        if ($curlErr) {
            throw new Exception("cURL Error: {$curlErr}");
        }

        $decoded = json_decode($responseBody, true);
        if ($httpCode >= 400 || (isset($decoded['status']) && $decoded['status'] === 'error')) {
            $errMsg = $decoded['message'] ?? "HTTP {$httpCode}: {$responseBody}";
            throw new Exception("Gupshup API Error: {$errMsg}");
        }

        return is_array($decoded) ? $decoded : ['raw' => $responseBody];
    }
}
