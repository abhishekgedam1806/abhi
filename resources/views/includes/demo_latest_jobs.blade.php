@php
// Filter active, non-expired jobs with active companies for Latest Jobs
$validLatestJobs = collect();

if (isset($latestJobs) && count($latestJobs)) {
    foreach ($latestJobs as $lj) {
        $comp = $lj->getCompany();
        if ($comp && $comp->is_active && $lj->is_active && (!$lj->expiry_date || \Carbon\Carbon::parse($lj->expiry_date)->gte(\Carbon\Carbon::today()))) {
            $validLatestJobs->push($lj);
        }
    }
}

// Total jobs available
$totalLatestCount = $validLatestJobs->count();
// Limit initial display to 6 prominent jobs
$initialLimit = 6;
$initialJobs = $validLatestJobs->take($initialLimit);
$remainingJobs = $validLatestJobs->slice($initialLimit, 6);
@endphp

<style>
/* Latest Jobs Section - Prominent & Curated */
.demo-lj-section {
    padding: 50px 0 40px 0;
    background: #F8FAFC;
    width: 100%;
    position: relative;
    border-top: 1px solid #EEF2F6;
    border-bottom: 1px solid #EEF2F6;
}

/* Section Header */
.demo-lj-header {
    margin-bottom: 28px;
}
.demo-lj-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: #EFF6FF;
    color: #2563EB;
    font-size: 11.5px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.9px;
    padding: 5px 14px;
    border-radius: 50px;
    margin-bottom: 10px;
    border: 1px solid #DBEAFE;
}
.demo-lj-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #2563EB;
    display: inline-block;
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.25);
    animation: demoPulse 1.8s infinite;
}
@keyframes demoPulse {
    0% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.4); }
    70% { box-shadow: 0 0 0 6px rgba(37, 99, 235, 0); }
    100% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0); }
}

.demo-lj-title {
    font-size: 28px;
    font-weight: 800;
    color: #0F172A;
    letter-spacing: -0.5px;
    margin: 0 0 6px 0;
}
.demo-lj-gradient {
    background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.demo-lj-subtitle {
    font-size: 14.5px;
    color: #64748B;
    margin: 0;
    max-width: 620px;
}

.demo-lj-header-action {
    display: flex;
    justify-content: flex-end;
    align-items: center;
}
.demo-lj-top-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #FFFFFF;
    color: #2563EB !important;
    border: 1.5px solid #DBEAFE;
    font-size: 13.5px;
    font-weight: 700;
    padding: 9px 20px;
    border-radius: 10px;
    text-decoration: none !important;
    transition: all 0.22s ease;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.05);
}
.demo-lj-top-btn:hover {
    background: #2563EB;
    color: #FFFFFF !important;
    border-color: #2563EB;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.22);
}

/* Prominent Job Card */
.demo-lj-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: calc(100% - 20px);
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
    position: relative;
    box-sizing: border-box;
}
.demo-lj-card:hover {
    border-color: #93C5FD;
    transform: translateY(-3px);
    box-shadow: 0 14px 28px rgba(37, 99, 235, 0.09);
}

