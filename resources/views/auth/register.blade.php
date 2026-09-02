@extends('layouts.app')
@section('content') 

<!-- Header start --> 
@include('includes.header') 
<!-- Header end --> 

<!-- Inner Page Title start --> 
@include('includes.inner_page_title', ['page_title'=>__('Register')]) 
<!-- Inner Page Title end -->

<style>
.useraccountwrap {
    max-width: 520px !important;
    margin: 30px auto 50px auto !important;
}
.custom-login-pills {
    background: #F1F5F9 !important;
    border-radius: 14px !important;
    padding: 5px !important;
    display: flex !important;
    flex-direction: row !important;
    gap: 5px !important;
    border: none !important;
    width: 100% !important;
    align-items: stretch !important;
    margin: 0 0 20px 0 !important;
    list-style: none !important;
    list-style-type: none !important;
}
.custom-login-pills li,
.custom-login-pills .nav-item {
    flex: 1 1 0 !important;
    min-width: 0 !important;
    text-align: center !important;
    margin: 0 !important;
    padding: 0 !important;
    list-style: none !important;
    list-style-type: none !important;
}
.custom-login-pills li:before,
.custom-login-pills li:after,
.custom-login-pills .nav-item:before,
.custom-login-pills .nav-item:after {
    display: none !important;
    content: none !important;
}
.custom-login-pills .nav-link,
.custom-login-pills a {
    border-radius: 10px !important;
    padding: 10px 8px !important;
    font-weight: 700 !important;
    font-size: 13.5px !important;
    color: #64748B !important;
    border: none !important;
    text-align: center !important;
    width: 100% !important;
    height: 100% !important;
    min-height: 42px !important;
    transition: all 0.2s ease !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 6px !important;
    white-space: nowrap !important;
    background: transparent !important;
    cursor: pointer !important;
    box-sizing: border-box !important;
    text-decoration: none !important;
    outline: none !important;
}
.custom-login-pills .nav-link:hover,
.custom-login-pills a:hover {
    color: #1B4FD8 !important;
    text-decoration: none !important;
    background: rgba(27, 79, 216, 0.06) !important;
}
.custom-login-pills .nav-link.active,
.custom-login-pills .nav-link.active:focus,
.custom-login-pills .nav-link.active:hover,
.custom-login-pills li.active a {
    background: #1B4FD8 !important;
    color: #FFFFFF !important;
    box-shadow: 0 4px 12px rgba(27, 79, 216, 0.28) !important;
    font-weight: 700 !important;
    text-decoration: none !important;
}

/* 6-Digit OTP Boxes */
.otp-boxes-wrapper {
    display: flex;
    gap: 8px;
    justify-content: center;
    margin: 18px 0 22px 0;
}
.otp-digit-cell {
    width: 50px;
    height: 52px;
    font-size: 22px;
    font-weight: 800;
    text-align: center;
    color: #1E3A8A;
    background: #EFF6FF;
    border: 2px solid #BFDBFE;
    border-radius: 12px;
    outline: none;
    transition: all 0.2s ease;
}
.otp-digit-cell:focus {
    border-color: #2563EB;
    background: #FFFFFF;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.18);
}

