@extends('layouts.app')

@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end --> 

<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title' => $cmsContent->page_title])
<!-- Inner Page Title end -->

<div class="about-wraper" style="background: #F8FAFC; padding: 45px 0 65px;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 14px; padding: 36px 42px; box-shadow: 0 2px 12px rgba(15, 23, 42, 0.04); font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; color: #334155; line-height: 1.8; font-size: 15px;">
                    {!! $cmsContent->page_content !!}
                    
                    <!-- Signature Design Block -->
                    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #E2E8F0; text-align: left;">
                        <h6 style="color: #0F172A; font-weight: 700; font-size: 16px; margin-bottom: 5px;">JobNBiz Team</h6>
                        <p style="color: #64748B; font-size: 13px; margin: 0;">{{__('Committed to your career success')}}.</p>
                        <p style="color: #64748B; font-size: 13px; margin-top: 5px;">{{__('Last Updated')}}: {{ \Carbon\Carbon::now()->format('F j, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        @if(!empty($siteSetting->cms_page_ad))
        <div class="row" style="margin-top: 24px;">
            <div class="col-md-3"></div>
            <div class="col-md-6 text-center">{!! $siteSetting->cms_page_ad !!}</div>
            <div class="col-md-3"></div>
        </div>
        @endif
    </div>  
</div>

@include('includes.footer')
@endsection