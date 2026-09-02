<li class="nav-item {{ Request::is('admin/ai*') ? 'active open' : '' }}">
    <a href="javascript:;" class="nav-link nav-toggle">
        <i class="fa fa-microchip" aria-hidden="true" style="color: #60A5FA;"></i>
        <span class="title" style="font-weight: 600;">AI Engine</span>
        <span class="arrow {{ Request::is('admin/ai*') ? 'open' : '' }}"></span>
    </a>
    <ul class="sub-menu">
        <li class="nav-item {{ Request::is('admin/ai-job-import*') ? 'active' : '' }}">
            <a href="{{ route('admin.ai.job_import') }}" class="nav-link ">
                <i class="fa fa-sparkles fa-magic" style="color: #38BDF8; font-size: 13px;"></i>
                <span class="title">AI Job Import</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/ai-job-pipeline*') ? 'active' : '' }}">
            <a href="{{ route('admin.ai.pipeline') }}" class="nav-link ">
                <i class="fa fa-magic" style="color: #F59E0B; font-size: 13px;"></i>
                <span class="title">AI Job Pipeline</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/ai-cost-performance*') ? 'active' : '' }}">
            <a href="{{ route('admin.ai.cost_performance') }}" class="nav-link ">
                <i class="fa fa-line-chart" style="color: #10B981; font-size: 13px;"></i>
                <span class="title">Cost & Performance</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/ai-providers*') ? 'active' : '' }}">
            <a href="{{ route('admin.ai.providers') }}" class="nav-link ">
                <i class="fa fa-cogs" style="color: #6366F1; font-size: 13px;"></i>
                <span class="title">AI Providers</span>
            </a>
        </li>
    </ul>
</li>
