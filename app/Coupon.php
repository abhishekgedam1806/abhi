<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';

    protected $fillable = [
        'offer_id',
        'code',
        'discount_type',
        'discount_value',
        'max_discount',
        'min_order_value',
        'applicable_packages',
        'applicable_user_types',
        'is_first_purchase_only',
        'total_usage_limit',
        'per_user_usage_limit',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
        'status'
    ];

    protected $casts = [
        'discount_value' => 'float',
        'max_discount' => 'float',
        'min_order_value' => 'float',
        'is_first_purchase_only' => 'boolean',
        'is_active' => 'boolean',
        'total_usage_limit' => 'integer',
        'per_user_usage_limit' => 'integer',
        'used_count' => 'integer'
    ];

    protected $dates = [
        'starts_at',
        'expires_at',
        'created_at',
        'updated_at'
    ];

    /**
     * Relationship: Coupon belongs to Offer
     */
    public function offer()
    {
        return $this->belongsTo('App\Offer', 'offer_id', 'id');
    }

    /**
     * Relationship: Coupon has many Redemptions
     */
    public function redemptions()
    {
        return $this->hasMany('App\CouponRedemption', 'coupon_id', 'id');
    }

    /**
     * Scope: Lookup coupon case-insensitively
     */
    public function scopeByCode($query, $code)
    {
        return $query->whereRaw('LOWER(code) = ?', [strtolower(trim($code))]);
    }

    /**
     * Helper: Get array of applicable package IDs
     */
    public function getApplicablePackagesArray(): array
    {
        if (empty($this->applicable_packages)) {
            return [];
        }
        if (is_array($this->applicable_packages)) {
            return $this->applicable_packages;
        }
        $decoded = json_decode($this->applicable_packages, true);
        if (is_array($decoded)) {
            return $decoded;
        }
        return array_map('intval', explode(',', $this->applicable_packages));
    }

    /**
     * Helper: Get array of applicable user types
     */
    public function getApplicableUserTypesArray(): array
    {
        if (empty($this->applicable_user_types)) {
            return ['all'];
        }
        if (is_array($this->applicable_user_types)) {
            return $this->applicable_user_types;
        }
        $decoded = json_decode($this->applicable_user_types, true);
        if (is_array($decoded)) {
            return $decoded;
        }
        return explode(',', $this->applicable_user_types);
    }

    /**
     * Computed Status
     */
    public function getComputedStatusAttribute()
    {
        if (!$this->is_active || $this->status === 'disabled') {
            return 'Disabled';
        }

        $now = now();
        if ($this->starts_at && $now->lt($this->starts_at)) {
            return 'Scheduled';
        }

        if ($this->expires_at && $now->gt($this->expires_at)) {
            return 'Expired';
        }

        if ($this->total_usage_limit && $this->used_count >= $this->total_usage_limit) {
            return 'Exhausted';
        }

        return 'Active';
    }

    /**
     * Formatted Discount String (e.g. '20% OFF' or '₹500 OFF')
     */
    public function getFormattedDiscountAttribute()
    {
        if ($this->discount_type === 'percentage') {
            $str = rtrim(rtrim(number_format($this->discount_value, 2), '0'), '.') . '% OFF';
            if ($this->max_discount > 0) {
                $str .= ' (Max ₹' . number_format($this->max_discount, 0) . ')';
            }
            return $str;
        }
        return '₹' . number_format($this->discount_value, 0) . ' OFF';
    }
}
