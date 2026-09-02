<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentRefund extends Model
{
    protected $table = 'payment_refunds';

    protected $fillable = [
        'payment_id',
        'order_id',
        'gateway',
        'gateway_refund_id',
        'amount',
        'currency',
        'status',
        'reason',
        'raw_response',
    ];

    public function payment()
    {
        return $this->belongsTo('App\Payment', 'payment_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id', 'id');
    }
}
