@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end --> 

<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title' => __('Business Owner Profile')])
<!-- Inner Page Title end -->

<div class="listpgWraper">
    <div class="container">
        <div class="row">
            {{-- 1. STRICTLY BUSINESS OWNER DASHBOARD SIDEBAR MENU --}}
            @include('includes.business_dashboard_menu')

            {{-- 2. MAIN BUSINESS OWNER PROFILE SETTINGS --}}
            <div class="col-lg-9 col-md-8">
                
                @include('flash::message')

                <div class="useraccountwrap" style="max-width:100% !important; margin: 0 0 30px 0 !important;">
                    <div class="userccount" style="background:#fff; border: 1px solid #E2E8F0; border-radius: 16px; padding: 28px; box-shadow: 0 1px 4px rgba(0,0,0,0.03);">
                        
                        <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #F1F5F9; padding-bottom: 16px; margin-bottom: 24px;">
                            <div>
                                <h3 style="font-size: 20px; font-weight: 800; color: #0F172A; margin: 0;">
                                    <i class="fa fa-user-circle text-primary"></i> Business Owner Account Settings
                                </h3>
                                <div style="font-size: 13px; color: #64748B; margin-top: 3px;">
                                    Manage your name, contact phone, login email & security password
                                </div>
                            </div>
                            @if(isset($userPackage) && $userPackage)
                            <div style="text-align: right;">
                                <span class="badge" style="background:#EFF6FF;color:#2563EB;font-size:12px;font-weight:700;padding:6px 12px;border-radius:12px;border:1px solid #BFDBFE;">
                                    <i class="fa fa-diamond"></i> {{ $userPackage->package_title }}
                                </span>
                            </div>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('business.owner.profile.update') }}" enctype="multipart/form-data">
                            @csrf

                            {{-- 1. PROFILE PHOTO SECTION --}}
                            <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                                <label style="font-size: 14px; font-weight: 700; color: #0F172A; margin-bottom: 12px; display: block;">
                                    <i class="fa fa-camera text-primary"></i> Profile Photo / Avatar
                                </label>
                                <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
                                    <div style="width: 76px; height: 76px; border-radius: 50%; background: #fff; border: 2px dashed #CBD5E1; overflow: hidden; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
                                        @if($user->image)
                                        <img id="biz_user_avatar_preview" src="{{ asset('user_images/' . $user->image) }}" style="width: 100%; height: 100%; object-fit: cover;" alt="">
                                        @else
                                        <i id="biz_user_avatar_placeholder" class="fa fa-user text-muted" style="font-size: 34px; color: #94A3B8;"></i>
                                        <img id="biz_user_avatar_preview" src="" style="width: 100%; height: 100%; object-fit: cover; display: none;" alt="">
                                        @endif
                                    </div>
                                    <div style="flex: 1; min-width: 240px;">
                                        <input type="file" name="image" id="biz_user_image_input" accept="image/*" class="form-control" style="height: auto; padding: 7px 10px; font-size: 13px; border-radius: 8px;">
                                        <div style="font-size: 12px; color: #64748B; margin-top: 5px;">
                                            Upload JPG, PNG, WEBP (Max: 5MB)
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. PERSONAL & ACCOUNT INFORMATION --}}
                            <div class="formpanel">
                                <div class="row">
                                    <div class="col-md-6 col-12 formrow mb-3 {{ $errors->has('first_name') ? 'has-error' : '' }}">
                                        <label style="font-size: 13px; font-weight: 700; color: #334155;">First Name *</label>
                                        <input type="text" name="first_name" class="form-control" required value="{{ old('first_name', $user->first_name ?: $user->name) }}" style="height: 42px; border-radius: 8px;">
                                        @if ($errors->has('first_name'))
                                        <span class="help-block text-danger" style="font-size:12px;"><strong>{{ $errors->first('first_name') }}</strong></span>
                                        @endif
                                    </div>

                                    <div class="col-md-6 col-12 formrow mb-3 {{ $errors->has('last_name') ? 'has-error' : '' }}">
                                        <label style="font-size: 13px; font-weight: 700; color: #334155;">Last Name *</label>
                                        <input type="text" name="last_name" class="form-control" required value="{{ old('last_name', $user->last_name) }}" style="height: 42px; border-radius: 8px;">
                                        @if ($errors->has('last_name'))
                                        <span class="help-block text-danger" style="font-size:12px;"><strong>{{ $errors->first('last_name') }}</strong></span>
                                        @endif
                                    </div>

                                    <div class="col-md-6 col-12 formrow mb-3 {{ $errors->has('email') ? 'has-error' : '' }}">
                                        <label style="font-size: 13px; font-weight: 700; color: #334155;">Email Address *</label>
                                        <input type="email" name="email" class="form-control" required value="{{ old('email', $user->email) }}" style="height: 42px; border-radius: 8px;">
                                        @if ($errors->has('email'))
                                        <span class="help-block text-danger" style="font-size:12px;"><strong>{{ $errors->first('email') }}</strong></span>
                                        @endif
                                    </div>

                                    <div class="col-md-6 col-12 formrow mb-3 {{ $errors->has('phone') ? 'has-error' : '' }}">
                                        <label style="font-size: 13px; font-weight: 700; color: #334155;">Phone / Mobile Number</label>
                                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone ?: $user->mobile_num) }}" placeholder="e.g. 9876543210" style="height: 42px; border-radius: 8px;">
                                        @if ($errors->has('phone'))
                                        <span class="help-block text-danger" style="font-size:12px;"><strong>{{ $errors->first('phone') }}</strong></span>
                                        @endif
                                    </div>
                                </div>

                                {{-- 3. SECURITY / PASSWORD CHANGE --}}
                                <div style="background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 12px; padding: 18px; margin: 16px 0 24px 0;">
                                    <div style="font-size: 13.5px; font-weight: 700; color: #92400E; margin-bottom: 12px;">
                                        <i class="fa fa-lock"></i> Change Password <span style="font-weight: normal; font-size: 12px; color: #B45309;">(Leave blank to keep existing password)</span>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-12 formrow mb-2 {{ $errors->has('password') ? 'has-error' : '' }}">
                                            <label style="font-size: 12.5px; font-weight: 600; color: #78350F;">New Password</label>
                                            <div class="pwd-field-wrap">
                                                <input type="password" name="password" class="form-control" placeholder="New Password (min 6 chars)" style="height: 40px; border-radius: 8px;">
                                                <button type="button" class="btn-pwd-eye" onclick="togglePasswordVisibility(this)" tabindex="-1" title="Toggle Password Visibility"><i class="fa fa-eye-slash"></i></button>
                                            </div>
                                            @if ($errors->has('password'))
                                            <span class="help-block text-danger" style="font-size:12px;"><strong>{{ $errors->first('password') }}</strong></span>
                                            @endif
                                        </div>

                                        <div class="col-md-6 col-12 formrow mb-2 {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                                            <label style="font-size: 12.5px; font-weight: 600; color: #78350F;">Confirm New Password</label>
                                            <div class="pwd-field-wrap">
                                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm New Password" style="height: 40px; border-radius: 8px;">
                                                <button type="button" class="btn-pwd-eye" onclick="togglePasswordVisibility(this)" tabindex="-1" title="Toggle Password Visibility"><i class="fa fa-eye-slash"></i></button>
                                            </div>
                                            @if ($errors->has('password_confirmation'))
                                            <span class="help-block text-danger" style="font-size:12px;"><strong>{{ $errors->first('password_confirmation') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- SUBMIT BUTTON --}}
                                <div style="display: flex; gap: 12px; align-items: center; margin-top: 10px;">
                                    <button type="submit" class="btn btn-primary" style="background: #2563EB; border: none; border-radius: 10px; padding: 12px 28px; font-size: 14px; font-weight: 700; box-shadow: 0 4px 12px rgba(37,99,235,0.25);">
                                        <i class="fa fa-check"></i> Save Profile Settings
                                    </button>
                                    <a href="{{ route('business.dashboard') }}" class="btn btn-default" style="border-radius: 10px; padding: 12px 20px; font-size: 14px; font-weight: 600;">
                                        Cancel
                                    </a>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('biz_user_image_input').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        var reader = new FileReader();
        reader.onload = function(evt) {
            var preview = document.getElementById('biz_user_avatar_preview');
            preview.src = evt.target.result;
            preview.style.display = 'block';
            var placeholder = document.getElementById('biz_user_avatar_placeholder');
            if (placeholder) placeholder.style.display = 'none';
        }
        reader.readAsDataURL(e.target.files[0]);
    }
});
</script>
@endpush

@include('includes.footer')
@endsection
