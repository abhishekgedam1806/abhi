@if(isset($job))
{!! Form::model($job, array('method' => 'put', 'route' => array('update.front.job', $job->id), 'class' => 'form')) !!}
{!! Form::hidden('id', $job->id) !!}
@else
{!! Form::open(array('method' => 'post', 'route' => array('store.front.job'), 'class' => 'form')) !!}
@endif

<!-- Card 1: Core Job Details & Description -->
<div class="post-job-card">
    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; border-bottom: 1px solid #F1F5F9; padding-bottom: 14px;">
        <div style="width: 38px; height: 38px; border-radius: 10px; background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
            <i class="fa fa-briefcase"></i>
        </div>
        <div>
            <h3 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.2px;">{{__('Core Job Details & Overview')}}</h3>
            <span style="font-size: 12.5px; color: #64748B;">{{__('Provide key job information to attract top applicants')}}</span>
        </div>
    </div>

    <div class="row">  
        <div class="col-md-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'title') !!}">
                <label for="title">{{__('Job Title')}} <span style="color: #EF4444;">*</span></label>
                {!! Form::text('title', null, array('class'=>'form-control', 'id'=>'title', 'placeholder'=>__('e.g. Senior Software Engineer, Operations Manager...'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'title') !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'functional_area_id') !!}" id="functional_area_id_div">
                <label for="functional_area_id">{{__('Functional Area / Category')}} <span style="color: #EF4444;">*</span></label>
                {!! Form::select('functional_area_id', ['' => __('Select Functional Area')]+$functionalAreas, null, array('class'=>'form-control', 'id'=>'functional_area_id')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'functional_area_id') !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'num_of_positions') !!}" id="num_of_positions_div">
                <label for="num_of_positions">{{__('Number of Openings')}} <span style="color: #EF4444;">*</span></label>
                {!! Form::select('num_of_positions', ['' => __('Select Number of Positions')]+MiscHelper::getNumPositions(), null, array('class'=>'form-control', 'id'=>'num_of_positions')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'num_of_positions') !!}
            </div>
        </div>

        <div class="col-md-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'description') !!}">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
                    <label for="description" style="margin: 0; font-weight: 700; font-size: 13.5px; color: #1E293B;">{{__('Job Description & Responsibilities')}} <span style="color: #EF4444;">*</span></label>
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <button type="button" id="btn_ai_proofread_job" onclick="proofreadJobWithAI();" title="Find spelling & grammar mistakes while preserving 100% of your original text and structure" style="display: inline-flex; align-items: center; gap: 6px; background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; font-size: 12px; font-weight: 700; padding: 6px 14px; border-radius: 8px; cursor: pointer; transition: all 0.2s ease;">
                            <i class="fa fa-check-circle" style="color: #03855c;"></i>
                            <span>{{__('🔍 Proofread & Fix (Grammarly Mode)')}}</span>
                        </button>
                        <button type="button" id="btn_ai_optimize_job" onclick="optimizeJobWithAI();" title="Format raw notes into structured role sections" style="display: inline-flex; align-items: center; gap: 6px; background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; font-size: 12px; font-weight: 700; padding: 6px 14px; border-radius: 8px; cursor: pointer; transition: all 0.2s ease;">
                            <i class="fa fa-magic text-primary"></i>
                            <span>{{__('✨ Auto-Structure')}}</span>
                        </button>
                    </div>
                </div>
                
                {{-- Grammarly Interactive Review Panel --}}
                <div id="ai_grammarly_panel" style="display: none; margin-bottom: 14px; background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 12px; padding: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);"></div>
                <div id="ai_job_feedback" style="display: none; margin-bottom: 10px;"></div>

                {!! Form::textarea('description', null, array('class'=>'form-control', 'id'=>'description', 'placeholder'=>__('Detailed role description, expectations, and day-to-day duties...'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'description') !!}
            </div>
        </div>
        
        <div class="col-md-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'benefits') !!}">
                <label for="benefits">{{__('Perks & Benefits Offered')}}</label>
                {!! Form::textarea('benefits', null, array('class'=>'form-control', 'id'=>'benefits', 'rows'=>'4', 'placeholder'=>__('e.g. Health Insurance, Performance Bonus, Flexible Hours, Paid Leave, Free Lunch...'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'benefits') !!}
            </div>
        </div>
        
        <div class="col-md-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'skills') !!}">
                <label for="skills">{{__('Required Skills & Competencies')}} <span style="color: #EF4444;">*</span></label>
                @php
                    $skills = old('skills', $jobSkillIds);
                @endphp
                {!! Form::select('skills[]', $jobSkills, $skills, array('class'=>'form-control select2-multiple', 'id'=>'skills', 'multiple'=>'multiple')) !!}
                <span style="font-size: 11.5px; color: #94A3B8; display: block; margin-top: 4px;">{{__('Skills automatically filter based on the chosen Functional Area above.')}}</span>
                {!! APFrmErrHelp::showErrors($errors, 'skills') !!}
            </div>
        </div>
    </div>
</div>

<!-- Card 2: Compensation & Salary -->
<div class="post-job-card">
    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; border-bottom: 1px solid #F1F5F9; padding-bottom: 14px;">
        <div style="width: 38px; height: 38px; border-radius: 10px; background: #ECFDF5; color: #03855c; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
            <i class="fa fa-money"></i>
        </div>
        <div>
            <h3 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.2px;">{{__('Salary & Compensation')}}</h3>
            <span style="font-size: 12.5px; color: #64748B;">{{__('Transparent pay ranges receive up to 40% more qualified applicants')}}</span>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'salary_from') !!}" id="salary_from_div">
                <label for="salary_from">{{__('Minimum Salary (From)')}}</label>
                {!! Form::number('salary_from', null, array('class'=>'form-control', 'id'=>'salary_from', 'placeholder'=>__('e.g. 25000'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'salary_from') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'salary_to') !!}" id="salary_to_div">
                <label for="salary_to">{{__('Maximum Salary (To)')}}</label>
                {!! Form::number('salary_to', null, array('class'=>'form-control', 'id'=>'salary_to', 'placeholder'=>__('e.g. 45000'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'salary_to') !!}
            </div>
        </div>

        <div class="col-md-4">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'salary_currency') !!}" id="salary_currency_div">
                <label for="salary_currency">{{__('Salary Currency')}}</label>
                @php
                $salary_currency = Request::get('salary_currency', (isset($job))? $job->salary_currency:$siteSetting->default_currency_code);
                @endphp
                {!! Form::select('salary_currency', ['' => __('Select Salary Currency')]+$currencies, $salary_currency, array('class'=>'form-control', 'id'=>'salary_currency')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'salary_currency') !!}
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'salary_period_id') !!}" id="salary_period_id_div">
                <label for="salary_period_id">{{__('Payment Frequency')}}</label>
                {!! Form::select('salary_period_id', ['' => __('Select Frequency')]+$salaryPeriods, null, array('class'=>'form-control', 'id'=>'salary_period_id')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'salary_period_id') !!}
            </div>
        </div>

        <div class="col-md-4">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'hide_salary') !!}">
                <label>{{__('Hide Salary on Public Listing?')}}</label>
                @php
                    $hide_salary_1 = '';
                    $hide_salary_2 = 'checked="checked"';
                    if (old('hide_salary', ((isset($job)) ? $job->hide_salary : 0)) == 1) {
                        $hide_salary_1 = 'checked="checked"';
                        $hide_salary_2 = '';
                    }
                @endphp
                <div style="display: flex; gap: 14px; align-items: center; height: 44px;">
                    <label style="display: inline-flex; align-items: center; gap: 6px; font-weight: 600; color: #334155; margin: 0; cursor: pointer;">
                        <input id="hide_salary_no" name="hide_salary" type="radio" value="0" {{$hide_salary_2}} style="width: 17px; height: 17px; accent-color: #2563EB;">
                        {{__('No (Show Salary)')}}
                    </label>
                    <label style="display: inline-flex; align-items: center; gap: 6px; font-weight: 600; color: #334155; margin: 0; cursor: pointer;">
                        <input id="hide_salary_yes" name="hide_salary" type="radio" value="1" {{$hide_salary_1}} style="width: 17px; height: 17px; accent-color: #2563EB;">
                        {{__('Yes (Hide Salary)')}}
                    </label>
                </div>
                {!! APFrmErrHelp::showErrors($errors, 'hide_salary') !!}
            </div>
        </div>
    </div>
