<?php

namespace App\Http\Controllers;

use App\Package;
use Auth;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    /**
     * Display the multi-tier Pricing & Membership Hub
     */
    public function index(Request $request)
    {
        // 1. Fetch packages by role
        $seekerPackages = Package::where('package_for', 'like', 'job_seeker')
            ->orderBy('package_price', 'ASC')
            ->get();

        $employerPackages = Package::where('package_for', 'like', 'employer')
            ->orderBy('package_price', 'ASC')
            ->get();

        $businessPackages = Package::where('package_for', 'like', 'business')
            ->orderBy('package_price', 'ASC')
            ->get();

        // 2. Determine default tab based on user session or query param
        $activeTab = $request->get('tab');
        if (!$activeTab) {
            if (Auth::guard('company')->check()) {
                $activeTab = 'employers';
            } elseif (Auth::check()) {
                $activeTab = 'candidates';
            } else {
                $activeTab = 'candidates';
            }
        }

        // 3. User's active package if logged in
        $currentPackage = null;
        if (Auth::guard('company')->check()) {
            $currentPackage = Auth::guard('company')->user()->getPackage();
        } elseif (Auth::check()) {
            $currentPackage = Auth::user()->getPackage();
        }

        return view('pricing.index')
            ->with('seekerPackages', $seekerPackages)
            ->with('employerPackages', $employerPackages)
            ->with('businessPackages', $businessPackages)
            ->with('activeTab', $activeTab)
            ->with('currentPackage', $currentPackage);
    }
}
