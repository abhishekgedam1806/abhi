<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteMenuItem extends Model
{
    protected $table = 'site_menu_items';

    protected $fillable = [
        'menu_type',
        'title',
        'url',
        'icon',
        'target',
        'order_num',
        'is_active',
        'audience',
        'custom_class'
    ];

    protected $casts = [
        'is_active' => 'integer',
        'order_num' => 'integer'
    ];

    public static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            static::clearMenuCache();
        });

        static::deleted(function ($model) {
            static::clearMenuCache();
        });
    }

    public static function clearMenuCache()
    {
        Cache::forget('site_menu_header');
        Cache::forget('site_menu_footer_col1');
        Cache::forget('site_menu_footer_col2');
        Cache::forget('site_menu_footer_col3');
        Cache::forget('site_menu_footer_cities');
    }

    public static function getHeaderItems()
    {
        return Cache::remember('site_menu_header', 3600, function () {
            return static::where('menu_type', 'header')
                ->where('is_active', 1)
                ->orderBy('order_num', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        });
    }

    public static function getFooterCol1Items()
    {
        return Cache::remember('site_menu_footer_col1', 3600, function () {
            return static::where('menu_type', 'footer_col1')
                ->where('is_active', 1)
                ->orderBy('order_num', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        });
    }

    public static function getFooterCol2Items()
    {
        return Cache::remember('site_menu_footer_col2', 3600, function () {
            return static::where('menu_type', 'footer_col2')
                ->where('is_active', 1)
                ->orderBy('order_num', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        });
    }

    public static function getFooterCol3Items()
    {
        return Cache::remember('site_menu_footer_col3', 3600, function () {
            return static::where('menu_type', 'footer_col3')
                ->where('is_active', 1)
                ->orderBy('order_num', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        });
    }

    public static function getFooterCitiesItems()
    {
        return Cache::remember('site_menu_footer_cities', 3600, function () {
            return static::where('menu_type', 'footer_cities')
                ->where('is_active', 1)
                ->orderBy('order_num', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        });
    }

    /**
     * Resolves dynamic URL or route names to a real URL
     */
    public function getFormattedUrl()
    {
        $url = trim($this->url);
        if (empty($url)) {
            return url('/');
        }

        if (preg_match('#^https?://#i', $url) || strpos($url, '//') === 0) {
            return $url;
        }

        if (strpos($url, '/') === 0) {
            return url($url);
        }

        return url('/' . $url);
    }
}
