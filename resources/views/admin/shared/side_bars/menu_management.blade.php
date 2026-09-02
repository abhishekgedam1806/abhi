<li class="nav-item {{ Request::is('admin/manage-header*') || Request::is('admin/manage-footer*') ? 'active open' : '' }}">
    <a href="javascript:;" class="nav-link nav-toggle">
        <i class="fa fa-bars" aria-hidden="true"></i>
        <span class="title">Header & Footer</span>
        <span class="arrow {{ Request::is('admin/manage-header*') || Request::is('admin/manage-footer*') ? 'open' : '' }}"></span>
    </a>
    <ul class="sub-menu">
        <li class="nav-item {{ Request::is('admin/manage-header*') ? 'active' : '' }}">
            <a href="{{ route('admin.manage.header') }}" class="nav-link">
                <i class="fa fa-compass"></i> <span class="title">Manage Header</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/manage-footer*') ? 'active' : '' }}">
            <a href="{{ route('admin.manage.footer') }}" class="nav-link">
                <i class="fa fa-sitemap"></i> <span class="title">Manage Footer</span>
            </a>
        </li>
    </ul>
</li>
