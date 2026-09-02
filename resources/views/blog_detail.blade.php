@extends('layouts.app')

@push('styles')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{{ $blog->getCanonicalUrl() }}"
  },
  "headline": {!! json_encode($blog->heading) !!},
  "description": {!! json_encode($blog->getOgDescription()) !!},
  "image": [
    "{{ $blog->getOgImage() }}"
  ],
  "datePublished": "{{ !empty($blog->created_at) ? date('c', strtotime($blog->created_at)) : date('c') }}",
  "dateModified": "{{ !empty($blog->updated_at) ? date('c', strtotime($blog->updated_at)) : date('c') }}",
  "author": {
    "@type": "Person",
    "name": {!! json_encode($blog->getAuthorName()) !!},
    "jobTitle": {!! json_encode($blog->getAuthorTitle()) !!},
    "url": "{{ url('/blog') }}"
  },
  "publisher": {
    "@type": "Organization",
    "name": {!! json_encode($siteSetting->site_name ?? 'Jobs Portal') !!},
    "logo": {
      "@type": "ImageObject",
      "url": "{{ asset('sitesetting_images/thumb/' . ($siteSetting->site_logo ?? '')) }}"
    }
  }
}
</script>
<style>
.blog-post-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    padding: 32px;
    margin-bottom: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.02);
}
.blog-featured-img {
    width: 100%;
    max-height: 420px;
    object-fit: cover;
    border-radius: 12px;
    margin-bottom: 24px;
}
.blog-cat-pill {
    display: inline-block;
    background: #EFF6FF;
    color: #2563EB;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 20px;
    text-decoration: none !important;
    margin-right: 6px;
    margin-bottom: 10px;
    transition: all 0.15s ease;
}
.blog-cat-pill:hover {
    background: #2563EB;
    color: #FFFFFF;
}
.blog-meta-bar {
    display: flex;
    align-items: center;
    gap: 16px;
    font-size: 13px;
    color: #64748B;
    margin-bottom: 20px;
    flex-wrap: wrap;
    border-bottom: 1px solid #F1F5F9;
    padding-bottom: 16px;
}
.blog-meta-bar strong {
    color: #0F172A;
}
.blog-body-text {
    font-size: 16px;
    line-height: 1.85;
    color: #334155;
}
.blog-body-text h2, .blog-body-text h3 {
    color: #0F172A;
    font-weight: 800;
    margin-top: 28px;
    margin-bottom: 14px;
}
.blog-body-text p {
    margin-bottom: 18px;
}
.blog-body-text img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 16px 0;
}

/* Author Admin Bio Section */
.blog-author-card {
    background: #F8FAFC;
    border: 1.5px solid #E2E8F0;
    border-radius: 14px;
    padding: 24px;
    margin-top: 32px;
    display: flex;
    gap: 20px;
    align-items: center;
}
@media (max-width: 767px) {
    .blog-author-card {
        flex-direction: column;
        text-align: center;
    }
}
.author-avatar-img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #FFFFFF;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    flex-shrink: 0;
}
.author-info {
    flex-grow: 1;
}
.author-name-title {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 4px;
    flex-wrap: wrap;
}
@media (max-width: 767px) {
    .author-name-title {
        justify-content: center;
    }
}
.author-name {
    font-size: 17px;
    font-weight: 800;
    color: #0F172A;
}
.verified-badge {
    background: #03855c;
    color: #FFFFFF;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.author-role {
    font-size: 13px;
    font-weight: 600;
    color: #2563EB;
    margin-bottom: 8px;
}
.author-bio-text {
    font-size: 13.5px;
    color: #475569;
    line-height: 1.5;
    margin-bottom: 10px;
}
.author-tags {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}
@media (max-width: 767px) {
    .author-tags {
        justify-content: center;
    }
}
.author-tag-pill {
    font-size: 11.5px;
    font-weight: 600;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    color: #64748B;
    padding: 2px 8px;
    border-radius: 6px;
}

/* Related Jobs Card */
.related-jobs-box {
    background: #F8FAFC;
    border: 1.5px solid #E2E8F0;
    border-radius: 14px;
    padding: 24px;
    margin-top: 32px;
}
.related-job-item {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 14px 18px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.2s ease;
    text-decoration: none !important;
}
.related-job-item:hover {
    border-color: #2563EB;
    box-shadow: 0 4px 12px rgba(37,99,235,0.08);
    transform: translateY(-1px);
}
.related-job-title {
    font-size: 15px;
    font-weight: 700;
    color: #0F172A;
    margin-bottom: 4px;
}
.related-job-meta {
    font-size: 12.5px;
    color: #64748B;
    display: flex;
    align-items: center;
    gap: 12px;
}

