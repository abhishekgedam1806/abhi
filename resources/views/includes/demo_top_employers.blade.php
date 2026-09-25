@php
$activeCompanies = collect();

if (isset($topCompanyIds) && count($topCompanyIds)) {
    foreach ($topCompanyIds as $item) {
        $comp = App\Company::where('id', $item->company_id)->active()->first();
        if ($comp) {
            $comp->open_jobs_count = $item->num_jobs ?? 0;
            $activeCompanies->push($comp);
        }
    }
}

// Ensure at least 8 companies for a rich slider
if ($activeCompanies->count() < 8) {
    $fallbackComps = App\Company::active()
        ->orderBy('is_featured', 'desc')
        ->orderBy('id', 'desc')
        ->take(12)
        ->get();

    foreach ($fallbackComps as $fc) {
        if (!$activeCompanies->contains('id', $fc->id)) {
            $fc->open_jobs_count = App\Job::where('company_id', $fc->id)->where('is_active', 1)->count();
            $activeCompanies->push($fc);
        }
    }
}
@endphp

@if($activeCompanies->count() > 0)
<style>
/* Modern Clickable Companies Slider */
.demo-companies-section {
    padding: 44px 0 50px 0;
    background: #F8FAFC;
    position: relative;
    width: 100%;
    overflow: hidden;
}
.demo-sec-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #EFF6FF;
    color: #2563EB;
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    padding: 5px 12px;
    border-radius: 50px;
    margin-bottom: 8px;
}
.demo-sec-title {
    font-size: 26px;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 6px;
    letter-spacing: -0.4px;
}
.demo-sec-subtitle {
    font-size: 14.5px;
    color: #64748B;
    margin: 0;
}

/* Slider Controls */
.slider-nav-btns {
    display: flex;
    gap: 8px;
    align-items: center;
    justify-content: flex-end;
}
.slider-arrow-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #FFFFFF;
    border: 1.5px solid #CBD5E1;
    color: #0F172A;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}
.slider-arrow-btn:hover {
    background: #2563EB;
    border-color: #2563EB;
    color: #FFFFFF;
    transform: scale(1.05);
}

/* Slider Track */
.companies-slider-viewport {
    width: 100%;
    overflow-x: auto;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    padding: 10px 2px 18px 2px;
    scroll-snap-type: x mandatory;
}
.companies-slider-viewport::-webkit-scrollbar {
    display: none;
}
.companies-slider-track {
    display: flex;
    gap: 14px;
    margin: 0;
    padding: 0;
    list-style: none;
    width: max-content;
}

