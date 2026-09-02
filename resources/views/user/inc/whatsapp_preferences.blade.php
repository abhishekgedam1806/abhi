<div class="userccount" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 25px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="background: #25D366; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);">
                <i class="fa fa-whatsapp" style="font-size: 24px; color: #FFFFFF;"></i>
            </div>
            <div>
                <h5 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0;">{{ __('WhatsApp Notification Alerts') }}</h5>
                <p style="font-size: 13px; color: #64748B; margin: 0;">{{ __('Get instant alerts on WhatsApp for matching jobs and application status.') }}</p>
            </div>
        </div>
        <div id="waVerificationBadge">
            <span class="badge" style="background: #F1F5F9; color: #475569; padding: 6px 12px; font-size: 11px; font-weight: 700; border-radius: 20px;">
                <i class="fa fa-circle-o"></i> {{ __('Loading...') }}
            </span>
        </div>
    </div>

    <!-- Phone Number Input & OTP Verification Row -->
    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 18px; margin-bottom: 20px;">
        <div class="row" style="display: flex; align-items: flex-end; flex-wrap: wrap;">
            <div class="col-md-7 col-sm-12" style="margin-bottom: 10px;">
                <label style="font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block;">
                    {{ __('Your WhatsApp Number (with Country Code)') }}
                </label>
                <div class="input-group" style="width: 100%;">
                    <input type="text" id="candidateWaNumber" class="form-control" placeholder="+919876543210" style="border-radius: 8px; font-weight: 600; font-size: 14px; height: 42px;">
                </div>
            </div>
            <div class="col-md-5 col-sm-12" style="margin-bottom: 10px;">
                <button type="button" class="btn btn-default" id="btnRequestWaOtp" style="height: 42px; font-weight: 700; border-radius: 8px; background: #FFFFFF; border: 1.5px solid #CBD5E1; color: #1E293B; width: 100%;">
                    <i class="fa fa-shield"></i> {{ __('Verify WhatsApp Number') }}
                </button>
            </div>
        </div>
        <div id="waPhoneStatusMsg" style="font-size: 12px; margin-top: 6px;"></div>
    </div>

    <!-- Notification Category Toggles -->
    <div class="row">
        <div class="col-md-6" style="margin-bottom: 15px;">
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px;">
                <div>
                    <strong style="font-size: 13.5px; color: #0F172A; display: block;">🎯 {{ __('Matching Job Alerts') }}</strong>
                    <span style="font-size: 12px; color: #64748B;">{{ __('Receive alerts when jobs matching your skills are posted') }}</span>
                </div>
                <div>
                    <input type="checkbox" id="chkCandidateJobs" class="wa-pref-toggle" style="width: 20px; height: 20px; cursor: pointer;">
                </div>
            </div>
        </div>

        <div class="col-md-6" style="margin-bottom: 15px;">
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px;">
                <div>
                    <strong style="font-size: 13.5px; color: #0F172A; display: block;">✅ {{ __('Application Status Updates') }}</strong>
                    <span style="font-size: 12px; color: #64748B;">{{ __('Confirmations, shortlisting, and interview invites') }}</span>
                </div>
                <div>
                    <input type="checkbox" id="chkCandidateApps" class="wa-pref-toggle" style="width: 20px; height: 20px; cursor: pointer;">
                </div>
            </div>
        </div>

        <div class="col-md-6" style="margin-bottom: 15px;">
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px;">
                <div>
                    <strong style="font-size: 13.5px; color: #0F172A; display: block;">💬 {{ __('Employer Messages') }}</strong>
                    <span style="font-size: 12px; color: #64748B;">{{ __('Get notified when an employer replies or contacts you') }}</span>
                </div>
                <div>
                    <input type="checkbox" id="chkCandidateMsgs" class="wa-pref-toggle" style="width: 20px; height: 20px; cursor: pointer;">
                </div>
            </div>
        </div>

        <div class="col-md-6" style="margin-bottom: 15px;">
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px;">
                <div>
                    <strong style="font-size: 13.5px; color: #0F172A; display: block;">🎁 {{ __('Career Tips & Offers') }}</strong>
                    <span style="font-size: 12px; color: #64748B;">{{ __('Occasional resume tips, webinars & career promotions') }}</span>
                </div>
                <div>
                    <input type="checkbox" id="chkCandidatePromo" class="wa-pref-toggle" style="width: 20px; height: 20px; cursor: pointer;">
                </div>
            </div>
        </div>
    </div>

    <div style="text-align: right; margin-top: 10px;">
        <button type="button" class="btn btn-primary" id="btnSaveWaPreferences" style="background: #2563EB; border: none; font-weight: 700; border-radius: 8px; padding: 8px 24px;">
            <i class="fa fa-save"></i> {{ __('Save Preferences') }}
        </button>
    </div>
</div>

