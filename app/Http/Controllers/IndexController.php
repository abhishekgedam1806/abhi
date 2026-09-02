<?php

namespace App\Http\Controllers;

use App;
use App\Seo;
use App\Job;
use App\Company;
use App\FunctionalArea;
use App\Country;
use App\Video;
use App\Testimonial;
use App\Slider;
use App\Blog;
use Illuminate\Http\Request;
use Redirect;
use App\Traits\CompanyTrait;
use App\Traits\FunctionalAreaTrait;
use App\Traits\CityTrait;
use App\Traits\JobTrait;
use App\Traits\Active;
use App\Helpers\DataArrayHelper;

class IndexController extends Controller
{

    use CompanyTrait;
    use FunctionalAreaTrait;
    use CityTrait;
    use JobTrait;
    use Active;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locale = \App::getLocale();

        $topCompanyIds = \Cache::remember('home_top_company_ids', 1800, function () {
            return $this->getCompanyIdsAndNumJobs(16);
        });

        $topFunctionalAreaIds = \Cache::remember('home_top_functional_areas', 1800, function () {
            return $this->getFunctionalAreaIdsAndNumJobs(32);
        });

        $topIndustryIds = \Cache::remember('home_top_industries', 1800, function () {
            return $this->getIndustryIdsFromCompanies(32);
        });

        $topCityIds = \Cache::remember('home_top_cities', 1800, function () {
            return $this->getCityIdsAndNumJobs(32);
        });

        $user = \Auth::user();
        if ($user) {
            $rec = \App\Services\JobRecommendationEngine::searchAndRankJobs('', [], $user, null, 18);
            $latestJobs = collect($rec['ranked_jobs']);
        } else {
            $latestJobs = \Cache::remember('home_latest_jobs', 600, function () {
                return Job::with(['company', 'city', 'state', 'jobType', 'jobShift', 'functionalArea'])
                    ->active()
                    ->notExpire()
                    ->orderBy('id', 'desc')
                    ->limit(18)
                    ->get();
            });
        }

        $featuredJobs = \Cache::remember('home_featured_jobs', 600, function () {
            return Job::with(['company', 'city', 'state', 'jobType', 'jobShift', 'functionalArea'])
                ->active()
                ->featured()
                ->notExpire()
                ->orderBy('id', 'desc')
                ->limit(12)
                ->get();
        });

        $homeBusinesses = \Cache::remember('home_businesses_featured', 1800, function () {
            return \App\Business::with(['category', 'city'])
                ->where('is_active', 1)
                ->orderBy('is_featured', 'desc')
                ->orderByRaw("CASE WHEN verification_status = 'verified' THEN 0 ELSE 1 END")
                ->orderBy('views_count', 'desc')
                ->take(4)
                ->get();
        });

        $homeBizCategories = \Cache::remember('home_biz_categories_featured', 3600, function () {
            return \App\BusinessCategory::active()->where('is_featured', 1)->orderBy('sort_order', 'asc')->take(8)->get();
        });

        $blogs = \Cache::remember("home_blogs_{$locale}", 1800, function () use ($locale) {
            return Blog::orderBy('id', 'desc')->where('lang', 'like', $locale)->limit(3)->get();
        });

        $video = \Cache::remember('home_video', 3600, function () {
            return Video::getVideo();
        });

        $testimonials = \Cache::remember("home_testimonials_{$locale}", 3600, function () {
            return Testimonial::langTestimonials();
        });

        $functionalAreas = \Cache::remember("home_fa_array_{$locale}", 3600, function () {
            return DataArrayHelper::langFunctionalAreasArray();
        });

        $countries = \Cache::remember("home_countries_array_{$locale}", 3600, function () {
            return DataArrayHelper::langCountriesArray();
        });

        $sliders = \Cache::remember("home_sliders_{$locale}", 3600, function () {
            return Slider::langSliders();
        });

        $seo = \Cache::remember('home_seo', 3600, function () {
            return SEO::where('seo.page_title', 'like', 'front_index_page')->first();
        });

        return view('welcome')
            ->with('topCompanyIds', $topCompanyIds)
            ->with('topFunctionalAreaIds', $topFunctionalAreaIds)
            ->with('topCityIds', $topCityIds)
            ->with('topIndustryIds', $topIndustryIds)
            ->with('featuredJobs', $featuredJobs)
            ->with('latestJobs', $latestJobs)
            ->with('homeBusinesses', $homeBusinesses)
            ->with('homeBizCategories', $homeBizCategories)
            ->with('blogs', $blogs)
            ->with('functionalAreas', $functionalAreas)
            ->with('countries', $countries)
            ->with('sliders', $sliders)
            ->with('video', $video)
            ->with('testimonials', $testimonials)
            ->with('seo', $seo);
    }

    public function setLocale(Request $request)
    {
        $locale = $request->input('locale');
        $return_url = $request->input('return_url');
        $is_rtl = $request->input('is_rtl');
        $localeDir = ((bool) $is_rtl) ? 'rtl' : 'ltr';

        session(['locale' => $locale]);
        session(['localeDir' => $localeDir]);

        return Redirect::to($return_url);
    }

}
