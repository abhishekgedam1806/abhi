@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 

<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('Job Detail')]) 
<!-- Inner Page Title end -->

@include('flash::message')

@php
$company = $job->getCompany();
@endphp

@push('styles')
@include('includes.job_posting_schema')
@endpush

<div class="listpgWraper job-detail-redesign" style="background: #F8FAFC; padding: 36px 0 60px; min-height: 85vh;">
    <div class="container" style="max-width: 1300px;"> 
        @include('flash::message')

        <div class="row">
            <!-- Left Main Column (Job Header + Description + Benefits + Skills) -->
            <div class="col-lg-8 col-md-7"> 
				
                <!-- 1. Main Job Hero Card -->
                <div class="job-hero-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
                    
                    <div style="display: flex; gap: 20px; align-items: flex-start; margin-bottom: 20px; flex-wrap: wrap;">
                        <!-- Company Logo -->
                        <div style="width: 72px; height: 72px; border-radius: 14px; overflow: hidden; border: 1.5px solid #E2E8F0; background: #F8FAFC; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                            @if(!empty($company->logo))
                                <a href="{{route('company.detail', $company->slug)}}">
                                    {{ $company->printCompanyImage(72, 72) }}
                                </a>
                            @else
                                <span style="font-size: 24px; font-weight: 800; color: #2563EB;">
                                    {{ strtoupper(substr($company->name, 0, 1)) }}
                                </span>
                            @endif
                        </div>

                        <!-- Job Title & Meta -->
                        <div style="flex: 1; min-width: 260px;">
                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 4px;">
                                <h1 style="font-size: 22px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.3px; line-height: 1.3;">
                                    {{ $job->title }}
                                </h1>
                            </div>

                            <div style="font-size: 15px; font-weight: 700; color: #2563EB; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                                <a href="{{route('company.detail', $company->slug)}}" style="color: #2563EB; text-decoration: none;">
                                    {{ $company->name }}
                                </a>
                                <span style="display: inline-flex; align-items: center; justify-content: center; background: #03855c; color: #FFFFFF; width: 16px; height: 16px; border-radius: 50%; font-size: 10px; font-weight: bold;" title="{{__('Verified Employer')}}">
                                    &#10003;
                                </span>
                            </div>

                            <div style="display: flex; align-items: center; gap: 14px; font-size: 13px; color: #64748B; flex-wrap: wrap;">
                                <span><i class="fa fa-map-marker text-danger"></i> {{ $job->is_freelance ? __('Freelance / Remote') : (!empty($job->area_name) ? $job->area_name . ', ' : '') . ($job->getLocation() ?: __('Location Not Specified')) }}</span>
                                <span><i class="fa fa-calendar-check-o text-primary"></i> <strong>{{ __('Posted:') }}</strong> {{ $job->created_at ? $job->created_at->format('d M Y, h:i A') . ' (' . $job->created_at->diffForHumans() . ')' : __('Recently') }}</span>
                                @if($job->expiry_date)
                                    <span><i class="fa fa-hourglass-end text-danger"></i> <strong>{{ __('Expires:') }}</strong> {{ $job->expiry_date->format('d M Y') }}</span>
                                @endif
                                <span><i class="fa fa-eye"></i> {{ (int)$job->num_of_views }} {{ __('Views') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Salary Highlight Banner -->
                    <div style="background: #F0FDF4; border: 1px solid #DCFCE7; border-radius: 12px; padding: 14px 18px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; flex-wrap: wrap; gap: 10px;">
                        <div>
                            <span style="font-size: 11px; font-weight: 700; color: #15803D; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 2px;">
                                {{__('Offered Salary')}} ({{ $job->getSalaryPeriod('salary_period') ?: __('Monthly') }})
                            </span>
                            @if(!Auth::user() && !Auth::guard('company')->user())
                                <a href="{{route('login')}}" style="font-size: 14px; font-weight: 700; color: #2563EB; text-decoration: none;">
                                    <i class="fa fa-lock"></i> {{__('Login to View Salary')}}
                                </a>
                            @else
                                @if(!(bool)$job->hide_salary)
                                    @if($job->salary_from > 0 || $job->salary_to > 0)
                                        <span style="font-size: 18px; font-weight: 900; color: #047857;">
                                            {{ $job->salary_from ? number_format($job->salary_from).' '.$job->salary_currency : '' }}
                                            {{ ($job->salary_from > 0 && $job->salary_to > 0) ? ' – ' : '' }}
                                            {{ $job->salary_to ? number_format($job->salary_to).' '.$job->salary_currency : '' }}
                                        </span>
                                    @else
                                        <span style="font-size: 14px; font-weight: 700; color: #475569;">{{__('Salary Negotiable')}}</span>
                                    @endif
                                @else
                                    <span style="font-size: 14px; font-weight: 700; color: #475569;">{{__('Salary Negotiable')}}</span>
                                @endif
                            @endif
                        </div>

                        <div style="display: flex; gap: 8px; align-items: center;">
                            <span style="background: #ECFDF5; border: 1px solid #A7F3D0; color: #03855c; font-size: 12px; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
                                {{ $job->getJobType('job_type') ?: __('Full Time') }}
                            </span>
                            <span style="background: #EFF6FF; border: 1px solid #BFDBFE; color: #1D4ED8; font-size: 12px; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
                                {{ $job->getJobShift('job_shift') ?: __('Day Shift') }}
                            </span>
                        </div>
                    </div>

                    <!-- Key Job Specifications Grid -->
                    <div style="border-top: 1px solid #F1F5F9; padding-top: 18px; margin-bottom: 20px;">
                        <h4 style="font-size: 15px; font-weight: 800; color: #0F172A; margin: 0 0 14px 0;">
                            {{__('Job Overview & Specifications')}}
                        </h4>
                        
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;" class="job-specs-grid">
                            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 10px 12px;">
                                <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase;">{{__('Career Level')}}</div>
                                <div style="font-size: 13.5px; font-weight: 700; color: #0F172A; margin-top: 2px;">{{ $job->getCareerLevel('career_level') ?: __('Entry Level') }}</div>
                            </div>
                            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 10px 12px;">
                                <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase;">{{__('Experience')}}</div>
                                <div style="font-size: 13.5px; font-weight: 700; color: #0F172A; margin-top: 2px;">{{ $job->getJobExperience('job_experience') ?: __('Fresh / Any') }}</div>
                            </div>
                            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 10px 12px;">
                                <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase;">{{__('Openings')}}</div>
                                <div style="font-size: 13.5px; font-weight: 700; color: #0F172A; margin-top: 2px;">{{ $job->num_of_positions ?: 1 }} {{__('Positions')}}</div>
                            </div>
                            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 10px 12px;">
                                <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase;">{{__('Gender')}}</div>
                                <div style="font-size: 13.5px; font-weight: 700; color: #0F172A; margin-top: 2px;">{{ $job->getGender('gender') ?: __('No Preference') }}</div>
                            </div>
                            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 10px 12px;">
                                <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase;">{{__('Qualification')}}</div>
                                <div style="font-size: 13.5px; font-weight: 700; color: #0F172A; margin-top: 2px;">{{ $job->getDegreeLevel('degree_level') ?: __('Bachelors / Graduate') }}</div>
                            </div>
                            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 10px 12px;">
                                <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase;">{{__('Apply Deadline')}}</div>
                                <div style="font-size: 13.5px; font-weight: 700; color: #DC2626; margin-top: 2px;">{{ $job->expiry_date ? $job->expiry_date->format('M d, Y') : __('Open') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Job Action Bar -->
                    <div style="display: flex; gap: 10px; flex-wrap: wrap; border-top: 1px solid #F1F5F9; padding-top: 16px;">
                        <a href="{{route('email.to.friend', $job->slug)}}" class="btn-job-action" style="display: inline-flex; align-items: center; gap: 6px; background: #FFFFFF; border: 1.5px solid #CBD5E1; color: #475569; font-size: 12.5px; font-weight: 600; padding: 7px 14px; border-radius: 8px; text-decoration: none; transition: all 0.15s ease;">
                            <i class="fa fa-envelope-o"></i> {{__('Email to Friend')}}
                        </a>
                        
                        @if(Auth::check() && Auth::user()->isFavouriteJob($job->slug))
                            <a href="{{route('remove.from.favourite', $job->slug)}}" class="btn-job-action" style="display: inline-flex; align-items: center; gap: 6px; background: #FEF2F2; border: 1.5px solid #FECACA; color: #DC2626; font-size: 12.5px; font-weight: 600; padding: 7px 14px; border-radius: 8px; text-decoration: none;">
                                <i class="fa fa-heart"></i> {{__('Saved to Favourites')}}
                            </a>
                        @else
                            <a href="{{route('add.to.favourite', $job->slug)}}" class="btn-job-action" style="display: inline-flex; align-items: center; gap: 6px; background: #FFFFFF; border: 1.5px solid #CBD5E1; color: #475569; font-size: 12.5px; font-weight: 600; padding: 7px 14px; border-radius: 8px; text-decoration: none; transition: all 0.15s ease;">
                                <i class="fa fa-heart-o"></i> {{__('Add to Favourite')}}
                            </a>
                        @endif

                        <a href="{{route('report.abuse', $job->slug)}}" class="btn-job-action" style="display: inline-flex; align-items: center; gap: 6px; background: #FFFFFF; border: 1.5px solid #FECACA; color: #DC2626; font-size: 12.5px; font-weight: 600; padding: 7px 14px; border-radius: 8px; text-decoration: none; transition: all 0.15s ease;">
                            <i class="fa fa-exclamation-triangle"></i> {{__('Report Abuse')}}
                        </a>
                    </div>
                </div>

                <!-- 2. Job Description Card -->
                <div class="job-detail-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 26px 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
                    <h3 style="font-size: 18px; font-weight: 800; color: #0F172A; margin: 0 0 16px 0; display: flex; align-items: center; gap: 10px;">
                        <i class="fa fa-file-text-o" style="color: #2563EB; font-size: 17px;"></i>
                        <span>{{__('Job Description & Responsibilities')}}</span>
                    </h3>
                    <div class="job-rich-description" style="font-size: 14.5px; color: #334155; line-height: 1.7;">
                        {!! $job->description !!}
                    </div>
                </div>

                <!-- 3. Benefits Card (If present and not duplicate) -->
                @if(!empty($job->benefits) && trim(strip_tags($job->benefits)) != '' && trim(strip_tags($job->benefits)) != trim(strip_tags($job->description)))
                    <div class="job-detail-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 26px 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
                        <h3 style="font-size: 18px; font-weight: 800; color: #0F172A; margin: 0 0 16px 0; display: flex; align-items: center; gap: 10px;">
                            <i class="fa fa-gift" style="color: #03855c; font-size: 17px;"></i>
                            <span>{{__('Job Benefits & Perks')}}</span>
                        </h3>
                        <div class="job-rich-benefits" style="font-size: 14.5px; color: #334155; line-height: 1.7;">
                            {!! $job->benefits !!}
                        </div>
                    </div>
                @endif

                <!-- 4. Skills Required Card -->
                <div class="job-detail-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 26px 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
                    <h3 style="font-size: 18px; font-weight: 800; color: #0F172A; margin: 0 0 16px 0; display: flex; align-items: center; gap: 10px;">
                        <i class="fa fa-puzzle-piece" style="color: #7C3AED; font-size: 17px;"></i>
                        <span>{{__('Required Skills')}}</span>
                    </h3>
                    <div class="skills-pill-cloud" style="display: flex; flex-wrap: wrap; gap: 8px;">
                        @if($job->jobSkills && count($job->jobSkills))
                            @foreach($job->jobSkills as $jsm)
                                @if($jsm->getJobSkill())
                                    <span style="background: #EFF6FF; border: 1px solid #DBEAFE; color: #1E40AF; font-size: 13px; font-weight: 600; padding: 6px 14px; border-radius: 20px;">
                                        {{ $jsm->getJobSkill('job_skill') }}
                                    </span>
                                @endif
                            @endforeach
                        @else
                            <ul class="skillslist" style="margin: 0; padding: 0; list-style: none;">
                                {!! $job->getJobSkillsList() !!}
                            </ul>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Right Sidebar Column (Apply Box + Company Overview + Map + Related Jobs) -->
            <div class="col-lg-4 col-md-5"> 
				
                <!-- 1. Apply Action Box -->
                <div class="job-apply-widget" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px; text-align: center;">
                    @if($job->isJobExpired())
                        <div style="background: #FEF2F2; border: 1px solid #FECACA; color: #DC2626; font-size: 14px; font-weight: 800; padding: 12px; border-radius: 10px; margin-bottom: 8px;">
                            <i class="fa fa-exclamation-circle"></i> {{__('This job has expired')}}
                        </div>
                        <div style="font-size: 12px; color: #64748B;">{{__('Applications are no longer being accepted.')}}</div>
                    @elseif(Auth::guard('company')->check())
                        @php
                            $loggedCompany = Auth::guard('company')->user();
                            $isOwnJob = ($loggedCompany->id == $job->company_id);
                            $appCount = \App\JobApply::where('job_id', $job->id)->count();
                        @endphp
                        @if($isOwnJob)
                            <!-- Recruiter Viewing Their Own Posted Job -->
                            <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 12px; padding: 14px; margin-bottom: 14px; text-align: left;">
                                <div style="font-size: 13.5px; font-weight: 800; color: #1D4ED8; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                                    <i class="fa fa-briefcase"></i> <span>{{__('Your Posted Job')}}</span>
                                </div>
                                <div style="font-size: 12px; color: #475569; line-height: 1.4;">
                                    {{__('You are previewing this job as the employer / recruiter.')}}
                                </div>
                            </div>
                            <a href="{{ route('list.applied.users', [$job->id]) }}" class="btn-main-apply" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; background: #2563EB; color: #FFFFFF; font-size: 14px; font-weight: 800; padding: 12px 18px; border-radius: 10px; text-decoration: none; margin-bottom: 8px; box-shadow: 0 4px 12px rgba(37,99,235,0.25);">
                                <i class="fa fa-users"></i>
                                <span>{{__('View Applicants')}} ({{ $appCount }})</span>
                            </a>
                            <a href="{{ route('edit.front.job', [$job->id]) }}" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; background: #FFFFFF; border: 1.5px solid #CBD5E1; color: #334155; font-size: 13.5px; font-weight: 700; padding: 10px 18px; border-radius: 10px; text-decoration: none;">
                                <i class="fa fa-pencil"></i>
                                <span>{{__('Edit This Job')}}</span>
                            </a>
                        @else
                            <!-- Recruiter Viewing Another Company's Job -->
                            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 14px; margin-bottom: 12px; text-align: left;">
                                <div style="font-size: 13.5px; font-weight: 700; color: #0F172A; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                                    <i class="fa fa-building-o text-primary"></i> <span>{{ __('Logged in as Employer') }}</span>
                                </div>
                                <div style="font-size: 12px; color: #64748B; line-height: 1.4;">
                                    {{ __('You are logged in with your company account.') }} <strong>{{ __('Only Job Seekers / Candidates can apply for jobs.') }}</strong>
                                </div>
                            </div>
                            <a href="{{ route('posted.jobs') }}" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; background: #FFFFFF; border: 1.5px solid #CBD5E1; color: #2563EB; font-size: 13px; font-weight: 700; padding: 10px 16px; border-radius: 10px; text-decoration: none;">
                                <i class="fa fa-arrow-left"></i>
                                <span>{{__('Go to My Posted Jobs')}}</span>
                            </a>
                        @endif
                    @elseif(Auth::check() && Auth::user()->isAppliedOnJob($job->id))
                        <div style="background: #ECFDF5; border: 1px solid #A7F3D0; color: #03855c; font-size: 15px; font-weight: 800; padding: 12px; border-radius: 10px; margin-bottom: 8px;">
                            <i class="fa fa-check-circle"></i> {{__('Already Applied')}}
                        </div>
                        <a href="{{ route('my.job.applications') }}" style="font-size: 12.5px; font-weight: 700; color: #2563EB; text-decoration: none;">
                            {{__('View Application Status')}} &rarr;
                        </a>
                    @else
                        @php
                            $hasExternalApply = $job->isExternalApply();
                            $hrEmail = $job->getHrEmail();
                            $hrPhone = $job->getHrPhone();
                            $sourceUrl = $job->getSourceUrl();
                        @endphp

                        @if(!Auth::check())
                            <!-- Guest / New User: Redirect to Login / Sign Up -->
                            <a href="{{ route('login') }}" class="btn-main-apply" style="display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; background: linear-gradient(135deg, #2563EB, #1D4ED8); color: #FFFFFF; font-size: 15px; font-weight: 800; padding: 14px 20px; border-radius: 12px; text-decoration: none; box-shadow: 0 4px 14px rgba(37,99,235,0.35); transition: all 0.15s ease; margin-bottom: 8px;">
                                @if($hasExternalApply)
                                    <i class="fa fa-external-link"></i>
                                    <span>{{__('Apply on Official Website')}}</span>
                                @elseif(!empty($hrEmail))
                                    <i class="fa fa-envelope"></i>
                                    <span>{{__('1-Click Email Resume to HR')}}</span>
                                @else
                                    <i class="fa fa-paper-plane"></i>
                                    <span>{{__('Apply for this Job')}}</span>
                                @endif
                            </a>
                            @if(!empty($hrPhone))
                                <a href="{{ route('login') }}" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; background: #25D366; color: #FFFFFF; font-size: 14px; font-weight: 800; padding: 11px 18px; border-radius: 10px; text-decoration: none; margin-bottom: 8px; box-shadow: 0 4px 12px rgba(37,211,102,0.25);">
                                    <i class="fa fa-whatsapp" style="font-size: 17px;"></i>
                                    <span>{{__('Apply via WhatsApp')}}</span>
                                </a>
                            @endif
                            <div style="font-size: 11.5px; color: #64748B; display: flex; align-items: center; justify-content: center; gap: 4px;">
                                <i class="fa fa-lock text-primary"></i> {{__('Login or Sign up to apply')}}
                            </div>
                        @elseif($hasExternalApply)
                            <!-- AI / External Job Flow (Adzuna / Official Board) -->
                            <a href="javascript:void(0);" onclick="handleExternalApply('{{ $job->slug }}', '{{ addslashes($sourceUrl) }}')" class="btn-main-apply" style="display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; background: linear-gradient(135deg, #2563EB, #1D4ED8); color: #FFFFFF; font-size: 15px; font-weight: 800; padding: 14px 20px; border-radius: 12px; text-decoration: none; box-shadow: 0 4px 14px rgba(37,99,235,0.35); transition: all 0.15s ease; margin-bottom: 8px;">
                                <i class="fa fa-external-link"></i>
                                <span>{{__('Apply on Official Website')}}</span>
                            </a>
                            <div style="font-size: 11.5px; color: #64748B; display: flex; align-items: center; justify-content: center; gap: 4px;">
                                <i class="fa fa-check-circle text-success"></i> {{__('Verified external vacancy from hiring board')}}
                            </div>
                        @elseif(!empty($hrEmail))
                            <!-- Manual / Imported Job Flow with Direct HR Email -->
                            <a href="javascript:void(0);" onclick="openDirectEmailModal()" class="btn-main-apply" style="display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; background: #03855c; color: #FFFFFF; font-size: 15px; font-weight: 800; padding: 14px 20px; border-radius: 12px; text-decoration: none; box-shadow: 0 4px 14px rgba(3,133,92,0.3); transition: all 0.15s ease; margin-bottom: 8px;">
                                <i class="fa fa-envelope"></i>
                                <span>{{__('1-Click Email Resume to HR')}}</span>
                            </a>
                            @if(!empty($hrPhone))
                                <a href="javascript:void(0);" onclick="handleWhatsappApply('{{ $job->slug }}')" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; background: #25D366; color: #FFFFFF; font-size: 14px; font-weight: 800; padding: 11px 18px; border-radius: 10px; text-decoration: none; margin-bottom: 8px; box-shadow: 0 4px 12px rgba(37,211,102,0.25);">
                                    <i class="fa fa-whatsapp" style="font-size: 17px;"></i>
                                    <span>{{__('Apply via WhatsApp')}}</span>
                                </a>
                            @endif
                            <div style="font-size: 11.5px; color: #64748B;">
                                <i class="fa fa-paper-plane text-primary"></i> {{__('Instant resume PDF dispatch directly to HR')}}
                            </div>
                        @else
                            <!-- Standard Registered Employer Flow -->
                            <a href="{{route('apply.job', $job->slug)}}" class="btn-main-apply" style="display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; background: #2563EB; color: #FFFFFF; font-size: 15px; font-weight: 800; padding: 14px 20px; border-radius: 12px; text-decoration: none; box-shadow: 0 4px 12px rgba(37,99,235,0.3); transition: all 0.15s ease; margin-bottom: 8px;">
                                <i class="fa fa-paper-plane"></i>
                                <span>{{__('Apply for this Job')}}</span>
                            </a>
                            @if(!empty($hrPhone))
                                <a href="javascript:void(0);" onclick="handleWhatsappApply('{{ $job->slug }}')" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; background: #25D366; color: #FFFFFF; font-size: 14px; font-weight: 800; padding: 11px 18px; border-radius: 10px; text-decoration: none; margin-bottom: 8px;">
                                    <i class="fa fa-whatsapp" style="font-size: 17px;"></i>
                                    <span>{{__('Apply via WhatsApp')}}</span>
                                </a>
                            @endif
                            <div style="font-size: 12px; color: #64748B;">{{__('Takes less than 1 minute to apply')}}</div>
                        @endif
                    @endif
                </div>

                <!-- 2. Company Overview Card -->
                <div class="company-overview-widget" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
                    <h3 style="font-size: 16.5px; font-weight: 800; color: #0F172A; margin: 0 0 16px 0; display: flex; align-items: center; gap: 8px;">
                        <i class="fa fa-building-o" style="color: #2563EB; font-size: 16px;"></i>
                        <span>{{__('Company Overview')}}</span>
                    </h3>

                    <div style="display: flex; gap: 14px; align-items: center; margin-bottom: 14px;">
                        <div style="width: 52px; height: 52px; border-radius: 12px; overflow: hidden; background: #F8FAFC; border: 1px solid #E2E8F0; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            @if(!empty($company->logo))
                                {{ $company->printCompanyImage(52, 52) }}
                            @else
                                <span style="font-size: 20px; font-weight: 800; color: #2563EB;">{{ strtoupper(substr($company->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div>
                            <h4 style="font-size: 15.5px; font-weight: 800; color: #0F172A; margin: 0 0 2px 0;">
                                <a href="{{route('company.detail', $company->slug)}}" style="color: #0F172A; text-decoration: none;">
                                    {{ $company->name }}
                                </a>
                            </h4>
                            <div style="font-size: 12.5px; color: #64748B;">
                                <i class="fa fa-map-marker text-danger"></i> {{ $company->getLocation() ?: __('Location Not Specified') }}
                            </div>
                        </div>
                    </div>

                    @if(!empty($company->description))
                        <p style="font-size: 13.5px; color: #475569; line-height: 1.5; margin-bottom: 14px;">
                            {{ \Illuminate\Support\Str::limit(strip_tags($company->description), 160, '...') }}
                        </p>
                    @endif

                    <!-- Company Official Website & Email Details -->
                    @php
                        $companyWebsite = $company->website;
                        if (!empty($companyWebsite) && !preg_match("~^(?:f|ht)tps?://~i", $companyWebsite)) {
                            $companyWebsite = "https://" . $companyWebsite;
                        }
                    @endphp

                    @if(!empty($companyWebsite) || !empty($company->email))
                        <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 12px 14px; margin-bottom: 14px; font-size: 13px; text-align: left;">
                            @if(!empty($companyWebsite))
                                <div style="margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                    <span style="font-weight: 700; color: #0F172A; display: flex; align-items: center; gap: 6px; font-size: 12.5px;">
                                        <i class="fa fa-globe text-primary"></i> {{ __('Website') }}
                                    </span>
                                    <a href="{{ $companyWebsite }}" target="_blank" rel="noopener noreferrer" style="color: #2563EB; font-weight: 700; text-decoration: none; font-size: 12.5px; display: inline-flex; align-items: center; gap: 4px;" title="{{ $companyWebsite }}">
                                        {{ __('Visit Website') }} <i class="fa fa-external-link" style="font-size: 11px;"></i>
                                    </a>
                                </div>
                            @endif
                            @if(!empty($company->email) && !\Illuminate\Support\Str::contains($company->email, ['@company.com', 'contact@company.com']))
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                    <span style="font-weight: 700; color: #0F172A; display: flex; align-items: center; gap: 6px; font-size: 12.5px;">
                                        <i class="fa fa-envelope-o text-success"></i> {{ __('Contact') }}
                                    </span>
                                    <a href="mailto:{{ $company->email }}" style="color: #475569; font-weight: 600; text-decoration: none; font-size: 12px; max-width: 170px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $company->email }}">
                                        {{ $company->email }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                    <a href="{{route('company.detail', $company->slug)}}" style="display: block; width: 100%; text-align: center; background: #F8FAFC; border: 1px solid #CBD5E1; color: #2563EB; font-size: 13px; font-weight: 700; padding: 9px; border-radius: 10px; text-decoration: none;">
                        {{ App\Company::countNumJobs('company_id', $company->id) }} {{__('Open Positions')}} &rarr;
                    </a>
                </div>

                <!-- 3. Google Map & Location Widget (Only if location is specified) -->
                @php
                    $mapCityName = null;
                    $mapEmbedUrl = null;

                    // Prefer job's own city_id over company city
                    if (!empty($job->city_id)) {
                        $mapCityName = $job->getCity('city');
                        // Build embed URL from job city name
                        if ($mapCityName) {
                            $mapEmbedUrl = 'https://maps.google.com/maps?q=' . urlencode($mapCityName) . '&t=&z=13&ie=UTF8&iwloc=&output=embed';
                        }
                    } elseif ($company && $company->city_id && $company->getCity('city')) {
                        $mapCityName = $company->getCity('city');
                        $mapEmbedUrl = $company->getGoogleMapEmbedUrl();
                    }

                    // For remote jobs, never show map
                    if ($job->is_freelance) {
                        $mapEmbedUrl = null;
                        $mapCityName = null;
                    }
                @endphp

                @if($mapEmbedUrl && $mapCityName)
                <div class="company-map-widget" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 22px 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
                    <h3 style="font-size: 16.5px; font-weight: 800; color: #0F172A; margin: 0 0 14px 0; display: flex; align-items: center; justify-content: space-between;">
                        <span style="display: flex; align-items: center; gap: 8px;">
                            <i class="fa fa-map-marker text-danger" style="font-size: 17px;"></i>
                            <span>{{__('Job Location')}}</span>
                        </span>
                        <span style="font-size: 12px; color: #64748B; font-weight: 600;">{{ $mapCityName }}</span>
                    </h3>

                    <div style="overflow: hidden; border-radius: 12px; border: 1px solid #E2E8F0; height: 210px; background: #F8FAFC;">
                        <iframe src="{{ $mapEmbedUrl }}" width="100%" height="210" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
                @endif

                <!-- 4. Related Jobs Widget -->
                @if(isset($relatedJobs) && count($relatedJobs))
                    <div class="related-jobs-widget" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 22px 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
                        <h3 style="font-size: 16.5px; font-weight: 800; color: #0F172A; margin: 0 0 16px 0;">
                            {{__('Related Jobs')}}
                        </h3>

                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            @foreach($relatedJobs as $relJob)
                                @php $relCompany = $relJob->getCompany(); @endphp
                                @if($relCompany)
                                    <div class="rel-job-item" style="padding: 12px 14px; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; transition: all 0.15s ease;">
                                        <h5 style="font-size: 14px; font-weight: 800; color: #0F172A; margin: 0 0 2px 0;">
                                            <a href="{{route('job.detail', [$relJob->slug])}}" style="color: #0F172A; text-decoration: none;">
                                                {{ $relJob->title }}
                                            </a>
                                        </h5>
                                        <div style="font-size: 12px; color: #2563EB; font-weight: 600; margin-bottom: 4px;">
                                            {{ $relCompany->name }}
                                        </div>
                                        <div style="font-size: 11.5px; color: #64748B; display: flex; justify-content: space-between;">
                                            <span><i class="fa fa-map-marker text-danger"></i> {{ $relJob->getCity('city') ?: 'Nagpur' }}</span>
                                            <span style="color: #03855c; font-weight: 700;">{{ $relJob->getJobType('job_type') ?: 'Full Time' }}</span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>

<style>
.job-detail-redesign .btn-main-apply:hover {
    background: #1D4ED8 !important;
    box-shadow: 0 6px 20px rgba(37,99,235,0.4) !important;
    transform: translateY(-1px);
}
.job-detail-redesign .btn-job-action:hover {
    background: #F1F5F9 !important;
    border-color: #94A3B8 !important;
}
.job-detail-redesign .rel-job-item:hover {
    background: #EFF6FF !important;
    border-color: #BFDBFE !important;
}
.job-detail-redesign .job-rich-description ul,
.job-detail-redesign .job-rich-benefits ul {
    padding-left: 20px;
    margin-bottom: 14px;
}
.job-detail-redesign .job-rich-description li,
.job-detail-redesign .job-rich-benefits li {
    margin-bottom: 6px;
}

@media (max-width: 767px) {
    .job-specs-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}
</style>

<!-- 1-Click Direct Email Modal -->
<div class="modal fade" id="directEmailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background: #03855c; color: #FFFFFF; padding: 18px 24px; border: none;">
                <h5 class="modal-title" style="font-weight: 800; font-size: 16.5px; display: flex; align-items: center; gap: 8px; margin: 0;">
                    <i class="fa fa-paper-plane-o"></i> {{__('Send Application & Resume to HR')}}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #FFFFFF; opacity: 0.9; font-size: 24px; margin-top: -8px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 24px;">
                @if(Auth::check() && !Auth::guard('company')->check())
                    @php
                        $userCvs = \App\ProfileCv::where('user_id', Auth::user()->id)->get();
                    @endphp
                    <div style="background: #F0FDF4; border: 1px solid #DCFCE7; border-radius: 10px; padding: 12px 14px; margin-bottom: 16px;">
                        <div style="font-size: 13px; color: #15803D; font-weight: 700; margin-bottom: 2px;">
                            {{__('Direct HR Recipient:')}} <span style="color: #0F172A;">{{ $job->getHrEmail() ?: ($company ? $company->email : 'HR Team') }}</span>
                        </div>
                        <div style="font-size: 11.5px; color: #475569;">
                            {{__('Your JobnBiz profile details & attached PDF resume will be emailed directly to the hiring manager.')}}
                        </div>
                    </div>

                    <form id="directEmailForm">
                        @csrf
                        <div class="form-group" style="margin-bottom: 14px;">
                            <label style="font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 4px;">{{__('Select Resume / CV to Attach')}}</label>
                            @if($userCvs->count() > 0)
                                <select name="cv_id" id="emailApplyCvId" class="form-control" style="border-radius: 8px; border-color: #CBD5E1; font-weight: 600;">
                                    @foreach($userCvs as $cv)
                                        <option value="{{ $cv->id }}" {{ $cv->is_default ? 'selected' : '' }}>
                                            📄 {{ $cv->title }} {{ $cv->is_default ? ' (Default)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <div style="background: #FEF2F2; border: 1px solid #FECACA; padding: 10px; border-radius: 8px; font-size: 12px; color: #DC2626; margin-bottom: 8px;">
                                    <i class="fa fa-warning"></i> {{__('No Resume found.')}} <a href="{{ route('my.profile') }}" style="color: #2563EB; font-weight: bold;">{{__('Upload CV in Profile')}}</a>
                                </div>
                            @endif
                        </div>

                        <div class="form-group" style="margin-bottom: 18px;">
                            <label style="font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 4px;">{{__('Quick Cover Note (Optional)')}}</label>
                            <textarea name="cover_note" id="emailApplyCoverNote" class="form-control" rows="3" style="border-radius: 8px; border-color: #CBD5E1; font-size: 13px;" placeholder="{{__('Hi, I am interested in this role and have relevant experience...')}}"></textarea>
                        </div>

                        <div id="emailApplyStatus" style="display: none; margin-bottom: 14px; font-size: 13px; padding: 10px; border-radius: 8px;"></div>

                        <button type="button" id="btnSubmitDirectEmail" onclick="submitDirectEmail('{{ $job->slug }}')" class="btn btn-block" style="background: #03855c; color: #FFFFFF; font-weight: 800; font-size: 14.5px; padding: 12px; border-radius: 10px;" {{ $userCvs->count() == 0 ? 'disabled' : '' }}>
                            <i class="fa fa-paper-plane"></i> {{__('Confirm & Send Application')}}
                        </button>
                    </form>
                @else
                    <div style="text-align: center; padding: 16px 8px;">
                        <div style="font-size: 40px; color: #2563EB; margin-bottom: 10px;"><i class="fa fa-user-circle-o"></i></div>
                        <h4 style="font-weight: 800; color: #0F172A; font-size: 17px; margin-bottom: 6px;">{{__('Login to 1-Click Apply')}}</h4>
                        <p style="font-size: 13px; color: #64748B; margin-bottom: 18px;">
                            {{__('Login or create a free Job Seeker account so your Resume PDF can be automatically attached.')}}
                        </p>
                        <a href="{{ route('login') }}" class="btn btn-primary btn-block" style="background: #2563EB; font-weight: 800; padding: 11px; border-radius: 10px;">
                            {{__('Login / Sign Up to Apply')}}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function handleExternalApply(slug, fallbackUrl) {
    @if(!Auth::check())
        window.location.href = "{{ route('login') }}";
        return;
    @endif
    const url = "{{ url('apply-external') }}/" + slug;
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(r => r.json())
    .then(data => {
        if (data.require_login) {
            window.location.href = data.redirect_url || "{{ route('login') }}";
            return;
        }
        const dest = data.redirect_url || fallbackUrl;
        if (dest) {
            window.open(dest, '_blank');
        }
    })
    .catch(err => {
        if (fallbackUrl) {
            window.open(fallbackUrl, '_blank');
        }
    });
}

function handleWhatsappApply(slug) {
    @if(!Auth::check())
        window.location.href = "{{ route('login') }}";
        return;
    @endif
    const url = "{{ url('apply-whatsapp') }}/" + slug;
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(r => r.json())
    .then(data => {
        if (data.whatsapp_url) {
            window.open(data.whatsapp_url, '_blank');
        }
    })
    .catch(err => console.error(err));
}

function openDirectEmailModal() {
    @if(!Auth::check())
        window.location.href = "{{ route('login') }}";
        return;
    @endif
    $('#directEmailModal').modal('show');
}

function submitDirectEmail(slug) {
    const btn = document.getElementById('btnSubmitDirectEmail');
    const statusBox = document.getElementById('emailApplyStatus');
    const cvId = document.getElementById('emailApplyCvId') ? document.getElementById('emailApplyCvId').value : null;
    const coverNote = document.getElementById('emailApplyCoverNote') ? document.getElementById('emailApplyCoverNote').value : '';

    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> {{__("Sending Application to HR...")}}';
    statusBox.style.display = 'none';

    fetch("{{ url('apply-direct-email') }}/" + slug, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ cv_id: cvId, cover_note: coverNote })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            statusBox.className = 'alert alert-success';
            statusBox.style.display = 'block';
            statusBox.innerHTML = '✓ ' + data.message;
            btn.innerHTML = '✓ {{__("Sent Successfully")}}';
            setTimeout(() => {
                $('#directEmailModal').modal('hide');
                location.reload();
            }, 2200);
        } else {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-paper-plane"></i> {{__("Confirm & Send Application")}}';
            statusBox.className = 'alert alert-danger';
            statusBox.style.display = 'block';
            statusBox.innerHTML = '✕ ' + (data.message || 'Error sending application.');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-paper-plane"></i> {{__("Confirm & Send Application")}}';
        statusBox.className = 'alert alert-danger';
        statusBox.style.display = 'block';
        statusBox.innerHTML = '✕ Server error. Please try again.';
    });
}
</script>

@include('includes.footer')
@endsection