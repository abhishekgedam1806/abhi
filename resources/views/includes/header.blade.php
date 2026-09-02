<div class="header">
    <div class="header-container-fluid">
        <!-- Brand Logo (Left Pinned) -->
        <div class="header-logo-area">
            <a href="{{url('/')}}" class="logo">
                <img src="{{ asset('/') }}sitesetting_images/thumb/{{ $siteSetting->site_logo }}" alt="{{ $siteSetting->site_name }}" width="160" height="40" style="max-height: 40px; width: auto; object-fit: contain;" decoding="async" />
            </a>
        </div>

        <!-- Mobile Toggler (Right on Mobile) -->
        <button class="navbar-toggler collapsed d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#nav-main" aria-controls="nav-main" aria-expanded="false" aria-label="Toggle navigation" style="border: none; background: transparent; color: #FFFFFF; font-size: 20px; outline: none; padding: 6px 10px;">
            <i class="fa fa-bars"></i>
        </button>

        <!-- Navigation Menu & Actions (Pushed to the Far Right) -->
        <div class="header-nav-area">
            <nav class="navbar navbar-expand-lg navbar-dark p-0">
                <div class="navbar-collapse collapse" id="nav-main">
                    
                    {{-- 📱 MOBILE DRAWER TOP: User / Company Card or Guest Auth (Mobile Only) --}}
                    @if(Auth::guard('company')->check())
                        @php $loggedCompany = Auth::guard('company')->user(); @endphp
                        <div class="mobile-user-card d-lg-none">
                            <div class="mobile-user-avatar">
                                {!! $loggedCompany->printCompanyImage(46, 46) !!}
                            </div>
                            <div class="mobile-user-info">
                                <div class="mobile-user-name">{{ $loggedCompany->name }}</div>
                                <div class="mobile-user-role"><i class="fa fa-building-o"></i> {{ __('Employer Account') }}</div>
                            </div>
                            <a href="{{ route('company.home') }}" class="mobile-user-dash-btn" title="{{ __('Dashboard') }}">
                                <i class="fa fa-th-large"></i>
                            </a>
                        </div>
                    @elseif(Auth::guard('web')->check())
                        @php $loggedUser = Auth::guard('web')->user(); @endphp
                        <div class="mobile-user-card d-lg-none">
                            <div class="mobile-user-avatar">
                                {!! $loggedUser->printUserImage(46, 46) !!}
                            </div>
                            <div class="mobile-user-info">
                                <div class="mobile-user-name">{{ $loggedUser->getName() }}</div>
                                <div class="mobile-user-role"><i class="fa fa-user-circle"></i> {{ __('Job Seeker') }}</div>
                            </div>
                            <a href="{{ route('home') }}" class="mobile-user-dash-btn" title="{{ __('Dashboard') }}">
                                <i class="fa fa-th-large"></i>
                            </a>
                        </div>
                    @else
                        <div class="mobile-guest-actions d-lg-none">
                            <a href="{{ route('login') }}" class="btn-mobile-login">
                                <i class="fa fa-sign-in"></i> {{ __('Sign in') }}
                            </a>
                            <a href="{{ route('register') }}" class="btn-mobile-register">
                                <i class="fa fa-user-plus"></i> {{ __('Register') }}
                            </a>
                        </div>
                    @endif

                    <ul class="navbar-nav ml-auto align-items-center">
                        {{-- DYNAMIC / MANAGABLE HEADER LINKS --}}
                        @php
                            $dynamicHeaderItems = App\SiteMenuItem::getHeaderItems();
                            $isCompanyAuth = Auth::guard('company')->check();
                            $isUserAuth = Auth::guard('web')->check();
                        @endphp

                        @if($dynamicHeaderItems && count($dynamicHeaderItems) > 0)
                            @foreach($dynamicHeaderItems as $hItem)
                                @php
                                    if ($hItem->audience === 'seeker' && !$isUserAuth) continue;
                                    if ($hItem->audience === 'company' && !$isCompanyAuth) continue;
                                    if ($hItem->audience === 'guest' && ($isUserAuth || $isCompanyAuth)) continue;

                                    $itemUrl = $hItem->getFormattedUrl();
                                    $cleanPath = trim($hItem->url, '/');
                                    $isActive = (Request::url() == $itemUrl || ($cleanPath !== '' && Request::is($cleanPath . '*')));
                                @endphp
                                <li class="nav-item {{ $isActive ? 'active' : '' }}">
                                    <a href="{{ $itemUrl }}" target="{{ $hItem->target ?? '_self' }}" class="nav-link">
                                        <span class="nav-link-content">
                                            @if(!empty($hItem->icon))
                                            <i class="{{ $hItem->icon }} nav-icon d-lg-none"></i>
                                            @else
                                            <i class="fa fa-link nav-icon d-lg-none"></i>
                                            @endif
                                            <span>{{ __($hItem->title) }}</span>
                                        </span>
                                        <i class="fa fa-angle-right nav-arrow d-lg-none"></i>
                                    </a>
                                </li>
                            @endforeach
                        @else
                            {{-- Fallback Default Links --}}
                            <li class="nav-item {{ Request::url() == route('index') ? 'active' : '' }}">
                                <a href="{{url('/')}}" class="nav-link"><span class="nav-link-content"><i class="fa fa-home nav-icon d-lg-none"></i><span>{{__('Home')}}</span></span><i class="fa fa-angle-right nav-arrow d-lg-none"></i></a>
                            </li>
                            <li class="nav-item {{ Request::url() == url('/jobs') ? 'active' : '' }}">
                                <a href="{{url('/jobs')}}" class="nav-link"><span class="nav-link-content"><i class="fa fa-briefcase nav-icon d-lg-none"></i><span>{{__('Jobs')}}</span></span><i class="fa fa-angle-right nav-arrow d-lg-none"></i></a>
                            </li>
                            <li class="nav-item {{ Request::url() == url('/companies') ? 'active' : '' }}">
                                <a href="{{url('/companies')}}" class="nav-link"><span class="nav-link-content"><i class="fa fa-building-o nav-icon d-lg-none"></i><span>{{__('Companies')}}</span></span><i class="fa fa-angle-right nav-arrow d-lg-none"></i></a>
                            </li>
                            <li class="nav-item {{ Request::is('business*') ? 'active' : '' }}">
                                <a href="{{ route('business.list') }}" class="nav-link"><span class="nav-link-content"><i class="fa fa-handshake-o nav-icon d-lg-none"></i><span>{{__('Businesses')}}</span></span><i class="fa fa-angle-right nav-arrow d-lg-none"></i></a>
                            </li>
                            <li class="nav-item {{ Request::url() == route('pricing') ? 'active' : '' }}">
                                <a href="{{ route('pricing') }}" class="nav-link"><span class="nav-link-content"><i class="fa fa-tags nav-icon d-lg-none"></i><span>{{__('Pricing')}}</span></span><i class="fa fa-angle-right nav-arrow d-lg-none"></i></a>
                            </li>
                            <li class="nav-item {{ Request::url() == route('blogs') ? 'active' : '' }}">
                                <a href="{{ route('blogs') }}" class="nav-link"><span class="nav-link-content"><i class="fa fa-newspaper-o nav-icon d-lg-none"></i><span>{{__('Blog')}}</span></span><i class="fa fa-angle-right nav-arrow d-lg-none"></i></a>
                            </li>
                            <li class="nav-item {{ Request::url() == route('contact.us') ? 'active' : '' }}">
                                <a href="{{ route('contact.us') }}" class="nav-link"><span class="nav-link-content"><i class="fa fa-phone nav-icon d-lg-none"></i><span>{{__('Contact')}}</span></span><i class="fa fa-angle-right nav-arrow d-lg-none"></i></a>
                            </li>
                        @endif

                        {{-- 📱 MOBILE COMPANY EXTRA ACTIONS --}}
                        @if(Auth::guard('company')->check())
                            <li class="nav-item d-lg-none mobile-cta-item">
                                <a href="{{route('post.job')}}" class="btn-mobile-postjob">
                                    <i class="fa fa-plus-circle"></i> {{__('Post a Job')}}
                                </a>
                            </li>
                            <li class="nav-item d-lg-none">
                                <a href="{{route('company.messages')}}" class="nav-link">
                                    <span class="nav-link-content">
                                        <i class="fa fa-envelope-o nav-icon"></i>
                                        <span>{{__('Company Messages')}}</span>
                                    </span>
                                    <i class="fa fa-angle-right nav-arrow"></i>
                                </a>
                            </li>
                            <li class="nav-item d-lg-none">
                                <a href="{{ route('company.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-header1').submit();" class="nav-link text-danger-mobile">
                                    <span class="nav-link-content">
                                        <i class="fa fa-sign-out text-danger nav-icon"></i>
                                        <span class="text-danger">{{__('Logout')}}</span>
                                    </span>
                                </a>
                            </li>
                        @endif

                        {{-- 📱 MOBILE CANDIDATE EXTRA ACTIONS --}}
                        @if(Auth::guard('web')->check())
                            <li class="nav-item d-lg-none">
                                <a href="{{ route('my.job.applications') }}" class="nav-link">
                                    <span class="nav-link-content">
                                        <i class="fa fa-desktop nav-icon"></i>
                                        <span>{{__('My Applications')}}</span>
                                    </span>
                                    <i class="fa fa-angle-right nav-arrow"></i>
                                </a>
                            </li>
                            <li class="nav-item d-lg-none">
                                <a href="{{ route('my.favourite.jobs') }}" class="nav-link">
                                    <span class="nav-link-content">
                                        <i class="fa fa-heart-o nav-icon"></i>
                                        <span>{{__('My Saved Jobs')}}</span>
                                    </span>
                                    <i class="fa fa-angle-right nav-arrow"></i>
                                </a>
                            </li>
                            <li class="nav-item d-lg-none">
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();" class="nav-link text-danger-mobile">
                                    <span class="nav-link-content">
                                        <i class="fa fa-sign-out text-danger nav-icon"></i>
                                        <span class="text-danger">{{__('Logout')}}</span>
                                    </span>
                                </a>
                            </li>
                        @endif
                        
                        {{-- 💻 DESKTOP USER PROFILE DROPDOWN (Desktop Only) --}}
                        @if(Auth::guard('web')->check())
                        <li class="nav-item dropdown userbtn d-none d-lg-block">
                            <a href="javascript:;" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                {{Auth::guard('web')->user()->printUserImage()}}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                @php
                                    $isBusinessUser = Auth::guard('web')->user()->isBusinessUser();
                                @endphp

                                @if($isBusinessUser)
                                <li class="nav-item"><a href="{{route('business.dashboard')}}" class="nav-link dropdown-item"><i class="fa fa-briefcase text-primary" aria-hidden="true"></i> Business Dashboard</a> </li>
                                <li class="nav-item"><a href="{{route('my.businesses')}}" class="nav-link dropdown-item"><i class="fa fa-building" style="color:#EA580C;" aria-hidden="true"></i> My Businesses</a> </li>
                                <li class="nav-item"><a href="{{route('business.all.leads')}}" class="nav-link dropdown-item"><i class="fa fa-phone" style="color:#0284C7;" aria-hidden="true"></i> Business Leads</a> </li>
                                <li class="nav-item"><a href="{{route('business.packages')}}" class="nav-link dropdown-item"><i class="fa fa-credit-card" style="color:#10B981;" aria-hidden="true"></i> Packages & Upgrade</a> </li>
                                <li class="nav-item"><a href="{{ route('business.owner.profile') }}" class="nav-link dropdown-item"><i class="fa fa-user-circle text-primary" aria-hidden="true"></i> {{__('Edit Profile')}}</a> </li>
                                <li class="nav-item"><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();" class="nav-link dropdown-item"><i class="fa fa-sign-out text-danger" aria-hidden="true"></i> {{__('Logout')}}</a> </li>
                                @else
                                <li class="nav-item"><a href="{{route('home')}}" class="nav-link dropdown-item"><i class="fa fa-tachometer" aria-hidden="true"></i> Candidate Dashboard</a> </li>
                                <li class="nav-item"><a href="{{ route('my.profile') }}" class="nav-link dropdown-item"><i class="fa fa-pencil" aria-hidden="true"></i> {{__('Edit Profile')}}</a> </li>
                                <li class="nav-item"><a href="{{ route('view.public.profile', Auth::guard('web')->user()->id) }}" class="nav-link dropdown-item"><i class="fa fa-eye" aria-hidden="true"></i> {{__('View Public Profile')}}</a> </li>
                                <li class="nav-item"><a href="{{ route('my.job.applications') }}" class="nav-link dropdown-item"><i class="fa fa-desktop" aria-hidden="true"></i> {{__('My Job Applications')}}</a> </li>
                                <li class="nav-item"><a href="{{ route('my.favourite.jobs') }}" class="nav-link dropdown-item"><i class="fa fa-heart" aria-hidden="true"></i> {{__('My Favourite Jobs')}}</a> </li>
                                <li class="nav-item"><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();" class="nav-link dropdown-item"><i class="fa fa-sign-out text-danger" aria-hidden="true"></i> {{__('Logout')}}</a> </li>
                                @endif

                                <form id="logout-form-header" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </ul>
                        </li>
                        @endif

                        {{-- 💻 DESKTOP COMPANY PROFILE DROPDOWN (Desktop Only) --}}
                        @if(Auth::guard('company')->check())
                        <li class="nav-item postjob d-none d-lg-block"><a href="{{route('post.job')}}" class="nav-link register">{{__('Post a job')}}</a> </li>
                        <li class="nav-item dropdown userbtn d-none d-lg-block">
                            <a href="javascript:;" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                {{Auth::guard('company')->user()->printCompanyImage()}}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li class="nav-item"><a href="{{route('company.home')}}" class="nav-link dropdown-item"><i class="fa fa-tachometer" aria-hidden="true"></i> {{__('Dashboard')}}</a> </li>
                                <li class="nav-item"><a href="{{ route('company.profile') }}" class="nav-link dropdown-item"><i class="fa fa-user" aria-hidden="true"></i> {{__('Company Profile')}}</a></li>
                                <li class="nav-item"><a href="{{ route('post.job') }}" class="nav-link dropdown-item"><i class="fa fa-desktop" aria-hidden="true"></i> {{__('Post Job')}}</a></li>
                                <li class="nav-item"><a href="{{route('company.messages')}}" class="nav-link dropdown-item"><i class="fa fa-envelope-o" aria-hidden="true"></i> {{__('Company Messages')}}</a></li>
                                <li class="nav-item"><a href="{{ route('company.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-header1').submit();" class="nav-link dropdown-item"><i class="fa fa-sign-out text-danger" aria-hidden="true"></i> {{__('Logout')}}</a> </li>
                                <form id="logout-form-header1" action="{{ route('company.logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </ul>
                        </li>
                        @endif

                        {{-- 💻 DESKTOP GUEST BUTTONS (Desktop Only) --}}
                        @if(!Auth::user() && !Auth::guard('company')->user())
                        <li class="nav-item d-none d-lg-block">
                            <a href="{{route('login')}}" class="nav-link">{{__('Sign in')}}</a>
                        </li>
                        <li class="nav-item d-none d-lg-block">
                            <a href="{{route('register')}}" class="nav-link nav-register-btn">{{__('Register')}}</a>
                        </li>                            
                        @endif

                        {{-- 💻 DESKTOP PWA INSTALL BUTTON --}}
                        <li class="nav-item d-none d-lg-block" id="pwaHeaderBtn">
                            <a href="javascript:void(0);" onclick="installPwaApp()" class="nav-link" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: #FFFFFF !important; border-radius: 6px; font-weight: 700; padding: 6px 12px; margin-left: 8px;" title="Install Jobs Portal App">
                                <i class="fa fa-download" style="margin-right: 4px;"></i> {{ __('Install App') }}
                            </a>
                        </li>

                        {{-- 📱 MOBILE PWA INSTALL BUTTON IN DRAWER --}}
                        <li class="nav-item d-lg-none" id="pwaMobileDrawerBtn" style="margin: 6px 0 6px 0; width: 100%;">
                            <a href="javascript:void(0);" onclick="installPwaApp()" class="nav-link" style="background: #EFF6FF !important; border: 1.5px solid #BFDBFE !important; border-radius: 10px !important; font-weight: 700 !important; color: #2563EB !important; padding: 10px 14px !important; display: flex !important; align-items: center !important; justify-content: space-between !important; width: 100% !important;">
                                <span class="nav-link-content" style="display: flex; align-items: center; gap: 8px;">
                                    <i class="fa fa-download" style="color: #2563EB; font-size: 15px;"></i>
                                    <span style="font-size: 13.5px; font-weight: 700; color: #1E40AF;">{{ __('Install Mobile App') }}</span>
                                </span>
                                <span class="badge" style="background: #2563EB; color: #FFF; font-size: 10.5px; font-weight: 800; padding: 4px 8px; border-radius: 6px;">FREE</span>
                            </a>
                        </li>

                        {{-- 📱 MOBILE DEDICATED LANGUAGE ACCORDION --}}
                        @php
                            $activeLangObj = isset($siteLanguages) ? $siteLanguages->firstWhere('iso_code', App::getLocale()) : null;
                            $activeLangLabel = $activeLangObj ? $activeLangObj->native : 'English';
                        @endphp
                        <li class="nav-item d-lg-none" style="margin: 4px 0 0 0; width: 100%; border-top: 1px solid #F1F5F9; padding-top: 8px;">
                            <button type="button" class="btn-mob-lang-toggle" onclick="toggleMobLanguageAccordion(event)" style="width: 100%; display: flex; align-items: center; justify-content: space-between; background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 10px; padding: 10px 14px; cursor: pointer; color: #1E293B; font-family: inherit; font-size: 13.5px; font-weight: 700;">
                                <span style="display: flex; align-items: center; gap: 10px;">
                                    <span style="width: 28px; height: 28px; border-radius: 50%; background: #EFF6FF; border: 1px solid #BFDBFE; display: inline-flex; align-items: center; justify-content: center; color: #2563EB;">
                                        <i class="fa fa-globe"></i>
                                    </span>
                                    <span>{{ __('Language') }}: <span style="color: #2563EB;">{{ $activeLangLabel }}</span></span>
                                </span>
                                <i class="fa fa-chevron-down" id="mobLangArrow" style="color: #94A3B8; font-size: 12px; transition: transform 0.2s ease;"></i>
                            </button>
                            <div id="mobLangDrawerList" style="display: none; margin-top: 8px; background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 10px; padding: 6px; max-height: 220px; overflow-y: auto; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                                @foreach($siteLanguages as $siteLang)
                                <a href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('locale-form-mob-{{$siteLang->iso_code}}').submit();" style="display: flex; align-items: center; justify-content: space-between; padding: 10px 12px; border-radius: 8px; font-size: 13.5px; font-weight: 600; color: {{ App::getLocale() == $siteLang->iso_code ? '#2563EB' : '#1E293B' }}; background: {{ App::getLocale() == $siteLang->iso_code ? '#EFF6FF' : 'transparent' }}; text-decoration: none; margin-bottom: 2px;">
                                    <span style="display: flex; align-items: center; gap: 8px;">
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background: {{ App::getLocale() == $siteLang->iso_code ? '#2563EB' : '#CBD5E1' }};"></span>
                                        <span>{{ $siteLang->native }}</span>
                                    </span>
                                    @if(App::getLocale() == $siteLang->iso_code)
                                        <i class="fa fa-check text-primary"></i>
                                    @endif
                                </a>
                                <form id="locale-form-mob-{{$siteLang->iso_code}}" action="{{ route('set.locale') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="locale" value="{{$siteLang->iso_code}}"/>
                                    <input type="hidden" name="return_url" value="{{url()->full()}}"/>
                                    <input type="hidden" name="is_rtl" value="{{$siteLang->is_rtl}}"/>
                                </form>
                                @endforeach
                            </div>
                        </li>

                        {{-- 🔔 DESKTOP ONLY NOTIFICATION BELL --}}
                        @if(Auth::check() || Auth::guard('company')->check())
                        <li class="nav-item dropdown notif-dropdown-nav d-none d-lg-block">
                            <a href="javascript:void(0);" class="nav-link notif-toggle-btn" id="notifDropdownBtn" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" title="{{ __('Notifications') }}">
                                <div class="notif-bell-wrap">
                                    <i class="fa fa-bell-o"></i>
                                    <span class="notif-badge-count d-none" id="notif_badge_count">0</span>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right notif-dropdown-panel" aria-labelledby="notifDropdownBtn">
                                <div class="notif-panel-header">
                                    <div class="notif-panel-title">
                                        <i class="fa fa-bell text-primary mr-1"></i> {{ __('Notifications') }}
                                        <span class="notif-header-badge" id="notif_header_badge">0 New</span>
                                    </div>
                                    <a href="javascript:void(0);" onclick="markAllNotificationsReadDropdown()" class="notif-mark-all-btn">
                                        <i class="fa fa-check-circle-o"></i> {{ __('Mark all read') }}
                                    </a>
                                </div>
                                <div class="notif-panel-body" id="notif_items_container">
                                    <div class="notif-loading-state">
                                        <i class="fa fa-spinner fa-spin"></i> {{ __('Loading...') }}
                                    </div>
                                </div>
                                <div class="notif-panel-footer">
                                    <a href="{{ route('notification.all') }}" class="notif-view-all-link">
                                        {{ __('View All Notifications') }} <i class="fa fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                        @endif

                        {{-- 💻 DESKTOP ONLY SLEEK LANGUAGE DROPDOWN --}}
                        <li class="nav-item dropdown lang-dropdown-item d-none d-lg-block">
                            <a href="javascript:void(0);" class="nav-link dropdown-toggle lang-toggle-btn" id="langDropdownBtn" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" title="{{ __('Change Language') }}">
                                <div class="d-flex align-items-center">
                                    <span class="lang-pill-wrap">
                                        <i class="fa fa-globe"></i>
                                    </span>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right lang-dropdown-menu" aria-labelledby="langDropdownBtn" style="max-height: 320px; overflow-y: auto; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); border: 1px solid #E2E8F0; padding: 6px 0;">
                                @foreach($siteLanguages as $siteLang)
                                <li class="lang-option-row">
                                    <a href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('locale-form-{{$siteLang->iso_code}}').submit();" class="dropdown-item lang-choice-btn {{ App::getLocale() == $siteLang->iso_code ? 'active-lang' : '' }}">
                                        <span class="lang-flag-dot"></span>
                                        <span class="lang-name-text">{{ $siteLang->native }}</span>
                                        @if(App::getLocale() == $siteLang->iso_code)
                                            <i class="fa fa-check text-primary ml-auto lang-check-icon"></i>
                                        @endif
                                    </a>
                                    <form id="locale-form-{{$siteLang->iso_code}}" action="{{ route('set.locale') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="locale" value="{{$siteLang->iso_code}}"/>
                                        <input type="hidden" name="return_url" value="{{url()->full()}}"/>
                                        <input type="hidden" name="is_rtl" value="{{$siteLang->is_rtl}}"/>
                                    </form>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>

