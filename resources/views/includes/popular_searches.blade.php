@php
if (!function_exists('getNaukriCategoryIcon')) {
    function getNaukriCategoryIcon($name) {
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
        } elseif (strpos($n, 'fortune') !== false || strpos($n, 'top') !== false || strpos($n, 'exec') !== false) {
            return ['icon' => 'fa fa-trophy', 'bg' => '#FEFCE8', 'color' => '#CA8A04'];
        } elseif (strpos($n, 'sale') !== false || strpos($n, 'retail') !== false || strpos($n, 'business') !== false) {
            return ['icon' => 'fa fa-briefcase', 'bg' => '#F0F9FF', 'color' => '#0284C7'];
        } elseif (strpos($n, 'hr') !== false || strpos($n, 'recruit') !== false || strpos($n, 'human') !== false || strpos($n, 'consult') !== false) {
            return ['icon' => 'fa fa-users', 'bg' => '#FDF2F8', 'color' => '#DB2777'];
        } elseif (strpos($n, 'driv') !== false || strpos($n, 'transp') !== false || strpos($n, 'auto') !== false || strpos($n, 'ride') !== false) {
            return ['icon' => 'fa fa-car', 'bg' => '#FFF7ED', 'color' => '#EA580C'];
        } elseif (strpos($n, 'bpo') !== false || strpos($n, 'call') !== false || strpos($n, 'support') !== false || strpos($n, 'tele') !== false || strpos($n, 'front') !== false) {
            return ['icon' => 'fa fa-headphones', 'bg' => '#FFF1F2', 'color' => '#E11D48'];
        } elseif (strpos($n, 'health') !== false || strpos($n, 'medic') !== false || strpos($n, 'pharma') !== false || strpos($n, 'doctor') !== false) {
            return ['icon' => 'fa fa-heartbeat', 'bg' => '#FEE2E2', 'color' => '#DC2626'];
        } elseif (strpos($n, 'hotel') !== false || strpos($n, 'restaur') !== false || strpos($n, 'cook') !== false || strpos($n, 'chef') !== false) {
            return ['icon' => 'fa fa-cutlery', 'bg' => '#FEF3C7', 'color' => '#D97706'];
        } elseif (strpos($n, 'construct') !== false || strpos($n, 'architect') !== false) {
            return ['icon' => 'fa fa-building', 'bg' => '#F1F5F9', 'color' => '#475569'];
        } elseif (strpos($n, 'design') !== false || strpos($n, 'art') !== false || strpos($n, 'creat') !== false) {
            return ['icon' => 'fa fa-paint-brush', 'bg' => '#FDF4FF', 'color' => '#A855F7'];
        }
        return ['icon' => 'fa fa-briefcase', 'bg' => '#F8FAFC', 'color' => '#475569'];
    }
}

// Fetch active functional areas, prioritizing top modern categories
$allActiveFunctionalAreas = App\FunctionalArea::lang()->active()
    ->orderByRaw("FIELD(functional_area, 'Software & IT', 'Sales', 'Marketing', 'Data Science', 'Engineering', 'Banking & Finance', 'Accounts & Finance', 'HR & Recruitment', 'BPO / Call Center', 'Delivery & Logistics', 'Driver Jobs', 'Supply Chain', 'Work From Home', 'Internship / Fresher') DESC, functional_area ASC")
    ->limit(24)->get();
$allActiveJobTypes = App\JobType::lang()->active()->orderBy('job_type', 'asc')->get();
@endphp

<style>
/* Modern Category Pills Styling */
.naukri-categories-section {
    padding: 50px 0 60px 0;
    background: #F8FAFC;
    position: relative;
}
.naukri-cat-header {
    margin-bottom: 30px;
}
.naukri-cat-badge {
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
    margin-bottom: 10px;
}
.naukri-cat-title {
    font-size: 28px;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 8px;
    letter-spacing: -0.5px;
}
.naukri-cat-subtitle {
    font-size: 15px;
    color: #64748B;
    margin: 0;
}
.naukri-nav-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    border-bottom: none !important;
    margin-bottom: 24px;
}
.naukri-nav-tabs .nav-item {
    margin: 0;
}
.naukri-nav-tabs .nav-link {
    border: 1px solid #E2E8F0 !important;
    background: #FFFFFF !important;
    color: #475569 !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    padding: 10px 22px !important;
    border-radius: 50px !important;
    transition: all 0.2s ease !important;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}
