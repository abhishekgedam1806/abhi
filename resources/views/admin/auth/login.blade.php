@extends('admin.layouts.login_layout')

@section('content')
<div class="admin-auth-card">
    <!-- Brand / Logo Header -->
    <div class="admin-auth-header">
        <div class="admin-auth-logo-box">
            <a href="{{ url('/') }}" title="{{ $siteSetting->site_name ?? 'Job Portal' }}">
                @if(!empty($siteSetting->site_logo))
                    <img src="{{ asset('/') }}sitesetting_images/mid/{{ $siteSetting->site_logo }}" alt="{{ $siteSetting->site_name ?? 'Job Portal' }}" />
                @else
                    <span class="admin-auth-brand-text">{{ $siteSetting->site_name ?? 'Job Portal' }}</span>
                @endif
            </a>
        </div>
        <div class="admin-auth-badge">
            <i class="fa fa-shield"></i> Secure Admin Portal
        </div>
        <h1 class="admin-auth-title">Welcome Back</h1>
        <p class="admin-auth-subtitle">Sign in to access your administrative control center</p>
    </div>

    <!-- Client-side JS Validation Alert -->
    <div class="admin-alert admin-alert-danger login-js-alert" style="display: none;">
        <i class="fa fa-exclamation-circle admin-alert-icon"></i>
        <div class="admin-alert-content">Please enter both your email address and password.</div>
        <button type="button" class="admin-alert-close" onclick="$(this).closest('.admin-alert').slideUp(150);">&times;</button>
    </div>

    <!-- Server-side Session/Errors Alert -->
    @if ($errors->any())
    <div class="admin-alert admin-alert-danger">
        <i class="fa fa-exclamation-circle admin-alert-icon"></i>
        <div class="admin-alert-content">
            @if ($errors->has('email'))
                {{ $errors->first('email') }}
            @elseif ($errors->has('password'))
                {{ $errors->first('password') }}
            @else
                {{ $errors->first() }}
            @endif
        </div>
        <button type="button" class="admin-alert-close" onclick="$(this).closest('.admin-alert').slideUp(150);">&times;</button>
    </div>
    @endif

    @if (session('status'))
    <div class="admin-alert admin-alert-success">
        <i class="fa fa-check-circle admin-alert-icon"></i>
        <div class="admin-alert-content">{{ session('status') }}</div>
        <button type="button" class="admin-alert-close" onclick="$(this).closest('.admin-alert').slideUp(150);">&times;</button>
    </div>
    @endif

    <!-- Login Form -->
    <form class="login-form" role="form" method="POST" action="{{ route('admin.login') }}" novalidate>
        {{ csrf_field() }}

        <!-- Email Field -->
        <div class="admin-field-group {{ $errors->has('email') ? 'has-error' : '' }}">
            <label class="admin-field-label" for="adminEmailInput">Email Address</label>
            <div class="admin-input-wrap">
                <span class="admin-input-icon"><i class="fa fa-envelope-o"></i></span>
                <input class="admin-auth-input" 
                       id="adminEmailInput"
                       type="email" 
                       name="email" 
                       value="{{ old('email', 'softwarestore.biz@gmail.com') }}" 
                       placeholder="admin@jobportal.com" 
                       autocomplete="email"
                       required 
                       autofocus />
            </div>
        </div>

        <!-- Password Field -->
        <div class="admin-field-group {{ $errors->has('password') ? 'has-error' : '' }}">
            <label class="admin-field-label" for="adminPasswordField">Password</label>
            <div class="admin-input-wrap">
                <span class="admin-input-icon"><i class="fa fa-lock"></i></span>
                <input class="admin-auth-input" 
                       id="adminPasswordField"
                       type="password" 
                       name="password" 
                       placeholder="Enter your password" 
                       autocomplete="current-password"
                       required />
                <button type="button" class="admin-pwd-toggle" id="adminPwdToggle" onclick="toggleAdminPasswordVisibility()" title="Toggle password visibility" tabindex="-1">
                    <i class="fa fa-eye" id="adminEyeIcon"></i>
                </button>
            </div>
        </div>

        <!-- Options Row: Remember Me & Forgot Password -->
        <div class="admin-auth-options">
            <label class="admin-checkbox-label" for="rememberMeCheckbox">
                <input type="checkbox" name="remember" id="rememberMeCheckbox" checked />
                <span class="admin-checkbox-custom"></span>
                <span class="admin-checkbox-text">Remember me</span>
            </label>
            <a class="admin-forgot-link" href="{{ route('admin.password.request') }}">
                Forgot Password?
            </a>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="admin-btn-primary" id="adminSubmitBtn">
            <span class="btn-text">Sign In to Dashboard</span>
            <i class="fa fa-arrow-right btn-icon"></i>
        </button>

        <!-- Credentials Quick Info Card -->
        <div class="admin-credentials-hint">
            <div class="admin-hint-header">
                <i class="fa fa-info-circle"></i> <span>Default Login Credentials:</span>
            </div>
            <div class="admin-hint-row">
                <span class="hint-key">Email:</span>
                <code class="hint-val" onclick="copyHint('softwarestore.biz@gmail.com', this)" title="Click to copy">softwarestore.biz@gmail.com</code>
            </div>
            <div class="admin-hint-row">
                <span class="hint-key">Password:</span>
                <code class="hint-val" onclick="copyHint('admin123', this)" title="Click to copy">admin123</code>
            </div>
        </div>

        <!-- Back to Website Link -->
        <div class="admin-back-wrap">
            <a href="{{ url('/') }}" class="admin-back-link">
                <i class="fa fa-angle-left"></i> Return to Job Portal
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
function toggleAdminPasswordVisibility() {
    var field = document.getElementById('adminPasswordField');
    var icon = document.getElementById('adminEyeIcon');
    if (!field || !icon) return;
    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'fa fa-eye-slash';
    } else {
        field.type = 'password';
        icon.className = 'fa fa-eye';
    }
}

function copyHint(text, el) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(function() {
            var orig = el.innerText;
            el.innerText = 'Copied!';
            setTimeout(function() { el.innerText = orig; }, 1500);
        });
    }
}
</script>
@endpush