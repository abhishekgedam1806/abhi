<?php

namespace App\Services\WhatsApp\Drivers;

use App\Services\WhatsApp\Contracts\WhatsAppProviderInterface;
use App\WhatsAppSetting;
use App\WhatsAppTemplate;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class MetaCloudDriver implements WhatsAppProviderInterface
{
    protected $setting;
    protected $token;
    protected $phoneNumberId;
    protected $apiVersion = 'v20.0';

    public function __construct(WhatsAppSetting $setting)
    {
        $this->setting = $setting;
        $this->token = $setting->getDecryptedApiKey();
        $this->phoneNumberId = $setting->phone_number_id;
    }

    public function sendTemplate(string $to, string $templateKey, array $variables = [], array $buttons = []): array
    {
        $template = WhatsAppTemplate::where('template_key', $templateKey)->first();
        $cleanPhone = $this->sanitizePhone($to);

        $providerTemplateName = $template && !empty($template->provider_template_name)
            ? $template->provider_template_name
            : $templateKey;

        $languageCode = $template && !empty($template->language) ? $template->language : 'en';

        // Prepare component body parameters
        $bodyParams = [];
        foreach ($variables as $key => $val) {
            $bodyParams[] = [
                'type' => 'text',
                'text' => (string)$val,
            ];
        }

        $components = [];
        if (!empty($bodyParams)) {
            $components[] = [
                'type' => 'body',
                'parameters' => $bodyParams,
            ];
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $cleanPhone,
            'type' => 'template',
            'template' => [
                'name' => $providerTemplateName,
                'language' => [
                    'code' => $languageCode,
                ],
            ],
        ];

        if (!empty($components)) {
            $payload['template']['components'] = $components;
        }

        $url = "https://graph.facebook.com/{$this->apiVersion}/{$this->phoneNumberId}/messages";

        try {
            $response = $this->sendHttpRequest($url, $payload);

            $messageId = $response['messages'][0]['id'] ?? null;
            $rendered = $template ? $template->renderFullMessage($variables) : "Template: {$templateKey}";

            return [
                'success' => !empty($messageId),
                'message_id' => $messageId,
                'rendered_message' => $rendered,
                'error' => null,
                'raw' => $response,
            ];
        } catch (Exception $e) {
            Log::error("Meta WhatsApp Cloud API Error: " . $e->getMessage(), ['payload' => $payload]);
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

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $cleanPhone,
            'type' => 'text',
            'text' => [
                'preview_url' => false,
                'body' => $message,
            ],
        ];

        $url = "https://graph.facebook.com/{$this->apiVersion}/{$this->phoneNumberId}/messages";

        try {
            $response = $this->sendHttpRequest($url, $payload);
            $messageId = $response['messages'][0]['id'] ?? null;

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
        if (empty($this->token) || empty($this->phoneNumberId)) {
            return [
                'success' => false,
                'latency_ms' => 0,
                'message' => 'Missing Phone Number ID or Access Token in WhatsApp Settings.',
            ];
        }

        $url = "https://graph.facebook.com/{$this->apiVersion}/{$this->phoneNumberId}?access_token=" . urlencode($this->token);

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
        if ($httpCode >= 400 || !empty($data['error'])) {
            $errMsg = $data['error']['message'] ?? "HTTP {$httpCode}: {$response}";
            return [
                'success' => false,
                'latency_ms' => $latency,
                'message' => "Meta API Error: {$errMsg}",
            ];
        }

        $verifiedName = $data['verified_name'] ?? ($data['display_phone_number'] ?? 'WhatsApp Phone ID Verified');
        return [
            'success' => true,
            'latency_ms' => $latency,
            'message' => "Successfully connected to Meta WhatsApp Cloud API ({$verifiedName}).",
        ];
    }

    public function parseWebhook(array $payload): array
    {
        $events = [];

        if (!empty($payload['entry'])) {
            foreach ($payload['entry'] as $entry) {
                if (!empty($entry['changes'])) {
                    foreach ($entry['changes'] as $change) {
                        $value = $change['value'] ?? [];
                        if (!empty($value['statuses'])) {
                            foreach ($value['statuses'] as $st) {
                                $messageId = $st['id'] ?? null;
                                $status = $st['status'] ?? null; // sent, delivered, read, failed
                                $timestamp = isset($st['timestamp']) ? Carbon::createFromTimestamp($st['timestamp']) : Carbon::now();
                                $err = !empty($st['errors']) ? json_encode($st['errors']) : null;

                                if ($messageId && $status) {
                                    $events[] = [
                                        'message_id' => $messageId,
                                        'status' => $status,
                                        'timestamp' => $timestamp,
                                        'error' => $err,
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }

        return $events;
    }

    protected function sanitizePhone(string $phone): string
    {
        // Strip everything except numbers
        $clean = preg_replace('/[^\d]/', '', $phone);
        // Default Indian prefix if 10 digits
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
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->token,
            'Content-Type: application/json',
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
        if ($httpCode >= 400 || isset($decoded['error'])) {
            $errMsg = $decoded['error']['message'] ?? "HTTP {$httpCode}: {$responseBody}";
            throw new Exception("Meta API returned HTTP {$httpCode}: {$errMsg}");
        }

        return is_array($decoded) ? $decoded : ['raw' => $responseBody];
    }
}
