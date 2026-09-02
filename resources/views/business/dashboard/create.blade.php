@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->

<!-- Inner Page Title -->
<div class="pageTitle" style="background: #0F172A; padding: 32px 0; color: #FFFFFF !important;">
    <div class="container">
        <h1 style="font-size: 24px; font-weight: 800; color: #FFFFFF !important; margin: 0;">Add New Local Business</h1>
        <p style="color: #E2E8F0 !important; font-size: 13.5px; margin-top: 4px; margin-bottom: 0;">Fill in your business details and NAP information to start receiving local customer leads.</p>
    </div>
</div>

<style>
/* Modern Business Form Styling */
.biz-form-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 32px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.03);
}
.biz-section-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 800;
    color: #2563EB;
    background: #EFF6FF;
    border: 1px solid #DBEAFE;
    padding: 6px 14px;
    border-radius: 20px;
    margin-bottom: 16px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
.biz-form-label {
    font-size: 13px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 6px;
}
.biz-input {
    height: 42px;
    border: 1px solid #CBD5E1;
    border-radius: 10px;
    padding: 0 14px;
    font-size: 13.5px;
    color: #1E293B;
    transition: all 0.15s ease;
}
.biz-input:focus {
    border-color: #2563EB;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
}

/* Upload Dropzone Box */
.biz-upload-box {
    border: 2px dashed #CBD5E1;
    border-radius: 12px;
    padding: 24px 16px;
    text-align: center;
    background: #FAFBFC;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
}
.biz-upload-box:hover {
    border-color: #2563EB;
    background: #EFF6FF;
}
.biz-upload-box input[type="file"] {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}
.biz-upload-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #EFF6FF;
    color: #2563EB;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    margin-bottom: 8px;
}
.biz-upload-title {
    font-size: 13.5px;
    font-weight: 700;
    color: #1E293B;
    margin-bottom: 2px;
}
.biz-upload-sub {
    font-size: 11.5px;
    color: #94A3B8;
}
.biz-upload-preview {
    max-height: 80px;
    border-radius: 8px;
    margin-top: 10px;
    display: none;
    border: 1px solid #E2E8F0;
}

/* Action Buttons */
.biz-form-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid #F1F5F9;
    flex-wrap: wrap;
}
.btn-submit-listing {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #2563EB;
    color: #FFFFFF !important;
    font-size: 14px;
    font-weight: 700;
    padding: 12px 28px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(37,99,235,0.35);
    transition: all 0.2s ease;
}
.btn-submit-listing:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(37,99,235,0.45);
}
.btn-cancel-listing {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #F8FAFC;
    color: #64748B !important;
    font-size: 14px;
    font-weight: 600;
    padding: 11px 22px;
    border-radius: 10px;
    border: 1px solid #CBD5E1;
    text-decoration: none !important;
    transition: all 0.15s ease;
}
.btn-cancel-listing:hover {
    background: #E2E8F0;
    color: #1E293B !important;
}
</style>

