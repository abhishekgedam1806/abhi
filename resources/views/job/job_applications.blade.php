@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end --> 
<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title'=>__('Job Applications')])
<!-- Inner Page Title end -->

<style>
/* ============================================================
   PREMIUM JOB APPLICATIONS & CANDIDATE HIRING PIPELINE STYLES
   ============================================================ */
.applications-container {
    padding: 35px 0 60px 0;
    background: #F8FAFC;
    min-height: 85vh;
}

.applications-main-card {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E2E8F0;
    box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04);
    padding: 28px;
    margin-bottom: 24px;
}

/* Back Link & Job Context Header */
.app-context-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
}

.btn-back-jobs {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 13.5px;
    font-weight: 600;
    color: #475569 !important;
    background: #F1F5F9;
    padding: 7px 16px;
    border-radius: 10px;
    text-decoration: none !important;
    transition: all 0.2s ease;
}

.btn-back-jobs:hover {
    background: #E2E8F0;
    color: #0F172A !important;
    transform: translateX(-2px);
}

.job-headline-card {
    background: #F8FAFC;
    border: 1.5px solid #DBEAFE;
    border-radius: 16px;
    padding: 20px 24px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}

.job-headline-info h2 {
    font-size: 20px;
    font-weight: 800;
    color: #0F172A;
    margin: 0 0 6px 0;
}

.job-headline-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.job-chip-badge {
    font-size: 12px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 6px;
    background: #FFFFFF;
    border: 1px solid #BFDBFE;
    color: #1E40AF;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

/* Tabs Navigation */
.app-tabs-nav {
    display: flex;
    align-items: center;
    gap: 10px;
    border-bottom: 1.5px solid #F1F5F9;
    padding-bottom: 16px;
    margin-bottom: 24px;
}

.app-tab-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 700;
    padding: 9px 20px;
    border-radius: 10px;
    text-decoration: none !important;
    transition: all 0.2s ease;
}

.app-tab-btn.active-tab {
    background: #2563EB;
    color: #FFFFFF !important;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
}

.app-tab-btn.inactive-tab {
    background: #F8FAFC;
    color: #64748B !important;
    border: 1px solid #E2E8F0;
}

.app-tab-btn.inactive-tab:hover {
    background: #F1F5F9;
    color: #0F172A !important;
}

.tab-count-pill {
    font-size: 11.5px;
    font-weight: 800;
    padding: 2px 7px;
    border-radius: 20px;
}

.active-tab .tab-count-pill {
    background: rgba(255, 255, 255, 0.25);
    color: #FFFFFF;
}

.inactive-tab .tab-count-pill {
    background: #E2E8F0;
    color: #475569;
}

