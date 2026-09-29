<?php

namespace App\Http\Controllers;

use App\Business;
use App\BusinessCategory;
use App\BusinessLead;
use App\BusinessClaim;
use App\City;
use App\State;
use App\Country;
use App\Services\BusinessSearchEngine;
use Illuminate\Http\Request;
use Auth;
use DB;

class BusinessController extends Controller
{
    /**
     * Public Business Directory Search & Listing Page
     */
    public function listBusinesses(Request $request, $citySlug = null, $categorySlug = null)
    {
        $params = $request->all();

        // City from URL parameter if passed (e.g. /businesses/nagpur)
        if ($citySlug) {
            $cityObj = City::where('city', 'like', str_replace('-', ' ', $citySlug))->first();
            if ($cityObj) {
                $params['city_id'] = $cityObj->city_id;
            }
        }

        // Category from URL parameter if passed (e.g. /businesses/nagpur/digital-marketing)
        if ($categorySlug) {
            $params['category'] = $categorySlug;
        }

        $businesses = BusinessSearchEngine::search($params, 12);
        $categories = BusinessCategory::active()->orderBy('sort_order', 'asc')->get();
        $featuredCategories = BusinessCategory::active()->featured()->take(8)->get();

        // Selected category for SEO & UI badges
        $selectedCategory = !empty($params['category']) ? BusinessCategory::where('slug', $params['category'])->first() : null;

        // Cities for dropdown
        $cities = City::select('city_id', 'city')->take(50)->get();

        $cityName = isset($cityObj) && $cityObj ? $cityObj->city : ($citySlug ? ucfirst(str_replace('-', ' ', $citySlug)) : null);

        // SEO metadata via central SeoHelper
        if ($selectedCategory && $cityName) {
            $seo = \App\Helpers\SeoHelper::businessCategoryCity($selectedCategory->name, $cityName, $selectedCategory->slug);
        } elseif ($cityName) {
            $seo = \App\Helpers\SeoHelper::businessCity($cityName);
        } elseif ($selectedCategory) {
            $seo = \App\Helpers\SeoHelper::businessCategory($selectedCategory->name, $selectedCategory->slug);
        } else {
            $seo = \App\Helpers\SeoHelper::businessListing();
        }

        $seoTitle = $seo->seo_title;
        $seoDescription = $seo->seo_description;

        return view('business.list', compact(
            'businesses',
            'categories',
            'featuredCategories',
            'selectedCategory',
            'cities',
            'params',
            'seoTitle',
            'seoDescription',
            'seo'
        ));
    }

    /**
     * Category-specific landing route
     */
    public function listBusinessesByCategory(Request $request, $category_slug)
    {
        return $this->listBusinesses($request, null, $category_slug);
    }

    /**
     * City-specific landing route
     */
    public function listBusinessesByCity(Request $request, $city_slug)
    {
        return $this->listBusinesses($request, $city_slug, null);
    }

    /**
     * Public Business Detail Page
     */
    public function detail($slug)
    {
        $business = Business::with(['category', 'services', 'workingHours', 'media', 'city', 'state', 'country'])
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        // Increment view count (fire-and-forget)
        $business->increment('views_count');

        // Fetch related jobs posted by this business (via linked company)
        $relatedJobs = $business->jobs()->take(6)->get();

        // Similar businesses in the same category
        $similarBusinesses = Business::where('category_id', $business->category_id)
            ->where('id', '!=', $business->id)
            ->where('is_active', 1)
            ->take(4)
            ->get();

        // Generate LocalBusiness Schema JSON-LD
        $schemaJson = [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => $business->name,
            'description' => $business->short_description ?: $business->description,
            'telephone' => $business->phone,
            'email' => $business->email,
            'url' => route('business.detail', $business->slug),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $business->address_line1 . ($business->address_line2 ? ', ' . $business->address_line2 : ''),
                'addressLocality' => $business->area_locality ?: ($business->city ? $business->city->city : ''),
                'addressRegion' => $business->state ? $business->state->state : '',
                'postalCode' => $business->postal_code,
                'addressCountry' => $business->country ? $business->country->country : 'IN'
            ]
        ];