<style>
/* ==========================================================================
   MODERN EXECUTIVE HEADER NAVIGATION STYLING (True Edge-to-Edge Layout)
   ========================================================================== */
.header {
    background: #2563EB !important;
    box-shadow: 0 4px 20px rgba(30, 64, 175, 0.25) !important;
    position: sticky !important;
    top: 0 !important;
    z-index: 1000 !important;
    padding: 0 !important;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
    width: 100% !important;
}

.header-container-fluid {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    width: 100% !important;
    padding: 8px 24px !important;
    margin: 0 !important;
    box-sizing: border-box !important;
}

.header-logo-area {
    flex: 0 0 auto !important;
    display: flex !important;
    align-items: center !important;
}

.header-logo-area .logo img {
    height: 38px !important;
    width: auto !important;
    filter: brightness(0) invert(1) !important;
    transition: opacity 0.2s ease !important;
}
.header-logo-area .logo img:hover {
    opacity: 0.9 !important;
}

.header-nav-area {
    flex: 1 1 auto !important;
    display: flex !important;
    justify-content: flex-end !important;
    align-items: center !important;
}

/* Nav Item List Container */
.header .navbar-nav {
    display: flex !important;
    align-items: center !important;
    justify-content: flex-end !important;
    gap: 16px !important;
    list-style: none !important;
    margin: 0 !important;
    margin-left: auto !important;
    padding: 0 !important;
}

