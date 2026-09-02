{{--
    MOBILE FILTER DRAWER BODY
    Renders all filter options inside the mobile bottom sheet.
    All filter logic is identical to job_list_side_bar.blade.php.
    Used ONLY inside the mobile drawer — no sidebar wrapper/card.
--}}

{{-- 1. Date Posted --}}
<div class="apna-filter-group">
    <div class="apna-filter-title" data-toggle="collapse" data-target="#drwDatePosted" aria-expanded="true">
        <span>Date posted</span>
        <i class="fa fa-chevron-up toggle-ico"></i>
    </div>
    <div id="drwDatePosted" class="collapse show">
        <div class="apna-filter-options">
            <label class="apna-radio-label">
                <input type="radio" name="date_posted" value="" {{ empty(Request::get('date_posted')) ? 'checked' : '' }}>
                <span class="custom-radio"></span>
                <span class="opt-text">All Time</span>
            </label>
            <label class="apna-radio-label">
                <input type="radio" name="date_posted" value="1" {{ Request::get('date_posted') == '1' ? 'checked' : '' }}>
                <span class="custom-radio"></span>
                <span class="opt-text">Last 24 hours</span>
            </label>
            <label class="apna-radio-label">
                <input type="radio" name="date_posted" value="3" {{ Request::get('date_posted') == '3' ? 'checked' : '' }}>
                <span class="custom-radio"></span>
                <span class="opt-text">Last 3 days</span>
            </label>
            <label class="apna-radio-label">
                <input type="radio" name="date_posted" value="7" {{ Request::get('date_posted') == '7' ? 'checked' : '' }}>
                <span class="custom-radio"></span>
                <span class="opt-text">Last 7 days</span>
            </label>
        </div>
    </div>
</div>

{{-- 2. Minimum Monthly Salary Slider --}}
<div class="apna-filter-group">
    <div class="apna-filter-title" data-toggle="collapse" data-target="#drwSalary" aria-expanded="true">
        <span>Salary</span>
        <i class="fa fa-chevron-up toggle-ico"></i>
    </div>
    <div id="drwSalary" class="collapse show">
        <div class="salary-sublabel">Minimum monthly salary</div>
        <div class="salary-slider-wrap">
            <input type="range" class="salary-range-slider" min="0" max="150000" step="5000"
                   id="salaryRangeInputDrawer" name="salary_from"
                   value="{{ Request::get('salary_from', 0) }}"
                   oninput="updateSalaryLabelDrawer(this.value)">
            <div class="salary-range-labels">
                <span id="salaryMinValDrawer">₹ {{ Request::get('salary_from', 0) > 0 ? number_format(Request::get('salary_from')) : '0' }}</span>
                <span>1.5 Lakhs+</span>
            </div>
        </div>
    </div>
</div>