<div class="listpgWraper" style="background: #F8FAFC; padding: 40px 0 60px;">
    <div class="container">
        <div class="row">
            @include('includes.business_dashboard_menu')

            <div class="col-lg-9 col-md-8">
                <div class="biz-form-card">
                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #F1F5F9; padding-bottom: 16px; margin-bottom: 24px;">
                        <div>
                            <h3 style="font-size: 19px; font-weight: 800; color: #0F172A; margin: 0 0 4px;">
                                <i class="fa fa-plus-circle" style="color:#2563EB;"></i> Business Registration Form
                            </h3>
                            <div style="font-size: 13px; color: #94A3B8;">Provide official NAP (Name, Address, Phone) & offerings</div>
                        </div>
                    </div>

                    @if ($errors->any())
                    <div class="alert alert-danger" style="border-radius:10px;font-size:13.5px;">
                        <ul style="margin:0;padding-left:18px;">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('store.business') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- SECTION 1: Basic Information --}}
                        <div class="biz-section-badge"><i class="fa fa-info-circle"></i> 1. Basic Information</div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="biz-form-label">Business Name *</label>
                                <input type="text" name="name" class="form-control biz-input" required placeholder="e.g. Apex Digital Solutions" value="{{ old('name') }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="biz-form-label">Business Category *</label>
                                <select name="category_id" class="form-control biz-input" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 form-group">
                                <label class="biz-form-label">Short Summary</label>
                                <input type="text" name="short_description" class="form-control biz-input" placeholder="1-2 sentences summarizing what your business offers..." value="{{ old('short_description') }}">
                            </div>
                            <div class="col-md-12 form-group">
                                <label class="biz-form-label">Full Business Description</label>
                                <textarea name="description" class="form-control" style="border-radius:10px;border-color:#CBD5E1;font-size:13.5px;" rows="4" placeholder="Describe your background, team, experience, and services in detail...">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        {{-- SECTION 2: NAP & Contact Details --}}
                        <div class="biz-section-badge" style="margin-top:20px;"><i class="fa fa-id-card-o"></i> 2. NAP (Name, Address, Phone) & Contact</div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="biz-form-label">Primary Phone Number *</label>
                                <input type="text" name="phone" class="form-control biz-input" required placeholder="e.g. +91 9876543210" value="{{ old('phone') }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="biz-form-label">WhatsApp Number</label>
                                <input type="text" name="whatsapp_number" class="form-control biz-input" placeholder="e.g. 9876543210" value="{{ old('whatsapp_number') }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="biz-form-label">Email Address</label>
                                <input type="email" name="email" class="form-control biz-input" placeholder="contact@example.com" value="{{ old('email') }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="biz-form-label">Website URL</label>
                                <input type="url" name="website" class="form-control biz-input" placeholder="https://example.com" value="{{ old('website') }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="biz-form-label">Address Line 1 *</label>
                                <input type="text" name="address_line1" class="form-control biz-input" required placeholder="Shop/Office No., Street, Building" value="{{ old('address_line1') }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="biz-form-label">Area / Locality</label>
                                <input type="text" name="area_locality" class="form-control biz-input" placeholder="e.g. Dharampeth, Civil Lines" value="{{ old('area_locality') }}">
                            </div>

                            {{-- Cascading Location Flow: Country -> State -> City -> Postal Code --}}
                            <div class="col-md-3 form-group">
                                <label class="biz-form-label">Country *</label>
                                <select name="country_id" id="country_id" class="form-control biz-input" required>
                                    <option value="">Select Country</option>
                                    @foreach($countries as $cId => $cName)
                                    <option value="{{ $cId }}" {{ old('country_id', (isset($siteSetting) ? $siteSetting->default_country_id : 1)) == $cId ? 'selected' : '' }}>{{ $cName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="biz-form-label">State *</label>
                                <span id="default_state_dd">
                                    <select name="state_id" id="state_id" class="form-control biz-input" required>
                                        <option value="">Select State</option>
                                    </select>
                                </span>
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="biz-form-label">City *</label>
                                <span id="default_city_dd">
                                    <select name="city_id" id="city_id" class="form-control biz-input" required>
                                        <option value="">Select City</option>
                                    </select>
                                </span>
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="biz-form-label">Postal Code / PIN *</label>
                                <input type="text" name="postal_code" class="form-control biz-input" required placeholder="e.g. 440001" value="{{ old('postal_code') }}">
                            </div>
                        </div>

                        {{-- SECTION 3: Geo Location --}}
                        <div class="biz-section-badge" style="margin-top:20px;"><i class="fa fa-map-marker"></i> 3. Geo Location (For "Near Me" Distance Ranking)</div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="biz-form-label">Latitude</label>
                                <input type="text" name="latitude" id="latInput" class="form-control biz-input" placeholder="e.g. 21.145800" value="{{ old('latitude') }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="biz-form-label">Longitude</label>
                                <input type="text" name="longitude" id="lngInput" class="form-control biz-input" placeholder="e.g. 79.088200" value="{{ old('longitude') }}">
                            </div>
                            <div class="col-md-12">
                                <button type="button" class="btn btn-sm btn-default" id="btnAutoGeo" style="font-weight:600;font-size:12.5px;border-radius:8px;padding:6px 14px;border:1px solid #CBD5E1;">
                                    <i class="fa fa-crosshairs text-primary"></i> Detect Coordinates from Current Location
                                </button>
                            </div>
                        </div>

                        {{-- SECTION 4: Services --}}
                        <div class="biz-section-badge" style="margin-top:24px;"><i class="fa fa-check-square-o"></i> 4. Services & Offerings</div>
                        <div id="servicesContainer">
                            <div class="row service-row mb-2">
                                <div class="col-md-10">
                                    <input type="text" name="services[]" class="form-control biz-input" placeholder="e.g. Local SEO, Website Development, AC Gas Refilling">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-default btn-block btn-add-service" style="font-weight:600;height:42px;border-radius:10px;border-color:#CBD5E1;"><i class="fa fa-plus"></i> Add</button>
                                </div>
                            </div>
                        </div>

                        {{-- SECTION 5: Media Upload --}}
                        <div class="biz-section-badge" style="margin-top:24px;"><i class="fa fa-picture-o"></i> 5. Media (Logo & Cover Image)</div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="biz-form-label">Business Logo</label>
                                <div class="biz-upload-box" onclick="$('#logoFileInput').click()">
                                    <input type="file" name="logo" id="logoFileInput" accept="image/*" onchange="previewUpload(this, '#logoPreview')">
                                    <div class="biz-upload-icon"><i class="fa fa-cloud-upload"></i></div>
                                    <div class="biz-upload-title">Choose Logo Image</div>
                                    <div class="biz-upload-sub">PNG, JPG, WEBP up to 5MB</div>
                                    <img id="logoPreview" class="biz-upload-preview" alt="Logo preview">
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="biz-form-label">Cover Image</label>
                                <div class="biz-upload-box" onclick="$('#coverFileInput').click()">
                                    <input type="file" name="cover_image" id="coverFileInput" accept="image/*" onchange="previewUpload(this, '#coverPreview')">
                                    <div class="biz-upload-icon" style="background:#FAF5FF;color:#9333EA;"><i class="fa fa-image"></i></div>
                                    <div class="biz-upload-title">Choose Cover Banner</div>
                                    <div class="biz-upload-sub">Wide banner, PNG or JPG</div>
                                    <img id="coverPreview" class="biz-upload-preview" alt="Cover preview">
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="biz-form-actions">
                            <button type="submit" class="btn btn-submit-listing">
                                <i class="fa fa-check-circle"></i> Submit Business Listing
                            </button>
                            <a href="{{ route('my.businesses') }}" class="btn btn-cancel-listing">
                                <i class="fa fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.footer')
@endsection

@push('scripts')
<script>
    function previewUpload(input, previewSelector) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(previewSelector).attr('src', e.target.result).show();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#btnAutoGeo').on('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(pos) {
                $('#latInput').val(pos.coords.latitude);
                $('#lngInput').val(pos.coords.longitude);
                alert('Coordinates captured successfully!');
            }, function(err) {
                alert('Unable to get location: ' + err.message);
            });
        }
    });

    $(document).on('click', '.btn-add-service', function() {
        var html = '<div class="row service-row mb-2">' +
            '<div class="col-md-10"><input type="text" name="services[]" class="form-control biz-input" placeholder="Another service..."></div>' +
            '<div class="col-md-2"><button type="button" class="btn btn-danger btn-block btn-remove-service" style="height:42px;border-radius:10px;"><i class="fa fa-trash"></i></button></div>' +
            '</div>';
        $('#servicesContainer').append(html);
    });

    $(document).on('click', '.btn-remove-service', function() {
        $(this).closest('.service-row').remove();
    });

    /* Cascading Country -> State -> City Dropdowns */
    $('#country_id').on('change', function (e) {
        e.preventDefault();
        filterLangStates(0);
    });
    $(document).on('change', '#state_id', function (e) {
        e.preventDefault();
        filterLangCities(0);
    });

    function filterLangStates(state_id) {
        var country_id = $('#country_id').val();
        if (country_id != '') {
            $.post("{{ route('filter.lang.states.dropdown') }}", {
                country_id: country_id,
                state_id: state_id,
                _method: 'POST',
                _token: '{{ csrf_token() }}'
            }).done(function (response) {
                $('#default_state_dd').html(response);
                $('#state_id').addClass('biz-input');
                filterLangCities(<?php echo old('city_id', 0); ?>);
            });
        }
    }

    function filterLangCities(city_id) {
        var state_id = $('#state_id').val();
        if (state_id != '') {
            $.post("{{ route('filter.lang.cities.dropdown') }}", {
                state_id: state_id,
                city_id: city_id,
                _method: 'POST',
                _token: '{{ csrf_token() }}'
            }).done(function (response) {
                $('#default_city_dd').html(response);
                $('#city_id').addClass('biz-input');
            });
        }
    }

    $(document).ready(function() {
        filterLangStates(<?php echo old('state_id', 0); ?>);
    });
</script>
@endpush
