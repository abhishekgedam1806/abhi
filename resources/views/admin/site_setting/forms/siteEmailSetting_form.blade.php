{!! APFrmErrHelp::showErrorsNotice($errors) !!}
@include('flash::message')

{{-- LIVE SMTP TEST & OTP SECURITY BANNER --}}
<div class="portlet light bordered" style="background: #F0FDF4; border: 1.5px solid #86EFAC !important; border-radius: 8px; margin-bottom: 20px;">
    <div class="portlet-title" style="border-bottom: 1px solid #BBF7D0; min-height: auto; padding: 10px 15px;">
        <div class="caption font-green-dark" style="font-weight: 700; font-size: 15px;">
            <i class="fa fa-paper-plane font-green-dark"></i> Live SMTP Mail Test & Security Diagnostics
        </div>
        <div class="actions">
            <a href="{{ route('admin.otp.logs') }}" class="btn btn-sm btn-outline green-dark" style="font-weight: 600;">
                <i class="fa fa-shield"></i> View OTP & Anti-Fraud Security Logs
            </a>
        </div>
    </div>
    <div class="portlet-body" style="padding: 15px;">
        <p style="font-size: 13px; color: #166534; margin-bottom: 12px;">
            Test your live email server connectivity. Enter any email address below to dispatch a real-time test email and verify that OTP delivery is working smoothly without delays.
        </p>
        <div style="display: flex; gap: 10px; align-items: center; max-width: 600px; flex-wrap: wrap;">
            <input type="email" id="test_smtp_email_input" class="form-control" placeholder="Enter recipient email (e.g. yourname@gmail.com)" style="flex: 1; min-width: 250px; height: 38px; border-radius: 6px;">
            <button type="button" id="btn_send_smtp_test" class="btn green" style="font-weight: 700; border-radius: 6px; height: 38px;">
                <i class="fa fa-paper-plane"></i> Send Test Email
            </button>
        </div>
        <div id="smtp_test_result" style="margin-top: 10px; display: none;"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('btn_send_smtp_test');
    var input = document.getElementById('test_smtp_email_input');
    var resultBox = document.getElementById('smtp_test_result');

    if (btn) {
        btn.addEventListener('click', function() {
            var email = input.value.trim();
            if (!email) {
                alert('Please enter a valid email address to test.');
                input.focus();
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Sending Test Email...';
            resultBox.style.display = 'block';
            resultBox.innerHTML = '<div class="alert alert-info" style="margin-bottom:0;"><i class="fa fa-spinner fa-spin"></i> Connecting to SMTP server and dispatching test message...</div>';

            $.ajax({
                url: '{{ route("test.smtp.email") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    test_email: email
                },
                success: function(res) {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa fa-paper-plane"></i> Send Test Email';
                    resultBox.innerHTML = '<div class="alert alert-success" style="margin-bottom:0;"><i class="fa fa-check-circle"></i> <strong>SUCCESS:</strong> ' + res.message + '</div>';
                },
                error: function(xhr) {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa fa-paper-plane"></i> Send Test Email';
                    var errMsg = xhr.responseJSON ? xhr.responseJSON.message : 'Unknown error occurred while connecting to SMTP server.';
                    resultBox.innerHTML = '<div class="alert alert-danger" style="margin-bottom:0;"><i class="fa fa-exclamation-triangle"></i> <strong>SMTP ERROR:</strong> ' + errMsg + '</div>';
                }
            });
        });
    }
});
</script>

