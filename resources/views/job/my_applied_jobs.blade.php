@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->

<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title'=>__('My Applications')])
<!-- Inner Page Title end -->

<div class="listpgWraper" style="background: #F8FAFC; padding: 40px 0; min-height: 80vh;">
    <div class="container">
        <div class="row">
            @include('includes.user_dashboard_menu')

            <div class="col-lg-9 col-md-8">
                <!-- Page Title & Navigation Tabs -->
                <div class="applications-container" style="background: transparent;">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 style="font-size: 26px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.5px;">
                            {{__('My Applications')}}
                        </h2>
                    </div>

                    <!-- Modern Tabs (Matching Reference Design) -->
                    <div class="application-tabs-nav" style="display: flex; gap: 12px; border-bottom: 2px solid #E2E8F0; margin-bottom: 20px; padding-bottom: 2px;">
                        <a href="{{ route('my.job.applications', ['tab' => 'applied']) }}" 
                           class="app-tab-btn {{ ($activeTab != 'invites') ? 'active' : '' }}" 
                           style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; font-size: 15px; font-weight: 700; text-decoration: none; border-bottom: 3px solid {{ ($activeTab != 'invites') ? '#03855c' : 'transparent' }}; color: {{ ($activeTab != 'invites') ? '#03855c' : '#64748B' }}; margin-bottom: -2px; transition: all 0.2s ease;">
                            <span>{{__('Applied Jobs')}}</span>
                            <span class="badge-tab-count" style="background: {{ ($activeTab != 'invites') ? '#03855c' : '#E2E8F0' }}; color: {{ ($activeTab != 'invites') ? '#FFFFFF' : '#475569' }}; font-size: 12px; font-weight: 700; padding: 2px 8px; border-radius: 12px;">
                                {{ $appliedCount }}
                            </span>
                        </a>

                        <a href="{{ route('my.job.applications', ['tab' => 'invites']) }}" 
                           class="app-tab-btn {{ ($activeTab == 'invites') ? 'active' : '' }}" 
                           style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; font-size: 15px; font-weight: 700; text-decoration: none; border-bottom: 3px solid {{ ($activeTab == 'invites') ? '#03855c' : 'transparent' }}; color: {{ ($activeTab == 'invites') ? '#03855c' : '#64748B' }}; margin-bottom: -2px; transition: all 0.2s ease;">
                            <span>{{__('Interview Invites')}}</span>
                            <span class="badge-tab-count" style="background: {{ ($activeTab == 'invites') ? '#03855c' : '#E2E8F0' }}; color: {{ ($activeTab == 'invites') ? '#FFFFFF' : '#475569' }}; font-size: 12px; font-weight: 700; padding: 2px 8px; border-radius: 12px;">
                                {{ $interviewInvitesCount }}
                            </span>
                        </a>
                    </div>

                    <!-- Subtitle Counter -->
                    <div class="applied-count-subtext" style="font-size: 14px; font-weight: 600; color: #475569; margin-bottom: 20px; display: flex; align-items: center; gap: 6px;">
                        <span>{{ ($activeTab == 'invites') ? $interviewInvitesCount . ' ' . __('interview invites') : $appliedCount . ' ' . __('applied jobs') }}</span>
                        <i class="fa fa-info-circle text-muted" style="font-size: 13px;" title="{{__('Applications submitted from your profile')}}"></i>
                    </div>

                    <!-- Application Cards List -->
                    <div class="application-cards-list" style="display: flex; flex-direction: column; gap: 20px;">
                        @if(isset($applications) && count($applications))
                            @foreach($applications as $application)
                                @php
                                    $job = $application->getJob();
                                    $company = $job ? $job->getCompany() : null;
                                @endphp
                                @if($job && $company)
                                    @php
                                        $badgeInfo = $application->getStatusBadgeInfo();
                                        $isShortlisted = ($application->getApplicationStatus() === 'shortlisted' || in_array($application->getApplicationStatus(), ['interview_scheduled', 'interview_completed', 'selected']));
                                        $jobShift = $job->getJobShift('job_shift');
                                        $city = $job->getCity('city');
                                        $hrName = $company->getHrName();
                                        $hrPhone = $company->getCleanPhone();
                                        $hrWhatsapp = $company->getCleanWhatsapp();
                                        $canCall = $company->canCallHr();
                                        $canWhatsapp = $company->canWhatsappHr();
                                        
                                        // WhatsApp Pre-filled message
                                        $siteName = isset($siteSetting) ? $siteSetting->site_name : config('app.name');
                                        $candidateName = Auth::user()->getName();
                                        $waMessageText = "Hello " . $hrName . ",\n\nI have applied for the " . $job->title . " position at " . $company->name . " through " . $siteName . ".\n\nI would like to introduce myself and know the next steps regarding my application.\n\nThank you.\n- " . $candidateName;
                                        $waUrl = "https://wa.me/" . $hrWhatsapp . "?text=" . urlencode($waMessageText);
                                    @endphp

                                    <div class="application-card" id="app_card_{{ $application->id }}" 
                                         style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); transition: all 0.2s ease;">
                                        
                                        <!-- Card Top Header -->
                                        <div class="app-card-header" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; margin-bottom: 20px; flex-wrap: wrap;">
                                            
                                            <!-- Left: Logo & Job Info -->
                                            <div class="app-job-identity" style="display: flex; gap: 16px; align-items: flex-start;">
                                                <!-- Company Logo -->
                                                <div class="app-company-logo" style="width: 62px; height: 62px; min-width: 62px; border-radius: 12px; border: 1.5px solid #E2E8F0; overflow: hidden; background: #FFFFFF; display: flex; align-items: center; justify-content: center; padding: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                                                    @if(!empty($company->logo))
                                                        {{ ImgUploader::print_image("company_logos/$company->logo", 60, 60) }}
                                                    @else
                                                        <div style="width: 100%; height: 100%; background: #EEF2F6; color: #03855c; font-weight: 800; font-size: 20px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                                            {{ strtoupper(substr($company->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Job & Company Titles -->
                                                <div class="app-job-details">
                                                    <h3 style="font-size: 18px; font-weight: 800; color: #0F172A; margin: 0 0 4px 0; line-height: 1.3;">
                                                        <a href="{{ route('job.detail', [$job->slug]) }}" style="color: #0F172A; text-decoration: none;" class="hover-underline">
                                                            {{ $job->title }}
                                                        </a>
                                                    </h3>

                                                    <div class="app-company-name" style="display: flex; align-items: center; gap: 6px; font-size: 14.5px; font-weight: 600; color: #475569; margin-bottom: 8px;">
                                                        <a href="{{ route('company.detail', $company->slug) }}" style="color: #475569; text-decoration: none;">
                                                            {{ $company->name }}
                                                        </a>
                                                        @if((bool)$company->verified)
                                                            <span title="{{__('Verified Employer')}}" style="display: inline-flex; align-items: center; justify-content: center; background: #03855c; color: #FFFFFF; width: 15px; height: 15px; border-radius: 50%; font-size: 9px; font-weight: bold;">
                                                                &#10003;
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <!-- Shift & Location Meta -->
                                                    <div class="app-job-meta" style="display: flex; align-items: center; gap: 8px; font-size: 13.5px; color: #64748B; font-weight: 500;">
                                                        @if(!empty($jobShift))
                                                            <span style="display: inline-flex; align-items: center; gap: 5px; color: #2563EB; background: #EFF6FF; border: 1px solid #DBEAFE; padding: 2px 10px; border-radius: 6px; font-size: 12.5px; font-weight: 600;">
                                                                <i class="fa fa-briefcase" style="font-size: 11px;"></i> {{ $jobShift }}
                                                            </span>
                                                        @endif

                                                        @if(!empty($jobShift) && !empty($city))
                                                            <span style="color: #94A3B8;">-</span>
                                                        @endif

                                                        @if(!empty($city))
                                                            <span style="display: inline-flex; align-items: center; gap: 4px; color: #475569;">
                                                                <i class="fa fa-map-marker text-danger" style="font-size: 13px;"></i> {{ $city }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Right: Status Badge & Application Date -->
                                            <div class="app-status-date-box" style="text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 6px;">
                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                    <span class="app-status-badge {{ $badgeInfo['class'] }}" 
                                                          style="display: inline-block; padding: 4px 14px; font-size: 13px; font-weight: 700; border-radius: 9999px; background: {{ $badgeInfo['bg'] }}; color: {{ $badgeInfo['color'] }}; border: 1px solid {{ $badgeInfo['border'] }};">
                                                        {{ $badgeInfo['label'] }}
                                                    </span>
                                                    <a href="{{ route('job.detail', [$job->slug]) }}" style="color: #94A3B8; font-size: 18px; text-decoration: none;" title="{{__('View Job Details')}}">
                                                        <i class="fa fa-angle-right" style="font-weight: 700;"></i>
                                                    </a>
                                                </div>

                                                <div class="app-applied-time" style="font-size: 13px; color: #64748B; font-weight: 500;">
                                                    {{__('Applied on')}} {{ $application->created_at->format('d M Y') }} 
                                                    <span style="color: #94A3B8;">&bull;</span> 
                                                    <span style="color: #475569;">{{ $application->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>

                                        </div>

                                        <!-- Card Middle: Application Status Timeline & HR Contact Box (Mint Background matching screenshot) -->
                                        <div class="app-contact-status-box" 
                                             style="background: #F0FDF4; border: 1.5px solid #DCFCE7; border-radius: 14px; padding: 18px 22px; display: flex; justify-content: space-between; align-items: center; gap: 20px; margin-bottom: 16px; flex-wrap: wrap;">
                                            
                                            <!-- Section A: Application Status Timeline -->
                                            <div class="app-timeline-section" style="min-width: 230px;">
                                                <div class="timeline-step" style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
                                                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 50%; background: #03855c; color: #FFFFFF; font-size: 11px; font-weight: bold; flex-shrink: 0;">
                                                        &#10003;
                                                    </span>
                                                    <span style="font-size: 14px; font-weight: 700; color: #0F172A;">
                                                        {{__('Job Applied')}}
                                                    </span>
                                                </div>

                                                <div class="timeline-step" style="display: flex; align-items: center; gap: 10px; position: relative;">
                                                    <!-- Connecting vertical line -->
                                                    <div style="position: absolute; left: 10px; top: -16px; width: 2px; height: 16px; background: {{ $isShortlisted ? '#03855c' : '#CBD5E1' }};"></div>
                                                    
                                                    @if($isShortlisted)
                                                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 50%; background: #03855c; color: #FFFFFF; font-size: 11px; font-weight: bold; flex-shrink: 0; box-shadow: 0 0 0 3px rgba(3,133,92,0.2);">
                                                            &#10003;
                                                        </span>
                                                        <span style="font-size: 14px; font-weight: 700; color: #065F46;">
                                                            {{__('Application shortlisted by HR')}}
                                                        </span>
                                                    @else
                                                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 50%; background: #FFFFFF; border: 2.5px solid #03855c; color: #03855c; flex-shrink: 0;">
                                                            <span style="width: 6px; height: 6px; background: #03855c; border-radius: 50%;"></span>
                                                        </span>
                                                        <span style="font-size: 14px; font-weight: 600; color: #475569;">
                                                            {{__('Application shortlisted by HR')}}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Section B: HR / Recruiter Identity -->
                                            <div class="app-hr-identity-section" style="display: flex; align-items: center; gap: 14px; flex: 1; min-width: 220px;">
                                                <div class="hr-avatar-circle" style="width: 48px; height: 48px; min-width: 48px; border-radius: 50%; background: #581C87; color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-size: 20px; box-shadow: 0 2px 6px rgba(88,28,135,0.2);">
                                                    @if($company->getHrAvatar())
                                                        <img src="{{ $company->getHrAvatar() }}" alt="{{ $hrName }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                                    @else
                                                        <i class="fa fa-user" style="font-size: 20px; color: #FFFFFF;"></i>
                                                    @endif
                                                </div>

                                                <div class="hr-text-info">
                                                    <h4 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0 0 2px 0;">
                                                        {{ $hrName }}
                                                    </h4>
                                                    <p style="font-size: 13px; color: #475569; margin: 0; line-height: 1.3;">
                                                        {{__('Introduce yourself and know the next steps')}}
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Section C: Action Buttons (Call HR & WhatsApp HR) -->
                                            <div class="app-hr-actions-section">
                                                @if($canCall)
                                                    <a href="tel:{{ $hrPhone }}" 
                                                       class="btn-call-hr" 
                                                       onclick="trackHrContactActivity({{ $application->id }}, 'phone')">
                                                        <i class="fa fa-phone"></i>
                                                        <span>{{__('Call HR')}}</span>
                                                    </a>
                                                @endif

                                                @if($canWhatsapp)
                                                    <a href="{{ $waUrl }}" 
                                                       target="_blank" 
                                                       rel="noopener noreferrer"
                                                       class="btn-whatsapp-hr" 
                                                       onclick="trackHrContactActivity({{ $application->id }}, 'whatsapp')">
                                                        <i class="fa fa-whatsapp"></i>
                                                        <span>{{__('WhatsApp HR')}}</span>
                                                    </a>
                                                @endif

                                                @if(!$canCall && !$canWhatsapp)
                                                    <span style="font-size: 12.5px; color: #64748B; font-style: italic;">
                                                        {{__('Direct HR contact is currently offline.')}}
                                                    </span>
                                                @endif
                                            </div>

                                        </div>

                                        <!-- Card Footer: Job ID & Report Option -->
                                        <div class="app-card-footer" style="display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: #64748B; padding-top: 4px;">
                                            <div class="app-job-id" style="display: flex; align-items: center; gap: 6px; font-weight: 500;">
                                                <i class="fa fa-info-circle text-muted"></i>
                                                <span>{{__('Job ID:')}} <strong style="color: #334155;">{{ $application->getFormattedJobId() }}</strong></span>
                                            </div>

                                            <div class="app-report-btn">
                                                <button type="button" 
                                                        class="btn-report-job" 
                                                        onclick="openReportJobModal({{ $job->id }}, '{{ addslashes($job->title) }}', '{{ addslashes($company->name) }}')"
                                                        style="border: none; background: transparent; color: #DC2626; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 5px; cursor: pointer; padding: 0;">
                                                    <i class="fa fa-exclamation-circle"></i>
                                                    <span>{{__('Report')}}</span>
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                @endif
                            @endforeach

                            <!-- Pagination -->
                            <div class="pagiWrap text-center" style="margin-top: 20px;">
                                {{ $applications->appends(['tab' => $activeTab])->links() }}
                            </div>
                        @else
                            <div class="empty-applications-box" style="background: #FFFFFF; border: 1.5px dashed #CBD5E1; border-radius: 16px; padding: 48px 24px; text-align: center;">
                                <div style="width: 64px; height: 64px; background: #F1F5F9; border-radius: 50%; color: #94A3B8; font-size: 26px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                                    <i class="fa fa-briefcase"></i>
                                </div>
                                <h3 style="font-size: 18px; font-weight: 800; color: #0F172A; margin: 0 0 6px 0;">
                                    {{ ($activeTab == 'invites') ? __('No Interview Invites Yet') : __('No Applications Found') }}
                                </h3>
                                <p style="font-size: 14px; color: #64748B; margin-bottom: 20px;">
                                    {{ ($activeTab == 'invites') ? __('When recruiters shortlist you or send an invite, it will appear here.') : __('Explore verified jobs matching your skills and apply in one click!') }}
                                </p>
                                <a href="{{ route('job.list') }}" class="btn" style="background: #03855c; color: #FFFFFF; font-weight: 700; border-radius: 10px; padding: 10px 24px;">
                                    {{__('Browse Open Jobs')}}
                                </a>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Professional Inline Report Job Modal -->
<div class="modal fade" id="report_job_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.15); overflow: hidden;">
            <div class="modal-header" style="background: #FFF1F2; border-bottom: 1px solid #FECDD3; padding: 18px 24px;">
                <h4 class="modal-title" style="font-size: 17px; font-weight: 800; color: #9F1239; margin: 0; display: flex; align-items: center; gap: 8px;">
                    <i class="fa fa-exclamation-triangle text-danger"></i> {{__('Report This Job')}}
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size: 24px; color: #9F1239; opacity: 0.8; cursor: pointer; border: none; background: transparent;">&times;</button>
            </div>
            <div class="modal-body" style="padding: 24px;">
                <p style="font-size: 13.5px; color: #475569; margin-bottom: 16px;">
                    {{__('Help us keep the job portal safe and verified. Why are you reporting')}} <strong id="report_job_name"></strong> {{__('at')}} <strong id="report_company_name"></strong>?
                </p>

                <input type="hidden" id="report_modal_job_id" value="">

                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="font-size: 13px; font-weight: 700; color: #1E293B; margin-bottom: 6px; display: block;">
                        {{__('Select Reason')}} <span class="text-danger">*</span>
                    </label>
                    <select id="report_reason" class="form-control" style="height: 44px; border: 1.5px solid #CBD5E1; border-radius: 8px; font-size: 14px; font-weight: 600; color: #0F172A;">
                        <option value="Fake Job">{{__('Fake Job')}}</option>
                        <option value="Incorrect Information">{{__('Incorrect Information')}}</option>
                        <option value="Employer Misleading">{{__('Employer Misleading')}}</option>
                        <option value="Scam / Fraud">{{__('Scam / Fraud')}}</option>
                        <option value="Inappropriate Content">{{__('Inappropriate Content')}}</option>
                        <option value="Other">{{__('Other')}}</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 10px;">
                    <label style="font-size: 13px; font-weight: 700; color: #1E293B; margin-bottom: 6px; display: block;">
                        {{__('Additional Details (Optional)')}}
                    </label>
                    <textarea id="report_details" class="form-control" rows="3" placeholder="{{__('Describe the issue in detail...')}}" style="border: 1.5px solid #CBD5E1; border-radius: 8px; font-size: 13.5px;"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 14px 24px; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px; font-weight: 600; padding: 8px 18px;">
                    {{__('Cancel')}}
                </button>
                <button type="button" class="btn btn-danger" onclick="submitJobReport()" style="border-radius: 8px; font-weight: 700; background: #DC2626; border: none; padding: 8px 20px;">
                    <i class="fa fa-flag"></i> {{__('Submit Report')}}
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.application-card:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,0.06) !important;
    border-color: #CBD5E1 !important;
}

/* Action Buttons (Call HR & WhatsApp HR) */
.app-hr-actions-section {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-call-hr {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    background: #03855c;
    color: #FFFFFF !important;
    border: 1.5px solid #03855c;
    border-radius: 10px;
    height: 42px;
    padding: 0 18px;
    font-size: 13.5px;
    font-weight: 700;
    text-decoration: none !important;
    box-shadow: 0 2px 6px rgba(3,133,92,0.2);
    transition: all 0.15s ease;
    white-space: nowrap;
    box-sizing: border-box;
}

.btn-call-hr i {
    font-size: 14px;
    line-height: 1;
}

.btn-call-hr:hover {
    background: #047857 !important;
    border-color: #047857 !important;
    color: #FFFFFF !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(3,133,92,0.35) !important;
}

.btn-whatsapp-hr {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    background: #FFFFFF;
    color: #065F46 !important;
    border: 1.5px solid #03855c;
    border-radius: 10px;
    height: 42px;
    padding: 0 18px;
    font-size: 13.5px;
    font-weight: 700;
    text-decoration: none !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    transition: all 0.15s ease;
    white-space: nowrap;
    box-sizing: border-box;
}

.btn-whatsapp-hr i {
    font-size: 17px;
    color: #25D366 !important;
    line-height: 1;
}

.btn-whatsapp-hr:hover {
    background: #ECFDF5 !important;
    border-color: #047857 !important;
    color: #047857 !important;
    transform: translateY(-1px);
}

.hover-underline:hover {
    text-decoration: underline !important;
    color: #03855c !important;
}

@media (max-width: 991px) {
    .app-contact-status-box {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 16px !important;
    }
    .app-hr-actions-section {
        width: 100%;
        justify-content: flex-start;
    }
}

@media (max-width: 576px) {
    .app-card-header {
        flex-direction: column;
        align-items: flex-start !important;
    }
    .app-status-date-box {
        align-items: flex-start !important;
        text-align: left !important;
    }
    .app-hr-actions-section {
        display: flex !important;
        flex-direction: row !important;
        gap: 8px !important;
        width: 100% !important;
        flex-wrap: nowrap !important;
    }
    .btn-call-hr, .btn-whatsapp-hr {
        flex: 1 1 50% !important;
        width: 50% !important;
        min-width: 0 !important;
        padding: 0 8px !important;
        font-size: 13px !important;
        height: 42px !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }
}
</style>

@include('includes.footer')
@endsection

@push('scripts')
<script type="text/javascript">
    // Contact activity tracking
    function trackHrContactActivity(applicationId, contactType) {
        $.ajax({
            type: "POST",
            url: "{{ route('track.hr.contact') }}",
            data: {
                application_id: applicationId,
                contact_type: contactType,
                _token: "{{ csrf_token() }}"
            },
            dataType: "json"
        });
    }

    // Open Report Modal
    function openReportJobModal(jobId, jobTitle, companyName) {
        $('#report_modal_job_id').val(jobId);
        $('#report_job_name').text(jobTitle);
        $('#report_company_name').text(companyName);
        $('#report_details').val('');
        $('#report_job_modal').modal('show');
    }

    // Submit Job Report via AJAX
    function submitJobReport() {
        var jobId = $('#report_modal_job_id').val();
        var reason = $('#report_reason').val();
        var details = $('#report_details').val();

        if (!jobId) return;

        $.ajax({
            type: "POST",
            url: "{{ route('report.job.abuse.ajax') }}",
            data: {
                job_id: jobId,
                reason: reason,
                details: details,
                _token: "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(res) {
                $('#report_job_modal').modal('hide');
                alert(res.message || "{{__('Thank you. Your report has been submitted.')}}");
            },
            error: function() {
                alert("{{__('Could not submit report. Please try again.')}}");
            }
        });
    }
</script>
@include('includes.immediate_available_btn')
@endpush