.header .navbar-nav > li,
.header .navbar-nav .nav-item {
    position: relative !important;
    margin: 0 !important;
    padding: 0 !important;
    border: none !important;
    background: transparent !important;
}

/* Standard Nav Links */
.header .navbar-nav .nav-link {
    color: #FFFFFF !important;
    opacity: 0.88 !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    padding: 8px 4px !important;
    background: transparent !important;
    border: none !important;
    border-bottom: none !important;
    border-radius: 0 !important;
    box-shadow: none !important;
    text-decoration: none !important;
    display: inline-flex !important;
    align-items: center !important;
    transition: opacity 0.15s ease, color 0.15s ease !important;
    position: relative !important;
}

.header .navbar-nav .nav-link:hover {
    opacity: 1 !important;
    color: #FFFFFF !important;
    background: transparent !important;
    border: none !important;
}

/* Clean Underline ONLY on Active Tab (e.g. Home / Jobs) */
.header .navbar-nav .nav-item.active .nav-link {
    opacity: 1 !important;
    font-weight: 600 !important;
    color: #FFFFFF !important;
    background: transparent !important;
    border: none !important;
}

.header .navbar-nav .nav-item.active .nav-link::after {
    content: "" !important;
    position: absolute !important;
    bottom: -2px !important;
    left: 4px !important;
    right: 4px !important;
    height: 2.5px !important;
    background: #FFFFFF !important;
    border-radius: 2px !important;
}