        if ($business->latitude && $business->longitude) {
            $schemaJson['geo'] = [
                '@type' => 'GeoCoordinates',
                'latitude' => $business->latitude,
                'longitude' => $business->longitude
            ];
        }

        $seo = \App\Helpers\SeoHelper::businessDetail(
            $business->name,
            $business->category ? $business->category->name : '',
            $business->city ? $business->city->city : '',
            $business->area_locality ?: '',
            $business->slug,
            false
        );

        return view('business.detail', compact(
            'business',
            'relatedJobs',
            'similarBusinesses',
            'schemaJson',
            'seo'
        ));
    }

    /**
     * Capture Lead (Call, WhatsApp, Enquiry click)
     */
    public function captureLead(Request $request, $id)
    {
        $business = Business::findOrFail($id);
        $leadType = $request->input('lead_type', 'enquiry');

        $lead = new BusinessLead();
        $lead->business_id = $business->id;
        $lead->user_id = Auth::check() ? Auth::id() : null;
        $lead->lead_type = $leadType;
        $lead->sender_name = $request->input('sender_name', Auth::check() ? Auth::user()->name : null);
        $lead->sender_phone = $request->input('sender_phone', Auth::check() ? Auth::user()->phone : null);
        $lead->sender_email = $request->input('sender_email', Auth::check() ? Auth::user()->email : null);
        $lead->message = $request->input('message');
        $lead->ip_address = $request->ip();
        $lead->user_agent = $request->userAgent();
        $lead->status = 'new';
        $lead->save();

        $business->increment('leads_count');

        // Dispatch in-app notification to business owner
        if (!empty($business->user_id)) {
            $sender = $lead->sender_name ?: __('A customer');
            \App\AppNotification::sendNotification(
                'lead_received',
                'user',
                $business->user_id,
                __('New Lead: :sender inquired on :biz', ['sender' => $sender, 'biz' => $business->name]),
                __(':sender submitted a new enquiry for ":biz". Phone: :phone', ['sender' => $sender, 'biz' => $business->name, 'phone' => $lead->sender_phone ?: 'N/A']),
                route('business.all.leads'),
                'fa-phone',
                '#F59E0B'
            );
        }

        if ($request->ajax()) {
            return response()->json(['status' => 'ok', 'message' => 'Your inquiry has been sent to ' . $business->name . '!']);
        }

        flash('Your inquiry has been sent successfully! The business will contact you soon.')->success();
        return redirect()->back();
    }

    /**
     * Submit Business Ownership Claim
     */
    public function submitClaim(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'auth_required', 'message' => 'Please login with a Business Account to claim this business listing.'], 401);
        }

        if (Auth::user()->isJobSeeker()) {
            return response()->json(['status' => 'business_account_required', 'message' => 'A Business Account is required to claim a business listing. Job Seeker accounts cannot manage business listings.'], 403);
        }

        $business = Business::findOrFail($id);

        $request->validate([
            'claimant_name' => 'required|string|max:100',
            'claimant_phone' => 'required|string|max:30',
            'claimant_email' => 'required|email|max:100',
        ]);

        $claim = new BusinessClaim();
        $claim->business_id = $business->id;
        $claim->user_id = Auth::id();
        $claim->claimant_name = $request->input('claimant_name');
        $claim->claimant_phone = $request->input('claimant_phone');
        $claim->claimant_email = $request->input('claimant_email');
        $claim->status = 'pending';
        $claim->admin_notes = $request->input('notes');
        $claim->save();

        return response()->json(['status' => 'ok', 'message' => 'Claim request submitted! Our team will verify and contact you shortly.']);
    }
}
