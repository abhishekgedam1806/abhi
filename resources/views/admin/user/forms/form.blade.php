<style>
/* Modern Admin User Form Styling */
.adm-user-edit-wrapper {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #1E293B;
}
.adm-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 22px 24px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    transition: box-shadow 0.2s ease;
}
.adm-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.adm-card-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 18px;
    padding-bottom: 14px;
    border-bottom: 1px solid #F1F5F9;
}
.adm-card-header i {
    font-size: 18px;
    color: #2563EB;
}
.adm-card-title {
    font-size: 15.5px;
    font-weight: 700;
    color: #0F172A;
    margin: 0;
}
.adm-label {
    font-size: 13px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 6px;
    display: block;
}
.adm-input, .adm-card .form-control {
    height: 42px !important;
    border: 1.5px solid #CBD5E1 !important;
    border-radius: 8px !important;
    padding: 0 14px !important;
    font-size: 13.5px !important;
    color: #0F172A !important;
    background: #FFFFFF !important;
    box-shadow: none !important;
    transition: all 0.2s ease !important;
}
.adm-card textarea.form-control {
    height: auto !important;
    min-height: 80px !important;
    padding: 10px 14px !important;
}
.adm-input:focus, .adm-card .form-control:focus {
    border-color: #2563EB !important;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.12) !important;
}
.adm-input-icon-wrap {
    position: relative;
}
.adm-input-icon-wrap i {
    position: absolute;
    left: 14px;
    top: 13px;
    color: #94A3B8;
    font-size: 15px;
}
.adm-input-icon-wrap .form-control {
    padding-left: 38px !important;
}
.adm-hint {
    font-size: 12px;
    color: #64748B;
    margin-top: 4px;
    display: block;
}
.adm-radio-box {
    display: flex;
    gap: 10px;
}
.adm-radio-option {
    flex: 1;
    border: 1.5px solid #E2E8F0;
    border-radius: 8px;
    padding: 8px 12px;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    background: #F8FAFC;
    transition: all 0.2s ease;
    margin: 0;
}
.adm-radio-option:hover {
    border-color: #CBD5E1;
    background: #FFFFFF;
}
.adm-radio-option input[type="radio"] {
    margin: 0;
    cursor: pointer;
}
.adm-radio-option span {
    font-size: 12.5px;
    font-weight: 600;
    color: #334155;
}
.adm-btn-save {
    background: #2563EB !important;
    color: #FFFFFF !important;
    font-size: 14.5px !important;
    font-weight: 700 !important;
    padding: 12px 28px !important;
    border-radius: 8px !important;
    border: none !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 8px !important;
    box-shadow: 0 4px 12px rgba(37,99,235,0.25) !important;
    cursor: pointer !important;
    transition: transform 0.15s ease, box-shadow 0.15s ease !important;
}
.adm-btn-save:hover {
    transform: translateY(-1px) !important;
    box-shadow: 0 6px 16px rgba(37,99,235,0.35) !important;
}
.adm-photo-preview-box {
    border: 2px dashed #CBD5E1;
    border-radius: 10px;
    padding: 16px;
    text-align: center;
    background: #F8FAFC;
    margin-bottom: 14px;
}
.adm-photo-preview-box img {
    max-height: 160px;
    max-width: 100%;
    object-fit: cover;
    border-radius: 8px;
}
</style>

{!! APFrmErrHelp::showErrorsNotice($errors) !!}
@include('flash::message')

@if(isset($user))
{!! Form::model($user, array('method' => 'put', 'route' => array('update.user', $user->id), 'class' => 'form', 'files'=>true)) !!}
{!! Form::hidden('id', $user->id) !!}
@else
{!! Form::open(array('method' => 'post', 'route' => 'store.user', 'class' => 'form', 'files'=>true)) !!}
@endif

