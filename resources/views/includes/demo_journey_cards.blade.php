<style>
/* Three Core Journey Cards Section ("What Are You Looking For?") */
.journey-section {
    padding: 32px 0 20px 0;
    background: #F8FAFC;
    position: relative;
    width: 100%;
}
.journey-header {
    margin-bottom: 20px;
    text-align: left;
}
.journey-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #EFF6FF;
    color: #2563EB;
    font-size: 11.5px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.9px;
    padding: 5px 14px;
    border-radius: 50px;
    margin-bottom: 8px;
}
.journey-title {
    font-size: 24px;
    font-weight: 800;
    color: #0F172A;
    letter-spacing: -0.4px;
    margin: 0;
}

/* Individual Journey Card: Larger Image Left, Text Content Right */
.journey-card {
    position: relative;
    border-radius: 16px;
    border: 1px solid #E2E8F0;
    background: #FFFFFF;
    display: flex;
    flex-direction: row;
    align-items: stretch;
    overflow: hidden;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
    margin-bottom: 18px;
    box-sizing: border-box;
}
.journey-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 26px rgba(15, 23, 42, 0.09);
}

/* Card Themes & Accents */
.journey-card-seekers {
    border-color: #E0E7FF;
}
.journey-card-seekers:hover {
    border-color: #3B82F6;
}
.journey-card-seekers .jc-image-col {
    background: linear-gradient(180deg, #EFF6FF 0%, #DBEAFE 100%);
}

.journey-card-employers {
    border-color: #DCFCE7;
}
.journey-card-employers:hover {
    border-color: #10B981;
}
.journey-card-employers .jc-image-col {
    background: linear-gradient(180deg, #F0FDF4 0%, #DCFCE7 100%);
}

.journey-card-biz {
    border-color: #F3E8FF;
}
.journey-card-biz:hover {
    border-color: #8B5CF6;
}
.journey-card-biz .jc-image-col {
    background: linear-gradient(180deg, #FAF5FF 0%, #F3E8FF 100%);
}

/* LEFT: Well-Proportioned Image Column */
.jc-image-col {
    width: 140px;
    min-width: 140px;
    max-width: 140px;
    display: flex;
    align-items: stretch;
    justify-content: center;
    position: relative;
    overflow: hidden;
    padding: 0;
    margin: 0;
    flex-shrink: 0;
}

/* Card 1 & Card 3: Full-Bleed Photos */
.jc-photo-fill {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center top;
    display: block;
    transition: transform 0.3s ease;
}
.journey-card:hover .jc-photo-fill {
    transform: scale(1.05);
}

/* Card 2: Cutout Portrait Standing on Gradient */
.jc-cutout-wrap {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding-top: 10px;
}
.jc-cutout-img {
    width: 100%;
    height: 100%;
    max-height: 195px;
    object-fit: contain;
    object-position: bottom center;
    display: block;
    transform: scale(1.06);
    transform-origin: bottom center;
    transition: transform 0.3s ease;
}
.journey-card:hover .jc-cutout-img {
    transform: scale(1.10);
}

/* RIGHT: Text Content Column */
.jc-content-col {
    flex: 1;
    min-width: 0;
    padding: 18px 18px 16px 14px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

/* Text Hierarchy */
.jc-eyebrow {
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 3px;
    line-height: 1.2;
}
.jc-eyebrow-seekers {
    color: #2563EB;
}
.jc-eyebrow-employers {
    color: #059669;
}
.jc-eyebrow-biz {
    color: #7C3AED;
}

.jc-title {
    font-size: 21px;
    font-weight: 800;
    color: #0F172A;
    letter-spacing: -0.3px;
    margin: 0 0 5px 0;
    line-height: 1.2;
}

.jc-desc {
    font-size: 12.5px;
    color: #475569;
    line-height: 1.45;
    margin: 0 0 14px 0;
}

/* Call-to-Action (CTA) Button */
.jc-btn-wrap {
    margin-top: auto;
}
.jc-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    font-size: 13px;
    font-weight: 700;
    padding: 9.5px 16px;
    border-radius: 10px;
    text-decoration: none !important;
    transition: all 0.2s ease;
    color: #FFFFFF !important;
    width: 100%;
    min-height: 40px;
    text-align: center;
    box-sizing: border-box;
}
.jc-btn i {
    transition: transform 0.2s ease;
}
.jc-btn:hover i {
    transform: translateX(3px);
}

.jc-btn-blue {
    background: #2563EB;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.22);
}
.jc-btn-blue:hover {
    background: #1D4ED8;
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.32);
}

.jc-btn-green {
    background: #059669;
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.22);
}
.jc-btn-green:hover {
    background: #047857;
    box-shadow: 0 6px 16px rgba(5, 150, 105, 0.32);
}

.jc-btn-purple {
    background: #7C3AED;
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.22);
}
.jc-btn-purple:hover {
    background: #6D28D9;
    box-shadow: 0 6px 16px rgba(124, 58, 237, 0.32);
}