.alert-auth-msg {
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.alert-auth-error {
    background: #FEF2F2;
    border: 1px solid #FECACA;
    color: #991B1B;
}
.alert-auth-success {
    background: #F0FDF4;
    border: 1px solid #BBF7D0;
    color: #166534;
}

@media (max-width: 576px) {
    .useraccountwrap {
        padding: 0 6px !important;
        margin: 15px auto 40px auto !important;
    }
    .custom-login-pills {
        padding: 4px !important;
        gap: 3px !important;
        border-radius: 12px !important;
    }
    .custom-login-pills .nav-link {
        padding: 8px 4px !important;
        font-size: 11.5px !important;
        min-height: 38px !important;
        gap: 3px !important;
    }
    .custom-login-pills .nav-link i {
        font-size: 11px !important;
    }
    .otp-digit-cell {
        width: 42px;
        height: 46px;
        font-size: 19px;
        border-radius: 10px;
    }
    .otp-boxes-wrapper {
        gap: 5px;
    }
}
</style>

<div class="listpgWraper">
    <div class="container">
        @include('flash::message')
        <div class="useraccountwrap">
            <div class="userccount" style="background:#fff; border:1px solid #E2E8F0; border-radius:18px; padding:28px 24px; box-shadow:0 8px 20px rgba(0,0,0,0.04);">
                
                <div id="reg_form_container">
                    {{-- Role Selector Pills --}}
                    <div class="userbtns">
                        <ul class="nav nav-pills custom-login-pills" role="tablist">
                            <?php
                            $tabParam = Request::query('tab');
                            $c_or_e = old('candidate_or_employer', $tabParam ?: 'candidate');
                            ?>
                            <li class="nav-item">
                                <a class="nav-link {{($c_or_e == 'candidate')? 'active':''}}" data-toggle="pill" href="#candidate" role="tab" onclick="setRegRole('candidate')">
                                    <i class="fa fa-user"></i> {{__('Candidate')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{($c_or_e == 'employer')? 'active':''}}" data-toggle="pill" href="#employer" role="tab" onclick="setRegRole('employer')">
                                    <i class="fa fa-building"></i> {{__('Employer')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{($c_or_e == 'business')? 'active':''}}" data-toggle="pill" href="#business" role="tab" onclick="setRegRole('business')">
                                    <i class="fa fa-briefcase"></i> {{__('Business')}}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div id="reg_global_alert" style="margin-top: 16px;"></div>

                    <div class="tab-content mt-4">

                        {{-- =========================================================================
                             1. CANDIDATE REGISTRATION FORM
                             ========================================================================= --}}
                        <div id="candidate" class="formpanel tab-pane {{($c_or_e == 'candidate')? 'active in':''}}">
                            <form id="candidate_reg_form" class="form-horizontal" onsubmit="handleCandidateRegister(event)">
                                @csrf
                                <input type="hidden" name="candidate_or_employer" value="candidate" />

                                <div class="formrow">
                                    <input type="text" name="first_name" id="cand_first_name" class="form-control" required placeholder="{{__('First Name')}} *" value="{{old('first_name')}}">
                                </div>

                                <div class="formrow">
                                    <input type="text" name="middle_name" id="cand_middle_name" class="form-control" placeholder="{{__('Middle Name')}} (Optional)" value="{{old('middle_name')}}">
                                </div>

                                <div class="formrow">
                                    <input type="text" name="last_name" id="cand_last_name" class="form-control" required placeholder="{{__('Last Name')}} *" value="{{old('last_name')}}">
                                </div>

                                <div class="formrow">
                                    <input type="email" name="email" id="cand_email" class="form-control" required placeholder="{{__('Email Address')}} *" value="{{old('email')}}">
                                </div>

                                <div class="formrow">
                                    <div class="pwd-field-wrap">
                                        <input type="password" name="password" id="cand_pwd" class="form-control" required placeholder="{{__('Create Password')}} (min 6 chars) *">
                                        <button type="button" class="btn-pwd-eye" onclick="togglePasswordVisibility(this)" tabindex="-1" title="Toggle Password Visibility"><i class="fa fa-eye-slash"></i></button>
                                    </div>
                                </div>

                                <div class="formrow">
                                    <div class="pwd-field-wrap">
                                        <input type="password" name="password_confirmation" id="cand_pwd_confirm" class="form-control" required placeholder="{{__('Confirm Password')}} *">
                                        <button type="button" class="btn-pwd-eye" onclick="togglePasswordVisibility(this)" tabindex="-1" title="Toggle Password Visibility"><i class="fa fa-eye-slash"></i></button>
                                    </div>
                                </div>

                                <div class="formrow">
                                    <input type="checkbox" value="1" name="is_subscribed" checked /> {{__('Subscribe to newsletter')}}
                                </div>

                                <div class="formrow">
                                    <input type="checkbox" value="1" name="terms_of_use" id="cand_terms" required checked />
                                    <a href="{{url('cms/terms-of-use')}}" target="_blank">{{__('I accept Terms of Use')}} *</a>
                                </div>

                                <button type="submit" class="btn btn-primary" id="btn_cand_submit" style="width:100%; height:46px; border-radius:10px; font-weight:800; background:#2563EB; border:none; box-shadow:0 4px 12px rgba(37,99,235,0.25);">
                                    <i class="fa fa-user-plus"></i> {{__('Register as Candidate')}}
                                </button>
                            </form>
                        </div>

                        {{-- =========================================================================
                             2. EMPLOYER REGISTRATION FORM
                             ========================================================================= --}}
                        <div id="employer" class="formpanel tab-pane fade {{($c_or_e == 'employer')? 'active in':''}}">
                            <form id="employer_reg_form" class="form-horizontal" onsubmit="handleEmployerRegister(event)">
                                @csrf
                                <input type="hidden" name="candidate_or_employer" value="employer" />

                                <div class="formrow">
                                    <input type="text" name="name" id="emp_name" class="form-control" required placeholder="{{__('Company / Employer Name')}} *" value="{{old('name')}}">
                                </div>

                                <div class="formrow">
                                    <input type="email" name="email" id="emp_email" class="form-control" required placeholder="{{__('Company Official Email')}} *" value="{{old('email')}}">
                                </div>

                                <div class="formrow">
                                    <div class="pwd-field-wrap">
                                        <input type="password" name="password" id="emp_pwd" class="form-control" required placeholder="{{__('Create Password')}} (min 6 chars) *">
                                        <button type="button" class="btn-pwd-eye" onclick="togglePasswordVisibility(this)" tabindex="-1" title="Toggle Password Visibility"><i class="fa fa-eye-slash"></i></button>
                                    </div>
                                </div>

                                <div class="formrow">
                                    <div class="pwd-field-wrap">
                                        <input type="password" name="password_confirmation" id="emp_pwd_confirm" class="form-control" required placeholder="{{__('Confirm Password')}} *">
                                        <button type="button" class="btn-pwd-eye" onclick="togglePasswordVisibility(this)" tabindex="-1" title="Toggle Password Visibility"><i class="fa fa-eye-slash"></i></button>
                                    </div>
                                </div>

                                <div class="formrow">
                                    <input type="checkbox" value="1" name="is_subscribed" checked /> {{__('Subscribe to newsletter')}}
                                </div>

                                <div class="formrow">
                                    <input type="checkbox" value="1" name="terms_of_use" id="emp_terms" required checked />
                                    <a href="{{url('terms-of-use')}}" target="_blank">{{__('I accept Terms of Use')}} *</a>
                                </div>

                                <button type="submit" class="btn btn-primary" id="btn_emp_submit" style="width:100%; height:46px; border-radius:10px; font-weight:800; background:#03855c; border:none; box-shadow:0 4px 12px rgba(3,133,92,0.25);">
                                    <i class="fa fa-building"></i> {{__('Register as Employer')}}
                                </button>
                            </form>
                        </div>

                        {{-- =========================================================================
                             3. BUSINESS OWNER REGISTRATION FORM
                             ========================================================================= --}}
                        <div id="business" class="formpanel tab-pane fade {{($c_or_e == 'business')? 'active in':''}}">
                            <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px;">
                                <strong style="color:#1E293B;"><i class="fa fa-briefcase text-primary"></i> Register as Business Owner</strong>
                                <p style="margin:4px 0 0;font-size:12.5px;color:#64748B;">List your local business, showcase services, and capture direct customer leads.</p>
                            </div>

                            <form id="business_reg_form" class="form-horizontal" onsubmit="handleBusinessRegister(event)">
                                @csrf
                                <input type="hidden" name="candidate_or_employer" value="business" />

                                <div class="formrow">
                                    <input type="text" name="first_name" id="biz_first_name" class="form-control" required placeholder="Owner First Name *" value="{{old('first_name')}}">
                                </div>

                                <div class="formrow">
                                    <input type="text" name="last_name" id="biz_last_name" class="form-control" required placeholder="Owner Last Name *" value="{{old('last_name')}}">
                                </div>

                                <div class="formrow">
                                    <input type="email" name="email" id="biz_email" class="form-control" required placeholder="Business / Owner Email Address *" value="{{old('email')}}">
                                </div>

                                <div class="formrow">
                                    <input type="text" name="phone" id="biz_phone" class="form-control" placeholder="Mobile / Phone Number (Optional)">
                                </div>

                                <div class="formrow">
                                    <div class="pwd-field-wrap">
                                        <input type="password" name="password" id="biz_pwd" class="form-control" required placeholder="Create Password (min 6 chars) *">
                                        <button type="button" class="btn-pwd-eye" onclick="togglePasswordVisibility(this)" tabindex="-1" title="Toggle Password Visibility"><i class="fa fa-eye-slash"></i></button>
                                    </div>
                                </div>

                                <div class="formrow">
                                    <div class="pwd-field-wrap">
                                        <input type="password" name="password_confirmation" id="biz_pwd_confirm" class="form-control" required placeholder="Confirm Password *">
                                        <button type="button" class="btn-pwd-eye" onclick="togglePasswordVisibility(this)" tabindex="-1" title="Toggle Password Visibility"><i class="fa fa-eye-slash"></i></button>
                                    </div>
                                </div>

                                <div class="formrow">
                                    <input type="checkbox" value="1" name="terms_of_use" id="biz_terms" required checked />
                                    <a href="{{url('terms-of-use')}}" target="_blank">{{__('I accept Terms of Use')}} *</a>
                                </div>

                                <button type="submit" class="btn btn-primary" id="btn_biz_submit" style="width:100%; height:46px; border-radius:10px; font-weight:800; background:#2563EB; border:none; box-shadow:0 4px 12px rgba(37,99,235,0.25);">
                                    <i class="fa fa-check-circle"></i> Create Business Account
                                </button>
                            </form>
                        </div>

                    </div>

                    <div class="newuser" style="margin-top: 24px; text-align: center; font-size: 13.5px; color: #64748B;">
                        <i class="fa fa-user" aria-hidden="true"></i> {{__('Already have an account')}}? 
                        <a href="{{route('login')}}" style="color: #2563EB; font-weight: 700;">{{__('Sign In')}}</a>
                    </div>
                </div>

                {{-- =========================================================================
                     4. MANDATORY EMAIL OTP VERIFICATION SCREEN
                     ========================================================================= --}}
                <div id="reg_otp_verification_view" style="display: none;">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <div style="width: 56px; height: 56px; border-radius: 50%; background: #EFF6FF; color: #2563EB; font-size: 26px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                            <i class="fa fa-envelope-open-o"></i>
                        </div>
                        <h3 style="font-size: 22px; font-weight: 800; color: #0F172A; margin: 0;">Verify Your Email</h3>
                        <p style="font-size: 13.5px; color: #64748B; margin-top: 6px;" id="reg_otp_subtitle">
                            We've sent a 6-digit verification code to: <strong id="reg_verify_email_display">your email</strong>
                        </p>
                    </div>

                    <div id="reg_verify_alert"></div>

                    <div class="otp-boxes-wrapper">
                        <input type="text" maxlength="1" class="otp-digit-cell reg-otp-cell" id="reg_digit_1" pattern="[0-9]*" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-digit-cell reg-otp-cell" id="reg_digit_2" pattern="[0-9]*" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-digit-cell reg-otp-cell" id="reg_digit_3" pattern="[0-9]*" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-digit-cell reg-otp-cell" id="reg_digit_4" pattern="[0-9]*" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-digit-cell reg-otp-cell" id="reg_digit_5" pattern="[0-9]*" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-digit-cell reg-otp-cell" id="reg_digit_6" pattern="[0-9]*" inputmode="numeric">
                    </div>

                    <button type="button" class="btn btn-primary" id="btn_verify_reg_otp" onclick="handleVerifyRegistrationOtp()" style="width:100%; height:48px; border-radius:11px; font-weight:800; background:#03855c; border:none; box-shadow:0 4px 14px rgba(3,133,92,0.28); font-size:15px;">
                        <i class="fa fa-check-circle"></i> Verify Email & Activate Account
                    </button>

                    <div style="text-align: center; margin-top: 18px; font-size: 13px; color: #64748B;">
                        Didn't receive the code? 
                        <span id="reg_resend_timer_wrap">Resend OTP in <strong id="reg_resend_countdown" style="color: #2563EB;">30</strong>s</span>
                        <a href="javascript:;" id="btn_reg_resend_otp" style="display: none; color: #2563EB; font-weight: 700;" onclick="handleResendRegistrationOtp()">Resend OTP</a>
                    </div>

                    <div style="margin-top: 22px; padding-top: 16px; border-top: 1px solid #F1F5F9; text-align: center;">
                        <a href="javascript:;" onclick="showRegFormView()" style="color: #64748B; font-weight: 600; font-size: 13px;">
                            <i class="fa fa-arrow-left"></i> Edit Details / Back to Form
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
var currentRegRole = "{{ $c_or_e }}";
var currentRegisteredEmail = "";
var regResendTimer = null;
var regCooldown = 30;

function setRegRole(role) {
    currentRegRole = role;
    clearRegAlert();
}

function showRegAlert(type, msg) {
    var alertBox = document.getElementById('reg_global_alert');
    var icon = (type === 'success') ? 'fa-check-circle' : 'fa-exclamation-triangle';
    var cssClass = (type === 'success') ? 'alert-auth-success' : 'alert-auth-error';
    alertBox.innerHTML = '<div class="alert-auth-msg ' + cssClass + '"><i class="fa ' + icon + '"></i> <div>' + msg + '</div></div>';
}

function clearRegAlert() {
    document.getElementById('reg_global_alert').innerHTML = '';
}

function showRegVerifyAlert(type, msg) {
    var alertBox = document.getElementById('reg_verify_alert');
    var icon = (type === 'success') ? 'fa-check-circle' : 'fa-exclamation-triangle';
    var cssClass = (type === 'success') ? 'alert-auth-success' : 'alert-auth-error';
    alertBox.innerHTML = '<div class="alert-auth-msg ' + cssClass + '"><i class="fa ' + icon + '"></i> <div>' + msg + '</div></div>';
}

function showOtpVerificationView(email, maskedEmail) {
    currentRegisteredEmail = email;
    document.getElementById('reg_form_container').style.display = 'none';
    document.getElementById('reg_otp_verification_view').style.display = 'block';
    document.getElementById('reg_verify_email_display').innerText = maskedEmail || email;
    
    // Clear boxes & focus first digit
    for (var i = 1; i <= 6; i++) {
        document.getElementById('reg_digit_' + i).value = '';
    }
    document.getElementById('reg_digit_1').focus();
    startRegResendCountdown(30);
}

function showRegFormView() {
    document.getElementById('reg_form_container').style.display = 'block';
    document.getElementById('reg_otp_verification_view').style.display = 'none';
    clearRegAlert();
}

function handleCandidateRegister(e) {
    e.preventDefault();
    clearRegAlert();

    var pwd = document.getElementById('cand_pwd').value;
    var pwdConf = document.getElementById('cand_pwd_confirm').value;
    if (pwd !== pwdConf) {
        showRegAlert('error', 'Passwords do not match. Please re-check.');
        return;
    }

    var btn = document.getElementById('btn_cand_submit');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Creating Account & Sending OTP...';

    var data = {
        _token: "{{ csrf_token() }}",
        first_name: document.getElementById('cand_first_name').value,
        middle_name: document.getElementById('cand_middle_name').value,
        last_name: document.getElementById('cand_last_name').value,
        email: document.getElementById('cand_email').value,
        password: pwd,
        password_confirmation: pwdConf,
        terms_of_use: document.getElementById('cand_terms').checked ? 1 : 0
    };

    submitRegistration("{{ route('otp.register.candidate') }}", data, btn, '<i class="fa fa-user-plus"></i> Register as Candidate');
}

function handleEmployerRegister(e) {
    e.preventDefault();
    clearRegAlert();

    var pwd = document.getElementById('emp_pwd').value;
    var pwdConf = document.getElementById('emp_pwd_confirm').value;
    if (pwd !== pwdConf) {
        showRegAlert('error', 'Passwords do not match. Please re-check.');
        return;
    }

    var btn = document.getElementById('btn_emp_submit');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Creating Account & Sending OTP...';

    var data = {
        _token: "{{ csrf_token() }}",
        name: document.getElementById('emp_name').value,
        email: document.getElementById('emp_email').value,
        password: pwd,
        password_confirmation: pwdConf,
        terms_of_use: document.getElementById('emp_terms').checked ? 1 : 0
    };

    submitRegistration("{{ route('otp.register.employer') }}", data, btn, '<i class="fa fa-building"></i> Register as Employer');
}

function handleBusinessRegister(e) {
    e.preventDefault();
    clearRegAlert();

    var pwd = document.getElementById('biz_pwd').value;
    var pwdConf = document.getElementById('biz_pwd_confirm').value;
    if (pwd !== pwdConf) {
        showRegAlert('error', 'Passwords do not match. Please re-check.');
        return;
    }

    var btn = document.getElementById('btn_biz_submit');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Creating Account & Sending OTP...';

    var data = {
        _token: "{{ csrf_token() }}",
        first_name: document.getElementById('biz_first_name').value,
        last_name: document.getElementById('biz_last_name').value,
        email: document.getElementById('biz_email').value,
        phone: document.getElementById('biz_phone').value,
        password: pwd,
        password_confirmation: pwdConf,
        terms_of_use: document.getElementById('biz_terms').checked ? 1 : 0
    };

    submitRegistration("{{ route('otp.register.business') }}", data, btn, '<i class="fa fa-check-circle"></i> Create Business Account');
}

function submitRegistration(url, data, btn, defaultBtnHtml) {
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function(resp) {
            btn.disabled = false;
            btn.innerHTML = defaultBtnHtml;
            if (resp.status === 'otp_sent') {
                showOtpVerificationView(resp.email, resp.masked_email);
            } else {
                showRegAlert('error', resp.message || 'Registration failed.');
            }
        },
        error: function(xhr) {
            btn.disabled = false;
            btn.innerHTML = defaultBtnHtml;
            var err = 'Registration failed. Please check form entries.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                err = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                var firstKey = Object.keys(xhr.responseJSON.errors)[0];
                err = xhr.responseJSON.errors[firstKey][0];
            }
            showRegAlert('error', err);
        }
    });
}

