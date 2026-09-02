<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Job;

class Blog extends Model
{
    protected $table = 'blogs';

    protected $fillable = [
        'heading',
        'slug',
        'cate_id',
        'content',
        'image',
        'featured',
        'is_published',
        'focus_keyword',
        'meta_title',
        'lang',
        'author_name',
        'author_title',
        'author_bio',
        'author_avatar',
        'meta_keywords',
        'meta_descriptions',
        'canonical_url',
        'robots_index',
        'robots_follow',
        'og_title',
        'og_description',
        'og_image',
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'twitter_image',
    ];

    /**
     * Scope for published blogs
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', 1);
    }

    /**
     * Featured image printer helper
     */
    public function printBlogImage($width = 0, $height = 0)
    {
        $logo = (string)$this->image;
        $logo = (!empty($logo)) ? $logo : 'no-no-image.gif';
        return \ImgUploader::print_image("uploads/blogs/$logo", $width, $height, '/admin_assets/no-image.png', $this->image);
    }

    /**
     * Get image URL
     */
    public function getImageUrl()
    {
        if (!empty($this->image) && file_exists(public_path('uploads/blogs/' . $this->image))) {
            return asset('uploads/blogs/' . $this->image);
        }
        return asset('admin_assets/no-image.png');
    }

    /**
     * Get Author Name (Real person)
     */
    public function getAuthorName()
    {
        return !empty($this->author_name) ? $this->author_name : 'Abhishek Sharma';
    }

    /**
     * Get Author Professional Title
     */
    public function getAuthorTitle()
    {
        return !empty($this->author_title) ? $this->author_title : 'Career Consultant & Lead Editor';
    }

    /**
     * Get Author Bio
     */
    public function getAuthorBio()
    {
        return !empty($this->author_bio) ? $this->author_bio : 'Passionate career counselor and recruitment specialist sharing expert job search strategies, interview preparation techniques, and hiring insights across India.';
    }

    /**
     * Get Author Avatar URL
     */
    public function getAuthorAvatar()
    {
        if (!empty($this->author_avatar) && file_exists(public_path('uploads/blogs/' . $this->author_avatar))) {
            return asset('uploads/blogs/' . $this->author_avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->getAuthorName()) . '&background=03855c&color=ffffff&size=128&bold=true';
    }

    /**
     * Get categories list
     */
    public function getCategories()
    {
        if (empty($this->cate_id)) {
            return collect();
        }
        $cate_ids = explode(',', $this->cate_id);
        return DB::table('blog_categories')->whereIn('id', $cate_ids)->get();
    }

    /**
     * Get Canonical URL
     */
    public function getCanonicalUrl()
    {
        if (!empty($this->canonical_url)) {
            return $this->canonical_url;
        }
        return url('/blog/' . $this->slug);
    }

    /**
     * Get OG Title
     */
    public function getOgTitle()
    {
        return !empty($this->og_title) ? $this->og_title : (!empty($this->meta_title) ? $this->meta_title : $this->heading);
    }

    /**
     * Get OG Description
     */
    public function getOgDescription()
    {
        return !empty($this->og_description) ? $this->og_description : (!empty($this->meta_descriptions) ? $this->meta_descriptions : strip_tags(substr($this->content, 0, 160)));
    }

    /**
     * Get OG Image
     */
    public function getOgImage()
    {
        if (!empty($this->og_image) && file_exists(public_path('uploads/blogs/' . $this->og_image))) {
            return asset('uploads/blogs/' . $this->og_image);
        }
        return $this->getImageUrl();
    }

    /**
     * Get Twitter Title
     */
    public function getTwitterTitle()
    {
        return !empty($this->twitter_title) ? $this->twitter_title : $this->getOgTitle();
    }

    /**
     * Get Twitter Description
     */
    public function getTwitterDescription()
    {
        return !empty($this->twitter_description) ? $this->twitter_description : $this->getOgDescription();
    }

    /**
     * Get Twitter Image
     */
    public function getTwitterImage()
    {
        return !empty($this->twitter_image) ? (file_exists(public_path('uploads/blogs/' . $this->twitter_image)) ? asset('uploads/blogs/' . $this->twitter_image) : $this->getOgImage()) : $this->getOgImage();
    }

    /**
     * Get related real jobs from database matching categories or keyword
     */
    public function getRelatedJobs($limit = 4)
    {
        $query = Job::where('is_active', 1)
            ->where('expiry_date', '>=', DB::raw('NOW()'));

        // Try to match focus keyword or heading in title
        if (!empty($this->focus_keyword)) {
            $kw = trim($this->focus_keyword);
            $query->where(function($q) use ($kw) {
                $q->where('title', 'like', "%{$kw}%")
                  ->orWhere('description', 'like', "%{$kw}%");
            });
        } elseif (!empty($this->heading)) {
            $words = explode(' ', preg_replace('/[^A-Za-z0-9 ]/', '', $this->heading));
            $significantWords = array_filter($words, function($w) {
                return strlen($w) > 3 && !in_array(strtolower($w), ['what', 'when', 'where', 'which', 'about', 'find', 'best', 'tips', 'guide', 'your']);
            });
            if (!empty($significantWords)) {
                $firstWord = reset($significantWords);
                $query->where('title', 'like', "%{$firstWord}%");
            }
        }

        $jobs = $query->orderBy('id', 'desc')->take($limit)->get();

        // Fallback: If not enough matching jobs, get recent active jobs
        if ($jobs->count() < $limit) {
            $existingIds = $jobs->pluck('id')->toArray();
            $fillers = Job::where('is_active', 1)
                ->where('expiry_date', '>=', DB::raw('NOW()'))
                ->whereNotIn('id', $existingIds)
                ->orderBy('id', 'desc')
                ->take($limit - $jobs->count())
                ->get();
            $jobs = $jobs->concat($fillers);
        }

        return $jobs;
    }
}