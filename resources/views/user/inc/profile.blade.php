
{!! Form::model($user, array('method' => 'put', 'route' => array('my.profile'), 'class' => 'form', 'files'=>true)) !!}

<div style="margin-bottom: 24px;">
    <h4 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0 0 4px 0; display: flex; align-items: center; gap: 8px;">
        <i class="fa fa-lock text-primary" style="font-size: 16px;"></i> {{__('Account Information')}}
    </h4>
    <p style="font-size: 13px; color: #64748B; margin: 0 0 16px 0;">{{__('Manage your login email and security credentials')}}</p>

    <div class="row">
        <div class="col-md-6 col-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'email') !!}" style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">{{__('Email Address')}} <span class="text-danger">*</span></label>
                {!! Form::text('email', null, array('class'=>'form-control modern-form-control', 'id'=>'email', 'placeholder'=>__('Email'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'email') !!}
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'password') !!}" style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">{{__('Password')}} <span style="font-size: 11.5px; font-weight: 500; color: #94A3B8;">({{__('Leave blank to keep unchanged')}})</span></label>
                <div class="pwd-field-wrap" style="position: relative;">
                    {!! Form::password('password', array('class'=>'form-control modern-form-control', 'id'=>'password_field', 'placeholder'=>__('New Password'), 'style'=>'padding-right: 44px;')) !!}
                    <button type="button" class="btn-pwd-eye" onclick="togglePasswordVisibility(this)" tabindex="-1" title="{{__('Show/Hide password')}}">
                        <i class="fa fa-eye-slash" style="font-size: 15px;"></i>
                    </button>
                </div>
                {!! APFrmErrHelp::showErrors($errors, 'password') !!}
            </div>
        </div>
    </div>
</div>

<hr style="border: 0; border-top: 1.5px dashed #E2E8F0; margin: 24px 0;">

