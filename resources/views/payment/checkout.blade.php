@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->

<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title' => __('Order Summary & Checkout')])
<!-- Inner Page Title end -->

<div class="listpgWraper" style="background: #F8FAFC; padding: 50px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-8 col-12">
                <div class="checkout-card" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 32px; box-shadow: 0 4px 20px rgba(15,23,42,0.06);">
                    
                    {{-- Header Badge --}}
                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #F1F5F9; padding-bottom: 18px; margin-bottom: 24px;">
                        <div>
                            <span style="font-size: 11.5px; font-weight: 700; color: #2563EB; background: #EFF6FF; padding: 4px 10px; border-radius: 50px; text-transform: uppercase; letter-spacing: 0.5px;">
                                <i class="fa fa-shield"></i> {{ __('Secure Checkout') }}
                            </span>
                            <h2 style="font-size: 22px; font-weight: 800; color: #0F172A; margin-top: 8px; margin-bottom: 0;">
                                {{ __('Order Summary') }}
                            </h2>
                        </div>
                        <div style="text-align: right;">
                            <span style="font-size: 12px; color: #64748B;">{{ __('Order #') }}</span>
                            <div style="font-size: 13.5px; font-weight: 700; color: #1E293B;">{{ $order->order_number }}</div>
                        </div>
                    </div>

                    {{-- Package Details Box --}}
                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div>
                                <span style="font-size: 12px; font-weight: 700; color: #03855c; text-transform: uppercase;">
                                    {{ ucfirst($order->package_type) }} Package
                                </span>
                                <h3 style="font-size: 18px; font-weight: 800; color: #0F172A; margin: 4px 0 8px 0;">
                                    {{ $order->package_title }}
                                </h3>
                                <ul style="list-style: none; padding: 0; margin: 0; font-size: 13px; color: #475569;">
                                    <li style="margin-bottom: 4px;"><i class="fa fa-check-circle" style="color: #10B981;"></i> <strong>{{ $package->package_num_days }}</strong> {{ __('Days Validity') }}</li>
                                    <li style="margin-bottom: 4px;"><i class="fa fa-check-circle" style="color: #10B981;"></i> <strong>{{ $package->package_num_listings }}</strong> {{ __('Job Posting Quota') }}</li>
                                    @if(!empty($package->has_verified_badge))
                                    <li style="margin-bottom: 4px;"><i class="fa fa-check-circle" style="color: #10B981;"></i> {{ __('Verified Employer Badge') }}</li>
                                    @endif
                                </ul>
                            </div>
                            <div style="text-align: right;">
                                <div id="display-total-header" style="font-size: 24px; font-weight: 900; color: #2563EB;">
                                    ₹{{ number_format($order->total_amount, 2) }}
                                </div>
                                <span style="font-size: 12px; color: #64748B;">{{ __('Inclusive of all taxes') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Promo Code / Coupon Section --}}
                    <div style="background: #F8FAFC; border: 1px dashed #CBD5E1; border-radius: 12px; padding: 16px 18px; margin-bottom: 22px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                            <label style="font-size: 13px; font-weight: 700; color: #1E293B; margin: 0;">
                                <i class="fa fa-ticket text-primary"></i> {{ __('Have a Promo Code or Coupon?') }}
                            </label>
                            <span id="coupon-applied-badge" style="display: {{ $order->coupon_code ? 'inline-block' : 'none' }}; font-size: 11px; font-weight: 700; color: #059669; background: #ECFDF5; padding: 3px 8px; border-radius: 50px; border: 1px solid #A7F3D0;">
                                <i class="fa fa-check-circle"></i> {{ __('Coupon Applied') }}
                            </span>
                        </div>
                        
                        <div style="display: flex; gap: 8px;">
                            <input type="text" id="coupon_input" placeholder="{{ __('Enter code (e.g. DIWALI20)') }}" value="{{ $order->coupon_code ?? '' }}" {{ $order->coupon_code ? 'readonly' : '' }} style="flex: 1; border: 1px solid #CBD5E1; border-radius: 8px; padding: 9px 14px; font-family: monospace; font-weight: 700; text-transform: uppercase; font-size: 13.5px; background: {{ $order->coupon_code ? '#F1F5F9' : '#FFFFFF' }};">
                            
                            <button type="button" id="btn_apply_coupon" onclick="applyCouponCode()" style="display: {{ $order->coupon_code ? 'none' : 'inline-block' }}; background: #0F172A; color: #FFFFFF; border: none; border-radius: 8px; padding: 9px 18px; font-weight: 700; font-size: 13px; cursor: pointer; transition: all 0.2s;">
                                {{ __('Apply') }}
                            </button>
                            
                            <button type="button" id="btn_remove_coupon" onclick="removeCouponCode()" style="display: {{ $order->coupon_code ? 'inline-block' : 'none' }}; background: #EF4444; color: #FFFFFF; border: none; border-radius: 8px; padding: 9px 14px; font-weight: 700; font-size: 13px; cursor: pointer;">
                                <i class="fa fa-times"></i> {{ __('Remove') }}
                            </button>
                        </div>
                        <div id="coupon-feedback" style="font-size: 12px; margin-top: 8px; display: none; font-weight: 600;"></div>
                    </div>

                    {{-- Price Breakdown --}}
                    <div style="border-bottom: 1px solid #F1F5F9; padding-bottom: 16px; margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; font-size: 14px; color: #475569; margin-bottom: 8px;">
                            <span>{{ __('Package Base Price') }}</span>
                            <span id="price-base-display">₹{{ number_format($order->package_price, 2) }}</span>
                        </div>
                        
                        <div id="discount-row" style="display: {{ $order->discount_amount > 0 ? 'flex' : 'none' }}; justify-content: space-between; font-size: 14px; color: #059669; font-weight: 700; margin-bottom: 8px;">
                            <span>
                                <i class="fa fa-tag"></i> {{ __('Coupon Discount') }} <span id="discount-label" style="font-size: 12px; font-weight: normal; color: #64748B;"></span>
                            </span>
                            <span id="price-discount-display">-₹{{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                        
                        @if($order->tax_amount > 0)
                        <div style="display: flex; justify-content: space-between; font-size: 14px; color: #475569; margin-bottom: 8px;">
                            <span>{{ __('Tax / GST') }}</span>
                            <span>+₹{{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                        @endif
                        
                        <div style="display: flex; justify-content: space-between; font-size: 16px; font-weight: 800; color: #0F172A; margin-top: 12px; padding-top: 12px; border-top: 1px dashed #E2E8F0;">
                            <span>{{ __('Total Payable Amount') }}</span>
                            <span id="price-total-display" style="color: #2563EB;">₹{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>

                    {{-- Payment Gateway Selector / Methods notice --}}
                    <div style="margin-bottom: 24px;">
                        <label style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 10px; display: block;">
                            {{ __('Supported Payment Methods:') }}
                        </label>
                        <div style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
                            <span style="display: inline-flex; align-items: center; gap: 6px; background: #FFFFFF; border: 1px solid #E2E8F0; padding: 6px 12px; border-radius: 8px; font-size: 12.5px; font-weight: 600; color: #1E293B;">
                                <i class="fa fa-google" style="color: #4285F4;"></i> Google Pay
                            </span>
                            <span style="display: inline-flex; align-items: center; gap: 6px; background: #FFFFFF; border: 1px solid #E2E8F0; padding: 6px 12px; border-radius: 8px; font-size: 12.5px; font-weight: 600; color: #1E293B;">
                                <i class="fa fa-mobile" style="color: #5F259F; font-size: 16px;"></i> PhonePe / UPI
                            </span>
                            <span style="display: inline-flex; align-items: center; gap: 6px; background: #FFFFFF; border: 1px solid #E2E8F0; padding: 6px 12px; border-radius: 8px; font-size: 12.5px; font-weight: 600; color: #1E293B;">
                                <i class="fa fa-credit-card" style="color: #03855c;"></i> Debit / Credit Cards
                            </span>
                            <span style="display: inline-flex; align-items: center; gap: 6px; background: #FFFFFF; border: 1px solid #E2E8F0; padding: 6px 12px; border-radius: 8px; font-size: 12.5px; font-weight: 600; color: #1E293B;">
                                <i class="fa fa-university" style="color: #D97706;"></i> Net Banking
                            </span>
                        </div>
                    </div>

                    {{-- Pay Button --}}
                    <div>
                        <button id="rzp-button1" class="btn btn-primary btn-block btn-lg" style="background: #2563EB; border: none; font-size: 16px; font-weight: 800; padding: 14px; border-radius: 12px; box-shadow: 0 4px 14px rgba(37,99,235,0.3); transition: all 0.2s;">
                            <i class="fa fa-lock"></i> <span id="btn-pay-text">{{ __('Proceed to Pay ₹') }}{{ number_format($order->total_amount, 2) }}</span>
                        </button>
                        <div style="text-align: center; margin-top: 14px; font-size: 12px; color: #94A3B8;">
                            <i class="fa fa-lock"></i> {{ __('256-bit Encrypted & Secure Payment via Razorpay') }}
                        </div>
                    </div>

                    {{-- Processing Overlay --}}
                    <div id="payment-loader" style="display: none; text-align: center; padding: 20px; background: #FFFFFF; border-radius: 12px; margin-top: 15px;">
                        <i class="fa fa-spinner fa-spin fa-2x" style="color: #2563EB;"></i>
                        <p style="margin-top: 10px; font-weight: 700; color: #0F172A;">{{ __('Verifying payment with bank... Please do not refresh.') }}</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.footer')
@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var currentOptions = {
    "key": "{{ $razorpayKey }}",
    "amount": "{{ $result['amount_paise'] }}",
    "currency": "{{ $order->currency }}",
    "name": "{{ $siteSetting->site_name ?? 'Job Portal' }}",
    "description": "{{ $order->package_title }} Package",
    "image": "{{ asset('sitesetting_images/thumb/' . ($siteSetting->site_logo ?? '')) }}",
    "order_id": "{{ $order->gateway_order_id }}",
    "handler": function (response){
        document.getElementById('payment-loader').style.display = 'block';
        document.getElementById('rzp-button1').disabled = true;

        fetch("{{ route('payment.verify') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                order_number: "{{ $order->order_number }}",
                razorpay_payment_id: response.razorpay_payment_id,
                razorpay_order_id: response.razorpay_order_id,
                razorpay_signature: response.razorpay_signature
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.redirect_url) {
                window.location.href = data.redirect_url;
            } else if (data.success) {
                window.location.href = "{{ route('payment.success', ['order_number' => $order->order_number]) }}";
            } else {
                alert(data.message || 'Payment verification failed.');
                window.location.href = "{{ route('payment.failed', ['order_number' => $order->order_number]) }}";
            }
        })
        .catch(err => {
            console.error(err);
            window.location.href = "{{ route('payment.success', ['order_number' => $order->order_number]) }}";
        });
    },
    "prefill": {
        "name": "{{ $result['buyer_name'] }}",
        "email": "{{ $result['buyer_email'] }}",
        "contact": "{{ $result['buyer_phone'] }}"
    },
    "theme": {
        "color": "#2563EB"
    }
};

var rzpInstance = null;
function initRazorpay() {
    rzpInstance = new Razorpay(currentOptions);
}

document.addEventListener("DOMContentLoaded", function() {
    initRazorpay();
    document.getElementById('rzp-button1').onclick = function(e){
        e.preventDefault();
        if (rzpInstance) rzpInstance.open();
    };
});

function applyCouponCode() {
    var code = document.getElementById('coupon_input').value.trim();
    var feedback = document.getElementById('coupon-feedback');
    var btn = document.getElementById('btn_apply_coupon');
    
    if (!code) {
        feedback.style.display = 'block';
        feedback.style.color = '#DC2626';
        feedback.innerText = 'Please enter a coupon code.';
        return;
    }

    btn.innerText = 'Applying...';
    btn.disabled = true;

    fetch("{{ route('payment.apply-coupon') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            order_number: "{{ $order->order_number }}",
            coupon_code: code
        })
    })
    .then(res => res.json())
    .then(data => {
        btn.innerText = 'Apply';
        btn.disabled = false;
        feedback.style.display = 'block';

        if (data.success) {
            feedback.style.color = '#059669';
            feedback.innerText = data.message;

            // Update UI elements
            document.getElementById('coupon_input').readOnly = true;
            document.getElementById('coupon_input').style.background = '#F1F5F9';
            document.getElementById('btn_apply_coupon').style.display = 'none';
            document.getElementById('btn_remove_coupon').style.display = 'inline-block';
            document.getElementById('coupon-applied-badge').style.display = 'inline-block';

            document.getElementById('discount-row').style.display = 'flex';
            document.getElementById('discount-label').innerText = '(' + data.formatted_discount + ')';
            document.getElementById('price-discount-display').innerText = '-' + data.discount_amount_formatted;
            document.getElementById('price-total-display').innerText = data.total_amount_formatted;
            document.getElementById('display-total-header').innerText = data.total_amount_formatted;
            document.getElementById('btn-pay-text').innerText = "{{ __('Proceed to Pay ') }}" + data.total_amount_formatted;

            // Update Razorpay Order instance
            currentOptions.amount = data.amount_paise;
            if (data.gateway_order_id) {
                currentOptions.order_id = data.gateway_order_id;
            }
            initRazorpay();
        } else {
            feedback.style.color = '#DC2626';
            feedback.innerText = data.message || 'Invalid coupon.';
        }
    })
    .catch(err => {
        btn.innerText = 'Apply';
        btn.disabled = false;
        feedback.style.display = 'block';
        feedback.style.color = '#DC2626';
        feedback.innerText = 'Error validating coupon code.';
    });
}

