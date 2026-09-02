<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table = 'offers';

    protected $fillable = [
        'name',
        'description',
        'audience_type',
        'starts_at',
        'expires_at',
        'status',
        'created_by'
    ];

    protected $dates = [
        'starts_at',
        'expires_at',
        'created_at',
        'updated_at'
    ];

    /**
     * Relationship: An Offer has many Coupons
     */
    public function coupons()
    {
        return $this->hasMany('App\Coupon', 'offer_id', 'id');
    }

    /**
     * Relationship: An Offer has many Redemptions
     */
    public function redemptions()
    {
        return $this->hasMany('App\CouponRedemption', 'offer_id', 'id');
    }

    /**
     * Dynamic Status Calculation
     */
    public function getComputedStatusAttribute()
    {
        if ($this->status === 'disabled') {
            return 'Disabled';
        }

        $now = now();
        if ($this->starts_at && $now->lt($this->starts_at)) {
            return 'Scheduled';
        }

        if ($this->expires_at && $now->gt($this->expires_at)) {
            return 'Expired';
        }

        return 'Active';
    }

    /**
     * Metrics: Total discount given across all coupons
     */
    public function getTotalDiscountGivenAttribute()
    {
        return $this->redemptions()->where('status', 'completed')->sum('discount_amount');
    }

    /**
     * Metrics: Total revenue generated
     */
    public function getTotalRevenueAttribute()
    {
        return $this->redemptions()->where('status', 'completed')->sum('final_amount');
    }

    /**
     * Metrics: Total completed redemptions
     */
    public function getTotalRedemptionsCountAttribute()
    {
        return $this->redemptions()->where('status', 'completed')->count();
    }
}
