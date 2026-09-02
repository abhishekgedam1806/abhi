<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentWebhook extends Model
{
    protected $table = 'payment_webhooks';

    protected $fillable = [
        'gateway',
        'event_id',
        'event_type',
        'payload',
        'signature_verified',
        'processed',
        'processed_at',
    ];

    protected $casts = [
        'signature_verified' => 'boolean',
        'processed' => 'boolean',
        'processed_at' => 'datetime',
    ];
}