/* Card Top Row */
.demo-lj-card-top {
    display: flex;
    gap: 14px;
    align-items: flex-start;
    margin-bottom: 12px;
}
.demo-lj-logo-box {
    width: 50px;
    height: 50px;
    min-width: 50px;
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
.demo-lj-logo-box img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
.demo-lj-logo-fallback {
    font-size: 18px;
    font-weight: 800;
    color: #2563EB;
    background: #EFF6FF;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
}

.demo-lj-card-header-txt {
    flex: 1;
    min-width: 0;
}
.demo-lj-job-title {
    font-size: 16px;
    font-weight: 800;
    color: #0F172A;
    margin: 0 0 3px 0;
    line-height: 1.3;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.demo-lj-job-title a {
    color: #0F172A;
    text-decoration: none !important;
    transition: color 0.2s ease;
}
.demo-lj-job-title a:hover {
    color: #2563EB;
}

.demo-lj-comp-row {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 600;
    color: #64748B;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.demo-lj-comp-row a {
    color: #64748B;
    text-decoration: none !important;
}
.demo-lj-comp-row a:hover {
    color: #2563EB;
}
.demo-lj-verified-icon {
    color: #059669;
    font-size: 12px;
}

/* Badges / Chips */
.demo-lj-chips-wrap {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 14px;
}
.demo-lj-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    font-weight: 600;
    padding: 3.5px 9px;
    border-radius: 6px;
    line-height: 1.2;
}
.demo-lj-chip-type {
    background: #EFF6FF;
    color: #2563EB;
}
.demo-lj-chip-loc {
    background: #F1F5F9;
    color: #475569;
}
.demo-lj-chip-sal {
    background: #ECFDF5;
    color: #047857;
    font-weight: 700;
}

/* Card Bottom Row */
.demo-lj-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 12px;
    border-top: 1px solid #F1F5F9;
    margin-top: 4px;
}
.demo-lj-posted-time {
    font-size: 11.5px;
    color: #94A3B8;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.demo-lj-apply-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #2563EB;
    color: #FFFFFF !important;
    font-size: 12px;
    font-weight: 700;
    padding: 7px 15px;
    border-radius: 8px;
    text-decoration: none !important;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2);
}
.demo-lj-apply-btn:hover {
    background: #1D4ED8;
    transform: translateX(2px);
    box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
}

/* Bottom Actions: View More Toggle & All Jobs */
.demo-lj-bottom-bar {
    text-align: center;
    margin-top: 18px;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}
.demo-lj-more-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: #FFFFFF;
    color: #334155 !important;
    border: 1.5px solid #CBD5E1;
    font-size: 13.5px;
    font-weight: 700;
    padding: 10px 22px;
    border-radius: 10px;
    text-decoration: none !important;
    cursor: pointer;
    transition: all 0.2s ease;
}
.demo-lj-more-btn:hover {
    border-color: #2563EB;
    color: #2563EB !important;
    background: #F8FAFC;
}
.demo-lj-all-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #2563EB;
    color: #FFFFFF !important;
    font-size: 13.5px;
    font-weight: 700;
    padding: 10px 24px;
    border-radius: 10px;
    text-decoration: none !important;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
}
.demo-lj-all-btn:hover {
    background: #1D4ED8;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(37, 99, 235, 0.35);
}

/* Mobile Responsiveness */
@media (max-width: 767px) {
    .demo-lj-section {
        padding: 28px 0 24px 0 !important;
    }
    .demo-lj-header {
        margin-bottom: 18px !important;
    }
    .demo-lj-title {
        font-size: 21px !important;
        margin-bottom: 4px !important;
    }
    .demo-lj-subtitle {
        font-size: 13px !important;
        line-height: 1.4 !important;
    }
    .demo-lj-card {
        padding: 15px !important;
        margin-bottom: 12px !important;
        border-radius: 14px !important;
        height: auto !important;
    }
    .demo-lj-job-title {
        font-size: 15px !important;
    }
    .demo-lj-logo-box {
        width: 44px !important;
        height: 44px !important;
        min-width: 44px !important;
    }
    .demo-lj-bottom-bar {
        margin-top: 14px !important;
        flex-direction: column !important;
        width: 100% !important;
    }
    .demo-lj-more-btn, .demo-lj-all-btn {
        width: 100% !important;
        justify-content: center !important;
    }
}
</style>

