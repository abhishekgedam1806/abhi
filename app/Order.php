<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'order_number',
        'payable_type',
        'payable_id',
        'package_id',
        'coupon_id',
        'coupon_code',
        'package_type',
        'package_title',
        'package_price',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'currency',
        'status',
        'gateway',
        'gateway_order_id',
        'notes',
    ];

    /**
     * Polymorphic relation to Company, User, or Business
     */
    public function payable()
    {
        return $this->morphTo();
    }

    public function payments()
    {
        return $this->hasMany('App\Payment', 'order_id', 'id');
    }

    public function package()
    {
        return $this->belongsTo('App\Package', 'package_id', 'id');
    }

    public function coupon()
    {
        return $this->belongsTo('App\Coupon', 'coupon_id', 'id');
    }

    public function latestPayment()
    {
        return $this->hasOne('App\Payment', 'order_id', 'id')->latest();
    }

    public function getBuyerNameAttribute()
    {
        if ($this->payable) {
            return $this->payable->name ?? ($this->payable->first_name . ' ' . $this->payable->last_name);
        }
        return 'N/A';
    }

    public function getBuyerEmailAttribute()
    {
        if ($this->payable) {
            return $this->payable->email ?? 'N/A';
        }
        return 'N/A';
    }

    public function getBuyerPhoneAttribute()
    {
        if ($this->payable) {
            return $this->payable->phone ?? ($this->payable->mobile_num ?? 'N/A');
        }
        return 'N/A';
    }

    public static function generateOrderNumber()
    {
        $prefix = 'ORD-' . date('Ymd') . '-';
        $random = strtoupper(substr(uniqid(), -5));
        return $prefix . $random;
    }
}
