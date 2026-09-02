<?php

namespace App\Http\Controllers\Admin;

use App\Offer;
use App\Coupon;
use App\CouponRedemption;
use App\Package;
use App\Services\Coupon\CouponService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class OfferController extends Controller
{
    protected CouponService $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * Display list of Offers & Coupons with Top KPI Metrics
     */
    public function index(Request $request)
    {
        // 1. Top KPI Metrics
        $totalOffersCount = Offer::count();
        $activeOffersCount = Offer::where('status', 'active')->count();
        $activeCouponsCount = Coupon::where('is_active', true)->count();
        $totalRedemptionsCount = CouponRedemption::where('status', 'completed')->count();
        $totalDiscountGiven = CouponRedemption::where('status', 'completed')->sum('discount_amount');
        $totalRevenueGenerated = CouponRedemption::where('status', 'completed')->sum('final_amount');

        // 2. Query Offers with search & status filter
        $query = Offer::with(['coupons', 'redemptions'])->orderBy('id', 'DESC');

        if ($request->filled('search')) {
            $term = trim($request->search);
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%")
                  ->orWhereHas('coupons', function ($cq) use ($term) {
                      $cq->where('code', 'like', "%{$term}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $offers = $query->paginate(15);

        return view('admin.offer.index', compact(
            'offers',
            'totalOffersCount',
            'activeOffersCount',
            'activeCouponsCount',
            'totalRedemptionsCount',
            'totalDiscountGiven',
            'totalRevenueGenerated'
        ));
    }

    /**
     * Show create offer & coupon form
     */
    public function create()
    {
        $packages = Package::orderBy('package_for', 'ASC')->orderBy('package_price', 'ASC')->get();
        return view('admin.offer.add', compact('packages'));
    }

    /**
     * Store new Offer and Coupon
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'coupon_code' => 'required|string|max:50|unique:coupons,code',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0.01',
            'max_discount' => 'nullable|numeric|min:0',
            'min_order_value' => 'nullable|numeric|min:0',
            'audience_type' => 'required|in:all,new_users,existing_users',
            'applicable_packages' => 'nullable|array',
            'applicable_user_types' => 'nullable|array',
            'total_usage_limit' => 'nullable|integer|min:1',
            'per_user_usage_limit' => 'required|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'is_first_purchase_only' => 'nullable|boolean',
            'is_active' => 'required|boolean',
        ]);

        DB::transaction(function () use ($request) {
            $adminId = Auth::guard('admin')->id();

            // 1. Create Offer
            $offer = Offer::create([
                'name' => $request->name,
                'description' => $request->description,
                'audience_type' => $request->audience_type,
                'starts_at' => $request->starts_at,
                'expires_at' => $request->expires_at,
                'status' => $request->is_active ? 'active' : 'disabled',
                'created_by' => $adminId,
            ]);

            // 2. Create Coupon
            Coupon::create([
                'offer_id' => $offer->id,
                'code' => strtoupper(trim($request->coupon_code)),
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount_value,
                'max_discount' => $request->filled('max_discount') ? $request->max_discount : null,
                'min_order_value' => $request->filled('min_order_value') ? $request->min_order_value : null,
                'applicable_packages' => !empty($request->applicable_packages) ? json_encode(array_map('intval', $request->applicable_packages)) : null,
                'applicable_user_types' => !empty($request->applicable_user_types) ? json_encode($request->applicable_user_types) : json_encode(['all']),
                'is_first_purchase_only' => $request->has('is_first_purchase_only') ? 1 : 0,
                'total_usage_limit' => $request->filled('total_usage_limit') ? $request->total_usage_limit : null,
                'per_user_usage_limit' => $request->per_user_usage_limit ?? 1,
                'starts_at' => $request->starts_at,
                'expires_at' => $request->expires_at,
                'is_active' => (bool)$request->is_active,
                'status' => $request->is_active ? 'active' : 'disabled',
            ]);
        });

        flash(__('Offer and Coupon created successfully!'))->success();
        return redirect()->route('admin.offers.index');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $offer = Offer::with('coupons')->findOrFail($id);
        $coupon = $offer->coupons->first();
        $packages = Package::orderBy('package_for', 'ASC')->orderBy('package_price', 'ASC')->get();
        $analytics = $this->couponService->getOfferAnalytics($id);

        return view('admin.offer.edit', compact('offer', 'coupon', 'packages', 'analytics'));
    }

    /**
     * Update Offer & Coupon
     */
    public function update(Request $request, $id)
    {
        $offer = Offer::with('coupons')->findOrFail($id);
        $coupon = $offer->coupons->first();

        $couponId = $coupon ? $coupon->id : 0;

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'coupon_code' => 'required|string|max:50|unique:coupons,code,' . $couponId,
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0.01',
            'max_discount' => 'nullable|numeric|min:0',
            'min_order_value' => 'nullable|numeric|min:0',
            'audience_type' => 'required|in:all,new_users,existing_users',
            'applicable_packages' => 'nullable|array',
            'applicable_user_types' => 'nullable|array',
            'total_usage_limit' => 'nullable|integer|min:1',
            'per_user_usage_limit' => 'required|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'is_first_purchase_only' => 'nullable|boolean',
            'is_active' => 'required|boolean',
        ]);

        DB::transaction(function () use ($request, $offer, $coupon) {
            $offer->update([
                'name' => $request->name,
                'description' => $request->description,
                'audience_type' => $request->audience_type,
                'starts_at' => $request->starts_at,
                'expires_at' => $request->expires_at,
                'status' => $request->is_active ? 'active' : 'disabled',
            ]);

            if ($coupon) {
                $coupon->update([
                    'code' => strtoupper(trim($request->coupon_code)),
                    'discount_type' => $request->discount_type,
                    'discount_value' => $request->discount_value,
                    'max_discount' => $request->filled('max_discount') ? $request->max_discount : null,
                    'min_order_value' => $request->filled('min_order_value') ? $request->min_order_value : null,
                    'applicable_packages' => !empty($request->applicable_packages) ? json_encode(array_map('intval', $request->applicable_packages)) : null,
                    'applicable_user_types' => !empty($request->applicable_user_types) ? json_encode($request->applicable_user_types) : json_encode(['all']),
                    'is_first_purchase_only' => $request->has('is_first_purchase_only') ? 1 : 0,
                    'total_usage_limit' => $request->filled('total_usage_limit') ? $request->total_usage_limit : null,
                    'per_user_usage_limit' => $request->per_user_usage_limit ?? 1,
                    'starts_at' => $request->starts_at,
                    'expires_at' => $request->expires_at,
                    'is_active' => (bool)$request->is_active,
                    'status' => $request->is_active ? 'active' : 'disabled',
                ]);
            }
        });

        flash(__('Offer and Coupon updated successfully!'))->success();
        return redirect()->route('admin.offers.index');
    }

    /**
     * 1-Click Toggle Active / Disabled
     */
    public function toggleStatus($id)
    {
        $offer = Offer::with('coupons')->findOrFail($id);
        $newStatus = ($offer->status === 'active') ? 'disabled' : 'active';
        $isActive = ($newStatus === 'active');

        $offer->status = $newStatus;
        $offer->save();

        foreach ($offer->coupons as $coupon) {
            $coupon->is_active = $isActive;
            $coupon->status = $newStatus;
            $coupon->save();
        }

        flash(__('Offer status toggled to ') . ucfirst($newStatus) . '!')->success();
        return redirect()->back();
    }

    /**
     * 1-Click Duplicate Campaign with fresh Coupon Code
     */
    public function duplicate($id)
    {
        $originalOffer = Offer::with('coupons')->findOrFail($id);
        $originalCoupon = $originalOffer->coupons->first();

        DB::transaction(function () use ($originalOffer, $originalCoupon) {
            $newOffer = $originalOffer->replicate();
            $newOffer->name = $originalOffer->name . ' (Copy)';
            $newOffer->created_by = Auth::guard('admin')->id();
            $newOffer->save();

            if ($originalCoupon) {
                $newCoupon = $originalCoupon->replicate();
                $newCoupon->offer_id = $newOffer->id;
                $newCoupon->code = $this->couponService->generateRandomCouponCode('COPY');
                $newCoupon->used_count = 0;
                $newCoupon->save();
            }
        });

        flash(__('Offer duplicated successfully with new coupon code!'))->success();
        return redirect()->route('admin.offers.index');
    }

    /**
     * Delete Offer (Soft Archive if redemptions exist, hard delete if fresh)
     */
    public function destroy($id)
    {
        $offer = Offer::with(['coupons', 'redemptions'])->findOrFail($id);

        if ($offer->redemptions()->count() > 0) {
            // Preserve financial history by disabling instead of hard-deleting
            $offer->status = 'disabled';
            $offer->save();
            foreach ($offer->coupons as $c) {
                $c->is_active = false;
                $c->status = 'disabled';
                $c->save();
            }
            flash(__('Offer has existing redemption history. It has been safely disabled and archived instead of permanently deleted.'))->info();
        } else {
            $offer->coupons()->delete();
            $offer->delete();
            flash(__('Offer and unused coupons permanently deleted!'))->success();
        }

        return redirect()->route('admin.offers.index');
    }

    /**
     * Ajax Helper: Generate random coupon code
     */
    public function generateCode(Request $request)
    {
        $prefix = $request->input('prefix', 'OFFER');
        $code = $this->couponService->generateRandomCouponCode($prefix, 6);
        return response()->json(['success' => true, 'code' => $code]);
    }
}
