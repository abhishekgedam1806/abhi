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
            <i class="fa fa-lock"></i> Set New Password
        </div>
        <h1 class="admin-auth-title">Create New Password</h1>
        <p class="admin-auth-subtitle">Please enter your new password to restore access to your account.</p>
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

    @if ($errors->has('password_confirmation'))
    <div class="alert alert-danger">
        <button class="close" data-close="alert"></button>
        <span><i class="fa fa-exclamation-circle"></i> {{ $errors->first('password_confirmation') }}</span>
    </div>
    @endif

    <form role="form" method="POST" action="{{ route('admin.password.request') }}">
        {{ csrf_field() }}
        <input type="hidden" name="token" value="{{ $token }}">

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
                       value="{{ old('email', $email) }}" 
                       required 
                       autofocus />
            </div>
        </div>

        <!-- Password Field -->
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} admin-field-group">
            <label class="admin-field-label">New Password</label>
            <div class="admin-input-wrap">
                <span class="admin-input-icon"><i class="fa fa-lock"></i></span>
                <input class="form-control admin-auth-input placeholder-no-fix" 
                       type="password" 
                       placeholder="••••••••••••" 
                       name="password" 
                       required />
            </div>
        </div>

        <!-- Confirm Password Field -->
        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }} admin-field-group">
            <label class="admin-field-label">Confirm New Password</label>
            <div class="admin-input-wrap">
                <span class="admin-input-icon"><i class="fa fa-lock"></i></span>
                <input class="form-control admin-auth-input placeholder-no-fix" 
                       type="password" 
                       placeholder="••••••••••••" 
                       name="password_confirmation" 
                       required />
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn admin-btn-primary">
            <span>Reset Password</span>
            <i class="fa fa-check"></i>
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