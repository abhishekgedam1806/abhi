<div class="col-lg-3">
    
    <!-- Mobile Menu Toggle Button (Visible ONLY on Mobile <992px) -->
    <button type="button" class="mobile-biz-menu-btn d-lg-none" onclick="toggleMobileBizMenu()" style="width: 100%; display: flex; align-items: center; justify-content: space-between; background: #1E293B; color: #FFFFFF !important; border: 1px solid #334155; border-radius: 12px; padding: 13px 16px; font-size: 14px; font-weight: 700; cursor: pointer; margin-bottom: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); outline: none;">
        <span style="display: flex; align-items: center; gap: 10px; color: #FFFFFF !important;">
            <i class="fa fa-briefcase" style="color: #60A5FA; font-size: 16px;"></i>
            <span style="color: #FFFFFF !important;">Business Dashboard Menu</span>
        </span>
        <i class="fa fa-chevron-down" id="mobileBizMenuCaret" style="color: #94A3B8; font-size: 13px; transition: transform 0.25s ease;"></i>
    </button>

    <!-- Collapsible Menu Content (Always visible on Desktop, togglable on Mobile) -->
    <div id="mobileBizMenuContent" class="biz-menu-collapsible">
        <div class="usernavwrap" style="background:#fff;border-radius:16px;border:1px solid #E2E8F0;padding:20px;box-shadow:0 1px 4px rgba(0,0,0,0.04);margin-bottom:24px;">
            
            {{-- Business Owner Badge --}}
            <div style="background: #1E293B; border-radius: 12px; padding: 14px 16px; color: #fff; margin-bottom: 18px; text-align: center;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: #2563EB; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 8px;">
                    <i class="fa fa-briefcase"></i>
                </div>
                <h4 style="font-size: 15px; font-weight: 800; color: #FFFFFF !important; margin: 0;">{{ Auth::user()->name }}</h4>
                <span style="display:inline-block;margin-top:6px;font-size:11px;font-weight:700;color:#93C5FD;background:rgba(37,99,235,0.25);padding:2px 10px;border-radius:12px;">
                    Business Owner Portal
                </span>
            </div>

            <ul class="usernavdash" style="list-style:none;padding:0;margin:0;">
                {{-- 1. Dashboard --}}
                <li class="{{ Request::is('business-dashboard') ? 'active' : '' }}" style="margin-bottom:4px;">
                    <a href="{{ route('business.dashboard') }}" style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:8px;font-size:13.5px;font-weight:600;color:{{ Request::is('business-dashboard') ? '#2563EB' : '#334155' }};background:{{ Request::is('business-dashboard') ? '#EFF6FF' : 'transparent' }};text-decoration:none;">
                        <i class="fa fa-tachometer" style="font-size:16px;color:#2563EB;width:20px;"></i> Dashboard
                    </a>
                </li>

                {{-- 2. Edit Profile --}}
                <li class="{{ Request::is('business-profile*') ? 'active' : '' }}" style="margin-bottom:4px;">
                    <a href="{{ route('business.owner.profile') }}" style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:8px;font-size:13.5px;font-weight:600;color:{{ Request::is('business-profile*') ? '#2563EB' : '#334155' }};background:{{ Request::is('business-profile*') ? '#EFF6FF' : 'transparent' }};text-decoration:none;">
                        <i class="fa fa-user-circle" style="font-size:16px;color:#03855c;width:20px;"></i> Edit Profile
                    </a>
                </li>

                {{-- 4. My Businesses --}}
                <li class="{{ Request::is('my-businesses') || Request::is('add-business') || Request::is('edit-business*') ? 'active' : '' }}" style="margin-bottom:4px;">
                    <a href="{{ route('my.businesses') }}" style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:8px;font-size:13.5px;font-weight:600;color:{{ Request::is('my-businesses') || Request::is('add-business') || Request::is('edit-business*') ? '#2563EB' : '#334155' }};background:{{ Request::is('my-businesses') || Request::is('add-business') || Request::is('edit-business*') ? '#EFF6FF' : 'transparent' }};text-decoration:none;">
                        <i class="fa fa-building" style="font-size:16px;color:#EA580C;width:20px;"></i> My Businesses
                    </a>
                </li>

                {{-- 5. Business Leads --}}
                <li class="{{ Request::is('my-business-leads') || Request::is('business-leads*') ? 'active' : '' }}" style="margin-bottom:4px;">
                    <a href="{{ route('business.all.leads') }}" style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:8px;font-size:13.5px;font-weight:600;color:{{ Request::is('my-business-leads') || Request::is('business-leads*') ? '#2563EB' : '#334155' }};background:{{ Request::is('my-business-leads') || Request::is('business-leads*') ? '#EFF6FF' : 'transparent' }};text-decoration:none;">
                        <i class="fa fa-phone" style="font-size:16px;color:#0284C7;width:20px;"></i> Business Leads
                    </a>
                </li>

                {{-- 6. Packages & Upgrade --}}
                <li class="{{ Request::is('business-packages*') || Request::is('business-pay-*') ? 'active' : '' }}" style="margin-bottom:4px;">
                    <a href="{{ route('business.packages') }}" style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:8px;font-size:13.5px;font-weight:600;color:{{ Request::is('business-packages*') || Request::is('business-pay-*') ? '#2563EB' : '#334155' }};background:{{ Request::is('business-packages*') || Request::is('business-pay-*') ? '#EFF6FF' : 'transparent' }};text-decoration:none;">
                        <i class="fa fa-credit-card" style="font-size:16px;color:#10B981;width:20px;"></i> Packages & Upgrade
                    </a>
                </li>

                {{-- 7. Logout --}}
                <li style="margin-top:12px;border-top:1px solid #F1F5F9;padding-top:10px;">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-biz').submit();" style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:8px;font-size:13.5px;font-weight:600;color:#DC2626;text-decoration:none;">
                        <i class="fa fa-sign-out" style="font-size:16px;color:#DC2626;width:20px;"></i> Logout
                    </a>
                    <form id="logout-form-biz" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
@media (max-width: 991px) {
    .biz-menu-collapsible {
        display: none;
    }
    .biz-menu-collapsible.show-mobile {
        display: block !important;
        animation: fadeInDown 0.2s ease-out;
    }
}
@media (min-width: 992px) {
    .mobile-biz-menu-btn {
        display: none !important;
    }
    .biz-menu-collapsible {
        display: block !important;
    }
}
@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
function toggleMobileBizMenu() {
    var menu = document.getElementById('mobileBizMenuContent');
    var caret = document.getElementById('mobileBizMenuCaret');
    if (menu) {
        menu.classList.toggle('show-mobile');
        if (menu.classList.contains('show-mobile')) {
            if (caret) caret.style.transform = 'rotate(180deg)';
        } else {
            if (caret) caret.style.transform = 'rotate(0deg)';
        }
    }
}
</script>