<div class="demo-lj-section">
    <div class="container">
        {{-- Section Header --}}
        <div class="row align-items-center demo-lj-header">
            <div class="col-md-8 col-12">
                <div class="demo-lj-badge">
                    <span class="demo-lj-dot"></span> {{ __('Fresh Opportunities') }}
                </div>
                <h2 class="demo-lj-title">
                    {{ __('Latest') }} <span class="demo-lj-gradient">{{ __('Job Openings') }}</span>
                </h2>
                <p class="demo-lj-subtitle">
                    {{ __('Hand-picked verified jobs recently posted directly by actively hiring companies.') }}
                </p>
            </div>
            <div class="col-md-4 col-12 text-md-right mt-3 mt-md-0 d-none d-md-block">
                <div class="demo-lj-header-action">
                    <a href="{{ route('job.list') }}" class="demo-lj-top-btn">
                        {{ __('Explore All Jobs') }} <i class="fa fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Initial 6 Prominent Job Cards (2 rows of 3 on desktop) --}}
        <div class="row">
            @if(count($initialJobs))
                @foreach($initialJobs as $latestJob)
                @php 
                    $company = $latestJob->getCompany(); 
                @endphp
                @if(null !== $company)
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="demo-lj-card">
                        <div>
                            <div class="demo-lj-card-top">
                                <div class="demo-lj-logo-box">
                                    <a href="{{ route('job.detail', [$latestJob->slug]) }}" title="{{ $latestJob->title }}">
                                        @if(!empty($company->logo) && file_exists(public_path('company_logos/' . $company->logo)))
                                            <img src="{{ asset('company_logos/' . $company->logo) }}" alt="{{ $company->name }}">
                                        @else
                                            <div class="demo-lj-logo-fallback">
                                                {{ strtoupper(substr($company->name ?? 'J', 0, 1)) }}
                                            </div>
                                        @endif
                                    </a>
                                </div>
                                <div class="demo-lj-card-header-txt">
                                    <h3 class="demo-lj-job-title">
                                        <a href="{{ route('job.detail', [$latestJob->slug]) }}" title="{{ $latestJob->title }}">
                                            {{ $latestJob->title }}
                                        </a>
                                    </h3>
                                    <p class="demo-lj-comp-row">
                                        <a href="{{ route('company.detail', $company->slug) }}" title="{{ $company->name }}">
                                            {{ $company->name }}
                                        </a>
                                        <i class="fa fa-check-circle demo-lj-verified-icon" title="{{ __('Verified Employer') }}"></i>
                                    </p>
                                </div>
                            </div>

                            {{-- Key Highlights / Meta Chips --}}
                            <div class="demo-lj-chips-wrap">
                                @if(!empty($latestJob->getJobType('job_type')))
                                    <span class="demo-lj-chip demo-lj-chip-type">
                                        <i class="fa fa-briefcase"></i> {{ $latestJob->getJobType('job_type') }}
                                    </span>
                                @endif

                                @if(!empty($latestJob->getCity('city')))
                                    <span class="demo-lj-chip demo-lj-chip-loc">
                                        <i class="fa fa-map-marker"></i> {{ $latestJob->getCity('city') }}
                                    </span>
                                @endif

                                @if(!empty($latestJob->salary_from) || !empty($latestJob->salary_to))
                                    <span class="demo-lj-chip demo-lj-chip-sal">
                                        <i class="fa fa-money"></i> 
                                        @if(!empty($latestJob->salary_currency)){{ $latestJob->salary_currency }} @endif
                                        {{ $latestJob->salary_from ? number_format($latestJob->salary_from) : '' }}
                                        @if($latestJob->salary_from && $latestJob->salary_to) - @endif
                                        {{ $latestJob->salary_to ? number_format($latestJob->salary_to) : '' }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Card Footer: Posted Time + Prominent Apply Button --}}
                        <div class="demo-lj-card-footer">
                            <span class="demo-lj-posted-time">
                                <i class="fa fa-clock-o"></i> {{ $latestJob->created_at ? $latestJob->created_at->diffForHumans() : __('Recently') }}
                            </span>
                            <a href="{{ route('job.detail', [$latestJob->slug]) }}" class="demo-lj-apply-btn">
                                <span>{{ __('Apply Now') }}</span> <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            @else
                <div class="col-12 text-center py-4">
                    <p style="color: #64748B;">{{ __('No jobs available at the moment.') }}</p>
                </div>
            @endif
        </div>

        {{-- Remaining Jobs (collapsible for fast loading and reduced clutter) --}}
        @if(count($remainingJobs))
        <div id="demo-lj-extra-jobs" class="row" style="display: none;">
            @foreach($remainingJobs as $latestJob)
            @php 
                $company = $latestJob->getCompany(); 
            @endphp
            @if(null !== $company)
            <div class="col-lg-4 col-md-6 col-12">
                <div class="demo-lj-card">
                    <div>
                        <div class="demo-lj-card-top">
                            <div class="demo-lj-logo-box">
                                <a href="{{ route('job.detail', [$latestJob->slug]) }}" title="{{ $latestJob->title }}">
                                    @if(!empty($company->logo) && file_exists(public_path('company_logos/' . $company->logo)))
                                        <img src="{{ asset('company_logos/' . $company->logo) }}" alt="{{ $company->name }}">
                                    @else
                                        <div class="demo-lj-logo-fallback">
                                            {{ strtoupper(substr($company->name ?? 'J', 0, 1)) }}
                                        </div>
                                    @endif
                                </a>
                            </div>
                            <div class="demo-lj-card-header-txt">
                                <h3 class="demo-lj-job-title">
                                    <a href="{{ route('job.detail', [$latestJob->slug]) }}" title="{{ $latestJob->title }}">
                                        {{ $latestJob->title }}
                                    </a>
                                </h3>
                                <p class="demo-lj-comp-row">
                                    <a href="{{ route('company.detail', $company->slug) }}" title="{{ $company->name }}">
                                        {{ $company->name }}
                                    </a>
                                    <i class="fa fa-check-circle demo-lj-verified-icon" title="{{ __('Verified Employer') }}"></i>
                                </p>
                            </div>
                        </div>

                        {{-- Chips --}}
                        <div class="demo-lj-chips-wrap">
                            @if(!empty($latestJob->getJobType('job_type')))
                                <span class="demo-lj-chip demo-lj-chip-type">
                                    <i class="fa fa-briefcase"></i> {{ $latestJob->getJobType('job_type') }}
                                </span>
                            @endif

                            @if(!empty($latestJob->getCity('city')))
                                <span class="demo-lj-chip demo-lj-chip-loc">
                                    <i class="fa fa-map-marker"></i> {{ $latestJob->getCity('city') }}
                                </span>
                            @endif

                            @if(!empty($latestJob->salary_from) || !empty($latestJob->salary_to))
                                <span class="demo-lj-chip demo-lj-chip-sal">
                                    <i class="fa fa-money"></i> 
                                    @if(!empty($latestJob->salary_currency)){{ $latestJob->salary_currency }} @endif
                                    {{ $latestJob->salary_from ? number_format($latestJob->salary_from) : '' }}
                                    @if($latestJob->salary_from && $latestJob->salary_to) - @endif
                                    {{ $latestJob->salary_to ? number_format($latestJob->salary_to) : '' }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="demo-lj-card-footer">
                        <span class="demo-lj-posted-time">
                            <i class="fa fa-clock-o"></i> {{ $latestJob->created_at ? $latestJob->created_at->diffForHumans() : __('Recently') }}
                        </span>
                        <a href="{{ route('job.detail', [$latestJob->slug]) }}" class="demo-lj-apply-btn">
                            <span>{{ __('Apply Now') }}</span> <i class="fa fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
        @endif

        {{-- Bottom Bar: "Show More Jobs (+6)" and "Explore All Jobs" --}}
        <div class="demo-lj-bottom-bar">
            @if(count($remainingJobs))
                <button type="button" id="btn-toggle-demo-lj" class="demo-lj-more-btn" onclick="toggleDemoLatestJobs()">
                    <i class="fa fa-plus-circle"></i> <span>{{ __('Show More Jobs (+:count)', ['count' => count($remainingJobs)]) }}</span>
                </button>
            @endif

            <a href="{{ route('job.list') }}" class="demo-lj-all-btn">
                <span>{{ __('Explore All Jobs') }}</span> <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<script>
function toggleDemoLatestJobs() {
    var extra = document.getElementById('demo-lj-extra-jobs');
    var btn = document.getElementById('btn-toggle-demo-lj');
    if (!extra || !btn) return;
    
    if (extra.style.display === 'none' || extra.style.display === '') {
        extra.style.display = 'flex';
        btn.innerHTML = '<i class="fa fa-minus-circle"></i> <span>{{ __("Show Less Jobs") }}</span>';
    } else {
        extra.style.display = 'none';
        btn.innerHTML = '<i class="fa fa-plus-circle"></i> <span>{{ __("Show More Jobs (+:count)", ["count" => count($remainingJobs)]) }}</span>';
    }
}
</script>