.naukri-nav-tabs .nav-link:hover {
    border-color: #CBD5E1 !important;
    color: #1E293B !important;
    transform: translateY(-1px);
}
.naukri-nav-tabs .nav-link.active {
    background: #4F46E5 !important;
    color: #FFFFFF !important;
    border-color: #4F46E5 !important;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25) !important;
}
.naukri-pills-wrap {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    align-items: center;
}
.naukri-pill-card {
    display: inline-flex;
    align-items: center;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 10px 18px;
    text-decoration: none !important;
    transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
    cursor: pointer;
    min-height: 48px;
    /* Critical: allow flex children to shrink */
    min-width: 0;
    overflow: hidden;
}
.naukri-pill-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px -4px rgba(0, 0, 0, 0.08);
    border-color: #CBD5E1;
    background: #FFFFFF;
}
.naukri-pill-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
    font-size: 13px;
    flex-shrink: 0;
}
.naukri-pill-name {
    font-size: 14px;
    font-weight: 600;
    color: #1E293B;
    /* Allow truncation */
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    min-width: 0;
    flex: 1;
    letter-spacing: -0.1px;
}
.naukri-pill-card:hover .naukri-pill-name {
    color: #0F172A;
}
.naukri-pill-badge {
    font-size: 12px;
    color: #64748B;
    font-weight: 500;
    margin-left: 6px;
}
.naukri-pill-arrow {
    color: #94A3B8;
    font-size: 13px;
    margin-left: auto;
    padding-left: 6px;
    transition: transform 0.2s ease, color 0.2s ease;
}
.naukri-pill-card:hover .naukri-pill-arrow {
    transform: translateX(3px);
    color: #475569;
}

/* Mobile & Tablet Responsiveness */
@media (max-width: 991px) {
    .naukri-categories-section {
        padding: 36px 0 45px !important;
    }
    .naukri-nav-tabs {
        margin-top: 16px !important;
        justify-content: flex-start !important;
    }
}

@media (max-width: 767px) {
    .naukri-cat-title {
        font-size: 23px !important;
        letter-spacing: -0.4px !important;
        line-height: 1.25 !important;
        margin-bottom: 6px !important;
    }
    .naukri-cat-subtitle {
        font-size: 13px !important;
        line-height: 1.5 !important;
        margin-bottom: 6px !important;
    }
    .naukri-nav-tabs {
        display: flex !important;
        flex-wrap: nowrap !important;
        overflow-x: auto !important;
        gap: 8px !important;
        padding: 4px 2px 10px 2px !important;
        margin-bottom: 16px !important;
        border: none !important;
        -webkit-overflow-scrolling: touch !important;
        scrollbar-width: none !important;
    }
    .naukri-nav-tabs::-webkit-scrollbar {
        display: none !important;
    }
    .naukri-nav-tabs .nav-item {
        flex-shrink: 0 !important;
        margin: 0 !important;
    }
    .naukri-nav-tabs .nav-link {
        white-space: nowrap !important;
        font-size: 12.5px !important;
        padding: 8px 16px !important;
        border-radius: 50px !important;
    }

    /* Native App Style 2-Column Grid on Mobile */
    .naukri-pills-wrap {
        display: grid !important;
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 10px !important;
    }
    .naukri-pill-card {
        width: 100% !important;
        padding: 10px 10px !important;
        min-height: 48px !important;
        border-radius: 12px !important;
        display: flex !important;
        align-items: center !important;
        box-sizing: border-box !important;
    }
    .naukri-pill-icon {
        width: 28px !important;
        height: 28px !important;
        font-size: 12px !important;
        margin-right: 8px !important;
        flex-shrink: 0 !important;
    }
    .naukri-pill-name {
        font-size: 12.5px !important;
        font-weight: 600 !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        flex: 1 !important;
    }
    .naukri-pill-badge {
        font-size: 11px !important;
        margin-left: 3px !important;
    }
    .naukri-pill-arrow {
        font-size: 11px !important;
        margin-left: 2px !important;
    }
}

