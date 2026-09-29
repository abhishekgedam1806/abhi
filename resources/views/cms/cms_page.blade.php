@extends('layouts.app')

@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->

@php
    $slug = $cms->page_slug ?? '';
    
    // Determine icon & category badge based on slug
    $categoryBadge = 'LEGAL & POLICY';
    $iconClass = 'fa-shield-alt';
    $subtitle = 'Official Policy Document — JobNBiz India';

    if (in_array($slug, ['disclaimer', 'fraud-alert'])) {
        $categoryBadge = 'SECURITY & COMPLIANCE';
        $iconClass = 'fa-exclamation-triangle';
        $subtitle = 'Anti-Fraud Alert & Candidate Safety Guidelines';
    } elseif (in_array($slug, ['employer-guide', 'employer-guidelines'])) {
        $categoryBadge = 'HIRING GUIDE';
        $iconClass = 'fa-briefcase';
        $subtitle = 'Best Practices for Recruiters, HRs and Employers';
    } elseif (in_array($slug, ['candidate-guide', 'career-guide'])) {
        $categoryBadge = 'CAREER GUIDE';
        $iconClass = 'fa-graduation-cap';
        $subtitle = 'Job Search, Resume Building & Interview Advice';
    } elseif (in_array($slug, ['refund-policy', 'cancellation-policy'])) {
        $categoryBadge = 'BILLING & PAYMENTS';
        $iconClass = 'fa-file-invoice-dollar';
        $subtitle = 'Payment Terms, Cancellation & Refund Rules';
    } elseif (in_array($slug, ['privacy-policy'])) {
        $categoryBadge = 'DATA PRIVACY';
        $iconClass = 'fa-user-lock';
        $subtitle = 'How We Protect Candidate & Recruiter Data';
    } elseif (in_array($slug, ['terms-of-use', 'terms-and-conditions'])) {
        $categoryBadge = 'TERMS OF SERVICE';
        $iconClass = 'fa-gavel';
        $subtitle = 'Terms & Conditions Governing Use of JobNBiz';
    } elseif (in_array($slug, ['about-us'])) {
        $categoryBadge = 'ABOUT JOBNBIZ';
        $iconClass = 'fa-building';
        $subtitle = "Connecting India's Talent with Top Employers";
    }

    // Default list of legal pages if not dynamically provided
    $defaultLegalPages = [
        ['slug' => 'about-us', 'title' => 'About Us', 'icon' => 'fa-info-circle'],
        ['slug' => 'privacy-policy', 'title' => 'Privacy Policy', 'icon' => 'fa-user-shield'],
        ['slug' => 'terms-of-use', 'title' => 'Terms of Use', 'icon' => 'fa-file-contract'],
        ['slug' => 'disclaimer', 'title' => 'Disclaimer & Anti-Fraud', 'icon' => 'fa-exclamation-triangle'],
        ['slug' => 'refund-policy', 'title' => 'Refund & Cancellation', 'icon' => 'fa-receipt'],
        ['slug' => 'employer-guide', 'title' => 'Employer Guide', 'icon' => 'fa-briefcase'],
        ['slug' => 'candidate-guide', 'title' => 'Candidate Guide', 'icon' => 'fa-graduation-cap'],
    ];
@endphp

<style>
/* ==========================================================================
   JobNBiz Modern CMS & Legal Page Styles
   ========================================================================== */
.cms-hero-banner {
    background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
    color: #FFFFFF;
    padding: 55px 0 65px;
    position: relative;
    overflow: hidden;
}
.cms-hero-banner::before {
    content: "";
    position: absolute;
    top: -50%;
    right: -20%;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(37, 99, 235, 0.18) 0%, rgba(37, 99, 235, 0) 70%);
    border-radius: 50%;
    pointer-events: none;
}
.cms-hero-badge {
    background: rgba(37, 99, 235, 0.25);
    border: 1px solid rgba(147, 197, 253, 0.35);
    color: #93C5FD;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.8px;
    padding: 6px 14px;
    border-radius: 50px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
    text-transform: uppercase;
}
.cms-hero-title {
    font-size: 38px;
    font-weight: 900;
    color: #FFFFFF !important;
    line-height: 1.25;
    margin-bottom: 12px;
    letter-spacing: -0.5px;
}
.cms-hero-subtitle {
    font-size: 16px;
    color: #CBD5E1;
    margin-bottom: 20px;
    max-width: 680px;
    line-height: 1.6;
}
.cms-meta-bar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 20px;
    font-size: 13.5px;
    color: #94A3B8;
    padding-top: 10px;
}
.cms-meta-item {
    display: inline-flex;
    align-items: center;
    gap: 7px;
}
.cms-meta-item i {
    color: #38BDF8;
}

