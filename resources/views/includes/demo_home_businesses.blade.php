@php
$homeBusinesses = $homeBusinesses ?? \App\Business::with(['category', 'city'])
    ->where('is_active', 1)
    ->orderBy('is_featured', 'desc')
    ->orderByRaw("CASE WHEN verification_status = 'verified' THEN 0 ELSE 1 END")
    ->orderBy('views_count', 'desc')
    ->take(4)
    ->get();

$homeBizCategories = $homeBizCategories ?? \App\BusinessCategory::active()->where('is_featured', 1)->orderBy('sort_order', 'asc')->take(8)->get();
@endphp

@if(count($homeBusinesses) > 0)
<style>
/* Local Business Directory - Scannable & High Hierarchy */
.demo-biz-section {
    background: #FFFFFF;
    padding: 50px 0 42px 0;
    width: 100%;
}
.demo-biz-header {
    margin-bottom: 26px;
}
.demo-biz-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #FAF5FF;
    color: #7C3AED;
    font-size: 11.5px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.9px;
    padding: 5px 14px;
    border-radius: 50px;
    margin-bottom: 8px;
    border: 1px solid #EDE9FE;
}
.demo-biz-title {
    font-size: 26px;
    font-weight: 800;
    color: #0F172A;
    letter-spacing: -0.4px;
    margin: 0 0 6px 0;
}
.demo-biz-subtitle {
    font-size: 14.5px;
    color: #64748B;
    margin: 0;
}
.demo-biz-viewall-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-weight: 700;
    border-radius: 10px;
    font-size: 13.5px;
    padding: 9px 20px;
    color: #7C3AED !important;
    background: #FAF5FF;
    border: 1.5px solid #DDD6FE;
    text-decoration: none !important;
    transition: all 0.2s ease;
}
.demo-biz-viewall-btn:hover {
    background: #7C3AED;
    color: #FFFFFF !important;
    border-color: #7C3AED;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(124, 58, 237, 0.22);
}

/* Category Grid */
.demo-biz-cat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 28px;
}
.demo-biz-cat-box {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 12px 14px;
    text-decoration: none !important;
    transition: all 0.22s ease;
}
.demo-biz-cat-box:hover {
    background: #FFFFFF;
    border-color: #C084FC;
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(124, 58, 237, 0.08);
}
.demo-biz-cat-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #FAF5FF;
    color: #7C3AED;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}
.demo-biz-cat-name {
    font-size: 13px;
    font-weight: 700;
    color: #0F172A;
    line-height: 1.3;
}
.demo-biz-cat-sub {
    font-size: 11px;
    color: #64748B;
    font-weight: 500;
    margin-top: 2px;
}

/* Business Card - High Scannability (<3 seconds) */
.demo-biz-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 20px;
    transition: all 0.25s ease;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: calc(100% - 20px);
    box-sizing: border-box;
}
.demo-biz-card:hover {
    border-color: #C084FC;
    transform: translateY(-3px);
    box-shadow: 0 12px 26px rgba(124, 58, 237, 0.09);
}

.demo-biz-card-head {
    display: flex;
    gap: 14px;
    align-items: flex-start;
    margin-bottom: 12px;
}
.demo-biz-logo {
    width: 52px;
    height: 52px;
    min-width: 52px;
    border-radius: 12px;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 4px;
    flex-shrink: 0;
}
.demo-biz-logo img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
.demo-biz-logo-fallback {
    font-size: 18px;
    font-weight: 800;
    color: #7C3AED;
    background: #FAF5FF;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
}