@media (max-width: 420px) {
    .naukri-pills-wrap {
        grid-template-columns: 1fr !important;
    }
    .naukri-pill-card {
        padding: 11px 14px !important;
    }
    .naukri-pill-name {
        font-size: 13.5px !important;
    }
}
</style>

<div class="naukri-categories-section">
    <div class="container">
        <div class="row align-items-end naukri-cat-header">
            <div class="col-lg-6 col-md-12">
                <div class="naukri-cat-badge">
                    <i class="fa fa-th-large"></i> {{__('Popular Categories')}}
                </div>
                <h2 class="naukri-cat-title">{{__('Browse Jobs by Category')}}</h2>
                <p class="naukri-cat-subtitle">{{__('Find high-paying career opportunities across top functional domains & roles')}}</p>
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
                        <a data-toggle="tab" class="nav-link" href="#bycities" aria-expanded="false">
                            <i class="fa fa-map-marker"></i> {{__('Cities')}}
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
            {{-- 1. FUNCTIONAL AREA PILLS --}}
            <div class="tab-pane fade show active" id="byfunctional" role="tabpanel">
                <div class="naukri-pills-wrap">
                    @if(isset($allActiveFunctionalAreas) && count($allActiveFunctionalAreas))
                        @foreach($allActiveFunctionalAreas as $fa)
                            @php
                                $iconData = getNaukriCategoryIcon($fa->functional_area);
                                $numJobs = App\Job::countNumJobs('functional_area_id', $fa->functional_area_id);
                            @endphp
                            <a href="{{ route('job.list', ['functional_area_id[]' => $fa->functional_area_id]) }}" class="naukri-pill-card" title="{{ $fa->functional_area }}">
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
            </div>

            {{-- 2. WORK MODE / JOB TYPE PILLS --}}
            <div class="tab-pane fade" id="byjobtype" role="tabpanel">
                <div class="naukri-pills-wrap">
                    @if(isset($allActiveJobTypes) && count($allActiveJobTypes))
                        @foreach($allActiveJobTypes as $jt)
                            @php
                                $iconData = getNaukriCategoryIcon($jt->job_type);
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

            {{-- 3. CITIES PILLS --}}
            <div class="tab-pane fade" id="bycities" role="tabpanel">
                <div class="naukri-pills-wrap">
                    @if(isset($topCityIds) && count($topCityIds))
                        @foreach($topCityIds as $city_id_num_jobs)
                            @php
                                $city = App\City::getCityById($city_id_num_jobs->city_id);
                            @endphp
                            @if(null !== $city)
                                @php
                                    $iconData = ['icon' => 'fa fa-map-marker', 'bg' => '#EFF6FF', 'color' => '#2563EB'];
                                @endphp
                                <a href="{{ route('jobs.city', ['city_slug' => \Illuminate\Support\Str::slug($city->city)]) }}" class="naukri-pill-card" title="{{ $city->city }}">
                                    <span class="naukri-pill-icon" style="background: {{ $iconData['bg'] }}; color: {{ $iconData['color'] }};">
                                        <i class="{{ $iconData['icon'] }}"></i>
                                    </span>
                                    <span class="naukri-pill-name">{{ $city->city }}</span>
                                    <span class="naukri-pill-badge">({{ $city_id_num_jobs->num_jobs }})</span>
                                    <i class="fa fa-angle-right naukri-pill-arrow"></i>
                                </a>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- 4. INDUSTRIES PILLS --}}
            <div class="tab-pane fade" id="byindustries" role="tabpanel">
                <div class="naukri-pills-wrap">
                    @if(isset($topIndustryIds) && count($topIndustryIds))
                        @foreach($topIndustryIds as $industry_id => $num_jobs)
                            @php
                                $industry = App\Industry::where('industry_id', '=', $industry_id)->lang()->active()->first();
                            @endphp
                            @if(null !== $industry)
                                @php
                                    $iconData = getNaukriCategoryIcon($industry->industry);
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
    </div>
</div>