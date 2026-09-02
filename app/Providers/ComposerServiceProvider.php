<?php

namespace App\Providers;

use DB;
use View;
use Cache;
use App\Language;
use App\SiteSetting;
use App\Cms;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $siteLanguages = Cache::remember('global_site_languages', 3600, function () {
            return Language::where('is_active', '=', 1)
                ->orderBy('is_default', 'desc')
                ->orderByRaw("CASE WHEN iso_code='en' THEN 0 ELSE 1 END")
                ->orderBy('lang', 'asc')
                ->get();
        });

        $siteSetting = Cache::remember('global_site_setting', 3600, function () {
            return SiteSetting::first();
        });

        $show_in_top_menu = Cache::remember('global_top_menu', 3600, function () {
            return Cms::where('show_in_top_menu', 1)->get();
        });

        $show_in_footer_menu = Cache::remember('global_footer_menu', 3600, function () {
            return Cms::where('show_in_footer_menu', 1)->get();
        });

        View::share(
            [
                'siteLanguages' => $siteLanguages,
                'siteSetting' => $siteSetting,
                'show_in_top_menu' => $show_in_top_menu,
                'show_in_footer_menu' => $show_in_footer_menu
            ]
        );
    }

    public function register()
    {
        //
    }

}
