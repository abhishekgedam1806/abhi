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
                <li class="{{ request()->is('company-home') ? 'nav-item-active' : '' }}">
                    @if(request()->is('company-home'))
                        <a href="{{route('company.home')}}" style="display: flex; align-items: center; gap: 12px; padding: 11px 16px; border-radius: 10px; background: #2563EB; color: #FFFFFF; font-size: 14px; font-weight: 700; text-decoration: none;">
                            <i class="fa fa-tachometer" style="font-size: 15px; width: 18px; text-align: center;"></i>
                            <span>{{__('Dashboard')}}</span>
                        </a>
                    @else
                        <a href="{{route('company.home')}}" class="sidebar-nav-link" style="display: flex; align-items: center; gap: 12px; padding: 11px 16px; border-radius: 10px; color: #334155; font-size: 14px; font-weight: 600; text-decoration: none; transition: all 0.15s ease;">
                            <i class="fa fa-tachometer" style="font-size: 15px; width: 18px; text-align: center; color: #64748B;"></i>
                            <span>{{__('Dashboard')}}</span>
                        </a>
                    @endif
                </li>
                <li class="{{ request()->is('company-profile') ? 'nav-item-active' : '' }}">
                    @if(request()->is('company-profile'))
                        <a href="{{ route('company.profile') }}" style="display: flex; align-items: center; gap: 12px; padding: 11px 16px; border-radius: 10px; background: #2563EB; color: #FFFFFF; font-size: 14px; font-weight: 700; text-decoration: none;">
                            <i class="fa fa-pencil" style="font-size: 15px; width: 18px; text-align: center;"></i>
                            <span>{{__('Edit Profile')}}</span>
                        </a>
                    @else
                        <a href="{{ route('company.profile') }}" class="sidebar-nav-link" style="display: flex; align-items: center; gap: 12px; padding: 11px 16px; border-radius: 10px; color: #334155; font-size: 14px; font-weight: 600; text-decoration: none; transition: all 0.15s ease;">
                            <i class="fa fa-pencil" style="font-size: 15px; width: 18px; text-align: center; color: #64748B;"></i>
                            <span>{{__('Edit Profile')}}</span>
                        </a>
                    @endif
                </li>
                <li>
                    <a href="{{ route('company.detail', Auth::guard('company')->user()->slug ?? '') }}" class="sidebar-nav-link" style="display: flex; align-items: center; gap: 12px; padding: 11px 16px; border-radius: 10px; color: #334155; font-size: 14px; font-weight: 600; text-decoration: none; transition: all 0.15s ease;">
                        <i class="fa fa-building-o" style="font-size: 15px; width: 18px; text-align: center; color: #64748B;"></i>
                        <span>{{__('Company Public Profile')}}</span>
                    </a>
                </li>
                <li class="{{ request()->is('post-job') ? 'nav-item-active' : '' }}">
                    <a href="{{ route('post.job') }}" class="sidebar-nav-link" style="display: flex; align-items: center; gap: 12px; padding: 11px 16px; border-radius: 10px; color: #03855c !important; font-size: 14px; font-weight: 700; text-decoration: none; transition: all 0.15s ease;">
                        <i class="fa fa-plus-circle" style="font-size: 15px; width: 18px; text-align: center; color: #03855c;"></i>
                        <span>{{__('Post New Job')}}</span>
                    </a>
                </li>
                <li class="{{ request()->is('posted-jobs') ? 'nav-item-active' : '' }}">
                    @if(request()->is('posted-jobs'))
                        <a href="{{ route('posted.jobs') }}" style="display: flex; align-items: center; gap: 12px; padding: 11px 16px; border-radius: 10px; background: #2563EB; color: #FFFFFF; font-size: 14px; font-weight: 700; text-decoration: none;">
                            <i class="fa fa-briefcase" style="font-size: 15px; width: 18px; text-align: center;"></i>
                            <span>{{__('Company Jobs')}}</span>
                        </a>
                    @else
                        <a href="{{ route('posted.jobs') }}" class="sidebar-nav-link" style="display: flex; align-items: center; gap: 12px; padding: 11px 16px; border-radius: 10px; color: #334155; font-size: 14px; font-weight: 600; text-decoration: none; transition: all 0.15s ease;">
                            <i class="fa fa-briefcase" style="font-size: 15px; width: 18px; text-align: center; color: #64748B;"></i>
                            <span>{{__('Company Jobs')}}</span>
                        </a>
                    @endif
                </li>

                <li class="{{ request()->is('company-messages') ? 'nav-item-active' : '' }}">
                    @if(request()->is('company-messages'))
                        <a href="{{route('company.messages')}}" style="display: flex; align-items: center; gap: 12px; padding: 11px 16px; border-radius: 10px; background: #2563EB; color: #FFFFFF; font-size: 14px; font-weight: 700; text-decoration: none;">
                            <i class="fa fa-envelope-o" style="font-size: 15px; width: 18px; text-align: center;"></i>
                            <span>{{__('Company Messages')}}</span>
                        </a>
                    @else
                        <a href="{{route('company.messages')}}" class="sidebar-nav-link" style="display: flex; align-items: center; gap: 12px; padding: 11px 16px; border-radius: 10px; color: #334155; font-size: 14px; font-weight: 600; text-decoration: none; transition: all 0.15s ease;">
                            <i class="fa fa-envelope-o" style="font-size: 15px; width: 18px; text-align: center; color: #64748B;"></i>
                            <span>{{__('Company Messages')}}</span>
                        </a>
                    @endif
                </li>

                <li class="{{ request()->is('company-followers') ? 'nav-item-active' : '' }}">
                    @if(request()->is('company-followers'))
                        <a href="{{route('company.followers')}}" style="display: flex; align-items: center; gap: 12px; padding: 11px 16px; border-radius: 10px; background: #2563EB; color: #FFFFFF; font-size: 14px; font-weight: 700; text-decoration: none;">
                            <i class="fa fa-users" style="font-size: 15px; width: 18px; text-align: center;"></i>
                            <span>{{__('Company Followers')}}</span>
                        </a>
                    @else
                        <a href="{{route('company.followers')}}" class="sidebar-nav-link" style="display: flex; align-items: center; gap: 12px; padding: 11px 16px; border-radius: 10px; color: #334155; font-size: 14px; font-weight: 600; text-decoration: none; transition: all 0.15s ease;">
                            <i class="fa fa-users" style="font-size: 15px; width: 18px; text-align: center; color: #64748B;"></i>
                            <span>{{__('Company Followers')}}</span>
                        </a>
                    @endif
                </li>

                <li class="{{ request()->is('company-payments') || request()->is('my-payments') ? 'nav-item-active' : '' }}">
                    @if(request()->is('company-payments') || request()->is('my-payments'))
                        <a href="{{route('company.my.payments')}}" style="display: flex; align-items: center; gap: 12px; padding: 11px 16px; border-radius: 10px; background: #2563EB; color: #FFFFFF; font-size: 14px; font-weight: 700; text-decoration: none;">
                            <i class="fa fa-credit-card" style="font-size: 15px; width: 18px; text-align: center;"></i>
                            <span>{{__('Transactions & Invoices')}}</span>
                        </a>
                    @else
                        <a href="{{route('company.my.payments')}}" class="sidebar-nav-link" style="display: flex; align-items: center; gap: 12px; padding: 11px 16px; border-radius: 10px; color: #334155; font-size: 14px; font-weight: 600; text-decoration: none; transition: all 0.15s ease;">
                            <i class="fa fa-credit-card" style="font-size: 15px; width: 18px; text-align: center; color: #64748B;"></i>
                            <span>{{__('Transactions & Invoices')}}</span>
                        </a>
                    @endif
                </li>

                <li>
                    <a href="{{ route('pricing', ['tab' => 'employers']) }}" class="sidebar-nav-link" style="display: flex; align-items: center; gap: 12px; padding: 11px 16px; border-radius: 10px; color: #2563EB !important; font-size: 14px; font-weight: 700; text-decoration: none; transition: all 0.15s ease;">
                        <i class="fa fa-arrow-circle-up" style="font-size: 15px; width: 18px; text-align: center; color: #2563EB;"></i>
                        <span>{{__('Packages & Upgrades')}}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('company.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="sidebar-nav-link text-danger" style="display: flex; align-items: center; gap: 12px; padding: 11px 16px; border-radius: 10px; color: #DC2626 !important; font-size: 14px; font-weight: 600; text-decoration: none; transition: all 0.15s ease;">
                        <i class="fa fa-sign-out" style="font-size: 15px; width: 18px; text-align: center; color: #DC2626;"></i>
                        <span>{{__('Logout')}}</span>
                    </a>
                    <form id="logout-form" action="{{ route('company.logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
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
    <style type="text/css">
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
</div>

<script type="text/javascript">
function toggleMobileCompanyMenu() {
    var menu = $('#mobileCompanyMenuContent');
    var caret = $('#mobileCompanyMenuCaret');
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