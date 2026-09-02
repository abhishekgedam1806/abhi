@php
$activeFilterCount = 0;
if (!empty(Request::get('search'))) $activeFilterCount++;
if (!empty(Request::get('job_title'))) $activeFilterCount += count((array)Request::get('job_title'));
if (!empty(Request::get('country_id'))) $activeFilterCount += count((array)Request::get('country_id'));
if (!empty(Request::get('state_id'))) $activeFilterCount += count((array)Request::get('state_id'));
if (!empty(Request::get('city_id'))) $activeFilterCount += count((array)Request::get('city_id'));
if (!empty(Request::get('functional_area_id'))) $activeFilterCount += count((array)Request::get('functional_area_id'));
if (!empty(Request::get('job_type_id'))) $activeFilterCount += count((array)Request::get('job_type_id'));
if (!empty(Request::get('job_experience_id'))) $activeFilterCount += count((array)Request::get('job_experience_id'));
if (!empty(Request::get('job_shift_id'))) $activeFilterCount += count((array)Request::get('job_shift_id'));
if (!empty(Request::get('salary_from')) && (int)Request::get('salary_from') > 0) $activeFilterCount++;
if (!empty(Request::get('date_posted'))) $activeFilterCount++;
if (Request::get('is_freelance') == '1') $activeFilterCount++;
@endphp

