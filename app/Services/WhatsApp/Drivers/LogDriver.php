<?php

namespace App\Services\WhatsApp\Drivers;

use App\Services\WhatsApp\Contracts\WhatsAppProviderInterface;
use App\WhatsAppSetting;
use App\WhatsAppTemplate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LogDriver implements WhatsAppProviderInterface
{
    protected $setting;

    public function __construct(WhatsAppSetting $setting)
    {
        $this->setting = $setting;
    }

    public function sendTemplate(string $to, string $templateKey, array $variables = [], array $buttons = []): array
    {
        $template = WhatsAppTemplate::where('template_key', $templateKey)->first();
        $messageBody = $template ? $template->renderFullMessage($variables) : "Template [{$templateKey}] with " . json_encode($variables);

        $msgId = 'mock_wa_' . Str::random(20);

        Log::info("📱 [WHATSAPP LOG DRIVER] Message Sent to {$to}", [
            'to' => $to,
            'template_key' => $templateKey,
            'variables' => $variables,
            'rendered_message' => $messageBody,
            'message_id' => $msgId,
            'timestamp' => Carbon::now()->toIso8601String(),
        ]);

        return [
            'success' => true,
            'message_id' => $msgId,
            'rendered_message' => $messageBody,
            'error' => null,
            'raw' => ['driver' => 'log', 'logged_at' => Carbon::now()->toIso8601String()],
        ];
    }

    public function sendDirectMessage(string $to, string $message): array
    {
        $msgId = 'mock_wa_' . Str::random(20);

        Log::info("📱 [WHATSAPP LOG DRIVER] Direct Message Sent to {$to}", [
            'to' => $to,
            'message' => $message,
            'message_id' => $msgId,
            'timestamp' => Carbon::now()->toIso8601String(),
        ]);

        return [
            'success' => true,
            'message_id' => $msgId,
            'rendered_message' => $message,
            'error' => null,
            'raw' => ['driver' => 'log'],
        ];
    }

    public function testConnection(): array
    {
        return [
            'success' => true,
            'latency_ms' => 5,
            'message' => 'Log Simulator Driver is active and logging cleanly to storage/logs/laravel.log.',
        ];
    }

    public function parseWebhook(array $payload): array
    {
        return [];
    }
}
