<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class Business extends Model
{
    protected $table = 'businesses';
    public $timestamps = true;
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_claimed' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /* =========================================================================
       RELATIONSHIPS
       ========================================================================= */

    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(\App\Company::class, 'company_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(\App\BusinessCategory::class, 'category_id', 'id');
    }

    public function services()
    {
        return $this->hasMany(\App\BusinessService::class, 'business_id', 'id')->where('is_active', 1);
    }

    public function allServices()
    {
        return $this->hasMany(\App\BusinessService::class, 'business_id', 'id');
    }

    public function workingHours()
    {
        return $this->hasMany(\App\BusinessHour::class, 'business_id', 'id')->orderBy('day', 'asc');
    }

    public function media()
    {
        return $this->hasMany(\App\BusinessMedia::class, 'business_id', 'id')->orderBy('sort_order', 'asc');
    }

    public function leads()
    {
        return $this->hasMany(\App\BusinessLead::class, 'business_id', 'id')->orderBy('created_at', 'desc');
    }

    public function city()
    {
        return $this->belongsTo(\App\City::class, 'city_id', 'city_id');
    }

    public function state()
    {
        return $this->belongsTo(\App\State::class, 'state_id', 'state_id');
    }

    public function country()
    {
        return $this->belongsTo(\App\Country::class, 'country_id', 'country_id');
    }

    /**
     * Cross-link: Get jobs posted by this business (via optional company link or owner user_id)
     */
    public function jobs()
    {
        if ($this->company_id) {
            return \App\Job::where('company_id', $this->company_id)->where('is_active', 1)->notExpire();
        }
        return \App\Job::whereRaw('1 = 0'); // Empty query builder
    }

    /* =========================================================================
       SCOPES
       ========================================================================= */

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
    }

    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    /* =========================================================================
       NAP & STATUS HELPERS
       ========================================================================= */

    /**
     * Formatted canonical full address string (NAP consistency)
     */
    public function getFullAddressAttribute()
    {
        $parts = [];
        if (!empty($this->address_line1)) $parts[] = $this->address_line1;
        if (!empty($this->address_line2)) $parts[] = $this->address_line2;
        if (!empty($this->area_locality)) $parts[] = $this->area_locality;

        $cityName = $this->city ? $this->city->city : null;
        $stateName = $this->state ? $this->state->state : null;
        $countryName = $this->country ? $this->country->country : null;

        if ($cityName) $parts[] = $cityName;
        if ($stateName) $parts[] = $stateName;
        if (!empty($this->postal_code)) $parts[] = $this->postal_code;
        if ($countryName) $parts[] = $countryName;

        return implode(', ', $parts);
    }

    /**
     * Clean city & area label
     */
    public function getLocationLabel()
    {
        $city = $this->city ? $this->city->city : '';
        $area = $this->area_locality ?: '';
        if ($area && $city) return $area . ', ' . $city;
        if ($city) return $city;
        if ($area) return $area;
        return 'Location Not Specified';
    }

    /**
     * Normalized Phone for Tel link
     */
    public function getCleanPhoneAttribute()
    {
        return preg_replace('/[^0-9+]/', '', $this->phone);
    }

    /**
     * Normalized WhatsApp link
     */
    public function getWhatsappUrlAttribute()
    {
        $num = $this->whatsapp_number ?: $this->phone;
        $clean = preg_replace('/[^0-9]/', '', $num);
        if (strlen($clean) == 10) {
            $clean = '91' . $clean; // Default India prefix
        }
        $msg = urlencode("Hi, I found {$this->name} on " . config('app.name', 'Jobs Portal') . ". I would like to inquire about your services.");
        return "https://wa.me/{$clean}?text={$msg}";
    }

    /**
     * Check if currently Open
     */
    public function getIsOpenNowAttribute()
    {
        $dayOfWeek = Carbon::now()->dayOfWeekIso - 1; // 0 = Mon ... 6 = Sun
        $currentTime = Carbon::now()->format('H:i:s');

        $schedule = $this->workingHours->where('day', $dayOfWeek)->first();
        if (!$schedule) return null; // No schedule set
        if ($schedule->is_closed) return false;
        if ($schedule->is_24_hours) return true;

        if ($schedule->open_time && $schedule->close_time) {
            return ($currentTime >= $schedule->open_time && $currentTime <= $schedule->close_time);
        }
        return false;
    }

    /**
     * Today's Hours string
     */
    public function getTodayHoursTextAttribute()
    {
        $dayOfWeek = Carbon::now()->dayOfWeekIso - 1;
        $schedule = $this->workingHours->where('day', $dayOfWeek)->first();
        if (!$schedule) return 'Hours not specified';
        if ($schedule->is_closed) return 'Closed Today';
        if ($schedule->is_24_hours) return 'Open 24 Hours';
        if ($schedule->open_time && $schedule->close_time) {
            $open = Carbon::createFromFormat('H:i:s', $schedule->open_time)->format('g:i A');
            $close = Carbon::createFromFormat('H:i:s', $schedule->close_time)->format('g:i A');
            return "Open {$open} - {$close}";
        }
        return 'Hours not specified';
    }

    /**
     * Logo image URL with fallback
     */
    public function getLogoUrl()
    {
        if (!empty($this->logo)) {
            if (file_exists(public_path('business_images/' . $this->logo))) {
                return asset('business_images/' . $this->logo);
            }
        }
        return asset('admin_assets/global/img/business_default.png');
    }

    /**
     * Cover image URL with fallback
     */
    public function getCoverUrl()
    {
        if (!empty($this->cover_image)) {
            if (file_exists(public_path('business_images/' . $this->cover_image))) {
                return asset('business_images/' . $this->cover_image);
            }
        }
        return null;
    }
}
