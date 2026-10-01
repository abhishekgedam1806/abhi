@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end --> 
<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title'=>__('Frequently asked questions')])
<!-- Inner Page Title end -->
<!-- Page Title End -->
<div class="listpgWraper">
    <div class="container"> 
        <!--Question-->
        <div class="faqs">
            <div class="panel-group" id="accordion">
                <h3>&nbsp;</h3>
                @if(isset($faqs) && count($faqs))
                @foreach($faqs as $faq)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" class="collapsed" href="#collapse{{ $faq->id }}">{!! $faq->faq_question !!}</a> </h4>
                    </div>
                    <div id="collapse{{ $faq->id }}" class="panel-collapse collapse">
                        <div class="panel-body">{!! $faq->faq_answer !!}</div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
        
        <!-- Signature Design Block -->
        <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 14px; padding: 24px 36px; margin-top: 30px; box-shadow: 0 2px 12px rgba(15, 23, 42, 0.04); text-align: center;">
            <h6 style="color: #0F172A; font-weight: 700; font-size: 16px; margin-bottom: 5px;">JobNBiz Team</h6>
            <p style="color: #64748B; font-size: 13px; margin: 0;">{{__('Committed to your career success')}}.</p>
            <p style="color: #64748B; font-size: 13px; margin-top: 5px;">{{__('Last Updated')}}: {{ \Carbon\Carbon::now()->format('F j, Y') }}</p>
        </div>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">{!! $siteSetting->cms_page_ad !!}</div>
            <div class="col-md-3"></div>
        </div>
    </div>
</div>
@endsection