@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->

<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title' => __('Payment Successful')])
<!-- Inner Page Title end -->

<div class="listpgWraper" style="background: #F8FAFC; padding: 60px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-12">
                <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 20px; padding: 40px; text-align: center; box-shadow: 0 10px 30px rgba(15,23,42,0.06);">
                    
                    {{-- Success Icon --}}
                    <div style="width: 72px; height: 72px; border-radius: 50%; background: #ECFDF5; border: 2px solid #A7F3D0; color: #10B981; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; margin-bottom: 20px;">
                        <i class="fa fa-check"></i>
                    </div>

                    <h2 style="font-size: 24px; font-weight: 800; color: #0F172A; margin-bottom: 8px;">
                        {{ __('Payment Successful!') }}
                    </h2>
                    <p style="color: #64748B; font-size: 14.5px; margin-bottom: 24px;">
                        {{ __('Thank you for your purchase. Your package and job posting quota has been activated immediately.') }}
                    </p>

                    {{-- Receipt Box --}}
                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 20px; text-align: left; margin-bottom: 28px;">
                        <div style="display: flex; justify-content: space-between; font-size: 13.5px; margin-bottom: 8px; color: #475569;">
                            <span>{{ __('Order Number:') }}</span>
                            <strong style="color: #0F172A;">{{ $order->order_number }}</strong>
                        </div>
                        @if($payment && $payment->gateway_payment_id)
                        <div style="display: flex; justify-content: space-between; font-size: 13.5px; margin-bottom: 8px; color: #475569;">
                            <span>{{ __('Payment ID:') }}</span>
                            <strong style="color: #0F172A;">{{ $payment->gateway_payment_id }}</strong>
                        </div>
                        @endif
                        <div style="display: flex; justify-content: space-between; font-size: 13.5px; margin-bottom: 8px; color: #475569;">
                            <span>{{ __('Package:') }}</span>
                            <strong style="color: #2563EB;">{{ $order->package_title }} ({{ ucfirst($order->package_type) }})</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 13.5px; margin-bottom: 8px; color: #475569;">
                            <span>{{ __('Amount Paid:') }}</span>
                            <strong style="color: #03855c;">₹{{ number_format($order->total_amount, 2) }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 13.5px; margin-bottom: 8px; color: #475569;">
                            <span>{{ __('Payment Method:') }}</span>
                            <strong style="color: #0F172A; text-transform: uppercase;">{{ $payment->payment_method ?? 'ONLINE / UPI' }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 13.5px; color: #475569;">
                            <span>{{ __('Status:') }}</span>
                            <span class="badge" style="background: #10B981; color: #fff; font-weight: 700; padding: 4px 8px; border-radius: 4px;">{{ __('PAID & ACTIVE') }}</span>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                        <a href="{{ route('payment.invoice', ['order_number' => $order->order_number]) }}" target="_blank" class="btn btn-outline-secondary" style="font-weight: 700; border-radius: 10px; padding: 10px 20px;">
                            <i class="fa fa-file-text-o"></i> {{ __('Download Invoice') }}
                        </a>

                        @if(Auth::guard('company')->check())
                            <a href="{{ route('post.job') }}" class="btn btn-success" style="background: #10B981; border: none; font-weight: 700; border-radius: 10px; padding: 10px 20px;">
                                <i class="fa fa-plus-circle"></i> {{ __('Post a Job Now') }}
                            </a>
                            <a href="{{ route('company.home') }}" class="btn btn-primary" style="background: #2563EB; border: none; font-weight: 700; border-radius: 10px; padding: 10px 20px;">
                                <i class="fa fa-dashboard"></i> {{ __('Go to Dashboard') }}
                            </a>
                        @else
                            <a href="{{ route('home') }}" class="btn btn-primary" style="background: #2563EB; border: none; font-weight: 700; border-radius: 10px; padding: 10px 20px;">
                                <i class="fa fa-home"></i> {{ __('Go to Homepage') }}
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
