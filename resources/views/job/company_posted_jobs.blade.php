@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end --> 
<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title'=>__('Company Posted Jobs')])
<!-- Inner Page Title end -->

<style>
/* ============================================================
   PREMIUM COMPANY POSTED JOBS STYLES
   ============================================================ */
.posted-jobs-container {
    padding: 35px 0 60px 0;
    background: #F8FAFC;
    min-height: 85vh;
}

.company-jobs-card {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E2E8F0;
    box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04);
    padding: 28px;
    margin-bottom: 24px;
}

.jobs-header-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 26px;
    padding-bottom: 20px;
    border-bottom: 1.5px solid #F1F5F9;
}

.jobs-header-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.jobs-header-title h2 {
    font-size: 22px;
    font-weight: 800;
    color: #0F172A;
    margin: 0;
    letter-spacing: -0.5px;
}

.jobs-count-pill {
    background: #EEF2FF;
    color: #4F46E5;
    font-size: 13px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 30px;
    border: 1px solid #E0E7FF;
}

.btn-post-job-action {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #2563EB;
    color: #FFFFFF !important;
    font-size: 14px;
    font-weight: 700;
    padding: 10px 22px;
    border-radius: 12px;
    text-decoration: none !important;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
    transition: all 0.2s ease;
}

.btn-post-job-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.35);
    color: #FFFFFF;
}

/* Individual Job Item Card */
.job-item-card {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 20px;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.job-item-card:hover {
    border-color: #CBD5E1;
    box-shadow: 0 10px 30px -4px rgba(15, 23, 42, 0.08);
    transform: translateY(-2px);
}

.job-main-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
}

.job-brand-details {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    flex: 1;
    min-width: 280px;
}

.job-logo-wrapper {
    width: 64px;
    height: 64px;
    min-width: 64px;
    border-radius: 14px;
    border: 1.5px solid #F1F5F9;
    background: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 6px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    overflow: hidden;
}

.job-logo-wrapper img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.job-title-group h3 {
    margin: 0 0 6px 0;
    font-size: 18px;
    font-weight: 700;
    line-height: 1.3;
}

.job-title-group h3 a {
    color: #0F172A;
    text-decoration: none;
    transition: color 0.2s;
}

.job-title-group h3 a:hover {
    color: #2563EB;
}

.company-subname {
    font-size: 14px;
    font-weight: 600;
    color: #64748B;
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 10px;
}

.company-subname a {
    color: #64748B;
    text-decoration: none;
}

.company-subname a:hover {
    color: #0F172A;
}

/* Badges / Meta Tags */
.job-meta-badges {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 7px;
    margin-bottom: 12px;
}

.meta-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 6px;
}

.meta-chip-shift {
    background: #F0FDF4;
    color: #15803D;
    border: 1px solid #DCFCE7;
}

.meta-chip-loc {
    background: #F8FAFC;
    color: #475569;
    border: 1px solid #E2E8F0;
}

.meta-chip-salary {
    background: #F8FAFC;
    color: #0F172A;
    border: 1px solid #E2E8F0;
    font-weight: 700;
}

.meta-chip-date {
    background: #F1F5F9;
    color: #64748B;
    border: 1px solid #E2E8F0;
}

.status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    font-weight: 700;
    color: #03855c;
    background: #ECFDF5;
    padding: 3px 9px;
    border-radius: 20px;
    border: 1px solid #A7F3D0;
}

.pulse-dot {
    width: 6px;
    height: 6px;
    background: #10B981;
    border-radius: 50%;
    box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
    animation: pulseDot 1.8s infinite;
}

@keyframes pulseDot {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 5px rgba(16, 185, 129, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

/* Job Description preview */
.job-snippet {
    font-size: 13.5px;
    color: #64748B;
    line-height: 1.6;
    margin: 0 0 16px 0;
    padding-top: 10px;
    border-top: 1px dashed #F1F5F9;
}

/* Action Buttons Bar */
.job-actions-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    padding-top: 14px;
    border-top: 1px solid #F1F5F9;
}

.candidate-actions-group {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
}

.btn-candidates-all {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: #2563EB;
    color: #FFFFFF !important;
    font-size: 13px;
    font-weight: 700;
    padding: 8px 16px;
    border-radius: 10px;
    text-decoration: none !important;
    transition: all 0.15s ease;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.22);
}

