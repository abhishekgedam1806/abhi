<?php

namespace App\Http\Controllers;

use App\Job;
use App\Company;
use App\Business;
use App\BusinessCategory;
use App\FunctionalArea;
use App\City;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Cms;
use App\Http\Requests;

class SitemapController extends Controller
{
    /**
     * Main Sitemap Index
     */
    public function index()
    {
        $content = view('sitemaps.index')->render();
        return response($content, 200)->header('Content-Type', 'text/xml');
    }

    /**
     * Core Static & Public Informational Pages Sitemap
     */
    public function pages()
    {
        $cmsPages = Cms::all(['page_slug', 'updated_at']);
        $blogs = \DB::table('blogs')
            ->where('is_published', 1)
            ->where('robots_index', '!=', 'noindex')
            ->orderBy('id', 'desc')
            ->take(1000)
            ->get(['slug', 'updated_at']);

        $content = view('sitemaps.pages', compact('cmsPages', 'blogs'))->render();
        return response($content, 200)->header('Content-Type', 'text/xml');
    }

    /**
     * Jobs & Career Landing Pages Sitemap
     */
    public function jobs()
    {
        $jobs = Job::where('is_active', 1)
            ->where('expiry_date', '>=', Carbon::now())
            ->orderBy('id', 'desc')
            ->take(5000)
            ->get(['slug', 'updated_at']);

        $cities = City::select('city_id', 'city')->take(100)->get();
        $functionalAreas = FunctionalArea::take(100)->get();

        $content = view('sitemaps.jobs', compact('jobs', 'cities', 'functionalAreas'))->render();
        return response($content, 200)->header('Content-Type', 'text/xml');
    }

    /**
     * Employers & Companies Sitemap
     */
    public function companies()
    {
        $companies = Company::where('is_active', 1)
            ->where('verified', 1)
            ->orderBy('id', 'desc')
            ->take(5000)
            ->get(['slug', 'updated_at']);

        $content = view('sitemaps.companies', compact('companies'))->render();
        return response($content, 200)->header('Content-Type', 'text/xml');
    }

    /**
     * Local Businesses & Directory Sitemap
     */
    public function businesses()
    {
        $businesses = Business::where('is_active', 1)
            ->orderBy('id', 'desc')
            ->take(5000)
            ->get(['slug', 'updated_at']);

        $categories = BusinessCategory::active()->get(['slug', 'updated_at']);
        $cities = City::select('city_id', 'city')->take(100)->get();

        $content = view('sitemaps.businesses', compact('businesses', 'categories', 'cities'))->render();
        return response($content, 200)->header('Content-Type', 'text/xml');
    }
}
