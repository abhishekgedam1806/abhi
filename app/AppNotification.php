<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    protected $table = 'app_notifications';

    protected $fillable = [
        'notifiable_type',
        'notifiable_id',
        'type',
        'title',
        'message',
        'target_url',
        'icon',
        'color',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', 0);
    }

    /**
     * Scope for a specific recipient entity
     */
    public function scopeForRecipient($query, $type, $id)
    {
        return $query->where('notifiable_type', $type)->where('notifiable_id', $id);
    }

    /**
     * Mark single notification as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => 1,
            'read_at' => Carbon::now(),
        ]);
        return $this;
    }

    /**
     * Static Dispatch Helper to send a notification
     *
     * @param string $type ('job_applied', 'new_message', 'job_posted', 'lead_received', 'status_changed', 'package_purchased')
     * @param string $notifiableType ('user', 'company', 'admin')
     * @param int $notifiableId
     * @param string $title
     * @param string $message
     * @param string $targetUrl
     * @param string $icon (FontAwesome icon class, e.g. 'fa-briefcase')
     * @param string $color (Hex color code e.g. '#2563EB')
     * @return AppNotification
     */
    public static function sendNotification(
        $type,
        $notifiableType,
        $notifiableId,
        $title,
        $message,
        $targetUrl = '#',
        $icon = 'fa-bell',
        $color = '#2563EB'
    ) {
        if (empty($notifiableId) || empty($notifiableType)) {
            return null;
        }

        // Ensure target_url is always stored as a portable relative path
        if (!empty($targetUrl) && (strpos($targetUrl, 'http://') === 0 || strpos($targetUrl, 'https://') === 0)) {
            $parsed = parse_url($targetUrl);
            $targetUrl = ($parsed['path'] ?? '/') . (isset($parsed['query']) ? '?' . $parsed['query'] : '') . (isset($parsed['fragment']) ? '#' . $parsed['fragment'] : '');
        }

        $inApp = self::create([
            'type' => $type,
            'notifiable_type' => $notifiableType,
            'notifiable_id' => $notifiableId,
            'title' => $title,
            'message' => $message,
            'target_url' => $targetUrl,
            'icon' => $icon,
            'color' => $color,
            'is_read' => 0,
        ]);

        // Non-intrusive asynchronous WhatsApp dispatch (fail-safe)
        try {
            if (in_array($notifiableType, ['user', 'company'])) {
                $whatsAppService = app(\App\Services\WhatsApp\WhatsAppNotificationService::class);
                $recipientName = ($notifiableType === 'user')
                    ? (\App\User::find($notifiableId) ? \App\User::find($notifiableId)->getName() : 'User')
                    : (\App\Company::find($notifiableId) ? \App\Company::find($notifiableId)->name : 'Company');

                $fullUrl = (strpos($targetUrl, 'http') === 0) ? $targetUrl : url($targetUrl);

                $whatsAppService->send(
                    $type,
                    $notifiableType,
                    $notifiableId,
                    [
                        'name' => $recipientName,
                        'title' => $title,
                        'message' => $message,
                        'action_url' => $fullUrl,
                    ],
                    null,
                    "inapp_{$type}_{$notifiableId}_{$inApp->id}"
                );
            }
        } catch (\Exception $e) {
            // Never block the application for notification issues
            \Illuminate\Support\Facades\Log::warning("AppNotification WhatsApp bridge error: " . $e->getMessage());
        }

        return $inApp;
    }
}
