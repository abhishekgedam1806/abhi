@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->

<!-- Inner Page Title -->
<div class="pageTitle" style="background: #0F172A; padding: 32px 0; color: #FFFFFF !important;">
    <div class="container">
        <h1 style="font-size: 24px; font-weight: 800; color: #FFFFFF !important; margin: 0;">Business Listing Packages & Upgrades</h1>
        <p style="color: #E2E8F0 !important; font-size: 13.5px; margin-top: 4px; margin-bottom: 0;">Choose the right plan to list your businesses, unlock customer leads, and get verified.</p>
    </div>
</div>

<style>
.pkg-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 28px 24px;
    text-align: center;
    position: relative;
    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.pkg-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px -6px rgba(0,0,0,0.08);
    border-color: #93C5FD;
}
.pkg-card.featured-pkg {
    border: 2px solid #2563EB;
    background: #FFFFFF;
    box-shadow: 0 8px 20px -4px rgba(37,99,235,0.15);
}
.pkg-badge {
    position: absolute;
    top: -12px;
    left: 50%;
    transform: translateX(-50%);
    background: #2563EB;
    color: #fff;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 3px 14px;
    border-radius: 20px;
}
.pkg-title {
    font-size: 18px;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 8px;
}
.pkg-price {
    font-size: 32px;
    font-weight: 900;
    color: #0F172A;
    line-height: 1;
    margin: 12px 0 6px;
}
.pkg-duration {
    font-size: 12.5px;
    font-weight: 600;
    color: #64748B;
    margin-bottom: 20px;
}
.pkg-features {
    list-style: none;
    padding: 0;
    margin: 0 0 24px 0;
    text-align: left;
    font-size: 13px;
}
.pkg-features li {
    padding: 8px 0;
    border-bottom: 1px dashed #F1F5F9;
    display: flex;
    align-items: center;
    gap: 8px;
    color: #334155;
}
.pkg-features li i.fa-check {
    color: #03855c;
    font-size: 14px;
}
.pkg-features li i.fa-times {
    color: #94A3B8;
    font-size: 14px;
}
.pkg-btn-wrap {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.btn-gateway {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 9px 16px;
    font-size: 13px;
    font-weight: 700;
    border-radius: 10px;
    text-decoration: none !important;
    transition: all 0.15s ease;
}
.btn-stripe {
    background: #635BFF;
    color: #fff !important;
}
.btn-stripe:hover {
    background: #4F46E5;
    box-shadow: 0 4px 12px rgba(99,91,255,0.3);
}
.btn-paypal {
    background: #0070BA;
    color: #fff !important;
}
.btn-paypal:hover {
    background: #005EA6;
    box-shadow: 0 4px 12px rgba(0,112,186,0.3);
}
.btn-free {
    background: #03855c;
    color: #fff !important;
}
.btn-free:hover {
    background: #047857;
    box-shadow: 0 4px 12px rgba(3,133,92,0.3);
}
</style>

<div class="listpgWraper" style="background: #F8FAFC; padding: 40px 0 60px;">
    <div class="container">
        @include('flash::message')
        <div class="row">
            {{-- Dedicated Business Dashboard Menu --}}
            @include('includes.business_dashboard_menu')

            <div class="col-lg-9 col-md-8">
                
                {{-- CURRENT ACTIVE PLAN STATUS --}}
                @if($currentPackage)
                <div style="background:#fff;border-radius:14px;border:1px solid #BFDBFE;padding:20px 24px;margin-bottom:28px;box-shadow:0 1px 3px rgba(37,99,235,0.06);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div>
                        <div style="font-size:12px;font-weight:700;color:#2563EB;text-transform:uppercase;letter-spacing:0.5px;">Current Active Subscription</div>
                        <h3 style="font-size:18px;font-weight:800;color:#0F172A;margin:2px 0 4px;">
                            {{ $currentPackage->package_title }}
                            @if(!$isExpired)
                            <span class="badge" style="background:#ECFDF5;color:#03855c;font-size:11px;font-weight:700;padding:3px 8px;border-radius:12px;margin-left:6px;">Active</span>
                            @else
                            <span class="badge" style="background:#FEF2F2;color:#DC2626;font-size:11px;font-weight:700;padding:3px 8px;border-radius:12px;margin-left:6px;">Expired</span>
                            @endif
                        </h3>
                        <div style="font-size:13px;color:#64748B;">
                            Listings Used: <strong>{{ $usedQuota }} / {{ $totalQuota }}</strong> &nbsp;|&nbsp; 
                            @if(!$isExpired)
                            Remaining: <strong>{{ $remainingDays }} Days</strong>
                            @else
                            <strong class="text-danger">Plan Expired - Please Renew</strong>
                            @endif
                        </div>
                    </div>
                    <div>
                        <a href="#plans" class="btn btn-primary" style="font-weight:700;border-radius:8px;padding:8px 18px;background:#2563EB;">
                            <i class="fa fa-arrow-down"></i> Upgrade / Renew Plan
                        </a>
                    </div>
                </div>
                @endif

                <div id="plans" style="margin-bottom:18px;">
                    <h2 style="font-size:20px;font-weight:800;color:#0F172A;margin:0 0 6px;">Available Listing Packages</h2>
                    <p style="font-size:13.5px;color:#64748B;margin:0;">Select a plan that fits your business needs. You can upgrade anytime.</p>
                </div>

                {{-- PRICING GRID --}}
                <div class="row">
                    @foreach($packages as $pkg)
                    @php
                        $isFeaturedPlan = $pkg->is_featured || $pkg->package_price >= 999;
                        $isCurrent = $currentPackage && $currentPackage->id == $pkg->id && !$isExpired;
                    @endphp
                    <div class="col-lg-6 col-md-6 mb-4">
                        <div class="pkg-card {{ $isFeaturedPlan ? 'featured-pkg' : '' }}">
                            @if($isFeaturedPlan)
                            <div class="pkg-badge">Most Popular</div>
                            @endif
                            
                            <div>
                                <div class="pkg-title">{{ $pkg->package_title }}</div>
                                
                                <div class="pkg-price">
                                    @if($pkg->package_price > 0)
                                    ₹{{ number_format($pkg->package_price, 0) }}
                                    @else
                                    Free
                                    @endif
                                </div>
                                <div class="pkg-duration">Valid for {{ $pkg->package_num_days }} Days</div>

                                <ul class="pkg-features">
                                    <li>
                                        <i class="fa fa-check"></i>
                                        <span><strong>{{ $pkg->package_num_listings }}</strong> Business Listing{{ $pkg->package_num_listings > 1 ? 's' : '' }}</span>
                                    </li>
                                    <li>
                                        <i class="fa fa-check"></i>
                                        <span>Up to <strong>{{ $pkg->package_num_services }}</strong> Services per Listing</span>
                                    </li>
                                    <li>
                                        <i class="fa fa-check"></i>
                                        <span>Up to <strong>{{ $pkg->package_num_photos }}</strong> Photo Gallery Uploads</span>
                                    </li>
                                    <li>
                                        @if($pkg->has_whatsapp_leads)
                                        <i class="fa fa-check"></i>
                                        <span>Direct WhatsApp & Phone Call Leads</span>
                                        @else
                                        <i class="fa fa-times"></i>
                                        <span style="color:#94A3B8;">Direct WhatsApp Leads</span>
                                        @endif
                                    </li>
                                    <li>
                                        @if($pkg->has_verified_badge)
                                        <i class="fa fa-check"></i>
                                        <span><strong>Verified Trust Badge</strong></span>
                                        @else
                                        <i class="fa fa-times"></i>
                                        <span style="color:#94A3B8;">Verified Trust Badge</span>
                                        @endif
                                    </li>
                                    <li>
                                        @if($pkg->is_featured)
                                        <i class="fa fa-check"></i>
                                        <span><strong>Featured Listing</strong> (Top of Search)</span>
                                        @else
                                        <i class="fa fa-times"></i>
                                        <span style="color:#94A3B8;">Featured Ranking</span>
                                        @endif
                                    </li>
                                </ul>
                            </div>

                            <div class="pkg-btn-wrap">
                                @if($isCurrent)
                                <button class="btn btn-default disabled" style="font-weight:700;border-radius:10px;padding:10px;" disabled>
                                    <i class="fa fa-check-circle text-success"></i> Current Active Plan
                                </button>
                                @elseif($pkg->package_price <= 0)
                                <a href="{{ route('business.order.free.package', $pkg->id) }}" class="btn-gateway btn-free">
                                    <i class="fa fa-gift"></i> Subscribe Free Plan
                                </a>
                                @else
                                    {{-- Paid Gateways --}}
                                    <a href="{{ route('business.pay.stripe', $pkg->id) }}" class="btn-gateway btn-stripe">
                                        <i class="fa fa-credit-card"></i> Pay with Card (Stripe)
                                    </a>
                                    
                                    <a href="{{ route('business.pay.paypal', $pkg->id) }}" class="btn-gateway btn-paypal">
                                        <i class="fa fa-paypal"></i> Pay with PayPal
                                    </a>

                                    {{-- Instant Test Activation in Local/Dev Mode --}}
                                    @if(config('app.env') === 'local' || true)
                                    <a href="{{ route('business.order.test.package', $pkg->id) }}" class="btn btn-xs btn-link" style="font-size:11.5px;color:#64748B;text-decoration:underline;">
                                        [⚡ Instant Test Activation]
                                    </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>

@include('includes.footer')
@endsection
