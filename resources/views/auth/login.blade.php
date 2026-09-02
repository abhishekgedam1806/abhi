@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end --> 
<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title'=>__('Login')])
<!-- Inner Page Title end -->

<style>
.useraccountwrap {
    max-width: 480px !important;
    margin: 35px auto 55px auto !important;
}
.auth-main-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 18px;
    padding: 32px 28px;
    box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.06), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
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
    margin: 0 0 24px 0 !important;
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

.auth-header-title {
    font-size: 22px;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 6px;
    text-align: center;
}
.auth-header-subtitle {
    font-size: 13.5px;
    color: #64748B;
    text-align: center;
    margin-bottom: 22px;
}

.auth-input-group {
    margin-bottom: 18px;
}
.auth-input-label {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 6px;
}
.auth-input-field {
    width: 100%;
    height: 46px;
    padding: 10px 14px;
    font-size: 14px;
    color: #0F172A;
    background: #FFFFFF;
    border: 1.5px solid #CBD5E1;
    border-radius: 10px;
    outline: none;
    transition: all 0.2s ease;
}
.auth-input-field:focus {
    border-color: #2563EB;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
}

.btn-auth-primary {
    width: 100%;
    height: 48px;
    background: #03855c;
    color: #FFFFFF;
    border: none;
    border-radius: 11px;
    font-size: 15px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 4px 14px rgba(3, 133, 92, 0.28);
}
.btn-auth-primary:hover {
    background: #047857;
    transform: translateY(-1px);
    color: #FFFFFF;
}

.btn-auth-secondary {
    width: 100%;
    height: 46px;
    background: #FFFFFF;
    color: #1E293B;
    border: 1.5px solid #CBD5E1;
    border-radius: 11px;
    font-size: 14px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.btn-auth-secondary:hover {
    background: #F8FAFC;
    border-color: #94A3B8;
    color: #0F172A;
}

.auth-divider {
    display: flex;
    align-items: center;
    margin: 20px 0;
    color: #94A3B8;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
}
.auth-divider::before,
.auth-divider::after {
    content: "";
    flex: 1;
    height: 1px;
    background: #E2E8F0;
}
.auth-divider span {
    padding: 0 14px;
}

.auth-footer-links {
    margin-top: 24px;
    padding-top: 18px;
    border-top: 1px solid #F1F5F9;
    text-align: center;
    font-size: 13.5px;
    color: #64748B;
}
.auth-footer-links a {
    color: #2563EB;
    font-weight: 700;
    text-decoration: none;
}
.auth-footer-links a:hover {
    text-decoration: underline;
}

/* 6-Digit OTP Boxes */
.otp-boxes-wrapper {
    display: flex;
    gap: 8px;
    justify-content: center;
    margin: 18px 0 22px 0;
}
.otp-digit-cell {
    width: 52px;
    height: 54px;
    font-size: 24px;
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
        padding: 0 8px !important;
        margin: 15px auto 40px auto !important;
    }
    .auth-main-card {
        padding: 24px 18px;
        border-radius: 16px;
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
    }
    .otp-digit-cell {
        width: 44px;
        height: 48px;
        font-size: 20px;
        border-radius: 10px;
    }
    .otp-boxes-wrapper {
        gap: 6px;
    }
}
</style>

