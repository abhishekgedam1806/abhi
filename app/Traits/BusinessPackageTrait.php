<?php

namespace App\Traits;

use DB;
use Carbon\Carbon;
use App\User;
use App\Business;

trait BusinessPackageTrait
{
    /**
     * Assign a new business package to the user
     */
    public function addBusinessPackage($user, $package)
    {
        $now = Carbon::now();
        $user->business_package_id = $package->id;
        $user->business_package_start_date = $now;
        $user->business_package_end_date = $now->copy()->addDays($package->package_num_days);
        $user->business_listings_quota = $package->package_num_listings;
        $user->availed_business_listings_quota = Business::where('user_id', $user->id)->count();
        $user->save();

        if ($package->is_featured) {
            Business::where('user_id', $user->id)->update(['is_featured' => 1]);
        }
        if ($package->has_verified_badge) {
            Business::where('user_id', $user->id)->update(['verification_status' => 'verified']);
        }
    }

    /**
     * Renew or upgrade an existing business package
     */
    public function updateBusinessPackage($user, $package)
    {
        $package_end_date = $user->business_package_end_date ? Carbon::parse($user->business_package_end_date) : Carbon::now();
        if ($package_end_date->isPast()) {
            $package_end_date = Carbon::now();
        }
        $user->business_package_id = $package->id;
        $user->business_package_end_date = $package_end_date->copy()->addDays($package->package_num_days);
        $currentQuota = max(0, $user->business_listings_quota - $user->availed_business_listings_quota);
        $user->business_listings_quota = $currentQuota + $package->package_num_listings;
        $user->availed_business_listings_quota = Business::where('user_id', $user->id)->count();
        $user->save();

        if ($package->is_featured) {
            Business::where('user_id', $user->id)->update(['is_featured' => 1]);
        }
        if ($package->has_verified_badge) {
            Business::where('user_id', $user->id)->update(['verification_status' => 'verified']);
        }
    }
}