<div class="form-body adm-user-edit-wrapper">    
    <input type="hidden" name="front_or_admin" value="admin" />

    {{-- Main 2-Column Form Layout --}}
    <div class="row">
        {{-- LEFT COLUMN (8 Cols) --}}
        <div class="col-md-8">
            {{-- 1. Personal Identity & Credentials --}}
            <div class="adm-card">
                <div class="adm-card-header">
                    <i class="fa fa-id-card-o"></i>
                    <h3 class="adm-card-title">Personal Information & Account Credentials</h3>
                </div>
                {{-- Names in 3 columns --}}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'first_name') !!}">
                            {!! Form::label('first_name', 'First Name', ['class' => 'adm-label']) !!}                    
                            {!! Form::text('first_name', null, array('class'=>'form-control adm-input', 'id'=>'first_name', 'placeholder'=>'e.g. John')) !!}
                            {!! APFrmErrHelp::showErrors($errors, 'first_name') !!}                                       
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'middle_name') !!}">
                            {!! Form::label('middle_name', 'Middle Name', ['class' => 'adm-label']) !!}                    
                            {!! Form::text('middle_name', null, array('class'=>'form-control adm-input', 'id'=>'middle_name', 'placeholder'=>'Optional')) !!}
                            {!! APFrmErrHelp::showErrors($errors, 'middle_name') !!}                                       
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'last_name') !!}">
                            {!! Form::label('last_name', 'Last Name', ['class' => 'adm-label']) !!}                    
                            {!! Form::text('last_name', null, array('class'=>'form-control adm-input', 'id'=>'last_name', 'placeholder'=>'e.g. Doe')) !!}
                            {!! APFrmErrHelp::showErrors($errors, 'last_name') !!}                                       
                        </div>
                    </div>
                </div>

                {{-- Email & Password --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'email') !!}">
                            {!! Form::label('email', 'Email Address', ['class' => 'adm-label']) !!}                    
                            <div class="adm-input-icon-wrap">
                                <i class="fa fa-envelope-o"></i>
                                {!! Form::text('email', null, array('class'=>'form-control adm-input', 'id'=>'email', 'placeholder'=>'candidate@example.com')) !!}
                            </div>
                            {!! APFrmErrHelp::showErrors($errors, 'email') !!}                                       
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'password') !!}">
                            {!! Form::label('password', 'Account Password', ['class' => 'adm-label']) !!}                    
                            <div class="adm-input-icon-wrap">
                                <i class="fa fa-lock"></i>
                                {!! Form::password('password', array('class'=>'form-control adm-input', 'id'=>'password', 'placeholder'=>'••••••••')) !!}
                            </div>
                            <span class="adm-hint">Leave blank to keep existing password</span>
                            {!! APFrmErrHelp::showErrors($errors, 'password') !!}                                       
                        </div>
                    </div>
                </div>

                {{-- Father Name & DOB --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'father_name') !!}">
                            {!! Form::label('father_name', 'Father Name', ['class' => 'adm-label']) !!}                    
                            {!! Form::text('father_name', null, array('class'=>'form-control adm-input', 'id'=>'father_name', 'placeholder'=>'Father Name')) !!}
                            {!! APFrmErrHelp::showErrors($errors, 'father_name') !!}                                       
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'date_of_birth') !!}">
                            {!! Form::label('date_of_birth', 'Date of Birth', ['class' => 'adm-label']) !!}                    
                            <div class="adm-input-icon-wrap">
                                <i class="fa fa-calendar"></i>
                                {!! Form::text('date_of_birth', null, array('class'=>'form-control adm-input datepicker', 'id'=>'date_of_birth', 'placeholder'=>'YYYY-MM-DD', 'autocomplete'=>'off')) !!}
                            </div>
                            {!! APFrmErrHelp::showErrors($errors, 'date_of_birth') !!}                                       
                        </div>
                    </div>
                </div>

                {{-- Demographics Grid --}}
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'gender_id') !!}">
                            {!! Form::label('gender_id', 'Gender', ['class' => 'adm-label']) !!}                    
                            {!! Form::select('gender_id', [''=>'Select Gender']+$genders, null, array('class'=>'form-control adm-input', 'id'=>'gender_id')) !!}
                            {!! APFrmErrHelp::showErrors($errors, 'gender_id') !!}                                       
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'marital_status_id') !!}">
                            {!! Form::label('marital_status_id', 'Marital Status', ['class' => 'adm-label']) !!}                    
                            {!! Form::select('marital_status_id', [''=>'Select Marital Status']+$maritalStatuses, null, array('class'=>'form-control adm-input', 'id'=>'marital_status_id')) !!}
                            {!! APFrmErrHelp::showErrors($errors, 'marital_status_id') !!}                                       
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'nationality_id') !!}">
                            {!! Form::label('nationality_id', 'Nationality', ['class' => 'adm-label']) !!}                    
                            {!! Form::select('nationality_id', [''=>'Select Nationality']+$nationalities, null, array('class'=>'form-control adm-input', 'id'=>'nationality_id')) !!}
                            {!! APFrmErrHelp::showErrors($errors, 'nationality_id') !!}                                       
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'national_id_card_number') !!}">
                            {!! Form::label('national_id_card_number', 'National ID / Aadhaar #', ['class' => 'adm-label']) !!}                    
                            {!! Form::text('national_id_card_number', null, array('class'=>'form-control adm-input', 'id'=>'national_id_card_number', 'placeholder'=>'National ID')) !!}
                            {!! APFrmErrHelp::showErrors($errors, 'national_id_card_number') !!}                                       
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Professional & Career Details --}}
            <div class="adm-card">
                <div class="adm-card-header">
                    <i class="fa fa-briefcase"></i>
                    <h3 class="adm-card-title">Professional Profile & Career Preferences</h3>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'job_experience_id') !!}">
                            {!! Form::label('job_experience_id', 'Total Work Experience', ['class' => 'adm-label']) !!}                    
                            {!! Form::select('job_experience_id', [''=>'Select Experience']+$jobExperiences, null, array('class'=>'form-control adm-input', 'id'=>'job_experience_id')) !!}
                            {!! APFrmErrHelp::showErrors($errors, 'job_experience_id') !!}                                       
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'career_level_id') !!}">
                            {!! Form::label('career_level_id', 'Career Level', ['class' => 'adm-label']) !!}                    
                            {!! Form::select('career_level_id', [''=>'Select Career Level']+$careerLevels, null, array('class'=>'form-control adm-input', 'id'=>'career_level_id')) !!}
                            {!! APFrmErrHelp::showErrors($errors, 'career_level_id') !!}                                       
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'functional_area_id') !!}">
                            {!! Form::label('functional_area_id', 'Functional Area / Job Role', ['class' => 'adm-label']) !!}                    
                            {!! Form::select('functional_area_id', [''=>'Select Functional Area']+$functionalAreas, null, array('class'=>'form-control adm-input', 'id'=>'functional_area_id')) !!}
                            {!! APFrmErrHelp::showErrors($errors, 'functional_area_id') !!}                                       
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'industry_id') !!}">
                            {!! Form::label('industry_id', 'Industry Domain', ['class' => 'adm-label']) !!}                    
                            {!! Form::select('industry_id', [''=>'Select Industry']+$industries, null, array('class'=>'form-control adm-input', 'id'=>'industry_id')) !!}
                            {!! APFrmErrHelp::showErrors($errors, 'industry_id') !!}                                       
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'current_salary') !!}">
                            {!! Form::label('current_salary', 'Current Monthly Salary', ['class' => 'adm-label']) !!}                    
                            {!! Form::text('current_salary', null, array('class'=>'form-control adm-input', 'id'=>'current_salary', 'placeholder'=>'e.g. 15000')) !!}
                            {!! APFrmErrHelp::showErrors($errors, 'current_salary') !!}                                       
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'expected_salary') !!}">
                            {!! Form::label('expected_salary', 'Expected Monthly Salary', ['class' => 'adm-label']) !!}                    
                            {!! Form::text('expected_salary', null, array('class'=>'form-control adm-input', 'id'=>'expected_salary', 'placeholder'=>'e.g. 25000')) !!}
                            {!! APFrmErrHelp::showErrors($errors, 'expected_salary') !!}                                       
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'salary_currency') !!}">
                            {!! Form::label('salary_currency', 'Salary Currency', ['class' => 'adm-label']) !!}                    
                            {!! Form::text('salary_currency', null, array('class'=>'form-control adm-input', 'id'=>'salary_currency', 'placeholder'=>'e.g. INR', 'autocomplete'=>'off')) !!}
                            {!! APFrmErrHelp::showErrors($errors, 'salary_currency') !!}                                       
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Location & Contact --}}
            <div class="adm-card">
                <div class="adm-card-header">
                    <i class="fa fa-map-marker"></i>
                    <h3 class="adm-card-title">Location & Contact Information</h3>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'country_id') !!}">
                            {!! Form::label('country_id', 'Country', ['class' => 'adm-label']) !!}                    
                            {!! Form::select('country_id', [''=>'Select Country']+$countries, old('country_id', (isset($user))? $user->country_id:$siteSetting->default_country_id), array('class'=>'form-control adm-input', 'id'=>'country_id')) !!}
                            {!! APFrmErrHelp::showErrors($errors, 'country_id') !!}                                       
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'state_id') !!}">
                            {!! Form::label('state_id', 'State', ['class' => 'adm-label']) !!}                    
                            <span id="default_state_dd">
                                {!! Form::select('state_id', [''=>'Select State'], null, array('class'=>'form-control adm-input', 'id'=>'state_id')) !!}
                            </span>
                            {!! APFrmErrHelp::showErrors($errors, 'state_id') !!}                                       
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'city_id') !!}">
                            {!! Form::label('city_id', 'City', ['class' => 'adm-label']) !!}                    
                            <span id="default_city_dd">
                                {!! Form::select('city_id', [''=>'Select City'], null, array('class'=>'form-control adm-input', 'id'=>'city_id')) !!}
                            </span>
                            {!! APFrmErrHelp::showErrors($errors, 'city_id') !!}                                       
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'phone') !!}">
                            {!! Form::label('phone', 'Phone Number', ['class' => 'adm-label']) !!}                    
                            <div class="adm-input-icon-wrap">
                                <i class="fa fa-phone"></i>
                                {!! Form::text('phone', null, array('class'=>'form-control adm-input', 'id'=>'phone', 'placeholder'=>'e.g. 0712-254123')) !!}
                            </div>
                            {!! APFrmErrHelp::showErrors($errors, 'phone') !!}                                       
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'mobile_num') !!}">
                            {!! Form::label('mobile_num', 'Mobile Number', ['class' => 'adm-label']) !!}                    
                            <div class="adm-input-icon-wrap">
                                <i class="fa fa-mobile" style="font-size:18px;"></i>
                                {!! Form::text('mobile_num', null, array('class'=>'form-control adm-input', 'id'=>'mobile_num', 'placeholder'=>'e.g. 9876543210')) !!}
                            </div>
                            {!! APFrmErrHelp::showErrors($errors, 'mobile_num') !!}                                       
                        </div>
                    </div>
                </div>
                <div class="form-group {!! APFrmErrHelp::hasError($errors, 'street_address') !!}">
                    {!! Form::label('street_address', 'Complete Street Address', ['class' => 'adm-label']) !!}                    
                    {!! Form::textarea('street_address', null, array('class'=>'form-control', 'id'=>'street_address', 'rows'=>'2', 'placeholder'=>'Enter complete residential address...')) !!}
                    {!! APFrmErrHelp::showErrors($errors, 'street_address') !!}                                       
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN (4 Cols) --}}
        <div class="col-md-4">
            {{-- 4. Candidate Photo --}}
            <div class="adm-card">
                <div class="adm-card-header">
                    <i class="fa fa-user-circle-o"></i>
                    <h3 class="adm-card-title">Profile Photo</h3>
                </div>
                <div class="form-group {!! APFrmErrHelp::hasError($errors, 'image') !!}">
                    <div class="fileinput fileinput-new" data-provides="fileinput" style="width:100%;">
                        <div class="adm-photo-preview-box">
                            @if(isset($user) && !empty($user->image))
                                <img src="{{ asset('user_images/'.$user->image) }}" alt="{{ $user->name }}" id="currentUserPhoto" />
                            @else
                                <div class="fileinput-new thumbnail" style="border:none; background:transparent; margin:0; padding:0;">
                                    <img src="{{ asset('/') }}admin_assets/no-image.png" alt="" />
                                </div>
                            @endif
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 100%; max-height: 160px; border:none; background:transparent;"></div>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <span class="btn default btn-file" style="flex: 1; border: 1.5px solid #CBD5E1; border-radius: 8px; font-weight: 600; padding: 8px 14px; background: #FFFFFF; font-size: 13px;">
                                <span class="fileinput-new"><i class="fa fa-upload"></i> Upload Photo</span>
                                <span class="fileinput-exists"><i class="fa fa-refresh"></i> Change</span>
                                {!! Form::file('image', null, array('id'=>'image')) !!}
                            </span>
                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput" style="border-radius: 8px; padding: 8px 14px; font-size: 13px;">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </div>
                    <span class="adm-hint">Recommended format: Square JPG/PNG (300x300px)</span>
                    {!! APFrmErrHelp::showErrors($errors, 'image') !!}
                </div>
            </div>

            {{-- 5. Status & Availability --}}
            <div class="adm-card">
                <div class="adm-card-header">
                    <i class="fa fa-toggle-on"></i>
                    <h3 class="adm-card-title">Status & Availability</h3>
                </div>

                {{-- Immediate Available --}}
                <div class="form-group {!! APFrmErrHelp::hasError($errors, 'is_immediate_available') !!}" style="margin-bottom:16px;">
                    {!! Form::label('is_immediate_available', 'Immediate Availability', ['class' => 'adm-label']) !!}
                    <?php
                    $is_immediate_available_1 = 'checked="checked"';
                    $is_immediate_available_2 = '';
                    if (old('is_immediate_available', ((isset($user)) ? $user->is_immediate_available : 1)) == 0) {
                        $is_immediate_available_1 = '';
                        $is_immediate_available_2 = 'checked="checked"';
                    }
                    ?>
                    <div class="adm-radio-box">
                        <label class="adm-radio-option" for="immediate_available">
                            <input id="immediate_available" name="is_immediate_available" type="radio" value="1" {{$is_immediate_available_1}}>
                            <span style="color:#03855c;"><i class="fa fa-bolt"></i> Available</span>
                        </label>
                        <label class="adm-radio-option" for="not_immediate_available">
                            <input id="not_immediate_available" name="is_immediate_available" type="radio" value="0" {{$is_immediate_available_2}}>
                            <span style="color:#64748B;">Not Avail.</span>
                        </label>
                    </div>
                    {!! APFrmErrHelp::showErrors($errors, 'is_immediate_available') !!}
                </div>

                {{-- Is Active --}}
                <div class="form-group {!! APFrmErrHelp::hasError($errors, 'is_active') !!}" style="margin-bottom:16px;">
                    {!! Form::label('is_active', 'Account Approval (Active)', ['class' => 'adm-label']) !!}
                    <?php
                    $is_active_1 = 'checked="checked"';
                    $is_active_2 = '';
                    if (old('is_active', ((isset($user)) ? $user->is_active : 1)) == 0) {
                        $is_active_1 = '';
                        $is_active_2 = 'checked="checked"';
                    }
                    ?>
                    <div class="adm-radio-box">
                        <label class="adm-radio-option" for="active">
                            <input id="active" name="is_active" type="radio" value="1" {{$is_active_1}}>
                            <span style="color:#03855c;"><i class="fa fa-check-circle"></i> Active</span>
                        </label>
                        <label class="adm-radio-option" for="not_active">
                            <input id="not_active" name="is_active" type="radio" value="0" {{$is_active_2}}>
                            <span style="color:#DC2626;"><i class="fa fa-times-circle"></i> In-Active</span>
                        </label>
                    </div>
                    {!! APFrmErrHelp::showErrors($errors, 'is_active') !!}
                </div>

                {{-- Verified --}}
                <div class="form-group {!! APFrmErrHelp::hasError($errors, 'verified') !!}">
                    {!! Form::label('verified', 'Email / KYC Verification', ['class' => 'adm-label']) !!}
                    <?php
                    $verified_1 = 'checked="checked"';
                    $verified_2 = '';
                    if (old('verified', ((isset($user)) ? $user->verified : 1)) == 0) {
                        $verified_1 = '';
                        $verified_2 = 'checked="checked"';
                    }
                    ?>
                    <div class="adm-radio-box">
                        <label class="adm-radio-option" for="verified">
                            <input id="verified" name="verified" type="radio" value="1" {{$verified_1}}>
                            <span style="color:#2563EB;"><i class="fa fa-shield"></i> Verified</span>
                        </label>
                        <label class="adm-radio-option" for="not_verified">
                            <input id="not_verified" name="verified" type="radio" value="0" {{$verified_2}}>
                            <span style="color:#64748B;">Unverified</span>
                        </label>
                    </div>
                    {!! APFrmErrHelp::showErrors($errors, 'verified') !!}
                </div>
            </div>

            {{-- 6. Package Subscription (if active) --}}
            @if((bool)config('jobseeker.is_jobseeker_package_active'))
            <div class="adm-card">
                <div class="adm-card-header">
                    <i class="fa fa-gift"></i>
                    <h3 class="adm-card-title">Jobseeker Package</h3>
                </div>
                @if(isset($user) && $user->package_id > 0)
                <div style="background:#F0FDF4; border:1px solid #BBF7D0; border-radius:8px; padding:12px; margin-bottom:14px;">
                    <div style="font-weight:700; color:#166534; font-size:13.5px; margin-bottom:4px;">
                        Plan: {{ $user->getPackage('package_title') }}
                    </div>
                    <div style="font-size:12px; color:#15803D;">
                        {{ $user->package_start_date ? $user->package_start_date->format('d M, Y') : '' }} - {{ $user->package_end_date ? $user->package_end_date->format('d M, Y') : '' }}
                    </div>
                </div>
                @endif
                <div class="form-group {!! APFrmErrHelp::hasError($errors, 'job_seeker_package_id') !!}">
                    {!! Form::label('job_seeker_package_id', 'Assign Package', ['class' => 'adm-label']) !!}
                    {!! Form::select('job_seeker_package_id', ['' => 'Select Package']+$packages, null, array('class'=>'form-control adm-input', 'id'=>'job_seeker_package_id')) !!}
                    {!! APFrmErrHelp::showErrors($errors, 'job_seeker_package_id') !!}
                </div>
            </div>
            @endif

            {{-- Submit Action Box --}}
            <div class="adm-card" style="text-align: center;">
                {!! Form::button('<i class="fa fa-check"></i> Save & Update Profile', array('class'=>'adm-btn-save', 'type'=>'submit', 'style'=>'width:100%; justify-content:center; padding:14px !important; font-size:15px !important;')) !!}
                <div style="margin-top: 12px;">
                    <a href="{{ route('list.users') }}" style="color: #64748B; font-size: 13px; font-weight: 600; text-decoration: none;">
                        <i class="fa fa-arrow-left"></i> Back to Users List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}

