{!! APFrmErrHelp::showOnlyErrorsNotice($errors) !!}
@include('flash::message')
<div class="form-body">
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'package_title') !!}"> {!! Form::label('package_title', 'Package Title', ['class' => 'bold']) !!}
        {!! Form::text('package_title', null, array('class'=>'form-control', 'id'=>'package_title', 'placeholder'=>'Package Title')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'package_title') !!} </div>
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'package_price') !!}"> {!! Form::label('package_price', 'Package Price(In USD)', ['class' => 'bold']) !!}
        {!! Form::text('package_price', null, array('class'=>'form-control', 'id'=>'package_price', 'placeholder'=>'Package Price')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'package_price') !!} </div>
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'package_num_days') !!}"> {!! Form::label('package_num_days', 'Package num days', ['class' => 'bold']) !!}
        {!! Form::text('package_num_days', null, array('class'=>'form-control', 'id'=>'package_num_days', 'placeholder'=>'Package num days')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'package_num_days') !!} </div>
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'package_num_listings') !!}"> {!! Form::label('package_num_listings', 'Package num listings*', ['class' => 'bold']) !!}
        {!! Form::text('package_num_listings', null, array('class'=>'form-control', 'id'=>'package_num_listings', 'placeholder'=>'Package num listings')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'package_num_listings') !!} 
        *On how many jobs a job seeker can apply<br />
        **How many jobs an employer can post<br />
        ***How many businesses an owner can list </div>

    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'package_for') !!}">
        {!! Form::label('package_for', 'Package for?', ['class' => 'bold']) !!}
        <div class="radio-list">
            <?php
            $pkgFor = old('package_for', ((isset($package)) ? $package->package_for : 'business'));
            ?>
            <label class="radio-inline">
                <input id="business" name="package_for" type="radio" value="business" {{ $pkgFor == 'business' ? 'checked="checked"' : '' }}>
                Business Owner </label>
            <label class="radio-inline">
                <input id="employer" name="package_for" type="radio" value="employer" {{ $pkgFor == 'employer' ? 'checked="checked"' : '' }}>
                Employer </label>
            <label class="radio-inline">
                <input id="job_seeker" name="package_for" type="radio" value="job_seeker" {{ $pkgFor == 'job_seeker' ? 'checked="checked"' : '' }}>
                Job Seeker </label>
        </div>
        {!! APFrmErrHelp::showErrors($errors, 'package_for') !!}
    </div>

    <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:10px;padding:16px;margin:20px 0;">
        <h4 style="font-size:14px;font-weight:700;margin-top:0;color:#0F172A;">Business Specific Limitations & Features</h4>
        <div class="row">
            <div class="col-md-6 form-group">
                <label class="bold">Max Services per Listing</label>
                {!! Form::number('package_num_services', isset($package) ? $package->package_num_services : 5, ['class'=>'form-control', 'min'=>1]) !!}
            </div>
            <div class="col-md-6 form-group">
                <label class="bold">Max Photo Gallery Uploads</label>
                {!! Form::number('package_num_photos', isset($package) ? $package->package_num_photos : 3, ['class'=>'form-control', 'min'=>1]) !!}
            </div>
            <div class="col-md-4 form-group">
                <label class="bold" style="display:block;">Featured Listing?</label>
                <label><input type="checkbox" name="is_featured" value="1" {{ (isset($package) && $package->is_featured) ? 'checked' : '' }}> Yes, Featured on Top</label>
            </div>
            <div class="col-md-4 form-group">
                <label class="bold" style="display:block;">WhatsApp / Call Leads?</label>
                <label><input type="checkbox" name="has_whatsapp_leads" value="1" {{ (!isset($package) || $package->has_whatsapp_leads) ? 'checked' : '' }}> Enable Direct Leads</label>
            </div>
            <div class="col-md-4 form-group">
                <label class="bold" style="display:block;">Verified Trust Badge?</label>
                <label><input type="checkbox" name="has_verified_badge" value="1" {{ (isset($package) && $package->has_verified_badge) ? 'checked' : '' }}> Instant Verified Badge</label>
            </div>
        </div>
    </div>
    <div class="form-actions"> {!! Form::button('Update <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>', array('class'=>'btn btn-large btn-primary', 'type'=>'submit')) !!} </div>
</div>