function handleVerifyRegistrationOtp() {
    var code = '';
    for (var i = 1; i <= 6; i++) {
        code += document.getElementById('reg_digit_' + i).value.trim();
    }

    if (code.length < 6) {
        showRegVerifyAlert('error', 'Please enter the full 6-digit OTP code.');
        return;
    }

    var btn = document.getElementById('btn_verify_reg_otp');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Verifying & Activating...';

    $.ajax({
        url: "{{ route('otp.verify') }}",
        type: 'POST',
        data: {
            _token: "{{ csrf_token() }}",
            email: currentRegisteredEmail,
            otp_code: code,
            user_type: currentRegRole
        },
        dataType: 'json',
        success: function(resp) {
            if (resp.status === 'success') {
                showRegVerifyAlert('success', 'Email verified successfully! Redirecting to your dashboard...');
                setTimeout(function() {
                    window.location.href = resp.redirect_url;
                }, 700);
            } else {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-check-circle"></i> Verify Email & Activate Account';
                showRegVerifyAlert('error', resp.message);
            }
        },
        error: function(xhr) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-check-circle"></i> Verify Email & Activate Account';
            var err = 'Verification failed. Please check your OTP code.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                err = xhr.responseJSON.message;
            }
            showRegVerifyAlert('error', err);
        }
    });
}