<div class="listpgWraper">
    <div class="container">
        @include('flash::message')
       
        <div class="useraccountwrap">
            <div class="auth-main-card">
                
                {{-- Role Selection Pills --}}
                <ul class="nav nav-pills custom-login-pills" role="tablist">
                    <?php
                    $tabParam = Request::query('tab');
                    $c_or_e = old('candidate_or_employer', $tabParam ?: 'candidate');
                    ?>
                    <li class="nav-item">
                        <a class="nav-link {{($c_or_e == 'candidate')? 'active':''}}" data-role="candidate" href="javascript:;" onclick="selectRole('candidate')">
                            <i class="fa fa-user"></i> {{__('Candidate')}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{($c_or_e == 'employer')? 'active':''}}" data-role="employer" href="javascript:;" onclick="selectRole('employer')">
                            <i class="fa fa-building"></i> {{__('Employer')}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{($c_or_e == 'business')? 'active':''}}" data-role="business" href="javascript:;" onclick="selectRole('business')">
                            <i class="fa fa-briefcase"></i> {{__('Business')}}
                        </a>
                    </li>
                </ul>

                <input type="hidden" id="selected_role" value="{{ $c_or_e }}">
                <div id="auth_global_alert"></div>

                {{-- =========================================================================
                     STATE 1: PRIMARY LOGIN SCREEN (EMAIL + SEND OTP)
                     ========================================================================= --}}
                <div id="login_view_primary">
                    <div class="auth-header-title">Login to your account</div>
                    <div class="auth-header-subtitle">
                        Enter your email to receive a secure 6-digit login OTP
                    </div>

                    <div class="auth-input-group">
                        <label class="auth-input-label">Email Address</label>
                        <input type="email" id="primary_email_input" class="auth-input-field" placeholder="name@example.com" value="{{ old('email') }}" autofocus autocomplete="email">
                    </div>

                    <button type="button" class="btn-auth-primary" id="btn_send_otp" onclick="handleSendOtp()">
                        <i class="fa fa-bolt"></i> Send 6-Digit OTP
                    </button>

                    <div class="auth-divider">
                        <span>OR</span>
                    </div>

                    <button type="button" class="btn-auth-secondary" onclick="showPasswordView()">
                        <i class="fa fa-key text-primary"></i> Login with Password
                    </button>

                    <div class="auth-footer-links">
                        New User? <a href="{{ route('register') }}" id="register_redirect_link">Register Here</a>
                    </div>
                </div>

                {{-- =========================================================================
                     STATE 2: OTP VERIFICATION SCREEN (ENTER 6-DIGIT CODE)
                     ========================================================================= --}}
                <div id="login_view_otp" style="display: none;">
                    <div class="auth-header-title">Verify 6-Digit OTP</div>
                    <div class="auth-header-subtitle" id="otp_subtitle_sent_to">
                        We've sent a verification code to your email
                    </div>

                    <div class="otp-boxes-wrapper">
                        <input type="text" maxlength="1" class="otp-digit-cell" id="digit_1" pattern="[0-9]*" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-digit-cell" id="digit_2" pattern="[0-9]*" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-digit-cell" id="digit_3" pattern="[0-9]*" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-digit-cell" id="digit_4" pattern="[0-9]*" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-digit-cell" id="digit_5" pattern="[0-9]*" inputmode="numeric">
                        <input type="text" maxlength="1" class="otp-digit-cell" id="digit_6" pattern="[0-9]*" inputmode="numeric">
                    </div>

                    <button type="button" class="btn-auth-primary" id="btn_verify_otp" onclick="handleVerifyOtp()">
                        <i class="fa fa-check-circle"></i> Verify & Login
                    </button>

                    <div style="text-align: center; margin-top: 18px; font-size: 13px; color: #64748B;">
                        Didn't receive the code? 
                        <span id="resend_timer_wrap">Resend in <strong id="resend_countdown" style="color: #2563EB;">30</strong>s</span>
                        <a href="javascript:;" id="btn_resend_otp" style="display: none; color: #2563EB; font-weight: 700;" onclick="handleSendOtp(true)">Resend OTP</a>
                    </div>

                    <div class="auth-footer-links" style="margin-top: 18px; padding-top: 14px;">
                        <a href="javascript:;" onclick="showPrimaryView()" style="color: #64748B; font-weight: 600;">
                            <i class="fa fa-arrow-left"></i> Change Email / Back
                        </a>
                    </div>
                </div>

                {{-- =========================================================================
                     STATE 3: PASSWORD LOGIN SCREEN (SECONDARY METHOD)
                     ========================================================================= --}}
                <div id="login_view_password" style="display: none;">
                    <div class="auth-header-title">Login with Password</div>
                    <div class="auth-header-subtitle">
                        Enter your email and password to access your account
                    </div>

                    <form id="password_login_form" method="POST" action="{{ route('login') }}" onsubmit="handlePasswordSubmit(event)">
                        @csrf
                        <input type="hidden" name="candidate_or_employer" id="pwd_candidate_or_employer" value="{{ $c_or_e }}">

                        <div class="auth-input-group">
                            <label class="auth-input-label">Email Address</label>
                            <input type="email" name="email" id="pwd_email_input" class="auth-input-field" placeholder="name@example.com" required autocomplete="email">
                        </div>

                        <div class="auth-input-group">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                <label class="auth-input-label" style="margin-bottom: 0;">Password</label>
                                <a href="{{ route('password.request') }}" style="font-size: 12px; color: #2563EB; font-weight: 600;">Forgot Password?</a>
                            </div>
                            <div class="pwd-field-wrap">
                                <input type="password" name="password" id="pwd_password_input" class="auth-input-field" placeholder="Enter your password" required autocomplete="current-password">
                                <button type="button" class="btn-pwd-eye" onclick="togglePasswordVisibility(this)" tabindex="-1" title="Toggle Password Visibility">
                                    <i class="fa fa-eye-slash"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn-auth-primary" id="btn_pwd_login">
                            <i class="fa fa-sign-in"></i> Login
                        </button>
                    </form>

                    <div class="auth-footer-links" style="margin-top: 20px;">
                        <a href="javascript:;" onclick="showPrimaryView()" style="color: #2563EB; font-weight: 700;">
                            <i class="fa fa-bolt text-warning"></i> Back to Instant OTP Login
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
var resendTimer = null;
var cooldownSeconds = 30;

