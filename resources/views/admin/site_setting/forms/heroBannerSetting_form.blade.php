{!! APFrmErrHelp::showErrorsNotice($errors) !!}
@include('flash::message')
<div class="form-body">
    <fieldset>
        <legend>Hero Banner Settings (Home Page):</legend>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group {!! APFrmErrHelp::hasError($errors, 'hero_image') !!}">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;"> 
                            @if(isset($siteSetting) && !empty($siteSetting->hero_image))
                                <img src="{{ asset('sitesetting_images/'.$siteSetting->hero_image) }}" alt="Hero Image" style="max-height: 140px; max-width: 190px;" />
                            @else
                                <img src="{{ asset('images/hero-person.png') }}" alt="Default Hero Image" style="max-height: 140px; max-width: 190px;" />
                            @endif
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                        <div> 
                            <span class="btn default btn-file"> 
                                <span class="fileinput-new"> Change Hero Image </span> 
                                <span class="fileinput-exists"> Change </span> 
                                {!! Form::file('hero_image', null, array('id'=>'hero_image')) !!} 
                            </span> 
                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a> 
                        </div>
                    </div>
                    {!! APFrmErrHelp::showErrors($errors, 'hero_image') !!}
                    <span class="help-block">Upload banner image (PNG recommended with transparent/white background)</span>
                </div>
            </div>
            @if(isset($siteSetting) && !empty($siteSetting->hero_image))
            <div class="col-md-6">
                <label class="bold">Current Hero Image:</label><br>
                <img src="{{ asset('sitesetting_images/'.$siteSetting->hero_image) }}" style="max-height: 150px; border: 1px solid #ddd; padding: 4px; border-radius: 8px;" />
            </div>    
            @endif  
        </div>

        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'hero_badge_text') !!}">
            {!! Form::label('hero_badge_text', 'Hero Badge Text (e.g. INDIA\'S #1 JOB PLATFORM)', ['class' => 'bold']) !!}                    
            {!! Form::text('hero_badge_text', null, array('class'=>'form-control', 'id'=>'hero_badge_text', 'placeholder'=>"🇮🇳 INDIA'S #1 JOB PLATFORM")) !!}
            {!! APFrmErrHelp::showErrors($errors, 'hero_badge_text') !!}                                       
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group {!! APFrmErrHelp::hasError($errors, 'hero_title_line1') !!}">
                    {!! Form::label('hero_title_line1', 'Main Title (Line 1)', ['class' => 'bold']) !!}                    
                    {!! Form::text('hero_title_line1', null, array('class'=>'form-control', 'id'=>'hero_title_line1', 'placeholder'=>'Your job search')) !!}
                    {!! APFrmErrHelp::showErrors($errors, 'hero_title_line1') !!}                                       
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group {!! APFrmErrHelp::hasError($errors, 'hero_title_line2') !!}">
                    {!! Form::label('hero_title_line2', 'Highlighted Title (Line 2 - Blue with Orange underline)', ['class' => 'bold']) !!}                    
                    {!! Form::text('hero_title_line2', null, array('class'=>'form-control', 'id'=>'hero_title_line2', 'placeholder'=>'ends here')) !!}
                    {!! APFrmErrHelp::showErrors($errors, 'hero_title_line2') !!}                                       
                </div>
            </div>
        </div>

        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'hero_subtitle') !!}">
            {!! Form::label('hero_subtitle', 'Hero Subtitle / Description', ['class' => 'bold']) !!}                    
            {!! Form::text('hero_subtitle', null, array('class'=>'form-control', 'id'=>'hero_subtitle', 'placeholder'=>'Discover 50 lakh+ career opportunities across India')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'hero_subtitle') !!}                                       
        </div>

        <hr>
        <h4><strong>Trust Stats (Pills below search)</strong></h4>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('hero_stat1_number', 'Stat 1 Number (e.g. 50L+)', ['class' => 'bold']) !!}
                    {!! Form::text('hero_stat1_number', null, array('class'=>'form-control', 'id'=>'hero_stat1_number', 'placeholder'=>'50L+')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('hero_stat1_label', 'Stat 1 Label (e.g. Jobs)', ['class' => 'bold']) !!}
                    {!! Form::text('hero_stat1_label', null, array('class'=>'form-control', 'id'=>'hero_stat1_label', 'placeholder'=>'Jobs')) !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('hero_stat2_number', 'Stat 2 Number (e.g. 1Cr+)', ['class' => 'bold']) !!}
                    {!! Form::text('hero_stat2_number', null, array('class'=>'form-control', 'id'=>'hero_stat2_number', 'placeholder'=>'1Cr+')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('hero_stat2_label', 'Stat 2 Label (e.g. Job Seekers)', ['class' => 'bold']) !!}
                    {!! Form::text('hero_stat2_label', null, array('class'=>'form-control', 'id'=>'hero_stat2_label', 'placeholder'=>'Job Seekers')) !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('hero_stat3_number', 'Stat 3 Number (e.g. 10K+)', ['class' => 'bold']) !!}
                    {!! Form::text('hero_stat3_number', null, array('class'=>'form-control', 'id'=>'hero_stat3_number', 'placeholder'=>'10K+')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('hero_stat3_label', 'Stat 3 Label (e.g. Companies)', ['class' => 'bold']) !!}
                    {!! Form::text('hero_stat3_label', null, array('class'=>'form-control', 'id'=>'hero_stat3_label', 'placeholder'=>'Companies')) !!}
                </div>
            </div>
        </div>

        <hr>
        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'hero_hired_text') !!}">
            {!! Form::label('hero_hired_text', 'Hired Floating Card Text', ['class' => 'bold']) !!}                    
            {!! Form::text('hero_hired_text', null, array('class'=>'form-control', 'id'=>'hero_hired_text', 'placeholder'=>'Rahul got placed at TCS')) !!}
            {!! APFrmErrHelp::showErrors($errors, 'hero_hired_text') !!}                                       
        </div>

    </fieldset>
</div>