<div style="margin-bottom: 24px;">
    <h4 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0 0 4px 0; display: flex; align-items: center; gap: 8px;">
        <i class="fa fa-user-circle text-primary" style="font-size: 16px;"></i> {{__('Personal Information')}}
    </h4>
    <p style="font-size: 13px; color: #64748B; margin: 0 0 18px 0;">{{__('Update your profile photo, personal details and address')}}</p>

    <!-- Profile Photo Uploader Card -->
    <div class="row">
        <div class="col-12">
            <div class="profile-photo-card" style="display: flex; align-items: center; gap: 20px; padding: 18px; background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 14px; margin-bottom: 20px; flex-wrap: wrap;">
                <div class="photo-avatar-container" style="position: relative; width: 88px; height: 88px; flex-shrink: 0;">
                    <img id="user_profile_avatar" src="{{ !empty($user->image) && file_exists(public_path('user_images/' . $user->image)) ? asset('user_images/' . $user->image) : asset('/admin_assets/no-image.png') }}" alt="{{ $user->getName() }}" class="profile-photo-img" style="width: 88px; height: 88px; border-radius: 50%; object-fit: cover; border: 2.5px solid #FFFFFF; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
                    <label for="image_input_file" class="photo-cam-badge" title="{{ __('Change Profile Photo') }}" style="position: absolute; right: 0; bottom: 0; width: 28px; height: 28px; background: #10B981; color: #FFFFFF; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; cursor: pointer; border: 2px solid #FFFFFF; box-shadow: 0 2px 5px rgba(0,0,0,0.15);">
                        <i class="fa fa-camera"></i>
                    </label>
                </div>
                <div class="photo-info-container" style="flex: 1; min-width: 220px;">
                    <h5 style="font-size: 14.5px; font-weight: 800; color: #0F172A; margin: 0 0 4px 0;">{{ __('Profile Photo') }}</h5>
                    <p style="font-size: 12.5px; color: #64748B; margin: 0 0 10px 0;">{{ __('Upload a clear, front-facing professional photo (JPG, PNG, WEBP, max 5MB).') }}</p>
                    <div class="photo-action-buttons" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <label for="image_input_file" class="btn btn-choose-photo" id="btn_choose_photo" style="display: inline-flex; align-items: center; gap: 8px; background: #0F172A; color: #FFFFFF; font-size: 13px; font-weight: 700; padding: 8px 18px; border-radius: 8px; cursor: pointer; margin: 0; transition: all 0.15s ease;">
                            <i class="fa fa-upload"></i> {{ __('UPLOAD NEW PHOTO') }}
                        </label>
                        <input type="file" name="image" id="image_input_file" accept="image/jpeg,image/png,image/jpg,image/webp,image/gif" style="display: none;">
                        <span id="photo_upload_indicator" class="photo-upload-indicator" style="display: none; font-size: 12.5px; color: #2563EB; font-weight: 600;">
                            <i class="fa fa-spinner fa-spin"></i> Uploading...
                        </span>
                    </div>
                    <div id="photo_success_alert" style="display: none; margin-top: 8px; font-size: 13px; font-weight: 700; color: #03855c;">
                        <i class="fa fa-check-circle"></i> {{ __('Photo updated successfully!') }}
                    </div>
                    <div id="photo_error_alert" style="display: none; margin-top: 8px; font-size: 13px; font-weight: 700; color: #DC2626;"></div>
                </div>
            </div>
            {!! APFrmErrHelp::showErrors($errors, 'image') !!}
        </div>
    </div>

    <!-- Personal Info Form Fields -->
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'first_name') !!}" style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">{{__('First Name')}} <span class="text-danger">*</span></label>
                {!! Form::text('first_name', null, array('class'=>'form-control modern-form-control', 'id'=>'first_name', 'placeholder'=>__('First Name'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'first_name') !!}
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'middle_name') !!}" style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">{{__('Middle Name')}}</label>
                {!! Form::text('middle_name', null, array('class'=>'form-control modern-form-control', 'id'=>'middle_name', 'placeholder'=>__('Middle Name'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'middle_name') !!}
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'last_name') !!}" style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">{{__('Last Name')}} <span class="text-danger">*</span></label>
                {!! Form::text('last_name', null, array('class'=>'form-control modern-form-control', 'id'=>'last_name', 'placeholder'=>__('Last Name'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'last_name') !!}
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'father_name') !!}" style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">{{__('Father Name')}}</label>
                {!! Form::text('father_name', null, array('class'=>'form-control modern-form-control', 'id'=>'father_name', 'placeholder'=>__('Father Name'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'father_name') !!}
            </div>
        </div>
        
        <div class="col-md-6 col-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'gender_id') !!}" style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">{{__('Gender')}}</label>
                {!! Form::select('gender_id', [''=>__('Select Gender')]+$genders, null, array('class'=>'form-control modern-form-control', 'id'=>'gender_id')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'gender_id') !!}
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'marital_status_id') !!}" style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">{{__('Marital Status')}}</label>
                {!! Form::select('marital_status_id', [''=>__('Select Marital Status')]+$maritalStatuses, null, array('class'=>'form-control modern-form-control', 'id'=>'marital_status_id')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'marital_status_id') !!}
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'country_id') !!}" style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">{{__('Country')}}</label>
                <?php $country_id = old('country_id', (isset($user) && (int) $user->country_id > 0) ? $user->country_id : $siteSetting->default_country_id); ?>
                {!! Form::select('country_id', [''=>__('Select Country')]+$countries, $country_id, array('class'=>'form-control modern-form-control', 'id'=>'country_id')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'country_id') !!}
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'state_id') !!}" style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">{{__('State')}}</label>
                <span id="state_dd"> {!! Form::select('state_id', [''=>__('Select State')], null, array('class'=>'form-control modern-form-control', 'id'=>'state_id')) !!} </span>
                {!! APFrmErrHelp::showErrors($errors, 'state_id') !!}
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'city_id') !!}" style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">{{__('City')}}</label>
                <span id="city_dd"> {!! Form::select('city_id', [''=>__('Select City')], null, array('class'=>'form-control modern-form-control', 'id'=>'city_id')) !!} </span>
                {!! APFrmErrHelp::showErrors($errors, 'city_id') !!}
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'nationality_id') !!}" style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">{{__('Nationality')}}</label>
                {!! Form::select('nationality_id', [''=>__('Select Nationality')]+$nationalities, null, array('class'=>'form-control modern-form-control', 'id'=>'nationality_id')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'nationality_id') !!}
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'date_of_birth') !!}" style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">{{__('Date of Birth')}}</label>
                {!! Form::date('date_of_birth', null, array('class'=>'form-control modern-form-control', 'id'=>'date_of_birth', 'placeholder'=>__('Date of Birth'), 'autocomplete'=>'off')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'date_of_birth') !!}
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'national_id_card_number') !!}" style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">{{__('National ID / Aadhaar')}}</label>
                {!! Form::text('national_id_card_number', null, array('class'=>'form-control modern-form-control', 'id'=>'national_id_card_number', 'placeholder'=>__('National ID Card#'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'national_id_card_number') !!}
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'phone') !!}" style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">{{__('Phone')}}</label>
                {!! Form::text('phone', null, array('class'=>'form-control modern-form-control', 'id'=>'phone', 'placeholder'=>__('Phone'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'phone') !!}
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'mobile_num') !!}" style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">{{__('Mobile Number')}}</label>
                {!! Form::text('mobile_num', null, array('class'=>'form-control modern-form-control', 'id'=>'mobile_num', 'placeholder'=>__('Mobile Number'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'mobile_num') !!}
            </div>
        </div>   
        <div class="col-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'street_address') !!}" style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">{{__('Street Address')}}</label>
                {!! Form::textarea('street_address', null, array('class'=>'form-control modern-form-control', 'id'=>'street_address', 'rows'=>2, 'placeholder'=>__('Street Address'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'street_address') !!}
            </div>
        </div>
    </div>
</div>

<hr style="border: 0; border-top: 1.5px dashed #E2E8F0; margin: 24px 0;">

<div style="margin-bottom: 24px;">
    <h4 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0 0 4px 0; display: flex; align-items: center; gap: 8px;">
        <i class="fa fa-briefcase text-primary" style="font-size: 16px;"></i> {{__('Career & Salary Information')}}
    </h4>
    <p style="font-size: 13px; color: #64748B; margin: 0 0 18px 0;">{{__('Specify your experience level, industry and salary expectations')}}</p>

    <div class="row">
        <div class="col-md-6 col-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'job_experience_id') !!}" style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">{{__('Total Job Experience')}}</label>
                {!! Form::select('job_experience_id', [''=>__('Select Experience')]+$jobExperiences, null, array('class'=>'form-control modern-form-control', 'id'=>'job_experience_id')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'job_experience_id') !!}
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'career_level_id') !!}" style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">{{__('Career Level')}}</label>
                {!! Form::select('career_level_id', [''=>__('Select Career Level')]+$careerLevels, null, array('class'=>'form-control modern-form-control', 'id'=>'career_level_id')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'career_level_id') !!}
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'industry_id') !!}" style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">{{__('Industry Domain')}}</label>
                {!! Form::select('industry_id', [''=>__('Select Industry')]+$industries, null, array('class'=>'form-control modern-form-control', 'id'=>'industry_id')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'industry_id') !!}
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'functional_area_id') !!}" style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">{{__('Functional Area')}}</label>
                {!! Form::select('functional_area_id', [''=>__('Select Functional Area')]+$functionalAreas, null, array('class'=>'form-control modern-form-control', 'id'=>'functional_area_id')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'functional_area_id') !!}
            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'current_salary') !!}" style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">{{__('Current Salary')}}</label>
                {!! Form::text('current_salary', null, array('class'=>'form-control modern-form-control', 'id'=>'current_salary', 'placeholder'=>__('e.g. 500000'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'current_salary') !!}
            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'expected_salary') !!}" style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">{{__('Expected Salary')}}</label>
                {!! Form::text('expected_salary', null, array('class'=>'form-control modern-form-control', 'id'=>'expected_salary', 'placeholder'=>__('e.g. 750000'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'expected_salary') !!}
            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'salary_currency') !!}" style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">{{__('Salary Currency')}}</label>			
                @php
                $salary_currency = Request::get('salary_currency', (isset($user) && !empty($user->salary_currency))? $user->salary_currency:$siteSetting->default_currency_code);
                @endphp
                {!! Form::text('salary_currency', $salary_currency, array('class'=>'form-control modern-form-control', 'id'=>'salary_currency', 'placeholder'=>__('e.g. INR / USD'), 'autocomplete'=>'off')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'salary_currency') !!}
            </div>
        </div>
    </div>
