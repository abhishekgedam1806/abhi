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
<div class="section" style="background: #F8FAFC; padding: 50px 0;">
    <div class="container">
        {{-- Section Header --}}
        <div class="row align-items-center" style="margin-bottom: 28px;">
            <div class="col-md-8">
                <div style="font-size: 13px; font-weight: 700; color: #2563EB; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                    <i class="fa fa-map-marker"></i> Local Directory
                </div>
                <h2 style="font-size: 24px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.3px;">
                    Explore Verified Local Businesses Near You
                </h2>
                <p style="color: #64748B; font-size: 14px; margin-top: 4px; margin-bottom: 0;">
                    Connect directly with trusted local service providers, digital agencies, clinics, and stores.
                </p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="{{ route('business.list') }}" class="btn btn-outline-primary" style="font-weight: 700; border-radius: 8px; font-size: 13.5px; padding: 8px 18px;">
                    View All Businesses <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        {{-- Popular Business Categories Grid --}}
        <div class="row" style="margin-bottom: 30px;">
            @foreach($homeBizCategories as $hCat)
            <div class="col-lg-3 col-md-4 col-6 mb-3">
                <a href="{{ route('business.list', ['category' => $hCat->slug]) }}" class="home-biz-cat-box">
                    <div class="cat-ico-circle">
                        <i class="fa {{ $hCat->icon ?: 'fa-folder-o' }}"></i>
                    </div>
                    <div>
                        <div class="cat-name-txt">{{ $hCat->name }}</div>
                        <div class="cat-sub-txt">Explore listings <i class="fa fa-angle-right"></i></div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        <style>
        .home-biz-cat-box {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 12px 14px;
            text-decoration: none !important;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .home-biz-cat-box:hover {
            border-color: #93C5FD;
            box-shadow: 0 4px 12px rgba(37,99,235,0.08);
            transform: translateY(-2px);
        }
        .home-biz-cat-box .cat-ico-circle {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #EFF6FF;
            color: #2563EB;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }
        .home-biz-cat-box .cat-name-txt {
            font-size: 13px;
            font-weight: 700;
            color: #1E293B;
            line-height: 1.3;
        }
        .home-biz-cat-box .cat-sub-txt {
            font-size: 11.5px;
            color: #64748B;
            font-weight: 500;
            margin-top: 2px;
        }
        </style>

        {{-- Featured Businesses Grid --}}
        <div class="row">
            @foreach($homeBusinesses as $hBiz)
            <div class="col-lg-6 col-md-6 col-12 mb-4">
                <div class="home-biz-card">
                    <div style="display: flex; gap: 14px; align-items: flex-start;">
                        <div class="home-biz-logo">
                            @if($hBiz->logo)
                            <img src="{{ $hBiz->getLogoUrl() }}" alt="{{ $hBiz->name }}">
                            @else
                            <i class="fa fa-building-o" style="font-size: 22px; color: #94A3B8;"></i>
                            @endif
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <div class="home-biz-title-wrap">
                                <a href="{{ route('business.detail', $hBiz->slug) }}" class="home-biz-title">{{ $hBiz->name }}</a>
                                @if($hBiz->verification_status === 'verified')
                                <span class="home-biz-verified-badge"><i class="fa fa-check-circle"></i> Verified</span>
                                @endif
                            </div>
                            @if($hBiz->category)
                            <div class="home-biz-cat-label">
                                <i class="fa {{ $hBiz->category->icon ?: 'fa-folder-o' }}"></i> {{ $hBiz->category->name }}
                            </div>
                            @endif
                            <div class="home-biz-addr">
                                <i class="fa fa-map-marker"></i> {{ $hBiz->getLocationLabel() }}
                            </div>
                        </div>
                    </div>

                    @if(!empty($hBiz->short_description))
                    <p style="font-size: 13px; color: #475569; margin: 10px 0; line-height: 1.45;">
                        {{ \Illuminate\Support\Str::limit($hBiz->short_description, 95) }}
                    </p>
                    @endif

                    {{-- Actions Bar --}}
                    <div class="home-biz-actions">
                        <a href="tel:{{ $hBiz->clean_phone }}" class="btn-home-biz-call">
                            <i class="fa fa-phone"></i> Call
                        </a>
                        <a href="{{ $hBiz->whatsapp_url }}" target="_blank" class="btn-home-biz-wa">
                            <i class="fa fa-whatsapp"></i> WhatsApp
                        </a>
                        <a href="{{ route('business.detail', $hBiz->slug) }}" class="btn-home-biz-view">
                            View Details <i class="fa fa-angle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<style>
.home-biz-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    padding: 18px 20px;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
}
.home-biz-card:hover {
    border-color: #CBD5E1;
    box-shadow: 0 6px 18px rgba(0,0,0,0.06);
    transform: translateY(-2px);
}
.home-biz-logo {
    width: 54px;
    height: 54px;
    border-radius: 10px;
    background: #F1F5F9;
    border: 1px solid #E2E8F0;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
}
.home-biz-logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.home-biz-title-wrap {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
.home-biz-title {
    font-size: 15.5px;
    font-weight: 700;
    color: #0F172A;
    text-decoration: none !important;
}
.home-biz-title:hover { color: #2563EB !important; }
.home-biz-verified-badge {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    background: #ECFDF5;
    color: #03855c;
    font-size: 10.5px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 12px;
    border: 1px solid #A7F3D0;
}
.home-biz-cat-label {
    font-size: 12.5px;
    font-weight: 600;
    color: #2563EB;
    margin-top: 2px;
}
.home-biz-addr {
    font-size: 12px;
    color: #64748B;
    display: flex;
    align-items: center;
    gap: 4px;
    margin-top: 3px;
}
.home-biz-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    border-top: 1px solid #F1F5F9;
    padding-top: 12px;
    margin-top: 8px;
}
.btn-home-biz-call {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #1D4ED8;
    background: #2563EB;
    color: #fff !important;
    font-size: 12px;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 7px;
    border: 1px solid #1D4ED8;
    text-decoration: none !important;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
}
.btn-home-biz-call:hover {
    background: #1D4ED8;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
    transform: translateY(-1px);
}
.btn-home-biz-wa {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #03855c !important;
    color: #fff !important;
    font-size: 12px;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 7px;
    border: none !important;
    text-decoration: none !important;
    box-shadow: none !important;
    transition: none !important;
}
.btn-home-biz-wa:hover {
    background: #03855c !important;
    color: #fff !important;
    transform: none !important;
    box-shadow: none !important;
}
.btn-home-biz-view {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    font-size: 12px;
    font-weight: 600;
    color: #475569 !important;
    margin-left: auto;
    text-decoration: none !important;
}
.btn-home-biz-view:hover { color: #2563EB !important; }
</style>
@endif
