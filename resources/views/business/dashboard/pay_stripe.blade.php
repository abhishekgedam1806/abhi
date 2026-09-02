@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->

<!-- Inner Page Title -->
<div class="pageTitle" style="background: #0F172A; padding: 32px 0; color: #FFFFFF !important;">
    <div class="container">
        <h1 style="font-size: 24px; font-weight: 800; color: #FFFFFF !important; margin: 0;">Pay with Stripe (Credit/Debit Card)</h1>
        <p style="color: #E2E8F0 !important; font-size: 13.5px; margin-top: 4px; margin-bottom: 0;">Secure 256-bit encrypted checkout for your Business Listing Plan.</p>
    </div>
</div>

<div class="listpgWraper" style="background: #F8FAFC; padding: 40px 0 60px;">
    <div class="container">
        @include('flash::message')
        <div class="row">
            {{-- Dedicated Business Dashboard Menu --}}
            @include('includes.business_dashboard_menu')

            <div class="col-lg-9 col-md-8">
                <div style="background:#fff;border-radius:16px;border:1px solid #E2E8F0;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,0.03);">
                    <div class="row">
                        
                        {{-- Left Column: Invoice Details --}}
                        <div class="col-md-5 mb-4 mb-md-0" style="border-right:1px solid #F1F5F9;padding-right:24px;">
                            <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:12px;padding:20px;">
                                <h4 style="font-size:16px;font-weight:800;color:#0F172A;margin-bottom:14px;">Invoice Summary</h4>
                                
                                <div style="margin-bottom:10px;font-size:13px;color:#64748B;">Plan: <strong style="color:#0F172A;">{{ $package->package_title }}</strong></div>
                                <div style="margin-bottom:10px;font-size:13px;color:#64748B;">Price: <strong style="font-size:18px;color:#2563EB;">₹{{ number_format($package->package_price, 0) }}</strong></div>
                                <div style="margin-bottom:10px;font-size:13px;color:#64748B;">Listings Allowed: <strong style="color:#0F172A;">{{ $package->package_num_listings }}</strong></div>
                                <div style="margin-bottom:10px;font-size:13px;color:#64748B;">Services Allowed: <strong style="color:#0F172A;">{{ $package->package_num_services }}</strong></div>
                                <div style="margin-bottom:14px;font-size:13px;color:#64748B;">Duration: <strong style="color:#0F172A;">{{ $package->package_num_days }} Days</strong></div>

                                <div style="border-top:1px dashed #CBD5E1;padding-top:12px;display:flex;align-items:center;gap:8px;font-size:12px;color:#03855c;font-weight:700;">
                                    <i class="fa fa-shield"></i> 100% Secure Checkout via Stripe
                                </div>
                            </div>
                        </div>

                        {{-- Right Column: Card Details Form --}}
                        <div class="col-md-7">
                            <h4 style="font-size:16px;font-weight:800;color:#0F172A;margin-bottom:18px;">
                                <i class="fa fa-credit-card text-primary"></i> Credit / Debit Card Details
                            </h4>

                            <div id="error_div" class="alert alert-danger" style="display:none;border-radius:8px;font-size:13px;"></div>

                            {!! Form::open(['method' => 'post', 'route' => 'business.stripe.order.package', 'id' => 'stripe-form']) !!}
                            {{ Form::hidden('package_id', $package->id) }}

                            <div class="form-group mb-3">
                                <label style="font-size:12.5px;font-weight:700;color:#475569;">Name on Card</label>
                                <input type="text" id="card_name" class="form-control" placeholder="Full Name" required style="height:42px;border-radius:8px;font-size:13.5px;">
                            </div>

                            <div class="form-group mb-3">
                                <label style="font-size:12.5px;font-weight:700;color:#475569;">Card Number</label>
                                <input type="text" id="card_no" class="form-control" placeholder="•••• •••• •••• ••••" required style="height:42px;border-radius:8px;font-size:13.5px;">
                            </div>

                            <div class="row">
                                <div class="col-md-4 form-group mb-3">
                                    <label style="font-size:12.5px;font-weight:700;color:#475569;">Expiry Month</label>
                                    <select id="ccExpiryMonth" class="form-control" style="height:42px;border-radius:8px;font-size:13px;">
                                        @for ($counter = 1; $counter <= 12; $counter++)
                                        @php $val = str_pad($counter, 2, '0', STR_PAD_LEFT); @endphp
                                        <option value="{{$val}}">{{$val}}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label style="font-size:12.5px;font-weight:700;color:#475569;">Expiry Year</label>
                                    <select id="ccExpiryYear" class="form-control" style="height:42px;border-radius:8px;font-size:13px;">
                                        @for ($year = date('Y'); $year <= date('Y') + 10; $year++)
                                        <option value="{{$year}}">{{$year}}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label style="font-size:12.5px;font-weight:700;color:#475569;">CVV / CVC</label>
                                    <input type="password" id="cvvNumber" class="form-control" placeholder="123" maxlength="4" required style="height:42px;border-radius:8px;font-size:13.5px;">
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" id="btn-submit-pay" class="btn btn-primary btn-block" style="height:46px;background:#2563EB;border:none;font-weight:800;border-radius:10px;font-size:15px;">
                                    <i class="fa fa-lock"></i> Pay ₹{{ number_format($package->package_price, 0) }} & Activate Plan
                                </button>
                            </div>
                            {!! Form::close() !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.footer')
@endsection

@push('scripts')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
Stripe.setPublishableKey('{{ Config::get('stripe.stripe_key') }}');
var $form = $('#stripe-form');
$form.submit(function (event) {
    $('#error_div').hide();
    $('#btn-submit-pay').prop('disabled', true).text('Processing Payment...');
    Stripe.card.createToken({
        number: $('#card_no').val(),
        cvc: $('#cvvNumber').val(),
        exp_month: $('#ccExpiryMonth').val(),
        exp_year: $('#ccExpiryYear').val(),
        name: $('#card_name').val()
    }, stripeResponseHandler);
    return false;
});
function stripeResponseHandler(status, response) {
    if (response.error) {
        $('#error_div').show().text(response.error.message);
        $('#btn-submit-pay').prop('disabled', false).html('<i class="fa fa-lock"></i> Pay & Activate Plan');
    } else {
        var token = response.id;
        $form.append($('<input type="hidden" name="stripeToken" />').val(token));
        $form.get(0).submit();
    }
}
</script>
@endpush