/* Reset all legacy borders on any link */
.header .navbar-nav > li > a,
.navbar-nav > li > a {
    border-bottom: none !important;
}

/* ==========================================================================
   REGISTER BUTTON (Crisp Solid White Pill - Matching Design)
   ========================================================================== */
.header .navbar-nav .nav-link.nav-register-btn,
.header .nav-item .nav-link.register {
    background: #FFFFFF !important;
    color: #2563EB !important;
    font-weight: 700 !important;
    font-size: 14px !important;
    padding: 7px 18px !important;
    border-radius: 8px !important;
    opacity: 1 !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
    border: none !important;
    transition: all 0.15s ease !important;
    margin-left: 2px !important;
}

.header .navbar-nav .nav-link.nav-register-btn:hover,
.header .nav-item .nav-link.register:hover {
    background: #F8FAFC !important;
    color: #1D4ED8 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
}

.header .navbar-nav .nav-link.nav-register-btn::after,
.header .nav-item .nav-link.register::after {
    display: none !important;
}

/* ==========================================================================
   LANGUAGE SELECTOR DROPDOWN (Circular Earth/Globe Icon Only)
   ========================================================================== */
.header .lang-dropdown-item {
    position: relative !important;
    margin-left: 2px !important;
}

.header .lang-toggle-btn {
    padding: 4px 4px !important;
    border: none !important;
    background: transparent !important;
}