.btn-candidates-all:hover {
    background: #1D4ED8;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
}

.btn-candidates-shortlisted {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: #ECFDF5;
    color: #047857 !important;
    font-size: 13px;
    font-weight: 700;
    padding: 8px 15px;
    border-radius: 10px;
    border: 1px solid #A7F3D0;
    text-decoration: none !important;
    transition: all 0.15s ease;
}

.btn-candidates-shortlisted:hover {
    background: #D1FAE5;
    border-color: #6EE7B7;
    transform: translateY(-1px);
}

.count-badge-sub {
    background: rgba(255, 255, 255, 0.28);
    color: #FFFFFF;
    font-size: 11px;
    font-weight: 800;
    padding: 1px 7px;
    border-radius: 20px;
}

.count-badge-amber {
    background: #10B981;
    color: #FFFFFF;
    font-size: 11px;
    font-weight: 800;
    padding: 1px 7px;
    border-radius: 20px;
}

.job-manage-group {
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-job-tool {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 600;
    padding: 7px 14px;
    border-radius: 9px;
    text-decoration: none !important;
    transition: all 0.15s ease;
    cursor: pointer;
    border: 1.5px solid transparent;
}

.btn-job-edit {
    background: #FFFFFF;
    color: #334155 !important;
    border-color: #CBD5E1;
}

.btn-job-edit:hover {
    background: #F8FAFC;
    color: #2563EB !important;
    border-color: #93C5FD;
}

.btn-job-delete {
    background: #FFF1F2;
    color: #E11D48 !important;
    border-color: #FECDD3;
}

.btn-job-delete:hover {
    background: #FFE4E6;
    color: #BE123C !important;
}

.btn-job-preview {
    background: #FFFFFF;
    color: #64748B !important;
    border-color: #CBD5E1;
    padding: 7px 11px;
}

.btn-job-preview:hover {
    background: #F8FAFC;
    color: #0F172A !important;
    border-color: #94A3B8;
}

/* Mobile Responsive Rules */
@media (max-width: 767px) {
    .posted-jobs-container {
        padding: 18px 0 40px 0;
    }
    .company-jobs-card {
        padding: 16px 14px;
        border-radius: 14px;
    }
    .jobs-header-row {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
        margin-bottom: 18px;
        padding-bottom: 14px;
    }
    .btn-post-job-action {
        justify-content: center;
        width: 100%;
        padding: 10px 16px;
    }
    .job-item-card {
        padding: 16px 14px;
        border-radius: 14px;
        margin-bottom: 16px;
    }
    .job-brand-details {
        gap: 12px;
        min-width: 100%;
    }
    .job-logo-wrapper {
        width: 52px;
        height: 52px;
        min-width: 52px;
        border-radius: 10px;
    }
    .job-title-group h3 {
        font-size: 16px;
        margin-bottom: 4px;
    }
    .company-subname {
        font-size: 13px;
        margin-bottom: 8px;
    }
    .job-meta-badges {
        gap: 6px;
        margin-bottom: 10px;
    }
    .meta-chip {
        font-size: 11.5px;
        padding: 3px 8px;
        border-radius: 6px;
    }
    .job-snippet {
        font-size: 12.5px;
        margin-bottom: 14px;
        line-height: 1.5;
    }
    .job-actions-toolbar {
        flex-direction: column;
        gap: 10px;
        padding-top: 12px;
    }
    .candidate-actions-group {
        width: 100%;
        display: flex;
        gap: 8px;
    }
    .btn-candidates-all {
        flex: 1 1 50%;
        justify-content: center;
        font-size: 12.5px;
        padding: 8px 10px;
        white-space: nowrap;
    }
    .btn-candidates-shortlisted {
        flex: 1 1 50%;
        justify-content: center;
        font-size: 12.5px;
        padding: 8px 10px;
        white-space: nowrap;
    }
    .job-manage-group {
        width: 100%;
        display: flex;
        gap: 8px;
    }
    .btn-job-tool.btn-job-edit {
        flex: 1 1 auto;
        justify-content: center;
        font-size: 12px;
        padding: 7px 10px;
    }
    .btn-job-tool.btn-job-delete {
        flex: 1 1 auto;
        justify-content: center;
        font-size: 12px;
        padding: 7px 10px;
    }
    .btn-job-tool.btn-job-preview {
        flex: 0 0 38px;
        justify-content: center;
        padding: 7px 0;
    }
}

/* Empty state */
.empty-jobs-state {
    text-align: center;
    padding: 60px 20px;
    background: #FFFFFF;
    border-radius: 16px;
    border: 2px dashed #E2E8F0;
}

.empty-jobs-icon {
    width: 72px;
    height: 72px;
    background: #EEF2FF;
    color: #4F46E5;
    font-size: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin-bottom: 18px;
}

.empty-jobs-state h3 {
    font-size: 20px;
    font-weight: 700;
    color: #0F172A;
    margin-bottom: 8px;
}

.empty-jobs-state p {
    font-size: 14.5px;
    color: #64748B;
    max-width: 420px;
    margin: 0 auto 24px auto;
}
</style>

<div class="posted-jobs-container">
    <div class="container">
        <div class="row">
            @include('includes.company_dashboard_menu')

            <div class="col-lg-9 col-md-8 col-sm-12"> 
                <div class="company-jobs-card">
                    <!-- Top Toolbar -->
                    <div class="jobs-header-row">
                        <div class="jobs-header-title">
                            <h2>{{__('Company Posted Jobs')}}</h2>
                            <span class="jobs-count-pill">{{ isset($jobs) ? $jobs->total() : 0 }} Jobs</span>
                        </div>
                        <a href="{{ route('post.job') }}" class="btn-post-job-action">
                            <i class="fa fa-plus-circle"></i> {{ __('Post New Job') }}
                        </a>
                    </div>

                    <!-- Job Listing -->
                    @if(isset($jobs) && count($jobs))
                        @foreach($jobs as $job)
                        @php 
                            $company = $job->getCompany(); 
                            $appCount = \App\JobApply::where('job_id', $job->id)->count();
                            $shortCount = \App\FavouriteApplicant::where('job_id', $job->id)->where('company_id', Auth::guard('company')->user()->id)->count();
                        @endphp
                        @if(null !== $company)
                        <div class="job-item-card" id="job_li_{{$job->id}}">
                            <!-- Job Info Header -->
                            <div class="job-main-header">
                                <div class="job-brand-details">
                                    <div class="job-logo-wrapper">
                                        {!! $company->printCompanyImage() !!}
                                    </div>
                                    <div class="job-title-group">
                                        <h3>
                                            <a href="{{route('job.detail', [$job->slug])}}" title="{{$job->title}}">{{$job->title}}</a>
                                        </h3>
                                        <div class="company-subname">
                                            <i class="fa fa-building-o"></i> 
                                            <a href="{{route('company.detail', $company->slug)}}">{{$company->name}}</a>
                                        </div>
                                        
                                        <!-- Metadata chips -->
                                        <div class="job-meta-badges">
                                            @if($job->getJobShift('job_shift'))
                                            <span class="meta-chip meta-chip-shift">
                                                <i class="fa fa-clock-o"></i> {{$job->getJobShift('job_shift')}}
                                            </span>
                                            @endif

                                            @if($job->getCity('city'))
                                            <span class="meta-chip meta-chip-loc">
                                                <i class="fa fa-map-marker text-danger"></i> {{$job->getCity('city')}}
                                            </span>
                                            @endif

                                            @if(!empty($job->salary_from) || !empty($job->salary_to))
                                            @php
                                                $jobCurr = !empty($job->salary_currency) ? $job->salary_currency : 'INR';
                                                $curSym = ($jobCurr == 'INR' || $jobCurr == 'Rs') ? '₹' : ($jobCurr == 'USD' ? '$' : $jobCurr . ' ');
                                                $sFromClean = preg_replace('/[^0-9.]/', '', $job->salary_from);
                                                $sFromNum = is_numeric($sFromClean) && $sFromClean > 0 ? number_format((float)$sFromClean) : $job->salary_from;
                                                $sToClean = preg_replace('/[^0-9.]/', '', $job->salary_to);
                                                $sToNum = is_numeric($sToClean) && $sToClean > 0 ? number_format((float)$sToClean) : $job->salary_to;
                                                $sPeriod = $job->getSalaryPeriod('salary_period') ? strtolower($job->getSalaryPeriod('salary_period')) : 'month';
                                                if ($sPeriod == 'monthly') $sPeriod = 'month';
                                                if ($sPeriod == 'yearly' || $sPeriod == 'annually') $sPeriod = 'year';
                                            @endphp
                                            <span class="meta-chip meta-chip-salary">
                                                <i class="fa fa-money text-success"></i> 
                                                @if(!empty($sFromNum) && !empty($sToNum))
                                                    {{ $curSym }}{{ $sFromNum }} - {{ $curSym }}{{ $sToNum }} / {{ $sPeriod }}
                                                @elseif(!empty($sFromNum))
                                                    {{ $curSym }}{{ $sFromNum }} / {{ $sPeriod }}
                                                @else
                                                    {{ $curSym }}{{ $sToNum }} / {{ $sPeriod }}
                                                @endif
                                            </span>
                                            @endif

                                            <span class="meta-chip meta-chip-date">
                                                <i class="fa fa-calendar"></i> Posted {{ $job->created_at ? $job->created_at->diffForHumans() : 'Recently' }}
                                            </span>

                                            <span class="status-indicator">
                                                <span class="pulse-dot"></span> Active
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Description Snippet -->
                            @if(!empty($job->description))
                            <p class="job-snippet">
                                {{\Illuminate\Support\Str::limit(strip_tags($job->description), 160, '...')}}
                            </p>
                            @endif

                            <!-- Bottom Toolbar with Candidates and Management Controls -->
                            <div class="job-actions-toolbar">
                                <div class="candidate-actions-group">
                                    <a href="{{route('list.applied.users', [$job->id])}}" class="btn-candidates-all" title="View all applicants for this role">
                                        <i class="fa fa-users"></i> {{__('Candidates')}}
                                        <span class="count-badge-sub">{{ $appCount }}</span>
                                    </a>
                                    <a href="{{route('list.favourite.applied.users', [$job->id])}}" class="btn-candidates-shortlisted" title="View shortlisted candidates">
                                        <i class="fa fa-star text-warning"></i> {{__('Shortlisted')}}
                                        <span class="count-badge-amber">{{ $shortCount }}</span>
                                    </a>
                                </div>

                                <div class="job-manage-group">
                                    <a href="{{route('edit.front.job', [$job->id])}}" class="btn-job-tool btn-job-edit" title="Edit this job">
                                        <i class="fa fa-pencil"></i> {{__('Edit')}}
                                    </a>
                                    <a href="javascript:;" onclick="deleteJob({{$job->id}});" class="btn-job-tool btn-job-delete" title="Delete job">
                                        <i class="fa fa-trash-o"></i> {{__('Delete')}}
                                    </a>
                                    <a href="{{route('job.detail', [$job->slug])}}" target="_blank" class="btn-job-tool btn-job-preview" title="View Public Job Listing">
                                        <i class="fa fa-external-link"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach

                        <!-- Pagination -->
                        <div class="pagiWrap mt-4">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="showreslt">
                                        {{__('Showing Pages')}} : {{ $jobs->firstItem() }} - {{ $jobs->lastItem() }} {{__('Total')}} {{ $jobs->total() }}
                                    </div>
                                </div>
                                <div class="col-md-7 text-right">
                                    {!! $jobs->appends(request()->query())->render() !!}
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Clean Empty State -->
                        <div class="empty-jobs-state">
                            <div class="empty-jobs-icon">
                                <i class="fa fa-briefcase"></i>
                            </div>
                            <h3>{{ __('No Jobs Posted Yet') }}</h3>
                            <p>{{ __('You have not posted any active job vacancies yet. Start reaching thousands of verified job seekers by creating a job post.') }}</p>
                            <a href="{{ route('post.job') }}" class="btn-post-job-action">
                                <i class="fa fa-plus-circle"></i> {{ __('Post Your First Job') }}
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
    function deleteJob(id) {
        if (confirm('Are you sure you want to delete this job? This action cannot be undone.')) {
            $.post("{{ route('delete.front.job') }}", {
                id: id,
                _token: '{{ csrf_token() }}'
            })
            .done(function (response) {
                if (response == 'ok') {
                    $('#job_li_' + id).slideUp(300, function() {
                        $(this).remove();
                    });
                } else {
                    alert('Delete failed! Please try again.');
                }
            })
            .fail(function(xhr) {
                alert('Error: ' + xhr.status + ' - ' + xhr.responseText);
            });
        }
    }
</script>
@endpush