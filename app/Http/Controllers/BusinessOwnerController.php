<?php

namespace App\Http\Controllers;

use App\Business;
use App\BusinessCategory;
use App\BusinessService;
use App\BusinessHour;
use App\BusinessLead;
use App\Country;
use App\State;
use App\City;
use App\Package;
use App\Helpers\DataArrayHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use File;
use ImgUploader;

class BusinessOwnerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'business.auth']);
    }

    /**
     * Dedicated Business Dashboard
     */
    public function dashboard()
    {
        $userId = Auth::id();
        $businessIds = Business::where('user_id', $userId)->pluck('id')->toArray();

        $totalBusinesses = count($businessIds);
        $verifiedBusinesses = Business::where('user_id', $userId)->where('verification_status', 'verified')->count();
        $pendingBusinesses = Business::where('user_id', $userId)->where('verification_status', 'pending')->count();
        
        $totalLeads = BusinessLead::whereIn('business_id', $businessIds)->count();
        $callLeads = BusinessLead::whereIn('business_id', $businessIds)->where('lead_type', 'call')->count();
        $whatsappLeads = BusinessLead::whereIn('business_id', $businessIds)->where('lead_type', 'whatsapp')->count();
        $enquiryLeads = BusinessLead::whereIn('business_id', $businessIds)->where('lead_type', 'inquiry')->count();

        $recentLeads = BusinessLead::with('business')
            ->whereIn('business_id', $businessIds)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $businesses = Business::with(['category', 'city', 'services'])
            ->where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->take(4)
            ->get();

        $user = Auth::user();
        $userPackage = $user->business_package_id ? Package::find($user->business_package_id) : null;
        $totalQuota = $user->business_listings_quota ?: 1;

        return view('business.dashboard.dashboard', compact(
            'totalBusinesses',
            'verifiedBusinesses',
            'pendingBusinesses',
            'totalLeads',
            'callLeads',
            'whatsappLeads',
            'enquiryLeads',
            'recentLeads',
            'businesses',
            'userPackage',
            'totalQuota'
        ));
    }

    /**
     * Owner's list of businesses
     */
    public function myBusinesses()
    {
        $businesses = Business::with(['category', 'city', 'services', 'leads'])
            ->where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('business.dashboard.index', compact('businesses'));
    }

    /**
     * Create Business Form
     */
    public function create()
    {
        $user = Auth::user();
        $totalAllowed = $user->business_listings_quota ?: 1;
        $totalUsed = Business::where('user_id', $user->id)->count();

        // Update counted quota
        $user->availed_business_listings_quota = $totalUsed;
        $user->save();

        if ($totalUsed >= $totalAllowed) {
            flash("You have reached your listing quota ({$totalUsed}/{$totalAllowed}). Please upgrade your Business Package to list more businesses.")->warning();
            return redirect()->route('business.packages');
        }

        $categories = BusinessCategory::active()->orderBy('name', 'asc')->get();
        $countries = DataArrayHelper::langCountriesArray();

        return view('business.dashboard.create', compact('categories', 'countries'));
    }

    /**
     * Store Business
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $totalAllowed = $user->business_listings_quota ?: 1;
        $totalUsed = Business::where('user_id', $user->id)->count();

        if ($totalUsed >= $totalAllowed) {
            flash("You have reached your listing quota ({$totalUsed}/{$totalAllowed}). Please upgrade your Business Package to list more businesses.")->warning();
            return redirect()->route('business.packages');
        }

        $request->validate([
            'name' => 'required|string|max:191',
            'category_id' => 'required|integer',
            'phone' => 'required|string|max:30',
            'address_line1' => 'required|string|max:255',
            'city_id' => 'required|integer',
            'postal_code' => 'required|string|max:20',
        ]);

        $slug = Str::slug($request->name);
        $count = Business::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        $business = new Business();
        $business->user_id = Auth::id();
        $business->name = $request->name;
        $business->slug = $slug;
        $business->category_id = $request->category_id;
        $business->phone = $request->phone;
        $business->phone_secondary = $request->phone_secondary;
        $business->whatsapp_number = $request->whatsapp_number;
        $business->email = $request->email;
        $business->website = $request->website;
        $business->address_line1 = $request->address_line1;
        $business->address_line2 = $request->address_line2;
        $business->area_locality = $request->area_locality;
        $business->city_id = $request->city_id;
        $business->state_id = $request->state_id;
        $business->country_id = $request->country_id ?: 1;
        $business->postal_code = $request->postal_code;
        $business->latitude = $request->latitude;
        $business->longitude = $request->longitude;
        $business->short_description = $request->short_description;
        $business->description = $request->description;
        $business->year_established = $request->year_established;
        $business->business_type = $request->business_type;
        $business->is_active = 1;

        // Check if user has featured package
        if ($user->business_package_id > 0) {
            $pkg = Package::find($user->business_package_id);
            if ($pkg && $pkg->is_featured) {
                $business->is_featured = 1;
            }
            if ($pkg && $pkg->has_verified_badge) {
                $business->verification_status = 'verified';
            } else {
                $business->verification_status = 'pending';
            }
        } else {
            $business->verification_status = 'pending';
        }

        // Upload Logo
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = 'biz_logo_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('business_images'), $fileName);
            $business->logo = $fileName;
        }

        // Upload Cover
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $fileName = 'biz_cover_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('business_images'), $fileName);
            $business->cover_image = $fileName;
        }

        $business->save();

        // Save Services
        if ($request->has('services') && is_array($request->services)) {
            foreach ($request->services as $srvName) {
                if (!empty(trim($srvName))) {
                    BusinessService::create([
                        'business_id' => $business->id,
                        'service_name' => trim($srvName),
                        'is_active' => 1
                    ]);
                }
            }
        }

        // Save Working Hours
        if ($request->has('hours') && is_array($request->hours)) {
            foreach ($request->hours as $day => $hData) {
                BusinessHour::create([
                    'business_id' => $business->id,
                    'day' => (int)$day,
                    'open_time' => !empty($hData['is_closed']) ? null : ($hData['open'] ?? '09:00:00'),
                    'close_time' => !empty($hData['is_closed']) ? null : ($hData['close'] ?? '20:00:00'),
                    'is_closed' => !empty($hData['is_closed']) ? 1 : 0,
                    'is_24_hours' => !empty($hData['is_24_hours']) ? 1 : 0,
                ]);
            }
        } else {
            // Default Mon-Sat 09:00 - 20:00
            for ($d = 0; $d <= 6; $d++) {
                BusinessHour::create([
                    'business_id' => $business->id,
                    'day' => $d,
                    'open_time' => ($d === 6) ? null : '09:00:00',
                    'close_time' => ($d === 6) ? null : '20:00:00',
                    'is_closed' => ($d === 6) ? 1 : 0,
                    'is_24_hours' => 0,
                ]);
            }
        }

        flash('Your business listing has been created and submitted for verification!')->success();
        return redirect()->route('my.businesses');
    }

    /**
     * Edit Business
     */
    public function edit($id)
    {
        $business = Business::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['services', 'workingHours'])
            ->firstOrFail();

        $categories = BusinessCategory::active()->orderBy('name', 'asc')->get();
        $countries = DataArrayHelper::langCountriesArray();

        return view('business.dashboard.edit', compact('business', 'categories', 'countries'));
    }

    /**
     * Update Business
     */
    public function update(Request $request, $id)
    {
        $business = Business::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:191',
            'category_id' => 'required|integer',
            'phone' => 'required|string|max:30',
            'address_line1' => 'required|string|max:255',
            'city_id' => 'required|integer',
            'postal_code' => 'required|string|max:20',
        ]);

        $business->name = $request->name;
        $business->category_id = $request->category_id;
        $business->phone = $request->phone;
        $business->phone_secondary = $request->phone_secondary;
        $business->whatsapp_number = $request->whatsapp_number;
        $business->email = $request->email;
        $business->website = $request->website;
        $business->address_line1 = $request->address_line1;
        $business->address_line2 = $request->address_line2;
        $business->area_locality = $request->area_locality;
        $business->city_id = $request->city_id;
        $business->state_id = $request->state_id;
        $business->country_id = $request->country_id ?: 1;
        $business->postal_code = $request->postal_code;
        $business->latitude = $request->latitude;
        $business->longitude = $request->longitude;
        $business->short_description = $request->short_description;
        $business->description = $request->description;
        $business->year_established = $request->year_established;
        $business->business_type = $request->business_type;

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = 'biz_logo_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('business_images'), $fileName);
            $business->logo = $fileName;
        }

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $fileName = 'biz_cover_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('business_images'), $fileName);
            $business->cover_image = $fileName;
        }

        $business->save();

        // Update Services
        BusinessService::where('business_id', $business->id)->delete();
        if ($request->has('services') && is_array($request->services)) {
            foreach ($request->services as $srvName) {
                if (!empty(trim($srvName))) {
                    BusinessService::create([
                        'business_id' => $business->id,
                        'service_name' => trim($srvName),
                        'is_active' => 1
                    ]);
                }
            }
        }

        // Update Hours
        BusinessHour::where('business_id', $business->id)->delete();
        if ($request->has('hours') && is_array($request->hours)) {
            foreach ($request->hours as $day => $hData) {
                BusinessHour::create([
                    'business_id' => $business->id,
                    'day' => (int)$day,
                    'open_time' => !empty($hData['is_closed']) ? null : ($hData['open'] ?? '09:00:00'),
                    'close_time' => !empty($hData['is_closed']) ? null : ($hData['close'] ?? '20:00:00'),
                    'is_closed' => !empty($hData['is_closed']) ? 1 : 0,
                    'is_24_hours' => !empty($hData['is_24_hours']) ? 1 : 0,
                ]);
            }
        }

        flash('Business details updated successfully!')->success();
        return redirect()->route('my.businesses');
    }

    /**
     * Leads received for a specific business
     */
    public function leads($id)
    {
        $business = Business::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $leads = BusinessLead::where('business_id', $business->id)
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('business.dashboard.leads', compact('business', 'leads'));
    }

    /**
     * All leads inbox across all owner's businesses
     */
    public function allLeads(Request $request)
    {
        $userId = Auth::id();
        $businessIds = Business::where('user_id', $userId)->pluck('id')->toArray();

        $query = BusinessLead::with('business')
            ->whereIn('business_id', $businessIds);

        if ($request->filled('lead_type')) {
            $query->where('lead_type', $request->lead_type);
        }
        if ($request->filled('business_id') && in_array($request->business_id, $businessIds)) {
            $query->where('business_id', $request->business_id);
        }

        $leads = $query->orderBy('created_at', 'desc')->paginate(15);
        $myBusinesses = Business::where('user_id', $userId)->pluck('name', 'id')->toArray();

        return view('business.dashboard.leads_all', compact('leads', 'myBusinesses'));
    }

    /**
     * Dedicated Business Owner Profile Page
     */
    public function profile()
    {
        $user = Auth::user();
        $userPackage = $user->business_package_id ? Package::find($user->business_package_id) : null;
        $totalBusinesses = Business::where('user_id', $user->id)->count();
        $totalQuota = $user->business_listings_quota ?: 1;

        return view('business.dashboard.profile', compact('user', 'userPackage', 'totalBusinesses', 'totalQuota'));
    }

    /**
     * Update Business Owner Profile Settings
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:191|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:25',
            'password' => 'nullable|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ]);

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->name = trim($request->input('first_name') . ' ' . $request->input('last_name'));
        $user->email = $request->input('email');
        if ($request->filled('phone')) {
            $user->phone = $request->input('phone');
        }

        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->input('password'));
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = 'user_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('user_images'), $fileName);
            $user->image = $fileName;
        }

        $user->save();

        flash('Business Owner Profile updated successfully!')->success();
        return redirect()->route('business.owner.profile');
    }
}
