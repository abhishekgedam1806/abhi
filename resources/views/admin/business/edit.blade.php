@extends('admin.layouts.admin_layout')
@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
            <div>
                <h1 style="font-size:22px;font-weight:800;color:#0F172A;margin:0;">Edit Business: {{ $business->name }}</h1>
                <div style="font-size:13px;color:#64748B;margin-top:3px;">Manage listing details, verification, logo & assigned subscription package</div>
            </div>
            <div>
                <a href="{{ route('business.detail', $business->slug) }}" target="_blank" class="btn btn-default" style="font-weight:600;border-radius:8px;">
                    <i class="fa fa-external-link"></i> View Public Listing
                </a>
                <a href="{{ route('admin.list.businesses') }}" class="btn btn-default" style="font-weight:600;border-radius:8px;">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        @include('flash::message')

        <div class="portlet light bordered" style="border-radius:14px;box-shadow:0 1px 4px rgba(0,0,0,0.04);max-width:920px;">
            <div class="portlet-body form">
                <form action="{{ route('admin.update.business', $business->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    {{-- 1. LOGO & PACKAGE HEADER SUMMARY CARD --}}
                    <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:12px;padding:20px;margin-bottom:24px;">
                        <div class="row" style="align-items:center;">
                            
                            {{-- Logo Preview & Upload --}}
                            <div class="col-md-6 mb-3 mb-md-0" style="border-right:1px solid #E2E8F0;">
                                <label style="font-size:13px;font-weight:700;color:#0F172A;margin-bottom:8px;display:block;">
                                    <i class="fa fa-image text-primary"></i> Business Logo
                                </label>
                                <div style="display:flex;align-items:center;gap:16px;">
                                    <div style="width:68px;height:68px;border-radius:12px;background:#fff;border:2px dashed #CBD5E1;display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;">
                                        @if($business->logo)
                                        <img id="admin_logo_preview" src="{{ $business->getLogoUrl() }}" style="width:100%;height:100%;object-fit:cover;" alt="">
                                        @else
                                        <i id="admin_logo_placeholder" class="fa fa-building text-muted" style="font-size:28px;color:#94A3B8;"></i>
                                        <img id="admin_logo_preview" src="" style="width:100%;height:100%;object-fit:cover;display:none;" alt="">
                                        @endif
                                    </div>
                                    <div style="flex:1;">
                                        <input type="file" name="logo" id="admin_logo_file" accept="image/*" class="form-control" style="font-size:12px;height:auto;padding:5px 8px;border-radius:8px;">
                                        <div style="font-size:11.5px;color:#94A3B8;margin-top:4px;">Recommended: 300x300 PNG, JPG, or WEBP</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Package & Subscription Assignment --}}
                            <div class="col-md-6" style="padding-left:24px;">
                                <label style="font-size:13px;font-weight:700;color:#0F172A;margin-bottom:8px;display:block;">
                                    <i class="fa fa-credit-card text-success"></i> Business Package / Subscription Plan
                                </label>
                                @php
                                    $currentPkgId = $business->package_id ?: ($business->user ? $business->user->business_package_id : 0);
                                @endphp
                                <select name="package_id" class="form-control" style="font-weight:700;font-size:13.5px;height:40px;border-radius:8px;color:#1E293B;">
                                    <option value="0">-- Free / No Package Assigned --</option>
                                    @foreach($packages as $pkg)
                                    <option value="{{ $pkg->id }}" {{ $currentPkgId == $pkg->id ? 'selected' : '' }}>
                                        {{ $pkg->package_title }} — ₹{{ number_format($pkg->package_price, 0) }} ({{ $pkg->package_num_days }} Days, {{ $pkg->package_num_listings }} Listings)
                                    </option>
                                    @endforeach
                                </select>
                                <div style="font-size:11.5px;color:#64748B;margin-top:6px;">
                                    Owner: <strong>{{ $business->user ? $business->user->name : 'Admin Listing' }}</strong> 
                                    @if($business->user && $business->user->email)
                                    ({{ $business->user->email }})
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- 2. MAIN BUSINESS FORM FIELDS --}}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label style="font-weight:700;color:#334155;">Business Name *</label>
                                <input type="text" name="name" class="form-control" required value="{{ $business->name }}" style="height:40px;border-radius:8px;">
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-weight:700;color:#334155;">Category *</label>
                                <select name="category_id" class="form-control" required style="height:40px;border-radius:8px;">
                                    @foreach($categories as $cId => $cName)
                                    <option value="{{ $cId }}" {{ $business->category_id == $cId ? 'selected' : '' }}>{{ $cName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-weight:700;color:#334155;">Phone Number *</label>
                                <input type="text" name="phone" class="form-control" required value="{{ $business->phone }}" style="height:40px;border-radius:8px;">
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-weight:700;color:#334155;">WhatsApp Number</label>
                                <input type="text" name="whatsapp_number" class="form-control" value="{{ $business->whatsapp_number }}" style="height:40px;border-radius:8px;">
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-weight:700;color:#334155;">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $business->email }}" style="height:40px;border-radius:8px;">
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-weight:700;color:#334155;">Website</label>
                                <input type="url" name="website" class="form-control" value="{{ $business->website }}" style="height:40px;border-radius:8px;">
                            </div>
                            <div class="col-md-12 form-group">
                                <label style="font-weight:700;color:#334155;">Address Line 1 *</label>
                                <input type="text" name="address_line1" class="form-control" required value="{{ $business->address_line1 }}" style="height:40px;border-radius:8px;">
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-weight:700;color:#334155;">Area / Locality</label>
                                <input type="text" name="area_locality" class="form-control" value="{{ $business->area_locality }}" style="height:40px;border-radius:8px;">
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-weight:700;color:#334155;">City *</label>
                                <select name="city_id" class="form-control" required style="height:40px;border-radius:8px;">
                                    @foreach($cities as $cId => $cName)
                                    <option value="{{ $cId }}" {{ $business->city_id == $cId ? 'selected' : '' }}>{{ $cName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-weight:700;color:#334155;">Latitude</label>
                                <input type="text" name="latitude" class="form-control" value="{{ $business->latitude }}" style="height:40px;border-radius:8px;">
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-weight:700;color:#334155;">Longitude</label>
                                <input type="text" name="longitude" class="form-control" value="{{ $business->longitude }}" style="height:40px;border-radius:8px;">
                            </div>
                            <div class="col-md-12 form-group">
                                <label style="font-weight:700;color:#334155;">Description</label>
                                <textarea name="description" class="form-control" rows="3" style="border-radius:8px;">{{ $business->description }}</textarea>
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-weight:700;color:#334155;">Verification Status</label>
                                <select name="verification_status" class="form-control" style="height:40px;border-radius:8px;font-weight:600;">
                                    <option value="verified" {{ $business->verification_status == 'verified' ? 'selected' : '' }}>✅ Verified</option>
                                    <option value="pending" {{ $business->verification_status == 'pending' ? 'selected' : '' }}>⏳ Pending Review</option>
                                    <option value="unverified" {{ $business->verification_status == 'unverified' ? 'selected' : '' }}>❌ Unverified</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-weight:700;color:#334155;">Featured Listing</label>
                                <select name="is_featured" class="form-control" style="height:40px;border-radius:8px;font-weight:600;">
                                    <option value="0" {{ !$business->is_featured ? 'selected' : '' }}>Standard (No)</option>
                                    <option value="1" {{ $business->is_featured ? 'selected' : '' }}>⭐ Yes (Featured on Top)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions" style="margin-top:20px;border-top:1px solid #F1F5F9;padding-top:18px;">
                        <button type="submit" class="btn btn-primary" style="font-weight:700;border-radius:8px;padding:9px 24px;">
                            <i class="fa fa-check"></i> Update Business & Package
                        </button>
                        <a href="{{ route('admin.list.businesses') }}" class="btn btn-default" style="font-weight:600;border-radius:8px;padding:9px 18px;">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('admin_logo_file').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        var reader = new FileReader();
        reader.onload = function(evt) {
            var preview = document.getElementById('admin_logo_preview');
            preview.src = evt.target.result;
            preview.style.display = 'block';
            var placeholder = document.getElementById('admin_logo_placeholder');
            if (placeholder) placeholder.style.display = 'none';
        }
        reader.readAsDataURL(e.target.files[0]);
    }
});
</script>
@endpush

@endsection