/* Candidate Card */
.candidate-card-item {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 22px;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.candidate-card-item:hover {
    border-color: #CBD5E1;
    box-shadow: 0 10px 30px -4px rgba(15, 23, 42, 0.08);
    transform: translateY(-2px);
}

.candidate-top-layout {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 16px;
}

.candidate-avatar-group {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    flex: 1;
    min-width: 280px;
}

.candidate-avatar-wrap {
    width: 64px;
    height: 64px;
    min-width: 64px;
    border-radius: 50%;
    border: 2px solid #E2E8F0;
    overflow: hidden;
    background: #F1F5F9;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.candidate-avatar-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.candidate-meta-info h3 {
    margin: 0 0 4px 0;
    font-size: 18px;
    font-weight: 700;
    line-height: 1.3;
}

.candidate-meta-info h3 a {
    color: #0F172A;
    text-decoration: none;
    transition: color 0.2s;
}

.candidate-meta-info h3 a:hover {
    color: #2563EB;
}

.candidate-loc {
    font-size: 13.5px;
    color: #64748B;
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 8px;
}

.candidate-pills-row {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    align-items: center;
}

.pill-exp {
    font-size: 12px;
    font-weight: 600;
    background: #EFF6FF;
    color: #1D4ED8;
    border: 1px solid #DBEAFE;
    padding: 3px 10px;
    border-radius: 6px;
}

.pill-career {
    font-size: 12px;
    font-weight: 600;
    background: #F0FDF4;
    color: #166534;
    border: 1px solid #DCFCE7;
    padding: 3px 10px;
    border-radius: 6px;
}

/* Unified Status System (Consistent Website-wide) */
.status-badge-applied {
    background: #EFF6FF;
    color: #1D4ED8;
    border: 1px solid #BFDBFE;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.status-badge-shortlisted {
    background: #ECFDF5;
    color: #047857;
    border: 1px solid #A7F3D0;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.status-badge-rejected {
    background: #FFF1F2;
    color: #9F1239;
    border: 1px solid #FECDD3;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.status-badge-hired {
    background: #F5F3FF;
    color: #6D28D9;
    border: 1px solid #DDD6FE;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

/* Expected Salary Highlight Box */
.candidate-salary-box {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    padding: 10px 16px;
    border-radius: 12px;
    text-align: right;
    min-width: 170px;
}

.salary-sub-label {
    font-size: 11px;
    font-weight: 700;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    margin-bottom: 2px;
}

.salary-main-val {
    font-size: 17px;
    font-weight: 800;
    color: #0F172A;
    line-height: 1.2;
}

.salary-period-sub {
    font-size: 12px;
    font-weight: 500;
    color: #64748B;
    margin-left: 2px;
}

/* Skills preview */
.candidate-skills-wrap {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 6px;
    margin: 12px 0 16px 0;
}

.skills-title-label {
    font-size: 12px;
    font-weight: 700;
    color: #475569;
    margin-right: 4px;
}

.candidate-skill-tag {
    font-size: 12px;
    font-weight: 600;
    color: #334155;
    background: #F1F5F9;
    border: 1px solid #E2E8F0;
    padding: 3px 10px;
    border-radius: 6px;
}

.candidate-summary-quote {
    font-size: 13.5px;
    color: #64748B;
    line-height: 1.5;
    margin: 0 0 18px 0;
    background: #F8FAFC;
    border-left: 3px solid #CBD5E1;
    padding: 10px 14px;
    border-radius: 0 8px 8px 0;
}

/* Bottom Action Bar & Streamlined Buttons */
.candidate-actions-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    padding-top: 18px;
    border-top: 1px solid #F1F5F9;
}

.applied-timestamp {
    font-size: 12.5px;
    color: #64748B;
    display: flex;
    align-items: center;
    gap: 6px;
}

.candidate-decision-group {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
}

/* 1. Hire / Select (Primary Prominent Action) */
.btn-card-hire {
    background: #2563EB !important;
    color: #FFFFFF !important;
    border: none !important;
    border-radius: 9px !important;
    font-size: 12.5px !important;
    font-weight: 700 !important;
    padding: 7.5px 16px !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    cursor: pointer !important;
    text-decoration: none !important;
    box-shadow: 0 2px 6px rgba(37,99,235,0.22) !important;
    transition: all 0.15s ease !important;
    white-space: nowrap !important;
}

.btn-card-hire:hover {
    background: #1D4ED8 !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(37,99,235,0.3) !important;
}

/* Secondary Action Buttons (White Card Outline) */
.btn-card-secondary {
    background: #FFFFFF !important;
    color: #334155 !important;
    border: 1.5px solid #CBD5E1 !important;
    border-radius: 9px !important;
    font-size: 12.5px !important;
    font-weight: 600 !important;
    padding: 7px 14px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 6px !important;
    cursor: pointer !important;
    text-decoration: none !important;
    transition: all 0.15s ease !important;
    white-space: nowrap !important;
}

.btn-card-secondary:hover {
    background: #F8FAFC !important;
    border-color: #94A3B8 !important;
    color: #0F172A !important;
    transform: translateY(-1px);
}

.btn-card-secondary.btn-card-view-profile {
    color: #2563EB !important;
    border-color: #BFDBFE !important;
    background: #F8FAFC !important;
}

.btn-card-secondary.btn-card-view-profile:hover {
    background: #EFF6FF !important;
    border-color: #2563EB !important;
    color: #1D4ED8 !important;
}

.btn-card-secondary.is-shortlisted-active {
    background: #ECFDF5 !important;
    border-color: #A7F3D0 !important;
    color: #047857 !important;
    font-weight: 700 !important;
}

/* Card Dropdown Menu */
.card-dropdown-wrap {
    position: relative;
    display: inline-block;
}

.card-dropdown-menu {
    display: none;
    position: absolute;
    bottom: calc(100% + 6px);
    right: 0;
    min-width: 180px;
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 12px;
    box-shadow: 0 10px 25px -4px rgba(15, 23, 42, 0.12);
    padding: 6px;
    z-index: 1050;
}

.card-dropdown-menu.show {
    display: block !important;
}

.card-dropdown-item {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 7.5px 12px;
    font-size: 13px;
    font-weight: 600;
    color: #334155 !important;
    border-radius: 7px;
    text-decoration: none !important;
    transition: all 0.15s ease;
}

.card-dropdown-item:hover {
    background: #F1F5F9;
    color: #0F172A !important;
}

.card-dropdown-item i {
    font-size: 13px;
    width: 16px;
    text-align: center;
    color: #64748B;
}

.card-dropdown-divider {
    height: 1px;
    background: #F1F5F9;
    margin: 4px 0;
}

.card-dropdown-item.text-danger-action {
    color: #DC2626 !important;
}

.card-dropdown-item.text-danger-action i {
    color: #DC2626 !important;
}

.card-dropdown-item.text-danger-action:hover {
    background: #FEF2F2 !important;
    color: #B91C1C !important;
}

/* Empty Candidate State */
.empty-candidates-box {
    text-align: center;
    padding: 60px 20px;
    background: #FFFFFF;
    border-radius: 16px;
    border: 2px dashed #E2E8F0;
}

.empty-candidates-icon {
    width: 72px;
    height: 72px;
    background: #F1F5F9;
    color: #64748B;
    font-size: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin-bottom: 18px;
}

.empty-candidates-box h3 {
    font-size: 20px;
    font-weight: 700;
    color: #0F172A;
    margin-bottom: 8px;
}

.empty-candidates-box p {
    font-size: 14.5px;
    color: #64748B;
    max-width: 420px;
    margin: 0 auto 24px auto;
}

/* Mobile Responsive Optimizations */
@media (max-width: 767px) {
    .applications-container {
        padding: 16px 0 40px 0;
    }
    .applications-main-card {
        padding: 16px 14px;
        border-radius: 14px;
    }
    .app-context-bar {
        margin-bottom: 14px;
    }
    .job-context-header {
        padding: 14px;
        border-radius: 12px;
        margin-bottom: 18px;
    }
    .job-context-title h2 {
        font-size: 17px;
    }
    .app-tabs-nav {
        gap: 8px;
        margin-bottom: 18px;
    }
    .app-tab-btn {
        flex: 1 1 50%;
        justify-content: center;
        padding: 9px 10px;
        font-size: 12.5px;
    }
    .candidate-card-item {
        padding: 16px 14px;
        border-radius: 14px;
        margin-bottom: 16px;
    }
    .candidate-top-layout {
        flex-direction: column;
        gap: 12px;
    }
    .candidate-avatar-group {
        gap: 12px;
        width: 100%;
    }
    .candidate-avatar-wrap {
        width: 52px;
        height: 52px;
        min-width: 52px;
        border-radius: 12px;
    }
    .candidate-meta-info h3 {
        font-size: 16px;
        margin-bottom: 3px;
    }
    .candidate-loc {
        font-size: 12.5px;
        margin-bottom: 6px;
    }
    .candidate-pills-row {
        gap: 6px;
    }
    .pill-exp, .pill-career {
        font-size: 11.5px;
        padding: 3px 8px;
    }
    .candidate-salary-box {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 9px 14px;
        border-radius: 10px;
        text-align: left;
        min-width: 0;
        margin-top: 2px;
    }
    .salary-sub-label {
        margin-bottom: 0;
        font-size: 11px;
    }
    .salary-main-val {
        font-size: 16px;
        display: flex;
        align-items: baseline;
    }
    .candidate-skills-wrap {
        margin: 10px 0 12px 0;
        gap: 5px;
    }
    .candidate-skill-tag {
        font-size: 11.5px;
        padding: 2.5px 8px;
    }
    .candidate-actions-toolbar {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
        padding-top: 12px;
    }
    .applied-timestamp {
        font-size: 12px;
    }
    .candidate-decision-group {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        width: 100%;
    }
    .btn-card-hire {
        flex: 1 1 calc(50% - 4px);
        justify-content: center;
        font-size: 13px !important;
        padding: 8.5px 8px !important;
        white-space: nowrap !important;
    }
    .btn-card-secondary.btn-card-view-profile {
        flex: 1 1 calc(50% - 4px);
        justify-content: center;
        font-size: 13px !important;
        padding: 8.5px 8px !important;
        white-space: nowrap !important;
    }
    .btn-card-secondary:not(.btn-card-view-profile) {
        flex: 1 1 auto;
        justify-content: center;
        font-size: 12px !important;
        padding: 7.5px 8px !important;
        white-space: nowrap !important;
    }
    .card-dropdown-wrap {
        flex: 1 1 auto;
        display: flex;
    }
    .card-dropdown-wrap > button {
        width: 100%;
        justify-content: center;
        font-size: 12px !important;
        padding: 7.5px 8px !important;
    }
}
</style>

<div class="applications-container">
    <div class="container">
        <div class="row">
            @include('includes.company_dashboard_menu')

            <div class="col-lg-9 col-md-8 col-sm-12"> 
                <div class="applications-main-card">
                    <!-- Back Link -->
                    <div class="app-context-bar">
                        <a href="{{ route('posted.jobs') }}" class="btn-back-jobs">
                            <i class="fa fa-arrow-left"></i> {{ __('Back to All Posted Jobs') }}
                        </a>
                    </div>

                    <!-- Job Context Header Banner (if job object is present) -->
                    @if(isset($job) && null !== $job)
                    @php
                        $totalApplicantsCount = \App\JobApply::where('job_id', $job->id)->count();
                        $totalShortlistCount = \App\FavouriteApplicant::where('job_id', $job->id)->where('company_id', Auth::guard('company')->user()->id)->count();
                    @endphp
                    <div class="job-headline-card">
                        <div class="job-headline-info">
                            <h2>{{ $job->title }}</h2>
                            <div class="job-headline-chips">
                                @if($job->getJobShift('job_shift'))
                                <span class="job-chip-badge">
                                    <i class="fa fa-clock-o"></i> {{ $job->getJobShift('job_shift') }}
                                </span>
                                @endif
                                @if($job->getCity('city'))
                                <span class="job-chip-badge">
                                    <i class="fa fa-map-marker text-danger"></i> {{ $job->getCity('city') }}
                                </span>
                                @endif
                                @if(!empty($job->salary_from) || !empty($job->salary_to))
                                <span class="job-chip-badge">
                                    <i class="fa fa-money text-success"></i> 
                                    @if(!empty($job->salary_from) && !empty($job->salary_to))
                                        {{$job->salary_from}} - {{$job->salary_to}} {{$job->salary_currency}}
                                    @elseif(!empty($job->salary_from))
                                        {{$job->salary_from}} {{$job->salary_currency}}
                                    @else
                                        {{$job->salary_to}} {{$job->salary_currency}}
                                    @endif
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Tabs: All Candidates vs Shortlisted -->
                    <div class="app-tabs-nav">
                        <a href="{{ route('list.applied.users', [$job->id]) }}" class="app-tab-btn {{ (!isset($is_favourite_view) || !$is_favourite_view) ? 'active-tab' : 'inactive-tab' }}">
                            <i class="fa fa-users"></i> {{ __('All Applicants') }}
                            <span class="tab-count-pill" id="tab_all_count">{{ $totalApplicantsCount }}</span>
                        </a>
                        <a href="{{ route('list.favourite.applied.users', [$job->id]) }}" class="app-tab-btn {{ (isset($is_favourite_view) && $is_favourite_view) ? 'active-tab' : 'inactive-tab' }}">
                            <i class="fa fa-star text-warning"></i> {{ __('Shortlisted') }}
                            <span class="tab-count-pill" id="tab_short_count">{{ $totalShortlistCount }}</span>
                        </a>
                    </div>
                    @endif

                    <!-- Candidates List -->
                    @if(isset($job_applications) && count($job_applications))
                        @foreach($job_applications as $job_application)
                        @php
                            $user = $job_application->getUser();
                            $currentJob = isset($job) ? $job : $job_application->getJob();
                            $company = Auth::guard('company')->user();             
                            $profileCv = $job_application->getProfileCv();
                            
                            $appStatus = strtolower($job_application->status ?: 'applied');

                            $userSkills = [];
                            if ($user) {
                                $savedSkills = \App\ProfileSkill::where('user_id', $user->id)->take(6)->get();
                                foreach ($savedSkills as $sk) {
                                    $skName = $sk->getJobSkill('job_skill');
                                    if (!empty($skName)) {
                                        $userSkills[] = $skName;
                                    }
                                }
                            }
                        @endphp

                        @if(null !== $job_application && null !== $user && null !== $currentJob)
                        <div class="candidate-card-item" id="app_card_{{ $job_application->id }}">
                            <!-- Top candidate info -->
                            <div class="candidate-top-layout">
                                <div class="candidate-avatar-group">
                                    <div class="candidate-avatar-wrap">
                                        {!! $user->printUserImage(80, 80) !!}
                                    </div>
                                    <div class="candidate-meta-info">
                                        <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                            <h3>
                                                <a href="{{route('applicant.profile', $job_application->id)}}">
                                                    {{$user->getName()}}
                                                </a>
                                            </h3>
                                            <!-- Dynamic Status Badge -->
                                            <span id="status_badge_{{ $job_application->id }}">
                                                @if($appStatus == 'hired')
                                                    <span class="status-badge-hired"><i class="fa fa-check-circle"></i> {{ __('Hired / Selected') }}</span>
                                                @elseif($appStatus == 'rejected')
                                                    <span class="status-badge-rejected"><i class="fa fa-times-circle"></i> {{ __('Rejected') }}</span>
                                                @elseif($appStatus == 'shortlisted')
                                                    <span class="status-badge-shortlisted"><i class="fa fa-star"></i> {{ __('Shortlisted') }}</span>
                                                @else
                                                    <span class="status-badge-applied"><i class="fa fa-clock-o"></i> {{ __('Under Review') }}</span>
                                                @endif
                                            </span>
                                        </div>

                                        <div class="candidate-loc">
                                            <i class="fa fa-map-marker text-danger"></i> 
                                            {{$user->getLocation() ?: 'Location not specified'}}
                                        </div>
                                        <div class="candidate-pills-row">
                                            @if($user->getJobExperience('job_experience'))
                                            <span class="pill-exp">
                                                <i class="fa fa-briefcase"></i> {{$user->getJobExperience('job_experience')}}
                                            </span>
                                            @endif
                                            @if($user->getCareerLevel('career_level'))
                                            <span class="pill-career">
                                                {{$user->getCareerLevel('career_level')}}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Expected Salary Box -->
                                @if(!empty($job_application->expected_salary))
                                @php
                                    $appSalaryCurr = !empty($job_application->salary_currency) ? $job_application->salary_currency : 'INR';
                                    $appCurrencySym = ($appSalaryCurr == 'INR' || $appSalaryCurr == 'Rs') ? '₹' : ($appSalaryCurr == 'USD' ? '$' : $appSalaryCurr . ' ');
                                    $appNumClean = preg_replace('/[^0-9.]/', '', $job_application->expected_salary);
                                    $appSalaryNum = is_numeric($appNumClean) && $appNumClean > 0 ? number_format((float)$appNumClean) : $job_application->expected_salary;
                                    $appSalaryPeriod = isset($currentJob) && $currentJob && $currentJob->getSalaryPeriod('salary_period') ? strtolower($currentJob->getSalaryPeriod('salary_period')) : 'month';
                                    if ($appSalaryPeriod == 'monthly') $appSalaryPeriod = 'month';
                                    if ($appSalaryPeriod == 'yearly' || $appSalaryPeriod == 'annually') $appSalaryPeriod = 'year';
                                @endphp
                                <div class="candidate-salary-box">
                                    <div class="salary-sub-label">{{ __('Expected Salary') }}</div>
                                    <div class="salary-main-val">
                                        <span>{{ $appCurrencySym }}{{ $appSalaryNum }}</span>
                                        <span class="salary-period-sub">/ {{ $appSalaryPeriod }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Skills Chips (if any) -->
                            @if(count($userSkills))
                            <div class="candidate-skills-wrap">
                                <span class="skills-title-label"><i class="fa fa-tags"></i> {{ __('Skills') }}:</span>
                                @foreach($userSkills as $skillItem)
                                    <span class="candidate-skill-tag">{{ $skillItem }}</span>
                                @endforeach
                            </div>
                            @endif

                            <!-- Summary excerpt -->
                            @if($user->getProfileSummary('summary'))
                            <div class="candidate-summary-quote">
                                "{{\Illuminate\Support\Str::limit($user->getProfileSummary('summary'), 180, '...')}}"
                            </div>
                            @endif

                            <!-- Bottom Action Bar with 1-Click Hire / Reject / Shortlist Pipeline -->
                            <!-- Bottom Action Bar with Streamlined Priority Buttons & More Dropdown -->
                            <div class="candidate-actions-toolbar">
                                <div class="applied-timestamp">
                                    <i class="fa fa-clock-o"></i> 
                                    Applied {{ $job_application->created_at ? $job_application->created_at->diffForHumans() : 'Recently' }}
                                </div>

                                <div class="candidate-decision-group">
                                    <!-- 1. Hire / Select (Primary Prominent Action) -->
                                    <button type="button" onclick="setCandidateStatus({{ $job_application->id }}, 'hired')" class="btn-card-hire" title="{{ __('Mark candidate as Hired / Selected') }}">
                                        <i class="fa fa-check-circle"></i> <span>{{ __('Hire / Select') }}</span>
                                    </button>

                                    <!-- 2. View Profile (Primary/Secondary Link) -->
                                    <a href="{{route('applicant.profile', $job_application->id)}}" class="btn-card-secondary btn-card-view-profile" title="{{ __('View Candidate Full Profile') }}">
                                        <i class="fa fa-user-circle"></i> <span>{{__('View Profile')}}</span>
                                    </a>

                                    <!-- 3. Resume Button (if available) -->
                                    @if(null !== $profileCv && !empty($profileCv->cv_file))
                                        <a href="{{asset('cvs/'.$profileCv->cv_file)}}" target="_blank" class="btn-card-secondary" title="{{ __('Download Candidate Resume') }}">
                                            <i class="fa fa-file-pdf-o text-danger"></i> <span>{{ __('Resume') }}</span>
                                        </a>
                                    @endif

                                    <!-- 4. Shortlist Button (Secondary Action) -->
                                    <button type="button" id="btn_card_shortlist_{{ $job_application->id }}" onclick="setCandidateStatus({{ $job_application->id }}, '{{ $appStatus == 'shortlisted' ? 'applied' : 'shortlisted' }}')" class="btn-card-secondary {{ $appStatus == 'shortlisted' ? 'is-shortlisted-active' : '' }}" title="{{ $appStatus == 'shortlisted' ? __('Candidate is Shortlisted') : __('Click to Shortlist') }}">
                                        <i class="fa {{ $appStatus == 'shortlisted' ? 'fa-check text-success' : 'fa-bookmark-o' }}" id="icon_card_shortlist_{{ $job_application->id }}"></i> 
                                        <span id="text_card_shortlist_{{ $job_application->id }}">{{ $appStatus == 'shortlisted' ? __('Shortlisted') : __('Shortlist') }}</span>
                                    </button>

                                    <!-- 5. More Dropdown (Reject, Share, Add Note) -->
                                    <div class="card-dropdown-wrap">
                                        <button type="button" class="btn-card-secondary" onclick="toggleCardDropdown(event, {{ $job_application->id }})">
                                            <span>{{ __('More') }}</span>
                                            <i class="fa fa-angle-down" id="card_caret_{{ $job_application->id }}" style="font-size: 13px; margin-left: 2px; transition: transform 0.2s;"></i>
                                        </button>
                                        <div class="card-dropdown-menu" id="card_dropdown_{{ $job_application->id }}">
                                            <a href="{{route('applicant.profile', $job_application->id)}}" class="card-dropdown-item">
                                                <i class="fa fa-sticky-note-o"></i> <span>{{ __('Add Note') }}</span>
                                            </a>
                                            <a href="javascript:;" onclick="shareCardProfile('{{ route('applicant.profile', $job_application->id) }}')" class="card-dropdown-item">
                                                <i class="fa fa-share-alt"></i> <span>{{ __('Share Profile') }}</span>
                                            </a>
                                            <div class="card-dropdown-divider"></div>
                                            <a href="javascript:;" onclick="setCandidateStatus({{ $job_application->id }}, 'rejected')" class="card-dropdown-item text-danger-action">
                                                <i class="fa fa-times"></i> <span>{{ __('Reject Candidate') }}</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    @else
                        <!-- Clean Empty State -->
                        <div class="empty-candidates-box">
                            <div class="empty-candidates-icon">
                                <i class="fa fa-users"></i>
                            </div>
                            <h3>
                                @if(isset($is_favourite_view) && $is_favourite_view)
                                    {{ __('No Shortlisted Candidates') }}
                                @else
                                    {{ __('No Applications Received Yet') }}
                                @endif
                            </h3>
                            <p>
                                @if(isset($is_favourite_view) && $is_favourite_view)
                                    {{ __('You have not shortlisted any candidates for this job yet. Review all applicants and click "Shortlist" or "Hire" to organize them.') }}
                                @else
                                    {{ __('No candidates have applied for this position yet. Check back soon or promote your job posting to get more applicants.') }}
                                @endif
                            </p>
                            <a href="{{ route('posted.jobs') }}" class="btn-back-jobs">
                                <i class="fa fa-arrow-left"></i> {{ __('Back to All Posted Jobs') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.footer')
@endsection

@push('scripts')
<script type="text/javascript">
function toggleCardDropdown(e, appId) {
    if (e) e.stopPropagation();
    var menu = $('#card_dropdown_' + appId);
    var caret = $('#card_caret_' + appId);
    var wasOpen = menu.hasClass('show');
    
    // Close other open card dropdowns
    $('.card-dropdown-menu').removeClass('show');
    $('.card-dropdown-wrap i.fa-angle-down').css('transform', 'rotate(0deg)');
    
    if (!wasOpen) {
        menu.addClass('show');
        caret.css('transform', 'rotate(180deg)');
    }
}

// Close card menus on outside click
$(document).on('click', function(e) {
    if (!$(e.target).closest('.card-dropdown-wrap').length) {
        $('.card-dropdown-menu').removeClass('show');
        $('.card-dropdown-wrap i.fa-angle-down').css('transform', 'rotate(0deg)');
    }
});

function shareCardProfile(profileUrl) {
    $('.card-dropdown-menu').removeClass('show');
    $('.card-dropdown-wrap i.fa-angle-down').css('transform', 'rotate(0deg)');
    
    var dummy = document.createElement('input');
    document.body.appendChild(dummy);
    dummy.value = profileUrl;
    dummy.select();
    document.execCommand('copy');
    document.body.removeChild(dummy);

    if (typeof swal !== 'undefined') {
        swal({
            title: "Link Copied!",
            text: "Candidate profile link copied to clipboard.",
            icon: "success",
            button: "OK"
        });
    } else {
        alert("Candidate profile link copied to clipboard!");
    }
}

function setCandidateStatus(applicationId, newStatus) {
    var confirmMsg = '';
    if (newStatus === 'hired') {
        confirmMsg = 'Are you sure you want to mark this candidate as HIRED / SELECTED?';
    } else if (newStatus === 'rejected') {
        confirmMsg = 'Are you sure you want to REJECT this candidate?';
    } else if (newStatus === 'shortlisted') {
        confirmMsg = 'Are you sure you want to SHORTLIST this candidate?';
    }

    if (confirmMsg && !confirm(confirmMsg)) {
        return;
    }

    $('.card-dropdown-menu').removeClass('show');
    $('.card-dropdown-wrap i.fa-angle-down').css('transform', 'rotate(0deg)');

    $.ajax({
        url: "{{ route('company.update.application.status') }}",
        type: 'POST',
        data: {
            application_id: applicationId,
            status: newStatus,
            _token: '{{ csrf_token() }}'
        },
        beforeSend: function() {
            $('#status_badge_' + applicationId).css('opacity', '0.5');
        },
        success: function(response) {
            $('#status_badge_' + applicationId).css('opacity', '1');
            if (response.status === 'success') {
                var badgeHtml = '';
                if (newStatus === 'hired') {
                    badgeHtml = '<span class="status-badge-hired"><i class="fa fa-check-circle"></i> Hired / Selected</span>';
                } else if (newStatus === 'rejected') {
                    badgeHtml = '<span class="status-badge-rejected"><i class="fa fa-times-circle"></i> Rejected</span>';
                } else if (newStatus === 'shortlisted') {
                    badgeHtml = '<span class="status-badge-shortlisted"><i class="fa fa-star"></i> Shortlisted</span>';
                } else {
                    badgeHtml = '<span class="status-badge-applied"><i class="fa fa-clock-o"></i> Under Review</span>';
                }
                $('#status_badge_' + applicationId).html(badgeHtml);
                
                // Update shortlist button in card
                var btnShortlist = $('#btn_card_shortlist_' + applicationId);
                var iconShortlist = $('#icon_card_shortlist_' + applicationId);
                var textShortlist = $('#text_card_shortlist_' + applicationId);
                
                if (newStatus === 'shortlisted') {
                    btnShortlist.addClass('is-shortlisted-active');
                    iconShortlist.removeClass('fa-bookmark-o fa-star').addClass('fa-check text-success');
                    textShortlist.text('Shortlisted');
                    btnShortlist.attr('onclick', "setCandidateStatus(" + applicationId + ", 'applied')");
                } else if (newStatus === 'rejected' || newStatus === 'applied') {
                    btnShortlist.removeClass('is-shortlisted-active');
                    iconShortlist.removeClass('fa-check text-success fa-star').addClass('fa-bookmark-o');
                    textShortlist.text('Shortlist');
                    btnShortlist.attr('onclick', "setCandidateStatus(" + applicationId + ", 'shortlisted')");
                }

                if (typeof swal !== 'undefined') {
                    swal({
                        title: "Updated",
                        text: response.message,
                        icon: "success",
                        button: "OK"
                    });
                } else {
                    alert(response.message);
                }
            } else {
                alert(response.message || 'Status update failed.');
            }
        },
        error: function(xhr) {
            $('#status_badge_' + applicationId).css('opacity', '1');
            alert('Error updating status: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.statusText));
        }
    });
}
</script>
@endpush