/* Company Slider Card - 100% Clickable */
.company-card-link {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    flex: 0 0 250px;
    width: 250px;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    padding: 18px;
    text-decoration: none !important;
    transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    cursor: pointer;
    text-align: center;
    position: relative;
    scroll-snap-align: start;
    box-sizing: border-box;
}
.company-card-link:hover {
    border-color: #2563EB;
    box-shadow: 0 10px 24px rgba(37,99,235,0.1);
    transform: translateY(-3px);
}
.comp-card-logo-wrap {
    width: 56px;
    height: 56px;
    margin: 0 auto 10px auto;
    border-radius: 12px;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 5px;
    font-size: 20px;
    font-weight: 800;
    color: #2563EB;
}
.comp-card-logo-wrap img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
.comp-card-name {
    font-size: 14.5px;
    font-weight: 700;
    color: #0F172A;
    margin-bottom: 3px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.comp-card-ind {
    font-size: 12px;
    color: #64748B;
    margin-bottom: 10px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.comp-card-jobs-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #EFF6FF;
    color: #2563EB;
    font-size: 11.5px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 50px;
    margin-bottom: 8px;
}
.comp-card-action {
    font-size: 12px;
    font-weight: 600;
    color: #2563EB;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
}

@media (max-width: 767px) {
    .demo-companies-section {
        padding: 28px 0 34px 0 !important;
    }
    .demo-sec-title {
        font-size: 21px !important;
    }
    .demo-sec-subtitle {
        font-size: 13px !important;
    }
    .company-card-link {
        flex: 0 0 175px !important;
        width: 175px !important;
        padding: 14px 10px !important;
        border-radius: 12px !important;
    }
    .comp-card-logo-wrap {
        width: 46px !important;
        height: 46px !important;
        font-size: 17px !important;
    }
    .comp-card-name {
        font-size: 13px !important;
    }
    .comp-card-ind {
        font-size: 11px !important;
        margin-bottom: 8px !important;
    }
    .comp-card-jobs-badge {
        font-size: 11px !important;
        padding: 3px 8px !important;
    }
    .slider-arrow-btn {
        width: 32px !important;
        height: 32px !important;
        font-size: 12px !important;
    }
}
</style>

<div class="section demo-companies-section">
    <div class="container">
        {{-- Section 5: Featured Companies Header --}}
        <div class="row align-items-center mb-3">
            <div class="col-8 col-md-9">
                <div class="demo-sec-badge">
                    <i class="fa fa-building-o"></i> {{ __('Top Employers') }}
                </div>
                <h2 class="demo-sec-title">
                    {{ __('Featured') }} <span style="color: #2563EB;">{{ __('Companies') }}</span>
                </h2>
                <p class="demo-sec-subtitle">
                    {{ __('Leading verified workplaces actively hiring today') }}
                </p>
            </div>
            <div class="col-4 col-md-3">
                <div class="slider-nav-btns">
                    <button type="button" class="slider-arrow-btn" onclick="slideCompaniesScroll(-1)" aria-label="Previous Companies">
                        <i class="fa fa-chevron-left"></i>
                    </button>
                    <button type="button" class="slider-arrow-btn" onclick="slideCompaniesScroll(1)" aria-label="Next Companies">
                        <i class="fa fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Companies Slider Viewport --}}
        <div class="companies-slider-viewport" id="companiesSliderViewport">
            <div class="companies-slider-track">
                @foreach($activeCompanies as $c)
                    @php
                        $compInitial = strtoupper(substr($c->name ?? 'C', 0, 1));
                        $compLocation = $c->getLocation();
                        $ind = $c->getIndustry('industry');
                        $numJobs = $c->open_jobs_count ?? App\Job::where('company_id', $c->id)->where('is_active', 1)->count();
                    @endphp
                    <a href="{{ route('company.detail', $c->slug) }}" class="company-card-link" title="{{ $c->name }}">
                        <div>
                            <div class="comp-card-logo-wrap">
                                @if($c->logo && file_exists(public_path('company_logos/'.$c->logo)))
                                    <img src="{{ asset('company_logos/'.$c->logo) }}" alt="{{ $c->name }}">
                                @else
                                    <span>{{ $compInitial }}</span>
                                @endif
                            </div>
                            <div class="comp-card-name" title="{{ $c->name }}">{{ $c->name }}</div>
                            <div class="comp-card-ind">
                                @if(!empty($compLocation))
                                    <i class="fa fa-map-marker"></i> {{ $compLocation }}
                                @elseif(!empty($ind))
                                    {{ $ind }}
                                @else
                                    {{ __('Hiring Now') }}
                                @endif
                            </div>
                        </div>

                        <div>
                            <div class="comp-card-jobs-badge">
                                <i class="fa fa-briefcase"></i> 
                                {{ $numJobs > 0 ? $numJobs . ' ' . __('Openings') : __('View Profile') }}
                            </div>
                            <div class="comp-card-action">
                                <span>{{ __('View Company') }}</span> <i class="fa fa-arrow-right"></i>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
function slideCompaniesScroll(dir) {
    var viewport = document.getElementById('companiesSliderViewport');
    if (!viewport) return;
    var scrollAmt = 220 * dir;
    viewport.scrollBy({ left: scrollAmt, behavior: 'smooth' });
}
</script>
@endif
