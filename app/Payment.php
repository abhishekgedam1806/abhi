<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'order_id',
        'payable_type',
        'payable_id',
        'gateway',
        'gateway_payment_id',
        'gateway_order_id',
        'amount',
        'currency',
        'payment_method',
        'payment_status',
        'transaction_reference',
        'failure_reason',
        'raw_response',
        'paid_at',
    ];

    protected $dates = [
        'paid_at',
        'created_at',
        'updated_at',
    ];

    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id', 'id');
    }

    public function payable()
    {
        return $this->morphTo();
    }

    public function refunds()
    {
        return $this->hasMany('App\PaymentRefund', 'payment_id', 'id');
    }
}