function selectRole(role) {
    document.getElementById('selected_role').value = role;
    document.getElementById('pwd_candidate_or_employer').value = role;
    
    // Update active pill
    document.querySelectorAll('.custom-login-pills .nav-link').forEach(function(el) {
        el.classList.remove('active');
    });
    var targetPill = Array.from(document.querySelectorAll('.custom-login-pills .nav-link')).find(function(el) {
        return el.textContent.toLowerCase().includes(role.toLowerCase());
    });
    if (targetPill) targetPill.classList.add('active');

    // Update password form action based on role
    var form = document.getElementById('password_login_form');
    if (role === 'employer') {
        form.action = "{{ route('company.login') }}";
    } else {
        form.action = "{{ route('login') }}";
    }

    // Update Register Link
    var regLink = document.getElementById('register_redirect_link');
    if (regLink) {
        regLink.href = "{{ route('register') }}?tab=" + role;
    }

    clearAlert();
}

function showPrimaryView() {
    document.getElementById('login_view_primary').style.display = 'block';
    document.getElementById('login_view_otp').style.display = 'none';
    document.getElementById('login_view_password').style.display = 'none';
    clearAlert();
}

function showPasswordView() {
    var primaryEmail = document.getElementById('primary_email_input').value.trim();
    if (primaryEmail) {
        document.getElementById('pwd_email_input').value = primaryEmail;
    }
    document.getElementById('login_view_primary').style.display = 'none';
    document.getElementById('login_view_otp').style.display = 'none';
    document.getElementById('login_view_password').style.display = 'block';
    document.getElementById('pwd_password_input').focus();
    clearAlert();
}

function showOtpView(maskedEmail) {
    document.getElementById('login_view_primary').style.display = 'none';
    document.getElementById('login_view_otp').style.display = 'block';
    document.getElementById('login_view_password').style.display = 'none';
    
    if (maskedEmail) {
        document.getElementById('otp_subtitle_sent_to').innerHTML = 'OTP sent to: <strong>' + maskedEmail + '</strong>';
    }
    
    // Clear & focus first digit
    for (var i = 1; i <= 6; i++) {
        document.getElementById('digit_' + i).value = '';
    }
    document.getElementById('digit_1').focus();
    startResendCountdown(30);
}

function showAlert(type, msg) {
    var alertBox = document.getElementById('auth_global_alert');
    var icon = (type === 'success') ? 'fa-check-circle' : 'fa-exclamation-triangle';
    var cssClass = (type === 'success') ? 'alert-auth-success' : 'alert-auth-error';
    alertBox.innerHTML = '<div class="alert-auth-msg ' + cssClass + '"><i class="fa ' + icon + '"></i> <div>' + msg + '</div></div>';
}