/* Mobile Responsiveness */
@media (max-width: 991px) {
    .journey-card {
        margin-bottom: 16px;
    }
}

@media (max-width: 767px) {
    .journey-section {
        padding: 20px 0 16px 0 !important;
    }
    .journey-header {
        margin-bottom: 16px !important;
    }
    .journey-badge {
        font-size: 11px !important;
        padding: 4px 12px !important;
    }
    .journey-title {
        font-size: 20px !important;
    }
    .journey-card {
        margin-bottom: 14px !important;
        border-radius: 14px !important;
    }
    .jc-image-col {
        width: 120px !important;
        min-width: 120px !important;
        max-width: 120px !important;
    }
    .jc-cutout-img {
        max-height: 170px !important;
    }
    .jc-content-col {
        padding: 14px 14px 12px 12px !important;
    }
    .jc-eyebrow {
        font-size: 10px !important;
        margin-bottom: 2px !important;
    }
    .jc-title {
        font-size: 18px !important;
        margin-bottom: 4px !important;
    }
    .jc-desc {
        font-size: 12px !important;
        margin-bottom: 10px !important;
        line-height: 1.38 !important;
    }
    .jc-btn {
        padding: 8.5px 12px !important;
        font-size: 12.5px !important;
        min-height: 38px !important;
        border-radius: 9px !important;
    }
}

@media (max-width: 380px) {
    .jc-image-col {
        width: 105px !important;
        min-width: 105px !important;
        max-width: 105px !important;
    }
    .jc-title {
        font-size: 16.5px !important;
    }
    .jc-desc {
        font-size: 11.5px !important;
    }
}
</style>

@php
$siteSetting = $siteSetting ?? \App\SiteSetting::first();

// Card 1 values
$c1_img = (!empty($siteSetting->journey_c1_image) && file_exists(public_path('sitesetting_images/' . $siteSetting->journey_c1_image)))
    ? asset('sitesetting_images/' . $siteSetting->journey_c1_image)
    : asset('images/seeker-woman.jpg');
$c1_eyebrow = $siteSetting->journey_c1_eyebrow ?: __('For Job Seekers');
$c1_title = $siteSetting->journey_c1_title ?: __('Find Jobs');
$c1_desc = $siteSetting->journey_c1_desc ?: __('Explore thousands of verified openings and apply directly with top employers.');
$c1_btn_text = $siteSetting->journey_c1_btn_text ?: __('Browse Jobs');
$c1_btn_url = !empty($siteSetting->journey_c1_btn_url) ? url($siteSetting->journey_c1_btn_url) : route('job.list');

// Card 2 values
$c2_img = (!empty($siteSetting->journey_c2_image) && file_exists(public_path('sitesetting_images/' . $siteSetting->journey_c2_image)))
    ? asset('sitesetting_images/' . $siteSetting->journey_c2_image)
    : asset('images/hero-man.png');
$c2_eyebrow = $siteSetting->journey_c2_eyebrow ?: __('For Employers');
$c2_title = $siteSetting->journey_c2_title ?: __('Hire Talent');
$c2_desc = $siteSetting->journey_c2_desc ?: __('Post jobs, discover verified candidates and hire the right talent quickly.');
$c2_btn_text = $siteSetting->journey_c2_btn_text ?: __('Post a Job');
$c2_btn_url = !empty($siteSetting->journey_c2_btn_url) ? url($siteSetting->journey_c2_btn_url) : route('post.job');

// Card 3 values
$c3_img = (!empty($siteSetting->journey_c3_image) && file_exists(public_path('sitesetting_images/' . $siteSetting->journey_c3_image)))
    ? asset('sitesetting_images/' . $siteSetting->journey_c3_image)
    : asset('images/store-business.jpg');