.demo-biz-head-txt {
    flex: 1;
    min-width: 0;
}
.demo-biz-title-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    margin-bottom: 3px;
}
.demo-biz-name {
    font-size: 16.5px;
    font-weight: 800;
    color: #0F172A;
    margin: 0;
    line-height: 1.3;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.demo-biz-name a {
    color: #0F172A;
    text-decoration: none !important;
    transition: color 0.2s ease;
}
.demo-biz-name a:hover {
    color: #7C3AED;
}
.demo-biz-verified-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 10.5px;
    font-weight: 800;
    background: #ECFDF5;
    color: #059669;
    padding: 2.5px 7px;
    border-radius: 6px;
    flex-shrink: 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Scannable Metadata Chips */
.demo-biz-chips-wrap {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 12px;
}
.demo-biz-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 6px;
}
.demo-biz-chip-cat {
    background: #FAF5FF;
    color: #7C3AED;
}
.demo-biz-chip-loc {
    background: #F1F5F9;
    color: #475569;
}

.demo-biz-desc {
    font-size: 13px;
    color: #475569;
    line-height: 1.45;
    margin: 0 0 14px 0;
}

/* Contact & Action Buttons */
.demo-biz-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    padding-top: 12px;
    border-top: 1px solid #F1F5F9;
    flex-wrap: wrap;
}
.demo-biz-btn-call {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    background: #F1F5F9;
    color: #0F172A !important;
    font-size: 12px;
    font-weight: 700;
    padding: 8px 14px;
    border-radius: 8px;
    text-decoration: none !important;
    transition: all 0.2s ease;
}
.demo-biz-btn-call:hover {
    background: #E2E8F0;
}
.demo-biz-btn-wa {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    background: #ECFDF5;
    color: #059669 !important;
    font-size: 12px;
    font-weight: 700;
    padding: 8px 14px;
    border-radius: 8px;
    text-decoration: none !important;
    transition: all 0.2s ease;
}
.demo-biz-btn-wa:hover {
    background: #059669;
    color: #FFFFFF !important;
}
.demo-biz-btn-view {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    background: #FAF5FF;
    color: #7C3AED !important;
    font-size: 12px;
    font-weight: 700;
    padding: 8px 16px;
    border-radius: 8px;
    text-decoration: none !important;
    margin-left: auto;
    transition: all 0.2s ease;
}
.demo-biz-btn-view:hover {
    background: #7C3AED;
    color: #FFFFFF !important;
}

/* Mobile Responsiveness */
@media (max-width: 991px) {
    .demo-biz-cat-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 767px) {
    .demo-biz-section {
        padding: 30px 0 24px 0 !important;
    }
    .demo-biz-title {
        font-size: 21px !important;
    }
    .demo-biz-subtitle {
        font-size: 13px !important;
    }
    .demo-biz-cat-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 8px !important;
        margin-bottom: 20px !important;
    }
    .demo-biz-cat-box {
        padding: 10px 10px !important;
    }
    .demo-biz-cat-name {
        font-size: 12px !important;
    }
    .demo-biz-card {
        padding: 16px !important;
        border-radius: 14px !important;
        margin-bottom: 14px !important;
    }
    .demo-biz-name {
        font-size: 15.5px !important;
    }
    .demo-biz-actions {
        gap: 6px !important;
    }
    .demo-biz-btn-call, .demo-biz-btn-wa {
        flex: 1 1 auto !important;
        padding: 7px 10px !important;
        font-size: 11.5px !important;
    }
    .demo-biz-btn-view {
        margin-left: 0 !important;
        width: 100% !important;
        padding: 8px 12px !important;
    }
}
</style>

