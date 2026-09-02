<div class="col-lg-3 col-md-4">
    <style type="text/css">
        .dashboard-sidebar-card {
            background: #FFFFFF;
            border: 1.5px solid #E2E8F0;
            border-radius: 16px;
            padding: 14px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            margin-bottom: 20px;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .sidebar-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 10px;
            color: #334155 !important;
            font-size: 13.5px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .sidebar-nav-link i {
            color: #64748B !important;
            font-size: 15px;
            width: 18px;
            text-align: center;
            transition: color 0.15s ease;
        }
        .sidebar-nav-link:hover {
            background: #F1F5F9 !important;
            color: #2563EB !important;
            text-decoration: none;
        }
        .sidebar-nav-link:hover i {
            color: #2563EB !important;
        }
        .sidebar-nav-active-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 10px;
            background: #2563EB !important;
            color: #FFFFFF !important;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(37,99,235,0.25);
        }
        .sidebar-nav-active-link i {
            color: #FFFFFF !important;
            font-size: 15px;
            width: 18px;
            text-align: center;
        }
        .sidebar-nav-active-link:hover {
            color: #FFFFFF !important;
            text-decoration: none;
        }
        .sidebar-nav-link-logout {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 10px;
            color: #DC2626 !important;
            font-size: 13.5px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .sidebar-nav-link-logout i {
            color: #DC2626 !important;
            font-size: 15px;
            width: 18px;
            text-align: center;
        }
        .sidebar-nav-link-logout:hover {
            background: #FEF2F2 !important;
            color: #DC2626 !important;
            text-decoration: none;
        }

        /* Mobile Collapsible Behavior */
        @media (max-width: 991px) {
            .dashboard-menu-collapsible {
                display: none;
                margin-top: 14px;
                padding-top: 12px;
                border-top: 1px solid #E2E8F0;
            }
            .mobile-sidebar-toggle-btn:hover,
            .mobile-sidebar-toggle-btn:focus {
                background: #F1F5F9 !important;
                border-color: #CBD5E1 !important;
            }
        }
        @media (min-width: 992px) {
            .dashboard-menu-collapsible {
                display: block !important;
            }
            .mobile-sidebar-toggle-btn {
                display: none !important;
            }
        }
    </style>

    <div class="dashboard-sidebar-card">
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
                <li>
                    @if(request()->is('home'))
                        <a href="{{route('home')}}" class="sidebar-nav-active-link">
                            <i class="fa fa-tachometer"></i>
                            <span>{{__('Dashboard')}}</span>
                        </a>
                    @else
                        <a href="{{route('home')}}" class="sidebar-nav-link">
                            <i class="fa fa-tachometer"></i>
                            <span>{{__('Dashboard')}}</span>
                        </a>
                    @endif
                </li>
                <li>
                    @if(request()->is('my-profile') && !request()->has('tab'))
                        <a href="{{ route('my.profile') }}" class="sidebar-nav-active-link">
                            <i class="fa fa-pencil"></i>
                            <span>{{__('Edit Profile')}}</span>
                        </a>
                    @else
                        <a href="{{ route('my.profile') }}" class="sidebar-nav-link">
                            <i class="fa fa-pencil"></i>
                            <span>{{__('Edit Profile')}}</span>
                        </a>
                    @endif
                </li>
                <li>
                    <a href="{{ route('view.public.profile', Auth::user()->id) }}" class="sidebar-nav-link">
                        <i class="fa fa-eye"></i>
                        <span>{{__('View Public Profile')}}</span>
                    </a>
                </li>
                <li>
                    @if(request()->is('my-job-applications') && !request()->has('tab'))
                        <a href="{{ route('my.job.applications') }}" class="sidebar-nav-active-link">
                            <i class="fa fa-desktop"></i>
                            <span>{{__('My Applications')}}</span>
                        </a>
                    @else
                        <a href="{{ route('my.job.applications') }}" class="sidebar-nav-link">
                            <i class="fa fa-desktop"></i>
                            <span>{{__('My Applications')}}</span>
                        </a>
                    @endif
                </li>
                <li>
                    @if(request()->is('my-job-applications') && request()->get('tab') === 'invites')
                        <a href="{{ route('my.job.applications', ['tab' => 'invites']) }}" class="sidebar-nav-active-link">
                            <i class="fa fa-bullhorn"></i>
                            <span>{{__('Interview Invites')}}</span>
                        </a>
                    @else
                        <a href="{{ route('my.job.applications', ['tab' => 'invites']) }}" class="sidebar-nav-link">
                            <i class="fa fa-bullhorn"></i>
                            <span>{{__('Interview Invites')}}</span>
                        </a>
                    @endif
                </li>
                <li>
                    @if(request()->is('my-favourite-jobs'))
                        <a href="{{ route('my.favourite.jobs') }}" class="sidebar-nav-active-link">
                            <i class="fa fa-heart"></i>
                            <span>{{__('My Favourite Jobs')}}</span>
                        </a>
                    @else
                        <a href="{{ route('my.favourite.jobs') }}" class="sidebar-nav-link">
                            <i class="fa fa-heart"></i>
                            <span>{{__('My Favourite Jobs')}}</span>
                        </a>
                    @endif
                </li>
                <li>
                    @if(request()->is('my-alerts'))
                        <a href="{{ route('my-alerts') }}" class="sidebar-nav-active-link">
                            <i class="fa fa-bell"></i>
                            <span>{{__('Job Alerts')}}</span>
                        </a>
                    @else
                        <a href="{{ route('my-alerts') }}" class="sidebar-nav-link">
                            <i class="fa fa-bell"></i>
                            <span>{{__('Job Alerts')}}</span>
                        </a>
                    @endif
                </li>
                <li>
                    <a href="{{url('my-profile#cvs')}}" onclick="if(window.location.pathname.indexOf('my-profile') !== -1){ window.location.hash = 'cvs'; if(typeof scrollToHash === 'function'){ scrollToHash(); } return false; }" class="sidebar-nav-link">
                        <i class="fa fa-file-text"></i>
                        <span>{{__('Manage Resume')}}</span>
                    </a>
                </li>
                <li>
                    @if(request()->is('my-messages'))
                        <a href="{{route('my.messages')}}" class="sidebar-nav-active-link">
                            <i class="fa fa-envelope-o"></i>
                            <span>{{__('My Messages')}}</span>
                        </a>
                    @else
                        <a href="{{route('my.messages')}}" class="sidebar-nav-link">
                            <i class="fa fa-envelope-o"></i>
                            <span>{{__('My Messages')}}</span>
                        </a>
                    @endif
                </li>
                <li>
                    @if(request()->is('my-followings'))
                        <a href="{{route('my.followings')}}" class="sidebar-nav-active-link">
                            <i class="fa fa-user-o"></i>
                            <span>{{__('My Followings')}}</span>
                        </a>
                    @else
                        <a href="{{route('my.followings')}}" class="sidebar-nav-link">
                            <i class="fa fa-user-o"></i>
                            <span>{{__('My Followings')}}</span>
                        </a>
                    @endif
                </li>
                <li>
                    <a href="{{ route('pricing', ['tab' => 'candidates']) }}" class="sidebar-nav-link" style="color: #2563EB; font-weight: 700;">
                        <i class="fa fa-arrow-circle-up" style="color: #2563EB;"></i>
                        <span>{{__('Upgrade Membership')}}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="sidebar-nav-link-logout">
                        <i class="fa fa-sign-out"></i>
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

    <script type="text/javascript">
        function toggleMobileDashboardMenu() {
            var menu = $('#mobileDashboardMenuContent');
            var caret = $('#mobileMenuCaret');
            if (menu.is(':visible')) {
                menu.slideUp(200, function() {
                    menu.removeClass('show');
                });
                caret.css('transform', 'rotate(0deg)');
            } else {
                menu.slideDown(200, function() {
                    menu.addClass('show');
                });
                caret.css('transform', 'rotate(180deg)');
            }
        }
    </script>
</div>