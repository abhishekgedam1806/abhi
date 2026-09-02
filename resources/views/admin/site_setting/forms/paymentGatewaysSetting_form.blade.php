{!! APFrmErrHelp::showErrorsNotice($errors) !!}

@include('flash::message')

<div class="form-body">

    <fieldset style="background: #F8FAFC; border: 1px solid #93C5FD; border-radius: 8px; padding: 20px; margin-bottom: 25px;">

        <legend style="color: #2563EB; font-weight: 800; border-bottom: none; width: auto; padding: 0 10px; margin-bottom: 15px;">
            <i class="fa fa-bolt"></i> Razorpay (Recommended - UPI, GPay, PhonePe, Cards, NetBanking):
        </legend>

        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'is_razorpay_active') !!}">
            {!! Form::label('is_razorpay_active', 'Enable Razorpay Gateway?', ['class' => 'bold']) !!}
            <div class="radio-list">
                <label class="radio-inline">{!! Form::radio('is_razorpay_active', 1, true, ['id' => 'is_razorpay_active_yes']) !!} Yes (Active) </label>
                <label class="radio-inline">{!! Form::radio('is_razorpay_active', 0, null, ['id' => 'is_razorpay_active_no']) !!} No </label>
            </div>
            {!! APFrmErrHelp::showErrors($errors, 'is_razorpay_active') !!}
        </div>

        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'razorpay_key') !!}">
            {!! Form::label('razorpay_key', 'Razorpay Key ID', ['class' => 'bold']) !!}                    
            {!! Form::text('razorpay_key', null, array('class'=>'form-control', 'id'=>'razorpay_key', 'placeholder'=>'rzp_test_... or rzp_live_...')) !!}
            <small class="text-muted">Obtain from Razorpay Dashboard &gt; Settings &gt; API Keys</small>
            {!! APFrmErrHelp::showErrors($errors, 'razorpay_key') !!}                                       
        </div>    

        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'razorpay_secret') !!}">
            {!! Form::label('razorpay_secret', 'Razorpay Key Secret', ['class' => 'bold']) !!}                    
            {!! Form::password('razorpay_secret', array('class'=>'form-control', 'id'=>'razorpay_secret', 'placeholder'=>'Enter Key Secret')) !!}
            <small class="text-muted">Never expose this secret publicly. Stored securely on server.</small>
            {!! APFrmErrHelp::showErrors($errors, 'razorpay_secret') !!}                                       
        </div>

        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'razorpay_webhook_secret') !!}">
            {!! Form::label('razorpay_webhook_secret', 'Razorpay Webhook Secret', ['class' => 'bold']) !!}                    
            {!! Form::text('razorpay_webhook_secret', null, array('class'=>'form-control', 'id'=>'razorpay_webhook_secret', 'placeholder'=>'Webhook Secret configured in Razorpay Dashboard')) !!}
            <small class="text-muted">Webhook URL to configure in Razorpay: <code>{{ url('payment/webhook/razorpay') }}</code></small>
            {!! APFrmErrHelp::showErrors($errors, 'razorpay_webhook_secret') !!}                                       
        </div>

        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'razorpay_mode') !!}">
            {!! Form::label('razorpay_mode', 'Payment Environment Mode', ['class' => 'bold']) !!}
            <div class="radio-list">
                <?php
                $rzp_mode_test = 'checked="checked"';
                $rzp_mode_live = '';
                if (old('razorpay_mode', ((isset($siteSetting)) ? $siteSetting->razorpay_mode : 'test')) == 'live') {
                    $rzp_mode_test = '';
                    $rzp_mode_live = 'checked="checked"';
                }
                ?>
                <label class="radio-inline">
                    <input id="razorpay_mode_test" name="razorpay_mode" type="radio" value="test" {{$rzp_mode_test}}>
                    Test / Sandbox Mode
                </label>
                <label class="radio-inline">
                    <input id="razorpay_mode_live" name="razorpay_mode" type="radio" value="live" {{$rzp_mode_live}}>
                    Live / Production Mode
                </label>
            </div>
            {!! APFrmErrHelp::showErrors($errors, 'razorpay_mode') !!}
        </div>

    </fieldset>

    <fieldset>

        <legend>PayPal:</legend>

        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'paypal_account') !!}">

            {!! Form::label('paypal_account', 'Paypal account', ['class' => 'bold']) !!}                    

            {!! Form::text('paypal_account', null, array('class'=>'form-control', 'id'=>'paypal_account', 'placeholder'=>'paypal account')) !!}

            {!! APFrmErrHelp::showErrors($errors, 'paypal_account') !!}                                       

        </div>    

        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'paypal_client_id') !!}">

            {!! Form::label('paypal_client_id', 'Paypal client_id', ['class' => 'bold']) !!}                    

            {!! Form::text('paypal_client_id', null, array('class'=>'form-control', 'id'=>'paypal_client_id', 'placeholder'=>'paypal client_id')) !!}

            {!! APFrmErrHelp::showErrors($errors, 'paypal_client_id') !!}                                       

        </div>    

        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'paypal_secret') !!}">

            {!! Form::label('paypal_secret', 'Paypal secret', ['class' => 'bold']) !!}                    

            {!! Form::text('paypal_secret', null, array('class'=>'form-control', 'id'=>'paypal_secret', 'placeholder'=>'paypal secret')) !!}

            {!! APFrmErrHelp::showErrors($errors, 'paypal_secret') !!}                                       

        </div>    

        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'paypal_live_sandbox') !!}">

            {!! Form::label('paypal_live_sandbox', 'Is Sandbox?', ['class' => 'bold']) !!}

            <div class="radio-list">

                <?php

                $radio_1 = 'checked="checked"';

                $radio_2 = '';

                if (old('paypal_live_sandbox', ((isset($siteSetting)) ? $siteSetting->paypal_live_sandbox : 'sandbox')) == 'live') {

                    $radio_1 = '';

                    $radio_2 = 'checked="checked"';

                }

                ?>

                <label class="radio-inline">

                    <input id="paypal_sandbox" name="paypal_live_sandbox" type="radio" value="sandbox" {{$radio_1}}>

                    Sandbox </label>

                <label class="radio-inline">

                    <input id="paypal_live" name="paypal_live_sandbox" type="radio" value="live" {{$radio_2}}>

                    Live </label>

            </div>

            {!! APFrmErrHelp::showErrors($errors, 'paypal_live_sandbox') !!}

        </div>

        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'is_paypal_active') !!}">

            {!! Form::label('is_paypal_active', 'Is Paypal active?', ['class' => 'bold']) !!}

            <div class="radio-list">

                <label class="radio-inline">{!! Form::radio('is_paypal_active', 1, true, ['id' => 'is_paypal_active_yes']) !!} Yes </label>

                <label class="radio-inline">{!! Form::radio('is_paypal_active', 0, null, ['id' => 'is_paypal_active_no']) !!} No </label>

            </div>

            {!! APFrmErrHelp::showErrors($errors, 'is_paypal_active') !!}

        </div>

    </fieldset>

    <fieldset>

        <legend>PayU Money:</legend>
        <?php 
        $mode = array(
            'test' => 'TEST MODE',
            'live' => 'LIVE MODE',
        );
         ?>

        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'payu_money_mode') !!}">

            {!! Form::label('payu_money_mode', 'PayU Mode', ['class' => 'bold']) !!}                    
            {!! Form::select('payu_money_mode', $mode, null, array('class'=>'form-control', 'id'=>'payu_money_mode')) !!}

            {!! APFrmErrHelp::showErrors($errors, 'payu_money_mode') !!}                                       

        </div>    

        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'payu_money_key') !!}">

            {!! Form::label('payu_money_key', 'PayU key', ['class' => 'bold']) !!}                    

            {!! Form::text('payu_money_key', null, array('class'=>'form-control', 'id'=>'payu_money_key', 'placeholder'=>'PayU key')) !!}

            {!! APFrmErrHelp::showErrors($errors, 'payu_money_key') !!}                                       

        </div>    

        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'salt') !!}">

            {!! Form::label('salt', 'PayU Salt', ['class' => 'bold']) !!}                    

            {!! Form::text('salt', null, array('class'=>'form-control', 'id'=>'salt', 'placeholder'=>'PayU Salt')) !!}

            {!! APFrmErrHelp::showErrors($errors, 'salt') !!}                                       

        </div>    

        

        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'is_payu_active') !!}">

            {!! Form::label('is_payu_active', 'Is PayU active?', ['class' => 'bold']) !!}

            <div class="radio-list">

                <label class="radio-inline">{!! Form::radio('is_payu_active', 1, true, ['id' => 'is_payu_active_yes']) !!} Yes </label>

                <label class="radio-inline">{!! Form::radio('is_payu_active', 0, null, ['id' => 'is_payu_active_no']) !!} No </label>

            </div>

            {!! APFrmErrHelp::showErrors($errors, 'is_payu_active') !!}

        </div>

    </fieldset>

    <fieldset>

        <legend>Stripe:</legend>

        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'stripe_key') !!}">

            {!! Form::label('stripe_key', 'Stripe Publishable Key', ['class' => 'bold']) !!}                    

            {!! Form::text('stripe_key', null, array('class'=>'form-control', 'id'=>'stripe_key', 'placeholder'=>'Stripe Publishable Key')) !!}

            {!! APFrmErrHelp::showErrors($errors, 'stripe_key') !!}                                       

        </div>    

        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'stripe_secret') !!}">

            {!! Form::label('stripe_secret', 'Stripe Secret', ['class' => 'bold']) !!}                    

            {!! Form::text('stripe_secret', null, array('class'=>'form-control', 'id'=>'stripe_secret', 'placeholder'=>'Stripe Secret')) !!}

            {!! APFrmErrHelp::showErrors($errors, 'stripe_secret') !!}                                       

        </div>    

        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'is_stripe_active') !!}">

            {!! Form::label('is_stripe_active', 'Is Stripe active?', ['class' => 'bold']) !!}

            <div class="radio-list">

                <label class="radio-inline">{!! Form::radio('is_stripe_active', 1, true, ['id' => 'is_stripe_active_yes']) !!} Yes </label>

                <label class="radio-inline">{!! Form::radio('is_stripe_active', 0, null, ['id' => 'is_stripe_active_no']) !!} No </label>

            </div>

            {!! APFrmErrHelp::showErrors($errors, 'is_stripe_active') !!}

        </div>

    </fieldset>

    <fieldset>

        <legend>Packages:</legend>

        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'is_jobseeker_package_active') !!}">

            {!! Form::label('is_jobseeker_package_active', 'Is Package active for job seaker?', ['class' => 'bold']) !!}

            <div class="radio-list">

                <label class="radio-inline">{!! Form::radio('is_jobseeker_package_active', 1, null, ['id' => 'is_jobseeker_package_active_yes']) !!} Yes </label>

                <label class="radio-inline">{!! Form::radio('is_jobseeker_package_active', 0, true, ['id' => 'is_jobseeker_package_active_no']) !!} No </label>

            </div>

            {!! APFrmErrHelp::showErrors($errors, 'is_jobseeker_package_active') !!}

        </div>

        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'is_company_package_active') !!}">

            {!! Form::label('is_company_package_active', 'Is Package active for company?', ['class' => 'bold']) !!}

            <div class="radio-list">

                <label class="radio-inline">{!! Form::radio('is_company_package_active', 1, true, ['id' => 'is_company_package_active_yes']) !!} Yes </label>

                <label class="radio-inline">{!! Form::radio('is_company_package_active', 0, null, ['id' => 'is_company_package_active_no']) !!} No </label>

            </div>

            {!! APFrmErrHelp::showErrors($errors, 'is_company_package_active') !!}

        </div>        

    </fieldset>



</div>

