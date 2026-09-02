<?php
$lang = config('default_lang');
$direction = MiscHelper::getLangDirection($lang);
?>
@extends('admin.layouts.admin_layout')
@section('content')
<style>
.site-settings-wrap {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}
.site-settings-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    background: #F8FAFC;
    padding: 8px;
    border-radius: 12px;
    border: 1px solid #E2E8F0;
    margin-bottom: 24px;
    list-style: none;
}
.site-settings-tabs > li {
    margin: 0 !important;
    float: none !important;
}
.site-settings-tabs > li > a {
    display: inline-flex !important;
    align-items: center;
    gap: 7px;
    padding: 9px 16px !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    color: #475569 !important;
    background: transparent !important;
    border: 1px solid transparent !important;
    border-radius: 8px !important;
    text-decoration: none !important;
    transition: all 0.15s ease !important;
    margin: 0 !important;
    cursor: pointer;
}
.site-settings-tabs > li > a:hover {
    background: #FFFFFF !important;
    color: #0F172A !important;
    border-color: #CBD5E1 !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
.site-settings-tabs > li.active > a,
.site-settings-tabs > li.active > a:focus,
.site-settings-tabs > li.active > a:hover {
    background: #2563EB !important;
    color: #FFFFFF !important;
    border-color: #2563EB !important;
    box-shadow: none !important;
}
.site-settings-tabs > li.active > a i {
    color: #FFFFFF !important;
}
.site-settings-tabs > li > a i {
    font-size: 13px;
    color: #64748B;
}

.portlet.light.bordered {
    border: 1px solid #E2E8F0 !important;
    border-radius: 14px !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02) !important;
    background: #FFFFFF !important;
    padding: 20px 24px !important;
}
.portlet-title {
    border-bottom: 1px solid #F1F5F9 !important;
    padding-bottom: 14px !important;
    margin-bottom: 20px !important;
}
.portlet-title .caption-subject {
    font-size: 16px !important;
    font-weight: 800 !important;
    color: #0F172A !important;
}
</style>

<div class="page-content-wrapper site-settings-wrap"> 
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content"> 
        <!-- BEGIN PAGE HEADER--> 
        <!-- BEGIN PAGE BAR -->
        <div class="page-bar" style="background: transparent; border: none; box-shadow: none; padding: 0 0 16px 0;">
            <ul class="page-breadcrumb" style="padding: 0; margin: 0; font-size: 13px;">
                <li> <a href="{{ route('admin.home') }}" style="color: #64748B; text-decoration: none;"><i class="fa fa-home"></i> Home</a> <i class="fa fa-angle-right" style="color: #CBD5E1; margin: 0 6px;"></i> </li>        
                <li> <span style="color: #0F172A; font-weight: 700;">Edit Site Settings</span> </li>
            </ul>
        </div>
        <!-- END PAGE BAR --> 
        
        @include('flash::message')
        
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo" style="display: flex; align-items: center; gap: 8px;"> 
                            <i class="icon-settings font-red-sunglo" style="font-size: 18px; color: #2563EB;"></i> 
                            <span class="caption-subject bold uppercase" style="color: #0F172A;">Site Setting Form</span> 
                        </div>
                    </div>
                    <div class="portlet-body form">          
                        <ul class="site-settings-tabs nav nav-tabs" role="tablist">              
                            <li class="active"> <a href="#site" data-toggle="tab" aria-expanded="true"><i class="fa fa-globe"></i> Site</a> </li>              
                            <li> <a href="#email" data-toggle="tab" aria-expanded="false"><i class="fa fa-envelope-o"></i> Email</a> </li>
                            <li> <a href="#social" data-toggle="tab" aria-expanded="false"><i class="fa fa-share-alt"></i> Social Media Links (Profiles)</a> </li>
                            <li> <a href="#ads" data-toggle="tab" aria-expanded="false"><i class="fa fa-bullhorn"></i> Manage Ads</a> </li>
                            <li> <a href="#captcha" data-toggle="tab" aria-expanded="false"><i class="fa fa-shield"></i> Captcha</a> </li>
                            <li> <a href="#socialMediaLogin" data-toggle="tab" aria-expanded="false"><i class="fa fa-key"></i> Social Login API Keys (OAuth)</a> </li>
                            <li> <a href="#paymentGateways" data-toggle="tab" aria-expanded="false"><i class="fa fa-credit-card"></i> Payment Gateways</a> </li>
                            <li> <a href="#homePageSlider" data-toggle="tab" aria-expanded="false"><i class="fa fa-sliders"></i> Home Page Slider</a> </li>
                            <li> <a href="#heroBanner" data-toggle="tab" aria-expanded="false"><i class="fa fa-picture-o"></i> Hero Banner</a> </li>
                            <li> <a href="#mailChimp" data-toggle="tab" aria-expanded="false"><i class="fa fa-paper-plane-o"></i> Mail Chimp</a> </li>              
                        </ul>
                        {!! Form::model($siteSetting, array('method' => 'put', 'route' => array('update.site.setting'), 'class' => 'form', 'files'=>true)) !!}
                        <div class="tab-content" style="padding-top: 10px;">              
                            <div class="tab-pane fade active in" id="site"> @include('admin.site_setting.forms.form') </div>
                            <div class="tab-pane fade" id="email"> @include('admin.site_setting.forms.siteEmailSetting_form') </div>
                            <div class="tab-pane fade" id="social"> @include('admin.site_setting.forms.siteSocialSetting_form') </div>
                            <div class="tab-pane fade" id="ads"> @include('admin.site_setting.forms.siteAds_form') </div>
                            <div class="tab-pane fade" id="captcha"> @include('admin.site_setting.forms.captchaSetting_form') </div>
                            <div class="tab-pane fade" id="socialMediaLogin"> @include('admin.site_setting.forms.socialMediaLoginSetting_form') </div>
                            <div class="tab-pane fade" id="paymentGateways"> @include('admin.site_setting.forms.paymentGatewaysSetting_form') </div>
                            <div class="tab-pane fade" id="homePageSlider"> @include('admin.site_setting.forms.homePageSliderSetting_form') </div>
                            <div class="tab-pane fade" id="heroBanner"> @include('admin.site_setting.forms.heroBannerSetting_form') </div>
                            <div class="tab-pane fade" id="mailChimp"> @include('admin.site_setting.forms.mailChimpSetting_form') </div>
                        </div>
                        <div class="form-actions" style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #F1F5F9; background: transparent;">
                            <button type="submit" class="btn btn-primary" style="background: #2563EB; border-color: #2563EB; font-weight: 700; padding: 10px 24px; border-radius: 8px; box-shadow: 0 2px 6px rgba(37,99,235,0.25);">
                                Update Settings <i class="fa fa-arrow-circle-right" aria-hidden="true" style="margin-left: 6px;"></i>
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- END CONTENT BODY --> 
    </div>
</div>
@endsection
@push('scripts')
@include('admin.shared.tinyMCE')
<script>
$(document).ready(function() {
    var hash = window.location.hash;
    if (hash) {
        $('.site-settings-tabs a[href="' + hash + '"]').tab('show');
    }
    $('.site-settings-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });
});
</script>
@endpush