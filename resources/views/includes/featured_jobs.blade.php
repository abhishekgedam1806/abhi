<style>
.featured-jobs-section {
    padding: 60px 0;
    background: #FFFFFF;
}
.fj-header {
    margin-bottom: 35px;
}
.fj-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #FEF3C7;
    color: #B45309;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    padding: 6px 14px;
    border-radius: 50px;
    margin-bottom: 8px;
}
.fj-title {
    font-size: 28px;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 6px;
    letter-spacing: -0.5px;
}
.fj-subtitle {
    font-size: 14.5px;
    color: #64748B;
    margin: 0;
}
.fj-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 22px;
    margin-bottom: 24px;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: calc(100% - 24px);
    position: relative;
}
.fj-card:hover {
    border-color: #93C5FD;
    box-shadow: 0 12px 28px rgba(37, 99, 235, 0.09);
    transform: translateY(-3px);
}
.fj-card-top {
    display: flex;
    gap: 16px;
    align-items: flex-start;
    margin-bottom: 14px;
}
.fj-logo-wrap {
    width: 54px;
    height: 54px;
    min-width: 54px;
    border-radius: 12px;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 4px;
}
.fj-logo-wrap img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
.fj-card-body-txt {
    flex: 1;
    min-width: 0;
}
.fj-job-title {
    font-size: 16.5px;
    font-weight: 700;
    color: #0F172A;
    margin: 0 0 4px 0;
    line-height: 1.35;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.fj-job-title a {
    color: #0F172A;
    text-decoration: none !important;
    transition: color 0.2s;
}
.fj-job-title a:hover {
    color: #2563EB;
}
.fj-company-name {
    font-size: 13.5px;
    font-weight: 600;
    color: #475569;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 6px;
}
.fj-company-name a {
    color: #475569;
    text-decoration: none !important;
}
.fj-company-name a:hover {
    color: #2563EB;
}
.fj-meta-wrap {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 16px;
}
.fj-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 6px;
}
.fj-pill-type {
    background: #EFF6FF;
    color: #2563EB;
}
.fj-pill-loc {
    background: #F1F5F9;
    color: #475569;
}
.fj-pill-sal {
    background: #ECFDF5;
    color: #03855c;
}
.fj-pill-exp {
    background: #FAF5FF;
    color: #9333EA;
}
.fj-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 14px;
    border-top: 1px solid #F1F5F9;
}
.fj-posted-time {
    font-size: 12px;
    color: #94A3B8;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.fj-view-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #2563EB;
    color: #FFFFFF !important;
    font-size: 12.5px;
    font-weight: 700;
    padding: 7px 16px;
    border-radius: 8px;
    text-decoration: none !important;
    transition: all 0.2s ease;
}
.fj-view-btn:hover {
    background: #1D4ED8;
    transform: translateX(2px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
}
.fj-viewall-wrap {
    text-align: center;
    margin-top: 15px;
}
.fj-viewall-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #FFFFFF;
    color: #2563EB !important;
    border: 1.5px solid #2563EB;
    font-size: 14px;
    font-weight: 700;
    padding: 10px 28px;
    border-radius: 10px;
    text-decoration: none !important;
    transition: all 0.2s ease;
}
.fj-viewall-btn:hover {
    background: #2563EB;
    color: #FFFFFF !important;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.2);
}
</style>

