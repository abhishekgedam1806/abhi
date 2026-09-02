@extends('layouts.app')

@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->

<!-- Business Section Hero Search Banner -->
<div class="apna-hero-section" style="padding: 40px 0 50px; position: relative;">
    <div class="container">
        <div class="row align-items-center">
            
            {{-- LEFT: Title, Description, Search Card --}}
            <div class="col-lg-7 col-md-7 col-12">
                <div class="apna-hero-badge" style="background: #2563EB; color: #fff; font-size: 11.5px; font-weight: 700; padding: 5px 14px; border-radius: 50px; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px; box-shadow: 0 2px 8px rgba(37,99,235,0.25);">
                    INDIA'S #1 LOCAL BUSINESS DIRECTORY
                </div>
                
                <h1 style="font-size: 36px; font-weight: 900; color: #0F172A !important; line-height: 1.22; margin-bottom: 10px; letter-spacing: -0.5px;">
                    Find & Connect with <br>
                    <span style="color: #2563EB;">Top Verified Businesses</span>
                </h1>
                
                <p style="color: #475569 !important; font-size: 15px; line-height: 1.55; margin-bottom: 22px; max-width: 540px;">
                    Discover trusted local services, doctors, shops, contractors, and agencies in your city with verified phone numbers & direct WhatsApp.
                </p>

                {{-- DEDICATED BUSINESS SEARCH CARD --}}
                <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 18px; padding: 22px 24px; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);">
                    <form action="{{ route('business.list') }}" method="GET">
                        <div class="form-group" style="margin-bottom: 14px;">
                            <label for="bizsearch" style="font-size: 12px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                                <i class="fa fa-search" style="color: #2563EB;"></i> Business Name, Service or Keywords
                            </label>
                            <input type="text" name="search" id="bizsearch" class="form-control" placeholder="e.g. SEO Agency, AC Repair, Doctor Clinic, Electrician..." value="{{ $params['search'] ?? '' }}" autocomplete="off" style="height: 46px; border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 0 14px; font-size: 14px; color: #0F172A; background: #F8FAFC;" />
                        </div>
                        
                        <div class="row" style="margin-bottom: 14px;">
                            <div class="col-lg-6 col-md-6 col-12 mb-2 mb-md-0">
                                <label for="biz_location" style="font-size: 12px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                                    <i class="fa fa-map-marker" style="color: #DC2626;"></i> City or Locality
                                </label>
                                <input type="text" name="location" id="biz_location" class="form-control" placeholder="e.g. Nagpur, Mumbai, Pune..." value="{{ $params['location'] ?? '' }}" autocomplete="off" style="height: 46px; border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 0 14px; font-size: 14px; color: #0F172A; background: #F8FAFC;" />
                            </div>
                            
                            <div class="col-lg-6 col-md-6 col-12">
                                <label for="biz_category" style="font-size: 12px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                                    <i class="fa fa-th-large" style="color: #2563EB;"></i> Category
                                </label>
                                <select name="category" id="biz_category" class="form-control" style="height: 46px; border: 1.5px solid #CBD5E1; border-radius: 10px; padding: 0 12px; font-size: 14px; color: #0F172A; background: #F8FAFC;">
                                    <option value="">All Business Categories</option>
                                    @foreach($categories as $bCat)
                                    <option value="{{ $bCat->slug }}" {{ ($params['category'] ?? '') == $bCat->slug ? 'selected' : '' }}>{{ $bCat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn" style="width: 100%; height: 48px; background: #2563EB; color: #FFFFFF !important; font-weight: 800; border-radius: 11px; border: none; box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35); font-size: 15px; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: all 0.2s ease;">
                            <i class="fa fa-search"></i> Search Businesses
                        </button>
                    </form>
                </div>

            </div>

            {{-- RIGHT: Solid Dark High-Contrast Growth Card --}}
            <div class="col-lg-5 col-md-5 col-12 d-none d-md-block text-right">
                <div style="background: #0F172A; border: 1px solid #334155; border-radius: 20px; padding: 28px 24px; text-align: left; box-shadow: 0 15px 35px -5px rgba(15, 23, 42, 0.25); color: #FFFFFF; max-width: 380px; margin-left: auto;">
                    
                    <div style="font-size: 12px; font-weight: 800; color: #34D399; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                        <i class="fa fa-check-circle" style="color: #34D399;"></i> BUSINESS GROWTH
                    </div>

                    <h3 style="font-size: 21px; font-weight: 800; color: #FFFFFF !important; margin: 0 0 16px 0; line-height: 1.35;">
                        Grow Your Local Business & Get 100% Free Leads
                    </h3>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
                        <div style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.12); padding: 14px 12px; border-radius: 12px;">
                            <div style="font-size: 24px; font-weight: 800; color: #60A5FA;">10,000+</div>
                            <div style="font-size: 12px; font-weight: 600; color: #E2E8F0; margin-top: 2px;">Verified Listings</div>
                        </div>
                        <div style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.12); padding: 14px 12px; border-radius: 12px;">
                            <div style="font-size: 24px; font-weight: 800; color: #34D399;">50,000+</div>
                            <div style="font-size: 12px; font-weight: 600; color: #E2E8F0; margin-top: 2px;">Monthly Leads</div>
                        </div>
                    </div>

                    <a href="{{ route('add.business') }}" class="btn" style="width: 100%; height: 48px; background: #03855c; color: #FFFFFF !important; font-weight: 800; border-radius: 11px; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none !important; box-shadow: 0 4px 14px rgba(3, 133, 92, 0.35); font-size: 14.5px;">
                        <i class="fa fa-plus-circle"></i> List Your Business Free
                    </a>

                    <div style="margin-top: 14px; font-size: 12px; color: #94A3B8; text-align: center; display: flex; align-items: center; justify-content: center; gap: 6px;">
                        <i class="fa fa-shield" style="color: #34D399;"></i> Instant Listing • Direct WhatsApp Leads
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<style>
/* ==========================================================================
   Business Directory Modern UI
   ========================================================================== */