<div class="col-lg-3 col-md-4 col-12 apna-filter-sidebar-col">
    <div class="apna-filter-card">
        {{-- Top Filter Header --}}
        <div class="apna-filter-header">
            <div class="filter-header-left">
                <i class="fa fa-sliders"></i>
                <span>Filters (<span id="totalActiveFilters">{{ $activeFilterCount }}</span>)</span>
            </div>
            @if($activeFilterCount > 0)
                <a href="{{ route('job.list') }}" class="apna-clear-all">Clear all</a>
            @else
                <a href="{{ route('job.list') }}" class="apna-clear-all" style="display:none;">Clear all</a>
            @endif
        </div>

        {{-- Active Filter Tags Row --}}
        @if($activeFilterCount > 0)
        <div class="apna-active-tags-row">
            @if(!empty(Request::get('search')))
                <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="apna-active-chip">"{{ Request::get('search') }}" <i class="fa fa-times"></i></a>
            @endif
            @if(Request::get('is_freelance') == '1')
                <a href="{{ request()->fullUrlWithQuery(['is_freelance' => null]) }}" class="apna-active-chip">Remote / WFH <i class="fa fa-times"></i></a>
            @endif
            @if(!empty(Request::get('date_posted')))
                <a href="{{ request()->fullUrlWithQuery(['date_posted' => null]) }}" class="apna-active-chip">
                    @if(Request::get('date_posted') == '1') Posted 24h
                    @elseif(Request::get('date_posted') == '3') Posted 3d
                    @else Posted 7d @endif
                    <i class="fa fa-times"></i>
                </a>
            @endif
            @if(!empty(Request::get('salary_from')) && (int)Request::get('salary_from') > 0)
                <a href="{{ request()->fullUrlWithQuery(['salary_from' => null]) }}" class="apna-active-chip">Min ₹{{ number_format(Request::get('salary_from')) }} <i class="fa fa-times"></i></a>
            @endif
            @if(!empty(Request::get('job_type_id')))
                @foreach((array)Request::get('job_type_id') as $jtid)
                    @php $jtObj = App\JobType::where('job_type_id', $jtid)->lang()->first(); @endphp
                    @if($jtObj)
                        <a href="{{ request()->fullUrlWithQuery(['job_type_id' => array_diff((array)Request::get('job_type_id'), [$jtid])]) }}" class="apna-active-chip">{{ $jtObj->job_type }} <i class="fa fa-times"></i></a>
                    @endif
                @endforeach
            @endif
            @if(!empty(Request::get('functional_area_id')))
                @foreach((array)Request::get('functional_area_id') as $faid)
                    @php $faObj = App\FunctionalArea::where('functional_area_id', $faid)->lang()->first(); @endphp
                    @if($faObj)
                        <a href="{{ request()->fullUrlWithQuery(['functional_area_id' => array_diff((array)Request::get('functional_area_id'), [$faid])]) }}" class="apna-active-chip">{{ $faObj->functional_area }} <i class="fa fa-times"></i></a>
                    @endif
                @endforeach
            @endif
            @if(!empty(Request::get('city_id')))
                @foreach((array)Request::get('city_id') as $cid)
                    @php $cityObj = App\City::getCityById($cid); @endphp
                    @if($cityObj)
                        <a href="{{ request()->fullUrlWithQuery(['city_id' => array_diff((array)Request::get('city_id'), [$cid])]) }}" class="apna-active-chip">📍 {{ $cityObj->city }} <i class="fa fa-times"></i></a>
                    @endif
                @endforeach
            @endif
            @if(!empty(Request::get('area_id')))
                @foreach((array)Request::get('area_id') as $aid)
                    @php $areaObj = App\Area::find($aid); @endphp
                    @if($areaObj)
                        <a href="{{ request()->fullUrlWithQuery(['area_id' => array_diff((array)Request::get('area_id'), [$aid])]) }}" class="apna-active-chip">🎯 {{ $areaObj->area_name }} <i class="fa fa-times"></i></a>
                    @endif
                @endforeach
            @endif
            @if(!empty(Request::get('job_experience_id')))
                @foreach((array)Request::get('job_experience_id') as $expid)
                    @php $expObj = App\JobExperience::where('job_experience_id', $expid)->lang()->first(); @endphp
                    @if($expObj)
                        <a href="{{ request()->fullUrlWithQuery(['job_experience_id' => array_diff((array)Request::get('job_experience_id'), [$expid])]) }}" class="apna-active-chip">{{ $expObj->job_experience }} <i class="fa fa-times"></i></a>
                    @endif
                @endforeach
            @endif
        </div>
        @endif

        <input type="hidden" name="search" value="{{ Request::get('search', '') }}" />
        @if(Request::has('is_freelance'))
            <input type="hidden" name="is_freelance" value="{{ Request::get('is_freelance') }}" />
        @endif

        {{-- 1. Date Posted Filter --}}
        <div class="apna-filter-group">
            <div class="apna-filter-title" data-toggle="collapse" data-target="#collapseDatePosted" aria-expanded="true">
                <span>Date posted</span>
                <i class="fa fa-chevron-up toggle-ico"></i>
            </div>
            <div id="collapseDatePosted" class="collapse show">
                <div class="apna-filter-options">
                    <label class="apna-radio-label">
                        <input type="radio" name="date_posted" value="" {{ empty(Request::get('date_posted')) ? 'checked' : '' }} onchange="this.form.submit()">
                        <span class="custom-radio"></span>
                        <span class="opt-text">All Time</span>
                    </label>
                    <label class="apna-radio-label">
                        <input type="radio" name="date_posted" value="1" {{ Request::get('date_posted') == '1' ? 'checked' : '' }} onchange="this.form.submit()">
                        <span class="custom-radio"></span>
                        <span class="opt-text">Last 24 hours</span>
                    </label>
                    <label class="apna-radio-label">
                        <input type="radio" name="date_posted" value="3" {{ Request::get('date_posted') == '3' ? 'checked' : '' }} onchange="this.form.submit()">
                        <span class="custom-radio"></span>
                        <span class="opt-text">Last 3 days</span>
                    </label>
                    <label class="apna-radio-label">
                        <input type="radio" name="date_posted" value="7" {{ Request::get('date_posted') == '7' ? 'checked' : '' }} onchange="this.form.submit()">
                        <span class="custom-radio"></span>
                        <span class="opt-text">Last 7 days</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- 2. Minimum Monthly Salary Slider --}}
        <div class="apna-filter-group">
            <div class="apna-filter-title" data-toggle="collapse" data-target="#collapseSalary" aria-expanded="true">
                <span>Salary</span>
                <i class="fa fa-chevron-up toggle-ico"></i>
            </div>
            <div id="collapseSalary" class="collapse show">
                <div class="salary-sublabel">Minimum monthly salary</div>
                <div class="salary-slider-wrap">
                    <input type="range" class="salary-range-slider" min="0" max="150000" step="5000" 
                           id="salaryRangeInput" name="salary_from" 
                           value="{{ Request::get('salary_from', 0) }}" 
                           oninput="updateSalaryLabel(this.value)" 
                           onchange="this.form.submit()">
                    <div class="salary-range-labels">
                        <span id="salaryMinVal">₹ {{ Request::get('salary_from', 0) > 0 ? number_format(Request::get('salary_from')) : '0' }}</span>
                        <span>1.5 Lakhs+</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Location / City with Quick Metro Pills & Search --}}
        <div class="apna-filter-group">
            <div class="apna-filter-title" data-toggle="collapse" data-target="#collapseCities" aria-expanded="true">
                <span>Location @if(!empty(Request::get('city_id')))<span class="group-count-badge">{{ count((array)Request::get('city_id')) }}</span>@endif</span>
                <i class="fa fa-chevron-up toggle-ico"></i>
            </div>
            <div id="collapseCities" class="collapse show">
                {{-- Quick Metro City Pills --}}
                <div class="quick-city-pills-row" style="display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 10px;">
                    @php
                        $topCities = [
                            'Nagpur' => 2715,
                            'Pune' => 2763,
                            'Mumbai' => 2707,
                            'Bengaluru' => 1558,
                        ];
                    @endphp
                    @foreach($topCities as $cName => $cId)
                        @php
                            $isCityActive = in_array($cId, (array)Request::get('city_id', []));
                        @endphp
                        <a href="{{ $isCityActive ? request()->fullUrlWithQuery(['city_id' => array_diff((array)Request::get('city_id'), [$cId])]) : request()->fullUrlWithQuery(['city_id' => array_merge((array)Request::get('city_id', []), [$cId])]) }}" 
                           class="quick-city-pill {{ $isCityActive ? 'active-city-pill' : '' }}"
                           style="padding: 3px 9px; font-size: 11px; font-weight: 600; border-radius: 16px; text-decoration: none; border: 1px solid {{ $isCityActive ? '#2563EB' : '#E2E8F0' }}; background: {{ $isCityActive ? '#EFF6FF' : '#F8FAFC' }}; color: {{ $isCityActive ? '#2563EB' : '#475569' }}; transition: all 0.2s ease;">
                            <i class="fa fa-map-marker" style="margin-right: 3px; font-size: 10px; color: {{ $isCityActive ? '#2563EB' : '#94A3B8' }};"></i> {{ $cName }}
                        </a>
                    @endforeach
                </div>

                <div class="filter-search-box">
                    <i class="fa fa-search"></i>
                    <input type="text" placeholder="Search city or area..." class="city-filter-input" onkeyup="filterCheckboxList(this, 'city-options-list')">
                </div>
                <div class="apna-filter-options scrollable-options" id="city-options-list">
                    @if(isset($cityIdsArray) && count($cityIdsArray))
                        @foreach($cityIdsArray as $key => $city_id)
                            @php
                                $city = App\City::where('city_id', '=', $city_id)->lang()->active()->first();
                            @endphp
                            @if(null !== $city)
                                @php
                                    $checked = (in_array($city->city_id, (array)Request::get('city_id', []))) ? 'checked="checked"' : '';
                                    $numJobs = App\Job::countNumJobs('city_id', $city->city_id);
                                @endphp
                                <label class="apna-checkbox-label filter-item-row">
                                    <input type="checkbox" name="city_id[]" value="{{ $city->city_id }}" {{ $checked }} onchange="this.form.submit()">
                                    <span class="custom-checkbox"></span>
                                    <span class="opt-text">{{ $city->city }}</span>
                                    @if($numJobs > 0)
                                    <span class="opt-count">{{ $numJobs }}</span>
                                    @endif
                                </label>
                            @endif
                        @endforeach
                    @endif
                </div>

                {{-- Local Areas for selected city --}}
                @php
                    $selectedCityIds = (array)Request::get('city_id', []);
                    $activeAreas = [];
                    if (!empty($selectedCityIds)) {
                        $activeAreas = App\Area::whereIn('city_id', $selectedCityIds)->where('is_active', 1)->orderBy('area_name', 'asc')->get();
                    }
                @endphp
                @if(count($activeAreas) > 0)
                    <div style="margin-top: 12px; border-top: 1px dashed #CBD5E1; padding-top: 10px;">
                        <div style="font-size: 11px; font-weight: 700; color: #1E293B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; justify-content: space-between;">
                            <span><i class="fa fa-crosshairs" style="color: #2563EB;"></i> Local Areas (5km Radius)</span>
                            <span style="font-size: 10px; color: #2563EB; font-weight: 800;">{{ count($activeAreas) }}</span>
                        </div>
                        <div class="apna-filter-options scrollable-options" style="max-height: 140px;">
                            @foreach($activeAreas as $area)
                                @php
                                    $areaChecked = in_array($area->id, (array)Request::get('area_id', [])) ? 'checked="checked"' : '';
                                    $numAreaJobs = App\Job::where('area_id', $area->id)->orWhere('area_name', 'like', '%' . $area->area_name . '%')->count();
                                @endphp
                                <label class="apna-checkbox-label filter-item-row" style="font-size: 12px;">
                                    <input type="checkbox" name="area_id[]" value="{{ $area->id }}" {{ $areaChecked }} onchange="this.form.submit()">
                                    <span class="custom-checkbox"></span>
                                    <span class="opt-text">{{ $area->area_name }}</span>
                                    @if($numAreaJobs > 0)
                                        <span class="opt-count">{{ $numAreaJobs }}</span>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- 4. Work Mode / Job Type --}}
        <div class="apna-filter-group">
            <div class="apna-filter-title" data-toggle="collapse" data-target="#collapseWorkMode" aria-expanded="true">
                <span>Job Type / Mode @if(!empty(Request::get('job_type_id')))<span class="group-count-badge">{{ count((array)Request::get('job_type_id')) }}</span>@endif</span>
                <i class="fa fa-chevron-up toggle-ico"></i>
            </div>
            <div id="collapseWorkMode" class="collapse show">
                <div class="apna-filter-options">
                    @if(isset($jobTypeIdsArray) && count($jobTypeIdsArray))
                        @foreach($jobTypeIdsArray as $key => $job_type_id)
                            @php
                                $jobType = App\JobType::where('job_type_id', '=', $job_type_id)->lang()->active()->first();
                            @endphp
                            @if(null !== $jobType)
                                @php
                                    $checked = (in_array($jobType->job_type_id, (array)Request::get('job_type_id', []))) ? 'checked="checked"' : '';
                                    $numJobs = App\Job::countNumJobs('job_type_id', $jobType->job_type_id);
                                @endphp
                                <label class="apna-checkbox-label">
                                    <input type="checkbox" name="job_type_id[]" value="{{ $jobType->job_type_id }}" {{ $checked }} onchange="this.form.submit()">
                                    <span class="custom-checkbox"></span>
                                    <span class="opt-text">{{ $jobType->job_type }}</span>
                                    @if($numJobs > 0)
                                    <span class="opt-count">{{ $numJobs }}</span>
                                    @endif
                                </label>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        {{-- 5. Department / Functional Area with search --}}
        <div class="apna-filter-group">
            <div class="apna-filter-title" data-toggle="collapse" data-target="#collapseDept" aria-expanded="true">
                <span>Department @if(!empty(Request::get('functional_area_id')))<span class="group-count-badge">{{ count((array)Request::get('functional_area_id')) }}</span>@endif</span>
                <i class="fa fa-chevron-up toggle-ico"></i>
            </div>
            <div id="collapseDept" class="collapse show">
                <div class="filter-search-box">
                    <i class="fa fa-search"></i>
                    <input type="text" placeholder="Search department..." class="dept-filter-input" onkeyup="filterCheckboxList(this, 'dept-options-list')">
                </div>
                <div class="apna-filter-options scrollable-options" id="dept-options-list">
                    @if(isset($functionalAreaIdsArray) && count($functionalAreaIdsArray))
                        @foreach($functionalAreaIdsArray as $key => $functional_area_id)
                            @php
                                $functionalArea = App\FunctionalArea::where('functional_area_id', '=', $functional_area_id)->lang()->active()->first();
                            @endphp
                            @if(null !== $functionalArea)
                                @php
                                    $checked = (in_array($functionalArea->functional_area_id, (array)Request::get('functional_area_id', []))) ? 'checked="checked"' : '';
                                    $numJobs = App\Job::countNumJobs('functional_area_id', $functionalArea->functional_area_id);
                                @endphp
                                <label class="apna-checkbox-label filter-item-row">
                                    <input type="checkbox" name="functional_area_id[]" value="{{ $functionalArea->functional_area_id }}" {{ $checked }} onchange="this.form.submit()">
                                    <span class="custom-checkbox"></span>
                                    <span class="opt-text">{{ $functionalArea->functional_area }}</span>
                                    @if($numJobs > 0)
                                    <span class="opt-count">{{ $numJobs }}</span>
                                    @endif
                                </label>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        {{-- 6. Experience Level --}}
        <div class="apna-filter-group">
            <div class="apna-filter-title" data-toggle="collapse" data-target="#collapseExp" aria-expanded="true">
                <span>Experience @if(!empty(Request::get('job_experience_id')))<span class="group-count-badge">{{ count((array)Request::get('job_experience_id')) }}</span>@endif</span>
                <i class="fa fa-chevron-up toggle-ico"></i>
            </div>
            <div id="collapseExp" class="collapse show">
                <div class="apna-filter-options">
                    @if(isset($jobExperienceIdsArray) && count($jobExperienceIdsArray))
                        @foreach($jobExperienceIdsArray as $key => $job_experience_id)
                            @php
                                $jobExperience = App\JobExperience::where('job_experience_id', '=', $job_experience_id)->lang()->active()->first();
                            @endphp
                            @if(null !== $jobExperience)
                                @php
                                    $checked = (in_array($jobExperience->job_experience_id, (array)Request::get('job_experience_id', []))) ? 'checked="checked"' : '';
                                    $numJobs = App\Job::countNumJobs('job_experience_id', $jobExperience->job_experience_id);
                                @endphp
                                <label class="apna-checkbox-label">
                                    <input type="checkbox" name="job_experience_id[]" value="{{ $jobExperience->job_experience_id }}" {{ $checked }} onchange="this.form.submit()">
                                    <span class="custom-checkbox"></span>
                                    <span class="opt-text">{{ $jobExperience->job_experience }}</span>
                                    @if($numJobs > 0)
                                    <span class="opt-count">{{ $numJobs }}</span>
                                    @endif
                                </label>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        {{-- 7. Work Shift --}}
        @if(isset($jobShiftIdsArray) && count($jobShiftIdsArray))
        <div class="apna-filter-group">
            <div class="apna-filter-title" data-toggle="collapse" data-target="#collapseShift" aria-expanded="false">
                <span>Work shift @if(!empty(Request::get('job_shift_id')))<span class="group-count-badge">{{ count((array)Request::get('job_shift_id')) }}</span>@endif</span>
                <i class="fa fa-chevron-down toggle-ico"></i>
            </div>
            <div id="collapseShift" class="collapse">
                <div class="apna-filter-options">
                    @foreach($jobShiftIdsArray as $key => $job_shift_id)
                        @php
                            $jobShift = App\JobShift::where('job_shift_id', '=', $job_shift_id)->lang()->active()->first();
                        @endphp
                        @if(null !== $jobShift)
                            @php
                                $checked = (in_array($jobShift->job_shift_id, (array)Request::get('job_shift_id', []))) ? 'checked="checked"' : '';
                                $numJobs = App\Job::countNumJobs('job_shift_id', $jobShift->job_shift_id);
                            @endphp
                            <label class="apna-checkbox-label">
                                <input type="checkbox" name="job_shift_id[]" value="{{ $jobShift->job_shift_id }}" {{ $checked }} onchange="this.form.submit()">
                                <span class="custom-checkbox"></span>
                                <span class="opt-text">{{ $jobShift->job_shift }}</span>
                                @if($numJobs > 0)
                                <span class="opt-count">{{ $numJobs }}</span>
                                @endif
                            </label>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- 8. Sort By --}}
        <div class="apna-filter-group" style="border-bottom:none;">
            <div class="apna-filter-title" data-toggle="collapse" data-target="#collapseSortBy" aria-expanded="true">
                <span>Sort by</span>
                <i class="fa fa-chevron-up toggle-ico"></i>
            </div>
            <div id="collapseSortBy" class="collapse show">
                <div class="apna-filter-options">
                    <label class="apna-radio-label">
                        <input type="radio" name="order_by" value="id" {{ (empty(Request::get('order_by')) || Request::get('order_by') == 'id') ? 'checked' : '' }} onchange="this.form.submit()">
                        <span class="custom-radio"></span>
                        <span class="opt-text">Recommended & Relevant</span>
                    </label>
                    <label class="apna-radio-label">
                        <input type="radio" name="order_by" value="salary" {{ Request::get('order_by') == 'salary' ? 'checked' : '' }} onchange="this.form.submit()">
                        <span class="custom-radio"></span>
                        <span class="opt-text">Salary: High to Low</span>
                    </label>
                    <label class="apna-radio-label">
                        <input type="radio" name="order_by" value="new" {{ Request::get('order_by') == 'new' ? 'checked' : '' }} onchange="this.form.submit()">
                        <span class="custom-radio"></span>
                        <span class="opt-text">Fresh: New to Old</span>
                    </label>
                </div>
            </div>
        </div>

    </div>
</div>