@push('css')
<style type="text/css">
    .datepicker>div {
        display: block;
    }
</style>
@endpush
@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        initdatepicker();
        $('#salary_currency').typeahead({
            source: function (query, process) {
                return $.get("{{ route('typeahead.currency_codes') }}", {query: query}, function (data) {
                    data = $.parseJSON(data);
                    return process(data);
                });
            }
        });

        $('#country_id').on('change', function (e) {
            e.preventDefault();
            filterDefaultStates(0);
        });
        $(document).on('change', '#state_id', function (e) {
            e.preventDefault();
            filterDefaultCities(0);
        });
        filterDefaultStates(<?php echo old('state_id', (isset($user)) ? $user->state_id : 0); ?>);
    });
    function filterDefaultStates(state_id)
    {
        var country_id = $('#country_id').val();
        if (country_id != '') {
            $.post("{{ route('filter.default.states.dropdown') }}", {country_id: country_id, state_id: state_id, _method: 'POST', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        $('#default_state_dd').html(response);
                        filterDefaultCities(<?php echo old('city_id', (isset($user)) ? $user->city_id : 0); ?>);
                    });
        }
    }
    function filterDefaultCities(city_id)
    {
        var state_id = $('#state_id').val();
        if (state_id != '') {
            $.post("{{ route('filter.default.cities.dropdown') }}", {state_id: state_id, city_id: city_id, _method: 'POST', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        $('#default_city_dd').html(response);
                    });
        }
    }
    function initdatepicker() {
        $(".datepicker").datepicker({
            autoclose: true,
            format: 'yyyy-m-d'
        });
    }
</script>
@endpush