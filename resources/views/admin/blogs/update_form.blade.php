@extends('admin.layouts.admin_layout')

@section('content')
<style>
.blog-editor-wrap {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #1E293B;
    padding: 10px 0 40px 0;
}
.blog-breadcrumb {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #94A3B8;
    margin-bottom: 20px;
    font-weight: 500;
}
.blog-breadcrumb a { color: #64748B; text-decoration: none; }
.blog-breadcrumb a:hover { color: #2563EB; }
.blog-breadcrumb i { font-size: 9px; color: #CBD5E1; }

.blog-header-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
    flex-wrap: wrap;
    gap: 12px;
}
.blog-page-title {
    font-size: 22px;
    font-weight: 800;
    color: #0F172A;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.blog-page-title .title-icon {
    width: 38px;
    height: 38px;
    background: #2563EB;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 17px;
}

.card-box {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    padding: 22px;
    margin-bottom: 22px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}
.card-box-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #F1F5F9;
    padding-bottom: 12px;
    margin-bottom: 16px;
}
.card-box-title {
    font-size: 15px;
    font-weight: 700;
    color: #0F172A;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-label-custom {
    font-size: 13px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 6px;
    display: block;
}
.form-control-custom {
    width: 100%;
    border: 1.5px solid #E2E8F0;
    border-radius: 8px;
    padding: 9px 13px;
    font-size: 13.5px;
    color: #0F172A;
    transition: border-color 0.15s;
    outline: none;
    background: #FFFFFF;
}
.form-control-custom:focus {
    border-color: #2563EB;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}

/* Rank Math Style SEO Panel */
.seo-score-box {
    display: flex;
    align-items: center;
    gap: 16px;
    background: #F8FAFC;
    border: 1.5px solid #E2E8F0;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 18px;
}
.seo-score-circle {
    width: 58px;
    height: 58px;
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #FFFFFF;
    font-weight: 800;
    font-size: 18px;
    line-height: 1;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: background 0.3s ease;
}
.seo-score-circle small {
    font-size: 9px;
    font-weight: 600;
    opacity: 0.9;
    text-transform: uppercase;
}

/* Google SERP Snippet Preview */
.google-preview-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 14px 16px;
    margin-bottom: 18px;
    font-family: Arial, sans-serif;
}
.google-preview-url {
    font-size: 12px;
    color: #202124;
    display: flex;
    align-items: center;
    gap: 4px;
    margin-bottom: 4px;
    word-break: break-all;
}
.google-preview-url span { color: #5F6368; }
.google-preview-title {
    font-size: 17px;
    font-weight: 500;
    color: #1A0DAB;
    margin-bottom: 4px;
    line-height: 1.3;
    cursor: pointer;
}
.google-preview-desc {
    font-size: 13px;
    color: #4D5156;
    line-height: 1.4;
    word-break: break-word;
}

/* Collapsible Accordion Items */
.seo-accordion-item {
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    margin-bottom: 10px;
    overflow: hidden;
    background: #FFFFFF;
}
.seo-accordion-header {
    padding: 12px 16px;
    background: #F8FAFC;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 13.5px;
    font-weight: 700;
    color: #334155;
    user-select: none;
    transition: background 0.15s;
}
.seo-accordion-header:hover { background: #F1F5F9; }
.seo-accordion-body {
    padding: 14px 16px;
    border-top: 1px solid #E2E8F0;
    display: none;
}
.seo-accordion-body.open { display: block; }

/* Checklist items */
.seo-check-item {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    font-size: 12.5px;
    margin-bottom: 8px;
    color: #475569;
    line-height: 1.4;
}
.seo-check-item i.fa-check-circle { color: #03855c; font-size: 14px; margin-top: 2px; }
.seo-check-item i.fa-times-circle { color: #DC2626; font-size: 14px; margin-top: 2px; }
.seo-check-item i.fa-exclamation-circle { color: #EA580C; font-size: 14px; margin-top: 2px; }

/* AI Helper Buttons */
.btn-ai-sparkle {
    background: #F0FDF4;
    border: 1px solid #BBF7D0;
    color: #03855c;
    font-size: 11.5px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 6px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.15s ease;
    outline: none;
}
.btn-ai-sparkle:hover {
    background: #DCFCE7;
    color: #026647;
}

.char-counter {
    font-size: 11.5px;
    font-weight: 700;
    color: #64748B;
    float: right;
}
.char-counter.valid { color: #03855c; }
.char-counter.warning { color: #EA580C; }
.char-counter.danger { color: #DC2626; }
</style>

<div class="page-content-wrapper">
    <div class="page-content">
        <div class="blog-editor-wrap">

            <!-- Breadcrumb -->
            <div class="blog-breadcrumb">
                <a href="{{ route('admin.home') }}"><i class="fa fa-home"></i> Dashboard</a>
                <i class="fa fa-chevron-right"></i>
                <a href="{{ route('blog') }}">Manage Blogs</a>
                <i class="fa fa-chevron-right"></i>
                <span style="color: #0F172A; font-weight: 700;">Edit Blog Post</span>
            </div>

            @include('flash::message')

            <!-- Page Title Row -->
            <div class="blog-header-row">
                <h1 class="blog-page-title">
                    <span class="title-icon"><i class="fa fa-pencil"></i></span>
                    Edit Blog Post #{{ $blog->id }}
                </h1>
                <div style="display: flex; gap: 8px;">
                    <a href="{{ url('/blog/' . $blog->slug) }}" target="_blank" class="btn btn-default" style="border-radius: 8px; font-weight: 600;">
                        <i class="fa fa-eye"></i> View Live Page
                    </a>
                    <a href="{{ route('blog') }}" class="btn btn-default" style="border-radius: 8px; font-weight: 600;">Back to List</a>
                </div>
            </div>

            <form id="formEditBlog" method="POST" action="{{ url('admin/blog/update') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $blog->id }}">
                <input type="hidden" name="action_type" id="action_type" value="{{ $blog->is_published ? 'publish' : 'draft' }}">

                <div class="row">
                    <!-- LEFT COLUMN: MAIN CONTENT -->
                    <div class="col-lg-7 col-12">
                        <div class="card-box">
                            <div class="form-group mb-3">
                                <label class="form-label-custom">Language <span class="text-danger">*</span></label>
                                <select name="lang" id="blog_lang" class="form-control-custom">
                                    @foreach($languages as $code => $langName)
                                    <option value="{{ $code }}" {{ ($blog->lang ?: 'en') === $code ? 'selected' : '' }}>{{ $langName }} ({{ $code }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                    <label class="form-label-custom" style="margin: 0;">Blog Title <span class="text-danger">*</span></label>
                                    <button type="button" class="btn-ai-sparkle" onclick="requestAiSeo('meta_title')">
                                        <i class="fa fa-magic"></i> ✨ AI Improve Title
                                    </button>
                                </div>
                                <input type="text" name="title_update" id="blog_title" class="form-control-custom" placeholder="e.g. How to Find High Paying IT Jobs in Mumbai" required value="{{ old('title_update', $blog->heading) }}" style="font-size: 15px; font-weight: 700;">
                                @if(isset($errors) && $errors->has('title_update'))
                                <span class="text-danger" style="font-size: 12px;">{{ $errors->first('title_update') }}</span>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label-custom">URL Slug <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon" style="background: #F8FAFC; border-color: #E2E8F0; font-size: 12.5px; color: #64748B;">{{ url('/blog') }}/</span>
                                    <input type="text" name="slug_update" id="blog_slug" class="form-control" placeholder="how-to-find-it-jobs" required value="{{ old('slug_update', $blog->slug) }}" style="border-color: #E2E8F0; border-radius: 0 8px 8px 0; font-size: 13px;">
                                </div>
                                @if(isset($errors) && $errors->has('slug_update'))
                                <span class="text-danger" style="font-size: 12px;">{{ $errors->first('slug_update') }}</span>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label-custom">Blog Article Content <span class="text-danger">*</span></label>
                                <textarea name="content_update" id="description" class="form-control" rows="18" placeholder="Write full article here...">{!! old('content_update', $blog->content) !!}</textarea>
                                @if(isset($errors) && $errors->has('content_update'))
                                <span class="text-danger" style="font-size: 12px;">{{ $errors->first('content_update') }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons Bar -->
                        <div class="card-box" style="padding: 16px 22px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                            <div>
                                <label style="display: flex; align-items: center; gap: 8px; margin: 0; font-weight: 700; font-size: 13px; color: #475569; cursor: pointer;">
                                    <input type="checkbox" name="featured" value="1" {{ $blog->featured ? 'checked' : '' }} style="width: 18px; height: 18px;"> Mark as Featured Post
                                </label>
                            </div>
                            <div style="display: flex; gap: 10px;">
                                <button type="submit" onclick="$('#action_type').val('draft')" class="btn btn-default" style="border-radius: 8px; font-weight: 700; padding: 9px 20px;">
                                    <i class="fa fa-save"></i> Save as Draft
                                </button>
                                <button type="submit" onclick="$('#action_type').val('publish')" class="btn btn-primary" style="background: #2563EB; border-color: #2563EB; border-radius: 8px; font-weight: 700; padding: 9px 24px; box-shadow: 0 2px 6px rgba(37,99,235,0.25);">
                                    <i class="fa fa-check-circle"></i> Save & Publish
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: RANK MATH SEO PANEL & METADATA -->
                    <div class="col-lg-5 col-12">
                        
                        <!-- Rank Math SEO & Search Metadata Box (Positioned at TOP of right column) -->
                        <div class="card-box">
                            <div class="card-box-header">
                                <h3 class="card-box-title"><i class="fa fa-tachometer text-primary"></i> Rank Math SEO Analysis</h3>
                                <span style="font-size: 11px; font-weight: 700; color: #03855c; background: #ECFDF5; padding: 2px 6px; border-radius: 4px;">₹0 AI Cost</span>
                            </div>

                            <div class="seo-score-box">
                                <div class="seo-score-circle" id="seoScoreGauge" style="background: #DC2626;">
                                    <span id="seoScoreVal">0</span>
                                    <small>/ 100</small>
                                </div>
                                <div>
                                    <div style="font-weight: 800; font-size: 14px; color: #0F172A;" id="seoScoreLabel">Needs Work</div>
                                    <div style="font-size: 11.5px; color: #64748B;" id="seoScoreSummary">Evaluating post SEO score...</div>
                                </div>
                            </div>

                            <!-- Google SERP Snippet Preview -->
                            <div style="font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 6px;">
                                <i class="fa fa-google text-primary"></i> Google Search Snippet Preview
                            </div>
                            <div class="google-preview-card">
                                <div class="google-preview-url">
                                    <span>{{ url('/blog') }}/</span><strong id="previewSlug">{{ $blog->slug }}</strong>
                                </div>
                                <div class="google-preview-title" id="previewTitle">{{ $blog->meta_title ?: $blog->heading }}</div>
                                <div class="google-preview-desc" id="previewDesc">{{ $blog->meta_descriptions ?: strip_tags(substr($blog->content, 0, 150)) }}</div>
                            </div>

                            <!-- Integrated Search Metadata Inputs -->
                            <div style="background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 10px; padding: 14px 16px; margin-bottom: 18px;">
                                <div style="font-weight: 800; font-size: 13px; color: #0F172A; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                                    <i class="fa fa-tags text-primary"></i> Search Metadata Tags
                                </div>

                                <!-- Focus Keyword -->
                                <div class="form-group mb-3">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                        <label class="form-label-custom" style="margin: 0; font-size: 12.5px;">Focus Keyword</label>
                                        <button type="button" class="btn-ai-sparkle" onclick="requestAiSeo('focus_keyword')">
                                            <i class="fa fa-magic"></i> ✨ Suggest
                                        </button>
                                    </div>
                                    <input type="text" name="focus_keyword" id="focus_keyword" class="form-control-custom" placeholder="e.g. IT Jobs in Mumbai" value="{{ old('focus_keyword', $blog->focus_keyword) }}">
                                </div>

                                <!-- Meta Title -->
                                <div class="form-group mb-3">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                        <label class="form-label-custom" style="margin: 0; font-size: 12.5px;">Meta Title</label>
                                        <span class="char-counter" id="metaTitleCounter">0 / 60</span>
                                    </div>
                                    <input type="text" name="meta_title_update" id="meta_title" class="form-control-custom" placeholder="Search engine title..." value="{{ old('meta_title_update', $blog->meta_title) }}">
                                </div>

                                <!-- Meta Description -->
                                <div class="form-group mb-3">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                        <label class="form-label-custom" style="margin: 0; font-size: 12.5px;">Meta Description</label>
                                        <span class="char-counter" id="metaDescCounter">0 / 155</span>
                                    </div>
                                    <textarea name="meta_descriptions_update" id="meta_descriptions" class="form-control-custom" rows="3" placeholder="Engaging snippet for Google results...">{{ old('meta_descriptions_update', $blog->meta_descriptions) }}</textarea>
                                    <div style="margin-top: 6px; text-align: right;">
                                        <button type="button" class="btn-ai-sparkle" onclick="requestAiSeo('meta_description')">
                                            <i class="fa fa-magic"></i> ✨ AI Improve Description
                                        </button>
                                    </div>
                                </div>

                                <!-- Meta Keywords -->
                                <div class="form-group mb-0">
                                    <label class="form-label-custom" style="margin-bottom: 4px; font-size: 12.5px;">Meta Keywords</label>
                                    <input type="text" name="meta_keywords_update" id="meta_keywords" class="form-control-custom" placeholder="e.g. jobs, career, mumbai hiring" value="{{ old('meta_keywords_update', $blog->meta_keywords) }}">
                                </div>
                            </div>

                            <!-- Accordion: Detailed SEO Sections -->
                            <div class="seo-accordion-item">
                                <div class="seo-accordion-header" onclick="toggleSeoSection('seoBasic')">
                                    <span><i class="fa fa-list-ul text-primary"></i> 1. Basic SEO Checklist</span>
                                    <i class="fa fa-chevron-down text-muted" id="icon-seoBasic"></i>
                                </div>
                                <div class="seo-accordion-body open" id="body-seoBasic">
                                    <div class="seo-check-item" id="check-kw-title"><i class="fa fa-times-circle"></i> <span>Focus keyword in Blog Title</span></div>
                                    <div class="seo-check-item" id="check-kw-desc"><i class="fa fa-times-circle"></i> <span>Focus keyword in Meta Description</span></div>
                                    <div class="seo-check-item" id="check-kw-slug"><i class="fa fa-times-circle"></i> <span>Focus keyword in URL Slug</span></div>
                                    <div class="seo-check-item" id="check-kw-intro"><i class="fa fa-times-circle"></i> <span>Focus keyword in Content Intro</span></div>
                                    <div class="seo-check-item" id="check-length"><i class="fa fa-times-circle"></i> <span>Content length: <strong id="wordCount">0</strong> words (min 300)</span></div>
                                </div>
                            </div>

                            <div class="seo-accordion-item">
                                <div class="seo-accordion-header" onclick="toggleSeoSection('seoContent')">
                                    <span><i class="fa fa-align-left text-primary"></i> 2. Content Quality & Headings</span>
                                    <i class="fa fa-chevron-down text-muted" id="icon-seoContent"></i>
                                </div>
                                <div class="seo-accordion-body" id="body-seoContent">
                                    <div class="seo-check-item" id="check-headings"><i class="fa fa-times-circle"></i> <span>H2 / H3 Subheadings used</span></div>
                                    <div class="seo-check-item" id="check-density"><i class="fa fa-times-circle"></i> <span>Keyword Density: <strong id="kwDensity">0%</strong> (Aim 1.0 - 2.5%)</span></div>
                                    <div class="seo-check-item" id="check-feat-img"><i class="fa fa-times-circle"></i> <span>Featured Image provided</span></div>
                                </div>
                            </div>

                            <div class="seo-accordion-item">
                                <div class="seo-accordion-header" onclick="toggleSeoSection('seoReadability')">
                                    <span><i class="fa fa-book text-primary"></i> 3. Readability & Structure</span>
                                    <i class="fa fa-chevron-down text-muted" id="icon-seoReadability"></i>
                                </div>
                                <div class="seo-accordion-body" id="body-seoReadability">
                                    <div class="seo-check-item" id="check-title-len"><i class="fa fa-times-circle"></i> <span>Title length optimal</span></div>
                                    <div class="seo-check-item" id="check-desc-len"><i class="fa fa-times-circle"></i> <span>Meta description optimal</span></div>
                                    <div class="seo-check-item" id="check-readability"><i class="fa fa-check-circle"></i> <span>Readability Score: <strong id="readScore">Good</strong></span></div>
                                </div>
                            </div>

                            <div class="seo-accordion-item">
                                <div class="seo-accordion-header" onclick="toggleSeoSection('seoSocial')">
                                    <span><i class="fa fa-share-alt text-primary"></i> 4. Social & Open Graph</span>
                                    <i class="fa fa-chevron-down text-muted" id="icon-seoSocial"></i>
                                </div>
                                <div class="seo-accordion-body" id="body-seoSocial">
                                    <div class="form-group mb-2">
                                        <label class="form-label-custom" style="font-size: 12px;">OG / Social Title</label>
                                        <input type="text" name="og_title" id="og_title" class="form-control-custom" placeholder="Leave empty to use Meta Title" value="{{ old('og_title', $blog->og_title) }}">
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="form-label-custom" style="font-size: 12px;">OG / Social Description</label>
                                        <textarea name="og_description" id="og_description" class="form-control-custom" rows="2" placeholder="Leave empty to use Meta Description">{{ old('og_description', $blog->og_description) }}</textarea>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="form-label-custom" style="font-size: 12px;">Twitter Card Type</label>
                                        <select name="twitter_card" class="form-control-custom">
                                            <option value="summary_large_image" {{ ($blog->twitter_card ?: 'summary_large_image') === 'summary_large_image' ? 'selected' : '' }}>Summary with Large Image</option>
                                            <option value="summary" {{ $blog->twitter_card === 'summary' ? 'selected' : '' }}>Standard Summary</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="seo-accordion-item">
                                <div class="seo-accordion-header" onclick="toggleSeoSection('seoTechnical')">
                                    <span><i class="fa fa-cog text-primary"></i> 5. Advanced / Technical SEO</span>
                                    <i class="fa fa-chevron-down text-muted" id="icon-seoTechnical"></i>
                                </div>
                                <div class="seo-accordion-body" id="body-seoTechnical">
                                    <div class="form-group mb-2">
                                        <label class="form-label-custom" style="font-size: 12px;">Canonical URL Override</label>
                                        <input type="text" name="canonical_url" id="canonical_url" class="form-control-custom" placeholder="Leave empty for auto: {{ url('/blog/' . $blog->slug) }}" value="{{ old('canonical_url', $blog->canonical_url) }}">
                                    </div>
                                    <div class="row">
                                        <div class="col-6 form-group mb-2">
                                            <label class="form-label-custom" style="font-size: 12px;">Robots Index</label>
                                            <select name="robots_index" class="form-control-custom">
                                                <option value="index" {{ ($blog->robots_index ?: 'index') === 'index' ? 'selected' : '' }}>Index</option>
                                                <option value="noindex" {{ $blog->robots_index === 'noindex' ? 'selected' : '' }}>Noindex</option>
                                            </select>
                                        </div>
                                        <div class="col-6 form-group mb-2">
                                            <label class="form-label-custom" style="font-size: 12px;">Robots Follow</label>
                                            <select name="robots_follow" class="form-control-custom">
                                                <option value="follow" {{ ($blog->robots_follow ?: 'follow') === 'follow' ? 'selected' : '' }}>Follow</option>
                                                <option value="nofollow" {{ $blog->robots_follow === 'nofollow' ? 'selected' : '' }}>Nofollow</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div style="font-size: 11px; color: #03855c; background: #ECFDF5; padding: 6px 10px; border-radius: 6px; margin-top: 4px;">
                                        <i class="fa fa-check"></i> BlogPosting Schema JSON-LD active on public page.
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Category & Featured Image Box -->
                        <div class="card-box">
                            <div class="card-box-header">
                                <h3 class="card-box-title"><i class="fa fa-folder-open-o text-primary"></i> Categories & Media</h3>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label-custom">Select Category</label>
                                @php $selectedCatIds = explode(',', $blog->cate_id); @endphp
                                <div style="max-height: 180px; overflow-y: auto; border: 1.5px solid #E2E8F0; border-radius: 8px; padding: 10px 12px; background: #F8FAFC;">
                                    @foreach($categories as $cate)
                                    <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 500; color: #334155; margin-bottom: 6px; cursor: pointer;">
                                        <input type="checkbox" name="cate_id_update[]" value="{{ $cate->id }}" {{ in_array($cate->id, $selectedCatIds) ? 'checked' : '' }} style="cursor: pointer;">
                                        {!! $cate->heading !!}
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label class="form-label-custom">Featured Image</label>
                                @if(!empty($blog->image))
                                <div style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px; background: #F8FAFC; padding: 8px; border-radius: 8px; border: 1px solid #E2E8F0;">
                                    <img src="{{ $blog->getImageUrl() }}" alt="" style="width: 70px; height: 50px; object-fit: cover; border-radius: 6px;">
                                    <div style="font-size: 12px; color: #64748B;">
                                        <div>Current Image</div>
                                        <code style="font-size: 11px;">{{ $blog->image }}</code>
                                    </div>
                                </div>
                                @endif
                                <input type="file" name="imageupdate" id="featured_image_input" class="form-control-custom" accept="image/*" onchange="previewFeaturedImage(this)">
                                <div id="imagePreviewContainer" style="margin-top: 10px; display: none;">
                                    <img id="imgPreview" src="" alt="Preview" style="max-width: 100%; height: 120px; object-fit: cover; border-radius: 8px; border: 1px solid #E2E8F0;">
                                </div>
                            </div>
                        </div>

                        <!-- Author Information Box -->
                        <div class="card-box">
                            <div class="card-box-header">
                                <h3 class="card-box-title"><i class="fa fa-user-circle text-primary"></i> Author Profile</h3>
                            </div>
                            <div class="form-group mb-2">
                                <label class="form-label-custom" style="font-size: 12.5px;">Author Name</label>
                                <input type="text" name="author_name" class="form-control-custom" placeholder="e.g. Abhishek Sharma" value="{{ old('author_name', $blog->author_name ?? 'Abhishek Sharma') }}">
                            </div>
                            <div class="form-group mb-2">
                                <label class="form-label-custom" style="font-size: 12.5px;">Professional Title / Designation</label>
                                <input type="text" name="author_title" class="form-control-custom" placeholder="e.g. Career Consultant & Lead Editor" value="{{ old('author_title', $blog->author_title ?? 'Career Consultant & Lead Editor') }}">
                            </div>
                            <div class="form-group mb-0">
                                <label class="form-label-custom" style="font-size: 12.5px;">Author Bio</label>
                                <textarea name="author_bio" class="form-control-custom" rows="2" placeholder="Short bio displayed at the end of the post...">{{ old('author_bio', $blog->author_bio ?? 'Passionate career counselor and recruitment specialist sharing expert job search strategies, interview preparation techniques, and hiring insights across India.') }}</textarea>
                            </div>
                        </div>

                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Modal: AI Suggestion Review & Accept -->
<div class="modal fade" id="modalAiReview" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header" style="border-bottom: 1px solid #F1F5F9; padding: 16px 22px;">
                <h5 class="modal-title" style="font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 8px;">
                    <i class="fa fa-magic text-success"></i> <span id="aiModalFieldTitle">AI SEO Suggestion</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 22px;">
                <div style="margin-bottom: 16px;">
                    <label style="font-size: 12px; font-weight: 700; color: #64748B; text-transform: uppercase;">Current Value:</label>
                    <div id="aiCurrentVal" style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 10px 14px; border-radius: 8px; font-size: 13.5px; color: #475569; min-height: 38px;">(Empty)</div>
                </div>
                <div>
                    <label style="font-size: 12px; font-weight: 700; color: #03855c; text-transform: uppercase;">✨ AI Generated Suggestion:</label>
                    <div id="aiSuggestedVal" style="background: #ECFDF5; border: 1.5px solid #A7F3D0; padding: 12px 14px; border-radius: 8px; font-size: 14px; font-weight: 600; color: #065F46; line-height: 1.5;"></div>
                    <div style="font-size: 11px; color: #64748B; margin-top: 6px;" id="aiProviderBadge">Powered by AI Provider</div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #F1F5F9; padding: 14px 22px;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 8px; font-weight: 600;">Keep Current</button>
                <button type="button" class="btn btn-success" id="btnAcceptAiSuggestion" style="background: #03855c; border-color: #03855c; border-radius: 8px; font-weight: 700;">
                    <i class="fa fa-check"></i> Accept & Apply
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@include('admin.shared.tinyMCE')
<script>
var pendingAiField = null;
var pendingAiText = null;

function toggleSeoSection(id) {
    $('#body-' + id).slideToggle(200, function() {
        if ($(this).is(':visible')) {
            $('#icon-' + id).removeClass('fa-chevron-down').addClass('fa-chevron-up');
        } else {
            $('#icon-' + id).removeClass('fa-chevron-up').addClass('fa-chevron-down');
        }
    });
}

function previewFeaturedImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imgPreview').attr('src', e.target.result);
            $('#imagePreviewContainer').show();
            calculateDeterministicSeo();
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Slug & Title listeners
$('#blog_title').on('input', function() {
    calculateDeterministicSeo();
});

$('#blog_slug, #meta_title, #meta_descriptions, #focus_keyword').on('input', function() {
    calculateDeterministicSeo();
});

// Deterministic Real-time SEO Scoring Engine (₹0 AI Cost)
function calculateDeterministicSeo() {
    var title = $('#blog_title').val().trim();
    var slug = $('#blog_slug').val().trim();
    var metaTitle = $('#meta_title').val().trim() || title;
    var metaDesc = $('#meta_descriptions').val().trim();
    var keyword = $('#focus_keyword').val().trim().toLowerCase();
    
    // Content extraction from TinyMCE or textarea
    var contentHtml = '';
    if (typeof tinymce !== 'undefined' && tinymce.get('description')) {
        contentHtml = tinymce.get('description').getContent();
    } else {
        contentHtml = $('#description').val();
    }
    var contentText = $('<div>').html(contentHtml).text().trim();
    var wordCount = contentText ? contentText.split(/\s+/).length : 0;
    $('#wordCount').text(wordCount);

    // Update Counters
    var titleLen = metaTitle.length;
    var descLen = metaDesc.length;
    $('#metaTitleCounter').text(titleLen + ' / 60');
    $('#metaDescCounter').text(descLen + ' / 155');

    $('#metaTitleCounter').removeClass('valid warning danger');
    if (titleLen >= 30 && titleLen <= 60) $('#metaTitleCounter').addClass('valid');
    else if (titleLen > 60) $('#metaTitleCounter').addClass('danger');
    else $('#metaTitleCounter').addClass('warning');

    $('#metaDescCounter').removeClass('valid warning danger');
    if (descLen >= 110 && descLen <= 155) $('#metaDescCounter').addClass('valid');
    else if (descLen > 155) $('#metaDescCounter').addClass('danger');
    else $('#metaDescCounter').addClass('warning');

    // Update Google SERP Preview
    $('#previewSlug').text(slug || 'post-slug');
    $('#previewTitle').text(metaTitle || 'Enter a title for this blog post...');
    $('#previewDesc').text(metaDesc || 'Enter a meta description to see how this career article will appear in Google search results.');

    // Calculate Checklist Items
    var score = 0;

    // 1. Focus Keyword in Title (15 pts)
    var inTitle = keyword && title.toLowerCase().includes(keyword);
    updateCheck('check-kw-title', inTitle, 'Focus keyword in Blog Title');
    if (inTitle) score += 15;

    // 2. Focus Keyword in Meta Desc (15 pts)
    var inDesc = keyword && metaDesc.toLowerCase().includes(keyword);
    updateCheck('check-kw-desc', inDesc, 'Focus keyword in Meta Description');
    if (inDesc) score += 15;

    // 3. Focus Keyword in Slug (10 pts)
    var kwSlug = keyword ? keyword.replace(/\s+/g, '-') : '';
    var inSlug = kwSlug && slug.toLowerCase().includes(kwSlug);
    updateCheck('check-kw-slug', inSlug, 'Focus keyword in URL Slug');
    if (inSlug) score += 10;

    // 4. Focus Keyword in Intro (10 pts)
    var first150Words = contentText.split(/\s+/).slice(0, 150).join(' ').toLowerCase();
    var inIntro = keyword && first150Words.includes(keyword);
    updateCheck('check-kw-intro', inIntro, 'Focus keyword in Content Intro');
    if (inIntro) score += 10;

    // 5. Content Length >= 300 words (15 pts)
    var goodLen = wordCount >= 300;
    updateCheck('check-length', goodLen, 'Content length: ' + wordCount + ' words (min 300)');
    if (goodLen) score += 15;
    else if (wordCount >= 150) score += 8;

    // 6. Headings (H2 / H3) (10 pts)
    var hasHeadings = /<h[2-4]/i.test(contentHtml);
    updateCheck('check-headings', hasHeadings, 'H2 / H3 Subheadings used');
    if (hasHeadings) score += 10;

    // 7. Keyword Density (10 pts)
    var density = 0;
    if (keyword && wordCount > 0) {
        var kwMatches = (contentText.toLowerCase().match(new RegExp(keyword, 'g')) || []).length;
        density = ((kwMatches / wordCount) * 100).toFixed(1);
    }
    $('#kwDensity').text(density + '%');
    var goodDensity = density >= 0.8 && density <= 3.0;
    updateCheck('check-density', goodDensity, 'Keyword Density: ' + density + '% (Aim 1.0 - 2.5%)');
    if (goodDensity) score += 10;
    else if (density > 0) score += 5;

    // 8. Featured Image (5 pts)
    var hasImg = '{{ $blog->image }}' !== '' || $('#featured_image_input').val() !== '' || $('#imagePreviewContainer').is(':visible');
    updateCheck('check-feat-img', hasImg, 'Featured Image provided');
    if (hasImg) score += 5;

    // 9. Meta Length checks (10 pts)
    var goodTitleLen = titleLen >= 20 && titleLen <= 65;
    updateCheck('check-title-len', goodTitleLen, 'Title length optimal (' + titleLen + '/60 chars)');
    if (goodTitleLen) score += 5;

    var goodDescLen = descLen >= 80 && descLen <= 160;
    updateCheck('check-desc-len', goodDescLen, 'Meta description optimal (' + descLen + '/155 chars)');
    if (goodDescLen) score += 5;

    // Final Gauge Update
    $('#seoScoreVal').text(score);
    var $gauge = $('#seoScoreGauge');
    var $label = $('#seoScoreLabel');
    var $sum = $('#seoScoreSummary');

    if (score >= 80) {
        $gauge.css('background', '#03855c');
        $label.text('Excellent SEO').css('color', '#03855c');
        $sum.text('Great job! This post is highly optimized for search rankings.');
    } else if (score >= 60) {
        $gauge.css('background', '#2563EB');
        $label.text('Good SEO').css('color', '#2563EB');
        $sum.text('Good progress. Add more content or headings for top score.');
    } else if (score >= 40) {
        $gauge.css('background', '#EA580C');
        $label.text('Needs Improvement').css('color', '#EA580C');
        $sum.text('Include your focus keyword in title, meta description & URL.');
    } else {
        $gauge.css('background', '#DC2626');
        $label.text('Poor SEO').css('color', '#DC2626');
        $sum.text('Add title, meta description & content to evaluate.');
    }
}

function updateCheck(elemId, isValid, text) {
    var $el = $('#' + elemId);
    if (isValid) {
        $el.html('<i class="fa fa-check-circle"></i> <span>' + text + '</span>');
    } else {
        $el.html('<i class="fa fa-times-circle"></i> <span>' + text + '</span>');
    }
}

// Request AI Assistance (Strict Preview & Accept Workflow)
function requestAiSeo(type) {
    var title = $('#blog_title').val().trim();
    var content = '';
    if (typeof tinymce !== 'undefined' && tinymce.get('description')) {
        content = tinymce.get('description').getContent();
    } else {
        content = $('#description').val();
    }
    var keyword = $('#focus_keyword').val().trim();

    if (!title && !content) {
        alert('Please enter a Blog Title or content first so AI can generate accurate suggestions.');
        return;
    }

    var currentVal = '';
    var fieldTitle = '';
    if (type === 'meta_title') {
        currentVal = $('#meta_title').val() || $('#blog_title').val();
        fieldTitle = 'SEO Meta Title';
    } else if (type === 'meta_description') {
        currentVal = $('#meta_descriptions').val();
        fieldTitle = 'SEO Meta Description';
    } else if (type === 'focus_keyword') {
        currentVal = $('#focus_keyword').val();
        fieldTitle = 'Focus Keyword';
    }

    $('#aiModalFieldTitle').text(fieldTitle + ' Suggestion');
    $('#aiCurrentVal').text(currentVal || '(Empty)');
    $('#aiSuggestedVal').html('<i class="fa fa-spinner fa-spin"></i> Generating tailored suggestion...');
    $('#btnAcceptAiSuggestion').prop('disabled', true);
    $('#modalAiReview').modal('show');

    $.ajax({
        url: '{{ route("admin.blog.ai.seo") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            type: type,
            title: title,
            content: content,
            focus_keyword: keyword
        },
        success: function(res) {
            if (res.success && res.suggestion) {
                pendingAiField = type;
                pendingAiText = res.suggestion;
                $('#aiSuggestedVal').text(res.suggestion);
                $('#aiProviderBadge').text('Powered by AI Provider');
                $('#btnAcceptAiSuggestion').prop('disabled', false);
            } else {
                $('#aiSuggestedVal').text('Could not generate suggestion. Please try again.');
            }
        },
        error: function(err) {
            $('#aiSuggestedVal').text('AI service temporarily busy. Please try again.');
        }
    });
}

$('#btnAcceptAiSuggestion').on('click', function() {
    if (!pendingAiField || !pendingAiText) return;

    if (pendingAiField === 'meta_title') {
        $('#meta_title').val(pendingAiText);
    } else if (pendingAiField === 'meta_description') {
        $('#meta_descriptions').val(pendingAiText);
    } else if (pendingAiField === 'focus_keyword') {
        $('#focus_keyword').val(pendingAiText);
    }

    $('#modalAiReview').modal('hide');
    calculateDeterministicSeo();
});

// Periodic check for TinyMCE content updates
setInterval(function() {
    calculateDeterministicSeo();
}, 2000);

$(document).ready(function() {
    calculateDeterministicSeo();
});
</script>
@endpush
@endsection