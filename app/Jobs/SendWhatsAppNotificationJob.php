<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\WhatsAppNotification;
use App\Services\WhatsApp\WhatsAppNotificationService;
use Illuminate\Support\Facades\Log;
use Exception;

class SendWhatsAppNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $notificationId;
    public $tries = 3;
    public $backoff = [15, 60, 180];
    public $timeout = 30;

    /**
     * Create a new job instance.
     */
    public function __construct(int $notificationId)
    {
        $this->notificationId = $notificationId;
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsAppNotificationService $service)
    {
        $notification = WhatsAppNotification::find($this->notificationId);

        if (!$notification) {
            return;
        }

        // Increment attempts count
        $notification->attempts = $notification->attempts + 1;
        $notification->save();

        try {
            $driver = $service->getDriver();

            $result = $driver->sendTemplate(
                $notification->recipient_phone,
                $notification->template_key,
                $notification->payload ?: []
            );

            if ($result['success']) {
                $notification->markAsSent($result['message_id'] ?? null);
                if (!empty($result['rendered_message'])) {
                    $notification->rendered_message = $result['rendered_message'];
                    $notification->save();
                }
            } else {
                $error = $result['error'] ?? 'Unknown WhatsApp Provider error';
                $notification->error_message = $error;

                if ($this->attempts() >= $this->tries) {
                    $notification->markAsFailed($error);
                } else {
                    $notification->save();
                    // Release back to queue for retry with backoff
                    $this->release($this->backoff[$this->attempts() - 1] ?? 60);
                }
            }
        } catch (Exception $e) {
            Log::warning("SendWhatsAppNotificationJob execution failed for ID #{$this->notificationId}: " . $e->getMessage());
            $notification->error_message = $e->getMessage();

            if ($this->attempts() >= $this->tries) {
                $notification->markAsFailed($e->getMessage());
            } else {
                $notification->save();
                $this->release($this->backoff[$this->attempts() - 1] ?? 60);
            }
        }
    }

    /**
     * Handle job failure
     */
    public function failed(Exception $exception)
    {
        $notification = WhatsAppNotification::find($this->notificationId);
        if ($notification) {
            $notification->markAsFailed("Job permanently failed: " . $exception->getMessage());
        }
    }
}
