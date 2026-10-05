@php
if (!function_exists('getDemoCategoryIcon')) {
    function getDemoCategoryIcon($name) {
        $n = strtolower(trim($name));
        if (strpos($n, 'remote') !== false || strpos($n, 'home') !== false) {
            return ['icon' => 'fa fa-home', 'bg' => '#EEF2FF', 'color' => '#4F46E5'];
        } elseif (strpos($n, 'mnc') !== false || strpos($n, 'corporate') !== false) {
            return ['icon' => 'fa fa-building-o', 'bg' => '#FFFBEB', 'color' => '#D97706'];
        } elseif (strpos($n, 'data') !== false || strpos($n, 'science') !== false || strpos($n, 'analytics') !== false) {
            return ['icon' => 'fa fa-bar-chart', 'bg' => '#FEF3C7', 'color' => '#B45309'];
        } elseif (strpos($n, 'engineer') !== false || strpos($n, 'tech') !== false || strpos($n, 'mechanical') !== false || strpos($n, 'electrical') !== false) {
            return ['icon' => 'fa fa-cogs', 'bg' => '#ECFEFF', 'color' => '#0891B2'];
        } elseif (strpos($n, 'it') !== false || strpos($n, 'soft') !== false || strpos($n, 'dev') !== false || strpos($n, 'web') !== false || strpos($n, 'computer') !== false || strpos($n, 'program') !== false) {
            return ['icon' => 'fa fa-desktop', 'bg' => '#EFF6FF', 'color' => '#2563EB'];
        } elseif (strpos($n, 'bank') !== false || strpos($n, 'finance') !== false || strpos($n, 'account') !== false) {
            return ['icon' => 'fa fa-inr', 'bg' => '#ECFDF5', 'color' => '#03855c'];
        } elseif (strpos($n, 'supply') !== false || strpos($n, 'logistics') !== false || strpos($n, 'deliver') !== false || strpos($n, 'ware') !== false || strpos($n, 'courier') !== false) {
            return ['icon' => 'fa fa-cube', 'bg' => '#F0FDF4', 'color' => '#16A34A'];
        } elseif (strpos($n, 'market') !== false || strpos($n, 'advert') !== false || strpos($n, 'seo') !== false || strpos($n, 'media') !== false) {
            return ['icon' => 'fa fa-line-chart', 'bg' => '#FAF5FF', 'color' => '#9333EA'];
        } elseif (strpos($n, 'intern') !== false || strpos($n, 'fresh') !== false || strpos($n, 'educat') !== false || strpos($n, 'train') !== false) {
            return ['icon' => 'fa fa-graduation-cap', 'bg' => '#FDF4FF', 'color' => '#C026D3'];
        } elseif (strpos($n, 'sale') !== false || strpos($n, 'retail') !== false || strpos($n, 'business') !== false) {
            return ['icon' => 'fa fa-briefcase', 'bg' => '#F0F9FF', 'color' => '#0284C7'];
        } elseif (strpos($n, 'hr') !== false || strpos($n, 'recruit') !== false || strpos($n, 'human') !== false || strpos($n, 'consult') !== false) {
            return ['icon' => 'fa fa-users', 'bg' => '#FDF2F8', 'color' => '#DB2777'];
        } elseif (strpos($n, 'bpo') !== false || strpos($n, 'call') !== false || strpos($n, 'support') !== false || strpos($n, 'tele') !== false) {
            return ['icon' => 'fa fa-headphones', 'bg' => '#FFF1F2', 'color' => '#E11D48'];
        } elseif (strpos($n, 'health') !== false || strpos($n, 'medic') !== false || strpos($n, 'pharma') !== false) {
            return ['icon' => 'fa fa-heartbeat', 'bg' => '#FEE2E2', 'color' => '#DC2626'];
        } elseif (strpos($n, 'construct') !== false || strpos($n, 'architect') !== false) {
            return ['icon' => 'fa fa-building', 'bg' => '#F1F5F9', 'color' => '#475569'];
        }
        return ['icon' => 'fa fa-briefcase', 'bg' => '#F8FAFC', 'color' => '#475569'];
    }
}

