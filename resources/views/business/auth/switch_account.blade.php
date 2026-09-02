@extends('layouts.app')

@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->

<style>
.biz-switch-wrap {
    background: #F8FAFC;
    min-height: 80vh;
    padding: 60px 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}
.biz-switch-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    max-width: 540px;
    width: 100%;
    padding: 40px 32px;
    text-align: center;
}
.biz-switch-icon-box {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #EFF6FF;
    border: 2px solid #BFDBFE;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 24px;
}
.biz-switch-icon-box i {
    font-size: 36px;
    color: #2563EB;
}
.biz-switch-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #FEF3C7;
    color: #92400E;
    border: 1px solid #FDE68A;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 4px 12px;
    border-radius: 50px;
    margin-bottom: 14px;
}
.biz-switch-title {
    font-size: 24px;
    font-weight: 800;
    color: #0F172A;
    margin: 0 0 12px;
    letter-spacing: -0.4px;
}
.biz-switch-desc {
    font-size: 14.5px;
    color: #475569;
    line-height: 1.6;
    margin-bottom: 24px;
}
.biz-current-user-box {
    background: #F1F5F9;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 12px 16px;
    margin-bottom: 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    text-align: left;
}
.biz-current-user-info {
    font-size: 13px;
    color: #334155;
}
.biz-current-user-info strong {
    color: #0F172A;
    display: block;
    font-size: 14px;
}
.biz-role-tag {
    background: #E2E8F0;
    color: #475569;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 6px;
    text-transform: uppercase;
}
.biz-switch-btn-stack {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.btn-biz-primary {
    background: #2563EB;
    color: #FFFFFF !important;
    font-weight: 700;
    font-size: 15px;
    padding: 13px 24px;
    border-radius: 12px;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 4px 14px rgba(37,99,235,0.35);
    transition: all 0.2s ease;
}
.btn-biz-primary:hover {
    background: #1D4ED8;
    transform: translateY(-1px);
}
.btn-biz-secondary {
    background: #FFFFFF;
    color: #2563EB !important;
    border: 1.5px solid #2563EB;
    font-weight: 700;
    font-size: 15px;
    padding: 12px 24px;
    border-radius: 12px;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.2s ease;
}
.btn-biz-secondary:hover {
    background: #EFF6FF;
}
.btn-biz-back {
    color: #64748B !important;
    font-size: 13.5px;
    font-weight: 600;
    text-decoration: none !important;
    margin-top: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.btn-biz-back:hover {
    color: #0F172A !important;
    text-decoration: underline !important;
}
</style>

<div class="biz-switch-wrap">
    <div class="biz-switch-card">
        <div class="biz-switch-icon-box">
            <i class="fa fa-briefcase"></i>
        </div>

        <div>
            <span class="biz-switch-badge">
                <i class="fa fa-shield"></i> Business Account Required
            </span>
        </div>

        <h1 class="biz-switch-title">Add Your Business</h1>

        <p class="biz-switch-desc">
            You're currently logged in as a <strong>Job Seeker</strong>.<br>
            Business listings are managed through a separate <strong>Business Account</strong>.
            Please log in or create a Business Account to continue.
        </p>

        @if(Auth::check())
        <div class="biz-current-user-box">
            <div class="biz-current-user-info">
                <strong>{{ Auth::user()->name }}</strong>
                <span>{{ Auth::user()->email }}</span>
            </div>
            <span class="biz-role-tag">Job Seeker</span>
        </div>
        @endif

        <div class="biz-switch-btn-stack">
            <a href="{{ route('business.login') }}" class="btn-biz-primary">
                <i class="fa fa-sign-in"></i> Login as Business
            </a>
            <a href="{{ route('business.register') }}" class="btn-biz-secondary">
                <i class="fa fa-plus-circle"></i> Create Business Account
            </a>
            <a href="{{ route('home') }}" class="btn-biz-back">
                <i class="fa fa-arrow-left"></i> Return to Job Seeker Dashboard
            </a>
        </div>
    </div>
</div>

@include('includes.footer')
@endsection
