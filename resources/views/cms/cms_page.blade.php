@extends('layouts.app')

@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->

<!-- Inner Page Title start (Matches the entire website standard banner) -->
@include('includes.inner_page_title', ['page_title' => $cmsContent->page_title])
<!-- Inner Page Title end -->

@php
    $slug = $cms->page_slug ?? '';

    // Legal & Information navigation links
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
.cms-main-wrapper {
    background: #F8FAFC;
    padding: 48px 0 75px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}
.cms-main-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 38px 44px;
    box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
}
@media (max-width: 768px) {
    .cms-main-card {
        padding: 24px 20px;
        border-radius: 12px;
    }
}

/* Document Title Header */
.cms-doc-header {
    border-bottom: 1.5px solid #F1F5F9;
    padding-bottom: 20px;
    margin-bottom: 28px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}
.cms-doc-title {
    font-size: 26px;
    font-weight: 800;
    color: #0F172A;
    margin: 0;
    letter-spacing: -0.3px;
}
.cms-doc-meta {
    font-size: 13px;
    color: #64748B;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* Typography & Content Styling */
.cms-body {
    color: #334155;
    font-size: 15.5px;
    line-height: 1.8;
}
.cms-body h2 {
    font-size: 20px;
    font-weight: 800;
    color: #0F172A;
    margin-top: 32px;
    margin-bottom: 14px;
    padding-bottom: 8px;
    border-bottom: 1px solid #F1F5F9;
    display: flex;
    align-items: center;
    gap: 10px;
}
.cms-body h2::before {
    content: "";
    display: inline-block;
    width: 4px;
    height: 18px;
    background: #2563EB;
    border-radius: 3px;
}
.cms-body h3 {
    font-size: 17px;
    font-weight: 700;
    color: #1E293B;
    margin-top: 24px;
    margin-bottom: 10px;
}
.cms-body p {
    margin-bottom: 16px;
    color: #475569;
}
.cms-body strong {
    color: #0F172A;
    font-weight: 700;
}
.cms-body ul, .cms-body ol {
    margin: 12px 0 20px 20px;
    padding-left: 10px;
}
.cms-body li {
    margin-bottom: 8px;
    color: #334155;
    line-height: 1.7;
}
.cms-body a {
    color: #2563EB;
    text-decoration: underline;
    font-weight: 600;
}
.cms-body a:hover {
    color: #1D4ED8;
    text-decoration: none;
}

/* Alert Boxes */
.cms-alert {
    background: #EFF6FF;
    border: 1px solid #BFDBFE;
    border-left: 4px solid #2563EB;
    border-radius: 10px;
    padding: 16px 20px;
    margin: 20px 0;
    color: #1E40AF;
    font-size: 14px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
}
.cms-alert.danger {
    background: #FEF2F2;
    border-color: #FECACA;
    border-left-color: #DC2626;
    color: #991B1B;
}

/* Sidebar Widgets */
.cms-sidebar-widget {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
}
.cms-widget-title {
    font-size: 15px;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.cms-menu-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.cms-menu-item a {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 11px 14px;
    border-radius: 10px;
    color: #475569;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.15s ease;
    border: 1px solid transparent;
}
.cms-menu-item a:hover {
    background: #F8FAFC;
    color: #2563EB;
    border-color: #E2E8F0;
}
.cms-menu-item.active a {
    background: #2563EB;
    color: #FFFFFF !important;
    font-weight: 700;
    box-shadow: 0 3px 10px rgba(37, 99, 235, 0.25);
}
.cms-menu-item.active a i {
    color: #FFFFFF !important;
}

/* Support Help Card */
.cms-help-card {
    background: #0F172A;
    color: #FFFFFF;
    border-radius: 16px;
    padding: 24px;
}
.cms-help-title {
    font-size: 16px;
    font-weight: 800;
    color: #FFFFFF;
    margin-bottom: 6px;
}
.cms-help-desc {
    font-size: 13px;
    color: #94A3B8;
    line-height: 1.55;
    margin-bottom: 16px;
}
.cms-help-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: #2563EB;
    color: #FFFFFF !important;
    font-weight: 700;
    font-size: 13px;
    padding: 10px 16px;
    border-radius: 8px;
    text-decoration: none;
    width: 100%;
    transition: background 0.15s ease;
}
.cms-help-btn:hover {
    background: #1D4ED8;
}
</style>

<div class="cms-main-wrapper">
    <div class="container">
        <div class="row">
            
            {{-- Main Content Column --}}
            <div class="col-lg-8 col-md-12 mb-4">
                <div class="cms-main-card">
                    
                    {{-- Document Header --}}
                    <div class="cms-doc-header">
                        <h2 class="cms-doc-title" style="border:none; padding:0; margin:0;">{{ $cmsContent->page_title }}</h2>
                        <span class="cms-doc-meta">
                            <i class="far fa-clock"></i> Updated: {{ date('M Y') }}
                        </span>
                    </div>

                    @if(in_array($slug, ['disclaimer', 'fraud-alert']))
                    <div class="cms-alert danger">
                        <i class="fas fa-exclamation-triangle" style="font-size: 18px; margin-top: 2px;"></i>
                        <div>
                            <strong>ANTI-FRAUD WARNING:</strong> JobNBiz and legitimate employers NEVER ask candidates for interview fees, security deposits, or laptop charges. Never share bank details or OTPs.
                        </div>
                    </div>
                    @endif

                    {{-- Main Document Body --}}
                    <div class="cms-body">
                        {!! $cmsContent->page_content !!}
                    </div>

                    {{-- Bottom Footer Line --}}
                    <div style="margin-top: 36px; padding-top: 20px; border-top: 1px solid #E2E8F0; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 12px; font-size: 13px; color: #64748B;">
                        <div>
                            Have questions regarding this document?
                            <a href="{{ route('contact.us') }}" style="color: #2563EB; font-weight: 700; margin-left: 4px;">Contact Support &rarr;</a>
                        </div>
                        <span style="font-weight: 600; color: #94A3B8;">JobNBiz India</span>
                    </div>

                </div>

                {{-- CMS Advertisement Space if active --}}
                @if(!empty($siteSetting->cms_page_ad))
                <div style="margin-top: 20px; text-align: center;">
                    {!! $siteSetting->cms_page_ad !!}
                </div>
                @endif
            </div>

            {{-- Sidebar Column --}}
            <div class="col-lg-4 col-md-12">
                
                {{-- Quick Policy Links --}}
                <div class="cms-sidebar-widget">
                    <div class="cms-widget-title">
                        <i class="fas fa-file-alt" style="color: #2563EB;"></i>
                        <span>Legal & Guidelines</span>
                    </div>
                    <ul class="cms-menu-list">
                        @foreach($defaultLegalPages as $p)
                            @php
                                $isActive = ($slug === $p['slug']);
                            @endphp
                            <li class="cms-menu-item {{ $isActive ? 'active' : '' }}">
                                <a href="{{ url('cms/' . $p['slug']) }}">
                                    <span>
                                        <i class="fas {{ $p['icon'] }}" style="width: 16px; margin-right: 8px; color: {{ $isActive ? '#FFFFFF' : '#64748B' }};"></i>
                                        {{ $p['title'] }}
                                    </span>
                                    <i class="fas fa-chevron-right" style="font-size: 10px; opacity: 0.6;"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Help Card --}}
                <div class="cms-help-card">
                    <div class="cms-help-title">Need Assistance?</div>
                    <p class="cms-help-desc">
                        Have queries about employer verification, candidate rights, or payments? Our support team is here to help.
                    </p>
                    <a href="{{ route('contact.us') }}" class="cms-help-btn">
                        <i class="fas fa-headset"></i>
                        <span>Contact Helpdesk</span>
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>

@include('includes.footer')
@endsection