@extends('admin.layouts.login_layout')

@section('content')
<div class="admin-auth-card">
    <!-- Brand / Logo Header -->
    <div class="admin-auth-header">
        <div class="admin-auth-logo-box">
            <a href="{{ url('/') }}" title="{{ $siteSetting->site_name ?? 'JobNBiz' }}">
                @php
                    $authLogoVersion = isset($siteSetting->updated_at) ? strtotime($siteSetting->updated_at) : time();
                    $authLogoFile = !empty($siteSetting->site_logo) ? $siteSetting->site_logo : '';
                    if (!empty($authLogoFile) && file_exists(public_path('sitesetting_images/thumb/' . $authLogoFile))) {
                        $authPrimaryLogo = asset('sitesetting_images/thumb/' . $authLogoFile);
                    } elseif (!empty($authLogoFile) && file_exists(public_path('sitesetting_images/' . $authLogoFile))) {
                        $authPrimaryLogo = asset('sitesetting_images/' . $authLogoFile);
                    } elseif (file_exists(public_path('images/jobnbiz-logo-dark.png'))) {
                        $authPrimaryLogo = asset('images/jobnbiz-logo-dark.png');
                    } else {
                        $authPrimaryLogo = asset('images/logo.png');
                    }
                @endphp
                <img src="{{ $authPrimaryLogo }}?v={{ $authLogoVersion }}" 
                     alt="{{ $siteSetting->site_name ?? 'JobNBiz' }}" 
                     style="max-height: 48px; width: auto; object-fit: contain;" 
                     onerror="this.onerror=null; this.src='{{ asset('images/jobnbiz-logo-dark.png') }}';" />
            </a>
        </div>
        <div class="admin-auth-badge">
            <i class="fa fa-key"></i> Password Recovery
        </div>
        <h1 class="admin-auth-title">Reset Password</h1>
        <p class="admin-auth-subtitle">Enter your registered email address to receive password reset instructions.</p>
    </div>

    @if (session('status'))
    <div class="alert alert-success">
        <span><i class="fa fa-check-circle"></i> {{ session('status') }}</span>
    </div>
    @endif

    @if ($errors->has('email'))
    <div class="alert alert-danger">
        <button class="close" data-close="alert"></button>
        <span><i class="fa fa-exclamation-circle"></i> {{ $errors->first('email') }}</span>
    </div>
    @endif

    <form class="forget-form" role="form" method="POST" action="{{ route('admin.password.email') }}">
        {{ csrf_field() }}

        <!-- Email Field -->
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} admin-field-group">
            <label class="admin-field-label">Email Address</label>
            <div class="admin-input-wrap">
                <span class="admin-input-icon"><i class="fa fa-envelope-o"></i></span>
                <input class="form-control admin-auth-input placeholder-no-fix" 
                       type="email" 
                       autocomplete="email" 
                       placeholder="admin@jobportal.com" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus />
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn admin-btn-primary">
            <span>Send Reset Link</span>
            <i class="fa fa-paper-plane"></i>
        </button>

        <!-- Back to Login -->
        <div class="admin-back-wrap">
            <a href="{{ route('admin.login') }}" class="admin-back-link">
                <i class="fa fa-angle-left"></i> Back to Login
            </a>
        </div>
    </form>
</div>
@endsection