.header .lang-toggle-btn::after {
    display: none !important;
}

.header .lang-pill-wrap {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 34px !important;
    height: 34px !important;
    border-radius: 50% !important;
    background: rgba(255, 255, 255, 0.12) !important;
    border: 1px solid rgba(255, 255, 255, 0.25) !important;
    color: #FFFFFF !important;
    transition: all 0.2s ease !important;
    cursor: pointer !important;
}

.header .lang-pill-wrap:hover {
    background: rgba(255, 255, 255, 0.25) !important;
    border-color: rgba(255, 255, 255, 0.5) !important;
    transform: scale(1.06);
}

.header .lang-pill-wrap i.fa-globe {
    font-size: 16px !important;
    color: #FFFFFF !important;
}

.header .lang-dropdown-menu {
    min-width: 160px !important;
    border-radius: 12px !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
    border: 1px solid #E2E8F0 !important;
    padding: 6px !important;
    background: #FFFFFF !important;
    right: 0 !important;
    left: auto !important;
    top: calc(100% + 8px) !important;
}

.header .lang-dropdown-menu .dropdown-item {
    color: #1E293B !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    padding: 8px 12px !important;
    border-radius: 8px !important;
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
    opacity: 1 !important;
}

.header .lang-dropdown-menu .dropdown-item:hover,
.header .lang-dropdown-menu .dropdown-item.active-lang {
    background: #EFF6FF !important;
    color: #2563EB !important;
}

.header .lang-dropdown-menu .lang-flag-dot {
    width: 6px !important;
    height: 6px !important;
    border-radius: 50% !important;
    background: #2563EB !important;
    display: inline-block !important;
}

.header .lang-dropdown-item:hover .lang-dropdown-menu,
.header .lang-dropdown-item.show .lang-dropdown-menu,
.header .lang-dropdown-item .lang-dropdown-menu.show {
    display: block !important;
}

/* ==========================================================================
   USER AVATAR & DROPDOWNS
   ========================================================================== */
.header .userbtn {
    position: relative !important;
}
.header .userbtn > a {
    cursor: pointer !important;
    display: flex !important;
    align-items: center !important;
    padding: 2px 4px !important;
    border: none !important;
}
.header .userbtn > a::after {
    display: none !important;
}
.header .userbtn > a img,
.header .userbtn > a .userimg {
    width: 36px !important;
    height: 36px !important;
    border-radius: 50% !important;
    border: 2px solid rgba(255, 255, 255, 0.9) !important;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15) !important;
    object-fit: cover !important;
}

.header .userbtn .dropdown-menu {
    display: none;
    position: absolute !important;
    top: 100% !important;
    right: 0 !important;
    left: auto !important;
    min-width: 220px !important;
    background: #FFFFFF !important;
    border-radius: 14px !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.16) !important;
    border: 1px solid #E2E8F0 !important;
    padding: 8px 6px !important;
    margin: 0 !important;
    margin-top: 8px !important;
    z-index: 99999 !important;
    list-style: none !important;
}

