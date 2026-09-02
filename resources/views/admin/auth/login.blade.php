@extends('admin.layouts.login_layout')

@section('content')
<div class="admin-auth-card">
    <!-- Brand / Logo Header -->
    <div class="admin-auth-header">
        <div class="admin-auth-logo-box">
            <a href="{{ url('/') }}" title="{{ $siteSetting->site_name }}">
                <img src="{{ asset('/') }}sitesetting_images/mid/{{ $siteSetting->site_logo }}" alt="{{ $siteSetting->site_name }}" />
            </a>
        </div>
        <div class="admin-auth-badge">
            <i class="fa fa-shield"></i> Secure Admin Portal
        </div>
        <h1 class="admin-auth-title">Welcome Back</h1>
        <p class="admin-auth-subtitle">Please enter your credentials to access the administrative dashboard.</p>
    </div>

    <!-- Error Alerts -->
    <div class="alert alert-danger display-hide login-js-alert">
        <button class="close" data-close="alert"></button>
        <span><i class="fa fa-exclamation-circle"></i> Please enter both email and password.</span>
    </div>

    @if ($errors->has('email'))
    <div class="alert alert-danger">
        <button class="close" data-close="alert"></button>
        <span><i class="fa fa-exclamation-circle"></i> {{ $errors->first('email') }}</span>
    </div>
    @endif

    @if ($errors->has('password'))
    <div class="alert alert-danger">
        <button class="close" data-close="alert"></button>
        <span><i class="fa fa-exclamation-circle"></i> {{ $errors->first('password') }}</span>
    </div>
    @endif

    <!-- Login Form -->
    <form class="login-form" role="form" novalidate="novalidate" method="POST" action="{{ route('admin.login') }}">
        {{ csrf_field() }}

        <!-- Email Field -->
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} admin-field-group">
            <label class="admin-field-label">Email Address</label>
            <div class="admin-input-wrap">
                <span class="admin-input-icon"><i class="fa fa-envelope-o"></i></span>
                <input class="form-control admin-auth-input placeholder-no-fix" 
                       type="email" 
                       autocomplete="username" 
                       placeholder="admin@jobportal.com" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus />
            </div>
        </div>

        <!-- Password Field -->
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} admin-field-group">
            <label class="admin-field-label">Password</label>
            <div class="admin-input-wrap">
                <span class="admin-input-icon"><i class="fa fa-lock"></i></span>
                <input class="form-control admin-auth-input placeholder-no-fix" 
                       type="password" 
                       id="adminPasswordField"
                       autocomplete="current-password" 
                       placeholder="••••••••••••" 
                       name="password" 
                       required />
                <button type="button" class="admin-pwd-toggle" id="adminPwdToggle" onclick="toggleAdminPasswordVisibility()" title="Show/Hide Password">
                    <i class="fa fa-eye" id="adminEyeIcon"></i>
                </button>
            </div>
        </div>

        <!-- Options Row: Remember Me & Forgot Password -->
        <div class="admin-auth-options">
            <label class="admin-checkbox-label">
                <input type="checkbox" name="remember" id="rememberMeCheckbox" />
                <span class="admin-checkbox-custom"></span>
                <span class="admin-checkbox-text">Remember me</span>
            </label>
            <a class="admin-forgot-link" href="{{ route('admin.password.request') }}">
                Forgot Password?
            </a>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn admin-btn-primary">
            <span>Sign In to Dashboard</span>
            <i class="fa fa-arrow-right"></i>
        </button>

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
    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'fa fa-eye-slash';
    } else {
        field.type = 'password';
        icon.className = 'fa fa-eye';
    }
}
</script>
@endpush