/* Main Content Area */
.cms-page-wrapper {
    background: #F8FAFC;
    padding: 50px 0 80px;
    min-height: 600px;
}
.cms-content-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 20px;
    padding: 42px 48px;
    box-shadow: 0 4px 25px -4px rgba(15, 23, 42, 0.04), 0 2px 8px -2px rgba(15, 23, 42, 0.02);
}
@media (max-width: 768px) {
    .cms-content-card {
        padding: 24px 20px;
        border-radius: 16px;
    }
    .cms-hero-title {
        font-size: 28px;
    }
}

/* Prose Typography */
.cms-prose {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #334155;
    font-size: 15.5px;
    line-height: 1.8;
}
.cms-prose h1, .cms-prose h2, .cms-prose h3, .cms-prose h4 {
    color: #0F172A;
    font-weight: 800;
    letter-spacing: -0.3px;
}
.cms-prose h2 {
    font-size: 22px;
    margin-top: 36px;
    margin-bottom: 16px;
    padding-bottom: 10px;
    border-bottom: 1.5px solid #F1F5F9;
    display: flex;
    align-items: center;
    gap: 10px;
}
.cms-prose h2::before {
    content: "";
    display: inline-block;
    width: 6px;
    height: 22px;
    background: #2563EB;
    border-radius: 4px;
}
.cms-prose h3 {
    font-size: 18px;
    margin-top: 26px;
    margin-bottom: 12px;
    color: #1E293B;
}
.cms-prose h4 {
    font-size: 16px;
    margin-top: 20px;
    margin-bottom: 10px;
}
.cms-prose p {
    margin-bottom: 18px;
    color: #475569;
}
.cms-prose strong {
    color: #0F172A;
    font-weight: 700;
}
.cms-prose ul, .cms-prose ol {
    margin: 14px 0 22px 20px;
    padding-left: 10px;
}
.cms-prose li {
    margin-bottom: 10px;
    color: #334155;
    line-height: 1.7;
}
.cms-prose a {
    color: #2563EB;
    text-decoration: underline;
    font-weight: 600;
    transition: color 0.15s ease;
}
.cms-prose a:hover {
    color: #1D4ED8;
    text-decoration: none;
}

/* Notice / Alert Box */
.cms-alert-box {
    background: #EFF6FF;
    border: 1px solid #BFDBFE;
    border-left: 5px solid #2563EB;
    border-radius: 12px;
    padding: 18px 22px;
    margin: 24px 0;
    color: #1E40AF;
    font-size: 14.5px;
    display: flex;
    align-items: flex-start;
    gap: 14px;
}
.cms-alert-box.danger {
    background: #FEF2F2;
    border-color: #FECACA;
    border-left-color: #DC2626;
    color: #991B1B;
}
.cms-alert-box i {
    font-size: 20px;
    margin-top: 2px;
    flex-shrink: 0;
}

/* Sidebar Widgets */
.cms-sidebar-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 18px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
}
.cms-sidebar-title {
    font-size: 16px;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    letter-spacing: -0.2px;
}
.cms-nav-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.cms-nav-item a {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    border-radius: 12px;
    color: #475569;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    border: 1px solid transparent;
}
.cms-nav-item a:hover {
    background: #F1F5F9;
    color: #2563EB;
    border-color: #E2E8F0;
    transform: translateX(3px);
}
.cms-nav-item.active a {
    background: #2563EB;
    color: #FFFFFF !important;
    font-weight: 700;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.28);
}
.cms-nav-item.active a i {
    color: #FFFFFF !important;
}

/* Support Help Card */
.cms-support-card {
    background: linear-gradient(145deg, #1E293B, #0F172A);
    color: #FFFFFF;
    border-radius: 18px;
    padding: 26px;
    border: 1px solid #334155;
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
}
.cms-support-title {
    font-size: 17px;
    font-weight: 800;
    color: #FFFFFF;
    margin-bottom: 8px;
}
.cms-support-desc {
    font-size: 13.5px;
    color: #94A3B8;
    line-height: 1.6;
    margin-bottom: 20px;
}
.cms-support-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: #2563EB;
    color: #FFFFFF !important;
    font-weight: 700;
    font-size: 13.5px;
    padding: 12px 20px;
    border-radius: 10px;
    text-decoration: none;
    width: 100%;
    transition: background 0.15s ease;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
}
.cms-support-btn:hover {
    background: #1D4ED8;
}
</style>

