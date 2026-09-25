@php
// Query strictly active, non-expired jobs with active companies
$validFeaturedJobs = collect();

if (isset($featuredJobs) && count($featuredJobs)) {
    foreach ($featuredJobs as $fj) {
        $comp = $fj->getCompany();
        if ($comp && $comp->is_active && $fj->is_active && (!$fj->expiry_date || \Carbon\Carbon::parse($fj->expiry_date)->isFuture())) {
            $validFeaturedJobs->push($fj);
        }
    }
}

// If featured jobs are few, supplement with active non-expired latest jobs
if ($validFeaturedJobs->count() < 4 && isset($latestJobs)) {
    foreach ($latestJobs as $lj) {
        if ($validFeaturedJobs->count() >= 6) break;
        if (!$validFeaturedJobs->contains('id', $lj->id)) {
            $comp = $lj->getCompany();
            if ($comp && $comp->is_active && $lj->is_active && (!$lj->expiry_date || \Carbon\Carbon::parse($lj->expiry_date)->isFuture())) {
                $validFeaturedJobs->push($lj);
            }
        }
    }
}
@endphp

<style>
.demo-fj-section {
    padding: 48px 0 36px 0;
    background: #FFFFFF;
    width: 100%;
    overflow: hidden;
}
.demo-fj-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #FEF3C7;
    color: #B45309;
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    padding: 5px 12px;
    border-radius: 50px;
    margin-bottom: 8px;
}
.demo-fj-title {
    font-size: 26px;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 6px;
    letter-spacing: -0.4px;
}
.demo-fj-subtitle {
    font-size: 14.5px;
    color: #64748B;
    margin: 0;
}
.demo-fj-viewall-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #2563EB;
    font-size: 13.5px;
    font-weight: 700;
    border: 1.5px solid #E2E8F0;
    padding: 8px 18px;
    border-radius: 10px;
    text-decoration: none !important;
    transition: all 0.2s ease;
    background: #FFFFFF;
}
.demo-fj-viewall-btn:hover {
    border-color: #2563EB;
    background: #EFF6FF;
    color: #1D4ED8;
}

/* Fixed Job Card */
.demo-job-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    padding: 18px;
    margin-bottom: 16px;
    transition: all 0.22s ease;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: calc(100% - 16px);
    position: relative;
    box-sizing: border-box;
    width: 100%;
}
.demo-job-card:hover {
    border-color: #93C5FD;
    box-shadow: 0 10px 24px rgba(37, 99, 235, 0.08);
    transform: translateY(-2px);
}
.demo-job-top {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    margin-bottom: 12px;
}
.demo-job-logo {
    width: 46px;
    height: 46px;
    min-width: 46px;
    border-radius: 10px;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 3px;
    font-weight: 800;
    font-size: 17px;
    color: #2563EB;
    flex-shrink: 0;
}
.demo-job-logo img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
.demo-job-heading {
    flex: 1;
    min-width: 0;
}
.demo-job-title-txt {
    font-size: 15.5px;
    font-weight: 700;
    color: #0F172A;
    margin: 0 0 3px 0;
    line-height: 1.35;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.demo-job-title-txt a {
    color: #0F172A;
    text-decoration: none !important;
}
.demo-job-title-txt a:hover {
    color: #2563EB;
}
.demo-job-company {
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.demo-job-company a {
    color: #475569;
    text-decoration: none !important;
}
.demo-job-company a:hover {
    color: #2563EB;
}

/* Meta Badges */
.demo-job-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 12px;
}
.demo-meta-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11.5px;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 6px;
    line-height: 1.4;
    white-space: nowrap;
}
.demo-pill-loc {
    background: #F1F5F9;
    color: #475569;
}
.demo-pill-exp {
    background: #FAF5FF;
    color: #9333EA;
}
.demo-pill-sal {
    background: #ECFDF5;
    color: #03855c;
}
.demo-pill-type {
    background: #EFF6FF;
    color: #2563EB;
}

/* Card Bottom */
.demo-job-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 10px;
    border-top: 1px solid #F1F5F9;
}
.demo-job-time {
    font-size: 11.5px;
    color: #94A3B8;
    font-weight: 500;
}
.demo-btn-apply {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #2563EB;
    color: #FFFFFF !important;
    font-size: 12.5px;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 8px;
    text-decoration: none !important;
    transition: all 0.2s ease;
    min-height: 36px;
}
.demo-btn-apply:hover {
    background: #1D4ED8;
    box-shadow: 0 4px 10px rgba(37,99,235,0.2);
}

