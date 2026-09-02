<!-- BEGIN SIDEBAR -->
<div class="page-sidebar navbar-collapse collapse">
    <ul class="page-sidebar-menu page-header-fixed" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 10px;">
        <li class="sidebar-toggler-wrapper hide">
            <div class="sidebar-toggler"> </div>
        </li>
        <li class="sidebar-search-wrapper hide"></li>

        <!-- DASHBOARD -->
        <li class="nav-item start active">
            <a href="{{ route('admin.home') }}" class="nav-link">
                <i class="icon-home"></i>
                <span class="title">Dashboard</span>
                <span class="selected"></span>
            </a>
        </li>

        <!-- 1. PORTAL & RECRUITMENT -->
        <li class="heading">
            <h3 class="uppercase"><i class="fa fa-briefcase" style="font-size: 10px; margin-right: 4px; opacity: 0.7;"></i> Recruitment</h3>
        </li>
        @include('admin/shared/side_bars/job')
        @include('admin/shared/side_bars/company')
        @include('admin/shared/side_bars/site_user')
        @include('admin/shared/side_bars/business')

        <!-- 2. AI ENGINE & AUTOMATION & COMMUNICATIONS -->
        <li class="heading">
            <h3 class="uppercase"><i class="fa fa-magic" style="font-size: 10px; margin-right: 4px; opacity: 0.7;"></i> AI & Messaging</h3>
        </li>
        @include('admin/shared/side_bars/ai')
        @include('admin/shared/side_bars/whatsapp')


        <!-- 3. CONTENT & CMS -->
        <li class="heading">
            <h3 class="uppercase"><i class="fa fa-newspaper-o" style="font-size: 10px; margin-right: 4px; opacity: 0.7;"></i> Content & CMS</h3>
        </li>
        @include('admin/shared/side_bars/blogs')
        @include('admin/shared/side_bars/cms')
        @include('admin/shared/side_bars/menu_management')
        @include('admin/shared/side_bars/seo')
        
        <!-- Media & Widgets Nested Dropdown -->
        <li class="nav-item">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-picture-o"></i>
                <span class="title">Media & Widgets</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                @include('admin/shared/side_bars/slider')
                @include('admin/shared/side_bars/faq')
                @include('admin/shared/side_bars/testimonial')
                @include('admin/shared/side_bars/video')
            </ul>
        </li>

        @if(APAuthHelp::check(['SUP_ADM']))
        <!-- 4. MASTER SETTINGS & ATTRIBUTES -->
        <li class="heading">
            <h3 class="uppercase"><i class="fa fa-database" style="font-size: 10px; margin-right: 4px; opacity: 0.7;"></i> Master Data</h3>
        </li>

        <!-- Revenue & Plans Dropdown -->
        <li class="nav-item">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-credit-card"></i>
                <span class="title">Revenue & Plans</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                @include('admin/shared/side_bars/package')
                @include('admin/shared/side_bars/payment')
            </ul>
        </li>

        <!-- Locations & Language Dropdown -->
        <li class="nav-item">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-globe"></i>
                <span class="title">Locations & Language</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                @include('admin/shared/side_bars/country')
                @include('admin/shared/side_bars/state')
                @include('admin/shared/side_bars/city')
                @include('admin/shared/side_bars/country_detail')
                @include('admin/shared/side_bars/language')
            </ul>
        </li>

        <!-- Master Job & User Attributes Dropdown -->
        <li class="nav-item">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-sliders"></i>
                <span class="title">Job & Candidate Attributes</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                @include('admin/shared/side_bars/functional_area')
                @include('admin/shared/side_bars/industry')
                @include('admin/shared/side_bars/job_skill')
                @include('admin/shared/side_bars/job_type')
                @include('admin/shared/side_bars/job_shift')
                @include('admin/shared/side_bars/career_level')
                @include('admin/shared/side_bars/job_experience')
                @include('admin/shared/side_bars/salary_period')
                @include('admin/shared/side_bars/degree_level')
                @include('admin/shared/side_bars/degree_type')
                @include('admin/shared/side_bars/major_subject')
                @include('admin/shared/side_bars/result_type')
                @include('admin/shared/side_bars/language_level')
                @include('admin/shared/side_bars/gender')
                @include('admin/shared/side_bars/marital_status')
                @include('admin/shared/side_bars/ownership_type')
            </ul>
        </li>

        <!-- 5. ADMINISTRATION -->
        <li class="heading">
            <h3 class="uppercase"><i class="fa fa-cogs" style="font-size: 10px; margin-right: 4px; opacity: 0.7;"></i> Administration</h3>
        </li>
        @include('admin/shared/side_bars/admin_user')
        @include('admin/shared/side_bars/site_setting')
        @endif

    </ul>
</div>
<!-- END SIDEBAR -->