</div>

<!-- Card 3: Requirements & Role Parameters -->
<div class="post-job-card">
    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; border-bottom: 1px solid #F1F5F9; padding-bottom: 14px;">
        <div style="width: 38px; height: 38px; border-radius: 10px; background: #FEF3C7; color: #D97706; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
            <i class="fa fa-sliders"></i>
        </div>
        <div>
            <h3 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.2px;">{{__('Candidate Requirements & Specifications')}}</h3>
            <span style="font-size: 12.5px; color: #64748B;">{{__('Define criteria to screen and match qualified talent')}}</span>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'job_type_id') !!}" id="job_type_id_div">
                <label for="job_type_id">{{__('Job Type')}} <span style="color: #EF4444;">*</span></label>
                {!! Form::select('job_type_id', ['' => __('Select Job Type')]+$jobTypes, null, array('class'=>'form-control', 'id'=>'job_type_id')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'job_type_id') !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'job_shift_id') !!}" id="job_shift_id_div">
                <label for="job_shift_id">{{__('Shift Timing')}}</label>
                {!! Form::select('job_shift_id', ['' => __('Select Shift Timing')]+$jobShifts, null, array('class'=>'form-control', 'id'=>'job_shift_id')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'job_shift_id') !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'career_level_id') !!}" id="career_level_id_div">
                <label for="career_level_id">{{__('Target Career Level')}}</label>
                {!! Form::select('career_level_id', ['' => __('Select Career Level')]+$careerLevels, null, array('class'=>'form-control', 'id'=>'career_level_id')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'career_level_id') !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'job_experience_id') !!}" id="job_experience_id_div">
                <label for="job_experience_id">{{__('Required Work Experience')}} <span style="color: #EF4444;">*</span></label>
                {!! Form::select('job_experience_id', ['' => __('Select Experience Level')]+$jobExperiences, null, array('class'=>'form-control', 'id'=>'job_experience_id')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'job_experience_id') !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'degree_level_id') !!}" id="degree_level_id_div">
                <label for="degree_level_id">{{__('Required Education Level')}}</label>
                {!! Form::select('degree_level_id', ['' =>__('Select Education / Degree')]+$degreeLevels, null, array('class'=>'form-control', 'id'=>'degree_level_id')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'degree_level_id') !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'gender_id') !!}" id="gender_id_div">
                <label for="gender_id">{{__('Gender Preference')}}</label>
                {!! Form::select('gender_id', ['' => __('No Preference (Open to all)')]+$genders, null, array('class'=>'form-control', 'id'=>'gender_id')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'gender_id') !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'expiry_date') !!}">
                <label for="expiry_date">{{__('Application Deadline / Expiry Date')}} <span style="color: #EF4444;">*</span></label>
                {!! Form::text('expiry_date', null, array('class'=>'form-control datepicker', 'id'=>'expiry_date', 'placeholder'=>__('YYYY-MM-DD'), 'autocomplete'=>'off')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'expiry_date') !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'is_freelance') !!}">
                <label>{{__('Is Freelance / Remote Role?')}}</label>
                @php
                    $is_freelance_1 = '';
                    $is_freelance_2 = 'checked="checked"';
                    if (old('is_freelance', ((isset($job)) ? $job->is_freelance : 0)) == 1) {
                        $is_freelance_1 = 'checked="checked"';
                        $is_freelance_2 = '';
                    }
                @endphp
                <div style="display: flex; gap: 14px; align-items: center; height: 44px;">
                    <label style="display: inline-flex; align-items: center; gap: 6px; font-weight: 600; color: #334155; margin: 0; cursor: pointer;">
                        <input id="is_freelance_no" name="is_freelance" type="radio" value="0" {{$is_freelance_2}} style="width: 17px; height: 17px; accent-color: #2563EB;">
                        {{__('No (On-Site / Office)')}}
                    </label>
                    <label style="display: inline-flex; align-items: center; gap: 6px; font-weight: 600; color: #334155; margin: 0; cursor: pointer;">
                        <input id="is_freelance_yes" name="is_freelance" type="radio" value="1" {{$is_freelance_1}} style="width: 17px; height: 17px; accent-color: #2563EB;">
                        {{__('Yes (Freelance / Remote)')}}
                    </label>
                </div>
                {!! APFrmErrHelp::showErrors($errors, 'is_freelance') !!}
            </div>
        </div>
    </div>
