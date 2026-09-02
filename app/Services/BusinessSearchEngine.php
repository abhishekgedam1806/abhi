<?php

namespace App\Services;

use App\Business;
use App\BusinessCategory;
use Carbon\Carbon;
use DB;

class BusinessSearchEngine
{
    /**
     * Search and rank businesses strictly isolated from job search.
     *
     * @param array $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function search(array $params = [], $perPage = 12)
    {
        $query = Business::with(['category', 'city', 'state', 'services', 'workingHours'])
            ->where('is_active', 1);

        $search = trim($params['search'] ?? '');
        $categorySlug = trim($params['category'] ?? '');
        $cityId = $params['city_id'] ?? null;
        $stateId = $params['state_id'] ?? null;
        $location = trim($params['location'] ?? '');
        $userLat = isset($params['lat']) && is_numeric($params['lat']) ? (float)$params['lat'] : null;
        $userLng = isset($params['lng']) && is_numeric($params['lng']) ? (float)$params['lng'] : null;
        $radiusKm = isset($params['radius']) && is_numeric($params['radius']) ? (float)$params['radius'] : null;
        $openNow = !empty($params['open_now']);
        $verifiedOnly = !empty($params['verified_only']);
        $featuredOnly = !empty($params['featured_only']);
        $sortBy = $params['sort_by'] ?? 'relevance'; // relevance, distance, newest, name

        // 1. Keyword / Intent Search
        if (!empty($search)) {
            $terms = array_filter(explode(' ', strtolower($search)));
            $query->where(function ($q) use ($search, $terms) {
                // Exact or partial name match
                $q->where('businesses.name', 'LIKE', "%{$search}%")
                  ->orWhere('businesses.short_description', 'LIKE', "%{$search}%")
                  ->orWhere('businesses.description', 'LIKE', "%{$search}%")
                  ->orWhere('businesses.area_locality', 'LIKE', "%{$search}%");

                // Match in category name
                $q->orWhereHas('category', function ($catQ) use ($search) {
                    $catQ->where('name', 'LIKE', "%{$search}%");
                });

                // Match in services
                $q->orWhereHas('services', function ($srvQ) use ($search) {
                    $srvQ->where('service_name', 'LIKE', "%{$search}%")
                         ->orWhere('service_description', 'LIKE', "%{$search}%");
                });
            });
        }

        // 2. Category Filter
        if (!empty($categorySlug)) {
            $cat = BusinessCategory::where('slug', $categorySlug)->first();
            if ($cat) {
                $query->where('category_id', $cat->id);
            }
        }

        // 3. Location Filter (City / State / Locality)
        if (!empty($cityId)) {
            $query->where('city_id', $cityId);
        } elseif (!empty($location)) {
            $query->where(function ($q) use ($location) {
                $q->where('area_locality', 'LIKE', "%{$location}%")
                  ->orWhere('address_line1', 'LIKE', "%{$location}%")
                  ->orWhereHas('city', function ($cQ) use ($location) {
                      $cQ->where('city', 'LIKE', "%{$location}%");
                  });
            });
        }

        if (!empty($stateId)) {
            $query->where('state_id', $stateId);
        }

        // 4. Distance / Radius ("Near Me") Calculation
        $hasCoords = ($userLat !== null && $userLng !== null && $userLat != 0 && $userLng != 0);
        if ($hasCoords) {
            // Haversine formula
            $haversine = "(6371 * acos(least(1.0, greatest(-1.0, cos(radians({$userLat})) * cos(radians(latitude)) * cos(radians(longitude) - radians({$userLng})) + sin(radians({$userLat})) * sin(radians(latitude))))))";
            $query->selectRaw("businesses.*, {$haversine} AS distance");

            if ($radiusKm && $radiusKm > 0) {
                $query->having('distance', '<=', $radiusKm);
            }
        }

        // 5. Verified Only
        if ($verifiedOnly) {
            $query->where('verification_status', 'verified');
        }

        // 6. Featured Only
        if ($featuredOnly) {
            $query->where('is_featured', 1);
        }

        // 7. Open Now Filter
        if ($openNow) {
            $dayOfWeek = Carbon::now()->dayOfWeekIso - 1; // 0 = Mon
            $currentTime = Carbon::now()->format('H:i:s');
            $query->whereHas('workingHours', function ($whQ) use ($dayOfWeek, $currentTime) {
                $whQ->where('day', $dayOfWeek)
                    ->where('is_closed', 0)
                    ->where(function ($timeQ) use ($currentTime) {
                        $timeQ->where('is_24_hours', 1)
                              ->orWhere(function ($openQ) use ($currentTime) {
                                  $openQ->where('open_time', '<=', $currentTime)
                                        ->where('close_time', '>=', $currentTime);
                              });
                    });
            });
        }

        // 8. Sorting / Ranking
        if ($hasCoords && ($sortBy === 'distance' || $sortBy === 'relevance')) {
            // Rank by verification + featured + distance
            $query->orderByRaw("CASE WHEN is_featured = 1 THEN 0 ELSE 1 END")
                  ->orderByRaw("CASE WHEN verification_status = 'verified' THEN 0 ELSE 1 END")
                  ->orderBy('distance', 'asc');
        } elseif ($sortBy === 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sortBy === 'name') {
            $query->orderBy('name', 'asc');
        } else {
            // Standard Relevance: Featured first, then Verified, then newest
            $query->orderBy('is_featured', 'desc')
                  ->orderByRaw("CASE WHEN verification_status = 'verified' THEN 0 ELSE 1 END")
                  ->orderBy('views_count', 'desc')
                  ->orderBy('id', 'desc');
        }

        return $query->paginate($perPage);
    }
}
