<?php

namespace App\Http\Controllers\Admin;

use App\CouponRedemption;
use App\Offer;
use App\Coupon;
use App\Package;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CouponRedemptionController extends Controller
{
    /**
     * Display all Coupon Redemptions Audit Report with Filters
     */
    public function index(Request $request)
    {
        $query = CouponRedemption::with(['coupon', 'offer', 'package', 'payable', 'order'])->orderBy('id', 'DESC');

        // Filter: Offer
        if ($request->filled('offer_id')) {
            $query->where('offer_id', $request->offer_id);
        }

        // Filter: Coupon Code
        if ($request->filled('coupon_code')) {
            $code = trim($request->coupon_code);
            $query->whereHas('coupon', function ($cq) use ($code) {
                $cq->where('code', 'like', "%{$code}%");
            });
        }

        // Filter: Package
        if ($request->filled('package_id')) {
            $query->where('package_id', $request->package_id);
        }

        // Filter: Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('redeemed_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('redeemed_at', '<=', $request->date_to);
        }

        // Summary KPI Metrics for the filtered result
        $totalRedemptions = (clone $query)->where('status', 'completed')->count();
        $totalDiscountGiven = (clone $query)->where('status', 'completed')->sum('discount_amount');
        $totalRevenue = (clone $query)->where('status', 'completed')->sum('final_amount');

        $redemptions = $query->paginate(20);

        $offers = Offer::orderBy('name', 'ASC')->get();
        $packages = Package::orderBy('package_title', 'ASC')->get();

        return view('admin.coupon_redemption.index', compact(
            'redemptions',
            'offers',
            'packages',
            'totalRedemptions',
            'totalDiscountGiven',
            'totalRevenue'
        ));
    }
}