<div class="section featured-jobs-section">
    <div class="container"> 
        {{-- Section Title --}}
        <div class="row align-items-center fj-header">
            <div class="col-md-8">
                <div class="fj-badge">
                    <i class="fa fa-bolt"></i> {{ __('Top Verified Openings') }}
                </div>
                <h2 class="fj-title">
                    {{ __('Featured') }} <span style="color: #2563EB;">{{ __('Jobs') }}</span>
                </h2>
                <p class="fj-subtitle">
                    {{ __('Hand-picked career opportunities from leading and verified employers.') }}
                </p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0 d-none d-md-block">
                <a href="{{ route('job.list', ['is_featured' => 1]) }}" class="fj-viewall-btn">
                    {{ __('View All') }} ({{ isset($featuredJobs) ? count($featuredJobs) : 0 }}) <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        {{-- Featured Jobs Grid --}}
        <div class="row">
            @if(isset($featuredJobs) && count($featuredJobs))
                @foreach($featuredJobs as $featuredJob)
                @php 
                    $company = $featuredJob->getCompany(); 
                @endphp
                @if(null !== $company)
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="fj-card">
                        <div>
                            <div class="fj-card-top">
                                <div class="fj-logo-wrap">
                                    <a href="{{ route('job.detail', [$featuredJob->slug]) }}" title="{{ $featuredJob->title }}">
                                        {{ $company->printCompanyImage() }}
                                    </a>
                                </div>
                                <div class="fj-card-body-txt">
                                    <h3 class="fj-job-title">
                                        <a href="{{ route('job.detail', [$featuredJob->slug]) }}" title="{{ $featuredJob->title }}">
                                            {{ $featuredJob->title }}
                                        </a>
                                    </h3>
                                    <p class="fj-company-name">
                                        <i class="fa fa-building-o" style="color: #94A3B8; font-size: 12px;"></i>
                                        <a href="{{ route('company.detail', $company->slug) }}" title="{{ $company->name }}">
                                            {{ $company->name }}
                                        </a>
                                    </p>
                                </div>
                            </div>

                            {{-- Meta Badges --}}
                            <div class="fj-meta-wrap">
                                @if(!empty($featuredJob->getJobType('job_type')))
                                    <span class="fj-pill fj-pill-type">
                                        <i class="fa fa-briefcase"></i> {{ $featuredJob->getJobType('job_type') }}
                                    </span>
                                @endif

                                @if(!empty($featuredJob->getCity('city')))
                                    <span class="fj-pill fj-pill-loc">
                                        <i class="fa fa-map-marker"></i> {{ $featuredJob->getCity('city') }}
                                    </span>
                                @endif

                                @if(!empty($featuredJob->salary_from) || !empty($featuredJob->salary_to))
                                    <span class="fj-pill fj-pill-sal">
                                        <i class="fa fa-money"></i> 
                                        @if(!empty($featuredJob->salary_currency)){{ $featuredJob->salary_currency }}@endif
                                        {{ $featuredJob->salary_from ? number_format($featuredJob->salary_from) : '' }}
                                        @if($featuredJob->salary_from && $featuredJob->salary_to) - @endif
                                        {{ $featuredJob->salary_to ? number_format($featuredJob->salary_to) : '' }}
                                        @if(!empty($featuredJob->getSalaryPeriod('salary_period'))) / {{ $featuredJob->getSalaryPeriod('salary_period') }}@endif
                                    </span>
                                @endif

                                @if(!empty($featuredJob->getJobExperience('job_experience')))
                                    <span class="fj-pill fj-pill-exp">
                                        <i class="fa fa-clock-o"></i> {{ $featuredJob->getJobExperience('job_experience') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Card Footer --}}
                        <div class="fj-card-footer">
                            <span class="fj-posted-time">
                                <i class="fa fa-calendar-o"></i> {{ $featuredJob->created_at ? $featuredJob->created_at->diffForHumans() : __('Recently') }}
                            </span>
                            <a href="{{ route('job.detail', [$featuredJob->slug]) }}" class="fj-view-btn">
                                {{ __('Apply Now') }} <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            @else
                <div class="col-12 text-center py-4">
                    <p style="color: #64748B;">{{ __('No featured jobs available at the moment.') }}</p>
                </div>
            @endif
        </div>

        {{-- Mobile View All Button --}}
        <div class="fj-viewall-wrap d-block d-md-none">
            <a href="{{ route('job.list', ['is_featured' => 1]) }}" class="fj-viewall-btn">
                {{ __('View All Featured Jobs') }} <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>