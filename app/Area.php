<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'areas';
    
    protected $fillable = [
        'city_id',
        'area_name',
        'pincode',
        'latitude',
        'longitude',
        'is_active'
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'area_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Get areas by city ID
     */
    public static function getAreasByCityId($cityId)
    {
        return self::where('city_id', $cityId)
            ->where('is_active', 1)
            ->orderBy('area_name', 'asc')
            ->get();
    }
}