.biz-dir-wrap {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    padding: 30px 0 60px;
    background: #F8FAFC;
    color: #1E293B;
}

/* Category Quick Filter Chips */
.biz-cat-pills-bar {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    padding-bottom: 12px;
    margin-bottom: 24px;
    scrollbar-width: thin;
}
.biz-cat-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 50px;
    padding: 6px 14px;
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    text-decoration: none !important;
    white-space: nowrap;
    transition: all 0.15s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}
.biz-cat-pill:hover, .biz-cat-pill.active {
    background: #2563EB;
    color: #FFFFFF !important;
    border-color: #2563EB;
}

/* Filter Card */
.biz-filter-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}
.biz-filter-title {
    font-size: 14px;
    font-weight: 700;
    color: #0F172A;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.biz-filter-input {
    width: 100%;
    height: 38px;
    border: 1px solid #CBD5E1;
    border-radius: 8px;
    padding: 0 12px;
    font-size: 13px;
    color: #1E293B;
    background: #fff;
    outline: none;
    transition: border-color 0.15s;
    margin-bottom: 12px;
}
.biz-filter-input:focus {
    border-color: #2563EB;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}

/* Business Result Card */
.biz-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    padding: 20px;
    margin-bottom: 16px;
    transition: all 0.2s ease;
    box-shadow: 0 1px 4px rgba(0,0,0,0.03);
    position: relative;
}
.biz-card:hover {
    border-color: #CBD5E1;
    box-shadow: 0 6px 20px rgba(0,0,0,0.06);
    transform: translateY(-2px);
}
.biz-card-logo {
    width: 64px;
    height: 64px;
    border-radius: 10px;
    background: #F1F5F9;
    border: 1px solid #E2E8F0;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
}
.biz-card-logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.biz-card-title {
    font-size: 17px;
    font-weight: 700;
    color: #0F172A;
    margin: 0 0 4px;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
.biz-card-title a {
    color: #0F172A;
    text-decoration: none;
}
.biz-card-title a:hover {
    color: #2563EB;
}
.biz-badge-verified {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #ECFDF5;
    color: #03855c;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
    border: 1px solid #A7F3D0;
}
.biz-badge-featured {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #FEF3C7;
    color: #D97706;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
    border: 1px solid #FDE68A;
}
.biz-card-category {
    font-size: 13px;
    font-weight: 600;
    color: #2563EB;
    margin-bottom: 6px;
}
.biz-card-address {
    font-size: 13px;
    color: #64748B;
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 10px;
}
.biz-card-services {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 14px;
}
.biz-service-chip {
    background: #F1F5F9;
    color: #475569;
    font-size: 11.5px;
    font-weight: 500;
    padding: 3px 9px;
    border-radius: 6px;
}
.biz-card-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    border-top: 1px solid #F1F5F9;
    padding-top: 14px;
}
.btn-biz-call {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #1D4ED8;
    background: #2563EB;
    color: #FFFFFF !important;
    font-size: 12.5px;
    font-weight: 700;
    padding: 7px 16px;
    border-radius: 8px;
    border: 1px solid #1D4ED8;
    text-decoration: none !important;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
}
.btn-biz-call:hover {
    background: #1D4ED8;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
    transform: translateY(-1px);
}
.btn-biz-wa {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #03855c !important;
    color: #FFFFFF !important;
    font-size: 12.5px;
    font-weight: 700;
    padding: 7px 16px;
    border-radius: 8px;
    border: none !important;
    text-decoration: none !important;
    box-shadow: none !important;
    transition: none !important;
}
.btn-biz-wa:hover {
    background: #03855c !important;
    color: #FFFFFF !important;
    transform: none !important;
    box-shadow: none !important;
}
.btn-biz-details {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #F8FAFC;
    color: #475569 !important;
    border: 1px solid #CBD5E1;
    font-size: 12.5px;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 8px;
    text-decoration: none !important;
    margin-left: auto;
}
.btn-biz-details:hover {
    background: #EDF2F7;
}
.biz-status-dot {
    display: inline-block;
    width: 7px;
    height: 7px;
    border-radius: 50%;
    margin-right: 4px;
}
.biz-status-open { background: #10B981; }
.biz-status-closed { background: #EF4444; }
</style>

<div class="biz-dir-wrap">
    <div class="container">

        {{-- Top Category Pills --}}
        <div class="biz-cat-pills-bar">
            <a href="{{ route('business.list') }}" class="biz-cat-pill {{ empty($params['category']) ? 'active' : '' }}">
                <i class="fa fa-th-large"></i> All Categories
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('business.list', ['category' => $cat->slug]) }}" class="biz-cat-pill {{ ($params['category'] ?? '') == $cat->slug ? 'active' : '' }}">
                <i class="fa {{ $cat->icon ?: 'fa-folder-o' }}"></i> {{ $cat->name }}
            </a>
            @endforeach
        </div>

        <div class="row">
            {{-- LEFT: Filters Sidebar --}}
            <div class="col-lg-4 col-md-4 col-12">
                <form method="GET" action="{{ route('business.list') }}" id="bizFilterForm">
                    @if(!empty($params['category']))
                    <input type="hidden" name="category" value="{{ $params['category'] }}">
                    @endif
                    <input type="hidden" name="lat" id="userLat" value="{{ $params['lat'] ?? '' }}">
                    <input type="hidden" name="lng" id="userLng" value="{{ $params['lng'] ?? '' }}">

                    <div class="biz-filter-card">
                        <div class="biz-filter-title">
                            <i class="fa fa-search" style="color:#2563EB;"></i> Search Keyword
                        </div>
                        <input type="text" name="search" class="biz-filter-input" placeholder="e.g. SEO, AC Repair, Clinic..." value="{{ $params['search'] ?? '' }}">

                        <div class="biz-filter-title">
                            <i class="fa fa-map-marker" style="color:#2563EB;"></i> Location / City
                        </div>
                        <input type="text" name="location" class="biz-filter-input" placeholder="City or Locality..." value="{{ $params['location'] ?? '' }}">

                        {{-- Distance Radius / Near Me --}}
                        <div class="biz-filter-title">
                            <i class="fa fa-compass" style="color:#2563EB;"></i> Distance ("Near Me")
                        </div>
                        <select name="radius" class="biz-filter-input">
                            <option value="">Any Distance</option>
                            <option value="5" {{ ($params['radius'] ?? '') == '5' ? 'selected' : '' }}>Within 5 km</option>
                            <option value="10" {{ ($params['radius'] ?? '') == '10' ? 'selected' : '' }}>Within 10 km</option>
                            <option value="25" {{ ($params['radius'] ?? '') == '25' ? 'selected' : '' }}>Within 25 km</option>
                            <option value="50" {{ ($params['radius'] ?? '') == '50' ? 'selected' : '' }}>Within 50 km</option>
                        </select>
                        <button type="button" class="btn btn-sm btn-default btn-block" id="btnDetectLocation" style="font-size:12px;margin-bottom:14px;border-radius:6px;">
                            <i class="fa fa-crosshairs"></i> Use My Current Location
                        </button>

                        {{-- Category Dropdown --}}
                        <div class="biz-filter-title">
                            <i class="fa fa-folder-open-o" style="color:#2563EB;"></i> Category
                        </div>
                        <select name="category" class="biz-filter-input" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ ($params['category'] ?? '') == $cat->slug ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>

                        {{-- Checkbox Toggles --}}
                        <div style="margin-top: 14px; padding-top: 14px; border-top: 1px solid #F1F5F9;">
                            <label style="display:flex;align-items:center;gap:8px;font-size:13px;font-weight:600;color:#334155;cursor:pointer;margin-bottom:8px;">
                                <input type="checkbox" name="verified_only" value="1" {{ !empty($params['verified_only']) ? 'checked' : '' }} onchange="this.form.submit()">
                                <span><i class="fa fa-check-circle" style="color:#03855c;"></i> Verified Only</span>
                            </label>
                            <label style="display:flex;align-items:center;gap:8px;font-size:13px;font-weight:600;color:#334155;cursor:pointer;">
                                <input type="checkbox" name="open_now" value="1" {{ !empty($params['open_now']) ? 'checked' : '' }} onchange="this.form.submit()">
                                <span><span class="biz-status-dot biz-status-open"></span> Open Now</span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-block" style="background:#2563EB;color:#fff;font-weight:700;border-radius:8px;margin-top:16px;padding:8px;">
                            Apply Filters
                        </button>
                        <a href="{{ route('business.list') }}" class="btn btn-sm btn-link btn-block text-muted" style="font-size:12px;margin-top:4px;">
                            Reset Filters
                        </a>
                    </div>
                </form>
            </div>

            {{-- RIGHT: Results Column --}}
            <div class="col-lg-8 col-md-8 col-12">
                {{-- Header Result Count & Sort --}}
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; flex-wrap: wrap; gap: 8px;">
                    <div style="font-size: 14px; font-weight: 700; color: #0F172A;">
                        Showing {{ $businesses->total() }} Businesses
                        @if($selectedCategory)
                        in <span style="color:#2563EB;">{{ $selectedCategory->name }}</span>
                        @endif
                    </div>
                </div>

                {{-- Business Listings Loop --}}
                @forelse($businesses as $biz)
                <div class="biz-card">
                    <div style="display: flex; gap: 16px; align-items: flex-start;">
                        {{-- Logo --}}
                        <div class="biz-card-logo">
                            @if($biz->logo)
                            <img src="{{ $biz->getLogoUrl() }}" alt="{{ $biz->name }}">
                            @else
                            <i class="fa fa-building-o" style="font-size: 24px; color: #94A3B8;"></i>
                            @endif
                        </div>

                        {{-- Main Info --}}
                        <div style="flex: 1; min-width: 0;">
                            <h2 class="biz-card-title">
                                <a href="{{ route('business.detail', $biz->slug) }}">{{ $biz->name }}</a>
                                @if($biz->verification_status === 'verified')
                                <span class="biz-badge-verified"><i class="fa fa-check-circle"></i> Verified</span>
                                @endif
                                @if($biz->is_featured)
                                <span class="biz-badge-featured"><i class="fa fa-star"></i> Featured</span>
                                @endif
                            </h2>

                            @if($biz->category)
                            <div class="biz-card-category">
                                <i class="fa {{ $biz->category->icon ?: 'fa-folder-o' }}"></i> {{ $biz->category->name }}
                            </div>
                            @endif

                            {{-- NAP Address --}}
                            <div class="biz-card-address">
                                <i class="fa fa-map-marker" style="color: #64748B;"></i>
                                <span>{{ $biz->getLocationLabel() }}</span>
                            </div>

                            @if(!empty($biz->short_description))
                            <p style="font-size: 13px; color: #475569; margin-bottom: 10px; line-height: 1.5;">
                                {{ \Illuminate\Support\Str::limit($biz->short_description, 120) }}
                            </p>
                            @endif

                            {{-- Services Tags --}}
                            @if($biz->services && count($biz->services) > 0)
                            <div class="biz-card-services">
                                @foreach($biz->services->take(4) as $srv)
                                <span class="biz-service-chip">{{ $srv->service_name }}</span>
                                @endforeach
                                @if(count($biz->services) > 4)
                                <span class="biz-service-chip">+{{ count($biz->services) - 4 }} more</span>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Actions Bar (Call, WhatsApp, Details) --}}
                    <div class="biz-card-actions">
                        <a href="tel:{{ $biz->clean_phone }}" onclick="logLead({{ $biz->id }}, 'call')" class="btn-biz-call">
                            <i class="fa fa-phone"></i> Call Now
                        </a>
                        <a href="{{ $biz->whatsapp_url }}" target="_blank" onclick="logLead({{ $biz->id }}, 'whatsapp')" class="btn-biz-wa">
                            <i class="fa fa-whatsapp"></i> WhatsApp
                        </a>

                        <div style="font-size: 12px; color: #64748B; margin-left: 10px; display: inline-flex; align-items: center;">
                            @if($biz->is_open_now === true)
                            <span class="biz-status-dot biz-status-open"></span> <strong style="color:#03855c;">Open Now</strong>
                            @elseif($biz->is_open_now === false)
                            <span class="biz-status-dot biz-status-closed"></span> <strong style="color:#DC2626;">Closed</strong>
                            @endif
                        </div>

                        <a href="{{ route('business.detail', $biz->slug) }}" class="btn-biz-details">
                            View Profile <i class="fa fa-angle-right"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div style="background: #fff; border: 1px solid #E2E8F0; border-radius: 14px; padding: 48px 20px; text-align: center;">
                    <i class="fa fa-building-o" style="font-size: 40px; color: #CBD5E1; margin-bottom: 12px;"></i>
                    <h3 style="font-size: 16px; font-weight: 700; color: #334155; margin-bottom: 6px;">No local businesses found matching your criteria</h3>
                    <p style="color: #94A3B8; font-size: 13.5px; margin-bottom: 16px;">Try adjusting your search terms, changing the category, or expanding the distance radius.</p>
                    <a href="{{ route('business.list') }}" class="btn btn-primary btn-sm" style="font-weight: 600; border-radius: 6px;">
                        Clear All Filters
                    </a>
                </div>
                @endforelse

                {{-- Pagination --}}
                <div class="pagiWrap mt-4">
                    {!! $businesses->appends(Request::except('page'))->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.footer')
@endsection

@push('scripts')
<script>
    // Browser Geolocation Detector
    $('#btnDetectLocation').on('click', function() {
        if (navigator.geolocation) {
            $(this).html('<i class="fa fa-spinner fa-spin"></i> Detecting...');
            navigator.geolocation.getCurrentPosition(function(pos) {
                $('#userLat').val(pos.coords.latitude);
                $('#userLng').val(pos.coords.longitude);
                $('#btnDetectLocation').html('<i class="fa fa-check"></i> Location Captured!').addClass('btn-success');
                $('#bizFilterForm').submit();
            }, function(err) {
                alert('Could not access location: ' + err.message);
                $('#btnDetectLocation').html('<i class="fa fa-crosshairs"></i> Use My Current Location');
            });
        } else {
            alert('Geolocation is not supported by your browser.');
        }
    });

    // Capture Lead Ajax helper
    function logLead(bizId, type) {
        $.post("{{ url('business/lead') }}/" + bizId, {
            _token: "{{ csrf_token() }}",
            lead_type: type
        });
    }
</script>
@endpush
