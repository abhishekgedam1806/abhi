<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Blog;
use App\Blog_category;
use App\City;
use App\FunctionalArea;
use App\Helpers\DataArrayHelper;
use App\Services\AI\AIService;
use App\Services\AI\AIPrompts;
use Image;
use File;

class BlogsController extends Base
{
    /**
     * Display all blogs with search, status, category, and language filters
     */
    public function index(Request $request)
    {
        $query = Blog::orderBy('id', 'DESC');

        // Search Filter
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('heading', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('focus_keyword', 'like', "%{$search}%");
            });
        }

        // Category Filter
        if ($request->filled('category_id')) {
            $catId = $request->category_id;
            $query->whereRaw("FIND_IN_SET('{$catId}', cate_id)");
        }

        // Status Filter
        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', 1);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', 0);
            }
        }

        // Language Filter
        if ($request->filled('lang')) {
            $query->where('lang', $request->lang);
        }

        $blogs = $query->paginate(15);
        $categories = Blog_category::get();
        $languages = DataArrayHelper::languagesNativeCodeArray();

        return view('admin/blogs/blogs', compact('blogs', 'categories', 'languages'));
    }

    /**
     * Show the Create Blog Form
     */
    public function show_form()
    {
        $languages = DataArrayHelper::languagesNativeCodeArray();
        $categories = Blog_category::get();
        $lang = config('default_lang', 'en');
        $direction = \MiscHelper::getLangDirection($lang);
        return view('admin/blogs/post_form', compact('categories', 'languages', 'lang', 'direction'));
    }

    /**
     * Store new blog
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'slug' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:3072',
        ], [
            'title.required' => 'The blog title is required.',
            'slug.required' => 'The slug is required.',
            'content.required' => 'The content is required.',
        ]);

        $imagename = '';
        $image = $request->file('image');
        if ($image) {
            $nameonly = preg_replace('/\..+$/', '', $image->getClientOriginalName());
            $nameonly = preg_replace('/[^a-zA-Z0-9_-]/', '', $nameonly);
            $imagename = $nameonly . '_' . time() . '.' . $image->getClientOriginalExtension();

            $destinationPathThumb = public_path('/uploads/blogs/thumbnail');
            if (!File::exists($destinationPathThumb)) {
                File::makeDirectory($destinationPathThumb, 0775, true);
            }
            $img = Image::make($image->getRealPath());
            $img->resize(450, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($destinationPathThumb . '/' . $imagename);

            $destinationPath = public_path('/uploads/blogs/');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true);
            }
            $image->move($destinationPath, $imagename);
        }

        $page_slug = !empty($request->slug) ? $request->slug : str_slug($request->title);
        $slugs = unique_slug($page_slug, 'blogs', 'slug');

        $category_Ids = '';
        if ($request->has('cate_id') && is_array($request->cate_id)) {
            $category_Ids = implode(",", $request->cate_id);
        }

        // Determine Publishing Status
        $isPublished = 1;
        if ($request->input('action_type') === 'draft' || $request->input('is_published') === '0' || $request->has('save_draft')) {
            $isPublished = 0;
        }

        $blog = new Blog();
        $blog->heading = $request->title;
        $blog->slug = $slugs;
        $blog->cate_id = $category_Ids;
        $blog->content = $request->content;
        $blog->image = $imagename;
        $blog->featured = $request->input('featured', 0);
        $blog->is_published = $isPublished;
        $blog->author_name = $request->input('author_name', 'Abhishek Sharma');
        $blog->author_title = $request->input('author_title', 'Career Consultant & Lead Editor');
        $blog->author_bio = $request->input('author_bio');
        $blog->meta_title = $request->input('meta_title');
        $blog->meta_keywords = $request->input('meta_keywords');
        $blog->meta_descriptions = $request->input('meta_descriptions');
        $blog->canonical_url = $request->input('canonical_url');
        $blog->robots_index = $request->input('robots_index', 'index');
        $blog->robots_follow = $request->input('robots_follow', 'follow');
        $blog->og_title = $request->input('og_title');
        $blog->og_description = $request->input('og_description');
        $blog->og_image = $request->input('og_image');
        $blog->twitter_card = $request->input('twitter_card', 'summary_large_image');
        $blog->twitter_title = $request->input('twitter_title');
        $blog->twitter_description = $request->input('twitter_description');
        $blog->twitter_image = $request->input('twitter_image');
        $blog->save();

        $statusText = $isPublished ? 'published' : 'saved as draft';
        $request->session()->flash('message.added', 'success');
        $request->session()->flash('message.content', "Blog was successfully {$statusText}!");

        return redirect('/admin/blog');
    }

    /**
     * Show Edit Blog Form
     */
    public function get_blog($id = '')
    {
        if ($id != '') {
            $languages = DataArrayHelper::languagesNativeCodeArray();
            $blog = Blog::findOrFail($id);
            $categories = Blog_category::get();
            $lang = $blog->lang ?: config('default_lang', 'en');
            $direction = \MiscHelper::getLangDirection($lang);
            return view('admin/blogs/update_form', compact('blog', 'categories', 'languages', 'lang', 'direction'));
        }
        return redirect('/admin/blog');
    }

    public function get_blog_by_id($id = '')
    {
        if ($id != '') {
            $row = Blog::findOrFail($id);
            return response()->json($row);
        }
    }

    /**
     * Update existing blog
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'title_update' => 'required|max:255',
            'slug_update' => 'required|max:255',
            'content_update' => 'required',
            'imageupdate' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:3072',
        ], [
            'title_update.required' => 'The title field is required.',
            'slug_update.required' => 'The slug field is required.',
            'content_update.required' => 'The content field is required.',
        ]);

        $blog = Blog::findOrFail($request->id);

        $category_Ids = '';
        if ($request->has('cate_id_update') && is_array($request->cate_id_update)) {
            $category_Ids = implode(",", $request->cate_id_update);
        }

        $image = $request->file('imageupdate');
        if ($image) {
            $nameonly = preg_replace('/\..+$/', '', $image->getClientOriginalName());
            $nameonly = preg_replace('/[^a-zA-Z0-9_-]/', '', $nameonly);
            $imagename = $nameonly . '_' . time() . '.' . $image->getClientOriginalExtension();

            $destinationPathThumb = public_path('/uploads/blogs/thumbnail');
            if (!File::exists($destinationPathThumb)) {
                File::makeDirectory($destinationPathThumb, 0775, true);
            }
            $img = Image::make($image->getRealPath());
            $img->resize(450, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($destinationPathThumb . '/' . $imagename);

            $destinationPath = public_path('/uploads/blogs/');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true);
            }
            $image->move($destinationPath, $imagename);
            $blog->image = $imagename;
        }

        // Determine Publishing Status
        $isPublished = $blog->is_published;
        if ($request->input('action_type') === 'draft' || $request->has('save_draft')) {
            $isPublished = 0;
        } elseif ($request->input('action_type') === 'publish' || $request->has('publish_post')) {
            $isPublished = 1;
        } elseif ($request->has('is_published')) {
            $isPublished = $request->input('is_published');
        }

        $blog->heading = $request->title_update;
        $blog->slug = $request->slug_update;
        $blog->cate_id = $category_Ids;
        $blog->content = $request->content_update;
        $blog->lang = $request->input('lang', 'en');
        $blog->featured = $request->input('featured', 0);
        $blog->is_published = $isPublished;
        $blog->author_name = $request->input('author_name', 'Abhishek Sharma');
        $blog->author_title = $request->input('author_title', 'Career Consultant & Lead Editor');
        $blog->author_bio = $request->input('author_bio');
        $blog->focus_keyword = $request->input('focus_keyword');
        $blog->meta_title = $request->input('meta_title_update');
        $blog->meta_keywords = $request->input('meta_keywords_update');
        $blog->meta_descriptions = $request->input('meta_descriptions_update');
        $blog->canonical_url = $request->input('canonical_url');
        $blog->robots_index = $request->input('robots_index', 'index');
        $blog->robots_follow = $request->input('robots_follow', 'follow');
        $blog->og_title = $request->input('og_title');
        $blog->og_description = $request->input('og_description');
        $blog->og_image = $request->input('og_image');
        $blog->twitter_card = $request->input('twitter_card', 'summary_large_image');
        $blog->twitter_title = $request->input('twitter_title');
        $blog->twitter_description = $request->input('twitter_description');
        $blog->twitter_image = $request->input('twitter_image');
        $blog->save();

        $request->session()->flash('message.updated', 'success');
        $request->session()->flash('message.content', 'Blog was successfully updated!');

        return redirect('/admin/blog');
    }

    /**
     * Delete Blog
     */
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        
        // Remove image files
        if (!empty($blog->image)) {
            $filename = public_path('/uploads/blogs/' . $blog->image);
            $filenamethumb = public_path('/uploads/blogs/thumbnail/' . $blog->image);
            File::delete([$filename, $filenamethumb]);
        }

        $blog->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Blog deleted successfully']);
        }

        return redirect('/admin/blog')->with('flash_message', 'Blog deleted successfully');
    }

    /**
     * Instant AJAX Status Toggle (Draft <-> Published)
     */
    public function toggleStatus($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->is_published = $blog->is_published ? 0 : 1;
        $blog->save();

        return response()->json([
            'success' => true,
            'is_published' => (int)$blog->is_published,
            'status_label' => $blog->is_published ? 'Published' : 'Draft',
            'message' => 'Blog status updated successfully'
        ]);
    }

    /**
     * Remove Blog Featured Image
     */
    public function remove_blog_feature_image($id)
    {
        $blog = Blog::findOrFail($id);
        if (!empty($blog->image)) {
            $filename = public_path('/uploads/blogs/' . $blog->image);
            $filenamethumb = public_path('/uploads/blogs/thumbnail/' . $blog->image);
            File::delete([$filename, $filenamethumb]);
            $blog->image = '';
            $blog->save();
        }
        return response()->json(['status' => 'done']);
    }

    /**
     * Optional AI SEO Assistant endpoint (Never overwrites DB automatically)
     */
    public function aiSuggestSeo(Request $request)
    {
        $type = $request->input('type', 'meta_title'); // meta_title, meta_description, focus_keyword, slug, internal_links
        $title = trim($request->input('title', ''));
        $content = strip_tags(substr($request->input('content', ''), 0, 1500));
        $keyword = trim($request->input('focus_keyword', ''));

        if (empty($title) && empty($content)) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide at least a Blog Title or Content for AI analysis.'
            ], 422);
        }

        try {
            $aiService = new AIService();
            $provider = $aiService->getActiveProvider();

            if (!$provider) {
                // Fallback smart rule-based suggestion if AI is not configured
                return response()->json([
                    'success' => true,
                    'suggestion' => $this->generateFallbackSeo($type, $title, $content, $keyword),
                    'provider' => 'Rule Engine (Offline)'
                ]);
            }

            $systemPrompt = "You are an expert SEO specialist for a modern jobs and career portal in India. Generate strict, high-converting, factual SEO metadata. Do not invent fake claims, fake companies, or fake stats. Return ONLY the requested text without markdown formatting or quotation marks.";

            switch ($type) {
                case 'meta_title':
                    $userPrompt = "Generate an optimized SEO Meta Title (maximum 58 characters) for this career/job blog post:\nTitle: {$title}\nFocus Keyword: {$keyword}\nSummary: {$content}\nFormat: Compelling, keyword-rich, under 60 chars. Return only the title.";
                    break;
                case 'meta_description':
                    $userPrompt = "Generate an engaging SEO Meta Description (between 120 and 150 characters) with a clear call-to-action for this career/job blog post:\nTitle: {$title}\nFocus Keyword: {$keyword}\nSummary: {$content}\nFormat: Under 155 chars, natural keywords. Return only the description.";
                    break;
                case 'focus_keyword':
                    $userPrompt = "Extract 1-2 top high-intent SEO focus keywords for this job/career article:\nTitle: {$title}\nContent: {$content}\nFormat: Comma-separated phrase(s), e.g. 'Software Engineer Jobs, Career Guide'. Return only the keywords.";
                    break;
                case 'slug':
                    $userPrompt = "Generate a clean, SEO-friendly URL slug (lowercase, hyphens only, no stop words) for: '{$title}'. Return only the slug.";
                    break;
                case 'internal_links':
                    // Fetch real categories and cities from database
                    $cities = City::take(6)->pluck('city')->toArray();
                    $categories = FunctionalArea::take(6)->pluck('functional_area')->toArray();
                    return response()->json([
                        'success' => true,
                        'suggestion' => [
                            'cities' => array_map(function($c) { return ['title' => "Jobs in {$c}", 'url' => url('/jobs-in-' . str_slug($c))]; }, $cities),
                            'categories' => array_map(function($cat) { return ['title' => "{$cat} Jobs", 'url' => route('job.list') . '?functional_area=' . urlencode($cat)]; }, $categories),
                        ],
                        'provider' => 'Database Internal Links'
                    ]);
                default:
                    $userPrompt = "Improve SEO for title: {$title}";
            }

            $fullPrompt = "System: {$systemPrompt}\n\nTask: {$userPrompt}";
            $driver = $aiService->getDriver($provider);
            $response = $driver->generateText($fullPrompt, [
                'temperature' => 0.3,
                'max_tokens' => 200
            ]);

            $textResult = is_array($response) ? ($response['text'] ?? '') : (is_object($response) ? ($response->text ?? '') : (string)$response);
            $cleanSuggestion = trim(str_replace(['"', "'", '`'], '', $textResult));

            return response()->json([
                'success' => true,
                'suggestion' => $cleanSuggestion,
                'provider' => $provider->name
            ]);

        } catch (\Exception $e) {
            // Fallback gracefully without breaking the UI
            return response()->json([
                'success' => true,
                'suggestion' => $this->generateFallbackSeo($type, $title, $content, $keyword),
                'provider' => 'Smart Local Fallback'
            ]);
        }
    }

    /**
     * Deterministic local fallback if AI is unreachable
     */
    private function generateFallbackSeo($type, $title, $content, $keyword)
    {
        $siteName = config('app.name', 'Jobs Portal');
        switch ($type) {
            case 'meta_title':
                $t = !empty($title) ? $title : 'Career Tips & Guide';
                return substr("{$t} | {$siteName}", 0, 60);
            case 'meta_description':
                $c = !empty($content) ? substr($content, 0, 140) : "Read our latest career insights, hiring guides, and job search strategies on {$siteName}.";
                return "{$c}... Explore top opportunities today.";
            case 'focus_keyword':
                return !empty($title) ? strtolower($title) : 'job search tips';
            case 'slug':
                return str_slug($title);
            default:
                return $title;
        }
    }
}