.header .userbtn:hover .dropdown-menu,
.header .userbtn.show .dropdown-menu,
.header .userbtn .dropdown-menu.show {
    display: block !important;
}

.header .userbtn .dropdown-menu .dropdown-item {
    color: #1E293B !important;
    font-size: 13.5px !important;
    font-weight: 500 !important;
    padding: 9px 14px !important;
    border-radius: 8px !important;
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    text-decoration: none !important;
    opacity: 1 !important;
}

.header .userbtn .dropdown-menu .dropdown-item:hover {
    background: #F1F5F9 !important;
    color: #2563EB !important;
}

/* ==========================================================================
   📱 ULTRA-CLEAN MODERN MOBILE NAVIGATION DRAWER
   ========================================================================== */
@media (max-width: 991px) {
    .header {
        position: sticky !important;
        top: 0 !important;
        z-index: 99999 !important;
        overflow: visible !important;
    }
    .header-container-fluid {
        position: relative !important;
        padding: 10px 16px !important;
        flex-wrap: wrap !important;
    }
    .header-nav-area {
        position: static !important;
        width: 100% !important;
        flex: 1 1 100% !important;
    }
    .header .navbar {
        position: static !important;
        width: 100% !important;
        padding: 0 !important;
    }
    .header .navbar-collapse {
        position: fixed !important;
        top: 58px !important;
        left: 12px !important;
        right: 12px !important;
        width: auto !important;
        background: #FFFFFF !important;
        border-radius: 16px !important;
        padding: 16px 14px 60px 14px !important;
        box-shadow: 0 25px 60px rgba(15, 23, 42, 0.35) !important;
        border: 1.5px solid #E2E8F0 !important;
        max-height: calc(100vh - 74px) !important;
        max-height: calc(100dvh - 74px) !important;
        overflow-y: scroll !important;
        -webkit-overflow-scrolling: touch !important;
        overscroll-behavior: contain !important;
        z-index: 999999 !important;
        touch-action: pan-y !important;
    }
    .header .navbar-collapse::-webkit-scrollbar {
        width: 5px !important;
    }
    .header .navbar-collapse::-webkit-scrollbar-thumb {
        background: #94A3B8 !important;
        border-radius: 5px !important;
    }

    /* Mobile User / Company Header Card */
    .mobile-user-card {
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        background: #F8FAFC !important;
        border: 1.5px solid #E2E8F0 !important;
        border-radius: 12px !important;
        padding: 12px !important;
        margin-bottom: 14px !important;
    }
    .mobile-user-avatar {
        width: 44px !important;
        height: 44px !important;
        border-radius: 10px !important;
        overflow: hidden !important;
        background: #FFFFFF !important;
        border: 1.5px solid #CBD5E1 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        flex-shrink: 0 !important;
    }
    .mobile-user-avatar img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
    }
    .mobile-user-info {
        flex: 1 !important;
        min-width: 0 !important;
    }
    .mobile-user-name {
        font-size: 14.5px !important;
        font-weight: 700 !important;
        color: #0F172A !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }
    .mobile-user-role {
        font-size: 12px !important;
        font-weight: 600 !important;
        color: #64748B !important;
        margin-top: 2px !important;
    }
    .mobile-user-dash-btn {
        width: 38px !important;
        height: 38px !important;
        background: #EFF6FF !important;
        border: 1.5px solid #BFDBFE !important;
        color: #2563EB !important;
        border-radius: 10px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-decoration: none !important;
        font-size: 16px !important;
    }

    /* Mobile Guest Auth Buttons */
    .mobile-guest-actions {
        display: flex !important;
        gap: 10px !important;
        margin-bottom: 14px !important;
        padding-bottom: 14px !important;
        border-bottom: 1px solid #F1F5F9 !important;
    }
    .btn-mobile-login {
        flex: 1 1 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        background: #F8FAFC !important;
        border: 1.5px solid #CBD5E1 !important;
        color: #334155 !important;
        font-size: 13.5px !important;
        font-weight: 700 !important;
        padding: 10px !important;
        border-radius: 9px !important;
        text-decoration: none !important;
    }
    .btn-mobile-register {
        flex: 1 1 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        background: #2563EB !important;
        color: #FFFFFF !important;
        font-size: 13.5px !important;
        font-weight: 700 !important;
        padding: 10px !important;
        border-radius: 9px !important;
        text-decoration: none !important;
    }

    /* Nav Links in Mobile Drawer */
    .header .navbar-nav {
        flex-direction: column !important;
        align-items: stretch !important;
        justify-content: flex-start !important;
        gap: 3px !important;
        padding: 0 !important;
        border: none !important;
        margin: 0 !important;
        width: 100% !important;
    }
    .header .navbar-nav > li,
    .header .navbar-nav .nav-item {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        text-align: left !important;
    }
    .header .navbar-nav .nav-link {
        padding: 10px 12px !important;
        width: 100% !important;
        font-size: 14.5px !important;
        font-weight: 600 !important;
        color: #334155 !important;
        opacity: 1 !important;
        border-radius: 8px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        text-align: left !important;
        transition: all 0.15s ease !important;
    }
    .nav-link-content {
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
    }
    .nav-icon {
        width: 20px !important;
        text-align: center !important;
        font-size: 15px !important;
        color: #2563EB !important;
    }
    .nav-arrow {
        font-size: 14px !important;
        color: #94A3B8 !important;
    }
    .header .navbar-nav .nav-link:hover,
    .header .navbar-nav .nav-link:focus {
        background: #F1F5F9 !important;
        color: #0F172A !important;
    }
    .header .navbar-nav .nav-item.active .nav-link {
        background: #EFF6FF !important;
        color: #2563EB !important;
        font-weight: 700 !important;
    }
    .header .navbar-nav .nav-item.active .nav-link::after {
        display: none !important;
    }

    /* Mobile CTA Post Job Button */
    .mobile-cta-item {
        margin-top: 12px !important;
        margin-bottom: 6px !important;
    }
    .btn-mobile-postjob {
        background: #2563EB !important;
        color: #FFFFFF !important;
        font-size: 14px !important;
        font-weight: 700 !important;
        padding: 11px 16px !important;
        border-radius: 10px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 7px !important;
        text-align: center !important;
        width: 100% !important;
        box-shadow: 0 4px 12px rgba(37,99,235,0.25) !important;
        text-decoration: none !important;
    }
    .btn-mobile-postjob:hover {
        background: #1D4ED8 !important;
        color: #FFFFFF !important;
    }

    .text-danger-mobile .nav-link-content span {
        color: #DC2626 !important;
    }

    /* Language row in Mobile */
    .header .lang-dropdown-item {
        width: 100% !important;
        margin-top: 10px !important;
        padding-top: 10px !important;
        border-top: 1px solid #F1F5F9 !important;
        padding-bottom: 12px !important;
    }
    .header .lang-toggle-btn {
        width: 100% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 9px 12px !important;
        background: #F8FAFC !important;
        border: 1.5px solid #E2E8F0 !important;
        border-radius: 10px !important;
        color: #1E293B !important;
    }
    .header .lang-toggle-btn .lang-pill-wrap {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 32px !important;
        height: 32px !important;
        border-radius: 50% !important;
        background: #EFF6FF !important;
        border: 1.5px solid #BFDBFE !important;
        color: #2563EB !important;
        flex-shrink: 0 !important;
    }
    .header .lang-toggle-btn .lang-pill-wrap i.fa-globe {
        color: #2563EB !important;
        font-size: 16px !important;
    }
    .header .lang-dropdown-menu {
        display: none;
        position: static !important;
        float: none !important;
        width: 100% !important;
        margin-top: 8px !important;
        background: #FFFFFF !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06) !important;
        border: 1.5px solid #E2E8F0 !important;
        border-radius: 10px !important;
        padding: 6px !important;
        max-height: 220px !important;
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch !important;
    }
    .header .lang-dropdown-item.show .lang-dropdown-menu,
    .header .lang-dropdown-menu.show {
        display: block !important;
    }
    .header .lang-dropdown-item.show .lang-caret-icon {
        transform: rotate(180deg);
    }
    .header .lang-dropdown-menu .lang-option-row {
        list-style: none !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .header .lang-dropdown-menu .dropdown-item.lang-choice-btn {
        display: flex !important;
        align-items: center !important;
        justify-content: flex-start !important;
        gap: 10px !important;
        color: #1E293B !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        padding: 10px 14px !important;
        border-radius: 8px !important;
        text-align: left !important;
        width: 100% !important;
        text-decoration: none !important;
        transition: all 0.15s ease !important;
        cursor: pointer !important;
    }
    .header .lang-dropdown-menu .dropdown-item.lang-choice-btn:hover,
    .header .lang-dropdown-menu .dropdown-item.lang-choice-btn.active-lang {
        background: #EFF6FF !important;
        color: #2563EB !important;
    }
    .header .lang-dropdown-menu .dropdown-item.lang-choice-btn.active-lang .lang-name-text {
        color: #2563EB !important;
        font-weight: 700 !important;
    }
    .header .lang-dropdown-menu .lang-flag-dot {
        width: 7px !important;
        height: 7px !important;
        border-radius: 50% !important;
        background: #2563EB !important;
        display: inline-block !important;
        flex-shrink: 0 !important;
    }
    .header .lang-dropdown-menu .lang-name-text {
        color: #1E293B !important;
        font-size: 14px !important;
    }
    .header .lang-dropdown-menu .lang-check-icon {
        color: #2563EB !important;
        font-size: 14px !important;
    }
}

/* =========================================================
   🔔 REAL-TIME NOTIFICATION SYSTEM STYLES
   ========================================================= */
.notif-dropdown-nav {
    position: relative !important;
}
.notif-toggle-btn {
    padding: 6px 10px !important;
    display: flex !important;
    align-items: center !important;
    outline: none !important;
}
.notif-bell-wrap {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #FFFFFF;
    font-size: 16px;
    position: relative;
    transition: all 0.2s ease;
}
.notif-toggle-btn:hover .notif-bell-wrap {
    background: rgba(255, 255, 255, 0.22);
    transform: scale(1.05);
}
.notif-badge-count {
    position: absolute;
    top: -4px;
    right: -4px;
    background: #EF4444;
    color: #FFFFFF;
    font-size: 10px;
    font-weight: 800;
    min-width: 18px;
    height: 18px;
    line-height: 18px;
    text-align: center;
    border-radius: 50%;
    border: 2px solid #0F172A;
    box-shadow: 0 0 8px rgba(239, 68, 68, 0.6);
    animation: pulseBadge 2s infinite;
}
@keyframes pulseBadge {
    0% { transform: scale(1); }
    50% { transform: scale(1.15); }
    100% { transform: scale(1); }
}
.notif-dropdown-panel {
    width: 360px !important;
    max-width: 90vw !important;
    padding: 0 !important;
    border-radius: 16px !important;
    border: 1px solid #E2E8F0 !important;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.15) !important;
    overflow: hidden !important;
    background: #FFFFFF !important;
    margin-top: 10px !important;
}
.notif-panel-header {
    padding: 14px 18px;
    background: #F8FAFC;
    border-bottom: 1px solid #E2E8F0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.notif-panel-title {
    font-size: 14px;
    font-weight: 800;
    color: #0F172A;
    display: flex;
    align-items: center;
    gap: 6px;
}
.notif-header-badge {
    background: #EFF6FF;
    color: #2563EB;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
}
.notif-mark-all-btn {
    font-size: 12px;
    font-weight: 600;
    color: #64748B;
    text-decoration: none;
    transition: color 0.15s ease;
}
.notif-mark-all-btn:hover {
    color: #2563EB;
    text-decoration: none;
}
.notif-panel-body {
    max-height: 340px;
    overflow-y: auto;
    padding: 0;
}
.notif-loading-state,
.notif-empty-state {
    padding: 30px 16px;
    text-align: center;
    color: #94A3B8;
    font-size: 13px;
}
.notif-dropdown-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 16px;
    border-bottom: 1px solid #F1F5F9;
    text-decoration: none !important;
    color: inherit !important;
    transition: background 0.15s ease;
    position: relative;
}
.notif-dropdown-item:hover {
    background: #F8FAFC !important;
}
.notif-dropdown-item.unread-item {
    background: #F0F7FF;
}
.notif-item-icon {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
}
.notif-item-body {
    flex: 1;
    min-width: 0;
}
.notif-item-title {
    font-size: 13px;
    font-weight: 700;
    color: #0F172A;
    margin: 0 0 2px 0;
    line-height: 1.3;
}
.notif-item-desc {
    font-size: 12px;
    color: #64748B;
    margin: 0;
    line-height: 1.4;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.notif-item-time {
    font-size: 10.5px;
    color: #94A3B8;
    margin-top: 3px;
}
.notif-unread-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #2563EB;
    align-self: center;
    flex-shrink: 0;
}
.notif-panel-footer {
    padding: 10px 16px;
    background: #F8FAFC;
    border-top: 1px solid #E2E8F0;
    text-align: center;
}
.notif-view-all-link {
    font-size: 12.5px;
    font-weight: 700;
    color: #2563EB;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.notif-view-all-link:hover {
    text-decoration: underline;
}