<div class="form-body">
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'mail_driver') !!}">
        {!! Form::label('mail_driver', 'Mail Driver', ['class' => 'bold']) !!}                    
        {!! Form::select('mail_driver',$mail_drivers, null, array('class'=>'form-control', 'id'=>'mail_driver')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'mail_driver') !!}                                       
    </div>
    <br>
    <fieldset>
        <legend>SMTP Settings:</legend>    
        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'mail_host') !!}">
            {!! Form::label('mail_host', 'Mail Host', ['class' => 'bold']) !!}                    
            {!! Form::text('mail_host', null, array('class'=>'form-control', 'id'=>'mail_host', 'placeholder'=>'Mail Host')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'mail_host') !!}                                       
        </div>    
        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'mail_port') !!}">
            {!! Form::label('mail_port', 'Mail Port', ['class' => 'bold']) !!}                    
            {!! Form::text('mail_port', null, array('class'=>'form-control', 'id'=>'mail_port', 'placeholder'=>'Mail Port')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'mail_port') !!}                                       
        </div>    
        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'mail_encryption') !!}">
            {!! Form::label('mail_encryption', 'Mail Encryption', ['class' => 'bold']) !!}                    
            {!! Form::text('mail_encryption', null, array('class'=>'form-control', 'id'=>'mail_encryption', 'placeholder'=>'Mail Encryption')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'mail_encryption') !!}                                       
        </div>
        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'mail_username') !!}">
            {!! Form::label('mail_username', 'Mail Username', ['class' => 'bold']) !!}                    
            {!! Form::text('mail_username', null, array('class'=>'form-control', 'id'=>'mail_username', 'placeholder'=>'Mail Username')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'mail_username') !!}                                       
        </div>
        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'mail_password') !!}">
            {!! Form::label('mail_password', 'Mail Password', ['class' => 'bold']) !!}                    
            {!! Form::text('mail_password', null, array('class'=>'form-control', 'id'=>'mail_password', 'placeholder'=>'Mail Password')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'mail_password') !!}                                       
        </div>
    </fieldset>
    <br> 
    <fieldset>
        <legend>SendMail - Pretend Settings:</legend>     
        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'mail_sendmail') !!}">
            {!! Form::label('mail_sendmail', 'Mail Sendmail', ['class' => 'bold']) !!}                    
            {!! Form::text('mail_sendmail', null, array('class'=>'form-control', 'id'=>'mail_sendmail', 'placeholder'=>'Mail Sendmail')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'mail_sendmail') !!}                                       
        </div>
        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'mail_pretend') !!}">
            {!! Form::label('mail_pretend', 'Mail Pretend', ['class' => 'bold']) !!}                    
            {!! Form::text('mail_pretend', null, array('class'=>'form-control', 'id'=>'mail_pretend', 'placeholder'=>'Mail Pretend')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'mail_pretend') !!}                                       
        </div>
    </fieldset>    
    <br>
    <fieldset>
        <legend>MailGun Settings:</legend>    
        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'mailgun_domain') !!}">
            {!! Form::label('mailgun_domain', 'Mailgun Domain', ['class' => 'bold']) !!}                    
            {!! Form::text('mailgun_domain', null, array('class'=>'form-control', 'id'=>'mailgun_domain', 'placeholder'=>'Mailgun Domain')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'mailgun_domain') !!}                                       
        </div>
        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'mailgun_secret') !!}">
            {!! Form::label('mailgun_secret', 'Mailgun Secret', ['class' => 'bold']) !!}                    
            {!! Form::text('mailgun_secret', null, array('class'=>'form-control', 'id'=>'mailgun_secret', 'placeholder'=>'Mailgun Secret')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'mailgun_secret') !!}                                       
        </div>
    </fieldset>
    <br>
    <fieldset>
        <legend>Mandrill Settings:</legend>    
        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'mandrill_secret') !!}">
            {!! Form::label('mandrill_secret', 'Mandrill Secret', ['class' => 'bold']) !!}                    
            {!! Form::text('mandrill_secret', null, array('class'=>'form-control', 'id'=>'mandrill_secret', 'placeholder'=>'Mandrill Secret')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'mandrill_secret') !!}                                       
        </div>
    </fieldset>
    <br>
    <fieldset>
        <legend>Sparkpost Settings:</legend>    
        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'sparkpost_secret') !!}">
            {!! Form::label('sparkpost_secret', 'Sparkpost Secret', ['class' => 'bold']) !!}                    
            {!! Form::text('sparkpost_secret', null, array('class'=>'form-control', 'id'=>'sparkpost_secret', 'placeholder'=>'Sparkpost Secret')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'sparkpost_secret') !!}                                       
        </div>
    </fieldset>
    <br>
    <fieldset>
        <legend>AMAZON SES Settings:</legend>        
        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'ses_key') !!}">
            {!! Form::label('ses_key', 'SES Key', ['class' => 'bold']) !!}                    
            {!! Form::text('ses_key', null, array('class'=>'form-control', 'id'=>'ses_key', 'placeholder'=>'SES Key')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'ses_key') !!}                                       
        </div>
        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'ses_secret') !!}">
            {!! Form::label('ses_secret', 'SES Secret', ['class' => 'bold']) !!}                    
            {!! Form::text('ses_secret', null, array('class'=>'form-control', 'id'=>'ses_secret', 'placeholder'=>'SES Secret')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'ses_secret') !!}                                       
        </div>
        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'ses_region') !!}">
            {!! Form::label('ses_region', 'SES Region', ['class' => 'bold']) !!}                    
            {!! Form::text('ses_region', null, array('class'=>'form-control', 'id'=>'ses_region', 'placeholder'=>'SES Region')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'ses_region') !!}                                       
        </div>
    </fieldset>   
</div>
