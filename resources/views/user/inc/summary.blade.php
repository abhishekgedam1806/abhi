<style>
    .ai-summary-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #EFF6FF;
        color: #1D4ED8;
        border: 1px solid #BFDBFE;
        font-size: 12.5px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(29, 78, 216, 0.06);
    }
    .ai-summary-btn:hover {
        background: #DBEAFE;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(29, 78, 216, 0.12);
        color: #1E40AF;
        text-decoration: none;
    }
    .ai-summary-btn.ai-polish-btn {
        background: #FAF5FF;
        color: #7E22CE;
        border-color: #E9D5FF;
    }
    .ai-summary-btn.ai-polish-btn:hover {
        background: #F3E8FF;
        color: #6B21A8;
    }
    .ai-pulse-sparkle {
        animation: aiSparklePulse 2s infinite ease-in-out;
    }
    @keyframes aiSparklePulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.15); opacity: 0.85; }
    }
</style>

<div style="margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
    <div>
        <h4 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0 0 4px 0; display: flex; align-items: center; gap: 8px;">
            <i class="fa fa-align-left text-primary" style="font-size: 16px;"></i> {{__('Profile Summary & Objective')}}
        </h4>
        <p style="font-size: 13px; color: #64748B; margin: 0;">{{__('Write a compelling summary highlighting your strengths, career goals, and experience')}}</p>
    </div>
    
    <!-- AI Action Buttons -->
    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        <button type="button" class="ai-summary-btn" id="btn_ai_generate_summary" onclick="generateAISummary('generate');" title="Auto-generate ATS summary based on your experience and skills">
            <i class="fa fa-magic ai-pulse-sparkle" style="color: #2563EB;"></i>
            <span>{{__('✨ Write with AI')}}</span>
        </button>
        <button type="button" class="ai-summary-btn ai-polish-btn" id="btn_ai_polish_summary" onclick="generateAISummary('polish');" title="Polish your existing summary into executive ATS format">
            <i class="fa fa-bolt" style="color: #9333EA;"></i>
            <span>{{__('⚡ Enhance / Polish')}}</span>
        </button>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <form class="form" id="add_edit_profile_summary" method="POST" action="{{ route('update.front.profile.summary', [$user->id]) }}">
            {{ csrf_field() }}
            <div class="form-body">
                <div id="ai_summary_feedback" style="margin-bottom: 10px; display: none;"></div>
                <div id="success_msg" style="margin-bottom: 12px;"></div>
                
                <div class="formrow {!! APFrmErrHelp::hasError($errors, 'summary') !!}" style="margin-bottom: 16px; position: relative;">
                    <textarea name="summary" class="form-control modern-form-control" id="summary" rows="4" placeholder="{{__('e.g. Experienced Full Stack Engineer with 3+ years building responsive web apps with Laravel and React...')}}" style="width: 100%; padding: 12px 14px; border: 1.5px solid #CBD5E1; border-radius: 12px; font-size: 14px; outline: none; transition: all 0.2s ease;">{{ old('summary', $user->getProfileSummary('summary')) }}</textarea>
                    <span class="help-block summary-error" style="color: #DC2626; font-size: 12.5px; margin-top: 4px; display: block;"></span>
                </div>

                <button type="button" class="btn btn-save-summary" onClick="submitProfileSummaryForm();" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: #0F172A; color: #FFFFFF; font-size: 14px; font-weight: 700; padding: 10px 24px; border-radius: 10px; border: none; cursor: pointer; transition: all 0.15s ease;">
                    <i class="fa fa-check-circle"></i>
                    <span>{{__('Update Summary')}}</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts') 
<script type="text/javascript">
    function generateAISummary(mode) {
        var btn = (mode === 'polish') ? $('#btn_ai_polish_summary') : $('#btn_ai_generate_summary');
        var originalHtml = btn.html();
        var currentText = $('#summary').val();

        btn.prop('disabled', true).html('<i class="fa fa-circle-o-notch fa-spin"></i> AI Writing...');
        $('#ai_summary_feedback').slideUp();

        $.ajax({
            url: "{{ route('candidate.ai.summary') }}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                "mode": mode,
                "current_summary": currentText
            },
            dataType: 'json',
            success: function (res) {
                btn.prop('disabled', false).html(originalHtml);
                if (res.success && res.summary) {
                    $('#summary').val(res.summary);
                    $('#summary').css('border-color', '#2563EB').css('background-color', '#F0F7FF');
                    setTimeout(function(){
                        $('#summary').css('background-color', '#FFFFFF');
                    }, 1200);

                    $('#ai_summary_feedback').html(
                        '<div style="background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; padding: 8px 14px; border-radius: 8px; font-size: 12.5px; display: flex; align-items: center; justify-content: space-between;">' +
                        '<span><i class="fa fa-check-circle" style="color: #03855c; margin-right: 6px;"></i> <strong>AI Summary Generated!</strong> Review the text above and click "Update Summary" to save.</span>' +
                        '<a href="javascript:;" onclick="$(\'#ai_summary_feedback\').slideUp();" style="color: #065F46; font-weight: bold; text-decoration: none;">&times;</a>' +
                        '</div>'
                    ).slideDown();
                } else {
                    alert(res.message || 'Could not generate summary. Please try again.');
                }
            },
            error: function (err) {
                btn.prop('disabled', false).html(originalHtml);
                alert('AI Service is momentarily busy. Please try again in a moment.');
            }
        });
    }

    function submitProfileSummaryForm() {
        var form = $('#add_edit_profile_summary');
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(),
            dataType: 'json',
            success: function (json) {
                $("#success_msg").html("<span class='text text-success' style='font-weight: 600;'><i class='fa fa-check'></i> {{__('Summary updated successfully')}}</span>");
                $('#ai_summary_feedback').slideUp();
            },
            error: function (json) {
                if (json.status === 422) {
                    var resJSON = json.responseJSON;
                    $('.help-block').html('');
                    $.each(resJSON.errors, function (key, value) {
                        $('.' + key + '-error').html('<strong>' + value + '</strong>');
                        $('#div_' + key).addClass('has-error');
                    });
                }
            }
        });
    }
</script> 
@endpush