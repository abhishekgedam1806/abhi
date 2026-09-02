<style>
.latest-jobs-section {
    padding: 60px 0;
    background: #F8FAFC;
}
.lj-header {
    margin-bottom: 35px;
}
.lj-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #EEF2FF;
    color: #4F46E5;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    padding: 6px 14px;
    border-radius: 50px;
    margin-bottom: 8px;
}
.lj-title {
    font-size: 28px;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 6px;
    letter-spacing: -0.5px;
}
.lj-subtitle {
    font-size: 14.5px;
    color: #64748B;
    margin: 0;
}
.lj-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 24px;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: calc(100% - 24px);
    position: relative;
}
.lj-card:hover {
    border-color: #93C5FD;
    box-shadow: 0 12px 26px rgba(37, 99, 235, 0.08);
    transform: translateY(-3px);
}
.lj-card-top {
    display: flex;
    gap: 14px;
    align-items: flex-start;
    margin-bottom: 12px;
}
.lj-logo-wrap {
    width: 48px;
    height: 48px;
    min-width: 48px;
    border-radius: 10px;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 3px;
}
.lj-logo-wrap img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
.lj-card-body-txt {
    flex: 1;
    min-width: 0;
}
.lj-job-title {
    font-size: 15.5px;
    font-weight: 700;
    color: #0F172A;
    margin: 0 0 3px 0;
    line-height: 1.35;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.lj-job-title a {
    color: #0F172A;
    text-decoration: none !important;
    transition: color 0.2s;
}
.lj-job-title a:hover {
    color: #2563EB;
}
.lj-company-name {
    font-size: 13px;
    font-weight: 600;
    color: #64748B;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.lj-company-name a {
    color: #64748B;
    text-decoration: none !important;
}
.lj-company-name a:hover {
    color: #2563EB;
}
.lj-meta-wrap {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 14px;
}
.lj-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11.5px;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 6px;
}
.lj-pill-type {
    background: #EFF6FF;
    color: #2563EB;
}
.lj-pill-loc {
    background: #F1F5F9;
    color: #475569;
}
.lj-pill-sal {
    background: #ECFDF5;
    color: #03855c;
}
.lj-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 12px;
    border-top: 1px solid #F1F5F9;
}
.lj-posted-time {
    font-size: 11.5px;
    color: #94A3B8;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.lj-view-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #F1F5F9;
    color: #1E293B !important;
    font-size: 12px;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 8px;
    text-decoration: none !important;
    transition: all 0.2s ease;
}
.lj-view-btn:hover {
    background: #2563EB;
    color: #FFFFFF !important;
    transform: translateX(2px);
}
.lj-viewall-wrap {
    text-align: center;
    margin-top: 15px;
}
.lj-viewall-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #2563EB;
    color: #FFFFFF !important;
    font-size: 14px;
    font-weight: 700;
    padding: 11px 30px;
    border-radius: 10px;
    text-decoration: none !important;
    transition: all 0.2s ease;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.2);
}
.lj-viewall-btn:hover {
    background: #1D4ED8;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
}
</style>

<div class="section latest-jobs-section">
    <div class="container"> 
        {{-- Section Title --}}
        <div class="row align-items-center lj-header">
            <div class="col-md-8">
                <div class="lj-badge">
                    <i class="fa fa-clock-o"></i> {{ __('Fresh Opportunities') }}
                </div>
                <h2 class="lj-title">
                    {{ __('Latest') }} <span style="color: #2563EB;">{{ __('Jobs') }}</span>
                </h2>
                <p class="lj-subtitle">
                    {{ __('Recently posted jobs matching today’s active market requirements.') }}
                </p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0 d-none d-md-block">
                <a href="{{ route('job.list') }}" class="lj-viewall-btn">
                    {{ __('Explore All Jobs') }} <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        {{-- Latest Jobs Grid (3 per row) --}}
        <div class="row">
            @if(isset($latestJobs) && count($latestJobs))
                @foreach($latestJobs as $latestJob)
                @php 
                    $company = $latestJob->getCompany(); 
                @endphp
                @if(null !== $company)
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="lj-card">
                        <div>
                            <div class="lj-card-top">
                                <div class="lj-logo-wrap">
                                    <a href="{{ route('job.detail', [$latestJob->slug]) }}" title="{{ $latestJob->title }}">
                                        {{ $company->printCompanyImage() }}
                                    </a>
                                </div>
                                <div class="lj-card-body-txt">
                                    <h3 class="lj-job-title">
                                        <a href="{{ route('job.detail', [$latestJob->slug]) }}" title="{{ $latestJob->title }}">
                                            {{ $latestJob->title }}
                                        </a>
                                    </h3>
                                    <p class="lj-company-name">
                                        <a href="{{ route('company.detail', $company->slug) }}" title="{{ $company->name }}">
                                            {{ $company->name }}
                                        </a>
                                    </p>
                                </div>
                            </div>

                            {{-- Meta Badges --}}
                            <div class="lj-meta-wrap">
                                @if(!empty($latestJob->getJobType('job_type')))
                                    <span class="lj-pill lj-pill-type">
                                        <i class="fa fa-briefcase"></i> {{ $latestJob->getJobType('job_type') }}
                                    </span>
                                @endif

                                @if(!empty($latestJob->getCity('city')))
                                    <span class="lj-pill lj-pill-loc">
                                        <i class="fa fa-map-marker"></i> {{ $latestJob->getCity('city') }}
                                    </span>
                                @endif

                                @if(!empty($latestJob->salary_from) || !empty($latestJob->salary_to))
                                    <span class="lj-pill lj-pill-sal">
                                        <i class="fa fa-money"></i> 
                                        @if(!empty($latestJob->salary_currency)){{ $latestJob->salary_currency }}@endif
                                        {{ $latestJob->salary_from ? number_format($latestJob->salary_from) : '' }}
                                        @if($latestJob->salary_from && $latestJob->salary_to) - @endif
                                        {{ $latestJob->salary_to ? number_format($latestJob->salary_to) : '' }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Card Footer --}}
                        <div class="lj-card-footer">
                            <span class="lj-posted-time">
                                <i class="fa fa-calendar-o"></i> {{ $latestJob->created_at ? $latestJob->created_at->diffForHumans() : __('Recently') }}
                            </span>
                            <a href="{{ route('job.detail', [$latestJob->slug]) }}" class="lj-view-btn">
                                {{ __('Details') }} <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            @else
                <div class="col-12 text-center py-4">
                    <p style="color: #64748B;">{{ __('No jobs available at the moment.') }}</p>
                </div>
            @endif
        </div>

        {{-- Mobile View All Button --}}
        <div class="lj-viewall-wrap d-block d-md-none">
            <a href="{{ route('job.list') }}" class="lj-viewall-btn">
                {{ __('Explore All Jobs') }} <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>