{{-- 3. Location / City with Quick Metro Pills --}}
<div class="apna-filter-group">
    <div class="apna-filter-title" data-toggle="collapse" data-target="#drwCities" aria-expanded="true">
        <span>Location @if(!empty(Request::get('city_id')))<span class="group-count-badge">{{ count((array)Request::get('city_id')) }}</span>@endif</span>
        <i class="fa fa-chevron-up toggle-ico"></i>
    </div>
    <div id="drwCities" class="collapse show">
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
                @php $isCityActive = in_array($cId, (array)Request::get('city_id', [])); @endphp
                <a href="{{ $isCityActive ? request()->fullUrlWithQuery(['city_id' => array_diff((array)Request::get('city_id'), [$cId])]) : request()->fullUrlWithQuery(['city_id' => array_merge((array)Request::get('city_id', []), [$cId])]) }}" 
                   class="quick-city-pill {{ $isCityActive ? 'active-city-pill' : '' }}"
                   style="padding: 3px 9px; font-size: 11px; font-weight: 600; border-radius: 16px; text-decoration: none; border: 1px solid {{ $isCityActive ? '#2563EB' : '#E2E8F0' }}; background: {{ $isCityActive ? '#EFF6FF' : '#F8FAFC' }}; color: {{ $isCityActive ? '#2563EB' : '#475569' }};">
                    <i class="fa fa-map-marker" style="margin-right: 3px; font-size: 10px; color: {{ $isCityActive ? '#2563EB' : '#94A3B8' }};"></i> {{ $cName }}
                </a>
            @endforeach
        </div>
        <div class="filter-search-box">
            <i class="fa fa-search"></i>
            <input type="text" placeholder="Search city or area..." class="city-filter-input" onkeyup="filterCheckboxList(this, 'drw-city-options-list')">
        </div>
        <div class="apna-filter-options scrollable-options" id="drw-city-options-list">
            @if(isset($cityIdsArray) && count($cityIdsArray))
                @foreach($cityIdsArray as $key => $city_id)
                    @php
                        $city = App\City::where('city_id', '=', $city_id)->lang()->active()->first();
                    @endphp
                    @if(null !== $city)
                        @php
                            $checked = in_array($city->city_id, (array)Request::get('city_id', [])) ? 'checked' : '';
                            $numJobs = App\Job::countNumJobs('city_id', $city->city_id);
                        @endphp
                        <label class="apna-checkbox-label filter-item-row">
                            <input type="checkbox" name="city_id[]" value="{{ $city->city_id }}" {{ $checked }}>
                            <span class="custom-checkbox"></span>
                            <span class="opt-text">{{ $city->city }}</span>
                            @if($numJobs > 0)<span class="opt-count">{{ $numJobs }}</span>@endif
                        </label>
                    @endif
                @endforeach
            @endif
        </div>

        {{-- Local Areas for selected city in Drawer --}}
        @php
            $drwSelectedCityIds = (array)Request::get('city_id', []);
            $drwActiveAreas = [];
            if (!empty($drwSelectedCityIds)) {
                $drwActiveAreas = App\Area::whereIn('city_id', $drwSelectedCityIds)->where('is_active', 1)->orderBy('area_name', 'asc')->get();
            }
        @endphp
        @if(count($drwActiveAreas) > 0)
            <div style="margin-top: 12px; border-top: 1px dashed #CBD5E1; padding-top: 10px;">
                <div style="font-size: 11px; font-weight: 700; color: #1E293B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; justify-content: space-between;">
                    <span><i class="fa fa-crosshairs" style="color: #2563EB;"></i> Local Areas (5km Radius)</span>
                    <span style="font-size: 10px; color: #2563EB; font-weight: 800;">{{ count($drwActiveAreas) }}</span>
                </div>
                <div class="apna-filter-options scrollable-options" style="max-height: 140px;">
                    @foreach($drwActiveAreas as $area)
                        @php
                            $areaChecked = in_array($area->id, (array)Request::get('area_id', [])) ? 'checked' : '';
                            $numAreaJobs = App\Job::where('area_id', $area->id)->orWhere('area_name', 'like', '%' . $area->area_name . '%')->count();
                        @endphp
                        <label class="apna-checkbox-label filter-item-row" style="font-size: 12px;">
                            <input type="checkbox" name="area_id[]" value="{{ $area->id }}" {{ $areaChecked }}>
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
    <div class="apna-filter-title" data-toggle="collapse" data-target="#drwWorkMode" aria-expanded="true">
        <span>Job Type / Mode @if(!empty(Request::get('job_type_id')))<span class="group-count-badge">{{ count((array)Request::get('job_type_id')) }}</span>@endif</span>
        <i class="fa fa-chevron-up toggle-ico"></i>
    </div>
    <div id="drwWorkMode" class="collapse show">
        <div class="apna-filter-options">
            @if(isset($jobTypeIdsArray) && count($jobTypeIdsArray))
                @foreach($jobTypeIdsArray as $key => $job_type_id)
                    @php
                        $jobType = App\JobType::where('job_type_id', '=', $job_type_id)->lang()->active()->first();
                    @endphp
                    @if(null !== $jobType)
                        @php
                            $checked = in_array($jobType->job_type_id, (array)Request::get('job_type_id', [])) ? 'checked' : '';
                            $numJobs = App\Job::countNumJobs('job_type_id', $jobType->job_type_id);
                        @endphp
                        <label class="apna-checkbox-label">
                            <input type="checkbox" name="job_type_id[]" value="{{ $jobType->job_type_id }}" {{ $checked }}>
                            <span class="custom-checkbox"></span>
                            <span class="opt-text">{{ $jobType->job_type }}</span>
                            @if($numJobs > 0)<span class="opt-count">{{ $numJobs }}</span>@endif
                        </label>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div>

{{-- 5. Department / Functional Area with search --}}
<div class="apna-filter-group">
    <div class="apna-filter-title" data-toggle="collapse" data-target="#drwDept" aria-expanded="true">
        <span>Department @if(!empty(Request::get('functional_area_id')))<span class="group-count-badge">{{ count((array)Request::get('functional_area_id')) }}</span>@endif</span>
        <i class="fa fa-chevron-up toggle-ico"></i>
    </div>
    <div id="drwDept" class="collapse show">
        <div class="filter-search-box">
            <i class="fa fa-search"></i>
            <input type="text" placeholder="Search department..." class="dept-filter-input" onkeyup="filterCheckboxList(this, 'drw-dept-options-list')">
        </div>
        <div class="apna-filter-options scrollable-options" id="drw-dept-options-list">
            @if(isset($functionalAreaIdsArray) && count($functionalAreaIdsArray))
                @foreach($functionalAreaIdsArray as $key => $functional_area_id)
                    @php
                        $functionalArea = App\FunctionalArea::where('functional_area_id', '=', $functional_area_id)->lang()->active()->first();
                    @endphp
                    @if(null !== $functionalArea)
                        @php
                            $checked = in_array($functionalArea->functional_area_id, (array)Request::get('functional_area_id', [])) ? 'checked' : '';
                            $numJobs = App\Job::countNumJobs('functional_area_id', $functionalArea->functional_area_id);
                        @endphp
                        <label class="apna-checkbox-label filter-item-row">
                            <input type="checkbox" name="functional_area_id[]" value="{{ $functionalArea->functional_area_id }}" {{ $checked }}>
                            <span class="custom-checkbox"></span>
                            <span class="opt-text">{{ $functionalArea->functional_area }}</span>
                            @if($numJobs > 0)<span class="opt-count">{{ $numJobs }}</span>@endif
                        </label>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div>

