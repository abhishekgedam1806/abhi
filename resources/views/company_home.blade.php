@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->

<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title'=>__('Employer Dashboard')])
<!-- Inner Page Title end -->

<div class="listpgWraper company-dashboard-redesign" style="background: #F8FAFC; padding: 36px 0 60px; min-height: 85vh;">
    <div class="container" style="max-width: 1320px;">
        @include('flash::message')
        <div class="row">
            
            <!-- Left Sidebar Navigation -->
            <div class="col-lg-3 col-md-4">
                <div class="dashboard-sidebar-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 14px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 20px;">
                    
                    <!-- Mobile Menu Dropdown Toggle Button (Visible ONLY on Mobile <992px) -->
                    <button type="button" class="mobile-sidebar-toggle-btn d-lg-none" onclick="toggleMobileCompanyMenu()" style="width: 100%; display: flex; align-items: center; justify-content: space-between; background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 12px; padding: 12px 14px; font-size: 14px; font-weight: 700; color: #1E293B; cursor: pointer; outline: none; transition: all 0.15s ease;">
                        <span style="display: flex; align-items: center; gap: 10px;">
                            <i class="fa fa-bars" style="color: #2563EB; font-size: 16px;"></i>
                            <span>{{__('Employer Menu')}}</span>
                        </span>
                        <i class="fa fa-chevron-down" id="mobileCompanyMenuCaret" style="color: #64748B; font-size: 13px; transition: transform 0.25s ease;"></i>
                    </button>

                    <!-- Collapsible Menu Content (Always open on desktop, collapsible on mobile) -->
                    <div id="mobileCompanyMenuContent" class="dashboard-menu-collapsible">
                        <ul class="sidebar-nav-list" style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 4px;">
                            <li class="nav-item-active">
                                <a href="{{route('company.home')}}" style="display: flex; align-items: center; gap: 12px; padding: 11px 16px; border-radius: 10px; background: #2563EB; color: #FFFFFF; font-size: 14px; font-weight: 700; text-decoration: none;">
                                    <i class="fa fa-tachometer" style="font-size: 15px; width: 18px; text-align: center;"></i>
                                    <span>{{__('Dashboard')}}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('company.profile') }}" class="sidebar-nav-link">
                                    <i class="fa fa-pencil" style="font-size: 15px; width: 18px; text-align: center; color: #64748B;"></i>
                                    <span>{{__('Edit Profile')}}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('company.detail', $company->slug) }}" class="sidebar-nav-link">
                                    <i class="fa fa-building-o" style="font-size: 15px; width: 18px; text-align: center; color: #64748B;"></i>
                                    <span>{{__('Company Public Profile')}}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('post.job') }}" class="sidebar-nav-link" style="color: #03855c !important; font-weight: 700;">
                                    <i class="fa fa-plus-circle" style="font-size: 15px; width: 18px; text-align: center; color: #03855c;"></i>
                                    <span>{{__('Post New Job')}}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('posted.jobs') }}" class="sidebar-nav-link">
                                    <i class="fa fa-briefcase" style="font-size: 15px; width: 18px; text-align: center; color: #64748B;"></i>
                                    <span>{{__('Company Jobs')}} ({{ $openJobsCount }})</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('company.messages') }}" class="sidebar-nav-link">
                                    <i class="fa fa-envelope-o" style="font-size: 15px; width: 18px; text-align: center; color: #64748B;"></i>
                                    <span>{{__('Company Messages')}}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('company.followers') }}" class="sidebar-nav-link">
                                    <i class="fa fa-users" style="font-size: 15px; width: 18px; text-align: center; color: #64748B;"></i>
                                    <span>{{__('Company Followers')}} ({{ $followersCount }})</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('company.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="sidebar-nav-link text-danger" style="color: #DC2626 !important;">
                                    <i class="fa fa-sign-out" style="font-size: 15px; width: 18px; text-align: center; color: #DC2626;"></i>
                                    <span>{{__('Logout')}}</span>
                                </a>
                                <form id="logout-form" action="{{ route('company.logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                            </li>
                        </ul>
                    </div>

                </div>

                <!-- Gradient Promo Ad Banner Card (Visible on Desktop only) -->
                <div class="sidebar-promo-banner d-none d-lg-block" style="background: #1E3A8A; border-radius: 16px; padding: 26px 20px; color: #FFFFFF; position: relative; overflow: hidden; box-shadow: 0 10px 25px rgba(37, 99, 235, 0.25); margin-bottom: 24px;">
                    <div style="position: absolute; right: -20px; top: -20px; width: 100px; height: 100px; border: 4px solid rgba(255,255,255,0.15); border-radius: 50%;"></div>
                    <div style="position: absolute; right: 15px; top: 15px; width: 24px; height: 24px; border: 3px solid #FBBF24; border-radius: 4px;"></div>
                    
                    <span style="font-size: 11px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; color: #BFDBFE; display: block; margin-bottom: 4px;">
                        HIRE TOP TALENT
                    </span>
                    <h3 style="font-size: 21px; font-weight: 900; line-height: 1.2; color: #FFFFFF; margin: 0 0 14px 0; text-transform: uppercase; letter-spacing: -0.3px;">
                        FEATURE YOUR<br>JOB OPENINGS
                    </h3>
                    <div style="font-size: 13px; color: #E0F2FE; line-height: 1.4; margin-bottom: 16px;">
                        Get 10x more verified applicants with featured postings.
                    </div>
                    <a href="{{ route('post.job') }}" style="display: inline-block; background: #F59E0B; color: #0F172A; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; padding: 9px 20px; border-radius: 9999px; text-decoration: none; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);">
                        POST JOB NOW
                    </a>
                </div>
            </div>

            <!-- Right Main Dashboard Content -->
            <div class="col-lg-9 col-md-8">
                
                <!-- 1. Hero Employer Company Profile Card -->
                <div class="profile-hero-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
                    <div class="hero-card-inner" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 24px; flex-wrap: wrap;">
                        
                        <!-- Left: Company Logo + Details -->
                        <div class="hero-left-section" style="display: flex; gap: 22px; align-items: flex-start; flex: 1; min-width: 280px;">
                            
                            <!-- Company Logo with Online Dot -->
                            <div class="hero-avatar-wrapper" style="text-align: center; flex-shrink: 0;">
                                <div style="position: relative; width: 90px; height: 90px; margin: 0 auto 8px;">
                                    <div style="width: 90px; height: 90px; border-radius: 16px; overflow: hidden; border: 3px solid #FFFFFF; box-shadow: 0 4px 12px rgba(0,0,0,0.08); background: #F8FAFC; display: flex; align-items: center; justify-content: center;">
                                        @if(!empty($company->logo))
                                            {{ $company->printCompanyImage(90, 90) }}
                                        @else
                                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #EFF6FF; color: #2563EB; font-size: 32px; font-weight: 800;">
                                                {{ strtoupper(substr($company->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <span style="position: absolute; bottom: -2px; right: -2px; width: 18px; height: 18px; background: #03855c; border: 2.5px solid #FFFFFF; border-radius: 50%; display: block;" title="{{__('Verified Employer')}}"></span>
                                </div>

                                <div class="completion-pill-badge" style="display: inline-flex; align-items: center; gap: 6px; background: #F0FDF4; border: 1px solid #BBF7D0; padding: 3px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 700; color: #166534;">
                                    <span style="color: #03855c; font-weight: 800;">&#10003;</span>
                                    <span>{{__('Verified Account')}}</span>
                                </div>
                            </div>

                            <!-- Company Details -->
                            <div class="hero-candidate-details">
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px; flex-wrap: wrap;">
                                    <h2 style="font-size: 22px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.3px;">
                                        {{ $company->name }}
                                    </h2>
                                    <span style="display: inline-flex; align-items: center; justify-content: center; background: #03855c; color: #FFFFFF; width: 18px; height: 18px; border-radius: 50%; font-size: 11px; font-weight: bold;" title="{{__('Verified Employer')}}">
                                        &#10003;
                                    </span>
                                </div>

                                <div class="hero-designation" style="font-size: 14.5px; font-weight: 700; color: #2563EB; margin-bottom: 6px;">
                                    {{ $company->getIndustry('industry') ?: __('IT & Technology Services') }}
                                    @if(!empty($company->ceo))
                                        <span style="color: #64748B; font-weight: 500; margin-left: 8px;">&bull; CEO: {{ $company->ceo }}</span>
                                    @endif
                                </div>

                                <div class="hero-location" style="font-size: 13.5px; color: #475569; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                                    <i class="fa fa-map-marker text-danger" style="font-size: 14px;"></i>
                                    <span>{{ $company->location ?: ($company->getLocation() ?: __('Location not specified')) }}</span>
                                </div>

                                <div class="hero-contact-row" style="display: flex; align-items: center; gap: 16px; font-size: 13px; color: #64748B; margin-bottom: 12px; flex-wrap: wrap;">
                                    @if(!empty($company->phone))
                                        <span style="display: inline-flex; align-items: center; gap: 5px;">
                                            <i class="fa fa-phone" style="color: #94A3B8;"></i>
                                            <span>{{ $company->phone }}</span>
                                        </span>
                                    @endif
                                    <span style="display: inline-flex; align-items: center; gap: 5px;">
                                        <i class="fa fa-envelope" style="color: #94A3B8;"></i>
                                        <span>{{ $company->email }}</span>
                                    </span>
                                    @if(!empty($company->website))
                                        <span style="display: inline-flex; align-items: center; gap: 5px;">
                                            <i class="fa fa-globe" style="color: #94A3B8;"></i>
                                            <a href="{{ $company->website }}" target="_blank" style="color: #64748B; text-decoration: none;">{{ parse_url($company->website, PHP_URL_HOST) ?: $company->website }}</a>
                                        </span>
                                    @endif
                                </div>

                                <!-- HR / Recruiter Tag -->
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="background: #F0FDF4; border: 1px solid #BBF7D0; color: #166534; font-size: 12px; font-weight: 700; padding: 3px 10px; border-radius: 20px;">
                                        <i class="fa fa-user-circle"></i> HR: {{ $company->getHrName() }}
                                    </span>
                                    @if($company->canCallHr())
                                        <span style="background: #EFF6FF; border: 1px solid #BFDBFE; color: #1D4ED8; font-size: 11.5px; font-weight: 600; padding: 3px 8px; border-radius: 6px;">
                                            <i class="fa fa-phone"></i> Direct Call ON
                                        </span>
                                    @endif
                                    @if($company->canWhatsappHr())
                                        <span style="background: #ECFDF5; border: 1px solid #A7F3D0; color: #047857; font-size: 11.5px; font-weight: 600; padding: 3px 8px; border-radius: 6px;">
                                            <i class="fa fa-whatsapp"></i> WhatsApp ON
                                        </span>
                                    @endif
                                </div>

                            </div>
                        </div>

                        <!-- Right: Action Buttons (Post Job, Edit Profile, Public Profile) -->
                        <div class="hero-right-actions" style="display: flex; flex-direction: column; gap: 10px; min-width: 170px;">
                            <a href="{{ route('post.job') }}" class="btn-hero-action btn-hero-edit" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: #2563EB; color: #FFFFFF; font-size: 13.5px; font-weight: 700; padding: 10px 18px; border-radius: 10px; text-decoration: none; box-shadow: 0 2px 6px rgba(37,99,235,0.25); transition: all 0.15s ease;">
                                <i class="fa fa-plus-circle"></i>
                                <span>{{__('Post New Job')}}</span>
                            </a>

                            <a href="{{ route('company.profile') }}" class="btn-hero-action btn-hero-secondary" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: #FFFFFF; border: 1.5px solid #E2E8F0; color: #334155; font-size: 13px; font-weight: 600; padding: 9px 16px; border-radius: 10px; text-decoration: none; transition: all 0.15s ease;">
                                <i class="fa fa-pencil" style="color: #64748B;"></i>
                                <span>{{__('Edit Profile')}}</span>
                            </a>

                            <a href="{{ route('company.detail', $company->slug) }}" target="_blank" class="btn-hero-action btn-hero-secondary" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: #FFFFFF; border: 1.5px solid #E2E8F0; color: #334155; font-size: 13px; font-weight: 600; padding: 9px 16px; border-radius: 10px; text-decoration: none; transition: all 0.15s ease;">
                                <i class="fa fa-external-link" style="color: #64748B;"></i>
                                <span>{{__('Public Profile')}}</span>
                            </a>
                        </div>

                    </div>
                </div>

                <!-- 2. Metrics Statistics Row (5 Cards in 1 Row Matching Candidate Dashboard) -->
                <div class="metrics-stat-grid" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 14px; margin-bottom: 24px;">
                    
                    <!-- Stat 1: Open Jobs -->
                    <a href="{{ route('posted.jobs') }}" class="stat-card stat-link" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 18px 14px; text-align: center; text-decoration: none; box-shadow: 0 2px 8px rgba(0,0,0,0.02); display: block;">
                        <div style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background: #EFF6FF; color: #2563EB; font-size: 15px; margin-bottom: 8px;">
                            <i class="fa fa-briefcase"></i>
                        </div>
                        <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 2px;">{{__('Open Jobs')}}</div>
                        <div style="font-size: 22px; font-weight: 900; color: #0F172A; line-height: 1.1; margin-bottom: 2px;">{{ $openJobsCount }}</div>
                        <div style="font-size: 11px; color: #94A3B8; font-weight: 500;">{{__('Active Postings')}}</div>
                    </a>

                    <!-- Stat 2: Total Applications -->
                    <a href="{{ route('posted.jobs') }}" class="stat-card stat-link" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 18px 14px; text-align: center; text-decoration: none; box-shadow: 0 2px 8px rgba(0,0,0,0.02); display: block;">
                        <div style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background: #F3E8FF; color: #7C3AED; font-size: 15px; margin-bottom: 8px;">
                            <i class="fa fa-users"></i>
                        </div>
                        <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 2px;">{{__('Applications')}}</div>
                        <div style="font-size: 22px; font-weight: 900; color: #0F172A; line-height: 1.1; margin-bottom: 2px;">{{ $totalApplicantsCount }}</div>
                        <div style="font-size: 11px; color: #94A3B8; font-weight: 500;">{{__('Total Candidates')}}</div>
                    </a>

                    <!-- Stat 3: Shortlisted -->
                    <a href="{{ route('posted.jobs') }}" class="stat-card stat-link" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 18px 14px; text-align: center; text-decoration: none; box-shadow: 0 2px 8px rgba(0,0,0,0.02); display: block;">
                        <div style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background: #FEF3C7; color: #D97706; font-size: 15px; margin-bottom: 8px;">
                            <i class="fa fa-star"></i>
                        </div>
                        <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 2px;">{{__('Shortlisted')}}</div>
                        <div style="font-size: 22px; font-weight: 900; color: #0F172A; line-height: 1.1; margin-bottom: 2px;">{{ $shortlistedCount }}</div>
                        <div style="font-size: 11px; color: #94A3B8; font-weight: 500;">{{__('Shortlisted')}}</div>
                    </a>

                    <!-- Stat 4: Messages -->
                    <a href="{{ route('company.messages') }}" class="stat-card stat-link" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 18px 14px; text-align: center; text-decoration: none; box-shadow: 0 2px 8px rgba(0,0,0,0.02); display: block;">
                        <div style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background: #E0E7FF; color: #4F46E5; font-size: 15px; margin-bottom: 8px;">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 2px;">{{__('Messages')}}</div>
                        <div style="font-size: 22px; font-weight: 900; color: #0F172A; line-height: 1.1; margin-bottom: 2px;">{{ $messagesCount }}</div>
                        <div style="font-size: 11px; color: #94A3B8; font-weight: 500;">{{__('Unread Messages')}}</div>
                    </a>

                    <!-- Stat 5: Followers -->
                    <a href="{{ route('company.followers') }}" class="stat-card stat-link" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 18px 14px; text-align: center; text-decoration: none; box-shadow: 0 2px 8px rgba(0,0,0,0.02); display: block;">
                        <div style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background: #FCE7F3; color: #DB2777; font-size: 15px; margin-bottom: 8px;">
                            <i class="fa fa-heart"></i>
                        </div>
                        <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 2px;">{{__('Followers')}}</div>
                        <div style="font-size: 22px; font-weight: 900; color: #0F172A; line-height: 1.1; margin-bottom: 2px;">{{ $followersCount }}</div>
                        <div style="font-size: 11px; color: #94A3B8; font-weight: 500;">{{__('Followers')}}</div>
                    </a>

                </div>

                <!-- 3. Two-Column Content Grid -->
                <div class="row">
                    
                    <!-- Left Column: Recent Posted Jobs & Recent Applicants -->
                    <div class="col-lg-7">
                        
                        <!-- Recent Posted Jobs Card -->
                        <div class="dashboard-section-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 22px 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
                                <h3 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa fa-briefcase" style="color: #2563EB; font-size: 16px;"></i>
                                    <span>{{__('Recent Posted Jobs')}}</span>
                                </h3>
                                <a href="{{ route('post.job') }}" style="font-size: 13px; font-weight: 700; color: #2563EB; text-decoration: none;">
                                    + {{__('Post Job')}}
                                </a>
                            </div>

                            @if(isset($recentJobs) && count($recentJobs))
                                <div class="posted-jobs-list" style="display: flex; flex-direction: column; gap: 14px;">
                                    @foreach($recentJobs as $job)
                                        @php $appCount = $job->appliedUsers()->count(); @endphp
                                        <div class="posted-job-item" style="display: flex; align-items: center; justify-content: space-between; gap: 14px; padding: 14px 16px; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; transition: all 0.15s ease;">
                                            <div style="flex: 1; min-width: 0;">
                                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 3px; flex-wrap: wrap;">
                                                    <h4 style="font-size: 14.5px; font-weight: 800; color: #0F172A; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                        <a href="{{ route('job.detail', [$job->slug]) }}" style="color: #0F172A; text-decoration: none;">
                                                            {{ $job->title }}
                                                        </a>
                                                    </h4>
                                                    <span style="font-size: 11px; font-weight: 700; color: {{ $job->is_active ? '#03855c' : '#DC2626' }}; background: {{ $job->is_active ? '#ECFDF5' : '#FEF2F2' }}; border: 1px solid {{ $job->is_active ? '#A7F3D0' : '#FECACA' }}; padding: 1px 7px; border-radius: 4px;">
                                                        {{ $job->is_active ? __('Active') : __('Inactive') }}
                                                    </span>
                                                </div>
                                                <div style="font-size: 12px; color: #64748B; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                                                    <span><i class="fa fa-map-marker text-danger"></i> {{ $job->getCity('city') ?: 'Nagpur' }}</span>
                                                    <span><i class="fa fa-clock-o"></i> {{ $job->created_at ? $job->created_at->diffForHumans() : '' }}</span>
                                                </div>
                                            </div>

                                            <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;">
                                                <a href="{{ route('list.applied.users', [$job->id]) }}" style="display: inline-flex; align-items: center; gap: 5px; background: #EFF6FF; border: 1px solid #BFDBFE; color: #1D4ED8; font-size: 12px; font-weight: 700; padding: 6px 12px; border-radius: 8px; text-decoration: none;">
                                                    <i class="fa fa-users"></i> {{ $appCount }} {{__('Applicants')}}
                                                </a>
                                                <a href="{{ route('edit.front.job', [$job->id]) }}" style="display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; background: #FFFFFF; border: 1px solid #CBD5E1; color: #64748B; border-radius: 8px; text-decoration: none;" title="{{__('Edit Job')}}">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div style="padding: 24px; text-align: center; color: #64748B; font-size: 13.5px; background: #F8FAFC; border-radius: 12px; border: 1px dashed #CBD5E1;">
                                    <div style="font-size: 24px; margin-bottom: 6px;">💼</div>
                                    <p style="margin-bottom: 10px; font-weight: 600;">{{__('You have not posted any jobs yet.')}}</p>
                                    <a href="{{ route('post.job') }}" style="display: inline-block; background: #2563EB; color: #FFFFFF; font-size: 12.5px; font-weight: 700; padding: 8px 16px; border-radius: 8px; text-decoration: none;">
                                        + {{__('Post Your First Job')}}
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Recent Candidate Applications Card -->
                        <div class="dashboard-section-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 22px 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                                <h3 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa fa-users" style="color: #7C3AED; font-size: 16px;"></i>
                                    <span>{{__('Recent Candidate Applications')}}</span>
                                </h3>
                                <a href="{{ route('posted.jobs') }}" style="font-size: 13px; font-weight: 700; color: #2563EB; text-decoration: none;">
                                    {{__('View All')}}
                                </a>
                            </div>

                            @if(isset($recentApplicants) && count($recentApplicants))
                                <div class="recent-applicants-list" style="display: flex; flex-direction: column; gap: 12px;">
                                    @foreach($recentApplicants as $appl)
                                        @php $appUser = $appl->getUser(); $appJob = $appl->getJob(); @endphp
                                        @if($appUser && $appJob)
                                            <div class="applicant-item-card" style="display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 12px 14px; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px;">
                                                <div style="display: flex; align-items: center; gap: 12px; flex: 1; min-width: 0;">
                                                    <div style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden; background: #EEF2F6; border: 1.5px solid #CBD5E1; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #2563EB; flex-shrink: 0;">
                                                        @if(!empty($appUser->image))
                                                            {{ ImgUploader::print_image("user_images/$appUser->image", 40, 40) }}
                                                        @else
                                                            {{ strtoupper(substr($appUser->getName(), 0, 1)) }}
                                                        @endif
                                                    </div>
                                                    <div style="min-width: 0; flex: 1;">
                                                        <h5 style="font-size: 14px; font-weight: 800; color: #0F172A; margin: 0 0 2px 0;">
                                                            {{ $appUser->getName() }}
                                                        </h5>
                                                        <div style="font-size: 12px; color: #2563EB; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                            {{ $appJob->title }}
                                                        </div>
                                                        <div style="font-size: 11px; color: #94A3B8;">
                                                            {{ $appl->created_at ? $appl->created_at->diffForHumans() : '' }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div style="flex-shrink: 0; display: flex; align-items: center; gap: 8px;">
                                                    <span style="font-size: 11.5px; font-weight: 700; color: {{ $appl->status == 'shortlisted' ? '#03855c' : '#3B82F6' }}; background: {{ $appl->status == 'shortlisted' ? '#ECFDF5' : '#EFF6FF' }}; border: 1px solid {{ $appl->status == 'shortlisted' ? '#A7F3D0' : '#DBEAFE' }}; padding: 3px 8px; border-radius: 6px;">
                                                        {{ ucfirst($appl->status ?: 'Applied') }}
                                                    </span>
                                                    <a href="{{ route('applicant.profile', [$appl->id]) }}" style="font-size: 12px; font-weight: 700; color: #2563EB; text-decoration: none; padding: 4px 8px; border-radius: 6px; background: #FFFFFF; border: 1px solid #CBD5E1;">
                                                        {{__('View')}}
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div style="padding: 20px; text-align: center; color: #64748B; font-size: 13px; background: #F8FAFC; border-radius: 12px;">
                                    {{__('No applications received yet.')}}
                                </div>
                            @endif
                        </div>

                    </div>

                    <!-- Right Column: HR Contact Settings & Subscription Status -->
                    <div class="col-lg-5">
                        
                        <!-- HR & Direct Contact Widget -->
                        <div class="dashboard-section-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 22px 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
                            <h3 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0 0 14px 0; display: flex; align-items: center; gap: 8px;">
                                <i class="fa fa-address-card-o" style="color: #03855c; font-size: 16px;"></i>
                                <span>{{__('HR Contact System')}}</span>
                            </h3>

                            <div style="background: #F0FDF4; border: 1px solid #DCFCE7; border-radius: 12px; padding: 14px; margin-bottom: 16px;">
                                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #03855c; color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: bold;">
                                        HR
                                    </div>
                                    <div>
                                        <div style="font-size: 14px; font-weight: 800; color: #0F172A;">{{ $company->getHrName() }}</div>
                                        <div style="font-size: 11.5px; color: #03855c; font-weight: 600;">{{__('Official Contact for Applicants')}}</div>
                                    </div>
                                </div>
                                <div style="font-size: 12.5px; color: #374151; line-height: 1.4;">
                                    Candidates can reach your hiring manager directly through <strong>Call HR</strong> and <strong>WhatsApp HR</strong> buttons.
                                </div>
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px; font-size: 13px; font-weight: 600; color: #475569;">
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; background: #F8FAFC; border-radius: 8px;">
                                    <span><i class="fa fa-phone" style="color: #2563EB;"></i> {{__('Direct Call HR')}}</span>
                                    <span style="color: {{ $company->canCallHr() ? '#03855c' : '#DC2626' }}; font-weight: 800;">
                                        {{ $company->canCallHr() ? __('ENABLED') : __('DISABLED') }}
                                    </span>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; background: #F8FAFC; border-radius: 8px;">
                                    <span><i class="fa fa-whatsapp" style="color: #03855c;"></i> {{__('WhatsApp HR')}}</span>
                                    <span style="color: {{ $company->canWhatsappHr() ? '#03855c' : '#DC2626' }}; font-weight: 800;">
                                        {{ $company->canWhatsappHr() ? __('ENABLED') : __('DISABLED') }}
                                    </span>
                                </div>
                            </div>

                            <a href="{{ route('company.profile') }}" style="display: block; width: 100%; text-align: center; background: #FFFFFF; border: 1.5px solid #2563EB; color: #2563EB; font-size: 13.5px; font-weight: 700; padding: 9px; border-radius: 10px; text-decoration: none; transition: all 0.15s ease;">
                                {{__('Manage HR Settings')}}
                            </a>
                        </div>

                        <!-- Package & Membership Widget (Modern SaaS Design) -->
                        @if((bool)config('company.is_company_package_active'))
                            @php        
                                $packages = App\Package::where('package_for', 'like', 'employer')->get();
                                $package = Auth::guard('company')->user()->getPackage();
                                $companyUser = Auth::guard('company')->user();
                            @endphp
                            
                            <div class="dashboard-section-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 22px 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                                    <h3 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0; display: flex; align-items: center; gap: 8px;">
                                        <i class="fa fa-gift" style="color: #F59E0B; font-size: 16px;"></i>
                                        <span>{{__('Employer Package')}}</span>
                                    </h3>
                                    @if($package)
                                        <span style="font-size: 11px; font-weight: 800; color: #03855c; background: #ECFDF5; border: 1px solid #A7F3D0; padding: 2px 8px; border-radius: 6px;">
                                            {{__('ACTIVE PLAN')}}
                                        </span>
                                    @endif
                                </div>

                                @if(null !== $package)
                                    <!-- Current Active Plan Card -->
                                    <div style="background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 16px; margin-bottom: 16px;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                            <div style="font-size: 15px; font-weight: 800; color: #0F172A;">
                                                {{ $package->package_title }}
                                            </div>
                                            <div style="font-size: 14px; font-weight: 800; color: #2563EB;">
                                                {{ $siteSetting->default_currency_code }}{{ $package->package_price }}
                                            </div>
                                        </div>

                                        <!-- Quota Progress Bar -->
                                        @php
                                            $totalQuota = max(1, (int)$companyUser->jobs_quota);
                                            $availedQuota = (int)$companyUser->availed_jobs_quota;
                                            $quotaPercent = min(100, round(($availedQuota / $totalQuota) * 100));
                                        @endphp
                                        <div style="margin-bottom: 12px;">
                                            <div style="display: flex; justify-content: space-between; font-size: 12px; font-weight: 600; color: #64748B; margin-bottom: 4px;">
                                                <span>{{__('Job Posts Quota')}}</span>
                                                <span style="color: #0F172A; font-weight: 700;">{{ $availedQuota }} / {{ $totalQuota }} Used</span>
                                            </div>
                                            <div style="height: 7px; width: 100%; background: #E2E8F0; border-radius: 10px; overflow: hidden;">
                                                <div style="height: 100%; width: {{ $quotaPercent }}%; background: {{ $quotaPercent >= 100 ? '#EF4444' : '#2563EB' }}; border-radius: 10px;"></div>
                                            </div>
                                        </div>

                                        <!-- Validity Dates -->
                                        <div style="font-size: 12px; color: #64748B; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #E2E8F0; padding-top: 10px;">
                                            <span><i class="fa fa-calendar-check-o text-success"></i> {{ $companyUser->package_start_date ? (is_string($companyUser->package_start_date) ? date('d M, Y', strtotime($companyUser->package_start_date)) : $companyUser->package_start_date->format('d M, Y')) : 'N/A' }}</span>
                                            <span>&rarr;</span>
                                            <span><i class="fa fa-calendar-times-o text-danger"></i> {{ $companyUser->package_end_date ? (is_string($companyUser->package_end_date) ? date('d M, Y', strtotime($companyUser->package_end_date)) : $companyUser->package_end_date->format('d M, Y')) : 'N/A' }}</span>
                                        </div>
                                    </div>

                                    <!-- Upgrade Plan Section if other packages exist -->
                                    @php
                                        $upgradePackages = App\Package::where('package_for', 'like', 'employer')
                                            ->where('id', '<>', $package->id)
                                            ->where('package_price', '>=', $package->package_price)
                                            ->get();
                                    @endphp

                                    @if($upgradePackages->count() > 0)
                                        <div style="margin-top: 14px;">
                                            <div style="font-size: 13px; font-weight: 700; color: #1E293B; margin-bottom: 10px;">
                                                {{__('Upgrade Your Hiring Plan')}}:
                                            </div>
                                            <div style="display: flex; flex-direction: column; gap: 10px;">
                                                @foreach($upgradePackages as $upkg)
                                                    <div style="background: #FFFFFF; border: 1px solid #CBD5E1; border-radius: 12px; padding: 12px 14px; display: flex; flex-direction: column; gap: 8px;">
                                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                                            <div>
                                                                <div style="font-size: 13.5px; font-weight: 800; color: #0F172A;">{{ $upkg->package_title }}</div>
                                                                <div style="font-size: 11.5px; color: #64748B;">{{ $upkg->package_num_listings }} Jobs &bull; {{ $upkg->package_num_days }} Days</div>
                                                            </div>
                                                            <div style="font-size: 14px; font-weight: 900; color: #03855c;">
                                                                {{ $siteSetting->default_currency_code }}{{ $upkg->package_price }}
                                                            </div>
                                                        </div>

                                                        <!-- Payment Options Buttons -->
                                                        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                                            @if((bool)($siteSetting->is_razorpay_active ?? 1))
                                                                <a href="{{route('payment.checkout', $upkg->id)}}" style="font-size: 11px; font-weight: 700; color: #FFFFFF; background: #2563EB; border: 1px solid #1D4ED8; padding: 4px 10px; border-radius: 6px; text-decoration: none;">
                                                                    <i class="fa fa-bolt"></i> Razorpay / UPI
                                                                </a>
                                                            @endif
                                                            @if((bool)$siteSetting->is_paypal_active)
                                                                <a href="{{route('order.upgrade.package', $upkg->id)}}" style="font-size: 11px; font-weight: 700; color: #003087; background: #E0F2FE; border: 1px solid #BAE6FD; padding: 4px 8px; border-radius: 6px; text-decoration: none;">
                                                                    <i class="fa fa-paypal"></i> PayPal
                                                                </a>
                                                            @endif
                                                            @if((bool)$siteSetting->is_stripe_active)
                                                                <a href="{{route('stripe.order.form', [$upkg->id, 'upgrade'])}}" style="font-size: 11px; font-weight: 700; color: #4338CA; background: #EEF2FF; border: 1px solid #C7D2FE; padding: 4px 8px; border-radius: 6px; text-decoration: none;">
                                                                    <i class="fa fa-cc-stripe"></i> Stripe
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                @elseif(isset($packages) && $packages->count() > 0)
                                    <!-- No Active Package -> Available Packages List -->
                                    <div style="display: flex; flex-direction: column; gap: 10px;">
                                        @foreach($packages as $npkg)
                                            <div style="background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 12px; padding: 14px;">
                                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                                    <span style="font-size: 14px; font-weight: 800; color: #0F172A;">{{ $npkg->package_title }}</span>
                                                    <span style="font-size: 14px; font-weight: 900; color: #2563EB;">
                                                        {{ $npkg->package_price > 0 ? '₹' . number_format($npkg->package_price, 2) : __('Free') }}
                                                    </span>
                                                </div>
                                                <div style="font-size: 12px; color: #64748B; margin-bottom: 10px;">
                                                    {{__('Post')}} <strong>{{ $npkg->package_num_listings }} {{__('Jobs')}}</strong> &bull; {{ $npkg->package_num_days }} {{__('Days Duration')}}
                                                </div>

                                                <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                                    @if($npkg->package_price > 0)
                                                        @if((bool)($siteSetting->is_razorpay_active ?? 1))
                                                            <a href="{{route('payment.checkout', $npkg->id)}}" style="font-size: 11.5px; font-weight: 700; color: #FFFFFF; background: #2563EB; border: 1px solid #1D4ED8; padding: 5px 12px; border-radius: 6px; text-decoration: none;">
                                                                <i class="fa fa-bolt"></i> {{__('Pay with Razorpay / UPI')}}
                                                            </a>
                                                        @endif
                                                        @if((bool)$siteSetting->is_paypal_active)
                                                            <a href="{{route('order.package', $npkg->id)}}" style="font-size: 11.5px; font-weight: 700; color: #003087; background: #E0F2FE; border: 1px solid #BAE6FD; padding: 5px 10px; border-radius: 6px; text-decoration: none;">
                                                                <i class="fa fa-paypal"></i> PayPal
                                                            </a>
                                                        @endif
                                                        @if((bool)$siteSetting->is_stripe_active)
                                                            <a href="{{route('stripe.order.form', [$npkg->id, 'new'])}}" style="font-size: 11.5px; font-weight: 700; color: #4338CA; background: #EEF2FF; border: 1px solid #C7D2FE; padding: 5px 10px; border-radius: 6px; text-decoration: none;">
                                                                <i class="fa fa-cc-stripe"></i> Stripe
                                                            </a>
                                                        @endif
                                                    @else
                                                        <a href="{{route('order.free.package', $npkg->id)}}" style="font-size: 12px; font-weight: 700; color: #03855c; background: #ECFDF5; border: 1px solid #A7F3D0; padding: 6px 12px; border-radius: 6px; text-decoration: none; display: inline-block;">
                                                            {{__('Subscribe Free Plan')}}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

<style>
.company-dashboard-redesign .sidebar-nav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 16px;
    border-radius: 10px;
    color: #475569;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.15s ease;
}
.company-dashboard-redesign .sidebar-nav-link:hover {
    background: #F1F5F9;
    color: #0F172A;
}
.company-dashboard-redesign .btn-hero-edit:hover {
    background: #1D4ED8 !important;
    box-shadow: 0 4px 12px rgba(37,99,235,0.35) !important;
    transform: translateY(-1px);
}
.company-dashboard-redesign .btn-hero-secondary:hover {
    background: #F8FAFC !important;
    border-color: #CBD5E1 !important;
    transform: translateY(-1px);
}
.company-dashboard-redesign .stat-card {
    transition: all 0.15s ease;
}
.company-dashboard-redesign .stat-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.05) !important;
    border-color: #CBD5E1 !important;
}
.company-dashboard-redesign .posted-job-item:hover,
.company-dashboard-redesign .applicant-item-card:hover {
    background: #EFF6FF !important;
    border-color: #BFDBFE !important;
}

/* Tablet & Mobile Responsiveness */
@media (max-width: 991px) {
    .dashboard-menu-collapsible {
        display: none;
        padding-top: 10px;
    }
    .dashboard-menu-collapsible.show {
        display: block !important;
    }
    .dashboard-sidebar-card {
        padding: 10px !important;
        margin-bottom: 16px !important;
    }
    .metrics-stat-grid {
        grid-template-columns: repeat(3, 1fr) !important;
    }
}
@media (min-width: 992px) {
    .mobile-sidebar-toggle-btn {
        display: none !important;
    }
    .dashboard-menu-collapsible {
        display: block !important;
    }
}
@media (max-width: 767px) {
    .hero-card-inner {
        flex-direction: column !important;
        align-items: center !important;
        text-align: center !important;
    }
    .hero-left-section {
        flex-direction: column !important;
        align-items: center !important;
        text-align: center !important;
    }
    .hero-candidate-details {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .hero-location, .hero-contact-row {
        justify-content: center !important;
    }
    .hero-right-actions {
        width: 100% !important;
    }
    .metrics-stat-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}
</style>

@include('includes.footer')
@endsection

@push('scripts')
@include('includes.immediate_available_btn')
<script>
function toggleMobileCompanyMenu() {
    var menu = document.getElementById('mobileCompanyMenuContent');
    var caret = document.getElementById('mobileCompanyMenuCaret');
    if (menu) {
        if (menu.classList.contains('show')) {
            menu.classList.remove('show');
            if (caret) caret.style.transform = 'rotate(0deg)';
        } else {
            menu.classList.add('show');
            if (caret) caret.style.transform = 'rotate(180deg)';
        }
    }
}
</script>
@endpush