function removeCouponCode() {
    var feedback = document.getElementById('coupon-feedback');
    var btnRemove = document.getElementById('btn_remove_coupon');

    btnRemove.disabled = true;

    fetch("{{ route('payment.remove-coupon') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            order_number: "{{ $order->order_number }}"
        })
    })
    .then(res => res.json())
    .then(data => {
        btnRemove.disabled = false;
        feedback.style.display = 'block';
        feedback.style.color = '#64748B';
        feedback.innerText = 'Coupon removed.';

        // Reset UI
        document.getElementById('coupon_input').value = '';
        document.getElementById('coupon_input').readOnly = false;
        document.getElementById('coupon_input').style.background = '#FFFFFF';
        document.getElementById('btn_apply_coupon').style.display = 'inline-block';
        document.getElementById('btn_remove_coupon').style.display = 'none';
        document.getElementById('coupon-applied-badge').style.display = 'none';

        document.getElementById('discount-row').style.display = 'none';
        document.getElementById('price-total-display').innerText = data.total_amount_formatted;
        document.getElementById('display-total-header').innerText = data.total_amount_formatted;
        document.getElementById('btn-pay-text').innerText = "{{ __('Proceed to Pay ') }}" + data.total_amount_formatted;

        // Reset Razorpay options
        currentOptions.amount = data.amount_paise;
        if (data.gateway_order_id) {
            currentOptions.order_id = data.gateway_order_id;
        }
        initRazorpay();
    })
    .catch(err => {
        btnRemove.disabled = false;
        console.error(err);
    });
}
</script>
@endpush
