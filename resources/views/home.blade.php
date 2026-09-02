@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->

<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title'=>__('Dashboard')])
<!-- Inner Page Title end -->

<div class="listpgWraper user-dashboard-redesign" style="background: #F8FAFC; padding: 36px 0 60px; min-height: 85vh;">
    <div class="container" style="max-width: 1320px;">
        @include('flash::message')
        <div class="row">
            
            <!-- Left Sidebar Navigation -->
            <div class="col-lg-3 col-md-4">
                <div class="dashboard-sidebar-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 14px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 20px;">
                    
                    <!-- Mobile Menu Dropdown Toggle Button (Visible ONLY on Mobile <992px) -->
                    <button type="button" class="mobile-sidebar-toggle-btn d-lg-none" onclick="toggleMobileDashboardMenu()" style="width: 100%; display: flex; align-items: center; justify-content: space-between; background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 12px; padding: 12px 14px; font-size: 14px; font-weight: 700; color: #1E293B; cursor: pointer; outline: none; transition: all 0.15s ease;">
                        <span style="display: flex; align-items: center; gap: 10px;">
                            <i class="fa fa-bars" style="color: #2563EB; font-size: 16px;"></i>
                            <span>{{__('Dashboard Menu')}}</span>
                        </span>
                        <i class="fa fa-chevron-down" id="mobileMenuCaret" style="color: #64748B; font-size: 13px; transition: transform 0.25s ease;"></i>
                    </button>

                    <!-- Collapsible Menu Content (Always open on desktop, collapsible on mobile) -->
                    <div id="mobileDashboardMenuContent" class="dashboard-menu-collapsible">
                        <!-- Immediate Available Toggle -->
                        <div class="sidebar-switch-box" style="padding: 12px 14px; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; margin-bottom: 14px; display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 13px; font-weight: 700; color: #1E293B;">{{__('Immediate Available')}}</span>
                            <label class="switch switch-green" style="margin: 0;">
                                @php $checked = ((bool)Auth::user()->is_immediate_available) ? 'checked="checked"' : ''; @endphp
                                <input type="checkbox" name="is_immediate_available" id="is_immediate_available" class="switch-input" {{$checked}} onchange="changeImmediateAvailableStatus({{Auth::user()->id}}, {{Auth::user()->is_immediate_available}});">
                                <span class="switch-label" data-on="On" data-off="Off"></span>
                                <span class="switch-handle"></span>
                            </label>
                        </div>

                        <!-- Sidebar Navigation Items -->
                        <ul class="sidebar-nav-list" style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 4px;">
                            <li class="nav-item-active">
                                <a href="{{route('home')}}" style="display: flex; align-items: center; gap: 12px; padding: 11px 16px; border-radius: 10px; background: #2563EB; color: #FFFFFF; font-size: 14px; font-weight: 700; text-decoration: none;">
                                    <i class="fa fa-tachometer" style="font-size: 15px; width: 18px; text-align: center;"></i>
                                    <span>{{__('Dashboard')}}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('my.profile') }}" class="sidebar-nav-link">
                                    <i class="fa fa-pencil" style="font-size: 15px; width: 18px; text-align: center; color: #64748B;"></i>
                                    <span>{{__('Edit Profile')}}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('view.public.profile', Auth::user()->id) }}" class="sidebar-nav-link">
                                    <i class="fa fa-eye" style="font-size: 15px; width: 18px; text-align: center; color: #64748B;"></i>
                                    <span>{{__('View Public Profile')}}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('my.job.applications') }}" class="sidebar-nav-link">
                                    <i class="fa fa-desktop" style="font-size: 15px; width: 18px; text-align: center; color: #64748B;"></i>
                                    <span>{{__('My Applications')}}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('my.job.applications', ['tab' => 'invites']) }}" class="sidebar-nav-link">
                                    <i class="fa fa-bullhorn" style="font-size: 15px; width: 18px; text-align: center; color: #64748B;"></i>
                                    <span>{{__('Interview Invites')}}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('my.favourite.jobs') }}" class="sidebar-nav-link">
                                    <i class="fa fa-heart" style="font-size: 15px; width: 18px; text-align: center; color: #64748B;"></i>
                                    <span>{{__('My Favourite Jobs')}}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('my-alerts') }}" class="sidebar-nav-link">
                                    <i class="fa fa-bell" style="font-size: 15px; width: 18px; text-align: center; color: #64748B;"></i>
                                    <span>{{__('Job Alerts')}}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{url('my-profile#cvs')}}" class="sidebar-nav-link">
                                    <i class="fa fa-file-text" style="font-size: 15px; width: 18px; text-align: center; color: #64748B;"></i>
                                    <span>{{__('Manage Resume')}}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{route('my.messages')}}" class="sidebar-nav-link">
                                    <i class="fa fa-envelope-o" style="font-size: 15px; width: 18px; text-align: center; color: #64748B;"></i>
                                    <span>{{__('My Messages')}}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{route('my.followings')}}" class="sidebar-nav-link">
                                    <i class="fa fa-user-o" style="font-size: 15px; width: 18px; text-align: center; color: #64748B;"></i>
                                    <span>{{__('My Followings')}}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('my.profile') }}" class="sidebar-nav-link">
                                    <i class="fa fa-cog" style="font-size: 15px; width: 18px; text-align: center; color: #64748B;"></i>
                                    <span>{{__('Account Settings')}}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="sidebar-nav-link text-danger" style="color: #DC2626 !important;">
                                    <i class="fa fa-sign-out" style="font-size: 15px; width: 18px; text-align: center; color: #DC2626;"></i>
                                    <span>{{__('Logout')}}</span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                            </li>
                        </ul>
                    </div>

                </div>

                <!-- Gradient Promo Ad Banner Card (Visible on Desktop only) -->
                <div class="sidebar-promo-banner d-none d-lg-block" style="background: #7C3AED; border-radius: 16px; padding: 26px 20px; color: #FFFFFF; position: relative; overflow: hidden; box-shadow: 0 10px 25px rgba(124, 58, 237, 0.25); margin-bottom: 24px;">
                    <div style="position: absolute; right: -20px; top: -20px; width: 100px; height: 100px; border: 4px solid rgba(255,255,255,0.15); border-radius: 50%;"></div>
                    <div style="position: absolute; right: 15px; top: 15px; width: 24px; height: 24px; border: 3px solid #FBBF24; border-radius: 4px;"></div>
                    
                    <span style="font-size: 11px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; color: #DDD6FE; display: block; margin-bottom: 4px;">
                        ADVERTISE
                    </span>
                    <h3 style="font-size: 22px; font-weight: 900; line-height: 1.15; color: #FFFFFF; margin: 0 0 14px 0; text-transform: uppercase; letter-spacing: -0.3px;">
                        YOUR<br>BUSINESS<br>HERE
                    </h3>
                    <div style="font-size: 24px; font-weight: 900; color: #FFFFFF; line-height: 1; margin-bottom: 2px;">
                        $200
                    </div>
                    <span style="font-size: 11px; font-weight: 700; color: #E9D5FF; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 16px;">
                        PER MONTH
                    </span>
                    <a href="{{ route('contact.us') }}" style="display: inline-block; background: #EC4899; color: #FFFFFF; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; padding: 9px 20px; border-radius: 9999px; text-decoration: none; box-shadow: 0 4px 12px rgba(236, 72, 153, 0.4);">
                        CONTACT NOW
                    </a>
                </div>
            </div>

            <!-- Right Main Dashboard Content -->
            <div class="col-lg-9 col-md-8">
                
                <!-- 1. Hero Candidate Profile Card (Matching Screenshot) -->
                <div class="profile-hero-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
                    <div class="hero-card-inner" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 24px; flex-wrap: wrap;">
                        
                        <!-- Left: Avatar + Details -->
                        <div class="hero-left-section" style="display: flex; gap: 22px; align-items: flex-start; flex: 1; min-width: 280px;">
                            
                            <!-- Profile Avatar with Online Dot & Completion Ring -->
                            <div class="hero-avatar-wrapper" style="text-align: center; flex-shrink: 0;">
                                <div style="position: relative; width: 90px; height: 90px; margin: 0 auto 8px;">
                                    <div style="width: 90px; height: 90px; border-radius: 50%; overflow: hidden; border: 3px solid #FFFFFF; box-shadow: 0 4px 12px rgba(0,0,0,0.08); background: #F1F5F9;">
                                        @if(!empty($user->image) && file_exists(public_path('user_images/' . $user->image)))
                                            <img src="{{ asset('user_images/' . $user->image) }}" alt="{{ $user->getName() }}" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                        @else
                                            @php
                                                $displayName = $user->getName();
                                                $initial = !empty($displayName) ? strtoupper(mb_substr(trim($displayName), 0, 1)) : 'U';
                                            @endphp
                                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #2563EB; color: #FFFFFF; font-size: 34px; font-weight: 800; text-transform: uppercase;">
                                                {{ $initial }}
                                            </div>
                                        @endif
                                    </div>
                                    <!-- Online Status Badge -->
                                    <span style="position: absolute; bottom: 4px; right: 4px; width: 18px; height: 18px; background: #03855c; border: 2.5px solid #FFFFFF; border-radius: 50%; display: block;" title="{{__('Online')}}"></span>
                                </div>

                                <!-- Progress Percentage Pill Badge -->
                                <div class="completion-pill-badge" style="display: inline-flex; align-items: center; gap: 6px; background: #F0FDF4; border: 1px solid #BBF7D0; padding: 3px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 700; color: #166534;">
                                    <span style="color: #03855c; font-weight: 800;">{{ $strengthScore }}%</span>
                                    <span style="color: #374151; font-weight: 600;">{{__('Profile Complete')}}</span>
                                </div>
                            </div>

                            <!-- Candidate Details -->
                            <div class="hero-candidate-details">
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px; flex-wrap: wrap;">
                                    <h2 style="font-size: 22px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.3px;">
                                        {{ $user->getName() }}
                                    </h2>
                                    <span style="display: inline-flex; align-items: center; justify-content: center; background: #03855c; color: #FFFFFF; width: 18px; height: 18px; border-radius: 50%; font-size: 11px; font-weight: bold;" title="{{__('Verified Candidate')}}">
                                        &#10003;
                                    </span>
                                </div>

                                <div class="hero-designation" style="font-size: 15px; font-weight: 700; margin-bottom: 8px;">
                                    @if(!empty($designation))
                                        <span style="color: #2563EB;">{{ $designation }}</span>
                                    @else
                                        <a href="{{ route('my.profile') }}" style="color: #2563EB; font-weight: 600; text-decoration: none; font-size: 14px;">
                                            + {{__('Add your job title')}}
                                        </a>
                                    @endif
                                </div>

                                <div class="hero-location" style="font-size: 13.5px; color: #475569; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                                    <i class="fa fa-map-marker text-danger" style="font-size: 14px;"></i>
                                    @if($user->getLocation())
                                        <span>{{ $user->getLocation() }}</span>
                                    @else
                                        <a href="{{ route('my.profile') }}" style="color: #64748B; text-decoration: none;">
                                            + {{__('Add your location')}}
                                        </a>
                                    @endif
                                </div>

                                <div class="hero-contact-row" style="display: flex; align-items: center; gap: 16px; font-size: 13px; color: #64748B; margin-bottom: 14px; flex-wrap: wrap;">
                                    <span style="display: inline-flex; align-items: center; gap: 5px;">
                                        <i class="fa fa-phone" style="color: #94A3B8;"></i>
                                        @if(!empty($user->phone))
                                            <span>{{ $user->phone }}</span>
                                        @elseif(!empty($user->mobile_num))
                                            <span>{{ $user->mobile_num }}</span>
                                        @else
                                            <a href="{{ route('my.profile') }}" style="color: #64748B; text-decoration: none;">
                                                + {{__('Add phone number')}}
                                            </a>
                                        @endif
                                    </span>
                                    <span style="display: inline-flex; align-items: center; gap: 5px;">
                                        <i class="fa fa-envelope" style="color: #94A3B8;"></i>
                                        <span>{{ $user->email }}</span>
                                    </span>
                                </div>

                                <!-- Skill Badges Cloud -->
                                <div class="hero-skill-chips" style="display: flex; flex-wrap: wrap; gap: 6px; align-items: center;">
                                    @if(isset($skills) && count($skills))
                                        @foreach($skills->take(4) as $sk)
                                            @if($sk->getJobSkill())
                                                <span style="background: #EFF6FF; border: 1px solid #DBEAFE; color: #1E40AF; font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 20px;">
                                                    {{ $sk->getJobSkill('job_skill') }}
                                                </span>
                                            @endif
                                        @endforeach
                                        @if(count($skills) > 4)
                                            <span style="background: #F1F5F9; border: 1px solid #E2E8F0; color: #475569; font-size: 12px; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
                                                +{{ count($skills) - 4 }}
                                            </span>
                                        @endif
                                    @else
                                        <a href="{{ url('my-profile#skills') }}" style="font-size: 12.5px; font-weight: 600; color: #2563EB; text-decoration: none;">
                                            + {{__('Add Skills to your profile')}}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Right: Action Buttons (Edit, Download Resume, Share Profile) -->
                        <div class="hero-right-actions" style="display: flex; flex-direction: column; gap: 10px; min-width: 170px;">
                            <a href="{{ route('my.profile') }}" class="btn-hero-action btn-hero-edit" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: #2563EB; color: #FFFFFF; font-size: 13.5px; font-weight: 700; padding: 10px 18px; border-radius: 10px; text-decoration: none; box-shadow: 0 2px 6px rgba(37,99,235,0.25); transition: all 0.15s ease;">
                                <i class="fa fa-pencil"></i>
                                <span>{{__('Edit Profile')}}</span>
                            </a>

                            @if($defaultCv)
                                <a href="{{ asset('cvs/'.$defaultCv->cv_file) }}" target="_blank" class="btn-hero-action btn-hero-secondary" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: #FFFFFF; border: 1.5px solid #E2E8F0; color: #334155; font-size: 13px; font-weight: 600; padding: 9px 16px; border-radius: 10px; text-decoration: none; transition: all 0.15s ease;">
                                    <i class="fa fa-download" style="color: #64748B;"></i>
                                    <span>{{__('Download Resume')}}</span>
                                </a>
                            @else
                                <a href="{{ url('my-profile#cvs') }}" class="btn-hero-action btn-hero-secondary" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: #FFFFFF; border: 1.5px solid #E2E8F0; color: #334155; font-size: 13px; font-weight: 600; padding: 9px 16px; border-radius: 10px; text-decoration: none; transition: all 0.15s ease;">
                                    <i class="fa fa-upload" style="color: #64748B;"></i>
                                    <span>{{__('Upload Resume')}}</span>
                                </a>
                            @endif

                            <a href="{{ route('view.public.profile', $user->id) }}" target="_blank" class="btn-hero-action btn-hero-secondary" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: #FFFFFF; border: 1.5px solid #E2E8F0; color: #334155; font-size: 13px; font-weight: 600; padding: 9px 16px; border-radius: 10px; text-decoration: none; transition: all 0.15s ease;">
                                <i class="fa fa-share-alt" style="color: #64748B;"></i>
                                <span>{{__('Share Profile')}}</span>
                            </a>
                        </div>

                    </div>
                </div>

                <!-- 2. Metrics Statistics Row (5 Cards in 1 Row Matching Screenshot) -->
                <div class="metrics-stat-grid" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 14px; margin-bottom: 24px;">
                    
                    <!-- Stat 1: Profile Views -->
                    <div class="stat-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 18px 14px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                        <div style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background: #EFF6FF; color: #2563EB; font-size: 15px; margin-bottom: 8px;">
                            <i class="fa fa-eye"></i>
                        </div>
                        <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 2px;">{{__('Profile Views')}}</div>
                        <div style="font-size: 22px; font-weight: 900; color: #0F172A; line-height: 1.1; margin-bottom: 2px;">{{ $profileViews }}</div>
                        <div style="font-size: 11px; color: #94A3B8; font-weight: 500;">{{__('Total Views')}}</div>
                    </div>

                    <!-- Stat 2: My Applications -->
                    <a href="{{ route('my.job.applications') }}" class="stat-card stat-link" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 18px 14px; text-align: center; text-decoration: none; box-shadow: 0 2px 8px rgba(0,0,0,0.02); display: block;">
                        <div style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background: #F3E8FF; color: #7C3AED; font-size: 15px; margin-bottom: 8px;">
                            <i class="fa fa-briefcase"></i>
                        </div>
                        <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 2px;">{{__('My Applications')}}</div>
                        <div style="font-size: 22px; font-weight: 900; color: #0F172A; line-height: 1.1; margin-bottom: 2px;">{{ $appliedCount }}</div>
                        <div style="font-size: 11px; color: #94A3B8; font-weight: 500;">{{__('Applied Jobs')}}</div>
                    </a>

                    <!-- Stat 3: Shortlisted -->
                    <a href="{{ route('my.job.applications', ['tab' => 'invites']) }}" class="stat-card stat-link" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 18px 14px; text-align: center; text-decoration: none; box-shadow: 0 2px 8px rgba(0,0,0,0.02); display: block;">
                        <div style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background: #FEF3C7; color: #D97706; font-size: 15px; margin-bottom: 8px;">
                            <i class="fa fa-star"></i>
                        </div>
                        <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 2px;">{{__('Shortlisted')}}</div>
                        <div style="font-size: 22px; font-weight: 900; color: #0F172A; line-height: 1.1; margin-bottom: 2px;">{{ $shortlistedCount }}</div>
                        <div style="font-size: 11px; color: #94A3B8; font-weight: 500;">{{__('Shortlisted')}}</div>
                    </a>

                    <!-- Stat 4: Interview Invites -->
                    <a href="{{ route('my.job.applications', ['tab' => 'invites']) }}" class="stat-card stat-link" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 18px 14px; text-align: center; text-decoration: none; box-shadow: 0 2px 8px rgba(0,0,0,0.02); display: block;">
                        <div style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background: #FCE7F3; color: #DB2777; font-size: 15px; margin-bottom: 8px;">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 2px;">{{__('Interview Invites')}}</div>
                        <div style="font-size: 22px; font-weight: 900; color: #0F172A; line-height: 1.1; margin-bottom: 2px;">{{ $interviewInvitesCount }}</div>
                        <div style="font-size: 11px; color: #94A3B8; font-weight: 500;">{{__('Interview Calls')}}</div>
                    </a>

                    <!-- Stat 5: Messages -->
                    <a href="{{ route('my.messages') }}" class="stat-card stat-link" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 18px 14px; text-align: center; text-decoration: none; box-shadow: 0 2px 8px rgba(0,0,0,0.02); display: block;">
                        <div style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background: #E0E7FF; color: #4F46E5; font-size: 15px; margin-bottom: 8px;">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 2px;">{{__('Messages')}}</div>
                        <div style="font-size: 22px; font-weight: 900; color: #0F172A; line-height: 1.1; margin-bottom: 2px;">{{ $messagesCount }}</div>
                        <div style="font-size: 11px; color: #94A3B8; font-weight: 500;">{{__('Unread Messages')}}</div>
                    </a>

                </div>

                <!-- 3. Two-Column Content Grid (Matching Screenshot) -->
                <div class="row">
                    
                    <!-- Left Column: About Me & Work Experience -->
                    <div class="col-lg-7">
                        
                        <!-- About Me Card -->
                        <div class="dashboard-section-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 22px 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                                <h3 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa fa-user" style="color: #2563EB; font-size: 16px;"></i>
                                    <span>{{__('About Me')}}</span>
                                </h3>
                                <a href="{{ route('my.profile') }}" style="font-size: 13.5px; font-weight: 700; color: #2563EB; text-decoration: none;">
                                    {{__('Edit')}}
                                </a>
                            </div>

                            @if(!empty($summary))
                                <p style="font-size: 14px; color: #475569; line-height: 1.6; margin-bottom: 16px;">
                                    {{ $summary }}
                                </p>
                            @else
                                <p style="font-size: 14px; color: #64748B; margin-bottom: 16px;">
                                    <a href="{{ route('my.profile') }}" style="color: #2563EB; font-weight: 600; text-decoration: none;">
                                        + {{__('Add a professional summary / about me')}}
                                    </a>
                                </p>
                            @endif

                            <!-- Personal Details Metadata Tags -->
                            <div class="about-metadata-row" style="display: flex; flex-wrap: wrap; gap: 14px; font-size: 13px; color: #64748B; padding-top: 12px; border-top: 1px solid #F1F5F9;">
                                @if($user->date_of_birth)
                                    <span style="display: inline-flex; align-items: center; gap: 5px;">
                                        <i class="fa fa-calendar-o" style="color: #94A3B8;"></i>
                                        <span>{{ $user->date_of_birth->format('jS M Y') }}</span>
                                    </span>
                                @endif
                                
                                @if($user->getGender('gender'))
                                    <span style="display: inline-flex; align-items: center; gap: 5px;">
                                        <i class="fa fa-user-o" style="color: #94A3B8;"></i>
                                        <span>{{ $user->getGender('gender') }}</span>
                                    </span>
                                @endif

                                @if($user->getMaritalStatus('marital_status'))
                                    <span style="display: inline-flex; align-items: center; gap: 5px;">
                                        <i class="fa fa-heart-o" style="color: #94A3B8;"></i>
                                        <span>{{ $user->getMaritalStatus('marital_status') }}</span>
                                    </span>
                                @endif

                                @if($user->getLocation())
                                    <span style="display: inline-flex; align-items: center; gap: 5px;">
                                        <i class="fa fa-map-marker" style="color: #94A3B8;"></i>
                                        <span>{{ $user->getLocation() }}</span>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Work Experience Card -->
                        <div class="dashboard-section-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 22px 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
                                <h3 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa fa-briefcase" style="color: #64748B; font-size: 16px;"></i>
                                    <span>{{__('Work Experience')}}</span>
                                </h3>
                                <a href="{{ url('my-profile#experience') }}" style="font-size: 13.5px; font-weight: 700; color: #2563EB; text-decoration: none;">
                                    + {{__('Add Experience')}}
                                </a>
                            </div>

                            @if(isset($experiences) && count($experiences))
                                <div class="experience-timeline-list" style="display: flex; flex-direction: column; gap: 18px;">
                                    @foreach($experiences as $exp)
                                        <div class="exp-item-card" style="display: flex; gap: 16px; align-items: flex-start; padding-bottom: 16px; border-bottom: 1px solid #F1F5F9;">
                                            <div style="width: 44px; height: 44px; border-radius: 10px; background: #F8FAFC; border: 1.5px solid #E2E8F0; display: flex; align-items: center; justify-content: center; color: #64748B; font-size: 18px; flex-shrink: 0;">
                                                <i class="fa fa-building-o"></i>
                                            </div>
                                            <div style="flex: 1;">
                                                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px; flex-wrap: wrap; margin-bottom: 2px;">
                                                    <h4 style="font-size: 15.5px; font-weight: 800; color: #0F172A; margin: 0;">
                                                        {{ $exp->title }}
                                                    </h4>
                                                    <span style="font-size: 12px; font-weight: 700; color: #03855c; background: #ECFDF5; border: 1px solid #A7F3D0; padding: 2px 8px; border-radius: 6px;">
                                                        {{ $exp->is_currently_working ? __('Full Time') : __('Completed') }}
                                                    </span>
                                                </div>
                                                <div style="font-size: 13.5px; font-weight: 600; color: #2563EB; margin-bottom: 4px;">
                                                    {{ $exp->company }}
                                                </div>
                                                <div style="font-size: 12.5px; color: #64748B; margin-bottom: 8px;">
                                                    {{ $exp->date_start ? date('M Y', strtotime($exp->date_start)) : '' }} &ndash; {{ $exp->is_currently_working ? __('Present') : ($exp->date_end ? date('M Y', strtotime($exp->date_end)) : '') }}
                                                </div>
                                                @if(!empty($exp->description))
                                                    <p style="font-size: 13.5px; color: #475569; line-height: 1.5; margin-bottom: 8px;">
                                                        {{ $exp->description }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div style="text-align: center; padding: 28px 16px; background: #F8FAFC; border: 1.5px dashed #CBD5E1; border-radius: 12px;">
                                    <i class="fa fa-briefcase fa-2x" style="color: #94A3B8; margin-bottom: 8px;"></i>
                                    <p style="font-size: 13.5px; font-weight: 600; color: #475569; margin-bottom: 12px;">{{ __('No work experience added yet.') }}</p>
                                    <a href="{{ url('my-profile#experience') }}" class="btn btn-sm btn-primary" style="background: #2563EB; font-weight: 700; border-radius: 8px; padding: 6px 16px; text-decoration: none;">
                                        + {{ __('Add Experience') }}
                                    </a>
                                </div>
                            @endif
                        </div>

                    </div>

                    <!-- Right Column: Profile Strength Widget & Recommended Jobs Widget -->
                    <div class="col-lg-5">
                        
                        <!-- Profile Strength Widget (Matching Screenshot) -->
                        <div class="dashboard-section-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 22px 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
                            <h3 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0 0 16px 0;">
                                {{__('Profile Strength')}}
                            </h3>

                            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 18px;">
                                <!-- Circular Radial Score Indicator -->
                                <div class="radial-score-ring" style="position: relative; width: 62px; height: 62px; min-width: 62px; border-radius: 50%; background: conic-gradient(#03855c {{ $strengthScore * 3.6 }}deg, #E2E8F0 0deg); display: flex; align-items: center; justify-content: center;">
                                    <div style="width: 50px; height: 50px; border-radius: 50%; background: #FFFFFF; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 900; color: #0F172A;">
                                        {{ $strengthScore }}%
                                    </div>
                                </div>

                                <div>
                                    <p style="font-size: 13.5px; font-weight: 600; color: #334155; margin: 0; line-height: 1.4;">
                                        {{__('Great! Complete your profile to get more job matches.')}}
                                    </p>
                                </div>
                            </div>

                            <!-- Checklist -->
                            <ul class="strength-checklist" style="list-style: none; padding: 0; margin: 0 0 18px 0; display: flex; flex-direction: column; gap: 8px;">
                                <li style="display: flex; align-items: center; gap: 10px; font-size: 13px; color: {{ $strengthChecks['basic_info'] ? '#03855c' : '#64748B' }}; font-weight: 600;">
                                    <i class="fa fa-{{ $strengthChecks['basic_info'] ? 'check-circle' : 'circle-o' }}" style="color: {{ $strengthChecks['basic_info'] ? '#03855c' : '#94A3B8' }}; font-size: 15px;"></i>
                                    <span>{{__('Basic Information')}}</span>
                                </li>
                                <li style="display: flex; align-items: center; gap: 10px; font-size: 13px; color: {{ $strengthChecks['experience'] ? '#03855c' : '#64748B' }}; font-weight: 600;">
                                    <i class="fa fa-{{ $strengthChecks['experience'] ? 'check-circle' : 'circle-o' }}" style="color: {{ $strengthChecks['experience'] ? '#03855c' : '#94A3B8' }}; font-size: 15px;"></i>
                                    <span>{{__('Work Experience')}}</span>
                                </li>
                                <li style="display: flex; align-items: center; gap: 10px; font-size: 13px; color: {{ $strengthChecks['education'] ? '#03855c' : '#64748B' }}; font-weight: 600;">
                                    <i class="fa fa-{{ $strengthChecks['education'] ? 'check-circle' : 'circle-o' }}" style="color: {{ $strengthChecks['education'] ? '#03855c' : '#94A3B8' }}; font-size: 15px;"></i>
                                    <span>{{__('Education')}}</span>
                                </li>
                                <li style="display: flex; align-items: center; gap: 10px; font-size: 13px; color: {{ $strengthChecks['skills'] ? '#03855c' : '#64748B' }}; font-weight: 600;">
                                    <i class="fa fa-{{ $strengthChecks['skills'] ? 'check-circle' : 'circle-o' }}" style="color: {{ $strengthChecks['skills'] ? '#03855c' : '#94A3B8' }}; font-size: 15px;"></i>
                                    <span>{{__('Skills')}}</span>
                                </li>
                                <li style="display: flex; align-items: center; gap: 10px; font-size: 13px; color: {{ $strengthChecks['resume'] ? '#03855c' : '#D97706' }}; font-weight: 600;">
                                    <i class="fa fa-{{ $strengthChecks['resume'] ? 'check-circle' : 'exclamation-circle' }}" style="color: {{ $strengthChecks['resume'] ? '#03855c' : '#F59E0B' }}; font-size: 15px;"></i>
                                    <span>{{__('Resume Upload')}}</span>
                                </li>
                                <li style="display: flex; align-items: center; gap: 10px; font-size: 13px; color: #D97706; font-weight: 600;">
                                    <i class="fa fa-exclamation-circle" style="color: #F59E0B; font-size: 15px;"></i>
                                    <span>{{__('Career Preferences')}}</span>
                                </li>
                                <li style="display: flex; align-items: center; gap: 10px; font-size: 13px; color: {{ $strengthChecks['mobile'] ? '#03855c' : '#DC2626' }}; font-weight: 600;">
                                    <i class="fa fa-{{ $strengthChecks['mobile'] ? 'check-circle' : 'minus-circle' }}" style="color: {{ $strengthChecks['mobile'] ? '#03855c' : '#EF4444' }}; font-size: 15px;"></i>
                                    <span>{{__('Verify Mobile Number')}}</span>
                                </li>
                            </ul>

                            <a href="{{ route('my.profile') }}" class="btn-complete-profile" style="display: block; width: 100%; text-align: center; background: #FFFFFF; border: 1.5px solid #2563EB; color: #2563EB; font-size: 13.5px; font-weight: 700; padding: 10px; border-radius: 10px; text-decoration: none; transition: all 0.15s ease;">
                                {{__('Complete Your Profile')}}
                            </a>
                        </div>

                        <!-- Recommended Jobs Widget (Matching Screenshot) -->
                        <div class="dashboard-section-card" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 22px 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                                <h3 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0;">
                                    {{__('Recommended Jobs')}}
                                </h3>
                                <a href="{{ route('job.list') }}" style="font-size: 13px; font-weight: 700; color: #2563EB; text-decoration: none;">
                                    {{__('View All')}}
                                </a>
                            </div>

                            <div class="recommended-jobs-list" style="display: flex; flex-direction: column; gap: 12px;">
                                @if(isset($matchingJobs) && count($matchingJobs))
                                    @php $scores = ['92% Match', '88% Match', '85% Match', '80% Match']; $idx = 0; @endphp
                                    @foreach($matchingJobs as $mjob)
                                        @php $mcompany = $mjob->getCompany(); @endphp
                                        <div class="rec-job-card" style="display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 12px 14px; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; transition: all 0.15s ease;">
                                            <div style="display: flex; align-items: center; gap: 12px; flex: 1; min-width: 0;">
                                                <div style="width: 38px; height: 38px; border-radius: 8px; background: #FFFFFF; border: 1px solid #CBD5E1; display: flex; align-items: center; justify-content: center; color: #64748B; font-size: 15px; flex-shrink: 0;">
                                                    <i class="fa fa-briefcase"></i>
                                                </div>
                                                <div style="min-width: 0; flex: 1;">
                                                    <h5 style="font-size: 13.5px; font-weight: 800; color: #0F172A; margin: 0 0 2px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                        <a href="{{ route('job.detail', [$mjob->slug]) }}" style="color: #0F172A; text-decoration: none;">
                                                            {{ $mjob->title }}
                                                        </a>
                                                    </h5>
                                                    <div style="font-size: 12px; color: #2563EB; font-weight: 600; margin-bottom: 2px;">
                                                        {{ $mcompany ? $mcompany->name : 'Verified Employer' }}
                                                    </div>
                                                    <div style="font-size: 11.5px; color: #64748B;">
                                                        <i class="fa fa-map-marker text-danger"></i> {{ $mjob->getCity('city') ?: 'Nagpur' }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div style="flex-shrink: 0;">
                                                <span style="display: inline-block; background: #ECFDF5; border: 1px solid #A7F3D0; color: #03855c; font-size: 11px; font-weight: 800; padding: 3px 8px; border-radius: 6px;">
                                                    {{ $scores[$idx % count($scores)] }}
                                                </span>
                                            </div>
                                        </div>
                                        @php $idx++; @endphp
                                    @endforeach
                                @else
                                    <div style="padding: 16px; text-align: center; color: #64748B; font-size: 13px;">
                                        {{__('Browse jobs to discover matching recommendations.')}}
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

<style>
.user-dashboard-redesign .sidebar-nav-link {
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
.user-dashboard-redesign .sidebar-nav-link:hover {
    background: #F1F5F9;
    color: #0F172A;
}
.user-dashboard-redesign .btn-hero-edit:hover {
    background: #1D4ED8 !important;
    box-shadow: 0 4px 12px rgba(37,99,235,0.35) !important;
    transform: translateY(-1px);
}
.user-dashboard-redesign .btn-hero-secondary:hover {
    background: #F8FAFC !important;
    border-color: #CBD5E1 !important;
    transform: translateY(-1px);
}
.user-dashboard-redesign .stat-card {
    transition: all 0.15s ease;
}
.user-dashboard-redesign .stat-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.05) !important;
    border-color: #CBD5E1 !important;
}
.user-dashboard-redesign .rec-job-card:hover {
    background: #EFF6FF !important;
    border-color: #BFDBFE !important;
}
.user-dashboard-redesign .btn-complete-profile:hover {
    background: #2563EB !important;
    color: #FFFFFF !important;
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
    .hero-skill-chips {
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
function toggleMobileDashboardMenu() {
    var menu = document.getElementById('mobileDashboardMenuContent');
    var caret = document.getElementById('mobileMenuCaret');
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