@media (max-width: 991px) {
    .notif-dropdown-panel {
        position: static !important;
        width: 100% !important;
        max-width: 100% !important;
        box-shadow: none !important;
        border: none !important;
        border-radius: 0 !important;
    }
    .mobile-notif-label {
        color: #FFFFFF;
        font-size: 14px;
        font-weight: 600;
        margin-left: 10px;
    }
}
</style>

<script>
function renderNotificationsList(data) {
    var count = data.unread_count || 0;
    var badge = document.getElementById('notif_badge_count');
    var headerBadge = document.getElementById('notif_header_badge');
    var container = document.getElementById('notif_items_container');

    if (badge) {
        if (count > 0) {
            badge.innerText = count > 99 ? '99+' : count;
            badge.classList.remove('d-none');
        } else {
            badge.classList.add('d-none');
        }
    }

    if (headerBadge) {
        headerBadge.innerText = count + ' New';
    }

    if (!container) return;

    if (!data.notifications || data.notifications.length === 0) {
        container.innerHTML = '<div class="notif-empty-state"><i class="fa fa-bell-slash-o" style="font-size: 24px; color: #CBD5E1; margin-bottom: 6px; display: block;"></i>{{ __("No notifications yet") }}</div>';
        return;
    }

    var html = '';
    data.notifications.forEach(function(item) {
        var unreadClass = !item.is_read ? 'unread-item' : '';
        var bgLight = item.color ? (item.color + '18') : '#EFF6FF';
        var colorHex = item.color || '#2563EB';
        var iconClass = item.icon || 'fa-bell';

        html += '<a href="' + item.read_url + '" class="notif-dropdown-item ' + unreadClass + '">';
        html += '  <div class="notif-item-icon" style="background: ' + bgLight + '; color: ' + colorHex + ';">';
        html += '    <i class="fa ' + iconClass + '"></i>';
        html += '  </div>';
        html += '  <div class="notif-item-body">';
        html += '    <div class="notif-item-title">' + $('<div>').text(item.title).html() + '</div>';
        html += '    <div class="notif-item-desc">' + $('<div>').text(item.message).html() + '</div>';
        html += '    <div class="notif-item-time"><i class="fa fa-clock-o"></i> ' + item.time_ago + '</div>';
        html += '  </div>';
        if (!item.is_read) {
            html += '  <div class="notif-unread-dot"></div>';
        }
        html += '</a>';
    });

    container.innerHTML = html;
}