{{-- 6. Experience Level --}}
<div class="apna-filter-group">
    <div class="apna-filter-title" data-toggle="collapse" data-target="#drwExp" aria-expanded="true">
        <span>Experience @if(!empty(Request::get('job_experience_id')))<span class="group-count-badge">{{ count((array)Request::get('job_experience_id')) }}</span>@endif</span>
        <i class="fa fa-chevron-up toggle-ico"></i>
    </div>
    <div id="drwExp" class="collapse show">
        <div class="apna-filter-options">
            @if(isset($jobExperienceIdsArray) && count($jobExperienceIdsArray))
                @foreach($jobExperienceIdsArray as $key => $job_experience_id)
                    @php
                        $jobExperience = App\JobExperience::where('job_experience_id', '=', $job_experience_id)->lang()->active()->first();
                    @endphp
                    @if(null !== $jobExperience)
                        @php
                            $checked = in_array($jobExperience->job_experience_id, (array)Request::get('job_experience_id', [])) ? 'checked' : '';
                            $numJobs = App\Job::countNumJobs('job_experience_id', $jobExperience->job_experience_id);
                        @endphp
                        <label class="apna-checkbox-label">
                            <input type="checkbox" name="job_experience_id[]" value="{{ $jobExperience->job_experience_id }}" {{ $checked }}>
                            <span class="custom-checkbox"></span>
                            <span class="opt-text">{{ $jobExperience->job_experience }}</span>
                            @if($numJobs > 0)<span class="opt-count">{{ $numJobs }}</span>@endif
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
    <div class="apna-filter-title" data-toggle="collapse" data-target="#drwShift" aria-expanded="false">
        <span>Work shift @if(!empty(Request::get('job_shift_id')))<span class="group-count-badge">{{ count((array)Request::get('job_shift_id')) }}</span>@endif</span>
        <i class="fa fa-chevron-down toggle-ico"></i>
    </div>
    <div id="drwShift" class="collapse">
        <div class="apna-filter-options">
            @foreach($jobShiftIdsArray as $key => $job_shift_id)
                @php
                    $jobShift = App\JobShift::where('job_shift_id', '=', $job_shift_id)->lang()->active()->first();
                @endphp
                @if(null !== $jobShift)
                    @php
                        $checked = in_array($jobShift->job_shift_id, (array)Request::get('job_shift_id', [])) ? 'checked' : '';
                        $numJobs = App\Job::countNumJobs('job_shift_id', $jobShift->job_shift_id);
                    @endphp
                    <label class="apna-checkbox-label">
                        <input type="checkbox" name="job_shift_id[]" value="{{ $jobShift->job_shift_id }}" {{ $checked }}>
                        <span class="custom-checkbox"></span>
                        <span class="opt-text">{{ $jobShift->job_shift }}</span>
                        @if($numJobs > 0)<span class="opt-count">{{ $numJobs }}</span>@endif
                    </label>
                @endif
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- 8. Sort By --}}
<div class="apna-filter-group" style="border-bottom:none;">
    <div class="apna-filter-title" data-toggle="collapse" data-target="#drwSortBy" aria-expanded="true">
        <span>Sort by</span>
        <i class="fa fa-chevron-up toggle-ico"></i>
    </div>
    <div id="drwSortBy" class="collapse show">
        <div class="apna-filter-options">
            <label class="apna-radio-label">
                <input type="radio" name="order_by" value="id" {{ (empty(Request::get('order_by')) || Request::get('order_by') == 'id') ? 'checked' : '' }}>
                <span class="custom-radio"></span>
                <span class="opt-text">Recommended & Relevant</span>
            </label>
            <label class="apna-radio-label">
                <input type="radio" name="order_by" value="salary" {{ Request::get('order_by') == 'salary' ? 'checked' : '' }}>
                <span class="custom-radio"></span>
                <span class="opt-text">Salary: High to Low</span>
            </label>
            <label class="apna-radio-label">
                <input type="radio" name="order_by" value="new" {{ Request::get('order_by') == 'new' ? 'checked' : '' }}>
                <span class="custom-radio"></span>
                <span class="opt-text">Fresh: New to Old</span>
            </label>
        </div>
    </div>
</div>
