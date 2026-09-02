<li class="nav-item {{ Request::is('admin/*business*') ? 'active open' : '' }}">
    <a href="javascript:;" class="nav-link nav-toggle">
        <i class="fa fa-building"></i>
        <span class="title">Business Directory</span>
        <span class="arrow {{ Request::is('admin/*business*') ? 'open' : '' }}"></span>
    </a>
    <ul class="sub-menu">
        <li class="nav-item {{ Request::is('admin/list-businesses') ? 'active' : '' }}">
            <a href="{{ route('admin.list.businesses') }}" class="nav-link">
                <span class="title">All Businesses</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/create-business') ? 'active' : '' }}">
            <a href="{{ route('admin.create.business') }}" class="nav-link">
                <span class="title">Add New Business</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/list-business-categories') ? 'active' : '' }}">
            <a href="{{ route('admin.list.business_categories') }}" class="nav-link">
                <span class="title">Business Categories</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/list-packages*') ? 'active' : '' }}">
            <a href="{{ route('list.packages') }}" class="nav-link">
                <span class="title">Manage Packages</span>
            </a>
        </li>
    </ul>
</li>