<!-- Hero Section -->
<div class="cms-hero-banner">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-9 col-md-12">
                
                {{-- Breadcrumb --}}
                <div style="font-size: 13px; color: #94A3B8; margin-bottom: 12px; font-weight: 500;">
                    <a href="{{ route('index') }}" style="color: #93C5FD; text-decoration: none;">{{ __('Home') }}</a>
                    <span style="margin: 0 6px; color: #64748B;">/</span>
                    <span style="color: #CBD5E1;">Legal & Guidelines</span>
                    <span style="margin: 0 6px; color: #64748B;">/</span>
                    <span style="color: #FFFFFF; font-weight: 600;">{{ $cmsContent->page_title }}</span>
                </div>

                {{-- Heading --}}
                <h1 class="cms-hero-title">{{ $cmsContent->page_title }}</h1>
                <p class="cms-hero-subtitle">{{ $subtitle }}</p>

                {{-- Meta Info --}}
                <div class="cms-meta-bar">
                    <div class="cms-meta-item">
                        <i class="far fa-calendar-check"></i>
                        <span>Last Reviewed: {{ date('M Y') }}</span>
                    </div>
                    <div class="cms-meta-item">
                        <i class="fas fa-shield-check" style="color: #10B981;"></i>
                        <span>Verified Portal Policy</span>
                    </div>
                    <div class="cms-meta-item">
                        <i class="far fa-clock"></i>
                        <span>Approx. 3 min read</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Main Content Area -->
<div class="cms-page-wrapper">
    <div class="container">
        <div class="row">
            
            {{-- Left / Main Content --}}
            <div class="col-lg-8 col-md-12 mb-4">
                <div class="cms-content-card">
                    
                    @if(in_array($slug, ['disclaimer', 'fraud-alert']))
                    <div class="cms-alert-box danger">
                        <i class="fas fa-shield-alt"></i>
                        <div>
                            <strong>URGENT ANTI-FRAUD NOTICE:</strong> JobNBiz and verified recruiters never charge any registration fees, security deposits, or laptop fees for interview scheduling. Never share OTPs or bank details.
                        </div>
                    </div>
                    @endif

                    {{-- Prose Body --}}
                    <div class="cms-prose">
                        {!! $cmsContent->page_content !!}
                    </div>

                    {{-- Document Footer --}}
                    <div style="margin-top: 40px; padding-top: 24px; border-top: 1px solid #E2E8F0; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px; font-size: 13.5px; color: #64748B;">
                        <div>
                            <span>Need clarification on this policy?</span>
                            <a href="{{ route('contact.us') }}" style="color: #2563EB; font-weight: 700; margin-left: 6px;">Contact Compliance Support &rarr;</a>
                        </div>
                        <div>
                            <span style="background: #F1F5F9; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">JobNBiz Terms Version 2.1</span>
                        </div>
                    </div>

                </div>

                {{-- CMS Ad / Widget if configured in Admin --}}
                @if(!empty($siteSetting->cms_page_ad))
                <div style="margin-top: 24px; text-align: center;">
                    {!! $siteSetting->cms_page_ad !!}
                </div>
                @endif
            </div>

            {{-- Right Sticky Sidebar --}}
            <div class="col-lg-4 col-md-12">
                
                {{-- Quick Legal Navigation --}}
                <div class="cms-sidebar-card">
                    <div class="cms-sidebar-title">
                        <i class="fas fa-folder-open" style="color: #2563EB;"></i>
                        <span>Legal & Guidelines</span>
                    </div>
                    <ul class="cms-nav-list">
                        @foreach($defaultLegalPages as $p)
                            @php
                                $isActive = ($slug === $p['slug']);
                            @endphp
                            <li class="cms-nav-item {{ $isActive ? 'active' : '' }}">
                                <a href="{{ url('cms/' . $p['slug']) }}">
                                    <span style="display: inline-flex; align-items: center; gap: 10px;">
                                        <i class="fas {{ $p['icon'] }}" style="width: 16px; color: {{ $isActive ? '#FFFFFF' : '#64748B' }};"></i>
                                        {{ $p['title'] }}
                                    </span>
                                    <i class="fas fa-chevron-right" style="font-size: 10px; opacity: 0.6;"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Support Card --}}
                <div class="cms-support-card">
                    <div style="width: 42px; height: 42px; background: rgba(37,99,235,0.25); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; color: #38BDF8; font-size: 18px;">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="cms-support-title">Need Help or Clarification?</div>
                    <p class="cms-support-desc">
                        If you have questions regarding our terms, candidate privacy, or employer verification, our support desk is here to assist.
                    </p>
                    <a href="{{ route('contact.us') }}" class="cms-support-btn">
                        <i class="fas fa-envelope"></i>
                        <span>Contact Helpdesk</span>
                    </a>
                </div>

                {{-- Trust Card --}}
                <div style="background: #F8FAFC; border: 1px dashed #CBD5E1; border-radius: 14px; padding: 18px 20px; margin-top: 20px; display: flex; align-items: center; gap: 14px;">
                    <div style="width: 36px; height: 36px; background: #DCFCE7; color: #16A34A; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div style="font-size: 12.5px; color: #475569; line-height: 1.45;">
                        <strong style="color: #0F172A; display: block;">256-Bit SSL Encrypted</strong>
                        JobNBiz guarantees secure data transmission for all users.
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

@include('includes.footer')
@endsection