@extends('layouts.app')

@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->

<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title' => __('Pricing & Membership Plans')])
<!-- Inner Page Title end -->

@push('styles')
<style type="text/css">
    .pricing-page-wrapper {
        background: #F8FAFC;
        padding: 40px 0 80px;
        min-height: 85vh;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    .pricing-nav-toggle {
        display: inline-flex;
        background: #FFFFFF;
        border: 1.5px solid #E2E8F0;
        border-radius: 9999px;
        padding: 6px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        margin: 0 auto 36px;
        gap: 6px;
        flex-wrap: wrap;
        justify-content: center;
    }
    .pricing-tab-btn {
        border: none;
        background: transparent;
        padding: 10px 24px;
        border-radius: 9999px;
        font-size: 14px;
        font-weight: 700;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .pricing-tab-btn i {
        font-size: 15px;
    }
    .pricing-tab-btn.active {
        background: #2563EB;
        color: #FFFFFF;
        box-shadow: 0 4px 12px rgba(37,99,235,0.25);
    }
    .pricing-tab-btn.active i {
        color: #FFFFFF;
    }
    .pricing-card {
        background: #FFFFFF;
        border: 1.5px solid #E2E8F0;
        border-radius: 20px;
        padding: 32px 26px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
        transition: all 0.25s ease;
    }
    .pricing-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(37,99,235,0.08);
        border-color: #93C5FD;
    }
    .pricing-card.featured-card {
        border: 2px solid #2563EB;
        box-shadow: 0 10px 30px rgba(37,99,235,0.12);
        position: relative;
    }
    .featured-badge-pill {
        position: absolute;
        top: -14px;
        left: 50%;
        transform: translateX(-50%);
        background: #2563EB;
        color: #FFFFFF;
        font-size: 11.5px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        padding: 4px 16px;
        border-radius: 9999px;
        box-shadow: 0 3px 10px rgba(37,99,235,0.3);
    }
    .pricing-feature-list {
        list-style: none;
        padding: 0;
        margin: 24px 0;
        display: flex;
        flex-direction: column;
        gap: 12px;
        flex: 1;
    }
    .pricing-feature-item {
        font-size: 13.5px;
        color: #334155;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        line-height: 1.4;
    }
    .pricing-feature-item i.fa-check-circle {
        color: #03855c;
        font-size: 15px;
        margin-top: 2px;
        flex-shrink: 0;
    }
    .pricing-feature-item i.fa-times-circle {
        color: #CBD5E1;
        font-size: 15px;
        margin-top: 2px;
        flex-shrink: 0;
    }
    .pricing-privacy-banner {
        background: #0F172A;
        border-radius: 20px;
        padding: 30px 36px;
        color: #FFFFFF;
        box-shadow: 0 10px 30px rgba(15,23,42,0.15);
        margin: 50px 0;
        border: 1px solid #334155;
    }
</style>
@endpush

<div class="pricing-page-wrapper">
    <div class="container" style="max-width: 1280px;">
        @include('flash::message')

        <!-- Hero Title -->
        <div style="text-align: center; margin-bottom: 32px;">
            <span style="font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.2px; color: #2563EB; background: #EFF6FF; border: 1px solid #DBEAFE; padding: 4px 14px; border-radius: 9999px; display: inline-block; margin-bottom: 10px;">
                <i class="fa fa-tags" style="margin-right: 4px;"></i> {{ __('Transparent & Simple Plans') }}
            </span>
            <h1 style="font-size: 32px; font-weight: 900; color: #0F172A; margin: 0 0 10px 0; letter-spacing: -0.5px;">
                {{ __('Plans Built to Accelerate Careers & Hiring') }}
            </h1>
            <p style="font-size: 15px; color: #64748B; max-width: 680px; margin: 0 auto;">
                {{ __('Choose the ideal tier with AI-powered matching, elevated visibility, and 100% consent-first contact protection.') }}
            </p>
        </div>

        <!-- Role Selector Switcher Tabs (Clean Professional Vector Icons) -->
        <div style="text-align: center;">
            <div class="pricing-nav-toggle">
                <button type="button" class="pricing-tab-btn {{ $activeTab === 'candidates' ? 'active' : '' }}" onclick="switchPricingTab('candidates')">
                    <i class="fa fa-user"></i> <span>{{ __('For Job Seekers') }}</span>
                </button>
                <button type="button" class="pricing-tab-btn {{ $activeTab === 'employers' ? 'active' : '' }}" onclick="switchPricingTab('employers')">
                    <i class="fa fa-building-o"></i> <span>{{ __('For Employers & Recruiters') }}</span>
                </button>
                <button type="button" class="pricing-tab-btn {{ $activeTab === 'business' ? 'active' : '' }}" onclick="switchPricingTab('business')">
                    <i class="fa fa-briefcase"></i> <span>{{ __('For Local Businesses') }}</span>
                </button>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 1: CANDIDATE PLANS                    -->
        <!-- ========================================== -->
        <div id="pricing_tab_candidates" class="pricing-tab-content" style="display: {{ $activeTab === 'candidates' ? 'block' : 'none' }};">
            <div class="row">
                <!-- 1. Free Tier -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="pricing-card">
                        <div style="margin-bottom: 18px;">
                            <span style="font-size: 12px; font-weight: 800; color: #475569; background: #F1F5F9; padding: 3px 10px; border-radius: 6px; text-transform: uppercase;">{{ __('Standard Profile') }}</span>
                            <h3 style="font-size: 22px; font-weight: 800; color: #0F172A; margin: 8px 0 4px 0;">FREE</h3>
                            <p style="font-size: 13px; color: #64748B; margin: 0;">{{ __('Start searching and applying for verified jobs') }}</p>
                        </div>
                        <div style="display: flex; align-items: baseline; gap: 4px; padding-bottom: 18px; border-bottom: 1px solid #F1F5F9;">
                            <span style="font-size: 36px; font-weight: 900; color: #0F172A;">₹0</span>
                            <span style="font-size: 13px; color: #64748B; font-weight: 600;">/ {{ __('forever free') }}</span>
                        </div>
                        <ul class="pricing-feature-list">
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span><strong>10 Applications / day</strong></span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span>Standard Job Search & Filters</span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span>Basic AI Job Recommendations</span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span>Standard Recruiter Search Ranking</span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span>Instant In-App Chat Messaging</span></li>
                            <li class="pricing-feature-item"><i class="fa fa-times-circle"></i> <span style="color: #94A3B8;">Direct Recruiter Contact Request</span></li>
                            <li class="pricing-feature-item"><i class="fa fa-times-circle"></i> <span style="color: #94A3B8;">AI Skill Gap Analysis</span></li>
                        </ul>
                        @php $seekerFreePkg = $seekerPackages->where('package_price', 0)->first(); @endphp
                        <a href="{{ $seekerFreePkg ? route('payment.checkout', $seekerFreePkg->id) : route('register') }}" style="display: block; width: 100%; text-align: center; background: #F1F5F9; color: #334155; font-size: 14px; font-weight: 700; padding: 12px; border-radius: 12px; text-decoration: none; transition: all 0.15s ease;">
                            {{ __('Get Started Free') }}
                        </a>
                    </div>
                </div>

                <!-- 2. Plus Tier -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="pricing-card featured-card">
                        <div class="featured-badge-pill">{{ __('Most Popular') }}</div>
                        <div style="margin-bottom: 18px;">
                            <span style="font-size: 12px; font-weight: 800; color: #2563EB; background: #EFF6FF; padding: 3px 10px; border-radius: 6px; text-transform: uppercase;">{{ __('Enhanced Profile') }}</span>
                            <h3 style="font-size: 22px; font-weight: 800; color: #0F172A; margin: 8px 0 4px 0;">PLUS</h3>
                            <p style="font-size: 13px; color: #64748B; margin: 0;">{{ __('Get higher recruiter visibility & smart matching') }}</p>
                        </div>
                        <div style="display: flex; align-items: baseline; gap: 4px; padding-bottom: 18px; border-bottom: 1px solid #F1F5F9;">
                            <span style="font-size: 36px; font-weight: 900; color: #2563EB;">₹299</span>
                            <span style="font-size: 13px; color: #64748B; font-weight: 600;">/ {{ __('month') }}</span>
                        </div>
                        <ul class="pricing-feature-list">
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span><strong>20 Applications / day</strong></span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span><strong>Boosted Profile Visibility</strong></span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span>"Why This Job Matches" Insights</span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span>Instant Priority Job Alerts</span></li>
                            <li class="pricing-feature-item"><i class="fa fa-clock-o text-warning"></i> <span style="color: #475569;">Smart Match Radar <span style="background: #FEF3C7; color: #B45309; font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: 700; margin-left: 4px;">{{ __('Upcoming') }}</span></span></li>
                            <li class="pricing-feature-item"><i class="fa fa-clock-o text-warning"></i> <span style="color: #475569;">Skill Gap Analysis Checklist <span style="background: #FEF3C7; color: #B45309; font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: 700; margin-left: 4px;">{{ __('Upcoming') }}</span></span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span><i class="fa fa-lock" style="color: #64748B; font-size: 12px; margin-right: 2px;"></i> Recruiter Contact Requests (Consent Unlocked)</span></li>
                        </ul>
                        @php $seekerPlusPkg = $seekerPackages->where('package_title', 'PLUS')->first(); @endphp
                        <a href="{{ $seekerPlusPkg ? route('payment.checkout', $seekerPlusPkg->id) : route('register') }}" style="display: block; width: 100%; text-align: center; background: #2563EB; color: #FFFFFF; font-size: 14px; font-weight: 800; padding: 12px; border-radius: 12px; text-decoration: none; box-shadow: 0 4px 14px rgba(37,99,235,0.3); transition: all 0.15s ease;">
                            {{ __('Upgrade to Plus') }}
                        </a>
                    </div>
                </div>

                <!-- 3. Pro Tier -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="pricing-card">
                        <div style="margin-bottom: 18px;">
                            <span style="font-size: 12px; font-weight: 800; color: #7C3AED; background: #F3E8FF; padding: 3px 10px; border-radius: 6px; text-transform: uppercase;">{{ __('Premium Profile') }}</span>
                            <h3 style="font-size: 22px; font-weight: 800; color: #0F172A; margin: 8px 0 4px 0;">PRO</h3>
                            <p style="font-size: 13px; color: #64748B; margin: 0;">{{ __('Top candidate rank & AI-powered career growth') }}</p>
                        </div>
                        <div style="display: flex; align-items: baseline; gap: 4px; padding-bottom: 18px; border-bottom: 1px solid #F1F5F9;">
                            <span style="font-size: 36px; font-weight: 900; color: #0F172A;">₹699</span>
                            <span style="font-size: 13px; color: #64748B; font-weight: 600;">/ {{ __('month') }}</span>
                        </div>
                        <ul class="pricing-feature-list">
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span><strong>Unlimited Applications</strong></span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span><i class="fa fa-star" style="color: #F59E0B; font-size: 12px; margin-right: 2px;"></i> <strong>Featured Candidate Badge</strong></span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span>Top-Tier Recruiter Search Placement</span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span>Instant Priority Job Alerts</span></li>
                            <li class="pricing-feature-item"><i class="fa fa-clock-o text-warning"></i> <span style="color: #475569;">AI-Powered Resume Optimization <span style="background: #FEF3C7; color: #B45309; font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: 700; margin-left: 4px;">{{ __('Upcoming') }}</span></span></li>
                            <li class="pricing-feature-item"><i class="fa fa-clock-o text-warning"></i> <span style="color: #475569;">AI Skill Gap & Salary Analytics <span style="background: #FEF3C7; color: #B45309; font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: 700; margin-left: 4px;">{{ __('Upcoming') }}</span></span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span>Priority 24/7 Dedicated Support</span></li>
                        </ul>
                        @php $seekerProPkg = $seekerPackages->where('package_title', 'PRO')->first(); @endphp
                        <a href="{{ $seekerProPkg ? route('payment.checkout', $seekerProPkg->id) : route('register') }}" style="display: block; width: 100%; text-align: center; background: #0F172A; color: #FFFFFF; font-size: 14px; font-weight: 800; padding: 12px; border-radius: 12px; text-decoration: none; box-shadow: 0 4px 14px rgba(15,23,42,0.25); transition: all 0.15s ease;">
                            {{ __('Upgrade to Pro') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 2: EMPLOYER / RECRUITER PLANS         -->
        <!-- ========================================== -->
        <div id="pricing_tab_employers" class="pricing-tab-content" style="display: {{ $activeTab === 'employers' ? 'block' : 'none' }};">
            <div class="row">
                <!-- 1. Start (Free) -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="pricing-card">
                        <div style="margin-bottom: 16px;">
                            <span style="font-size: 11px; font-weight: 800; color: #475569; background: #F1F5F9; padding: 3px 8px; border-radius: 6px; text-transform: uppercase;">{{ __('Entry Hiring') }}</span>
                            <h3 style="font-size: 20px; font-weight: 800; color: #0F172A; margin: 6px 0 2px 0;">START</h3>
                            <p style="font-size: 12.5px; color: #64748B; margin: 0;">{{ __('Basic hiring for startups') }}</p>
                        </div>
                        <div style="display: flex; align-items: baseline; gap: 4px; padding-bottom: 16px; border-bottom: 1px solid #F1F5F9;">
                            <span style="font-size: 32px; font-weight: 900; color: #0F172A;">₹0</span>
                            <span style="font-size: 12px; color: #64748B; font-weight: 600;">/ {{ __('month') }}</span>
                        </div>
                        <ul class="pricing-feature-list">
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span><strong>3 Active Jobs</strong></span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span>Unlimited Applications</span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span>3 Contact Requests / month</span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span>Standard Candidate Search</span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span>Basic Hiring Analytics</span></li>
                            <li class="pricing-feature-item"><i class="fa fa-times-circle"></i> <span style="color: #94A3B8;">Verified Employer Badge</span></li>
                        </ul>
                        @php $empStartPkg = $employerPackages->where('package_price', 0)->first(); @endphp
                        <a href="{{ $empStartPkg ? route('payment.checkout', $empStartPkg->id) : route('register') }}" style="display: block; width: 100%; text-align: center; background: #F1F5F9; color: #334155; font-size: 13.5px; font-weight: 700; padding: 11px; border-radius: 10px; text-decoration: none;">
                            {{ __('Start Free') }}
                        </a>
                    </div>
                </div>

                <!-- 2. Grow -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="pricing-card featured-card">
                        <div class="featured-badge-pill">{{ __('Best Value') }}</div>
                        <div style="margin-bottom: 16px;">
                            <span style="font-size: 11px; font-weight: 800; color: #2563EB; background: #EFF6FF; padding: 3px 8px; border-radius: 6px; text-transform: uppercase;">{{ __('Growing Teams') }}</span>
                            <h3 style="font-size: 20px; font-weight: 800; color: #0F172A; margin: 6px 0 2px 0;">GROW</h3>
                            <p style="font-size: 12.5px; color: #64748B; margin: 0;">{{ __('Boosted reach & verified hiring') }}</p>
                        </div>
                        <div style="display: flex; align-items: baseline; gap: 4px; padding-bottom: 16px; border-bottom: 1px solid #F1F5F9;">
                            <span style="font-size: 32px; font-weight: 900; color: #2563EB;">₹999</span>
                            <span style="font-size: 12px; color: #64748B; font-weight: 600;">/ {{ __('month') }}</span>
                        </div>
                        <ul class="pricing-feature-list">
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span><strong>15 Active Jobs</strong></span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span><strong>25 Contact Requests / mo</strong></span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span><i class="fa fa-check" style="color: #03855c; font-size: 12px; margin-right: 2px;"></i> <strong>Verified Employer Badge</strong></span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span>Boosted Job Visibility</span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span>Urgent Hiring Tagging</span></li>
                            <li class="pricing-feature-item"><i class="fa fa-clock-o text-warning"></i> <span style="color: #475569;">Smart Candidate Matching <span style="background: #FEF3C7; color: #B45309; font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: 700; margin-left: 4px;">{{ __('Upcoming') }}</span></span></li>
                        </ul>
                        @php $empGrowPkg = $employerPackages->where('package_title', 'GROW')->first(); @endphp
                        <a href="{{ $empGrowPkg ? route('payment.checkout', $empGrowPkg->id) : route('register') }}" style="display: block; width: 100%; text-align: center; background: #2563EB; color: #FFFFFF; font-size: 13.5px; font-weight: 800; padding: 11px; border-radius: 10px; text-decoration: none; box-shadow: 0 3px 10px rgba(37,99,235,0.3);">
                            {{ __('Upgrade to Grow') }}
                        </a>
                    </div>
                </div>

                <!-- 3. Pro -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="pricing-card">
                        <div style="margin-bottom: 16px;">
                            <span style="font-size: 11px; font-weight: 800; color: #7C3AED; background: #F3E8FF; padding: 3px 8px; border-radius: 6px; text-transform: uppercase;">{{ __('Scale & Agencies') }}</span>
                            <h3 style="font-size: 20px; font-weight: 800; color: #0F172A; margin: 6px 0 2px 0;">PRO</h3>
                            <p style="font-size: 12.5px; color: #64748B; margin: 0;">{{ __('Top priority & bulk hiring power') }}</p>
                        </div>
                        <div style="display: flex; align-items: baseline; gap: 4px; padding-bottom: 16px; border-bottom: 1px solid #F1F5F9;">
                            <span style="font-size: 32px; font-weight: 900; color: #0F172A;">₹2,499</span>
                            <span style="font-size: 12px; color: #64748B; font-weight: 600;">/ {{ __('month') }}</span>
                        </div>
                        <ul class="pricing-feature-list">
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span><strong>50 Active Jobs</strong></span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span><strong>100 Contact Requests / mo</strong></span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span><i class="fa fa-star" style="color: #F59E0B; font-size: 12px; margin-right: 2px;"></i> <strong>Verified + Featured Status</strong></span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span>Bulk Candidate Outreach</span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span>Priority Urgent Hiring Placement</span></li>
                            <li class="pricing-feature-item"><i class="fa fa-clock-o text-warning"></i> <span style="color: #475569;">AI Top Match Algorithm <span style="background: #FEF3C7; color: #B45309; font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: 700; margin-left: 4px;">{{ __('Upcoming') }}</span></span></li>
                        </ul>
                        @php $empProPkg = $employerPackages->where('package_title', 'PRO')->first(); @endphp
                        <a href="{{ $empProPkg ? route('payment.checkout', $empProPkg->id) : route('register') }}" style="display: block; width: 100%; text-align: center; background: #0F172A; color: #FFFFFF; font-size: 13.5px; font-weight: 800; padding: 11px; border-radius: 10px; text-decoration: none;">
                            {{ __('Upgrade to Pro') }}
                        </a>
                    </div>
                </div>

                <!-- 4. Enterprise -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="pricing-card" style="background: #FFFFFF;">
                        <div style="margin-bottom: 16px;">
                            <span style="font-size: 11px; font-weight: 800; color: #03855c; background: #ECFDF5; padding: 3px 8px; border-radius: 6px; text-transform: uppercase;">{{ __('Custom Enterprise') }}</span>
                            <h3 style="font-size: 20px; font-weight: 800; color: #0F172A; margin: 6px 0 2px 0;">ENTERPRISE</h3>
                            <p style="font-size: 12.5px; color: #64748B; margin: 0;">{{ __('High-volume corporate recruitment') }}</p>
                        </div>
                        <div style="display: flex; align-items: baseline; gap: 4px; padding-bottom: 16px; border-bottom: 1px solid #F1F5F9;">
                            <span style="font-size: 28px; font-weight: 900; color: #0F172A;">{{ __('Custom') }}</span>
                            <span style="font-size: 12px; color: #64748B; font-weight: 600;">/ {{ __('annual quote') }}</span>
                        </div>
                        <ul class="pricing-feature-list">
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span><strong>Unlimited Job Postings</strong></span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span>Custom Contact Requests Quota</span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span>Custom ATS & HRMS Integrations</span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span>Dedicated Account Manager</span></li>
                            <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span>Custom SLA & Invoice Billing</span></li>
                            <li class="pricing-feature-item"><i class="fa fa-clock-o text-warning"></i> <span style="color: #475569;">Predictive Talent Matching <span style="background: #FEF3C7; color: #B45309; font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: 700; margin-left: 4px;">{{ __('Upcoming') }}</span></span></li>
                        </ul>
                        <a href="{{ route('contact.us') }}" style="display: block; width: 100%; text-align: center; background: #FFFFFF; border: 1.5px solid #CBD5E1; color: #0F172A; font-size: 13.5px; font-weight: 800; padding: 11px; border-radius: 10px; text-decoration: none;">
                            {{ __('Contact Sales') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 3: LOCAL BUSINESS PLANS                -->
        <!-- ========================================== -->
        <div id="pricing_tab_business" class="pricing-tab-content" style="display: {{ $activeTab === 'business' ? 'block' : 'none' }};">
            <div class="row">
                @foreach($businessPackages as $bPkg)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="pricing-card {{ $bPkg->package_title === 'Gold Premium' ? 'featured-card' : '' }}">
                            @if($bPkg->package_title === 'Gold Premium')
                                <div class="featured-badge-pill">{{ __('Top Pick') }}</div>
                            @endif
                            <div style="margin-bottom: 16px;">
                                <span style="font-size: 11px; font-weight: 800; color: #2563EB; background: #EFF6FF; padding: 3px 8px; border-radius: 6px; text-transform: uppercase;">{{ $bPkg->package_num_days }} {{ __('Days') }}</span>
                                <h3 style="font-size: 20px; font-weight: 800; color: #0F172A; margin: 6px 0 2px 0;">{{ $bPkg->package_title }}</h3>
                            </div>
                            <div style="display: flex; align-items: baseline; gap: 4px; padding-bottom: 16px; border-bottom: 1px solid #F1F5F9;">
                                <span style="font-size: 32px; font-weight: 900; color: #0F172A;">₹{{ number_format($bPkg->package_price) }}</span>
                                <span style="font-size: 12px; color: #64748B; font-weight: 600;">/ {{ $bPkg->package_num_days }}d</span>
                            </div>
                            <ul class="pricing-feature-list">
                                <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span><strong>{{ $bPkg->package_num_listings }} Business Listing(s)</strong></span></li>
                                <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span>{{ $bPkg->package_num_services }} Services Catalog</span></li>
                                <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span>{{ $bPkg->package_num_photos }} Gallery Photos</span></li>
                                <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span><i class="fa fa-whatsapp text-success" style="font-size: 13px; margin-right: 2px;"></i> Direct WhatsApp Customer Leads</span></li>
                                @if($bPkg->has_verified_badge)
                                    <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span><i class="fa fa-check" style="color: #03855c; font-size: 12px; margin-right: 2px;"></i> <strong>Verified Trust Badge</strong></span></li>
                                @endif
                                @if($bPkg->is_featured)
                                    <li class="pricing-feature-item"><i class="fa fa-check-circle"></i> <span><i class="fa fa-star" style="color: #F59E0B; font-size: 12px; margin-right: 2px;"></i> <strong>Featured Directory Rank</strong></span></li>
                                @endif
                            </ul>
                            <a href="{{ route('payment.checkout', $bPkg->id) }}" style="display: block; width: 100%; text-align: center; background: {{ $bPkg->package_title === 'Gold Premium' ? '#2563EB' : '#0F172A' }}; color: #FFFFFF; font-size: 13.5px; font-weight: 800; padding: 11px; border-radius: 10px; text-decoration: none;">
                                {{ __('Select Plan') }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Universal Platform Privacy Rule Banner -->
        <div class="pricing-privacy-banner">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                        <i class="fa fa-shield" style="color: #60A5FA; font-size: 24px;"></i>
                        <h3 style="font-size: 20px; font-weight: 800; color: #FFFFFF; margin: 0;">
                            {{ __('One Strict Privacy Rule Across the Platform') }}
                        </h3>
                    </div>
                    <p style="font-size: 14px; color: #94A3B8; margin: 0; line-height: 1.6;">
                        {{ __('No employer or candidate gets another user\'s private Phone number, Email address, or WhatsApp number without explicit mutual consent. The paid plan unlocks the ability to send contact requests, but payment itself never bypasses permission.') }}
                    </p>
                </div>
                <div class="col-lg-4 text-right" style="margin-top: 14px;">
                    <div style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 12px; padding: 12px 18px; display: inline-block; text-align: center;">
                        <span style="font-size: 12px; color: #93C5FD; text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px; display: block; margin-bottom: 2px;">
                            <i class="fa fa-lock" style="margin-right: 4px;"></i> {{ __('Standard Protocol') }}
                        </span>
                        <span style="font-size: 14.5px; font-weight: 800; color: #FFFFFF;">Request &rarr; Approval &rarr; Reveal</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@include('includes.footer')
@endsection

@push('scripts')
<script type="text/javascript">
function switchPricingTab(tab) {
    $('.pricing-tab-btn').removeClass('active');
    $('.pricing-tab-content').hide();
    
    if (tab === 'candidates') {
        $('.pricing-tab-btn:nth-child(1)').addClass('active');
        $('#pricing_tab_candidates').fadeIn(150);
    } else if (tab === 'employers') {
        $('.pricing-tab-btn:nth-child(2)').addClass('active');
        $('#pricing_tab_employers').fadeIn(150);
    } else if (tab === 'business') {
        $('.pricing-tab-btn:nth-child(3)').addClass('active');
        $('#pricing_tab_business').fadeIn(150);
    }
}
</script>
@endpush