function clearAlert() {
    document.getElementById('auth_global_alert').innerHTML = '';
}

function handleSendOtp(isResend) {
    var email = document.getElementById('primary_email_input').value.trim();
    var role = document.getElementById('selected_role').value;
    var btn = document.getElementById('btn_send_otp');

    if (!email) {
        showAlert('error', 'Please enter your email address to continue.');
        document.getElementById('primary_email_input').focus();
        return;
    }

    clearAlert();
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Sending 6-Digit Code...';
    }

    $.ajax({
        url: "{{ route('otp.send') }}",
        type: 'POST',
        data: {
            _token: "{{ csrf_token() }}",
            email: email,
            user_type: role
        },
        dataType: 'json',
        success: function(resp) {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-bolt"></i> Send 6-Digit OTP';
            }
            if (resp.status === 'ok') {
                showAlert('success', resp.message);
                showOtpView(resp.masked_email);
            } else {
                showAlert('error', resp.message || 'Unable to send OTP.');
            }
        },
        error: function(xhr) {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-bolt"></i> Send 6-Digit OTP';
            }
            var err = 'Failed to send OTP code. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                err = xhr.responseJSON.message;
            }
            showAlert('error', err);
        }
    });
}

function handleVerifyOtp() {
    var code = '';
    for (var i = 1; i <= 6; i++) {
        code += document.getElementById('digit_' + i).value.trim();
    }

    if (code.length < 6) {
        showAlert('error', 'Please enter the complete 6-digit OTP code.');
        return;
    }

    var email = document.getElementById('primary_email_input').value.trim();
    var role = document.getElementById('selected_role').value;
    var btn = document.getElementById('btn_verify_otp');

    clearAlert();
    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Verifying...';

    $.ajax({
        url: "{{ route('otp.verify') }}",
        type: 'POST',
        data: {
            _token: "{{ csrf_token() }}",
            email: email,
            otp_code: code,
            user_type: role
        },
        dataType: 'json',
        success: function(resp) {
            if (resp.status === 'success') {
                showAlert('success', resp.message);
                btn.innerHTML = '<i class="fa fa-check"></i> Verified! Redirecting...';
                setTimeout(function() {
                    window.location.href = resp.redirect_url;
                }, 800);
            } else {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-check-circle"></i> Verify & Login';
                showAlert('error', resp.message);
            }
        },
        error: function(xhr) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-check-circle"></i> Verify & Login';
            var err = 'Verification failed. Please check the OTP code.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                err = xhr.responseJSON.message;
            }
            showAlert('error', err);
        }
    });
}

function handlePasswordSubmit(e) {
    // Normal form submission
    var btn = document.getElementById('btn_pwd_login');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Signing in...';
    }
}

function startResendCountdown(seconds) {
    clearInterval(resendTimer);
    cooldownSeconds = seconds;
    var timerWrap = document.getElementById('resend_timer_wrap');
    var countdownEl = document.getElementById('resend_countdown');
    var resendBtn = document.getElementById('btn_resend_otp');

    timerWrap.style.display = 'inline';
    resendBtn.style.display = 'none';
    countdownEl.innerText = cooldownSeconds;

    resendTimer = setInterval(function() {
        cooldownSeconds--;
        countdownEl.innerText = cooldownSeconds;
        if (cooldownSeconds <= 0) {
            clearInterval(resendTimer);
            timerWrap.style.display = 'none';
            resendBtn.style.display = 'inline';
        }
    }, 1000);
}

// 6-Digit Auto-Focus & Paste Handler
document.querySelectorAll('.otp-digit-cell').forEach(function(input, idx, inputs) {
    input.addEventListener('input', function(e) {
        if (this.value.length === 1) {
            if (idx < inputs.length - 1) {
                inputs[idx + 1].focus();
            } else {
                handleVerifyOtp();
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
            handleVerifyOtp();
        }
    });
});

// Initialize role on load
document.addEventListener('DOMContentLoaded', function() {
    var initialRole = "{{ $c_or_e }}";
    selectRole(initialRole);
});
</script>
@endpush

@include('includes.footer')
@endsection
