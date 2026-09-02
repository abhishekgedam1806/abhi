<?php

namespace App\Services\WhatsApp\Drivers;

use App\Services\WhatsApp\Contracts\WhatsAppProviderInterface;
use App\WhatsAppSetting;
use App\WhatsAppTemplate;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class TwilioWhatsAppDriver implements WhatsAppProviderInterface
{
    protected $setting;
    protected $accountSid;
    protected $authToken;
    protected $fromNumber;

    public function __construct(WhatsAppSetting $setting)
    {
        $this->setting = $setting;
        $this->accountSid = $setting->phone_number_id; // Twilio Account SID
        $this->authToken = $setting->getDecryptedApiKey(); // Twilio Auth Token
        $this->fromNumber = $setting->sender_number ?: '+14155238886'; // Twilio WhatsApp Sender
    }

    public function sendTemplate(string $to, string $templateKey, array $variables = [], array $buttons = []): array
    {
        $template = WhatsAppTemplate::where('template_key', $templateKey)->first();
        $messageBody = $template ? $template->renderFullMessage($variables) : "Template [{$templateKey}]";

        return $this->sendDirectMessage($to, $messageBody);
    }

    public function sendDirectMessage(string $to, string $message): array
    {
        $toWhatsApp = 'whatsapp:' . $this->formatE164($to);
        $fromWhatsApp = 'whatsapp:' . $this->formatE164($this->fromNumber);

        $url = "https://api.twilio.com/2010-04-01/Accounts/{$this->accountSid}/Messages.json";

        $payload = [
            'To' => $toWhatsApp,
            'From' => $fromWhatsApp,
            'Body' => $message,
        ];

        try {
            $response = $this->sendHttpRequest($url, $payload);
            $messageId = $response['sid'] ?? null;

            return [
                'success' => !empty($messageId),
                'message_id' => $messageId,
                'rendered_message' => $message,
                'error' => null,
                'raw' => $response,
            ];
        } catch (Exception $e) {
            Log::error("Twilio WhatsApp API Error: " . $e->getMessage());
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
        if (empty($this->accountSid) || empty($this->authToken)) {
            return [
                'success' => false,
                'latency_ms' => 0,
                'message' => 'Missing Twilio Account SID or Auth Token in WhatsApp Settings.',
            ];
        }

        $url = "https://api.twilio.com/2010-04-01/Accounts/{$this->accountSid}.json";

        $startTime = microtime(true);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->accountSid}:{$this->authToken}");
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
        if ($httpCode === 200 && !empty($data['friendly_name'])) {
            return [
                'success' => true,
                'latency_ms' => $latency,
                'message' => "Twilio Account ({$data['friendly_name']}) connected successfully.",
            ];
        }

        $errMsg = $data['message'] ?? "HTTP {$httpCode}: {$response}";
        return [
            'success' => false,
            'latency_ms' => $latency,
            'message' => "Twilio Error: {$errMsg}",
        ];
    }

    public function parseWebhook(array $payload): array
    {
        $events = [];
        $messageId = $payload['MessageSid'] ?? null;
        $status = $payload['MessageStatus'] ?? null; // queued, sent, delivered, read, failed, undelivered

        if ($messageId && $status) {
            $mappedStatus = $status;
            if ($status === 'undelivered') $mappedStatus = 'failed';

            $events[] = [
                'message_id' => $messageId,
                'status' => $mappedStatus,
                'timestamp' => Carbon::now(),
                'error' => $payload['ErrorMessage'] ?? null,
            ];
        }

        return $events;
    }

    protected function formatE164(string $phone): string
    {
        $clean = preg_replace('/[^\d\+]/', '', $phone);
        if (strpos($clean, '+') !== 0) {
            if (strlen($clean) === 10) {
                $clean = '+91' . $clean;
            } else {
                $clean = '+' . $clean;
            }
        }
        return $clean;
    }

    protected function sendHttpRequest(string $url, array $payload): array
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->accountSid}:{$this->authToken}");
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
        if ($httpCode >= 400 || (isset($decoded['status']) && $decoded['status'] === 400)) {
            $errMsg = $decoded['message'] ?? "HTTP {$httpCode}: {$responseBody}";
            throw new Exception("Twilio API Error: {$errMsg}");
        }

        return is_array($decoded) ? $decoded : ['raw' => $responseBody];
    }
}
