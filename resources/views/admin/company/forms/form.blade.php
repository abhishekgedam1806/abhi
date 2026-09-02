<style>
/* Modern Admin Form Styling */
.adm-edit-wrapper {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #1E293B;
}
.adm-header-bar {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 18px 24px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}
.adm-header-left {
    display: flex;
    align-items: center;
    gap: 16px;
}
.adm-header-avatar {
    width: 52px;
    height: 52px;
    border-radius: 10px;
    background: #F1F5F9;
    border: 1px solid #E2E8F0;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
}
.adm-header-avatar img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
.adm-header-title {
    font-size: 20px;
    font-weight: 800;
    color: #0F172A;
    margin: 0 0 4px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.adm-header-sub {
    font-size: 13px;
    color: #64748B;
    margin: 0;
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
.adm-label .req {
    color: #EF4444;
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
    min-height: 100px !important;
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
    gap: 12px;
}
.adm-radio-option {
    flex: 1;
    border: 1.5px solid #E2E8F0;
    border-radius: 8px;
    padding: 10px 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    background: #F8FAFC;
    transition: all 0.2s ease;
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
    font-size: 13px;
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
.adm-pkg-banner {
    background: #F0FDF4;
    border: 1px solid #BBF7D0;
    border-radius: 10px;
    padding: 14px 16px;
    margin-bottom: 16px;
}
.adm-pkg-title {
    font-size: 14px;
    font-weight: 800;
    color: #166534;
    margin-bottom: 4px;
}
.adm-pkg-meta {
    font-size: 12.5px;
    color: #15803D;
    display: flex;
    justify-content: space-between;
    margin-bottom: 6px;
}
.adm-pkg-progress-bg {
    height: 6px;
    background: #E2E8F0;
    border-radius: 3px;
    overflow: hidden;
}
.adm-pkg-progress-bar {
    height: 100%;
    background: #16A34A;
    border-radius: 3px;
}
.adm-logo-preview-box {
    border: 2px dashed #CBD5E1;
    border-radius: 10px;
    padding: 16px;
    text-align: center;
    background: #F8FAFC;
    margin-bottom: 14px;
}
.adm-logo-preview-box img {
    max-height: 120px;
    max-width: 100%;
    object-fit: contain;
    border-radius: 6px;
}
</style>

{!! APFrmErrHelp::showOnlyErrorsNotice($errors) !!}
@include('flash::message')

<div class="adm-edit-wrapper">
    {{-- Header Banner --}}
    <div class="adm-header-bar">
        <div class="adm-header-left">
            <div class="adm-header-avatar">
                @if(isset($company) && !empty($company->logo))
                    <img src="{{ asset('company_logos/'.$company->logo) }}" alt="{{ $company->name }}">
                @else
                    <i class="fa fa-building-o" style="font-size: 22px; color: #94A3B8;"></i>
                @endif
            </div>
            <div>
                <h1 class="adm-header-title">
                    {{ isset($company) ? $company->name : 'Create New Company' }}
                    @if(isset($company))
                        @if($company->is_active)
                            <span class="label label-sm label-success" style="font-size: 11px; padding: 3px 8px; border-radius: 10px;">Active</span>
                        @else
                            <span class="label label-sm label-danger" style="font-size: 11px; padding: 3px 8px; border-radius: 10px;">In-Active</span>
                        @endif
                        @if($company->is_featured)
                            <span class="label label-sm label-warning" style="font-size: 11px; padding: 3px 8px; border-radius: 10px;"><i class="fa fa-bolt"></i> Featured</span>
                        @endif
                    @endif
                </h1>
                <p class="adm-header-sub">
                    <i class="fa fa-envelope-o"></i> {{ isset($company) ? $company->email : 'Set up company profile details' }}
                    @if(isset($company) && !empty($company->slug))
                        • <a href="{{ route('company.detail', $company->slug) }}" target="_blank" style="color: #2563EB; font-weight: 600; text-decoration: none;"><i class="fa fa-external-link"></i> View Frontend Profile</a>
                    @endif
                </p>
            </div>
        </div>
        <div>
            {!! Form::button('<i class="fa fa-check"></i> Save Changes', array('class'=>'adm-btn-save', 'type'=>'submit')) !!}
        </div>
    </div>

    {{-- Main 2-Column Form Layout --}}
    <div class="row">
        {{-- LEFT COLUMN (8 Cols) --}}
        <div class="col-md-8">
            {{-- 1. Basic Information --}}
            <div class="adm-card">
                <div class="adm-card-header">
                    <i class="fa fa-id-card-o"></i>
                    <h3 class="adm-card-title">Basic Information</h3>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'name') !!}">
                            {!! Form::label('name', 'Company Name', ['class' => 'adm-label']) !!}
                            {!! Form::text('name', null, array('class'=>'form-control adm-input', 'id'=>'name', 'placeholder'=>'e.g. Acme Corporation')) !!}
                            {!! APFrmErrHelp::showErrors($errors, 'name') !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'email') !!}">
                            {!! Form::label('email', 'Company Email', ['class' => 'adm-label']) !!}
                            {!! Form::text('email', null, array('class'=>'form-control adm-input', 'id'=>'email', 'placeholder'=>'admin@company.com')) !!}
                            {!! APFrmErrHelp::showErrors($errors, 'email') !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'password') !!}">
                            {!! Form::label('password', 'Account Password', ['class' => 'adm-label']) !!}
                            {!! Form::password('password', array('class'=>'form-control adm-input', 'id'=>'password', 'placeholder'=>'••••••••')) !!}
                            <span class="adm-hint">Leave blank to keep existing password</span>
                            {!! APFrmErrHelp::showErrors($errors, 'password') !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'ceo') !!}">
                            {!! Form::label('ceo', 'Company CEO / Director', ['class' => 'adm-label']) !!}
                            {!! Form::text('ceo', null, array('class'=>'form-control adm-input', 'id'=>'ceo', 'placeholder'=>'Optional (e.g. John Doe)')) !!}
                            {!! APFrmErrHelp::showErrors($errors, 'ceo') !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'industry_id') !!}">
                            {!! Form::label('industry_id', 'Industry Domain', ['class' => 'adm-label']) !!}
                            {!! Form::select('industry_id', ['' => 'Select Industry']+$industries, null, array('class'=>'form-control adm-input', 'id'=>'industry_id')) !!}
                            {!! APFrmErrHelp::showErrors($errors, 'industry_id') !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'ownership_type') !!}">
                            {!! Form::label('ownership_type', 'Ownership Type', ['class' => 'adm-label']) !!}
                            {!! Form::select('ownership_type_id', ['' => 'Select Ownership type']+$ownershipTypes, null, array('class'=>'form-control adm-input', 'id'=>'ownership_type_id')) !!}
                            {!! APFrmErrHelp::showErrors($errors, 'ownership_type_id') !!}
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. About & Overview --}}
            <div class="adm-card">
                <div class="adm-card-header">
                    <i class="fa fa-file-text-o"></i>
                    <h3 class="adm-card-title">Company Overview & Details</h3>
                </div>
                <div class="form-group {!! APFrmErrHelp::hasError($errors, 'description') !!}">
                    {!! Form::label('description', 'About Company / Description', ['class' => 'adm-label']) !!}
                    {!! Form::textarea('description', null, array('class'=>'form-control', 'id'=>'description', 'placeholder'=>'Write detailed overview of the company...')) !!}
                    {!! APFrmErrHelp::showErrors($errors, 'description') !!}
                </div>
                <div class="form-group {!! APFrmErrHelp::hasError($errors, 'map') !!}">
                    {!! Form::label('map', 'Google Map Embed Code / Coordinates', ['class' => 'adm-label']) !!}
                    {!! Form::textarea('map', null, array('class'=>'form-control', 'id'=>'map', 'rows'=>'3', 'placeholder'=>'<iframe src="https://maps.google.com/..." ></iframe>')) !!}
                    <span class="adm-hint">Paste Google Maps embed &lt;iframe&gt; or coordinates link</span>
                    {!! APFrmErrHelp::showErrors($errors, 'map') !!}
                </div>
            </div>

            {{-- 3. Location & Address --}}
            <div class="adm-card">
                <div class="adm-card-header">
                    <i class="fa fa-map-marker"></i>
                    <h3 class="adm-card-title">Location & Contact</h3>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'country_id') !!}">
                            {!! Form::label('country_id', 'Country', ['class' => 'adm-label']) !!}
                            {!! Form::select('country_id', ['' => 'Select Country']+$countries, old('country_id', (isset($company))? $company->country_id:$siteSetting->default_country_id), array('class'=>'form-control adm-input', 'id'=>'country_id')) !!}
                            {!! APFrmErrHelp::showErrors($errors, 'country_id') !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'state_id') !!}">
                            {!! Form::label('state_id', 'State', ['class' => 'adm-label']) !!}
                            <span id="default_state_dd">
                                {!! Form::select('state_id', ['' => 'Select State'], null, array('class'=>'form-control adm-input', 'id'=>'state_id')) !!}
                            </span>
                            {!! APFrmErrHelp::showErrors($errors, 'state_id') !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'city_id') !!}">
                            {!! Form::label('city_id', 'City', ['class' => 'adm-label']) !!}
                            <span id="default_city_dd">
                                {!! Form::select('city_id', ['' => 'Select City'], null, array('class'=>'form-control adm-input', 'id'=>'city_id')) !!}
                            </span>
                            {!! APFrmErrHelp::showErrors($errors, 'city_id') !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'location') !!}">
                            {!! Form::label('location', 'Complete Street Address', ['class' => 'adm-label']) !!}
                            <div class="adm-input-icon-wrap">
                                <i class="fa fa-map-pin"></i>
                                {!! Form::text('location', null, array('class'=>'form-control adm-input', 'id'=>'location', 'placeholder'=>'e.g. 102 Cyber Park, Wardha Road')) !!}
                            </div>
                            {!! APFrmErrHelp::showErrors($errors, 'location') !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'phone') !!}">
                            {!! Form::label('phone', 'Phone Number', ['class' => 'adm-label']) !!}
                            <div class="adm-input-icon-wrap">
                                <i class="fa fa-phone"></i>
                                {!! Form::text('phone', null, array('class'=>'form-control adm-input', 'id'=>'phone', 'placeholder'=>'+91 9876543210')) !!}
                            </div>
                            {!! APFrmErrHelp::showErrors($errors, 'phone') !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'fax') !!}">
                            {!! Form::label('fax', 'Fax Number', ['class' => 'adm-label']) !!}
                            <div class="adm-input-icon-wrap">
                                <i class="fa fa-fax"></i>
                                {!! Form::text('fax', null, array('class'=>'form-control adm-input', 'id'=>'fax', 'placeholder'=>'Optional')) !!}
                            </div>
                            {!! APFrmErrHelp::showErrors($errors, 'fax') !!}
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. Social Media Profiles --}}
            <div class="adm-card">
                <div class="adm-card-header">
                    <i class="fa fa-share-alt"></i>
                    <h3 class="adm-card-title">Social Media Links</h3>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'linkedin') !!}">
                            {!! Form::label('linkedin', 'LinkedIn URL', ['class' => 'adm-label']) !!}
                            <div class="adm-input-icon-wrap">
                                <i class="fa fa-linkedin" style="color:#0A66C2;"></i>
                                {!! Form::text('linkedin', null, array('class'=>'form-control adm-input', 'id'=>'linkedin', 'placeholder'=>'https://linkedin.com/company/...')) !!}
                            </div>
                            {!! APFrmErrHelp::showErrors($errors, 'linkedin') !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'facebook') !!}">
                            {!! Form::label('facebook', 'Facebook Page URL', ['class' => 'adm-label']) !!}
                            <div class="adm-input-icon-wrap">
                                <i class="fa fa-facebook" style="color:#1877F2;"></i>
                                {!! Form::text('facebook', null, array('class'=>'form-control adm-input', 'id'=>'facebook', 'placeholder'=>'https://facebook.com/...')) !!}
                            </div>
                            {!! APFrmErrHelp::showErrors($errors, 'facebook') !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'twitter') !!}">
                            {!! Form::label('twitter', 'Twitter / X Profile', ['class' => 'adm-label']) !!}
                            <div class="adm-input-icon-wrap">
                                <i class="fa fa-twitter" style="color:#1DA1F2;"></i>
                                {!! Form::text('twitter', null, array('class'=>'form-control adm-input', 'id'=>'twitter', 'placeholder'=>'https://twitter.com/...')) !!}
                            </div>
                            {!! APFrmErrHelp::showErrors($errors, 'twitter') !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'google_plus') !!}">
                            {!! Form::label('google_plus', 'Google / YouTube URL', ['class' => 'adm-label']) !!}
                            <div class="adm-input-icon-wrap">
                                <i class="fa fa-google-plus" style="color:#EA4335;"></i>
                                {!! Form::text('google_plus', null, array('class'=>'form-control adm-input', 'id'=>'google_plus', 'placeholder'=>'https://youtube.com/...')) !!}
                            </div>
                            {!! APFrmErrHelp::showErrors($errors, 'google_plus') !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'pinterest') !!}">
                            {!! Form::label('pinterest', 'Pinterest URL', ['class' => 'adm-label']) !!}
                            <div class="adm-input-icon-wrap">
                                <i class="fa fa-pinterest" style="color:#E60023;"></i>
                                {!! Form::text('pinterest', null, array('class'=>'form-control adm-input', 'id'=>'pinterest', 'placeholder'=>'https://pinterest.com/...')) !!}
                            </div>
                            {!! APFrmErrHelp::showErrors($errors, 'pinterest') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN (4 Cols) --}}
        <div class="col-md-4">
            {{-- 5. Company Logo & Branding --}}
            <div class="adm-card">
                <div class="adm-card-header">
                    <i class="fa fa-picture-o"></i>
                    <h3 class="adm-card-title">Company Logo</h3>
                </div>
                <div class="form-group {!! APFrmErrHelp::hasError($errors, 'logo') !!}">
                    <div class="fileinput fileinput-new" data-provides="fileinput" style="width:100%;">
                        <div class="adm-logo-preview-box">
                            @if(isset($company) && !empty($company->logo))
                                <img src="{{ asset('company_logos/'.$company->logo) }}" alt="{{ $company->name }}" id="currentCompanyLogo" />
                            @else
                                <div class="fileinput-new thumbnail" style="border:none; background:transparent; margin:0; padding:0;">
                                    <img src="{{ asset('/') }}admin_assets/no-image.png" alt="" />
                                </div>
                            @endif
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 100%; max-height: 140px; border:none; background:transparent;"></div>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <span class="btn default btn-file" style="flex: 1; border: 1.5px solid #CBD5E1; border-radius: 8px; font-weight: 600; padding: 8px 14px; background: #FFFFFF; font-size: 13px;">
                                <span class="fileinput-new"><i class="fa fa-upload"></i> Upload Logo</span>
                                <span class="fileinput-exists"><i class="fa fa-refresh"></i> Change</span>
                                {!! Form::file('logo', null, array('id'=>'logo')) !!}
                            </span>
                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput" style="border-radius: 8px; padding: 8px 14px; font-size: 13px;">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </div>
                    <span class="adm-hint">Recommended dimensions: 300x300px (PNG, JPG)</span>
                    {!! APFrmErrHelp::showErrors($errors, 'logo') !!}
                </div>
            </div>

            {{-- 6. Operational Specifications --}}
            <div class="adm-card">
                <div class="adm-card-header">
                    <i class="fa fa-building"></i>
                    <h3 class="adm-card-title">Specifications</h3>
                </div>
                <div class="form-group {!! APFrmErrHelp::hasError($errors, 'website') !!}">
                    {!! Form::label('website', 'Official Website', ['class' => 'adm-label']) !!}
                    <div class="adm-input-icon-wrap">
                        <i class="fa fa-globe"></i>
                        {!! Form::text('website', null, array('class'=>'form-control adm-input', 'id'=>'website', 'placeholder'=>'https://company.com')) !!}
                    </div>
                    {!! APFrmErrHelp::showErrors($errors, 'website') !!}
                </div>
                <div class="form-group {!! APFrmErrHelp::hasError($errors, 'established_in') !!}">
                    {!! Form::label('established_in', 'Established Year', ['class' => 'adm-label']) !!}
                    {!! Form::select('established_in', ['' => 'Select Established Year']+MiscHelper::getEstablishedIn(), null, array('class'=>'form-control adm-input', 'id'=>'established_in')) !!}
                    {!! APFrmErrHelp::showErrors($errors, 'established_in') !!}
                </div>
                <div class="form-group {!! APFrmErrHelp::hasError($errors, 'no_of_employees') !!}">
                    {!! Form::label('no_of_employees', 'Team / Company Size', ['class' => 'adm-label']) !!}
                    {!! Form::select('no_of_employees', ['' => 'Select num. of employees']+MiscHelper::getNumEmployees(), null, array('class'=>'form-control adm-input', 'id'=>'no_of_employees')) !!}
                    {!! APFrmErrHelp::showErrors($errors, 'no_of_employees') !!}
                </div>
                <div class="form-group {!! APFrmErrHelp::hasError($errors, 'no_of_offices') !!}">
                    {!! Form::label('no_of_offices', 'Number of Branches / Offices', ['class' => 'adm-label']) !!}
                    {!! Form::select('no_of_offices', ['' => 'Select num. of offices']+MiscHelper::getNumOffices(), null, array('class'=>'form-control adm-input', 'id'=>'no_of_offices')) !!}
                    {!! APFrmErrHelp::showErrors($errors, 'no_of_offices') !!}
                </div>
            </div>

            {{-- 7. Subscription Package --}}
            <div class="adm-card">
                <div class="adm-card-header">
                    <i class="fa fa-gift"></i>
                    <h3 class="adm-card-title">Subscription & Package</h3>
                </div>

                @if(isset($company) && $company->package_id > 0)
                <div class="adm-pkg-banner">
                    <div class="adm-pkg-title">
                        <i class="fa fa-check-circle"></i> Active Plan: {{ $company->getPackage('package_title') }}
                    </div>
                    <div class="adm-pkg-meta">
                        <span><i class="fa fa-calendar"></i> {{ $company->package_start_date ? $company->package_start_date->format('d M, Y') : 'N/A' }} - {{ $company->package_end_date ? $company->package_end_date->format('d M, Y') : 'N/A' }}</span>
                        <span><strong>{{ (int)$company->availed_jobs_quota }} / {{ (int)$company->jobs_quota }} Jobs</strong></span>
                    </div>
                    @php
                        $quotaPct = ($company->jobs_quota > 0) ? round(($company->availed_jobs_quota / $company->jobs_quota) * 100) : 0;
                    @endphp
                    <div class="adm-pkg-progress-bg">
                        <div class="adm-pkg-progress-bar" style="width: {{ min(100, $quotaPct) }}%;"></div>
                    </div>
                </div>
                @endif

                <div class="form-group {!! APFrmErrHelp::hasError($errors, 'company_package_id') !!}">
                    {!! Form::label('company_package_id', 'Assign / Upgrade Package', ['class' => 'adm-label']) !!}
                    {!! Form::select('company_package_id', ['' => 'Select Package']+$packages, null, array('class'=>'form-control adm-input', 'id'=>'company_package_id')) !!}
                    {!! APFrmErrHelp::showErrors($errors, 'company_package_id') !!}
                </div>
            </div>

            {{-- 8. Visibility & Status Settings --}}
            <div class="adm-card">
                <div class="adm-card-header">
                    <i class="fa fa-toggle-on"></i>
                    <h3 class="adm-card-title">Status & Visibility</h3>
                </div>

                {{-- Is Active --}}
                <div class="form-group {!! APFrmErrHelp::hasError($errors, 'is_active') !!}" style="margin-bottom:18px;">
                    {!! Form::label('is_active', 'Company Status', ['class' => 'adm-label']) !!}
                    <?php
                    $is_active_1 = 'checked="checked"';
                    $is_active_2 = '';
                    if (old('is_active', ((isset($company)) ? $company->is_active : 1)) == 0) {
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

                {{-- Is Featured --}}
                <div class="form-group {!! APFrmErrHelp::hasError($errors, 'is_featured') !!}">
                    {!! Form::label('is_featured', 'Featured Placement', ['class' => 'adm-label']) !!}
                    <?php
                    $is_featured_1 = '';
                    $is_featured_2 = 'checked="checked"';
                    if (old('is_featured', ((isset($company)) ? $company->is_featured : 0)) == 1) {
                        $is_featured_1 = 'checked="checked"';
                        $is_featured_2 = '';
                    }
                    ?>
                    <div class="adm-radio-box">
                        <label class="adm-radio-option" for="featured">
                            <input id="featured" name="is_featured" type="radio" value="1" {{$is_featured_1}}>
                            <span style="color:#D97706;"><i class="fa fa-bolt"></i> Featured</span>
                        </label>
                        <label class="adm-radio-option" for="not_featured">
                            <input id="not_featured" name="is_featured" type="radio" value="0" {{$is_featured_2}}>
                            <span style="color:#64748B;">Standard</span>
                        </label>
                    </div>
                    {!! APFrmErrHelp::showErrors($errors, 'is_featured') !!}
                </div>
            </div>

            {{-- Action Submit Box --}}
            <div class="adm-card" style="text-align: center;">
                {!! Form::button('<i class="fa fa-check"></i> Save & Update Company', array('class'=>'adm-btn-save', 'type'=>'submit', 'style'=>'width:100%; justify-content:center; padding:14px !important; font-size:15px !important;')) !!}
                <div style="margin-top: 12px;">
                    <a href="{{ route('list.companies') }}" style="color: #64748B; font-size: 13px; font-weight: 600; text-decoration: none;">
                        <i class="fa fa-arrow-left"></i> Back to Companies List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@include('admin.shared.tinyMCEFront') 
<script type="text/javascript">
    $(document).ready(function () {
        $('#country_id').on('change', function (e) {
            e.preventDefault();
            filterDefaultStates(0);
        });
        $(document).on('change', '#state_id', function (e) {
            e.preventDefault();
            filterDefaultCities(0);
        });
        filterDefaultStates(<?php echo old('state_id', (isset($company)) ? $company->state_id : 0); ?>);
    });
    function filterDefaultStates(state_id)
    {
        var country_id = $('#country_id').val();
        if (country_id != '') {
            $.post("{{ route('filter.default.states.dropdown') }}", {country_id: country_id, state_id: state_id, _method: 'POST', _token: '{{ csrf_token() }}'})
                    .done(function (response) {
                        $('#default_state_dd').html(response);
                        filterDefaultCities(<?php echo old('city_id', (isset($company)) ? $company->city_id : 0); ?>);
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
</script>
@endpush