</div>

<div class="row" style="margin-top: 10px;">
    <div class="col-12">
        <div class="formrow {!! APFrmErrHelp::hasError($errors, 'is_subscribed') !!}" style="margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <?php
            $is_checked = 'checked="checked"';	
            if (old('is_subscribed', ((isset($user)) ? $user->is_subscribed : 1)) == 0) {
                $is_checked = '';
            }
            ?>
            <input type="checkbox" value="1" name="is_subscribed" id="is_subscribed" {{$is_checked}} style="width: 17px; height: 17px; cursor: pointer;" />
            <label for="is_subscribed" style="font-size: 13.5px; font-weight: 600; color: #334155; margin: 0; cursor: pointer;">
                {{__('Subscribe to newsletter and job alert notifications')}}
            </label>
            {!! APFrmErrHelp::showErrors($errors, 'is_subscribed') !!}
        </div>
    </div>
    <div class="col-12">
        <div class="formrow">
            <button type="submit" class="btn btn-save-profile" style="display: inline-flex; align-items: center; justify-content: center; gap: 10px; background: #2563EB; color: #FFFFFF; font-size: 15px; font-weight: 700; padding: 13px 32px; border-radius: 12px; border: none; cursor: pointer; box-shadow: 0 4px 14px rgba(37,99,235,0.3); transition: all 0.15s ease;">
                <span>{{__('Update Profile and Save')}}</span>
                <i class="fa fa-arrow-right" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</div>