function fetchRealtimeNotifications() {
    if (!document.getElementById('notif_items_container')) return;
    $.ajax({
        url: "{{ route('notification.fetch') }}",
        type: "GET",
        dataType: "json",
        success: function(response) {
            renderNotificationsList(response);
        }
    });
}

function markAllNotificationsReadDropdown() {
    $.ajax({
        url: "{{ route('notification.mark-all-read') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}"
        },
        success: function(res) {
            fetchRealtimeNotifications();
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    var navMain = document.getElementById('nav-main');
    if (!navMain) return;

    var lastScrollY = window.scrollY || window.pageYOffset;
    var isNavOpen = false;

    // Initial Notification Fetch
    fetchRealtimeNotifications();

    // Auto-poll notifications every 20 seconds
    setInterval(fetchRealtimeNotifications, 20000);

    // Monitor Bootstrap collapse events
    if (window.jQuery) {
        $(navMain).on('shown.bs.collapse', function() {
            isNavOpen = true;
            lastScrollY = window.scrollY || window.pageYOffset;
        });
        $(navMain).on('hidden.bs.collapse', function() {
            isNavOpen = false;
        });
    }

    // Auto-close on page scroll upwards or downwards
    window.addEventListener('scroll', function() {
        var currentScrollY = window.scrollY || window.pageYOffset;
        if (isNavOpen && Math.abs(currentScrollY - lastScrollY) > 15) {
            if (window.jQuery) {
                $(navMain).collapse('hide');
            } else {
                navMain.classList.remove('show');
            }
            isNavOpen = false;
        }
        lastScrollY = currentScrollY;
    }, { passive: true });

    // Auto-close when drawer is swiped/dragged upwards
    var startTouchY = 0;
    navMain.addEventListener('touchstart', function(e) {
        if (e.touches && e.touches.length > 0) {
            startTouchY = e.touches[0].clientY;
        }
    }, { passive: true });

    navMain.addEventListener('touchmove', function(e) {
        if (!e.touches || e.touches.length === 0) return;
        var currentTouchY = e.touches[0].clientY;
        var diffY = startTouchY - currentTouchY;
        if (diffY > 40) {
            if (window.jQuery) {
                $(navMain).collapse('hide');
            } else {
                navMain.classList.remove('show');
            }
            isNavOpen = false;
        }
    }, { passive: true });

    // Mobile Language accordion toggle
    window.toggleMobLanguageAccordion = function(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        var list = document.getElementById('mobLangDrawerList');
        var arrow = document.getElementById('mobLangArrow');
        if (list) {
            if (list.style.display === 'none' || list.style.display === '') {
                list.style.display = 'block';
                if (arrow) arrow.style.transform = 'rotate(180deg)';
            } else {
                list.style.display = 'none';
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            }
        }
    };
});
</script>