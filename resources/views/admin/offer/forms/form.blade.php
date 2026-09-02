<style>
.section-title-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 800;
    color: #1E40AF;
    background: #EFF6FF;
    border: 1px solid #DBEAFE;
    padding: 6px 12px;
    border-radius: 6px;
    margin-bottom: 16px;
}
.pkg-checkbox-card {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    padding: 10px 14px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.2s;
}
.pkg-checkbox-card:hover {
    background: #EFF6FF;
    border-color: #BFDBFE;
}
</style>

<div class="form-body" style="padding: 10px 0;">
    
    <!-- 1. CAMPAIGN DETAILS -->
    <div style="margin-bottom: 30px;">
        <div class="section-title-badge">
            <i class="fa fa-bullhorn"></i> 1. Campaign & Offer Details
        </div>
        
        <div class="row">
            <div class="col-md-6 col-12">
                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label class="bold" style="color: #0F172A;">Offer / Campaign Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $offer->name ?? '') }}" class="form-control" placeholder="e.g. Diwali Employer Offer, Mega Pro Discount" required style="border-radius: 8px;">
                    @if ($errors->has('name'))
                        <span class="help-block text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group {{ $errors->has('audience_type') ? 'has-error' : '' }}">
                    <label class="bold" style="color: #0F172A;">Target Audience <span class="text-danger">*</span></label>
                    <select name="audience_type" id="audience_type" class="form-control" style="border-radius: 8px;">
                        <option value="all" {{ old('audience_type', $offer->audience_type ?? 'all') == 'all' ? 'selected' : '' }}>Everyone (Public Offer)</option>
                        <option value="new_users" {{ old('audience_type', $offer->audience_type ?? '') == 'new_users' ? 'selected' : '' }}>New Users Only</option>
                        <option value="existing_users" {{ old('audience_type', $offer->audience_type ?? '') == 'existing_users' ? 'selected' : '' }}>Existing Customers Only</option>
                    </select>
                    @if ($errors->has('audience_type'))
                        <span class="help-block text-danger">{{ $errors->first('audience_type') }}</span>
                    @endif
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                    <label class="bold" style="color: #0F172A;">Description (Optional)</label>
                    <textarea name="description" id="description" rows="2" class="form-control" placeholder="Internal or promotional notes about this campaign..." style="border-radius: 8px;">{{ old('description', $offer->description ?? '') }}</textarea>
                    @if ($errors->has('description'))
                        <span class="help-block text-danger">{{ $errors->first('description') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- 2. DISCOUNT RULES & COUPON CODE -->
    <div style="margin-bottom: 30px; border-top: 1px solid #F1F5F9; padding-top: 24px;">
        <div class="section-title-badge" style="color: #059669; background: #ECFDF5; border-color: #A7F3D0;">
            <i class="fa fa-ticket"></i> 2. Discount Rules & Coupon Code
        </div>

        <div class="row">
            <div class="col-md-6 col-12">
                <div class="form-group {{ $errors->has('coupon_code') ? 'has-error' : '' }}">
                    <label class="bold" style="color: #0F172A;">Coupon Code <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" name="coupon_code" id="coupon_code" value="{{ old('coupon_code', $coupon->code ?? '') }}" class="form-control" placeholder="e.g. DIWALI2026, WELCOME500" required style="text-transform: uppercase; font-family: monospace; font-weight: 700; border-radius: 8px 0 0 8px;">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default" onclick="generateRandomCode()" style="border-radius: 0 8px 8px 0; font-weight: 700; border-left: none; background: #F8FAFC;">
                                <i class="fa fa-random text-primary"></i> Generate Code
                            </button>
                        </span>
                    </div>
                    <span style="font-size: 11.5px; color: #64748B;">Codes are automatically case-insensitive for customers.</span>
                    @if ($errors->has('coupon_code'))
                        <span class="help-block text-danger">{{ $errors->first('coupon_code') }}</span>
                    @endif
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="form-group {{ $errors->has('discount_type') ? 'has-error' : '' }}">
                    <label class="bold" style="color: #0F172A;">Discount Type <span class="text-danger">*</span></label>
                    <select name="discount_type" id="discount_type" class="form-control" onchange="toggleDiscountFields()" style="border-radius: 8px;">
                        <option value="percentage" {{ old('discount_type', $coupon->discount_type ?? 'percentage') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                        <option value="fixed" {{ old('discount_type', $coupon->discount_type ?? '') == 'fixed' ? 'selected' : '' }}>Fixed Amount (₹)</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="form-group {{ $errors->has('discount_value') ? 'has-error' : '' }}">
                    <label class="bold" style="color: #0F172A;">Discount Value <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0.01" name="discount_value" id="discount_value" value="{{ old('discount_value', $coupon->discount_value ?? '20') }}" class="form-control" placeholder="20 or 500" required style="border-radius: 8px;">
                    @if ($errors->has('discount_value'))
                        <span class="help-block text-danger">{{ $errors->first('discount_value') }}</span>
                    @endif
                </div>
            </div>

            <div class="col-md-4 col-12" id="max_discount_wrapper">
                <div class="form-group {{ $errors->has('max_discount') ? 'has-error' : '' }}">
                    <label class="bold" style="color: #0F172A;">Maximum Discount Cap (₹) (Optional)</label>
                    <input type="number" step="0.01" min="0" name="max_discount" id="max_discount" value="{{ old('max_discount', $coupon->max_discount ?? '') }}" class="form-control" placeholder="e.g. 1000 (No cap if blank)" style="border-radius: 8px;">
                    <span style="font-size: 11.5px; color: #64748B;">For % discounts, limit maximum savings.</span>
                </div>
            </div>

            <div class="col-md-4 col-12">
                <div class="form-group {{ $errors->has('min_order_value') ? 'has-error' : '' }}">
                    <label class="bold" style="color: #0F172A;">Minimum Order Amount (₹) (Optional)</label>
                    <input type="number" step="0.01" min="0" name="min_order_value" id="min_order_value" value="{{ old('min_order_value', $coupon->min_order_value ?? '') }}" class="form-control" placeholder="e.g. 999 (No min if blank)" style="border-radius: 8px;">
                    <span style="font-size: 11.5px; color: #64748B;">Coupon applies only if base price &ge; this value.</span>
                </div>
            </div>

            <div class="col-md-4 col-12" style="display: flex; align-items: center; padding-top: 15px;">
                <label style="cursor: pointer; font-weight: 700; color: #1E293B;">
                    <input type="checkbox" name="is_first_purchase_only" value="1" {{ old('is_first_purchase_only', $coupon->is_first_purchase_only ?? false) ? 'checked' : '' }}>
                    <span style="margin-left: 6px;">Valid on First Purchase Only</span>
                </label>
            </div>
        </div>
    </div>

    <!-- 3. TARGETING & APPLICABLE PACKAGES -->
    <div style="margin-bottom: 30px; border-top: 1px solid #F1F5F9; padding-top: 24px;">
        <div class="section-title-badge" style="color: #9333EA; background: #FAF5FF; border-color: #E9D5FF;">
            <i class="fa fa-users"></i> 3. Applicable User Types & Packages
        </div>

        <div class="row">
            <div class="col-md-6 col-12">
                <label class="bold" style="color: #0F172A; margin-bottom: 8px; display: block;">Applicable Account Types:</label>
                @php
                    $selectedUserTypes = isset($coupon) ? $coupon->getApplicableUserTypesArray() : ['all'];
                    if (empty($selectedUserTypes)) $selectedUserTypes = ['all'];
                @endphp
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <label style="cursor: pointer; font-size: 13px;">
                        <input type="checkbox" name="applicable_user_types[]" value="all" {{ in_array('all', $selectedUserTypes) ? 'checked' : '' }}> All Accounts
                    </label>
                    <label style="cursor: pointer; font-size: 13px;">
                        <input type="checkbox" name="applicable_user_types[]" value="employer" {{ in_array('employer', $selectedUserTypes) ? 'checked' : '' }}> Employers / Companies
                    </label>
                    <label style="cursor: pointer; font-size: 13px;">
                        <input type="checkbox" name="applicable_user_types[]" value="job_seeker" {{ in_array('job_seeker', $selectedUserTypes) || in_array('candidate', $selectedUserTypes) ? 'checked' : '' }}> Candidates / Job Seekers
                    </label>
                    <label style="cursor: pointer; font-size: 13px;">
                        <input type="checkbox" name="applicable_user_types[]" value="business" {{ in_array('business', $selectedUserTypes) ? 'checked' : '' }}> Business Owners
                    </label>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <label class="bold" style="color: #0F172A; margin-bottom: 8px; display: block;">
                    Applicable Packages <span style="font-weight: normal; color: #64748B; font-size: 12px;">(Leave blank to apply to all packages)</span>:
                </label>
                @php
                    $selectedPackages = isset($coupon) ? $coupon->getApplicablePackagesArray() : [];
                @endphp
                <div style="max-height: 180px; overflow-y: auto; border: 1px solid #E2E8F0; border-radius: 8px; padding: 10px; background: #FFFFFF;">
                    @foreach($packages as $pkg)
                    <div class="pkg-checkbox-card" style="margin-bottom: 6px;">
                        <input type="checkbox" name="applicable_packages[]" value="{{ $pkg->id }}" id="pkg_{{ $pkg->id }}" {{ in_array($pkg->id, $selectedPackages) ? 'checked' : '' }}>
                        <label for="pkg_{{ $pkg->id }}" style="margin: 0; cursor: pointer; font-size: 13px; font-weight: 600; color: #1E293B; width: 100%;">
                            {{ $pkg->package_title }} &bull; ₹{{ number_format($pkg->package_price, 0) }} 
                            <span style="font-size: 11px; color: #64748B;">({{ ucfirst($pkg->package_for) }})</span>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- 4. USAGE LIMITS & VALIDITY DATES -->
    <div style="margin-bottom: 20px; border-top: 1px solid #F1F5F9; padding-top: 24px;">
        <div class="section-title-badge" style="color: #D97706; background: #FFFBEB; border-color: #FDE68A;">
            <i class="fa fa-clock-o"></i> 4. Usage Limits & Validity Period
        </div>

        <div class="row">
            <div class="col-md-3 col-6">
                <div class="form-group {{ $errors->has('total_usage_limit') ? 'has-error' : '' }}">
                    <label class="bold" style="color: #0F172A;">Total Usage Limit</label>
                    <input type="number" min="1" name="total_usage_limit" id="total_usage_limit" value="{{ old('total_usage_limit', $coupon->total_usage_limit ?? '') }}" class="form-control" placeholder="e.g. 100 (Blank = Unlimited)" style="border-radius: 8px;">
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="form-group {{ $errors->has('per_user_usage_limit') ? 'has-error' : '' }}">
                    <label class="bold" style="color: #0F172A;">Per User Limit <span class="text-danger">*</span></label>
                    <input type="number" min="1" name="per_user_usage_limit" id="per_user_usage_limit" value="{{ old('per_user_usage_limit', $coupon->per_user_usage_limit ?? '1') }}" class="form-control" required style="border-radius: 8px;">
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="form-group {{ $errors->has('starts_at') ? 'has-error' : '' }}">
                    <label class="bold" style="color: #0F172A;">Start Date & Time</label>
                    <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at', isset($offer->starts_at) && $offer->starts_at ? $offer->starts_at->format('Y-m-d\TH:i') : '') }}" class="form-control" style="border-radius: 8px;">
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="form-group {{ $errors->has('expires_at') ? 'has-error' : '' }}">
                    <label class="bold" style="color: #0F172A;">Expiry Date & Time</label>
                    <input type="datetime-local" name="expires_at" id="expires_at" value="{{ old('expires_at', isset($offer->expires_at) && $offer->expires_at ? $offer->expires_at->format('Y-m-d\TH:i') : '') }}" class="form-control" style="border-radius: 8px;">
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group" style="margin-top: 10px;">
                    <label class="bold" style="color: #0F172A; display: block;">Offer Status:</label>
                    <label style="cursor: pointer; font-weight: 700; color: #059669; margin-right: 20px;">
                        <input type="radio" name="is_active" value="1" {{ old('is_active', $offer->status ?? 'active') == 'active' ? 'checked' : '' }}> Active / Enabled
                    </label>
                    <label style="cursor: pointer; font-weight: 700; color: #64748B;">
                        <input type="radio" name="is_active" value="0" {{ old('is_active', $offer->status ?? '') == 'disabled' ? 'checked' : '' }}> Disabled / Inactive
                    </label>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function toggleDiscountFields() {
    var type = document.getElementById('discount_type').value;
    var maxWrap = document.getElementById('max_discount_wrapper');
    if (type === 'fixed') {
        if (maxWrap) maxWrap.style.display = 'none';
    } else {
        if (maxWrap) maxWrap.style.display = 'block';
    }
}

function generateRandomCode() {
    var nameVal = document.getElementById('name').value;
    var prefix = '';
    if (nameVal) {
        prefix = nameVal.substring(0, 4).toUpperCase().replace(/[^A-Z]/g, '');
    }
    if (!prefix) prefix = 'OFFER';

    $.ajax({
        url: "{{ route('admin.offers.generate-code') }}",
        type: "POST",
        data: {
            prefix: prefix,
            _token: "{{ csrf_token() }}"
        },
        success: function(res) {
            if (res.success && res.code) {
                document.getElementById('coupon_code').value = res.code;
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    toggleDiscountFields();
});
</script>
