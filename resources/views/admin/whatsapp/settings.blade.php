@extends('admin.layouts.admin_layout')
@section('content')
<div class="page-content-wrapper">
    <div class="page-content" style="background-color: #F8FAFC; min-height: 100vh; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
        <div class="wa-dashboard-container" style="max-width: 1400px; margin: 0 auto; padding-bottom: 50px;">
            
            <!-- Breadcrumb & Header -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #64748B; margin-bottom: 4px;">
                        <a href="{{ route('admin.home') }}" style="color: #64748B; text-decoration: none;">Dashboard</a>
                        <span>/</span>
                        <a href="{{ route('admin.whatsapp.index') }}" style="color: #64748B; text-decoration: none;">WhatsApp Desk</a>
                        <span>/</span>
                        <span style="color: #0F172A; font-weight: 600;">Settings</span>
                    </div>
                    <h1 style="font-size: 24px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -0.5px;">
                        Gateway & Notification Settings
                    </h1>
                </div>

                <div>
                    <a href="{{ route('admin.whatsapp.index') }}" class="btn btn-default" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 10px; font-weight: 700; color: #334155; padding: 9px 18px;">
                        <i class="fa fa-arrow-left" style="margin-right: 6px;"></i> Back to Overview
                    </a>
                </div>
            </div>

            @include('flash::message')

            <div class="row">
                <!-- Settings Form -->
                <div class="col-lg-8 col-md-12" style="margin-bottom: 25px;">
                    <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 18px; padding: 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
                        
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px; padding-bottom: 18px; border-bottom: 1.5px solid #F1F5F9;">
                            <div style="width: 40px; height: 40px; border-radius: 10px; background: #ECFDF5; display: flex; align-items: center; justify-content: center;">
                                <i class="fa fa-cogs" style="color: #059669; font-size: 20px;"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0;">
                                    WhatsApp Provider Configuration
                                </h3>
                                <p style="font-size: 13px; color: #64748B; margin: 0;">
                                    Select your gateway driver and configure API credentials securely.
                                </p>
                            </div>
                        </div>

                        <form action="{{ route('admin.whatsapp.settings.update') }}" method="POST">
                            @csrf

                            <!-- Master Switches Box -->
                            <div style="background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 20px; margin-bottom: 24px;">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12" style="margin-bottom: 10px;">
                                        <div style="display: flex; align-items: center; justify-content: space-between;">
                                            <div>
                                                <strong style="font-size: 14px; color: #0F172A; display: block;">Master WhatsApp Switch</strong>
                                                <span style="font-size: 12px; color: #64748B;">Enable or disable all outgoing alerts</span>
                                            </div>
                                            <label class="switch" style="position: relative; display: inline-block; width: 48px; height: 26px; margin: 0;">
                                                <input type="checkbox" name="is_enabled" value="1" {{ $setting->is_enabled ? 'checked' : '' }} style="opacity: 0; width: 0; height: 0;">
                                                <span class="slider round" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #CBD5E1; transition: .3s; border-radius: 34px;"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div style="display: flex; align-items: center; justify-content: space-between;">
                                            <div>
                                                <strong style="font-size: 14px; color: #0F172A; display: block;">Sandbox / Test Mode</strong>
                                                <span style="font-size: 12px; color: #64748B;">Enable verbose debug logging</span>
                                            </div>
                                            <label class="switch" style="position: relative; display: inline-block; width: 48px; height: 26px; margin: 0;">
                                                <input type="checkbox" name="test_mode" value="1" {{ $setting->test_mode ? 'checked' : '' }} style="opacity: 0; width: 0; height: 0;">
                                                <span class="slider round" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #CBD5E1; transition: .3s; border-radius: 34px;"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Provider Selector -->
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">
                                    Active Provider Driver <span class="text-danger">*</span>
                                </label>
                                <select name="provider" id="providerSelect" class="form-control" style="border: 1.5px solid #CBD5E1; border-radius: 10px; height: 46px; font-weight: 600; font-size: 14px;">
                                    <option value="log" {{ $setting->provider === 'log' ? 'selected' : '' }}>📝 Log Simulator Driver (Local Dev / ₹0 Offline Simulation)</option>
                                    <option value="meta" {{ $setting->provider === 'meta' ? 'selected' : '' }}>🌐 Meta Official WhatsApp Cloud API (Graph API v20.0+)</option>
                                    <option value="gupshup" {{ $setting->provider === 'gupshup' ? 'selected' : '' }}>🇮🇳 Gupshup Enterprise WhatsApp BSP</option>
                                    <option value="twilio" {{ $setting->provider === 'twilio' ? 'selected' : '' }}>📞 Twilio WhatsApp Messaging</option>
                                    <option value="ultramsg" {{ $setting->provider === 'ultramsg' ? 'selected' : '' }}>⚡ UltraMsg WhatsApp API Instance</option>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">
                                            Phone Number ID / SID
                                        </label>
                                        <input type="text" name="phone_number_id" class="form-control" value="{{ $setting->phone_number_id }}" placeholder="e.g. 104857291049281 or ACxxxxxxxxxxxx" style="border: 1.5px solid #CBD5E1; border-radius: 10px; height: 44px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">
                                            WABA ID / App Name
                                        </label>
                                        <input type="text" name="business_account_id" class="form-control" value="{{ $setting->business_account_id }}" placeholder="e.g. Meta WABA ID or Gupshup App" style="border: 1.5px solid #CBD5E1; border-radius: 10px; height: 44px;">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">
                                            Sender WhatsApp Number
                                        </label>
                                        <input type="text" name="sender_number" class="form-control" value="{{ $setting->sender_number }}" placeholder="+919876543210" style="border: 1.5px solid #CBD5E1; border-radius: 10px; height: 44px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">
                                            Daily Throttling Limit
                                        </label>
                                        <input type="number" name="daily_limit" class="form-control" value="{{ $setting->daily_limit ?: 500 }}" min="10" max="100000" style="border: 1.5px solid #CBD5E1; border-radius: 10px; height: 44px;">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">
                                            API Key / Access Token
                                        </label>
                                        <input type="password" name="api_key" class="form-control" placeholder="{{ !empty($setting->api_key) ? '••••••••••••••••••••••••••••••••' : 'Enter API Key or Token' }}" style="border: 1.5px solid #CBD5E1; border-radius: 10px; height: 44px;">
                                        <span class="help-block" style="font-size: 11.5px; color: #64748B;">Stored securely using AES-256 encryption.</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" style="margin-bottom: 20px;">
                                        <label style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">
                                            API Secret / Auth Token (Optional)
                                        </label>
                                        <input type="password" name="api_secret" class="form-control" placeholder="{{ !empty($setting->api_secret) ? '••••••••••••••••' : 'Enter Secret if required' }}" style="border: 1.5px solid #CBD5E1; border-radius: 10px; height: 44px;">
                                    </div>
                                </div>
                            </div>

                            <hr style="border-top: 1.5px solid #F1F5F9; margin: 25px 0;">

                            <!-- Automated Triggers -->
                            <h4 style="font-size: 15px; font-weight: 800; color: #0F172A; margin-bottom: 16px;">
                                Automated Notification Triggers
                            </h4>

                            <div class="row" style="margin-bottom: 20px;">
                                <div class="col-md-6">
                                    <div style="display: flex; flex-direction: column; gap: 12px;">
                                        <label style="display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 13.5px; color: #334155; cursor: pointer;">
                                            <input type="checkbox" name="enable_candidate_notifications" value="1" {{ $setting->enable_candidate_notifications ? 'checked' : '' }} style="width: 18px; height: 18px;">
                                            Candidate Notifications
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 13.5px; color: #334155; cursor: pointer;">
                                            <input type="checkbox" name="enable_matching_alerts" value="1" {{ $setting->enable_matching_alerts ? 'checked' : '' }} style="width: 18px; height: 18px;">
                                            AI Job Match Alerts
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 13.5px; color: #334155; cursor: pointer;">
                                            <input type="checkbox" name="enable_application_alerts" value="1" {{ $setting->enable_application_alerts ? 'checked' : '' }} style="width: 18px; height: 18px;">
                                            Application Confirmations
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div style="display: flex; flex-direction: column; gap: 12px;">
                                        <label style="display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 13.5px; color: #334155; cursor: pointer;">
                                            <input type="checkbox" name="enable_employer_notifications" value="1" {{ $setting->enable_employer_notifications ? 'checked' : '' }} style="width: 18px; height: 18px;">
                                            Employer Notifications
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 13.5px; color: #334155; cursor: pointer;">
                                            <input type="checkbox" name="enable_status_alerts" value="1" {{ $setting->enable_status_alerts ? 'checked' : '' }} style="width: 18px; height: 18px;">
                                            Job Status & Expiry Alerts
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 13.5px; color: #334155; cursor: pointer;">
                                            <input type="checkbox" name="enable_payment_alerts" value="1" {{ $setting->enable_payment_alerts ? 'checked' : '' }} style="width: 18px; height: 18px;">
                                            Payment & Package Receipts
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div style="text-align: right; padding-top: 15px; border-top: 1.5px solid #F1F5F9;">
                                <button type="submit" class="btn" style="background: #059669; color: #FFFFFF; font-weight: 700; border-radius: 10px; padding: 10px 24px; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);">
                                    <i class="fa fa-save" style="margin-right: 6px;"></i> Save All Settings
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

                <!-- Right Side Webhook Box -->
                <div class="col-lg-4 col-md-12">
                    <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 18px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
                            <div style="width: 34px; height: 34px; border-radius: 8px; background: #EFF6FF; display: flex; align-items: center; justify-content: center; color: #2563EB;">
                                <i class="fa fa-exchange" style="font-size: 16px;"></i>
                            </div>
                            <h3 style="font-size: 16px; font-weight: 800; color: #0F172A; margin: 0;">
                                Delivery Status Webhook
                            </h3>
                        </div>
                        <p style="font-size: 13px; color: #64748B; line-height: 1.5; margin-bottom: 18px;">
                            Add this URL to your Meta / Gupshup dashboard to receive real-time <b>Delivered</b> and <b>Blue Tick (Read)</b> receipts.
                        </p>

                        <div class="form-group" style="margin-bottom: 14px;">
                            <label style="font-size: 12px; font-weight: 700; color: #334155;">Webhook URL:</label>
                            <div class="input-group" style="width: 100%;">
                                <input type="text" class="form-control" id="txtWebhookUrl" value="{{ $webhookUrl }}" readonly style="font-size: 11.5px; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 8px 0 0 8px; height: 38px;">
                                <span class="input-group-btn">
                                    <button class="btn btn-default btn-copy" type="button" data-target="#txtWebhookUrl" style="border: 1.5px solid #CBD5E1; border-left: none; border-radius: 0 8px 8px 0; height: 38px;">
                                        <i class="fa fa-copy"></i>
                                    </button>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label style="font-size: 12px; font-weight: 700; color: #334155;">Verification Token:</label>
                            <div class="input-group" style="width: 100%;">
                                <input type="text" class="form-control" id="txtVerifyToken" value="{{ $setting->webhook_verify_token }}" readonly style="font-size: 11.5px; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 8px 0 0 8px; height: 38px;">
                                <span class="input-group-btn">
                                    <button class="btn btn-default btn-copy" type="button" data-target="#txtVerifyToken" style="border: 1.5px solid #CBD5E1; border-left: none; border-radius: 0 8px 8px 0; height: 38px;">
                                        <i class="fa fa-copy"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
.switch input:checked + .slider {
    background-color: #10B981 !important;
}
.switch input:focus + .slider {
    box-shadow: 0 0 1px #10B981;
}
.switch input:checked + .slider:before {
    -webkit-transform: translateX(20px);
    -ms-transform: translateX(20px);
    transform: translateX(20px);
}
.slider.round:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .3s;
    border-radius: 50%;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $('.btn-copy').on('click', function() {
        var target = $(this).data('target');
        var copyText = $(target)[0];
        copyText.select();
        document.execCommand("copy");
        alert("Copied to clipboard: " + copyText.value);
    });
});
</script>
@endpush
@endsection