function handleResendRegistrationOtp() {
    if (!currentRegisteredEmail) return;

    var btn = document.getElementById('btn_reg_resend_otp');
    btn.innerText = 'Sending...';

    $.ajax({
        url: "{{ route('otp.send') }}",
        type: 'POST',
        data: {
            _token: "{{ csrf_token() }}",
            email: currentRegisteredEmail,
            user_type: currentRegRole
        },
        dataType: 'json',
        success: function(resp) {
            showRegVerifyAlert('success', 'New 6-digit OTP code has been sent!');
            startRegResendCountdown(30);
        },
        error: function(xhr) {
            btn.innerText = 'Resend OTP';
            var err = 'Failed to resend OTP. Please try again in a few moments.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                err = xhr.responseJSON.message;
            }
            showRegVerifyAlert('error', err);
        }
    });
}

function startRegResendCountdown(seconds) {
    clearInterval(regResendTimer);
    regCooldown = seconds;
    var timerWrap = document.getElementById('reg_resend_timer_wrap');
    var countdownEl = document.getElementById('reg_resend_countdown');
    var resendBtn = document.getElementById('btn_reg_resend_otp');

    timerWrap.style.display = 'inline';
    resendBtn.style.display = 'none';
    countdownEl.innerText = regCooldown;

    regResendTimer = setInterval(function() {
        regCooldown--;
        countdownEl.innerText = regCooldown;
        if (regCooldown <= 0) {
            clearInterval(regResendTimer);
            timerWrap.style.display = 'none';
            resendBtn.style.display = 'inline';
            resendBtn.innerText = 'Resend OTP';
        }
    }, 1000);
}

// 6-Digit Auto-Focus & Paste Handler for Registration
document.querySelectorAll('.reg-otp-cell').forEach(function(input, idx, inputs) {
    input.addEventListener('input', function(e) {
        if (this.value.length === 1) {
            if (idx < inputs.length - 1) {
                inputs[idx + 1].focus();
            } else {
                handleVerifyRegistrationOtp();
            }
        }
    });

    input.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace' && !this.value && idx > 0) {
            inputs[idx - 1].focus();
        }
    });

    input.addEventListener('paste', function(e) {
        e.preventDefault();
        var pasteData = (e.clipboardData || window.clipboardData).getData('text').trim();
        if (/^\d{6}$/.test(pasteData)) {
            for (var i = 0; i < 6; i++) {
                inputs[i].value = pasteData[i];
            }
            inputs[5].focus();
            handleVerifyRegistrationOtp();
        }
    });
});
</script>
@endpush

@include('includes.footer')
@endsection