{!! Form::close() !!}
@push('styles')
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
                    console.log(data);
                    data = $.parseJSON(data);
                    return process(data);
                });
            }
        });

        $('#country_id').on('change', function (e) {
            e.preventDefault();
            filterStates(0);
        });
        $(document).on('change', '#state_id', function (e) {
            e.preventDefault();
            filterCities(0);
        });
        filterStates(<?php echo old('state_id', $user->state_id); ?>);

        /*******************************/
        var imageInput = document.getElementById("image_input_file");
        if (imageInput) {
            imageInput.addEventListener("change", function (e) {
                var files = this.files;
                if (!files || !files.length) return;
                var file = files[0];
                var imageType = /image.*/;
                if (!file.type.match(imageType)) {
                    $('#photo_error_alert').html('<i class="fa fa-exclamation-triangle"></i> Please select a valid image file.').fadeIn().delay(4000).fadeOut();
                    return;
                }

                // Instant local preview
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#user_profile_avatar').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);

                // Instant AJAX upload
                var formData = new FormData();
                formData.append('image', file);
                formData.append('_token', '{{ csrf_token() }}');

                $('#photo_upload_indicator').show().html('<i class="fa fa-spinner fa-spin"></i> Uploading photo...');
                $('#photo_success_alert').hide();
                $('#photo_error_alert').hide();

                $.ajax({
                    url: "{{ route('update.user.image') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function (response) {
                        $('#photo_upload_indicator').hide();
                        if (response.status === 'success') {
                            var newUrl = response.image_url + '?v=' + new Date().getTime();
                            $('#user_profile_avatar').attr('src', newUrl);
                            // Update header and navbar avatars dynamically
                            $('.userbtn img, .userbtn .userimg').attr('src', newUrl);
                            $('#photo_success_alert').html('<i class="fa fa-check-circle"></i> ' + response.message).fadeIn().delay(4000).fadeOut();
                        }
                    },
                    error: function (xhr) {
                        $('#photo_upload_indicator').hide();
                        var err = 'Failed to upload photo. Please check image format (JPG, PNG, WEBP) and size (max 5MB).';
                        if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.image) {
                            err = xhr.responseJSON.errors.image[0];
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            err = xhr.responseJSON.message;
                        }
                        $('#photo_error_alert').html('<i class="fa fa-exclamation-triangle"></i> ' + err).fadeIn().delay(5000).fadeOut();
                    }
                });
            }, false);
        }
    });

    function filterStates(state_id)
    {
        var country_id = $('#country_id').val();
        if (country_id != '') {
            $.post("{{ route('filter.lang.states.dropdown') }}", {country_id: country_id, state_id: state_id, _method: 'POST', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        $('#state_dd').html(response);
                        filterCities(<?php echo old('city_id', $user->city_id); ?>);
                    });
        }
    }
    function filterCities(city_id)
    {
        var state_id = $('#state_id').val();
        if (state_id != '') {
            $.post("{{ route('filter.lang.cities.dropdown') }}", {state_id: state_id, city_id: city_id, _method: 'POST', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        $('#city_dd').html(response);
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