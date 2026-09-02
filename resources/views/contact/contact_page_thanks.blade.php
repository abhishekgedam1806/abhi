@extends('layouts.app')

@push('styles')
<style>
.thanks-wrap {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    background: #F8FAFC;
    padding: 60px 0;
}
.thanks-card {
    background: #FFFFFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 18px;
    padding: 48px 36px;
    max-width: 680px;
    margin: 0 auto;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
}
.thanks-icon {
    width: 72px;
    height: 72px;
    background: #ECFDF5;
    color: #03855c;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    margin-bottom: 20px;
    box-shadow: 0 4px 12px rgba(3,133,92,0.15);
}
</style>
@endpush

@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->

<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title' => __('Message Sent Successfully')])
<!-- Inner Page Title end -->

<div class="thanks-wrap">
    <div class="container">
        <div class="thanks-card">
            <div class="thanks-icon">
                <i class="fa fa-check"></i>
            </div>
            <h1 style="font-size: 26px; font-weight: 800; color: #0F172A; margin-bottom: 12px;">
                Thank You for Reaching Out!
            </h1>
            <p style="font-size: 15px; color: #64748B; line-height: 1.6; margin-bottom: 24px;">
                We have received your support request. Our customer support team will review your inquiry and get back to you via email or phone shortly.
            </p>
            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 16px; margin-bottom: 28px; text-align: left; font-size: 13.5px; color: #334155;">
                <div style="font-weight: 700; margin-bottom: 6px;"><i class="fa fa-info-circle text-primary"></i> Need urgent assistance?</div>
                <div>You can directly connect with our helpline: <strong>+91 {{ $siteSetting->site_phone_primary ?? '7038424139' }}</strong> or message us on WhatsApp.</div>
            </div>
            <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ url('/') }}" class="btn btn-default" style="border-radius: 8px; font-weight: 700; padding: 10px 22px;">
                    <i class="fa fa-home"></i> Return to Home
                </a>
                <a href="{{ url('/jobs') }}" class="btn btn-primary" style="background: #2563EB; border-color: #2563EB; border-radius: 8px; font-weight: 700; padding: 10px 24px;">
                    <i class="fa fa-search"></i> Browse Jobs
                </a>
                @php
                    $rawPhone = preg_replace('/[^0-9]/', '', $siteSetting->site_phone_primary ?? '7038424139');
                    $waPhone = (strlen($rawPhone) == 10) ? '91' . $rawPhone : $rawPhone;
                @endphp
                <a href="https://api.whatsapp.com/send?phone={{ $waPhone }}&text=Hi%20Jobs%20Portal,%20I%20just%20submitted%20a%20support%20request" target="_blank" class="btn btn-success" style="background: #16A34A; border-color: #16A34A; border-radius: 8px; font-weight: 700; padding: 10px 22px;">
                    <i class="fa fa-whatsapp"></i> Chat on WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Footer start -->
@include('includes.footer')
<!-- Footer end -->
@endsection