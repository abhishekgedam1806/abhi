@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__($page_title)]) 
<!-- Inner Page Title end -->
<div class="listpgWraper candidate-profile-redesign" style="background: #F8FAFC; padding: 36px 0 60px; min-height: 85vh; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <div class="container" style="max-width: 1300px;">  
        @include('flash::message')  

        <div class="row">
            <!-- Left Main Column (Candidate Hero + About + Roles + Education + Experience + Portfolio) -->
            <div class="col-lg-8 col-md-7"> 
				
				<!-- 1. Candidate Hero Card (Clean Recruiter View) -->
                <div class="candidate-hero-card">
                    <div class="candidate-hero-top">
                        <!-- Candidate Avatar -->
                        <div class="candidate-avatar-wrap">
                            @if(!empty($user->image) && file_exists(public_path('user_images/' . $user->image)))
                                <img src="{{ asset('user_images/' . $user->image) }}" alt="{{ $user->getName() }}">
                            @else
                                <div class="candidate-avatar-placeholder">
                                    {{ strtoupper(substr($user->first_name ?: ($user->name ?: 'U'), 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <!-- Candidate Name & Highlights -->
                        <div class="candidate-info-wrap">
                            <div class="candidate-title-row">
                                <h1 class="candidate-name">
                                    {{ $user->getName() ?: 'Candidate' }}
                                </h1>
                                @if((bool)$user->is_immediate_available)
                                <span class="badge-available-now">
                                    <span class="pulse-dot"></span>
                                    {{__('Available Now')}}
                                </span>
                                @endif

                                <!-- Status Badge (if already Hired/Rejected) -->
                                @php
                                    $appStatus = (isset($job_application) && $job_application) ? strtolower($job_application->status ?: 'applied') : '';
                                @endphp
                                <span id="profile_status_badge">
                                    @if($appStatus == 'hired')
                                        <span class="status-chip-hired"><i class="fa fa-check-circle"></i> {{ __('Hired / Selected') }}</span>
                                    @elseif($appStatus == 'rejected')
                                        <span class="status-chip-rejected"><i class="fa fa-times-circle"></i> {{ __('Rejected') }}</span>
                                    @endif
                                </span>
                            </div>

                            <!-- Highlights Bar: Subtle metadata display (distinct from action buttons) -->
                            @php
                                $expStr = $user->getJobExperience('job_experience') ?: '1 Year Experience';
                                $careerStr = $user->getCareerLevel('career_level') ?: 'Experienced';
                                $locStr = $user->getLocation();

                                $expSalaryRaw = (isset($job_application) && $job_application && !empty($job_application->expected_salary)) ? $job_application->expected_salary : (!empty($user->expected_salary) ? $user->expected_salary : null);
                                $salaryCurr = (isset($job_application) && $job_application && !empty($job_application->salary_currency)) ? $job_application->salary_currency : (!empty($user->salary_currency) ? $user->salary_currency : 'INR');
                                $currencySym = ($salaryCurr == 'INR' || $salaryCurr == 'Rs' || empty($salaryCurr)) ? '₹' : ($salaryCurr == 'USD' ? '$' : $salaryCurr . ' ');
                                $salaryPeriod = isset($job) && $job && $job->getSalaryPeriod('salary_period') ? strtolower($job->getSalaryPeriod('salary_period')) : 'month';
                                if ($salaryPeriod == 'monthly') $salaryPeriod = 'month';
                                if ($salaryPeriod == 'yearly' || $salaryPeriod == 'annually') $salaryPeriod = 'year';
                                
                                $formattedExpectedSalary = null;
                                if (!empty($expSalaryRaw)) {
                                    $numClean = preg_replace('/[^0-9.]/', '', $expSalaryRaw);
                                    $salaryNum = is_numeric($numClean) && $numClean > 0 ? number_format((float)$numClean) : $expSalaryRaw;
                                    $formattedExpectedSalary = 'Expected: ' . $currencySym . $salaryNum . ' / ' . $salaryPeriod;
                                }
                            @endphp
                            <div class="candidate-highlights-bar">
                                @if(!empty($locStr))
                                <span class="highlight-chip">
                                    <i class="fa fa-map-marker text-danger"></i> {{ $locStr }}
                                </span>
                                @endif
                                <span class="highlight-chip">
                                    <i class="fa fa-briefcase text-primary"></i> {{ $expStr }}
                                </span>
                                <span class="highlight-chip">
                                    <i class="fa fa-line-chart text-muted"></i> {{ $careerStr }}
                                </span>
                                <span class="highlight-chip verified">
                                    <i class="fa fa-check-circle"></i> {{ __('Email Verified') }}
                                </span>
                                @if(!empty($formattedExpectedSalary))
                                <span class="highlight-chip salary" title="{{ __('Candidate Expected Salary') }}">
                                    <i class="fa fa-money text-success"></i> {{ $formattedExpectedSalary }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Clean Recruiter Action Buttons Toolbar -->
                    @php
                        $hasApplication = isset($job_application) && $job_application;
                        $isShortlisted = (isset($job) && isset($company) && Auth::guard('company')->check() && Auth::guard('company')->user()->isFavouriteApplicant($user->id, $job->id, $company->id)) || ($hasApplication && strtolower($job_application->status) === 'shortlisted');
                    @endphp
                    <div class="recruiter-action-toolbar">
                        @if($hasApplication)
                        <!-- 1. Hire / Select (Prominent Blue Button) -->
                        <button type="button" onclick="setApplicantStatus({{ $job_application->id }}, 'hired')" class="btn-recruiter-hire">
                            <i class="fa fa-check-circle"></i> <span>{{__('Hire / Select')}}</span>
                        </button>
                        @endif

                        <!-- 2. Send Message (Second Important Action) -->
                        <a href="javascript:;" onclick="send_message()" class="btn-recruiter-secondary btn-send-msg">
                            <i class="fa fa-envelope"></i> <span>{{__('Send Message')}}</span>
                        </a>

                        @if($hasApplication)
                        <!-- 3. Shortlist Action / Status Button (Single Checkmark) -->
                        <button type="button" id="btn_shortlist_action" onclick="toggleApplicantShortlist({{ $job_application->id }})" class="btn-recruiter-secondary {{ $isShortlisted ? 'is-active-shortlist' : '' }}" title="{{ $isShortlisted ? __('Candidate is Shortlisted') : __('Click to Shortlist') }}">
                            <i class="fa {{ $isShortlisted ? 'fa-check text-success' : 'fa-bookmark-o' }}" id="icon_shortlist_action"></i> 
                            <span id="text_shortlist_action">{{ __('Shortlisted') }}</span>
                        </button>
                        @endif

                        <!-- 4. Download CV (Simple Secondary Button) -->
                        @if(null !== $profileCv && !empty($profileCv->cv_file))
                        <a href="{{asset('cvs/'.$profileCv->cv_file)}}" target="_blank" class="btn-recruiter-secondary">
                            <i class="fa fa-download"></i> <span>{{__('Download CV')}}</span>
                        </a>
                        @endif

                        <!-- 5. More Actions Dropdown (Add Note, Share Profile, Reject) -->
                        <div class="recruiter-dropdown-wrap">
                            <button type="button" class="btn-recruiter-secondary btn-more-toggle" onclick="toggleRecruiterMore(event)">
                                <span>{{__('More')}}</span>
                                <i class="fa fa-angle-down" id="moreCaretIcon" style="font-size: 13px; margin-left: 3px; transition: transform 0.2s;"></i>
                            </button>
                            <div class="recruiter-dropdown-menu" id="recruiterMoreDropdown">
                                <a href="javascript:;" onclick="openAddNoteModal()" class="recruiter-dropdown-item">
                                    <i class="fa fa-sticky-note-o"></i> <span>{{__('Add Note')}}</span>
                                </a>
                                <a href="javascript:;" onclick="shareCandidateProfile()" class="recruiter-dropdown-item">
                                    <i class="fa fa-share-alt"></i> <span>{{__('Share Profile')}}</span>
                                </a>
                                @if($hasApplication)
                                <div class="recruiter-dropdown-divider"></div>
                                <a href="javascript:;" onclick="setApplicantStatus({{ $job_application->id }}, 'rejected')" class="recruiter-dropdown-item text-danger-action">
                                    <i class="fa fa-times"></i> <span>{{__('Reject Candidate')}}</span>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. About Candidate Card -->
                <div class="candidate-section-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 18px; padding: 24px 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                        <div style="width: 38px; height: 38px; border-radius: 10px; background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                            <i class="fa fa-user"></i>
                        </div>
                        <h3 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.2px;">{{__('About Me')}}</h3>
                    </div>
                    @php
                        $summary = $user->getProfileSummary('summary');
                    @endphp
                    @if(!empty($summary))
                        <p style="font-size: 14px; line-height: 1.7; color: #475569; margin: 0;">{{$summary}}</p>
                    @elseif(!empty($user->highest_qualification) || !empty($user->course_degree))
                        <p style="font-size: 14px; line-height: 1.7; color: #475569; margin: 0;">
                            {{ __('Career-driven candidate with qualification in') }} <strong>{{ $user->course_degree ?: $user->highest_qualification }}</strong>{{ !empty($user->institution_name) ? ' ' . __('from') . ' ' . $user->institution_name : '' }}. {{ __('Actively seeking promising career opportunities matching their skills and educational background.') }}
                        </p>
                    @else
                        <p class="text-muted" style="font-size: 13.5px; margin: 0;"><i class="fa fa-info-circle text-muted"></i> {{ __('No profile summary added yet.') }}</p>
                    @endif
                </div>

                <!-- 3. Preferred Job Roles Card -->
                @php
                    $preferredRoles = [];
                    if (!empty($user->preferred_job_roles)) {
                        $decoded = json_decode($user->preferred_job_roles, true);
                        $preferredRoles = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $user->preferred_job_roles)));
                    }
                @endphp
                @if(!empty($preferredRoles))
                <div class="candidate-section-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 18px; padding: 24px 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                        <div style="width: 38px; height: 38px; border-radius: 10px; background: #FEF3C7; color: #D97706; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                            <i class="fa fa-crosshairs"></i>
                        </div>
                        <h3 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.2px;">{{__('Preferred Job Roles')}}</h3>
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                        @foreach($preferredRoles as $role)
                            <span style="background: #EFF6FF; color: #1D4ED8; border: 1.5px solid #BFDBFE; font-size: 13px; font-weight: 700; padding: 6px 14px; border-radius: 20px; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="fa fa-briefcase" style="font-size: 12px; color: #3B82F6;"></i> {{$role}}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- 4. Education Card -->
                <div class="candidate-section-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 18px; padding: 24px 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                        <div style="width: 38px; height: 38px; border-radius: 10px; background: #F3E8FF; color: #7C3AED; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                            <i class="fa fa-graduation-cap"></i>
                        </div>
                        <h3 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.2px;">{{__('Education Details')}}</h3>
                    </div>
                    <div id="education_div">
                        <div class="text-muted" style="font-size: 13.5px;"><i class="fa fa-spinner fa-spin"></i> {{ __('Loading education...') }}</div>
                    </div>            
                </div>

                <!-- 5. Experience Card -->
                <div class="candidate-section-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 18px; padding: 24px 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                        <div style="width: 38px; height: 38px; border-radius: 10px; background: #ECFDF5; color: #03855c; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                            <i class="fa fa-briefcase"></i>
                        </div>
                        <h3 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.2px;">{{__('Work Experience')}}</h3>
                    </div>
                    <div id="experience_div">
                        <div class="text-muted" style="font-size: 13.5px;"><i class="fa fa-spinner fa-spin"></i> {{ __('Loading experience...') }}</div>
                    </div>            
                </div>

                <!-- 6. Portfolio Card -->
                <div class="candidate-section-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 18px; padding: 24px 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                        <div style="width: 38px; height: 38px; border-radius: 10px; background: #FFF1F2; color: #E11D48; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                            <i class="fa fa-folder-open-o"></i>
                        </div>
                        <h3 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.2px;">{{__('Portfolio & Projects')}}</h3>
                    </div>
                    <div id="projects_div">
                        <div class="text-muted" style="font-size: 13.5px;"><i class="fa fa-spinner fa-spin"></i> {{ __('Loading portfolio...') }}</div>
                    </div>            
                </div>
            </div>

            <!-- Right Sidebar Column (Contact + Candidate Detail + Skills + Languages) -->
            <div class="col-lg-4 col-md-5"> 
				
				<!-- Sidebar Card 1: Candidate Contact -->
				<div class="candidate-sidebar-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 18px; padding: 22px 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
					<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 18px; border-bottom: 1px solid #F1F5F9; padding-bottom: 12px;">
                        <div style="width: 34px; height: 34px; border-radius: 8px; background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                            <i class="fa fa-address-book-o"></i>
                        </div>
                        <h3 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0;">{{__('Candidate Contact')}}</h3>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 12px;">            
                        @if(!empty($user->phone))
                        <div style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; color: #334155;">
                            <span style="width: 30px; height: 30px; border-radius: 50%; background: #F1F5F9; color: #2563EB; display: flex; align-items: center; justify-content: center; font-size: 12px;"><i class="fa fa-phone"></i></span>
                            <a href="tel:{{$user->phone}}" style="color: #2563EB; font-weight: 600; text-decoration: none;">{{$user->phone}}</a>
                        </div>
                        @endif

                        @if(!empty($user->mobile_num))
                        <div style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; color: #334155;">
                            <span style="width: 30px; height: 30px; border-radius: 50%; background: #F1F5F9; color: #2563EB; display: flex; align-items: center; justify-content: center; font-size: 12px;"><i class="fa fa-mobile-phone" style="font-size: 16px;"></i></span>
                            <a href="tel:{{$user->mobile_num}}" style="color: #2563EB; font-weight: 600; text-decoration: none;">{{$user->mobile_num}}</a>
                        </div>
                        @endif

                        @if(!empty($user->email))
                        <div style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; color: #334155;">
                            <span style="width: 30px; height: 30px; border-radius: 50%; background: #F1F5F9; color: #2563EB; display: flex; align-items: center; justify-content: center; font-size: 12px;"><i class="fa fa-envelope-o"></i></span>
                            <a href="mailto:{{$user->email}}" style="color: #2563EB; font-weight: 600; text-decoration: none; word-break: break-all;">{{$user->email}}</a>
                        </div>
                        @endif

                        @if(!empty($user->street_address) || !empty($user->getLocation()))
                        <div style="display: flex; align-items: flex-start; gap: 10px; font-size: 13.5px; color: #475569;">
                            <span style="width: 30px; height: 30px; border-radius: 50%; background: #FEF2F2; color: #EF4444; display: flex; align-items: center; justify-content: center; font-size: 12px; flex-shrink: 0; margin-top: 2px;"><i class="fa fa-map-marker"></i></span>
                            <span style="font-weight: 600;">{{ $user->street_address ?: $user->getLocation() }}</span>
                        </div>
                        @endif
                    </div>  
				</div>
				
				
                <!-- Sidebar Card 2: Candidate Details Matrix -->
                <div class="candidate-sidebar-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 18px; padding: 22px 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
					<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px; border-bottom: 1px solid #F1F5F9; padding-bottom: 12px;">
                        <div style="width: 34px; height: 34px; border-radius: 8px; background: #F8FAFC; color: #475569; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                            <i class="fa fa-id-card-o"></i>
                        </div>
                        <h3 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0;">{{__('Candidate Details')}}</h3>
                    </div>

                    <div style="display: flex; flex-direction: column;">

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px dashed #F1F5F9; font-size: 13px;">
                            <span style="color: #64748B; font-weight: 600;">{{__('Email Verified')}}</span>
                            <span style="font-weight: 700; color: {{ ((bool)$user->verified) ? '#03855c' : '#94A3B8' }};">
                                <i class="fa {{ ((bool)$user->verified) ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                {{ ((bool)$user->verified) ? __('Yes') : __('No') }}
                            </span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px dashed #F1F5F9; font-size: 13px;">
                            <span style="color: #64748B; font-weight: 600;">{{__('Immediate Available')}}</span>
                            <span style="font-weight: 700; color: {{ ((bool)$user->is_immediate_available) ? '#03855c' : '#64748B' }};">
                                {{ ((bool)$user->is_immediate_available) ? __('Yes') : __('No') }}
                            </span>
                        </div>

                        @if(!empty($user->profile_type))
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px dashed #F1F5F9; font-size: 13px;">
                            <span style="color: #64748B; font-weight: 600;">{{__('Profile Type')}}</span>
                            <span style="font-weight: 700; color: #2563EB; text-transform: capitalize;">{{$user->profile_type}}</span>
                        </div>
                        @endif

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px dashed #F1F5F9; font-size: 13px;">
                            <span style="color: #64748B; font-weight: 600;">{{__('Age')}}</span>
                            <span style="font-weight: 700; color: #0F172A;">{{ !empty($user->getAge()) ? $user->getAge() . ' ' . __('Years') : __('Not Specified') }}</span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px dashed #F1F5F9; font-size: 13px;">
                            <span style="color: #64748B; font-weight: 600;">{{__('Gender')}}</span>
                            <span style="font-weight: 700; color: #0F172A;">{{ !empty($user->getGender('gender')) ? $user->getGender('gender') : __('Not Specified') }}</span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px dashed #F1F5F9; font-size: 13px;">
                            <span style="color: #64748B; font-weight: 600;">{{__('Marital Status')}}</span>
                            <span style="font-weight: 700; color: #0F172A;">{{ !empty($user->getMaritalStatus('marital_status')) ? $user->getMaritalStatus('marital_status') : __('Not Specified') }}</span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px dashed #F1F5F9; font-size: 13px;">
                            <span style="color: #64748B; font-weight: 600;">{{__('Experience')}}</span>
                            <span style="font-weight: 700; color: #0F172A;">{{ !empty($user->getJobExperience('job_experience')) ? $user->getJobExperience('job_experience') : ($user->profile_type == 'fresher' ? __('Fresher') : __('Not Specified')) }}</span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px dashed #F1F5F9; font-size: 13px;">
                            <span style="color: #64748B; font-weight: 600;">{{__('Career Level')}}</span>
                            <span style="font-weight: 700; color: #0F172A;">{{ !empty($user->getCareerLevel('career_level')) ? $user->getCareerLevel('career_level') : ($user->highest_qualification ?: __('Not Specified')) }}</span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px dashed #F1F5F9; font-size: 13px;">
                            <span style="color: #64748B; font-weight: 600;">{{__('Current Salary')}}</span>
                            <span style="font-weight: 700; color: #0F172A;">
                                @if(!empty($user->current_salary))
                                    @php
                                        $currClean = preg_replace('/[^0-9.]/', '', $user->current_salary);
                                        $currNum = is_numeric($currClean) && $currClean > 0 ? number_format((float)$currClean) : $user->current_salary;
                                    @endphp
                                    {{ $currencySym }}{{ $currNum }}/{{ $salaryPeriod }}
                                @else
                                    {{ __('Not Specified') }}
                                @endif
                            </span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px dashed #F1F5F9; font-size: 13px;">
                            <span style="color: #64748B; font-weight: 600;">{{__('Expected Salary')}}</span>
                            <span style="font-weight: 700; color: #03855c;">
                                @if(!empty($expSalaryRaw))
                                    {{ $currencySym }}{{ $salaryNum }}/{{ $salaryPeriod }}
                                @else
                                    {{ __('Negotiable') }}
                                @endif
                            </span>
                        </div>

                        @if(!empty($user->highest_qualification))
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px dashed #F1F5F9; font-size: 13px;">
                            <span style="color: #64748B; font-weight: 600;">{{__('Qualification')}}</span>
                            <span style="font-weight: 700; color: #0F172A;">{{$user->highest_qualification}}</span>
                        </div>
                        @endif

                        @if(!empty($user->preferred_work_type))
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px dashed #F1F5F9; font-size: 13px;">
                            <span style="color: #64748B; font-weight: 600;">{{__('Preferred Job Type')}}</span>
                            <span style="font-weight: 700; color: #0F172A;">{{$user->preferred_work_type}}</span>
                        </div>
                        @endif

                        @if(!empty($user->preferred_work_mode))
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; font-size: 13px;">
                            <span style="color: #64748B; font-weight: 600;">{{__('Work Mode')}}</span>
                            <span style="font-weight: 700; color: #0F172A;">{{$user->preferred_work_mode}}</span>
                        </div>
                        @endif

                    </div>
                </div>

                <!-- Sidebar Card 3: Skills -->
                <div class="candidate-sidebar-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 18px; padding: 22px 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
					<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px; border-bottom: 1px solid #F1F5F9; padding-bottom: 12px;">
                        <div style="width: 34px; height: 34px; border-radius: 8px; background: #ECFDF5; color: #03855c; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                            <i class="fa fa-check-square-o"></i>
                        </div>
                        <h3 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0;">{{__('Skills')}}</h3>
                    </div>
                    <div id="skill_div">
                        <div class="text-muted" style="font-size: 13px;"><i class="fa fa-spinner fa-spin"></i> {{ __('Loading skills...') }}</div>
                    </div>            
                </div>

                <!-- Sidebar Card 4: Languages -->
                <div class="candidate-sidebar-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 18px; padding: 22px 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
					<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px; border-bottom: 1px solid #F1F5F9; padding-bottom: 12px;">
                        <div style="width: 34px; height: 34px; border-radius: 8px; background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                            <i class="fa fa-globe"></i>
                        </div>
                        <h3 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0;">{{__('Languages')}}</h3>
                    </div>
                    <div id="language_div">
                        <div class="text-muted" style="font-size: 13px;"><i class="fa fa-spinner fa-spin"></i> {{ __('Loading languages...') }}</div>
                    </div>            
                </div>
               
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="sendmessage" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <form action="" id="send-form">
                @csrf
                <input type="hidden" name="seeker_id" id="seeker_id" value="{{$user->id}}">
                <div class="modal-header">                    
                    <h4 class="modal-title">Send Message</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <textarea class="form-control" name="message" id="message" cols="10" rows="7"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>

    </div>
</div>
@include('includes.footer')
@endsection
@push('styles')
<style type="text/css">
    .formrow iframe {
        height: 78px;
    }
</style>
@endpush
@push('scripts') 
<script type="text/javascript">
    $(document).ready(function () {
    $(document).on('click', '#send_applicant_message', function () {
    var postData = $('#send-applicant-message-form').serialize();
    $.ajax({
    type: 'POST',
            url: "{{ route('contact.applicant.message.send') }}",
            data: postData,
            //dataType: 'json',
            success: function (data)
            {
            response = JSON.parse(data);
            var res = response.success;
            if (res == 'success')
            {
            var errorString = '<div role="alert" class="alert alert-success">' + response.message + '</div>';
            $('#alert_messages').html(errorString);
            $('#send-applicant-message-form').hide('slow');
            $(document).scrollTo('.alert', 2000);
            } else
            {
            var errorString = '<div class="alert alert-danger" role="alert"><ul>';
            response = JSON.parse(data);
            $.each(response, function (index, value)
            {
            errorString += '<li>' + value + '</li>';
            });
            errorString += '</ul></div>';
            $('#alert_messages').html(errorString);
            $(document).scrollTo('.alert', 2000);
            }
            },
    });
    });
    showEducation();
    showProjects();
    showExperience();
    showSkills();
    showLanguages();
    });
    function showProjects()
    {
    $.post("{{ route('show.applicant.profile.projects', $user->id) }}", {user_id: {{$user->id}}, _method: 'POST', _token: '{{ csrf_token() }}'})
            .done(function (response) {
            $('#projects_div').html(response);
            });
    }
    function showExperience()
    {
    $.post("{{ route('show.applicant.profile.experience', $user->id) }}", {user_id: {{$user->id}}, _method: 'POST', _token: '{{ csrf_token() }}'})
            .done(function (response) {
            $('#experience_div').html(response);
            });
    }
    function showEducation()
    {
    $.post("{{ route('show.applicant.profile.education', $user->id) }}", {user_id: {{$user->id}}, _method: 'POST', _token: '{{ csrf_token() }}'})
            .done(function (response) {
            $('#education_div').html(response);
            });
    }
    function showLanguages()
    {
    $.post("{{ route('show.applicant.profile.languages', $user->id) }}", {user_id: {{$user->id}}, _method: 'POST', _token: '{{ csrf_token() }}'})
            .done(function (response) {
            $('#language_div').html(response);
            });
    }
    function showSkills()
    {
    $.post("{{ route('show.applicant.profile.skills', $user->id) }}", {user_id: {{$user->id}}, _method: 'POST', _token: '{{ csrf_token() }}'})
            .done(function (response) {
            $('#skill_div').html(response);
            });
    }

    function send_message() {
        const el = document.createElement('div')
        el.innerHTML = "Please <a class='btn' href='{{route('login')}}' onclick='set_session()'>log in</a> as a Employer and try again."
        @if(null!==(Auth::guard('company')->user()))
        $('#sendmessage').modal('show');
        @else
        swal({
            title: "You are not Loged in",
            content: el,
            icon: "error",
            button: "OK",
        });
        @endif
    }
    if ($("#send-form").length > 0) {
        $("#send-form").validate({
            validateHiddenInputs: true,
            ignore: "",

            rules: {
                message: {
                    required: true,
                    maxlength: 5000
                },
            },
            messages: {

                message: {
                    required: "Message is required",
                }

            },
            submitHandler: function(form) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                @if(null !== (Auth::guard('company')->user()))
                $.ajax({
                    url: "{{route('submit-message-seeker')}}",
                    type: "POST",
                    data: $('#send-form').serialize(),
                    success: function(response) {
                        $("#send-form").trigger("reset");
                        $('#sendmessage').modal('hide');
                        swal({
                            title: "Success",
                            text: response["msg"],
                            icon: "success",
                            button: "OK",
                        });
                    }
                });
                @endif
            }
        })
    }

    // 1. Status Update (Hire / Reject / Under Review)
    function setApplicantStatus(applicationId, newStatus) {
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

        // Close dropdown if open
        $('#recruiterMoreDropdown').removeClass('show');
        $('#moreCaretIcon').css('transform', 'rotate(0deg)');

        $.ajax({
            url: "{{ route('company.update.application.status') }}",
            type: 'POST',
            data: {
                application_id: applicationId,
                status: newStatus,
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function() {
                $('#profile_status_badge').css('opacity', '0.5');
            },
            success: function(response) {
                $('#profile_status_badge').css('opacity', '1');
                if (response.status === 'success') {
                    var badgeHtml = '';
                    if (newStatus === 'hired') {
                        badgeHtml = '<span class="status-chip-hired"><i class="fa fa-trophy"></i> Hired / Selected</span>';
                    } else if (newStatus === 'rejected') {
                        badgeHtml = '<span class="status-chip-rejected"><i class="fa fa-times-circle"></i> Rejected</span>';
                    } else if (newStatus === 'shortlisted') {
                        badgeHtml = '<span class="status-chip-shortlisted"><i class="fa fa-check-circle"></i> Shortlisted</span>';
                    } else {
                        badgeHtml = '<span class="status-chip-applied"><i class="fa fa-clock-o"></i> Under Review</span>';
                    }
                    $('#profile_status_badge').html(badgeHtml);
                    
                    if (newStatus === 'shortlisted') {
                        $('#btn_shortlist_action').addClass('is-active-shortlist');
                        $('#icon_shortlist_action').removeClass('fa-bookmark-o fa-bookmark text-primary').addClass('fa-check text-success');
                        $('#text_shortlist_action').text('Shortlisted');
                    } else if (newStatus === 'rejected' || newStatus === 'applied') {
                        $('#btn_shortlist_action').removeClass('is-active-shortlist');
                        $('#icon_shortlist_action').removeClass('fa-check text-success fa-bookmark text-primary').addClass('fa-bookmark-o');
                        $('#text_shortlist_action').text('Shortlist');
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
                $('#profile_status_badge').css('opacity', '1');
                alert('Error updating status: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.statusText));
            }
        });
    }

    // 2. Toggle Shortlist 1-Click Action
    function toggleApplicantShortlist(applicationId, currentIsShortlisted) {
        var newStatus = $('#btn_shortlist_action').hasClass('is-active-shortlist') ? 'applied' : 'shortlisted';
        setApplicantStatus(applicationId, newStatus);
    }

    // 3. Toggle Recruiter More Dropdown Menu
    function toggleRecruiterMore(e) {
        if (e) e.stopPropagation();
        var menu = $('#recruiterMoreDropdown');
        var caret = $('#moreCaretIcon');
        if (menu.hasClass('show')) {
            menu.removeClass('show');
            caret.css('transform', 'rotate(0deg)');
        } else {
            menu.addClass('show');
            caret.css('transform', 'rotate(180deg)');
        }
    }

    // Close More menu when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.recruiter-dropdown-wrap').length) {
            $('#recruiterMoreDropdown').removeClass('show');
            $('#moreCaretIcon').css('transform', 'rotate(0deg)');
        }
    });

    // 4. Share Candidate Profile (1-Click Copy Link)
    function shareCandidateProfile() {
        $('#recruiterMoreDropdown').removeClass('show');
        $('#moreCaretIcon').css('transform', 'rotate(0deg)');
        
        var dummy = document.createElement('input');
        var currentUrl = window.location.href;
        document.body.appendChild(dummy);
        dummy.value = currentUrl;
        dummy.select();
        document.execCommand('copy');
        document.body.removeChild(dummy);

        if (typeof swal !== 'undefined') {
            swal({
                title: "Link Copied!",
                text: "Candidate profile URL has been copied to your clipboard.",
                icon: "success",
                button: "OK"
            });
        } else {
            alert("Candidate profile link copied to clipboard!");
        }
    }

    // 5. Add Note Modal Handlers
    function openAddNoteModal() {
        $('#recruiterMoreDropdown').removeClass('show');
        $('#moreCaretIcon').css('transform', 'rotate(0deg)');
        
        var savedNote = localStorage.getItem('candidate_note_{{ $user->id }}') || '';
        $('#recruiter_note_text').val(savedNote);
        $('#recruiter_note_modal').modal('show');
    }

    function saveRecruiterNote() {
        var note = $('#recruiter_note_text').val();
        localStorage.setItem('candidate_note_{{ $user->id }}', note);
        $('#recruiter_note_modal').modal('hide');
        if (typeof swal !== 'undefined') {
            swal({
                title: "Note Saved",
                text: "Your private recruiter note for this candidate has been saved.",
                icon: "success",
                button: "OK"
            });
        } else {
            alert("Your private recruiter note has been saved.");
        }
    }
</script>

<!-- Clean Recruiter Private Note Modal -->
<div class="modal fade" id="recruiter_note_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.12); overflow: hidden;">
            <div class="modal-header" style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0; padding: 18px 24px;">
                <h4 class="modal-title" style="font-size: 16.5px; font-weight: 800; color: #0F172A; margin: 0; display: flex; align-items: center; gap: 8px;">
                    <i class="fa fa-sticky-note-o text-primary"></i> {{__('Recruiter Note')}} - {{ $user->getName() }}
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size: 24px; color: #64748B; opacity: 0.8; cursor: pointer; border: none; background: transparent;">&times;</button>
            </div>
            <div class="modal-body" style="padding: 24px;">
                <p style="font-size: 13px; color: #64748B; margin-bottom: 12px;">
                    {{__('Write private notes, interview feedback, or rating for this candidate (visible only to your recruitment team).')}}
                </p>
                <div class="form-group" style="margin: 0;">
                    <textarea id="recruiter_note_text" class="form-control" rows="4" placeholder="{{__('e.g. Cleared round 1 technical interview. Good communication skills, expected notice period 15 days...')}}" style="border: 1.5px solid #CBD5E1; border-radius: 10px; font-size: 13.5px; padding: 12px;"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 14px 24px; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px; font-weight: 600; padding: 8px 18px; border: 1.5px solid #CBD5E1; background: #FFFFFF;">
                    {{__('Cancel')}}
                </button>
                <button type="button" class="btn btn-primary" onclick="saveRecruiterNote()" style="border-radius: 8px; font-weight: 700; background: #2563EB; border: none; padding: 8px 22px; box-shadow: 0 2px 8px rgba(37,99,235,0.25);">
                    <i class="fa fa-check"></i> {{__('Save Note')}}
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Clean Recruiter Action Toolbar & Highlights Styles */
.candidate-hero-card {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 18px;
    padding: 26px 28px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.02);
    margin-bottom: 24px;
}
.candidate-hero-top {
    display: flex;
    gap: 20px;
    align-items: flex-start;
    flex-wrap: wrap;
    margin-bottom: 20px;
}
.candidate-avatar-wrap {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #EFF6FF;
    box-shadow: 0 4px 14px rgba(37,99,235,0.08);
    background: #F8FAFC;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.candidate-avatar-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.candidate-avatar-placeholder {
    width: 100%;
    height: 100%;
    background: #EFF6FF;
    color: #2563EB;
    font-size: 26px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
}
.candidate-info-wrap {
    flex: 1;
    min-width: 260px;
}
.candidate-title-row {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 8px;
}
.candidate-name {
    font-size: 22px;
    font-weight: 800;
    color: #0F172A;
    margin: 0;
    letter-spacing: -0.3px;
    line-height: 1.2;
}
.badge-available-now {
    background: #ECFDF5;
    color: #047857;
    border: 1px solid #A7F3D0;
    font-size: 11.5px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.pulse-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #10B981;
    display: inline-block;
}
.status-chip-hired {
    background: #F5F3FF;
    color: #6D28D9;
    border: 1px solid #DDD6FE;
    font-size: 11.5px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.status-chip-rejected {
    background: #FFF1F2;
    color: #9F1239;
    border: 1px solid #FECDD3;
    font-size: 11.5px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.status-chip-shortlisted {
    background: #ECFDF5;
    color: #047857;
    border: 1px solid #A7F3D0;
    font-size: 11.5px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.status-chip-applied {
    background: #EFF6FF;
    color: #1D4ED8;
    border: 1px solid #BFDBFE;
    font-size: 11.5px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.candidate-highlights-bar {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 4px;
}
.highlight-chip {
    background: #F1F5F9;
    color: #475569;
    border: none;
    font-size: 12.5px;
    font-weight: 500;
    padding: 3.5px 9px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.highlight-chip i {
    font-size: 12px;
}
.highlight-chip.verified {
    background: #EFF6FF;
    color: #1D4ED8;
    font-weight: 600;
}
.highlight-chip.verified i {
    color: #2563EB;
}
.highlight-chip.salary {
    background: #F8FAFC;
    color: #1E293B;
    border: 1px solid #E2E8F0;
    font-weight: 600;
}
.candidate-hero-top {
    display: flex;
    gap: 20px;
    align-items: flex-start;
    flex-wrap: wrap;
    margin-bottom: 22px;
}
.recruiter-action-toolbar {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    padding-top: 20px;
    margin-top: 4px;
    border-top: 1px solid #F1F5F9;
}
.btn-recruiter-hire {
    background: #2563EB !important;
    color: #FFFFFF !important;
    border: none !important;
    border-radius: 10px !important;
    font-size: 13.5px !important;
    font-weight: 700 !important;
    padding: 9px 20px !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 7px !important;
    cursor: pointer !important;
    text-decoration: none !important;
    box-shadow: 0 2px 8px rgba(37,99,235,0.25) !important;
    transition: all 0.15s ease !important;
    white-space: nowrap !important;
}
.btn-recruiter-hire:hover {
    background: #1D4ED8 !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(37,99,235,0.35) !important;
}
.btn-recruiter-secondary {
    background: #FFFFFF !important;
    color: #334155 !important;
    border: 1.5px solid #CBD5E1 !important;
    border-radius: 10px !important;
    font-size: 13.5px !important;
    font-weight: 600 !important;
    padding: 8.5px 18px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 7px !important;
    cursor: pointer !important;
    text-decoration: none !important;
    transition: all 0.15s ease !important;
    white-space: nowrap !important;
}
.btn-recruiter-secondary:hover {
    background: #F8FAFC !important;
    border-color: #94A3B8 !important;
    color: #0F172A !important;
    transform: translateY(-1px);
}
.btn-recruiter-secondary.btn-send-msg {
    color: #2563EB !important;
    border-color: #BFDBFE !important;
    background: #F8FAFC !important;
}
.btn-recruiter-secondary.btn-send-msg:hover {
    background: #EFF6FF !important;
    border-color: #2563EB !important;
    color: #1D4ED8 !important;
}
.btn-recruiter-secondary.is-active-shortlist {
    background: #EFF6FF !important;
    border-color: #93C5FD !important;
    color: #1D4ED8 !important;
    font-weight: 700 !important;
}
.recruiter-dropdown-wrap {
    position: relative;
    display: inline-block;
}
.recruiter-dropdown-menu {
    display: none;
    position: absolute;
    top: calc(100% + 6px);
    right: 0;
    min-width: 190px;
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 12px;
    box-shadow: 0 10px 25px -4px rgba(15, 23, 42, 0.12);
    padding: 6px;
    z-index: 1050;
}
.recruiter-dropdown-menu.show {
    display: block !important;
}
.recruiter-dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    font-size: 13.5px;
    font-weight: 600;
    color: #334155 !important;
    border-radius: 8px;
    text-decoration: none !important;
    transition: all 0.15s ease;
}
.recruiter-dropdown-item:hover {
    background: #F1F5F9;
    color: #0F172A !important;
}
.recruiter-dropdown-item i {
    font-size: 14px;
    width: 16px;
    text-align: center;
    color: #64748B;
}
.recruiter-dropdown-divider {
    height: 1px;
    background: #F1F5F9;
    margin: 4px 0;
}
.recruiter-dropdown-item.text-danger-action {
    color: #DC2626 !important;
}
.recruiter-dropdown-item.text-danger-action i {
    color: #DC2626 !important;
}
.recruiter-dropdown-item.text-danger-action:hover {
    background: #FEF2F2 !important;
    color: #B91C1C !important;
}
@media (max-width: 767px) {
    .candidate-hero-card {
        padding: 18px 16px;
    }
    .candidate-highlights-bar {
        gap: 6px;
    }
    .recruiter-action-toolbar {
        gap: 8px;
    }
    .btn-recruiter-hire,
    .btn-recruiter-secondary {
        font-size: 13px !important;
        padding: 8px 14px !important;
    }
}
</style>
@endpush