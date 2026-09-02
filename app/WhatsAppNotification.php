<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WhatsAppNotification extends Model
{
    protected $table = 'whatsapp_notifications';

    protected $fillable = [
        'notifiable_type',
        'notifiable_id',
        'recipient_phone',
        'event_type',
        'template_key',
        'idempotency_key',
        'provider',
        'provider_message_id',
        'status',
        'payload',
        'rendered_message',
        'attempts',
        'max_attempts',
        'error_message',
        'sent_at',
        'delivered_at',
        'read_at',
        'failed_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    /**
     * Scope for successful sent/delivered/read notifications
     */
    public function scopeSuccessful($query)
    {
        return $query->whereIn('status', ['sent', 'delivered', 'read']);
    }

    /**
     * Scope for failed notifications
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for pending queued notifications
     */
    public function scopeQueued($query)
    {
        return $query->where('status', 'queued');
    }

    /**
     * Mark notification as sent
     */
    public function markAsSent(?string $providerMessageId = null): self
    {
        $this->update([
            'status' => 'sent',
            'provider_message_id' => $providerMessageId ?: $this->provider_message_id,
            'sent_at' => Carbon::now(),
            'error_message' => null,
        ]);
        return $this;
    }

    /**
     * Mark notification as delivered
     */
    public function markAsDelivered(): self
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => Carbon::now(),
        ]);
        return $this;
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(): self
    {
        $this->update([
            'status' => 'read',
            'read_at' => Carbon::now(),
        ]);
        return $this;
    }

    /**
     * Mark notification as failed
     */
    public function markAsFailed(string $error): self
    {
        $this->update([
            'status' => 'failed',
            'failed_at' => Carbon::now(),
            'error_message' => $error,
        ]);
        return $this;
    }

    /**
     * Resolve recipient name / model safely
     */
    public function getRecipientNameAttribute(): string
    {
        if ($this->notifiable_type === 'user') {
            $user = User::find($this->notifiable_id);
            return $user ? $user->getName() : "User #{$this->notifiable_id}";
        } elseif ($this->notifiable_type === 'company') {
            $company = Company::find($this->notifiable_id);
            return $company ? $company->name : "Company #{$this->notifiable_id}";
        }
        return "Recipient #{$this->notifiable_id}";
    }
}
