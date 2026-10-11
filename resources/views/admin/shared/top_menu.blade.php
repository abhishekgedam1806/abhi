<div class="top-menu">
    <ul class="nav navbar-nav pull-right">
        <!-- BEGIN USER LOGIN DROPDOWN --> 
        <li class="dropdown dropdown-user">
            <a href="javascript:;" class="dropdown-toggle adm-top-user-btn" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" title="Admin Profile">
                <span class="adm-avatar-circle"><i class="fa fa-user"></i></span>
                <span class="username username-hide-on-mobile"> {{ (null !== (Auth::user()) && isset(Auth::user()->name)) ? Auth::user()->name : 'Admin' }} </span>
                <i class="fa fa-angle-down adm-chevron"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-default">
                <li> <a href="{{ route('admin.home') }}"> <i class="icon-user"></i> My Profile </a> </li>
                <li class="divider"> </li>
                <li> <a href="{{ route('admin.logout') }}"> <i class="icon-key"></i> Log Out </a> </li>
            </ul>
        </li>
        <!-- END USER LOGIN DROPDOWN -->
        <li class="dropdown dropdown-quick-sidebar-toggler">
            <a href="{{ route('admin.logout') }}" class="dropdown-toggle adm-top-logout-btn" title="Log Out">
                <i class="icon-logout"></i>
            </a>
        </li>
    </ul>
</div>