</div>

<!-- Card 4: Location & Placement -->
<div class="post-job-card">
    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; border-bottom: 1px solid #F1F5F9; padding-bottom: 14px;">
        <div style="width: 38px; height: 38px; border-radius: 10px; background: #FEE2E2; color: #DC2626; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
            <i class="fa fa-map-marker"></i>
        </div>
        <div>
            <h3 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.2px;">{{__('Job Location & Geography')}}</h3>
            <span style="font-size: 12.5px; color: #64748B;">{{__('Select where candidates will be working or reporting')}}</span>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'country_id') !!}" id="country_id_div">
                <label for="country_id">{{__('Country')}} <span style="color: #EF4444;">*</span></label>
                {!! Form::select('country_id', ['' => __('Select Country')]+$countries, old('country_id', (isset($job))? $job->country_id:$siteSetting->default_country_id), array('class'=>'form-control', 'id'=>'country_id')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'country_id') !!}
            </div>
        </div>

        <div class="col-md-3">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'state_id') !!}" id="state_id_div">
                <label for="state_id">{{__('State / Province')}} <span style="color: #EF4444;">*</span></label>
                <span id="default_state_dd">
                    {!! Form::select('state_id', ['' => __('Select State')], null, array('class'=>'form-control', 'id'=>'state_id')) !!}
                </span>
                {!! APFrmErrHelp::showErrors($errors, 'state_id') !!}
            </div>
        </div>

        <div class="col-md-3">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'city_id') !!}" id="city_id_div">
                <label for="city_id">{{__('City')}} <span style="color: #EF4444;">*</span></label>
                <span id="default_city_dd">
                    {!! Form::select('city_id', ['' => __('Select City')], null, array('class'=>'form-control', 'id'=>'city_id')) !!}
                </span>
                {!! APFrmErrHelp::showErrors($errors, 'city_id') !!}
            </div>
        </div>

        <div class="col-md-3">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'area_name') !!}" id="area_div">
                <label for="area_name">{{__('Area / Locality')}} <span style="font-size: 11px; font-weight: 500; color: #64748B;">(5km radius)</span></label>
                <input type="text" name="area_name" id="area_name" class="form-control" list="areas_datalist" placeholder="{{__('e.g. Hinjewadi, Dharampeth')}}" value="{{ old('area_name', (isset($job) ? $job->area_name : '')) }}">
                <datalist id="areas_datalist"></datalist>
                {!! APFrmErrHelp::showErrors($errors, 'area_name') !!}
            </div>
        </div>
    </div>