<div class="demo-biz-section">
    <div class="container">
        {{-- Section Header --}}
        <div class="row align-items-center demo-biz-header">
            <div class="col-md-8 col-12">
                <div class="demo-biz-badge">
                    <i class="fa fa-map-marker"></i> {{ __('Local Business Directory') }}
                </div>
                <h2 class="demo-biz-title">
                    {{ __('Explore Verified Local Businesses') }}
                </h2>
                <p class="demo-biz-subtitle">
                    {{ __('Connect directly with trusted local service providers, digital agencies, clinics, and stores near you.') }}
                </p>
            </div>
            <div class="col-md-4 col-12 text-md-right mt-3 mt-md-0 d-none d-md-block">
                <a href="{{ route('business.list') }}" class="demo-biz-viewall-btn">
                    <span>{{ __('View All Businesses') }}</span> <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        {{-- Popular Business Categories Grid --}}
        <div class="demo-biz-cat-grid">
            @foreach($homeBizCategories as $hCat)
            <a href="{{ route('business.list', ['category' => $hCat->slug]) }}" class="demo-biz-cat-box">
                <div class="demo-biz-cat-icon">
                    <i class="fa {{ $hCat->icon ?: 'fa-folder-o' }}"></i>
                </div>
                <div>
                    <div class="demo-biz-cat-name">{{ $hCat->name }}</div>
                    <div class="demo-biz-cat-sub">{{ __('Explore listings') }} <i class="fa fa-angle-right"></i></div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Verified Businesses Grid (Scannable Cards) --}}
        <div class="row">
            @foreach($homeBusinesses as $hBiz)
            <div class="col-lg-6 col-md-6 col-12 mb-3">
                <div class="demo-biz-card">
                    <div>
                        <div class="demo-biz-card-head">
                            <div class="demo-biz-logo">
                                @if($hBiz->logo)
                                    <img src="{{ $hBiz->getLogoUrl() }}" alt="{{ $hBiz->name }}" width="48" height="48" loading="lazy">
                                @else
                                    <div class="demo-biz-logo-fallback">
                                        {{ strtoupper(substr($hBiz->name ?? 'B', 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="demo-biz-head-txt">
                                <div class="demo-biz-title-wrap">
                                    <h3 class="demo-biz-name">
                                        <a href="{{ route('business.detail', $hBiz->slug) }}">{{ $hBiz->name }}</a>
                                    </h3>
                                    @if($hBiz->verification_status === 'verified')
                                        <span class="demo-biz-verified-badge"><i class="fa fa-check-circle"></i> {{ __('Verified') }}</span>
                                    @endif
                                </div>

                                {{-- Scannable Meta Badges --}}
                                <div class="demo-biz-chips-wrap">
                                    @if($hBiz->category)
                                        <span class="demo-biz-chip demo-biz-chip-cat">
                                            <i class="fa {{ $hBiz->category->icon ?: 'fa-folder-o' }}"></i> {{ $hBiz->category->name }}
                                        </span>
                                    @endif
                                    <span class="demo-biz-chip demo-biz-chip-loc">
                                        <i class="fa fa-map-marker"></i> {{ $hBiz->getLocationLabel() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if(!empty($hBiz->short_description))
                        <p class="demo-biz-desc">
                            {{ \Illuminate\Support\Str::limit($hBiz->short_description, 105) }}
                        </p>
                        @endif
                    </div>

                    {{-- Contact & Direct Interaction Actions --}}
                    <div class="demo-biz-actions">
                        @if(!empty($hBiz->clean_phone))
                        <a href="tel:{{ $hBiz->clean_phone }}" class="demo-biz-btn-call">
                            <i class="fa fa-phone"></i> {{ __('Call') }}
                        </a>
                        @endif

                        @if(!empty($hBiz->whatsapp_url))
                        <a href="{{ $hBiz->whatsapp_url }}" target="_blank" class="demo-biz-btn-wa">
                            <i class="fa fa-whatsapp"></i> {{ __('WhatsApp') }}
                        </a>
                        @endif

                        <a href="{{ route('business.detail', $hBiz->slug) }}" class="demo-biz-btn-view">
                            <span>{{ __('View Profile') }}</span> <i class="fa fa-angle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Mobile View All Button --}}
        <div class="text-center mt-3 d-block d-md-none">
            <a href="{{ route('business.list') }}" class="demo-biz-viewall-btn" style="width: 100%; justify-content: center;">
                <span>{{ __('View All Businesses') }}</span> <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
@endif
