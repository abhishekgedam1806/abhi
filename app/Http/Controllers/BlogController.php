<?php

namespace App\Http\Controllers;

use App;
use App\Seo;
use App\Job;
use App\Company;
use App\FunctionalArea;
use App\City;
use App\Blog;
use App\Blog_category;
use Illuminate\Http\Request;
use App\Traits\Lang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    use Lang;

    /**
     * Blog Home / Listing
     */
    public function index()
    {
        $data['blogs'] = Blog::published()
            ->orderBy('id', 'DESC')
            ->where('lang', 'like', App::getLocale())
            ->paginate(10);
            
        $data['categories'] = Blog_category::get();
        $data['seo'] = Seo::where('page_title', 'like', 'blogs')->first();
        
        return view('blog')->with($data);
    }

    /**
     * Blog Single Article Details
     */
    public function details($slug)
    {
        $blogQuery = Blog::where('slug', $slug);
        
        // If not logged in as admin, require published status
        if (!Auth::guard('admin')->check()) {
            $blogQuery->published();
        }

        $blog = $blogQuery->first();

        if (!$blog) {
            // Fallback fuzzy search if slug is slightly formatted
            $blog = Blog::where('slug', 'like', '%' . $slug . '%')->published()->first();
        }

        if (!$blog) {
            abort(404, 'Blog post not found.');
        }

        $data['blog'] = $blog;
        $data['blog_categories'] = Blog_category::get();
        $data['categories'] = Blog_category::get();
        
        // Real related jobs from database
        $data['related_jobs'] = $blog->getRelatedJobs(4);
        
        // Recent articles for sidebar
        $data['recent_blogs'] = Blog::published()
            ->where('id', '!=', $blog->id)
            ->orderBy('id', 'DESC')
            ->take(5)
            ->get();

        // Comprehensive SEO Object
        $data['seo'] = (object)[
            'seo_title' => !empty($blog->meta_title) ? $blog->meta_title : $blog->heading,
            'seo_description' => !empty($blog->meta_descriptions) ? $blog->meta_descriptions : strip_tags(substr($blog->content, 0, 160)),
            'seo_keywords' => !empty($blog->meta_keywords) ? $blog->meta_keywords : $blog->focus_keyword,
            'seo_other' => '',
            'canonical_url' => $blog->getCanonicalUrl(),
            'robots' => ($blog->robots_index ?: 'index') . ', ' . ($blog->robots_follow ?: 'follow'),
            'og_title' => $blog->getOgTitle(),
            'og_description' => $blog->getOgDescription(),
            'og_image' => $blog->getOgImage(),
            'twitter_card' => $blog->twitter_card ?: 'summary_large_image',
            'twitter_title' => $blog->getTwitterTitle(),
            'twitter_description' => $blog->getTwitterDescription(),
            'twitter_image' => $blog->getTwitterImage(),
        ];

        return view('blog_detail')->with($data);
    }

    /**
     * Blog Category Filter Listing
     */
    public function categories($slug)
    {
        $category = Blog_category::where('slug', $slug)->firstOrFail();
        $data['category'] = $category;
        $data['blogs_categories'] = Blog_category::get();
        $data['categories'] = Blog_category::get();

        $data['blogs'] = Blog::published()
            ->whereRaw("FIND_IN_SET('{$category->id}', cate_id)")
            ->orderBy('id', 'DESC')
            ->paginate(10);

        $data['seo'] = (object)[
            'seo_title' => $category->heading . ' - Career Articles & Guides',
            'seo_description' => 'Explore the latest career guides and advice in ' . $category->heading,
            'seo_keywords' => $category->heading . ', jobs, career advice',
            'seo_other' => ''
        ];

        return view('blog_categories_details')->with($data);
    }

    /**
     * Blog Keyword Search
     */
    public function search(Request $request)
    {
        $search = trim($request->get('search', ''));
        $data['serach_result'] = $search;

        $query = Blog::published()->orderBy('id', 'DESC');
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('heading', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('focus_keyword', 'like', "%{$search}%");
            });
        }

        $data['blogs'] = $query->paginate(10);
        $data['categories'] = Blog_category::get();

        return view('blog_search')->with($data);
    }
}