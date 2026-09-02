<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponRedemption extends Model
{
    protected $table = 'coupon_redemptions';

    protected $fillable = [
        'coupon_id',
        'offer_id',
        'payable_type',
        'payable_id',
        'package_id',
        'order_id',
        'payment_id',
        'discount_type',
        'discount_value',
        'discount_amount',
        'original_amount',
        'final_amount',
        'status',
        'redeemed_at'
    ];

    protected $casts = [
        'discount_value' => 'float',
        'discount_amount' => 'float',
        'original_amount' => 'float',
        'final_amount' => 'float',
    ];

    protected $dates = [
        'redeemed_at',
        'created_at',
        'updated_at'
    ];

    public function coupon()
    {
        return $this->belongsTo('App\Coupon', 'coupon_id', 'id');
    }

    public function offer()
    {
        return $this->belongsTo('App\Offer', 'offer_id', 'id');
    }

    public function package()
    {
        return $this->belongsTo('App\Package', 'package_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id', 'id');
    }

    public function payable()
    {
        return $this->morphTo();
    }

    /**
     * Get User / Buyer Name
     */
    public function getBuyerNameAttribute()
    {
        if ($this->payable) {
            if ($this->payable instanceof \App\Company) {
                return $this->payable->name;
            } elseif ($this->payable instanceof \App\User) {
                return $this->payable->name;
            }
        }
        return 'N/A';
    }
}
