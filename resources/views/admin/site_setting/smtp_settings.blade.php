@extends('admin.layouts.admin_layout')

@section('content')
<style>
.smtp-card {
    background: #FFFFFF;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    border: 1px solid #E5E7EB;
    padding: 28px 32px;
    margin-bottom: 24px;
}
.smtp-card-title {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.smtp-form-row {
    display: flex;
    align-items: center;
    margin-bottom: 18px;
}
.smtp-form-label {
    width: 32%;
    font-size: 12.5px;
    font-weight: 700;
    color: #374151;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0;
}
.smtp-form-input-wrap {
    width: 68%;
}
.smtp-input {
    width: 100%;
    height: 44px;
    border-radius: 8px;
    border: 1px solid #D1D5DB;
    padding: 8px 14px;
    font-size: 13.5px;
    color: #1F2937;
    background: #FFFFFF;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}
.smtp-input:focus {
    border-color: #EC4899;
    box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.15);
    outline: none;
}
.btn-smtp-update {
    background: #E11D48;
    color: #FFFFFF !important;
    font-weight: 700;
    font-size: 14px;
    padding: 10px 32px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(225, 29, 72, 0.3);
    transition: all 0.2s ease;
}
.btn-smtp-update:hover {
    background: #BE123C;
    box-shadow: 0 6px 16px rgba(225, 29, 72, 0.4);
    transform: translateY(-1px);
}
.instruction-warning {
    color: #FF2E7E;
    font-size: 13px;
    line-height: 1.5;
    margin-bottom: 20px;
    font-weight: 500;
}
.instruction-section-title {
    font-size: 14px;
    font-weight: 700;
    color: #1F2937;
    margin: 18px 0 10px 0;
}
.instruction-table {
    width: 100%;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 16px;
}
.instruction-table td {
    padding: 10px 14px;
    font-size: 12.5px;
    color: #374151;
    border-bottom: 1px solid #F3F4F6;
    background: #FFFFFF;
}
.instruction-table tr:last-child td {
    border-bottom: none;
}

@media (max-width: 768px) {
    .smtp-form-row {
        flex-direction: column;
        align-items: flex-start;
    }
    .smtp-form-label {
        width: 100%;
        margin-bottom: 6px;
    }
    .smtp-form-input-wrap {
        width: 100%;
    }
}
</style>

<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><a href="{{ route('admin.home') }}">Home</a> <i class="fa fa-circle"></i></li>
                <li><a href="{{ route('edit.site.setting') }}">Site Settings</a> <i class="fa fa-circle"></i></li>
                <li><span>SMTP Settings</span></li>
            </ul>
        </div>
        <br />
        @include('flash::message')

        <div class="row">
            {{-- LEFT CARD: SMTP SETTINGS FORM --}}
            <div class="col-md-6">
                <div class="smtp-card">
                    <div class="smtp-card-title">
                        <span>SMTP Settings</span>
                        <a href="{{ route('admin.otp.logs') }}" class="btn btn-sm btn-outline dark" style="font-size: 12px; font-weight: 600;">
                            <i class="fa fa-shield text-danger"></i> OTP & Security Logs
                        </a>
                    </div>

                    <form method="POST" action="{{ route('admin.update.smtp.settings') }}">
                        @csrf

                        <div class="smtp-form-row">
                            <label class="smtp-form-label">Type</label>
                            <div class="smtp-form-input-wrap">
                                <select name="mail_driver" class="smtp-input" id="mail_driver">
                                    @foreach($mail_drivers as $dKey => $dVal)
                                        <option value="{{ $dKey }}" {{ ($siteSetting && $siteSetting->mail_driver == $dKey) ? 'selected' : '' }}>{{ $dVal }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="smtp-form-row">
                            <label class="smtp-form-label">MAIL HOST</label>
                            <div class="smtp-form-input-wrap">
                                <input type="text" name="mail_host" class="smtp-input" placeholder="smtp.gmail.com" value="{{ $siteSetting ? $siteSetting->mail_host : '' }}">
                            </div>
                        </div>

                        <div class="smtp-form-row">
                            <label class="smtp-form-label">MAIL PORT</label>
                            <div class="smtp-form-input-wrap">
                                <input type="text" name="mail_port" class="smtp-input" placeholder="587" value="{{ $siteSetting ? $siteSetting->mail_port : '587' }}">
                            </div>
                        </div>

                        <div class="smtp-form-row">
                            <label class="smtp-form-label">MAIL USERNAME</label>
                            <div class="smtp-form-input-wrap">
                                <input type="text" name="mail_username" class="smtp-input" placeholder="info@example.com" value="{{ $siteSetting ? $siteSetting->mail_username : '' }}">
                            </div>
                        </div>

                        <div class="smtp-form-row">
                            <label class="smtp-form-label">MAIL PASSWORD</label>
                            <div class="smtp-form-input-wrap">
                                <input type="password" name="mail_password" class="smtp-input" placeholder="••••••••••••" value="{{ $siteSetting ? $siteSetting->mail_password : '' }}">
                            </div>
                        </div>

                        <div class="smtp-form-row">
                            <label class="smtp-form-label">MAIL ENCRYPTION</label>
                            <div class="smtp-form-input-wrap">
                                <input type="text" name="mail_encryption" class="smtp-input" placeholder="tls or ssl" value="{{ $siteSetting ? $siteSetting->mail_encryption : 'tls' }}">
                            </div>
                        </div>

                        <div class="smtp-form-row">
                            <label class="smtp-form-label">MAIL FROM ADDRESS</label>
                            <div class="smtp-form-input-wrap">
                                <input type="email" name="mail_from_address" class="smtp-input" placeholder="info@example.com" value="{{ $siteSetting ? $siteSetting->mail_from_address : '' }}">
                            </div>
                        </div>

                        <div class="smtp-form-row">
                            <label class="smtp-form-label">MAIL FROM NAME</label>
                            <div class="smtp-form-input-wrap">
                                <input type="text" name="mail_from_name" class="smtp-input" placeholder="Jobs Portal Support" value="{{ $siteSetting ? $siteSetting->mail_from_name : '' }}">
                            </div>
                        </div>

                        <div class="text-right" style="margin-top: 24px;">
                            <button type="submit" class="btn-smtp-update">Update</button>
                        </div>
                    </form>
                </div>

                {{-- LIVE SMTP TEST TOOL BOX --}}
                <div class="smtp-card" style="background: #F0FDF4; border: 1.5px solid #86EFAC;">
                    <div class="smtp-card-title" style="color: #166534; font-size: 15px; margin-bottom: 12px;">
                        <span><i class="fa fa-paper-plane text-success"></i> Live Test SMTP Connection</span>
                    </div>
                    <p style="font-size: 12.5px; color: #166534; margin-bottom: 14px;">
                        Enter your email address below to send a live test email and confirm that your SMTP server is configured and working 100%.
                    </p>
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <input type="email" id="test_smtp_email" class="form-control" placeholder="recipient@gmail.com" style="flex: 1; min-width: 220px; height: 40px; border-radius: 8px;">
                        <button type="button" id="btn_test_smtp" class="btn green" style="font-weight: 700; height: 40px; border-radius: 8px;">
                            Send Test Mail
                        </button>
                    </div>
                    <div id="smtp_test_response" style="margin-top: 12px; display: none;"></div>
                </div>
            </div>

            {{-- RIGHT CARD: INSTRUCTION GUIDE --}}
            <div class="col-md-6">
                <div class="smtp-card">
                    <div class="smtp-card-title">
                        <span>Instruction</span>
                    </div>

                    <p class="instruction-warning">
                        Please be carefull when you are configuring SMTP. For incorrect configuration you will get error at the time of order place, new registration, sending newsletter.
                    </p>

                    {{-- Non-SSL Guide --}}
                    <div class="instruction-section-title">For Non-SSL</div>
                    <table class="instruction-table">
                        <tr>
                            <td>Select sendmail for Mail Driver if you face any issue after configuring smtp as Mail Driver</td>
                        </tr>
                        <tr>
                            <td>Set Mail Host according to your server Mail Client Manual Settings</td>
                        </tr>
                        <tr>
                            <td>Set Mail port as 587</td>
                        </tr>
                        <tr>
                            <td>Set Mail Encryption as ssl if you face issue with tls</td>
                        </tr>
                    </table>

                    {{-- SSL Guide --}}
                    <div class="instruction-section-title" style="margin-top: 20px;">For SSL</div>
                    <table class="instruction-table">
                        <tr>
                            <td>Select sendmail for Mail Driver if you face any issue after configuring smtp as Mail Driver</td>
                        </tr>
                        <tr>
                            <td>Set Mail Host according to your server Mail Client Manual Settings</td>
                        </tr>
                        <tr>
                            <td>Set Mail port as 465</td>
                        </tr>
                        <tr>
                            <td>Set Mail Encryption as ssl</td>
                        </tr>
                    </table>

                    {{-- Quick Note --}}
                    <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 8px; padding: 12px 16px; margin-top: 20px;">
                        <div style="font-size: 12.5px; color: #1E40AF; line-height: 1.5;">
                            <strong><i class="fa fa-info-circle"></i> Gmail Users Note:</strong><br>
                            If using Gmail SMTP (`smtp.gmail.com`), please generate an <strong>App Password</strong> in your Google Account Security settings and enter it in the <strong>MAIL PASSWORD</strong> field.
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('btn_test_smtp');
    var input = document.getElementById('test_smtp_email');
    var resBox = document.getElementById('smtp_test_response');

    if (btn) {
        btn.addEventListener('click', function() {
            var email = input.value.trim();
            if (!email) {
                alert('Please enter a valid email address.');
                input.focus();
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Dispatching...';
            resBox.style.display = 'block';
            resBox.innerHTML = '<div class="alert alert-info" style="margin-bottom:0; font-size:12.5px;"><i class="fa fa-spinner fa-spin"></i> Connecting to SMTP server...</div>';

            $.ajax({
                url: '{{ route("test.smtp.email") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    test_email: email
                },
                success: function(res) {
                    btn.disabled = false;
                    btn.innerHTML = 'Send Test Mail';
                    resBox.innerHTML = '<div class="alert alert-success" style="margin-bottom:0; font-size:12.5px;"><i class="fa fa-check-circle"></i> <strong>SUCCESS:</strong> ' + res.message + '</div>';
                },
                error: function(xhr) {
                    btn.disabled = false;
                    btn.innerHTML = 'Send Test Mail';
                    var errMsg = xhr.responseJSON ? xhr.responseJSON.message : 'SMTP connection failed.';
                    resBox.innerHTML = '<div class="alert alert-danger" style="margin-bottom:0; font-size:12.5px;"><i class="fa fa-exclamation-triangle"></i> <strong>SMTP ERROR:</strong> ' + errMsg + '</div>';
                }
            });
        });
    }
});
</script>
@endsection