</div>

<!-- Submit Action Button -->
<div style="margin-top: 10px; margin-bottom: 30px; display: flex; justify-content: flex-end;">
    <button type="submit" style="background: #2563EB; color: #FFFFFF; font-size: 15px; font-weight: 800; padding: 14px 36px; border-radius: 12px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 10px; box-shadow: 0 4px 16px rgba(37,99,235,0.3); transition: all 0.2s ease;">
        <i class="fa fa-paper-plane" style="font-size: 15px;"></i>
        {{ isset($job) ? __('Save Changes & Update Job') : __('Publish New Job') }}
    </button>
</div>

<input type="file" name="image" id="image" style="display:none;" accept="image/*"/>
{!! Form::close() !!}

@push('styles')
<link href="{{ asset('admin_assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin_assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<style type="text/css">
    .datepicker>div {
        display: block;
    }
</style>
@endpush
@push('scripts')
<script src="{{ asset('admin_assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
@include('includes.tinyMCEFront')
<script type="text/javascript">
    $(document).ready(function () {
        if ($('.select2-multiple').length) {
            $('.select2-multiple').select2({
                placeholder: "{{__('Select Required Skills')}}",
                allowClear: true
            });
        }
        if ($(".datepicker").length) {
            $(".datepicker").datepicker({
                autoclose: true,
                format: 'yyyy-m-d'
            });
        }
        $('#country_id').on('change', function (e) {
            e.preventDefault();
            filterLangStates(0);
        });
        $(document).on('change', '#state_id', function (e) {
            e.preventDefault();
            filterLangCities(0);
        });
        filterLangStates(<?php echo old('state_id', (isset($job)) ? $job->state_id : 0); ?>);

        $('#functional_area_id').on('change', function (e) {
            filterDepartmentSkills();
        });
        if ($('#functional_area_id').val()) {
            filterDepartmentSkills();
        }
    });

    function filterDepartmentSkills() {
        var functional_area_id = $('#functional_area_id').val();
        var selectedSkills = $('#skills').val() || <?php echo json_encode(old('skills', (isset($jobSkillIds) ? $jobSkillIds : []))); ?>;
        if (functional_area_id != '') {
            $.post("{{ route('filter.skills.dropdown') }}", {
                functional_area_id: functional_area_id,
                selected_skills: selectedSkills,
                _token: '{{ csrf_token() }}'
            }).done(function(res) {
                if (res.options) {
                    $('#skills').html(res.options).trigger('change');
                }
            });
        }
    }

    function filterLangStates(state_id)
    {
        var country_id = $('#country_id').val();
        if (country_id != '') {
            $.post("{{ route('filter.lang.states.dropdown') }}", {country_id: country_id, state_id: state_id, _method: 'POST', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        $('#default_state_dd').html(response);
                        filterLangCities(<?php echo old('city_id', (isset($job)) ? $job->city_id : 0); ?>);
                    });
        }
    }
    function filterLangCities(city_id)
    {
        var state_id = $('#state_id').val();
        if (state_id != '') {
            $.post("{{ route('filter.lang.cities.dropdown') }}", {state_id: state_id, city_id: city_id, _method: 'POST', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        $('#default_city_dd').html(response);
                        var activeCityId = $('#city_id').val() || city_id;
                        if (activeCityId) loadAreas(activeCityId);
                    });
        }
    }

    function loadAreas(city_id) {
        if (!city_id) {
            $('#areas_datalist').empty();
            return;
        }
        $.ajax({
            url: "{{ route('get.areas.by.city') }}",
            type: "GET",
            data: { city_id: city_id },
            success: function(data) {
                var datalist = $('#areas_datalist');
                datalist.empty();
                if (data && data.length > 0) {
                    $.each(data, function(index, item) {
                        datalist.append('<option value="' + item.area_name + '">');
                    });
                }
            }
        });
    }

    $(document).on('change', '#city_id', function() {
        loadAreas($(this).val());
    });

    var grammarlyCorrectedText = '';

    function proofreadJobWithAI() {
        var btn = $('#btn_ai_proofread_job');
        var originalHtml = btn.html();
        var desc = (typeof tinymce !== 'undefined' && tinymce.get('description')) ? tinymce.get('description').getContent({format: 'text'}) : $('#description').val();
        var category = $('#functional_area_id option:selected').text();

        if (!desc || desc.trim().length < 10) {
            alert('Please type or paste your job description in the box first so AI can proofread it.');
            return;
        }

        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Proofreading...');
        $('#ai_grammarly_panel').slideUp();
        $('#ai_job_feedback').slideUp();

        $.ajax({
            url: "{{ route('company.ai.proofread_job') }}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                "description": desc,
                "category": category
            },
            dataType: "json",
            success: function (res) {
                btn.prop('disabled', false).html(originalHtml);
                if (res.success) {
                    grammarlyCorrectedText = res.corrected_full_description;

                    var html = '<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px solid #E2E8F0; padding-bottom: 8px;">';
                    html += '<h5 style="margin: 0; font-size: 14px; font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 6px;"><i class="fa fa-spell-check text-success"></i> Proofreading & Keyword Report (' + res.original_word_count + ' words preserved)</h5>';
                    html += '<a href="javascript:;" onclick="$(\'#ai_grammarly_panel\').slideUp();" style="color: #64748B; font-weight: bold; text-decoration: none;">&times; Close</a>';
                    html += '</div>';

                    // Corrections List
                    if (res.corrections && res.corrections.length > 0) {
                        html += '<div style="margin-bottom: 12px;"><strong style="font-size: 12.5px; color: #334155;">Detected Corrections (' + res.corrections.length + '):</strong>';
                        html += '<div style="display: grid; gap: 8px; margin-top: 6px;">';
                        $.each(res.corrections, function(i, c) {
                            html += '<div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-left: 3.5px solid #F59E0B; padding: 8px 12px; border-radius: 6px; font-size: 12.5px;">';
                            html += '<span style="color: #DC2626; text-decoration: line-through; margin-right: 8px;">' + c.original + '</span>';
                            html += '<span style="color: #03855c; font-weight: 700; margin-right: 8px;">→ ' + c.corrected + '</span>';
                            html += '<span style="color: #64748B; font-size: 11.5px;">(' + c.reason + ')</span>';
                            html += '</div>';
                        });
                        html += '</div></div>';
                    } else {
                        html += '<div style="background: #ECFDF5; color: #065F46; padding: 10px 12px; border-radius: 8px; font-size: 12.5px; margin-bottom: 12px;"><i class="fa fa-check-circle text-success"></i> <strong>Zero Grammar or Spelling Errors!</strong> Your wording is clean and correct.</div>';
                    }

                    // Missing Keyword Suggestions
                    if (res.missing_keywords && res.missing_keywords.length > 0) {
                        html += '<div style="margin-bottom: 14px;"><strong style="font-size: 12.5px; color: #334155;">💡 Recommended Keywords to Consider Adding:</strong>';
                        html += '<div style="display: flex; gap: 6px; flex-wrap: wrap; margin-top: 6px;">';
                        $.each(res.missing_keywords, function(i, kw) {
                            html += '<span style="background: #EFF6FF; border: 1px solid #BFDBFE; color: #1D4ED8; font-size: 11.5px; font-weight: 600; padding: 4px 10px; border-radius: 16px;">' + kw + '</span>';
                        });
                        html += '</div></div>';
                    }

                    // Action buttons (Apply or Keep)
                    html += '<div style="display: flex; gap: 10px; align-items: center; justify-content: flex-end; margin-top: 10px; border-top: 1px solid #E2E8F0; padding-top: 10px;">';
                    html += '<button type="button" onclick="$(\'#ai_grammarly_panel\').slideUp();" style="background: #F1F5F9; border: 1px solid #CBD5E1; color: #475569; font-size: 12.5px; font-weight: 700; padding: 6px 16px; border-radius: 8px; cursor: pointer;">Keep Original Text</button>';
                    if (res.corrections && res.corrections.length > 0) {
                        html += '<button type="button" onclick="applyGrammarlyCorrections();" style="background: #03855c; border: none; color: #FFFFFF; font-size: 12.5px; font-weight: 700; padding: 6px 18px; border-radius: 8px; cursor: pointer; box-shadow: 0 2px 6px rgba(3,133,92,0.2);"><i class="fa fa-check"></i> Apply Corrections to Editor</button>';
                    }
                    html += '</div>';

                    $('#ai_grammarly_panel').html(html).slideDown();
                } else {
                    alert(res.message || 'Could not proofread description.');
                }
            },
            error: function () {
                btn.prop('disabled', false).html(originalHtml);
                alert('AI Service is currently busy. Please try again.');
            }
        });
    }

    function applyGrammarlyCorrections() {
        if (grammarlyCorrectedText) {
            if (typeof tinymce !== 'undefined' && tinymce.get('description')) {
                tinymce.get('description').setContent(grammarlyCorrectedText);
            } else {
                $('#description').val(grammarlyCorrectedText);
            }
            $('#ai_grammarly_panel').slideUp();
            $('#ai_job_feedback').html(
                '<div style="background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; padding: 8px 14px; border-radius: 8px; font-size: 12.5px;">' +
                '<i class="fa fa-check-circle" style="color: #03855c; margin-right: 6px;"></i> <strong>Corrections Applied!</strong> Your original length and structure are preserved with spelling & grammar fixed.' +
                '</div>'
            ).slideDown().delay(5000).fadeOut();
        }
    }

    function optimizeJobWithAI() {
        var btn = $('#btn_ai_optimize_job');
        var originalHtml = btn.html();
        var title = $('#title').val();
        var desc = (typeof tinymce !== 'undefined' && tinymce.get('description')) ? tinymce.get('description').getContent() : $('#description').val();
        var jobType = $('#job_type_id option:selected').text();
        var salary = $('#salary_from').val() ? ($('#salary_from').val() + ' - ' + $('#salary_to').val()) : '';

        if (!title && !desc) {
            alert('Please enter a Job Title or brief description first.');
            $('#title').focus();
            return;
        }

        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Formatting...');
        $('#ai_grammarly_panel').slideUp();
        $('#ai_job_feedback').slideUp();

        $.ajax({
            url: "{{ route('company.ai.optimize_job') }}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                "title": title,
                "description": desc,
                "job_type": jobType,
                "salary": salary
            },
            dataType: "json",
            success: function (res) {
                btn.prop('disabled', false).html(originalHtml);
                if (res.success && res.optimized_description) {
                    if (typeof tinymce !== 'undefined' && tinymce.get('description')) {
                        tinymce.get('description').setContent(res.optimized_description);
                    } else {
                        $('#description').val(res.optimized_description);
                    }

                    if (res.matched_skill_ids && res.matched_skill_ids.length > 0) {
                        var currentSkills = $('#skills').val() || [];
                        var merged = Array.from(new Set(currentSkills.concat(res.matched_skill_ids)));
                        $('#skills').val(merged).trigger('change');
                    }

                    var suggestionsHtml = '';
                    if (res.suggestions && res.suggestions.length > 0) {
                        suggestionsHtml = '<div style="margin-top: 6px; font-size: 11.5px; color: #047857;">💡 <strong>AI Tip:</strong> ' + res.suggestions.join(' • ') + '</div>';
                    }

                    $('#ai_job_feedback').html(
                        '<div style="background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; padding: 10px 14px; border-radius: 8px; font-size: 12.5px;">' +
                        '<div style="display: flex; align-items: center; justify-content: space-between;">' +
                        '<span><i class="fa fa-check-circle" style="color: #03855c; margin-right: 6px;"></i> <strong>Job Description Formatted by AI!</strong> Review the updated text in the editor and click "Publish New Job" to save.</span>' +
                        '<a href="javascript:;" onclick="$(\'#ai_job_feedback\').slideUp();" style="color: #065F46; font-weight: bold; text-decoration: none;">&times;</a>' +
                        '</div>' +
                        suggestionsHtml +
                        '</div>'
                    ).slideDown();
                } else {
                    alert(res.message || 'Could not format description.');
                }
            },
            error: function () {
                btn.prop('disabled', false).html(originalHtml);
                alert('AI Service is currently busy. Please try again.');
            }
        });
    }
</script> 
@endpush