// 1. Curate Clean, Non-Duplicate Top Categories
$priorityCategoryNames = [
    'Internship / Fresher',
    'Work From Home',
    'Supply Chain',
    'HR & Recruitment',
    'Banking & Finance',
    'Accounts & Finance',
    'Engineering',
    'Data Science',
    'Marketing',
    'Sales',
    'Customer Support'
];

$priorityCategories = App\FunctionalArea::lang()->active()
    ->whereIn('functional_area', $priorityCategoryNames)
    ->orderByRaw("FIELD(functional_area, 'Internship / Fresher', 'Work From Home', 'Supply Chain', 'HR & Recruitment', 'Banking & Finance', 'Accounts & Finance', 'Engineering', 'Data Science', 'Marketing', 'Sales', 'Customer Support')")
    ->get();

// Supplementary categories for "View More" (excluding duplicates like 'Accountant', 'Accounts, Finance & Financial Services')
$otherCategories = App\FunctionalArea::lang()->active()
    ->whereNotIn('functional_area', array_merge($priorityCategoryNames, ['Accountant', 'Accounts, Finance & Financial Services', 'Admin Operation', 'Administration Clerical', 'Advertising']))
    ->orderBy('functional_area', 'asc')
    ->limit(14)
    ->get();

$allActiveFunctionalAreas = $priorityCategories->merge($otherCategories);
$allActiveJobTypes = App\JobType::lang()->active()->orderBy('job_type', 'asc')->get();

// Top 8 Metro Cities
$metroCityNames = ['Nagpur', 'Mumbai', 'Delhi', 'Bengaluru', 'Pune', 'Hyderabad', 'Chennai', 'Ahmedabad'];
$featuredCities = \App\City::whereIn('city', $metroCityNames)
    ->orderByRaw("FIELD(city, 'Nagpur', 'Mumbai', 'Delhi', 'Bengaluru', 'Pune', 'Hyderabad', 'Chennai', 'Ahmedabad')")
    ->get();

if ($featuredCities->isEmpty()) {
    $featuredCities = \App\City::limit(8)->get();
}
@endphp

<style>
/* Modern Categories Section - Mobile First */
.naukri-categories-section {
    padding: 36px 0 28px 0;
    background: #F8FAFC;
    position: relative;
    width: 100%;
    overflow: hidden;
}
.naukri-cat-header {
    margin-bottom: 20px;
}
.naukri-cat-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #EEF2FF;
    color: #4F46E5;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    padding: 5px 12px;
    border-radius: 50px;
    margin-bottom: 8px;
}
.naukri-cat-title {
    font-size: 24px;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 6px;
    letter-spacing: -0.4px;
    line-height: 1.25;
}
.naukri-cat-subtitle {
    font-size: 13.5px;
    color: #64748B;
    margin: 0;
    line-height: 1.45;
}

/* Nav Tabs */
.naukri-nav-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    border-bottom: none !important;
    margin-bottom: 16px;
}
.naukri-nav-tabs .nav-link {
    border: 1px solid #E2E8F0 !important;
    background: #FFFFFF !important;
    color: #475569 !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    padding: 8px 16px !important;
    border-radius: 50px !important;
    transition: all 0.2s ease !important;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap !important;
}
.naukri-nav-tabs .nav-link.active {
    background: #4F46E5 !important;
    color: #FFFFFF !important;
    border-color: #4F46E5 !important;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.22) !important;
}

/* Category Grid - 2 Column on Mobile, Auto-Fit on Desktop */
.naukri-pills-wrap {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    width: 100%;
}
.naukri-pill-card {
    display: flex;
    align-items: center;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 10px 12px;
    text-decoration: none !important;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    cursor: pointer;
    min-height: 48px;
    box-sizing: border-box;
    width: 100%;
}
.naukri-pill-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.07);
    border-color: #CBD5E1;
}
.naukri-pill-icon {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 8px;
    font-size: 12.5px;
    flex-shrink: 0;
}
.naukri-pill-name {
    font-size: 13px;
    font-weight: 600;
    color: #1E293B;
    line-height: 1.25;
    flex: 1;
    min-width: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    word-break: break-word;
}
.naukri-pill-badge {
    font-size: 11.5px;
    color: #64748B;
    font-weight: 500;
    margin-left: 4px;
    flex-shrink: 0;
}
.naukri-pill-arrow {
    color: #94A3B8;
    font-size: 11.5px;
    margin-left: auto;
    padding-left: 4px;
    flex-shrink: 0;
    transition: transform 0.2s ease, color 0.2s ease;
}
.naukri-pill-card:hover .naukri-pill-arrow {
    transform: translateX(2px);
    color: #4F46E5;
}

