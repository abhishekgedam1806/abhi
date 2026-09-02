<?php

namespace App\Services\Coupon;

use App\Coupon;
use App\Offer;
use App\CouponRedemption;
use App\Order;
use App\Package;
use App\Company;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class CouponService
{
    /**
     * Validate coupon against buyer, package, and rules
     *
     * @param string $code
     * @param Package $package
     * @param mixed $buyer (Company, User)
     * @param float|null $customPrice
     * @return array
     */
    public function validateCoupon(string $code, Package $package, $buyer, ?float $customPrice = null): array
    {
        $code = strtoupper(trim($code));
        if (empty($code)) {
            return ['valid' => false, 'message' => __('Please enter a coupon code.')];
        }

        // 1. Find coupon case-insensitively
        $coupon = Coupon::byCode($code)->first();
        if (!$coupon) {
            return ['valid' => false, 'message' => __('Invalid coupon code. Please check and try again.')];
        }

        // 2. Check active status
        if (!$coupon->is_active || $coupon->status === 'disabled') {
            return ['valid' => false, 'message' => __('This coupon is currently disabled or inactive.')];
        }

        $now = now();

        // 3. Check start date
        if ($coupon->starts_at && $now->lt($coupon->starts_at)) {
            return ['valid' => false, 'message' => __('This coupon campaign has not started yet.')];
        }

        // 4. Check expiration date
        if ($coupon->expires_at && $now->gt($coupon->expires_at)) {
            return ['valid' => false, 'message' => __('This coupon code has expired.')];
        }

        // 5. Check total usage limit
        if ($coupon->total_usage_limit && $coupon->used_count >= $coupon->total_usage_limit) {
            return ['valid' => false, 'message' => __('This coupon offer has reached its maximum usage limit.')];
        }

        // 6. Check package restriction
        $applicablePackages = $coupon->getApplicablePackagesArray();
        if (!empty($applicablePackages) && !in_array($package->id, $applicablePackages)) {
            return ['valid' => false, 'message' => __('This coupon code is not applicable to the selected package.')];
        }

        // 7. Check user type restriction
        $applicableUserTypes = $coupon->getApplicableUserTypesArray();
        if (!in_array('all', $applicableUserTypes)) {
            $isEmployer = ($buyer instanceof Company) || ($package->package_for === 'employer');
            $isCandidate = ($buyer instanceof User && !$buyer->isBusinessUser()) || ($package->package_for === 'job_seeker');
            $isBusiness = ($buyer instanceof User && $buyer->isBusinessUser()) || ($package->package_for === 'business');

            $allowed = false;
            if ($isEmployer && in_array('employer', $applicableUserTypes)) $allowed = true;
            if ($isCandidate && (in_array('job_seeker', $applicableUserTypes) || in_array('candidate', $applicableUserTypes))) $allowed = true;
            if ($isBusiness && in_array('business', $applicableUserTypes)) $allowed = true;

            if (!$allowed) {
                return ['valid' => false, 'message' => __('This coupon is not available for your account type.')];
            }
        }

        // 8. Determine base price
        $basePrice = ($customPrice !== null) ? floatval($customPrice) : floatval($package->package_price);

        // 9. Check minimum order value
        if ($coupon->min_order_value > 0 && $basePrice < $coupon->min_order_value) {
            return [
                'valid' => false,
                'message' => __('This coupon requires a minimum purchase amount of ₹') . number_format($coupon->min_order_value, 2) . '.'
            ];
        }

        // 10. Check per-user limit & first purchase rule (if buyer is provided)
        if ($buyer) {
            $payableType = get_class($buyer);
            $payableId = $buyer->id;

            // Per-user redemptions count
            $userRedemptionsCount = CouponRedemption::where('coupon_id', $coupon->id)
                ->where('payable_type', $payableType)
                ->where('payable_id', $payableId)
                ->where('status', 'completed')
                ->count();

            if ($coupon->per_user_usage_limit > 0 && $userRedemptionsCount >= $coupon->per_user_usage_limit) {
                return ['valid' => false, 'message' => __('You have already reached the maximum usage limit for this coupon.')];
            }

            // First purchase only check
            if ($coupon->is_first_purchase_only) {
                $pastPaidOrdersCount = Order::where('payable_type', $payableType)
                    ->where('payable_id', $payableId)
                    ->where('status', 'completed')
                    ->count();

                if ($pastPaidOrdersCount > 0) {
                    return ['valid' => false, 'message' => __('This coupon is only valid on your first paid purchase.')];
                }
            }
        }

        // 11. Calculate Discount
        $discountAmount = $this->calculateDiscount($coupon, $basePrice);
        $finalAmount = max(0, $basePrice - $discountAmount);

        return [
            'valid' => true,
            'coupon' => $coupon,
            'coupon_id' => $coupon->id,
            'coupon_code' => $coupon->code,
            'discount_type' => $coupon->discount_type,
            'discount_value' => $coupon->discount_value,
            'discount_amount' => $discountAmount,
            'original_amount' => $basePrice,
            'final_amount' => $finalAmount,
            'formatted_discount' => $coupon->formatted_discount,
            'message' => __('Coupon applied successfully! You saved ₹') . number_format($discountAmount, 2) . '.'
        ];
    }

    /**
     * Calculate discount value based on type and max discount rules
     *
     * @param Coupon $coupon
     * @param float $basePrice
     * @return float
     */
    public function calculateDiscount(Coupon $coupon, float $basePrice): float
    {
        if ($basePrice <= 0) {
            return 0.00;
        }

        $discount = 0.00;
        if ($coupon->discount_type === 'percentage') {
            $discount = ($basePrice * floatval($coupon->discount_value)) / 100;
            if ($coupon->max_discount > 0 && $discount > $coupon->max_discount) {
                $discount = floatval($coupon->max_discount);
            }
        } else {
            // Fixed amount
            $discount = floatval($coupon->discount_value);
        }

        // Discount cannot exceed base price
        return round(min($basePrice, max(0, $discount)), 2);
    }

    /**
     * Atomically redeem a coupon upon successful payment completion
     *
     * @param Order $order
     * @param string|null $paymentId
     * @return CouponRedemption|null
     */
    public function redeemCoupon(Order $order, ?string $paymentId = null): ?CouponRedemption
    {
        if (empty($order->coupon_id)) {
            return null;
        }

        return DB::transaction(function () use ($order, $paymentId) {
            // Lock coupon row for atomic update
            $coupon = Coupon::where('id', $order->coupon_id)->lockForUpdate()->first();
            if (!$coupon) {
                return null;
            }

            // Check if already redeemed for this specific order
            $existing = CouponRedemption::where('order_id', $order->id)->where('status', 'completed')->first();
            if ($existing) {
                return $existing;
            }

            // Increment atomic usage count
            $coupon->increment('used_count');

            // Record permanent redemption
            $redemption = CouponRedemption::create([
                'coupon_id' => $coupon->id,
                'offer_id' => $coupon->offer_id,
                'payable_type' => $order->payable_type,
                'payable_id' => $order->payable_id,
                'package_id' => $order->package_id,
                'order_id' => $order->id,
                'payment_id' => $paymentId,
                'discount_type' => $coupon->discount_type,
                'discount_value' => $coupon->discount_value,
                'discount_amount' => $order->discount_amount ?? 0.00,
                'original_amount' => $order->package_price ?? 0.00,
                'final_amount' => $order->total_amount ?? 0.00,
                'status' => 'completed',
                'redeemed_at' => now(),
            ]);

            Log::info("Coupon [{$coupon->code}] redeemed successfully for Order #{$order->order_number}. Saved ₹{$order->discount_amount}.");
            return $redemption;
        });
    }

    /**
     * Generate an unpredictable, unique coupon code
     *
     * @param string $prefix
     * @param int $length
     * @return string
     */
    public function generateRandomCouponCode(string $prefix = '', int $length = 8): string
    {
        $prefix = strtoupper(preg_replace('/[^A-Z0-9]/', '', $prefix));
        $chars = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ'; // exclude ambiguous 0, O, 1, I
        
        do {
            $randomStr = '';
            for ($i = 0; $i < $length; $i++) {
                $randomStr .= $chars[random_int(0, strlen($chars) - 1)];
            }
            $candidate = $prefix ? ($prefix . $randomStr) : $randomStr;
            $exists = Coupon::where('code', $candidate)->exists();
        } while ($exists);

        return $candidate;
    }

    /**
     * Get real-time analytics for an offer campaign
     *
     * @param int $offerId
     * @return array
     */
    public function getOfferAnalytics(int $offerId): array
    {
        $redemptions = CouponRedemption::where('offer_id', $offerId)->where('status', 'completed');

        $totalRedemptions = (clone $redemptions)->count();
        $totalDiscountGiven = (clone $redemptions)->sum('discount_amount');
        $totalRevenue = (clone $redemptions)->sum('final_amount');
        $aov = $totalRedemptions > 0 ? ($totalRevenue / $totalRedemptions) : 0.00;

        return [
            'total_redemptions' => $totalRedemptions,
            'total_discount_given' => $totalDiscountGiven,
            'total_revenue' => $totalRevenue,
            'average_order_value' => round($aov, 2)
        ];
    }
}