<!-- WhatsApp OTP Verification Modal -->
<div class="modal fade" id="modalCandidateWaOtp" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background: #064E3B; color: #FFFFFF; border-radius: 16px 16px 0 0; text-align: center; padding: 20px;">
                <h5 class="modal-title" style="font-weight: 800; font-size: 17px; margin: 0;">
                    <i class="fa fa-whatsapp" style="color: #25D366; font-size: 20px;"></i> {{ __('Verify WhatsApp Number') }}
                </h5>
            </div>
            <div class="modal-body" style="padding: 24px; text-align: center;">
                <p style="font-size: 13px; color: #475569; margin-bottom: 15px;">
                    {{ __('We sent a 6-digit verification code to your WhatsApp number.') }}
                </p>
                <div class="form-group">
                    <input type="text" id="candidateWaOtpCode" class="form-control" maxlength="6" placeholder="• • • • • •" style="font-size: 24px; text-align: center; letter-spacing: 6px; font-weight: 800; height: 48px; border-radius: 10px;">
                </div>
                <div id="otpValidationMsg" style="font-size: 12px; margin-top: 8px;"></div>
            </div>
            <div class="modal-footer" style="padding: 15px 20px; border-top: 1px solid #F1F5F9; display: flex; justify-content: space-between;">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px; font-weight: 600;">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-success" id="btnSubmitCandidateWaOtp" style="background: #059669; border: none; border-radius: 8px; font-weight: 700;">
                    {{ __('Verify Now') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Fetch Current Preferences
    function loadWaPreferences() {
        $.ajax({
            url: "{{ route('whatsapp.preferences.get') }}",
            type: "GET",
            success: function(res) {
                if (res.success) {
                    $('#candidateWaNumber').val(res.phone || '');
                    $('#chkCandidateJobs').prop('checked', res.preferences.allow_matching_jobs);
                    $('#chkCandidateApps').prop('checked', res.preferences.allow_application_updates);
                    $('#chkCandidateMsgs').prop('checked', res.preferences.allow_messages);
                    $('#chkCandidatePromo').prop('checked', res.preferences.allow_promotional);

                    if (res.is_verified) {
                        $('#waVerificationBadge').html(
                            '<span class="badge" style="background: #D1FAE5; color: #065F46; padding: 6px 12px; font-size: 11px; font-weight: 700; border-radius: 20px; border: 1px solid #10B981;">' +
                            '<i class="fa fa-check-circle" style="color: #059669;"></i> {{ __("Verified WhatsApp Number") }}' +
                            '</span>'
                        );
                        $('#btnRequestWaOtp').html('<i class="fa fa-check"></i> {{ __("Verified") }}').prop('disabled', true).css({'background': '#F1F5F9', 'color': '#059669'});
                    } else {
                        $('#waVerificationBadge').html(
                            '<span class="badge" style="background: #FEF3C7; color: #92400E; padding: 6px 12px; font-size: 11px; font-weight: 700; border-radius: 20px; border: 1px solid #F59E0B;">' +
                            '<i class="fa fa-exclamation-triangle" style="color: #D97706;"></i> {{ __("Unverified Number") }}' +
                            '</span>'
                        );
                    }
                }
            }
        });
    }

    loadWaPreferences();

    // 2. Save Preferences
    $('#btnSaveWaPreferences').on('click', function() {
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> {{ __("Saving...") }}');

        $.ajax({
            url: "{{ route('whatsapp.preferences.update') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                whatsapp_number: $('#candidateWaNumber').val(),
                allow_matching_jobs: $('#chkCandidateJobs').is(':checked') ? 1 : 0,
                allow_application_updates: $('#chkCandidateApps').is(':checked') ? 1 : 0,
                allow_messages: $('#chkCandidateMsgs').is(':checked') ? 1 : 0,
                allow_promotional: $('#chkCandidatePromo').is(':checked') ? 1 : 0
            },
            success: function(res) {
                $btn.prop('disabled', false).html('<i class="fa fa-save"></i> {{ __("Save Preferences") }}');
                alert(res.message);
                loadWaPreferences();
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html('<i class="fa fa-save"></i> {{ __("Save Preferences") }}');
                alert("Error saving preferences: " + (xhr.responseJSON ? xhr.responseJSON.message : xhr.statusText));
            }
        });
    });

    // 3. Request Verification OTP
    $('#btnRequestWaOtp').on('click', function() {
        var phone = $('#candidateWaNumber').val();
        if (!phone) {
            alert("{{ __('Please enter your WhatsApp phone number first.') }}");
            return;
        }

        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> {{ __("Sending OTP...") }}');

        $.ajax({
            url: "{{ route('whatsapp.preferences.send_otp') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                whatsapp_number: phone
            },
            success: function(res) {
                $btn.prop('disabled', false).html('<i class="fa fa-shield"></i> {{ __("Verify WhatsApp Number") }}');
                $('#candidateWaOtpCode').val('');
                $('#otpValidationMsg').empty();
                $('#modalCandidateWaOtp').modal('show');
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html('<i class="fa fa-shield"></i> {{ __("Verify WhatsApp Number") }}');
                alert("Error: " + (xhr.responseJSON ? xhr.responseJSON.message : xhr.statusText));
            }
        });
    });

    // 4. Submit & Verify OTP
    $('#btnSubmitCandidateWaOtp').on('click', function() {
        var otp = $('#candidateWaOtpCode').val();
        var phone = $('#candidateWaNumber').val();
        var $btn = $(this);

        if (!otp || otp.length < 6) {
            $('#otpValidationMsg').css('color', '#EF4444').text("{{ __('Please enter the 6-digit verification code.') }}");
            return;
        }

        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> {{ __("Verifying...") }}');

        $.ajax({
            url: "{{ route('whatsapp.preferences.verify_otp') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                whatsapp_number: phone,
                otp: otp
            },
            success: function(res) {
                $btn.prop('disabled', false).html('{{ __("Verify Now") }}');
                $('#modalCandidateWaOtp').modal('hide');
                alert(res.message);
                loadWaPreferences();
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html('{{ __("Verify Now") }}');
                $('#otpValidationMsg').css('color', '#EF4444').text(xhr.responseJSON ? xhr.responseJSON.message : 'Invalid OTP code.');
            }
        });
    });
});
</script>