@media (max-width: 767px) {
    .demo-fj-section {
        padding: 30px 0 20px 0 !important;
    }
    .demo-fj-title {
        font-size: 21px !important;
    }
    .demo-fj-subtitle {
        font-size: 13px !important;
    }
    .demo-job-card {
        padding: 14px 12px !important;
        margin-bottom: 12px !important;
        border-radius: 12px !important;
    }
    .demo-job-logo {
        width: 40px !important;
        height: 40px !important;
        min-width: 40px !important;
        font-size: 15px !important;
    }
    .demo-job-title-txt {
        font-size: 14.5px !important;
    }
    .demo-job-company {
        font-size: 12px !important;
    }
    .demo-meta-pill {
        font-size: 11px !important;
        padding: 2px 7px !important;
    }
    .demo-btn-apply {
        padding: 5px 12px !important;
        font-size: 12px !important;
        min-height: 34px !important;
    }
    .demo-fj-viewall-btn {
        width: 100% !important;
        justify-content: center !important;
        padding: 9px 18px !important;
    }
}
</style>

<div class="section demo-fj-section">
    <div class="container">
        {{-- Section 4: Featured Jobs Header --}}
        <div class="row align-items-center mb-3">
            <div class="col-8 col-md-8">
                <div class="demo-fj-badge">
                    <i class="fa fa-bolt"></i> {{ __('Verified Openings') }}
                </div>
                <h2 class="demo-fj-title">
                    {{ __('Featured') }} <span style="color: #2563EB;">{{ __('Jobs') }}</span>
                </h2>
                <p class="demo-fj-subtitle">
                    {{ __('Active openings from verified employers') }}
                </p>
            </div>
            <div class="col-4 col-md-4 text-right d-none d-md-block">
                <a href="{{ route('job.list', ['is_featured' => 1]) }}" class="demo-fj-viewall-btn">
                    {{ __('View All Openings') }} <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        {{-- Featured Jobs Grid --}}
        <div class="row">
            @if(count($validFeaturedJobs) > 0)
                @foreach($validFeaturedJobs->take(6) as $fj)
                @php
                    $comp = $fj->getCompany();
                    $initial = strtoupper(substr($comp->name ?? 'J', 0, 1));
                    $cityName = $fj->getCity('city');
                    $jobType = $fj->getJobType('job_type');
                    $exp = $fj->getJobExperience('job_experience');
                @endphp
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="demo-job-card">
                        <div>
                            <div class="demo-job-top">
                                <div class="demo-job-logo">
                                    @if($comp && $comp->logo && file_exists(public_path('company_logos/'.$comp->logo)))
                                        <img src="{{ asset('company_logos/'.$comp->logo) }}" alt="{{ $comp->name }}">
                                    @else
                                        <span>{{ $initial }}</span>
                                    @endif
                                </div>
                                <div class="demo-job-heading">
                                    <h3 class="demo-job-title-txt">
                                        <a href="{{ route('job.detail', [$fj->slug]) }}" title="{{ $fj->title }}">
                                            {{ $fj->title }}
                                        </a>
                                    </h3>
                                    <p class="demo-job-company">
                                        @if($comp)
                                        <a href="{{ route('company.detail', $comp->slug) }}">{{ $comp->name }}</a>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="demo-job-meta">
                                @if(!empty($cityName))
                                    <span class="demo-meta-pill demo-pill-loc">
                                        <i class="fa fa-map-marker"></i> {{ $cityName }}
                                    </span>
                                @endif

                                @if(!empty($jobType))
                                    <span class="demo-meta-pill demo-pill-type">
                                        <i class="fa fa-briefcase"></i> {{ $jobType }}
                                    </span>
                                @endif

                                @if(!empty($exp))
                                    <span class="demo-meta-pill demo-pill-exp">
                                        <i class="fa fa-clock-o"></i> {{ $exp }}
                                    </span>
                                @endif

                                @if(!empty($fj->salary_from) || !empty($fj->salary_to))
                                    <span class="demo-meta-pill demo-pill-sal">
                                        <i class="fa fa-inr"></i> 
                                        {{ $fj->salary_from ? number_format($fj->salary_from) : '' }}
                                        @if($fj->salary_from && $fj->salary_to) - @endif
                                        {{ $fj->salary_to ? number_format($fj->salary_to) : '' }}
                                    </span>
                                @else
                                    <span class="demo-meta-pill demo-pill-sal">
                                        <i class="fa fa-inr"></i> {{ __('Best in Industry') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="demo-job-footer">
                            <span class="demo-job-time">
                                <i class="fa fa-calendar-o"></i> {{ $fj->created_at ? $fj->created_at->diffForHumans() : __('Recently posted') }}
                            </span>
                            <a href="{{ route('job.detail', [$fj->slug]) }}" class="demo-btn-apply">
                                {{ __('Apply Now') }} <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-12 text-center py-4">
                    <p style="color: #64748B;">{{ __('No featured jobs available at the moment.') }}</p>
                </div>
            @endif
        </div>

        <div class="text-center mt-2 d-block d-md-none">
            <a href="{{ route('job.list', ['is_featured' => 1]) }}" class="demo-fj-viewall-btn">
                {{ __('View All Openings') }} <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
