@extends('layouts.app')
@section('content') 
<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 
<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=> isset($job) ? __('Edit Job Posting') : __('Post a New Job')]) 
<!-- Inner Page Title end -->
<div class="listpgWraper job-post-edit-redesign" style="background: #F8FAFC; padding: 36px 0 60px; min-height: 85vh; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <div class="container" style="max-width: 1320px;">
        @include('flash::message') 
        <div class="row">
            @include('includes.company_dashboard_menu')

            <div class="col-lg-9 col-md-8"> 
                @include('job.inc.job')
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
@endsection
@push('styles')
<style type="text/css">
    .job-post-edit-redesign .form-control {
        border: 1.5px solid #CBD5E1 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
        font-size: 13.5px !important;
        color: #0F172A !important;
        background-color: #FFFFFF !important;
        box-shadow: none !important;
        transition: all 0.15s ease-in-out !important;
        height: auto !important;
    }
    .job-post-edit-redesign .form-control:focus {
        border-color: #2563EB !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
        outline: none !important;
    }
    .job-post-edit-redesign select.form-control {
        height: 44px !important;
    }
    .job-post-edit-redesign label {
        font-size: 13px !important;
        font-weight: 700 !important;
        color: #334155 !important;
        margin-bottom: 6px !important;
        display: inline-block !important;
    }
    .job-post-edit-redesign .formrow {
        margin-bottom: 18px !important;
    }
    .job-post-edit-redesign .post-job-card {
        background: #FFFFFF;
        border: 1.5px solid #E2E8F0;
        border-radius: 18px;
        padding: 26px 28px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        margin-bottom: 24px;
    }
    .job-post-edit-redesign .select2-container--default .select2-selection--multiple {
        border: 1.5px solid #CBD5E1 !important;
        border-radius: 10px !important;
        min-height: 44px !important;
        padding: 4px 8px !important;
    }
    .job-post-edit-redesign .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #2563EB !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
    }
    .job-post-edit-redesign .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #EFF6FF !important;
        border: 1px solid #BFDBFE !important;
        color: #1D4ED8 !important;
        border-radius: 6px !important;
        font-weight: 600 !important;
        font-size: 12.5px !important;
        padding: 2px 8px !important;
    }
</style>
@endpush