/* Social Share Bar */
.social-share-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid #F1F5F9;
}
.share-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #FFFFFF !important;
    font-size: 14px;
    text-decoration: none !important;
    transition: transform 0.15s ease;
}
.share-btn:hover { transform: scale(1.1); }
.share-btn.whatsapp { background: #25D366; }
.share-btn.linkedin { background: #0A66C2; }
.share-btn.twitter { background: #000000; }
.share-btn.facebook { background: #1877F2; }
</style>
@endpush

@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->

<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title' => __('Career Articles & Guides')])
<!-- Inner Page Title end -->

<div class="listpgWraper" style="background: #F8FAFC; padding: 40px 0;">
    <div class="container">
        <div class="row">
            
            <!-- MAIN BLOG ARTICLE -->
            <div class="col-lg-8 col-md-12">
                <div class="blog-post-card">
                    
                    <!-- Categories -->
                    @php $blogCats = $blog->getCategories(); @endphp
                    @if($blogCats->count() > 0)
                    <div>
                        @foreach($blogCats as $c)
                        <a href="{{ url('/blog/category/' . $c->slug) }}" class="blog-cat-pill">{{ $c->heading }}</a>
                        @endforeach
                    </div>
                    @endif

                    <h1 style="font-size: 28px; font-weight: 800; color: #0F172A; line-height: 1.35; margin-bottom: 14px;">
                        {{ $blog->heading }}
                    </h1>

                    <div class="blog-meta-bar">
                        <span><i class="fa fa-user-circle text-muted"></i> By <strong>{{ $blog->getAuthorName() }}</strong></span>
                        <span><i class="fa fa-calendar-o text-muted"></i> {{ !empty($blog->created_at) ? date('M d, Y', strtotime($blog->created_at)) : date('M d, Y') }}</span>
                        <span><i class="fa fa-clock-o text-muted"></i> {{ max(1, round(str_word_count(strip_tags($blog->content)) / 200)) }} min read</span>
                    </div>

                    @if(!empty($blog->image))
                    <img src="{{ $blog->getImageUrl() }}" alt="{{ $blog->heading }}" class="blog-featured-img">
                    @endif

                    <!-- Content -->
                    <div class="blog-body-text">
                        {!! $blog->content !!}
                    </div>

                    <!-- Social Share -->
                    <div class="social-share-wrap">
                        <span style="font-size: 13px; font-weight: 700; color: #475569; margin-right: 6px;">Share Article:</span>
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($blog->heading . ' ' . $blog->getCanonicalUrl()) }}" target="_blank" class="share-btn whatsapp" title="Share on WhatsApp"><i class="fa fa-whatsapp"></i></a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($blog->getCanonicalUrl()) }}" target="_blank" class="share-btn linkedin" title="Share on LinkedIn"><i class="fa fa-linkedin"></i></a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($blog->heading) }}&url={{ urlencode($blog->getCanonicalUrl()) }}" target="_blank" class="share-btn twitter" title="Share on X / Twitter"><i class="fa fa-twitter"></i></a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($blog->getCanonicalUrl()) }}" target="_blank" class="share-btn facebook" title="Share on Facebook"><i class="fa fa-facebook"></i></a>
                    </div>

                    <!-- AUTHOR / ADMIN PROFILE SECTION -->
                    <div class="blog-author-card">
                        <img src="{{ $blog->getAuthorAvatar() }}" alt="{{ $blog->getAuthorName() }}" class="author-avatar-img">
                        <div class="author-info">
                            <div class="author-name-title">
                                <span class="author-name">{{ $blog->getAuthorName() }}</span>
                                <span class="verified-badge"><i class="fa fa-check"></i> Author</span>
                            </div>
                            <div class="author-role">{{ $blog->getAuthorTitle() }}</div>
                            <div class="author-bio-text">{{ $blog->getAuthorBio() }}</div>
                            <div class="author-tags">
                                <span class="author-tag-pill"><i class="fa fa-check-circle text-success"></i> Recruitment Insights</span>
                                <span class="author-tag-pill"><i class="fa fa-briefcase text-primary"></i> Career Guidance</span>
                                <span class="author-tag-pill"><i class="fa fa-graduation-cap text-info"></i> Interview Prep</span>
                            </div>
                        </div>
                    </div>

                    <!-- Real Related Database Opportunities -->
                    @if(isset($related_jobs) && $related_jobs->count() > 0)
                    <div class="related-jobs-box">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <h3 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0; display: flex; align-items: center; gap: 8px;">
                                <i class="fa fa-briefcase text-primary"></i> Explore Related Job Vacancies
                            </h3>
                            <a href="{{ url('/jobs') }}" style="font-size: 13px; font-weight: 700; color: #2563EB; text-decoration: none;">View All Jobs &rarr;</a>
                        </div>
                        <div>
                            @foreach($related_jobs as $relJob)
                            <a href="{{ route('job.detail', [$relJob->slug]) }}" class="related-job-item">
                                <div>
                                    <div class="related-job-title">{{ $relJob->title }}</div>
                                    <div class="related-job-meta">
                                        @if($relJob->getCompany())
                                        <span><i class="fa fa-building-o"></i> {{ $relJob->getCompany()->name }}</span>
                                        @endif
                                        @if($relJob->getCity())
                                        <span><i class="fa fa-map-marker"></i> {{ $relJob->getCity()->city }}</span>
                                        @endif
                                        @if(!empty($relJob->salary_from))
                                        <span><i class="fa fa-money"></i> ₹{{ number_format($relJob->salary_from) }} - ₹{{ number_format($relJob->salary_to) }}</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="btn btn-sm btn-primary" style="background: #2563EB; border-color: #2563EB; border-radius: 6px; font-weight: 700; font-size: 12px; white-space: nowrap;">
                                    Apply Now
                                </span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>
            </div>

            <!-- SIDEBAR -->
            <div class="col-lg-4 col-md-12">
                
                <!-- Search Box -->
                <div class="blog-post-card" style="padding: 22px; margin-bottom: 20px;">
                    <h4 style="font-size: 15px; font-weight: 800; color: #0F172A; margin-bottom: 14px;">Search Career Guides</h4>
                    <form action="{{ route('blog-search') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search keywords..." style="border-radius: 8px 0 0 8px; border-color: #E2E8F0; font-size: 13.5px;">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary" style="background: #2563EB; border-color: #2563EB; border-radius: 0 8px 8px 0;"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </form>
                </div>

                <!-- Categories -->
                @if(isset($categories) && $categories->count() > 0)
                <div class="blog-post-card" style="padding: 22px; margin-bottom: 20px;">
                    <h4 style="font-size: 15px; font-weight: 800; color: #0F172A; margin-bottom: 14px;">Explore by Topic</h4>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach($categories as $cat)
                        <li style="padding: 8px 0; border-bottom: 1px solid #F1F5F9; font-size: 14px;">
                            <a href="{{ url('/blog/category/' . $cat->slug) }}" style="color: #334155; font-weight: 600; text-decoration: none; display: flex; justify-content: space-between; align-items: center;">
                                <span><i class="fa fa-angle-right text-primary" style="margin-right: 6px;"></i> {{ $cat->heading }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Recent Articles -->
                @if(isset($recent_blogs) && $recent_blogs->count() > 0)
                <div class="blog-post-card" style="padding: 22px;">
                    <h4 style="font-size: 15px; font-weight: 800; color: #0F172A; margin-bottom: 14px;">Latest Articles</h4>
                    @foreach($recent_blogs as $rBlog)
                    <div style="display: flex; gap: 12px; margin-bottom: 14px; align-items: center;">
                        <div style="width: 58px; height: 44px; border-radius: 6px; overflow: hidden; background: #F1F5F9; flex-shrink: 0;">
                            <img src="{{ $rBlog->getImageUrl() }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div>
                            <a href="{{ url('/blog/' . $rBlog->slug) }}" style="font-size: 13px; font-weight: 700; color: #0F172A; text-decoration: none; line-height: 1.35; display: block;">
                                {{ \Illuminate\Support\Str::words($rBlog->heading, 7, '...') }}
                            </a>
                            <span style="font-size: 11.5px; color: #94A3B8;">{{ !empty($rBlog->created_at) ? date('M d, Y', strtotime($rBlog->created_at)) : '' }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

            </div>

        </div>
    </div>
</div>

<!-- Footer start -->
@include('includes.footer')
<!-- Footer end -->
@endsection