$c3_eyebrow = $siteSetting->journey_c3_eyebrow ?: __('For Businesses');
$c3_title = $siteSetting->journey_c3_title ?: __('Get Discovered');
$c3_desc = $siteSetting->journey_c3_desc ?: __('List your local business, reach nearby customers, and grow your presence.');
$c3_btn_text = $siteSetting->journey_c3_btn_text ?: __('Find Businesses');
$c3_btn_url = !empty($siteSetting->journey_c3_btn_url) ? url($siteSetting->journey_c3_btn_url) : route('business.list');
@endphp

<div class="journey-section">
    <div class="container">
        {{-- Section Header: What Are You Looking For? --}}
        <div class="journey-header">
            <div class="journey-badge">
                <i class="fa fa-compass"></i> {{ $siteSetting->journey_badge_text ?: __('What Are You Looking For?') }}
            </div>
            <h2 class="journey-title">
                {{ $siteSetting->journey_main_title ?: __('Choose Your Path on JobNBiz') }}
            </h2>
        </div>

        {{-- 3 Journey Cards Grid --}}
        <div class="row">
            {{-- Card 1: For Job Seekers --}}
            <div class="col-lg-4 col-md-12 col-12 mb-3 mb-lg-0">
                <div class="journey-card journey-card-seekers">
                    {{-- LEFT: Larger, well-proportioned portrait image --}}
                    <div class="jc-image-col">
                        <img src="{{ $c1_img }}" 
                             alt="{{ $c1_title }}" 
                             class="jc-photo-fill"
                             onerror="this.src='{{ asset('images/seeker-woman.jpg') }}'">
                    </div>

                    {{-- RIGHT: Text content (Heading -> Subheading -> CTA) --}}
                    <div class="jc-content-col">
                        <div>
                            <div class="jc-eyebrow jc-eyebrow-seekers">{{ $c1_eyebrow }}</div>
                            <h3 class="jc-title">{{ $c1_title }}</h3>
                            <p class="jc-desc">
                                {{ $c1_desc }}
                            </p>
                        </div>

                        <div class="jc-btn-wrap">
                            <a href="{{ $c1_btn_url }}" class="jc-btn jc-btn-blue">
                                <span>{{ $c1_btn_text }}</span> <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: For Employers --}}
            <div class="col-lg-4 col-md-12 col-12 mb-3 mb-lg-0">
                <div class="journey-card journey-card-employers">
                    {{-- LEFT: Standing cutout portrait image --}}
                    <div class="jc-image-col">
                        <div class="jc-cutout-wrap">
                            <img src="{{ $c2_img }}" 
                                 alt="{{ $c2_title }}" 
                                 class="jc-cutout-img"
                                 onerror="this.src='{{ asset('images/hero-man.png') }}'">
                        </div>
                    </div>

                    {{-- RIGHT: Text content (Heading -> Subheading -> CTA) --}}
                    <div class="jc-content-col">
                        <div>
                            <div class="jc-eyebrow jc-eyebrow-employers">{{ $c2_eyebrow }}</div>
                            <h3 class="jc-title">{{ $c2_title }}</h3>
                            <p class="jc-desc">
                                {{ $c2_desc }}
                            </p>
                        </div>

                        <div class="jc-btn-wrap">
                            <a href="{{ $c2_btn_url }}" class="jc-btn jc-btn-green">
                                <span>{{ $c2_btn_text }}</span> <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 3: For Businesses --}}
            <div class="col-lg-4 col-md-12 col-12">
                <div class="journey-card journey-card-biz">
                    {{-- LEFT: Storefront business photo --}}
                    <div class="jc-image-col">
                        <img src="{{ $c3_img }}" 
                             alt="{{ $c3_title }}" 
                             class="jc-photo-fill"
                             onerror="this.src='{{ asset('images/store-business.jpg') }}'">
                    </div>

                    {{-- RIGHT: Text content (Heading -> Subheading -> CTA) --}}
                    <div class="jc-content-col">
                        <div>
                            <div class="jc-eyebrow jc-eyebrow-biz">{{ $c3_eyebrow }}</div>
                            <h3 class="jc-title">{{ $c3_title }}</h3>
                            <p class="jc-desc">
                                {{ $c3_desc }}
                            </p>
                        </div>

                        <div class="jc-btn-wrap">
                            <a href="{{ $c3_btn_url }}" class="jc-btn jc-btn-purple">
                                <span>{{ $c3_btn_text }}</span> <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
