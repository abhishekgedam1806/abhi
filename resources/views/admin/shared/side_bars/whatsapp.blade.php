<li class="nav-item {{ Request::is('admin/whatsapp*') ? 'active open' : '' }}">
    <a href="javascript:;" class="nav-link nav-toggle">
        <i class="fa fa-whatsapp" aria-hidden="true" style="color: #25D366; font-size: 15px;"></i>
        <span class="title" style="font-weight: 600;">WhatsApp Desk</span>
        <span class="arrow {{ Request::is('admin/whatsapp*') ? 'open' : '' }}"></span>
    </a>
    <ul class="sub-menu">
        <li class="nav-item {{ Request::is('admin/whatsapp') ? 'active' : '' }}">
            <a href="{{ route('admin.whatsapp.index') }}" class="nav-link ">
                <i class="fa fa-dashboard" style="color: #60A5FA; font-size: 13px;"></i>
                <span class="title">Overview & KPIs</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/whatsapp/settings*') ? 'active' : '' }}">
            <a href="{{ route('admin.whatsapp.settings') }}" class="nav-link ">
                <i class="fa fa-sliders" style="color: #F59E0B; font-size: 13px;"></i>
                <span class="title">Provider & Settings</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/whatsapp/templates*') ? 'active' : '' }}">
            <a href="{{ route('admin.whatsapp.templates') }}" class="nav-link ">
                <i class="fa fa-file-text-o" style="color: #8B5CF6; font-size: 13px;"></i>
                <span class="title">Templates Registry</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/whatsapp/logs*') ? 'active' : '' }}">
            <a href="{{ route('admin.whatsapp.logs') }}" class="nav-link ">
                <i class="fa fa-history" style="color: #10B981; font-size: 13px;"></i>
                <span class="title">Delivery Logs</span>
            </a>
        </li>
    </ul>
</li>
