<li class="nav-item {{ Request::is('admin/site-setting*') ? 'active open' : '' }}"> 
    <a href="javascript:;" class="nav-link nav-toggle"> 
        <i class="icon-wrench"></i> 
        <span class="title">Site Settings</span> 
        <span class="arrow {{ Request::is('admin/site-setting*') ? 'open' : '' }}"></span> 
    </a>
    <ul class="sub-menu">
        <li class="nav-item"> 
            <a href="{{ route('edit.site.setting') }}" class="nav-link"> 
                <span class="title">Manage Site Settings</span> 
            </a> 
        </li>
        <li class="nav-item"> 
            <a href="{{ route('admin.smtp.settings') }}" class="nav-link"> 
                <span class="title"><i class="fa fa-envelope text-info" style="font-size:12px;margin-right:4px;"></i> SMTP Settings</span> 
            </a> 
        </li>
        <li class="nav-item"> 
            <a href="{{ route('edit.site.setting') }}#paymentGateways" class="nav-link"> 
                <span class="title"><i class="fa fa-credit-card text-success" style="font-size:12px;margin-right:4px;"></i> Payment Gateways</span> 
            </a> 
        </li>
        <li class="nav-item"> 
            <a href="{{ route('admin.otp.logs') }}" class="nav-link"> 
                <span class="title"><i class="fa fa-shield text-danger" style="font-size:12px;margin-right:4px;"></i> OTP & Security Logs</span> 
            </a> 
        </li>
    </ul>
</li>