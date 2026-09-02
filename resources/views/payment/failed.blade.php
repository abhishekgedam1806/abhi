@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->

<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title' => __('Payment Failed')])
<!-- Inner Page Title end -->

<div class="listpgWraper" style="background: #F8FAFC; padding: 60px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-12">
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 20px; padding: 40px; text-align: center; box-shadow: 0 10px 30px rgba(15,23,42,0.06);">
                    
                    {{-- Failed Icon --}}
                    <div style="width: 72px; height: 72px; border-radius: 50%; background: #FEF2F2; border: 2px solid #FECACA; color: #EF4444; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; margin-bottom: 20px;">
                        <i class="fa fa-times"></i>
                    </div>

                    <h2 style="font-size: 24px; font-weight: 800; color: #0F172A; margin-bottom: 8px;">
                        {{ __('Payment Incomplete / Failed') }}
                    </h2>
                    <p style="color: #64748B; font-size: 14.5px; margin-bottom: 24px;">
                        {{ __('We could not verify or complete your transaction. If your account was debited, the amount will be refunded automatically by your bank.') }}
                    </p>

                    {{-- Failure Details Box --}}
                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 20px; text-align: left; margin-bottom: 28px;">
                        <div style="display: flex; justify-content: space-between; font-size: 13.5px; margin-bottom: 8px; color: #475569;">
                            <span>{{ __('Order Number:') }}</span>
                            <strong style="color: #0F172A;">{{ $order->order_number }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 13.5px; margin-bottom: 8px; color: #475569;">
                            <span>{{ __('Package:') }}</span>
                            <strong>{{ $order->package_title }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 13.5px; margin-bottom: 8px; color: #475569;">
                            <span>{{ __('Amount:') }}</span>
                            <strong style="color: #EF4444;">₹{{ number_format($order->total_amount, 2) }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 13.5px; color: #475569;">
                            <span>{{ __('Status:') }}</span>
                            <span class="badge" style="background: #EF4444; color: #fff; font-weight: 700; padding: 4px 8px; border-radius: 4px;">{{ __('FAILED / PENDING') }}</span>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                        <a href="{{ route('payment.checkout', ['id' => $order->package_id]) }}" class="btn btn-primary" style="background: #2563EB; border: none; font-weight: 700; border-radius: 10px; padding: 10px 24px;">
                            <i class="fa fa-refresh"></i> {{ __('Try Again') }}
                        </a>

                        @if(Auth::guard('company')->check())
                            <a href="{{ route('company.home') }}" class="btn btn-outline-secondary" style="font-weight: 700; border-radius: 10px; padding: 10px 20px;">
                                <i class="fa fa-dashboard"></i> {{ __('Back to Dashboard') }}
                            </a>
                        @else
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary" style="font-weight: 700; border-radius: 10px; padding: 10px 20px;">
                                <i class="fa fa-home"></i> {{ __('Back to Home') }}
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.footer')
@endsection