/* Extra Categories Toggle */
.cat-extra-pill {
    display: none;
}
.cat-toggle-wrap {
    width: 100%;
    margin-top: 14px;
    text-align: center;
}
.btn-toggle-categories {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    color: #4F46E5;
    font-size: 13px;
    font-weight: 700;
    padding: 8px 22px;
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    min-height: 42px;
}
.btn-toggle-categories:hover {
    background: #EEF2FF;
    border-color: #4F46E5;
}

/* =========================================
   CLEAN CITY SECTION
   ========================================= */
.home-cities-section {
    margin-top: 28px;
    padding-top: 24px;
    border-top: 1px solid #E2E8F0;
    width: 100%;
}
.city-card-item {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 10px 12px;
    text-decoration: none !important;
    transition: all 0.22s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    margin-bottom: 10px;
    box-sizing: border-box;
    width: 100%;
    min-height: 48px;
}
.city-card-item:hover {
    border-color: #2563EB;
    box-shadow: 0 6px 18px rgba(37,99,235,0.08);
    transform: translateY(-2px);
}
.city-card-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #EFF6FF;
    color: #2563EB;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    flex-shrink: 0;
}
.city-card-name {
    font-size: 13px;
    font-weight: 700;
    color: #0F172A;
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.city-card-jobs {
    font-size: 11px;
    color: #64748B;
    font-weight: 500;
    margin-top: 1px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.city-card-arrow {
    margin-left: auto;
    color: #CBD5E1;
    font-size: 11.5px;
    flex-shrink: 0;
}

/* =========================================
   100% Mobile Responsiveness (< 992px & < 768px)
   ========================================= */
@media (max-width: 991px) {
    .naukri-pills-wrap {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 767px) {
    .naukri-categories-section {
        padding: 24px 0 18px 0 !important;
    }
    .naukri-cat-title {
        font-size: 20px !important;
    }
    .naukri-cat-subtitle {
        font-size: 12.5px !important;
        margin-bottom: 4px !important;
    }
    .naukri-nav-tabs {
        display: flex !important;
        flex-wrap: nowrap !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch !important;
        scrollbar-width: none !important;
        gap: 6px !important;
        padding-bottom: 2px !important;
        margin-bottom: 12px !important;
    }
    .naukri-nav-tabs::-webkit-scrollbar {
        display: none !important;
    }
    .naukri-nav-tabs .nav-link {
        font-size: 12px !important;
        padding: 6px 13px !important;
    }

    /* Perfect 2-Column Grid on Mobile */
    .naukri-pills-wrap {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 8px !important;
    }
    .naukri-pill-card {
        padding: 8px 10px !important;
        min-height: 46px !important;
        border-radius: 10px !important;
    }
    .naukri-pill-icon {
        width: 26px !important;
        height: 26px !important;
        font-size: 11px !important;
        margin-right: 7px !important;
    }
    .naukri-pill-name {
        font-size: 12px !important;
        line-height: 1.2 !important;
    }
    .naukri-pill-badge {
        display: none !important;
    }
    .naukri-pill-arrow {
        display: none !important;
    }

    .btn-toggle-categories {
        width: 100% !important;
        font-size: 12.5px !important;
        padding: 8px 16px !important;
    }

    /* Cities on Mobile: Clean 2 columns */
    .home-cities-section {
        margin-top: 20px !important;
        padding-top: 18px !important;
    }
    .city-card-item {
        padding: 8px 10px !important;
        gap: 7px !important;
        margin-bottom: 8px !important;
        border-radius: 10px !important;
        min-height: 44px !important;
    }
    .city-card-icon {
        width: 28px !important;
        height: 28px !important;
        font-size: 12px !important;
        border-radius: 6px !important;
    }
    .city-card-name {
        font-size: 12px !important;
    }
    .city-card-jobs {
        font-size: 10.5px !important;
    }
    .city-card-arrow {
        display: none !important;
    }
}
</style>

<div class="naukri-categories-section">
    <div class="container">
        {{-- Section 3: Browse Jobs by Category --}}
        <div class="row align-items-end naukri-cat-header">
            <div class="col-lg-6 col-md-12">
                <div class="naukri-cat-badge">
                    <i class="fa fa-th-large"></i> {{__('Popular Categories')}}
                </div>
                <h2 class="naukri-cat-title">{{__('Browse Jobs by Category')}}</h2>
                <p class="naukri-cat-subtitle">{{__('Explore career openings across high-demand roles & sectors')}}</p>
            </div>
            <div class="col-lg-6 col-md-12 mt-3 mt-lg-0 text-lg-right">
                <ul class="nav nav-tabs naukri-nav-tabs justify-content-lg-end" id="categoryTab" role="tablist">
                    <li class="nav-item">
                        <a data-toggle="tab" href="#byfunctional" class="nav-link active" aria-expanded="true">
                            <i class="fa fa-briefcase"></i> {{__('Functional Area')}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a data-toggle="tab" class="nav-link" href="#byjobtype" aria-expanded="false">
                            <i class="fa fa-clock-o"></i> {{__('Work Mode')}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a data-toggle="tab" href="#byindustries" class="nav-link" aria-expanded="false">
                            <i class="fa fa-building-o"></i> {{__('Industries')}}
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content" id="categoryTabContent">
            {{-- 1. FUNCTIONAL AREA PILLS (Clean initial 8-10 cards without duplicates) --}}
            <div class="tab-pane fade show active" id="byfunctional" role="tabpanel">
                <div class="naukri-pills-wrap" id="catsContainer">
                    @if(isset($allActiveFunctionalAreas) && count($allActiveFunctionalAreas))
                        @foreach($allActiveFunctionalAreas as $index => $fa)
                            @php
                                $iconData = getDemoCategoryIcon($fa->functional_area);
                                $numJobs = App\Job::countNumJobs('functional_area_id', $fa->functional_area_id);
                                $isExtra = $index >= 8; // Show initial 8 clean cards (4 neat rows on mobile)
                            @endphp
                            <a href="{{ route('job.list', ['functional_area_id[]' => $fa->functional_area_id]) }}" 
                               class="naukri-pill-card {{ $isExtra ? 'cat-extra-pill' : '' }}" 
                               title="{{ $fa->functional_area }}">
                                <span class="naukri-pill-icon" style="background: {{ $iconData['bg'] }}; color: {{ $iconData['color'] }};">
                                    <i class="{{ $iconData['icon'] }}"></i>
                                </span>
                                <span class="naukri-pill-name">{{ $fa->functional_area }}</span>
                                @if($numJobs > 0)
                                <span class="naukri-pill-badge">({{ $numJobs }})</span>
                                @endif
                                <i class="fa fa-angle-right naukri-pill-arrow"></i>
                            </a>
                        @endforeach
                    @endif
                </div>

                @if(isset($allActiveFunctionalAreas) && count($allActiveFunctionalAreas) > 8)
                <div class="cat-toggle-wrap">
                    <button type="button" class="btn-toggle-categories" id="btnToggleCategories" onclick="toggleCategoriesView()">
                        <i class="fa fa-th-large"></i> <span id="toggleCatTxt">{{ __('View More Categories') }} (+{{ count($allActiveFunctionalAreas) - 8 }})</span>
                        <i class="fa fa-angle-down" id="toggleCatIcon"></i>
                    </button>
                </div>
                @endif
            </div>

            {{-- 2. WORK MODE / JOB TYPE PILLS --}}
            <div class="tab-pane fade" id="byjobtype" role="tabpanel">
                <div class="naukri-pills-wrap">
                    @if(isset($allActiveJobTypes) && count($allActiveJobTypes))
                        @foreach($allActiveJobTypes as $jt)
                            @php
                                $iconData = getDemoCategoryIcon($jt->job_type);
                                $numJobs = App\Job::countNumJobs('job_type_id', $jt->job_type_id);
                            @endphp
                            <a href="{{ route('job.list', ['job_type_id[]' => $jt->job_type_id]) }}" class="naukri-pill-card" title="{{ $jt->job_type }}">
                                <span class="naukri-pill-icon" style="background: {{ $iconData['bg'] }}; color: {{ $iconData['color'] }};">
                                    <i class="{{ $iconData['icon'] }}"></i>
                                </span>
                                <span class="naukri-pill-name">{{ $jt->job_type }}</span>
                                @if($numJobs > 0)
                                <span class="naukri-pill-badge">({{ $numJobs }})</span>
                                @endif
                                <i class="fa fa-angle-right naukri-pill-arrow"></i>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- 3. INDUSTRIES PILLS --}}
            <div class="tab-pane fade" id="byindustries" role="tabpanel">
                <div class="naukri-pills-wrap">
                    @if(isset($topIndustryIds) && count($topIndustryIds))
                        @foreach($topIndustryIds as $industry_id => $num_jobs)
                            @php
                                $industry = App\Industry::where('industry_id', '=', $industry_id)->lang()->active()->first();
                            @endphp
                            @if(null !== $industry)
                                @php
                                    $iconData = getDemoCategoryIcon($industry->industry);
                                @endphp
                                <a href="{{ route('job.list', ['industry_id[]' => $industry->industry_id]) }}" class="naukri-pill-card" title="{{ $industry->industry }}">
                                    <span class="naukri-pill-icon" style="background: {{ $iconData['bg'] }}; color: {{ $iconData['color'] }};">
                                        <i class="{{ $iconData['icon'] }}"></i>
                                    </span>
                                    <span class="naukri-pill-name">{{ $industry->industry }}</span>
                                    <span class="naukri-pill-badge">({{ $num_jobs }})</span>
                                    <i class="fa fa-angle-right naukri-pill-arrow"></i>
                                </a>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        {{-- =========================================
             CITY SECTION (8 top hubs)
             ========================================= --}}
        <div class="home-cities-section">
            <div class="row align-items-center mb-3">
                <div class="col-8">
                    <div style="font-size: 11px; font-weight: 700; color: #2563EB; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 2px;">
                        <i class="fa fa-map-marker"></i> {{ __('Top Locations') }}
                    </div>
                    <h3 style="font-size: 18px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.3px;">
                        {{ __('Find Jobs in Top Cities') }}
                    </h3>
                </div>
                <div class="col-4 text-right">
                    <a href="{{ route('job.list') }}" style="font-size: 12px; font-weight: 700; color: #2563EB; text-decoration: none;">
                        {{ __('All Cities') }} <i class="fa fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="row">
                @foreach($featuredCities as $c)
                    @php
                        $cityJobs = App\Job::where('city_id', $c->city_id)->where('is_active', 1)->notExpire()->count();
                        $slug = \Illuminate\Support\Str::slug($c->city);
                    @endphp
                    <div class="col-lg-3 col-md-4 col-6">
                        <a href="{{ route('jobs.city', ['city_slug' => $slug]) }}" class="city-card-item" title="Jobs in {{ $c->city }}">
                            <div class="city-card-icon">
                                <i class="fa fa-building-o"></i>
                            </div>
                            <div style="min-width: 0; flex: 1;">
                                <div class="city-card-name">{{ $c->city }}</div>
                                <div class="city-card-jobs">
                                    {{ $cityJobs > 0 ? $cityJobs . ' ' . __('Jobs') : __('Explore Jobs') }}
                                </div>
                            </div>
                            <i class="fa fa-angle-right city-card-arrow"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
function toggleCategoriesView() {
    var extras = document.querySelectorAll('.cat-extra-pill');
    var btnTxt = document.getElementById('toggleCatTxt');
    var btnIcon = document.getElementById('toggleCatIcon');
    if (!extras.length) return;

    var isHidden = (extras[0].style.display === '' || extras[0].style.display === 'none');
    for (var i = 0; i < extras.length; i++) {
        extras[i].style.display = isHidden ? 'flex' : 'none';
    }

    if (isHidden) {
        btnTxt.innerText = '{{ __("Show Less Categories") }}';
        btnIcon.className = 'fa fa-angle-up';
    } else {
        btnTxt.innerText = '{{ __("View More Categories") }} (+' + extras.length + ')';
        btnIcon.className = 'fa fa-angle-down';
    }
}
</script>
