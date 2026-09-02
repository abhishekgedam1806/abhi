
{!! Form::model($company, array('method' => 'put', 'route' => array('update.company.profile'), 'class' => 'form', 'files'=>true)) !!}

<!-- Card 1: Account Information -->
<div class="profile-card">
    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; border-bottom: 1px solid #F1F5F9; padding-bottom: 14px;">
        <div style="width: 38px; height: 38px; border-radius: 10px; background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
            <i class="fa fa-lock"></i>
        </div>
        <div>
            <h3 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.2px;">{{__('Account & Login Information')}}</h3>
            <span style="font-size: 12.5px; color: #64748B;">{{__('Manage your primary login credentials')}}</span>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'email') !!}">
                <label for="email">{{__('Email Address')}} <span style="color: #EF4444;">*</span></label>
                {!! Form::text('email', null, array('class'=>'form-control', 'id'=>'email', 'placeholder'=>__('Company Email Address'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'email') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'password') !!}">
                <label for="password">{{__('Change Password')}}</label>
                {!! Form::password('password', array('class'=>'form-control', 'id'=>'password', 'placeholder'=>__('Enter new password (leave blank to keep current)'))) !!}
                <span style="font-size: 11.5px; color: #94A3B8; display: block; margin-top: 4px;">{{__('Leave blank if you do not wish to change your password.')}}</span>
                {!! APFrmErrHelp::showErrors($errors, 'password') !!}
            </div>
        </div>
    </div>
</div>

<!-- Card 2: Company Information -->
<div class="profile-card">
    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; border-bottom: 1px solid #F1F5F9; padding-bottom: 14px;">
        <div style="width: 38px; height: 38px; border-radius: 10px; background: #F3E8FF; color: #7C3AED; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
            <i class="fa fa-building-o"></i>
        </div>
        <div>
            <h3 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.2px;">{{__('Company Profile & Details')}}</h3>
            <span style="font-size: 12.5px; color: #64748B;">{{__('Public information visible to candidates and job seekers')}}</span>
        </div>
    </div>

    <!-- Company Logo Uploader Component -->
    <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 18px 22px; margin-bottom: 22px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
        <div style="display: flex; align-items: center; gap: 16px;">
            <!-- Logo Preview Squircle -->
            <div id="companyLogoPreviewBox" style="width: 72px; height: 72px; border-radius: 14px; overflow: hidden; border: 2px solid #CBD5E1; background: #FFFFFF; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(15,23,42,0.05); flex-shrink: 0;">
                @if(!empty($company->logo) && file_exists(public_path('company_logos/' . $company->logo)))
                    <img id="companyLogoImg" src="{{ asset('company_logos/' . $company->logo) }}" alt="{{ $company->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <div id="companyLogoInitial" style="width: 100%; height: 100%; background: #EFF6FF; color: #2563EB; font-size: 28px; font-weight: 800; display: flex; align-items: center; justify-content: center;">
                        {{ strtoupper(substr($company->name ?: 'C', 0, 1)) }}
                    </div>
                @endif
            </div>

            <!-- Meta Text & Live File Status -->
            <div>
                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                    <span style="font-size: 15px; font-weight: 800; color: #0F172A;">{{__('Company Logo')}}</span>
                    <span id="logoFileNameBadge" style="display: none; background: #ECFDF5; color: #065F46; border: 1.5px solid #6EE7B7; font-size: 12px; font-weight: 700; padding: 3px 10px; border-radius: 20px;">
                        <i class="fa fa-check-circle" style="color: #03855c; margin-right: 4px;"></i><span id="logoFileNameText"></span>
                    </span>
                </div>
                <p style="font-size: 12.5px; color: #475569; margin: 3px 0 0 0; line-height: 1.4; font-weight: 500;">
                    {{__('Recommended: 200x200px (JPG, PNG, WebP) - Max 2MB')}}
                </p>
            </div>
        </div>

        <!-- Action Controls -->
        <div style="display: flex; align-items: center; gap: 10px;">
            <label for="logo" style="background: #2563EB !important; color: #FFFFFF !important; font-size: 13.5px; font-weight: 700; padding: 10px 20px; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; margin: 0; box-shadow: 0 4px 12px rgba(37,99,235,0.3); transition: all 0.15s ease;">
                <i class="fa fa-cloud-upload" style="color: #FFFFFF !important; font-size: 15px;"></i>
                <span id="uploadBtnText" style="color: #FFFFFF !important; font-weight: 700;">{{ !empty($company->logo) ? __('Change Logo') : __('Upload Logo') }}</span>
                <input type="file" name="logo" id="logo" accept="image/*" style="display: none;">
            </label>
            <button type="button" id="resetLogoBtn" onclick="resetLogoPreview()" style="display: none; background: #F8FAFC; color: #334155; border: 1.5px solid #CBD5E1; font-size: 13px; font-weight: 700; padding: 9px 16px; border-radius: 10px; cursor: pointer; transition: all 0.15s ease;">
                {{__('Cancel')}}
            </button>
            {!! APFrmErrHelp::showErrors($errors, 'logo') !!}
        </div>
    </div>

    <!-- Basic Information -->
    <div class="row">
        <div class="col-md-6">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'name') !!}">
                <label>{{__('Company Name')}} <span style="color: #EF4444;">*</span></label>
                {!! Form::text('name', null, array('class'=>'form-control', 'id'=>'name', 'placeholder'=>__('e.g. Acme Corporation'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'name') !!}
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'ceo') !!}">
                <label>{{__('CEO / Director Name')}}</label>
                {!! Form::text('ceo', null, array('class'=>'form-control', 'id'=>'ceo', 'placeholder'=>__('Company CEO / Founder Name'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'ceo') !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'industry_id') !!}">
                <label>{{__('Industry')}} <span style="color: #EF4444;">*</span></label>
                {!! Form::select('industry_id', ['' => __('Select Industry')]+$industries, null, array('class'=>'form-control', 'id'=>'industry_id')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'industry_id') !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'ownership_type_id') !!}">
                <label>{{__('Ownership Type')}}</label>
                {!! Form::select('ownership_type_id', ['' => __('Select Ownership Type')]+$ownershipTypes, null, array('class'=>'form-control', 'id'=>'ownership_type_id')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'ownership_type_id') !!}
            </div>
        </div>

        <div class="col-md-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'description') !!}">
                <label>{{__('About Company & Description')}}</label>
                {!! Form::textarea('description', null, array('class'=>'form-control', 'id'=>'description', 'placeholder'=>__('Provide a comprehensive description of your company, culture, and achievements...'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'description') !!}
            </div>
        </div>

        <!-- Office Address & Geographical Map Location -->
        <div class="col-md-12">
            <div style="margin: 10px 0 16px 0; border-top: 1px solid #F1F5F9; padding-top: 16px;">
                <span style="font-size: 14px; font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 6px; margin-bottom: 2px;">
                    <i class="fa fa-map-marker text-danger" style="font-size: 16px;"></i> {{__('Office Address & Geographical Location')}}
                </span>
                <span style="font-size: 12px; color: #64748B;">{{__('Enter your physical office location to pin your headquarters on the map')}}</span>
            </div>
        </div>

        <div class="col-md-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'location') !!}">
                <label>{{__('Headquarters / Office Address')}} <span style="color: #EF4444;">*</span></label>
                {!! Form::text('location', null, array('class'=>'form-control', 'id'=>'location', 'placeholder'=>__('Complete street address, building name, landmark (e.g. Plot No. 24, IT Park Road, MIHAN SEZ)...'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'location') !!}
            </div>
        </div>

        <div class="col-md-4">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'country_id') !!}">
                <label>{{__('Country')}} <span style="color: #EF4444;">*</span></label>
                {!! Form::select('country_id', ['' => __('Select Country')]+$countries, old('country_id', (isset($company))? $company->country_id:$siteSetting->default_country_id), array('class'=>'form-control', 'id'=>'country_id')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'country_id') !!}
            </div>
        </div>

        <div class="col-md-4">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'state_id') !!}">
                <label>{{__('State')}} <span style="color: #EF4444;">*</span></label>
                <span id="default_state_dd">
                    {!! Form::select('state_id', ['' => __('Select State')], null, array('class'=>'form-control', 'id'=>'state_id')) !!}
                </span>
                {!! APFrmErrHelp::showErrors($errors, 'state_id') !!}
            </div>
        </div>

        <div class="col-md-4">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'city_id') !!}">
                <label>{{__('City')}} <span style="color: #EF4444;">*</span></label>
                <span id="default_city_dd">
                    {!! Form::select('city_id', ['' => __('Select City')], null, array('class'=>'form-control', 'id'=>'city_id')) !!}
                </span>
                {!! APFrmErrHelp::showErrors($errors, 'city_id') !!}
            </div>
        </div>

        <div class="col-md-12">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'map') !!}" style="margin-bottom: 10px;">
                <label for="map"><i class="fa fa-crosshairs" style="color: #2563EB; margin-right: 4px;"></i> {{__('Google Maps Location (Auto-detected from Address)')}}</label>
                {!! Form::text('map', null, array('class'=>'form-control', 'id'=>'map', 'placeholder'=>__('Auto-generated from your address or paste custom Google Maps URL'))) !!}
                <span style="font-size: 11.5px; color: #64748B; display: block; margin-top: 4px;">
                    <i class="fa fa-info-circle text-primary" style="margin-right: 3px;"></i> {{__('Your office address, city, and state are automatically used to pin your location on the map. You can also paste a custom Google Maps share link.')}}
                </span>
                {!! APFrmErrHelp::showErrors($errors, 'map') !!}
            </div>

            <!-- Live Google Map Preview Box -->
            <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 14px; margin-top: 10px; margin-bottom: 16px; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; flex-wrap: wrap; gap: 8px;">
                    <span style="font-size: 13px; font-weight: 700; color: #0F172A; display: flex; align-items: center; gap: 6px;">
                        <i class="fa fa-crosshairs" style="color: #2563EB;"></i> {{__('Live Map Preview')}}
                    </span>
                    <span id="mapDetectedAddressLabel" style="font-size: 11.5px; color: #64748B; font-weight: 600;"></span>
                </div>
                <div style="width: 100%; height: 200px; border-radius: 10px; overflow: hidden; background: #F1F5F9; border: 1px solid #CBD5E1;">
                    <iframe id="companyMapIframePreview" src="{{ isset($company) ? $company->getGoogleMapEmbedUrl() : 'https://maps.google.com/maps?q=India&t=&z=14&ie=UTF8&iwloc=&output=embed' }}" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>

        <!-- Business & Contact Details -->
        <div class="col-md-12">
            <div style="margin: 10px 0 16px 0; border-top: 1px solid #F1F5F9; padding-top: 16px;">
                <span style="font-size: 14px; font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 6px; margin-bottom: 2px;">
                    <i class="fa fa-briefcase" style="color: #2563EB;"></i> {{__('Business & Contact Details')}}
                </span>
                <span style="font-size: 12px; color: #64748B;">{{__('Company capacity, communication lines, and web presence')}}</span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'no_of_offices') !!}">
                <label>{{__('Number of Offices')}}</label>
                {!! Form::select('no_of_offices', ['' => __('Select number of offices')]+MiscHelper::getNumOffices(), null, array('class'=>'form-control', 'id'=>'no_of_offices')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'no_of_offices') !!}
            </div>
        </div>

        <div class="col-md-4">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'no_of_employees') !!}">
                <label>{{__('Number of Employees')}}</label>
                {!! Form::select('no_of_employees', ['' => __('Select number of employees')]+MiscHelper::getNumEmployees(), null, array('class'=>'form-control', 'id'=>'no_of_employees')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'no_of_employees') !!}
            </div>
        </div>

        <div class="col-md-4">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'established_in') !!}">
                <label>{{__('Established In (Year)')}}</label>
                {!! Form::select('established_in', ['' => __('Select Year')]+MiscHelper::getEstablishedIn(), null, array('class'=>'form-control', 'id'=>'established_in')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'established_in') !!}
            </div>
        </div>

        <div class="col-md-4">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'website') !!}">
                <label>{{__('Website URL')}}</label>
                {!! Form::text('website', null, array('class'=>'form-control', 'id'=>'website', 'placeholder'=>__('https://example.com'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'website') !!}
            </div>
        </div>

        <div class="col-md-4">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'phone') !!}">
                <label>{{__('Contact Phone Number')}}</label>
                {!! Form::text('phone', null, array('class'=>'form-control', 'id'=>'phone', 'placeholder'=>__('+91 9876543210'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'phone') !!}
            </div>
        </div>

        <div class="col-md-4">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'fax') !!}">
                <label>{{__('Fax Number')}}</label>
                {!! Form::text('fax', null, array('class'=>'form-control', 'id'=>'fax', 'placeholder'=>__('Fax number (optional)'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'fax') !!}
            </div>
        </div>

        <!-- Social Media Profiles -->
        <div class="col-md-12">
            <div style="margin: 10px 0 16px 0; border-top: 1px solid #F1F5F9; padding-top: 16px;">
                <span style="font-size: 14px; font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 6px; margin-bottom: 2px;">
                    <i class="fa fa-share-alt" style="color: #7C3AED;"></i> {{__('Social Media Profiles')}}
                </span>
                <span style="font-size: 12px; color: #64748B;">{{__('Connect your company social channels')}}</span>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'facebook') !!}">
                <label><i class="fa fa-facebook-square" style="color: #1877F2;"></i> {{__('Facebook')}}</label>
                {!! Form::text('facebook', null, array('class'=>'form-control', 'id'=>'facebook', 'placeholder'=>__('Facebook URL'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'facebook') !!}
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'twitter') !!}">
                <label><i class="fa fa-twitter-square" style="color: #1DA1F2;"></i> {{__('Twitter / X')}}</label>
                {!! Form::text('twitter', null, array('class'=>'form-control', 'id'=>'twitter', 'placeholder'=>__('Twitter / X URL'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'twitter') !!}
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'linkedin') !!}">
                <label><i class="fa fa-linkedin-square" style="color: #0A66C2;"></i> {{__('LinkedIn')}}</label>
                {!! Form::text('linkedin', null, array('class'=>'form-control', 'id'=>'linkedin', 'placeholder'=>__('LinkedIn URL'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'linkedin') !!}
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'pinterest') !!}">
                <label><i class="fa fa-pinterest-square" style="color: #E60023;"></i> {{__('Pinterest')}}</label>
                {!! Form::text('pinterest', null, array('class'=>'form-control', 'id'=>'pinterest', 'placeholder'=>__('Pinterest URL'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'pinterest') !!}
            </div>
        </div>

        <div class="col-md-12">
            <div style="padding: 12px 16px; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; margin-top: 6px;">
                @php
                    $is_checked = 'checked="checked"';	
                    if (old('is_subscribed', ((isset($company)) ? $company->is_subscribed : 1)) == 0) {
                        $is_checked = '';
                    }
                @endphp
                <label style="display: flex; align-items: center; gap: 10px; font-weight: 600; color: #1E293B; cursor: pointer; margin: 0;">
                    <input type="checkbox" value="1" name="is_subscribed" {{$is_checked}} style="width: 17px; height: 17px; accent-color: #2563EB;" />
                    <span>{{__('Subscribe to newsletter and company updates')}}</span>
                </label>
                {!! APFrmErrHelp::showErrors($errors, 'is_subscribed') !!}
            </div>
        </div>
    </div>
</div>

<!-- Card 3: HR / Recruiter & Candidate Contact Settings -->
<div class="profile-card">
    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; border-bottom: 1px solid #F1F5F9; padding-bottom: 14px;">
        <div style="width: 38px; height: 38px; border-radius: 10px; background: #ECFDF5; color: #03855c; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
            <i class="fa fa-address-card-o"></i>
        </div>
        <div>
            <h3 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.2px;">{{__('HR / Recruiter & Direct Candidate Contact')}}</h3>
            <span style="font-size: 12.5px; color: #64748B;">{{__('Enable direct calling and WhatsApp chat for prospective job applicants')}}</span>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'hr_name') !!}">
                <label for="hr_name"><i class="fa fa-user-circle-o" style="color: #2563EB; margin-right: 4px;"></i> {{__('HR / Recruiter Name')}}</label>
                {!! Form::text('hr_name', null, array('class'=>'form-control', 'id'=>'hr_name', 'placeholder'=>__('e.g. Alfaz, Lagan Saluja'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'hr_name') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="formrow {!! APFrmErrHelp::hasError($errors, 'whatsapp_number') !!}">
                <label for="whatsapp_number"><i class="fa fa-whatsapp" style="color: #03855c; margin-right: 4px;"></i> {{__('HR WhatsApp Number')}}</label>
                {!! Form::text('whatsapp_number', null, array('class'=>'form-control', 'id'=>'whatsapp_number', 'placeholder'=>__('e.g. +91 98765 43210'))) !!}
                {!! APFrmErrHelp::showErrors($errors, 'whatsapp_number') !!}
            </div>
        </div>

        <!-- Toggle Feature Card 1: Direct Call -->
        <div class="col-md-6">
            <label style="width: 100%; cursor: pointer; margin: 0 0 16px 0;">
                <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 16px 18px; display: flex; align-items: center; justify-content: space-between; gap: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.02); transition: all 0.2s ease;">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="width: 42px; height: 42px; border-radius: 12px; background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;">
                            <i class="fa fa-phone"></i>
                        </div>
                        <div>
                            <span style="font-size: 13.5px; font-weight: 700; color: #0F172A; display: block; margin-bottom: 2px;">{{__('Direct Phone Calling (Call HR)')}}</span>
                            <span style="font-size: 12px; color: #64748B; display: block; line-height: 1.3;">{{__('Display Call HR button on your job postings for instant calls')}}</span>
                        </div>
                    </div>
                    <div>
                        <input type="checkbox" name="allow_phone_contact" value="1" {{ old('allow_phone_contact', ((isset($company)) ? $company->allow_phone_contact : 1)) ? 'checked' : '' }} style="width: 20px; height: 20px; accent-color: #2563EB; cursor: pointer;">
                    </div>
                </div>
            </label>
        </div>

        <!-- Toggle Feature Card 2: WhatsApp Chat -->
        <div class="col-md-6">
            <label style="width: 100%; cursor: pointer; margin: 0 0 16px 0;">
                <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 16px 18px; display: flex; align-items: center; justify-content: space-between; gap: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.02); transition: all 0.2s ease;">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="width: 42px; height: 42px; border-radius: 12px; background: #ECFDF5; color: #03855c; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                            <i class="fa fa-whatsapp"></i>
                        </div>
                        <div>
                            <span style="font-size: 13.5px; font-weight: 700; color: #0F172A; display: block; margin-bottom: 2px;">{{__('WhatsApp Candidate Chat')}}</span>
                            <span style="font-size: 12px; color: #64748B; display: block; line-height: 1.3;">{{__('Allow job seekers to chat with your recruiter on WhatsApp')}}</span>
                        </div>
                    </div>
                    <div>
                        <input type="checkbox" name="allow_whatsapp_contact" value="1" {{ old('allow_whatsapp_contact', ((isset($company)) ? $company->allow_whatsapp_contact : 1)) ? 'checked' : '' }} style="width: 20px; height: 20px; accent-color: #03855c; cursor: pointer;">
                    </div>
                </div>
            </label>
        </div>
    </div>
</div>

<!-- Submit Action Button -->
<div style="margin-top: 10px; margin-bottom: 30px; display: flex; justify-content: flex-end;">
    <button type="submit" style="background: #2563EB; color: #FFFFFF; font-size: 15px; font-weight: 800; padding: 14px 36px; border-radius: 12px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 10px; box-shadow: 0 4px 16px rgba(37,99,235,0.3); transition: all 0.2s ease;">
        <i class="fa fa-check-circle" style="font-size: 16px;"></i>
        {{__('Update Profile and Save')}}
    </button>
</div>

<input type="file" name="image" id="image" style="display:none;" accept="image/*"/>
{!! Form::close() !!}

@push('styles')
<style type="text/css">
    .datepicker>div {
        display: block;
    }
</style>
@endpush
@push('scripts')
@include('includes.tinyMCEFront') 
<script type="text/javascript">
    var originalLogoHTML = '';
    var mapDebounceTimer = null;

    $(document).ready(function () {
        var previewBox = document.getElementById('companyLogoPreviewBox');
        if (previewBox) {
            originalLogoHTML = previewBox.innerHTML;
        }

        $('#country_id').on('change', function (e) {
            e.preventDefault();
            filterLangStates(0);
            updateLiveMapPreview();
        });
        $(document).on('change', '#state_id', function (e) {
            e.preventDefault();
            filterLangCities(0);
            updateLiveMapPreview();
        });
        $(document).on('change', '#city_id', function (e) {
            updateLiveMapPreview();
        });

        $('#location, #map').on('input keyup', function () {
            clearTimeout(mapDebounceTimer);
            mapDebounceTimer = setTimeout(function () {
                updateLiveMapPreview();
            }, 600);
        });

        filterLangStates(<?php echo old('state_id', (isset($company)) ? $company->state_id : 0); ?>);
        updateLiveMapPreview();

        var fileInput = document.getElementById("logo");
        if (fileInput) {
            fileInput.addEventListener("change", function (e) {
                var files = this.files;
                showThumbnail(files);
            }, false);
        }
    });

    function updateLiveMapPreview() {
        var customMap = $('#map').val() ? $('#map').val().trim() : '';
        var iframe = document.getElementById('companyMapIframePreview');
        var addressLabel = document.getElementById('mapDetectedAddressLabel');
        if (!iframe) return;

        if (customMap !== '') {
            var match = customMap.match(/src=["']([^"']+)["']/);
            if (match) {
                iframe.src = match[1];
                if (addressLabel) addressLabel.textContent = "{{ __('Custom Embed Active') }}";
                return;
            } else if (customMap.indexOf('http') === 0) {
                iframe.src = customMap;
                if (addressLabel) addressLabel.textContent = "{{ __('Custom URL Active') }}";
                return;
            }
        }

        var location = $('#location').val() ? $('#location').val().trim() : '';
        var country = $('#country_id option:selected').text();
        if (country.indexOf('Select') !== -1) country = '';
        var state = $('#state_id option:selected').text();
        if (state.indexOf('Select') !== -1) state = '';
        var city = $('#city_id option:selected').text();
        if (city.indexOf('Select') !== -1) city = '';

        var parts = [];
        if (location) parts.push(location);
        if (city) parts.push(city);
        if (state) parts.push(state);
        if (country) parts.push(country);

        var fullAddress = parts.join(', ');
        if (fullAddress === '') fullAddress = 'India';

        if (addressLabel) {
            addressLabel.innerHTML = '<i class="fa fa-map-marker text-danger" style="margin-right: 4px;"></i> ' + fullAddress;
        }
        iframe.src = 'https://maps.google.com/maps?q=' + encodeURIComponent(fullAddress) + '&t=&z=14&ie=UTF8&iwloc=&output=embed';
    }

    function showThumbnail(files) {
        if (files && files.length > 0) {
            var file = files[0];
            var imageType = /image.*/;
            if (!file.type.match(imageType)) {
                alert("Please select a valid image file (PNG, JPG, WebP).");
                return;
            }

            var reader = new FileReader();
            reader.onload = function (e) {
                var previewBox = document.getElementById('companyLogoPreviewBox');
                if (previewBox) {
                    previewBox.innerHTML = '<img id="companyLogoImg" src="' + e.target.result + '" alt="Company Logo" style="width: 100%; height: 100%; object-fit: cover;">';
                }

                var badge = document.getElementById('logoFileNameBadge');
                var textSpan = document.getElementById('logoFileNameText');
                var btnText = document.getElementById('uploadBtnText');
                var resetBtn = document.getElementById('resetLogoBtn');
                if (badge && textSpan) {
                    textSpan.textContent = file.name;
                    badge.style.display = 'inline-flex';
                }
                if (btnText) {
                    btnText.textContent = "{{ __('Change Logo') }}";
                }
                if (resetBtn) {
                    resetBtn.style.display = 'inline-block';
                }
            };
            reader.readAsDataURL(file);
        }
    }

    function resetLogoPreview() {
        var fileInput = document.getElementById("logo");
        if (fileInput) fileInput.value = '';

        var previewBox = document.getElementById('companyLogoPreviewBox');
        if (previewBox && originalLogoHTML) {
            previewBox.innerHTML = originalLogoHTML;
        }

        var badge = document.getElementById('logoFileNameBadge');
        var resetBtn = document.getElementById('resetLogoBtn');
        var btnText = document.getElementById('uploadBtnText');
        if (badge) badge.style.display = 'none';
        if (resetBtn) resetBtn.style.display = 'none';
        if (btnText) {
            btnText.textContent = "{{ !empty($company->logo) ? __('Change Logo') : __('Upload Logo') }}";
        }
    }

    function filterLangStates(state_id)
    {
        var country_id = $('#country_id').val();
        if (country_id != '') {
            $.post("{{ route('filter.lang.states.dropdown') }}", {country_id: country_id, state_id: state_id, _method: 'POST', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        $('#default_state_dd').html(response);
                        filterLangCities(<?php echo old('city_id', (isset($company)) ? $company->city_id : 0); ?>);
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
                        updateLiveMapPreview();
                    });
        }
    }
</script> 
@endpush