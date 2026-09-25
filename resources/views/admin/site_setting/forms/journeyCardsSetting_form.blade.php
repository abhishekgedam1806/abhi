{!! APFrmErrHelp::showErrorsNotice($errors) !!}
@include('flash::message')
<div class="form-body">
    <fieldset>
        <legend><i class="fa fa-compass" style="color: #2563EB;"></i> What Are You Looking For? (Home Page 3 Cards Section):</legend>
        
        {{-- Section Header Texts --}}
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('journey_badge_text', 'Section Badge Text', ['class' => 'bold']) !!}
                    {!! Form::text('journey_badge_text', null, ['class'=>'form-control', 'id'=>'journey_badge_text', 'placeholder'=>'What Are You Looking For?']) !!}
                    <span class="help-block">Top pill badge text (Default: What Are You Looking For?)</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('journey_main_title', 'Section Main Heading', ['class' => 'bold']) !!}
                    {!! Form::text('journey_main_title', null, ['class'=>'form-control', 'id'=>'journey_main_title', 'placeholder'=>'Choose Your Path on JobNBiz']) !!}
                    <span class="help-block">Section title heading (Default: Choose Your Path on JobNBiz)</span>
                </div>
            </div>
        </div>

        <hr style="margin: 20px 0; border-color: #E2E8F0;">

        {{-- Card 1: Job Seekers --}}
        <div style="background: #F8FAFC; border: 1.5px solid #DBEAFE; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
            <h4 style="color: #2563EB; font-weight: 800; margin-top: 0; margin-bottom: 16px;">
                <i class="fa fa-user"></i> Card 1: For Job Seekers
            </h4>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="bold">Card 1 Image:</label>
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 160px; height: 160px; border-radius: 10px; overflow: hidden; background: #EFF6FF; display: flex; align-items: center; justify-content: center;"> 
                                @if(isset($siteSetting) && !empty($siteSetting->journey_c1_image) && file_exists(public_path('sitesetting_images/'.$siteSetting->journey_c1_image)))
                                    <img src="{{ asset('sitesetting_images/'.$siteSetting->journey_c1_image) }}" alt="Card 1" style="max-height: 155px; max-width: 155px; object-fit: cover;" />
                                @else
                                    <img src="{{ asset('images/seeker-woman.jpg') }}" alt="Default Card 1" style="max-height: 155px; max-width: 155px; object-fit: cover;" />
                                @endif
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 160px; max-height: 160px;"> </div>
                            <div style="margin-top: 8px;"> 
                                <span class="btn default btn-file btn-sm"> 
                                    <span class="fileinput-new"> Change Image </span> 
                                    <span class="fileinput-exists"> Change </span> 
                                    {!! Form::file('journey_c1_image', null, ['id'=>'journey_c1_image', 'accept'=>'image/*']) !!} 
                                </span> 
                                <a href="javascript:;" class="btn red fileinput-exists btn-sm" data-dismiss="fileinput"> Remove </a> 
                            </div>
                        </div>
                        <span class="help-block" style="font-size: 11.5px;">Recommended: Vertical portrait (JPG or PNG)</span>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('journey_c1_eyebrow', 'Eyebrow Tag', ['class' => 'bold']) !!}
                                {!! Form::text('journey_c1_eyebrow', null, ['class'=>'form-control', 'id'=>'journey_c1_eyebrow', 'placeholder'=>'For Job Seekers']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('journey_c1_title', 'Card Title', ['class' => 'bold']) !!}
                                {!! Form::text('journey_c1_title', null, ['class'=>'form-control', 'id'=>'journey_c1_title', 'placeholder'=>'Find Jobs']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('journey_c1_desc', 'Short Description', ['class' => 'bold']) !!}
                        {!! Form::textarea('journey_c1_desc', null, ['class'=>'form-control', 'id'=>'journey_c1_desc', 'rows'=>2, 'placeholder'=>'Explore thousands of verified openings and apply directly with top employers.']) !!}
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('journey_c1_btn_text', 'Button Text', ['class' => 'bold']) !!}
                                {!! Form::text('journey_c1_btn_text', null, ['class'=>'form-control', 'id'=>'journey_c1_btn_text', 'placeholder'=>'Browse Jobs']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('journey_c1_btn_url', 'Button Link (URL / Route)', ['class' => 'bold']) !!}
                                {!! Form::text('journey_c1_btn_url', null, ['class'=>'form-control', 'id'=>'journey_c1_btn_url', 'placeholder'=>'/jobs']) !!}
                                <span class="help-block" style="font-size: 11.5px;">Leave blank for default /jobs route</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Employers --}}
        <div style="background: #F8FAFC; border: 1.5px solid #BBF7D0; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
            <h4 style="color: #059669; font-weight: 800; margin-top: 0; margin-bottom: 16px;">
                <i class="fa fa-briefcase"></i> Card 2: For Employers
            </h4>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="bold">Card 2 Image:</label>
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 160px; height: 160px; border-radius: 10px; overflow: hidden; background: #F0FDF4; display: flex; align-items: center; justify-content: center;"> 
                                @if(isset($siteSetting) && !empty($siteSetting->journey_c2_image) && file_exists(public_path('sitesetting_images/'.$siteSetting->journey_c2_image)))
                                    <img src="{{ asset('sitesetting_images/'.$siteSetting->journey_c2_image) }}" alt="Card 2" style="max-height: 155px; max-width: 155px; object-fit: contain;" />
                                @else
                                    <img src="{{ asset('images/hero-man.png') }}" alt="Default Card 2" style="max-height: 155px; max-width: 155px; object-fit: contain;" />
                                @endif
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 160px; max-height: 160px;"> </div>
                            <div style="margin-top: 8px;"> 
                                <span class="btn default btn-file btn-sm"> 
                                    <span class="fileinput-new"> Change Image </span> 
                                    <span class="fileinput-exists"> Change </span> 
                                    {!! Form::file('journey_c2_image', null, ['id'=>'journey_c2_image', 'accept'=>'image/*']) !!} 
                                </span> 
                                <a href="javascript:;" class="btn red fileinput-exists btn-sm" data-dismiss="fileinput"> Remove </a> 
                            </div>
                        </div>
                        <span class="help-block" style="font-size: 11.5px;">Recommended: Transparent PNG cutout or portrait</span>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('journey_c2_eyebrow', 'Eyebrow Tag', ['class' => 'bold']) !!}
                                {!! Form::text('journey_c2_eyebrow', null, ['class'=>'form-control', 'id'=>'journey_c2_eyebrow', 'placeholder'=>'For Employers']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('journey_c2_title', 'Card Title', ['class' => 'bold']) !!}
                                {!! Form::text('journey_c2_title', null, ['class'=>'form-control', 'id'=>'journey_c2_title', 'placeholder'=>'Hire Talent']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('journey_c2_desc', 'Short Description', ['class' => 'bold']) !!}
                        {!! Form::textarea('journey_c2_desc', null, ['class'=>'form-control', 'id'=>'journey_c2_desc', 'rows'=>2, 'placeholder'=>'Post jobs, discover verified candidates and hire the right talent quickly.']) !!}
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('journey_c2_btn_text', 'Button Text', ['class' => 'bold']) !!}
                                {!! Form::text('journey_c2_btn_text', null, ['class'=>'form-control', 'id'=>'journey_c2_btn_text', 'placeholder'=>'Post a Job']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('journey_c2_btn_url', 'Button Link (URL / Route)', ['class' => 'bold']) !!}
                                {!! Form::text('journey_c2_btn_url', null, ['class'=>'form-control', 'id'=>'journey_c2_btn_url', 'placeholder'=>'/post-job']) !!}
                                <span class="help-block" style="font-size: 11.5px;">Leave blank for default /post-job route</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Businesses --}}
        <div style="background: #F8FAFC; border: 1.5px solid #DDD6FE; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
            <h4 style="color: #7C3AED; font-weight: 800; margin-top: 0; margin-bottom: 16px;">
                <i class="fa fa-building-o"></i> Card 3: For Businesses
            </h4>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="bold">Card 3 Image:</label>
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 160px; height: 160px; border-radius: 10px; overflow: hidden; background: #FAF5FF; display: flex; align-items: center; justify-content: center;"> 
                                @if(isset($siteSetting) && !empty($siteSetting->journey_c3_image) && file_exists(public_path('sitesetting_images/'.$siteSetting->journey_c3_image)))
                                    <img src="{{ asset('sitesetting_images/'.$siteSetting->journey_c3_image) }}" alt="Card 3" style="max-height: 155px; max-width: 155px; object-fit: cover;" />
                                @else
                                    <img src="{{ asset('images/store-business.jpg') }}" alt="Default Card 3" style="max-height: 155px; max-width: 155px; object-fit: cover;" />
                                @endif
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 160px; max-height: 160px;"> </div>
                            <div style="margin-top: 8px;"> 
                                <span class="btn default btn-file btn-sm"> 
                                    <span class="fileinput-new"> Change Image </span> 
                                    <span class="fileinput-exists"> Change </span> 
                                    {!! Form::file('journey_c3_image', null, ['id'=>'journey_c3_image', 'accept'=>'image/*']) !!} 
                                </span> 
                                <a href="javascript:;" class="btn red fileinput-exists btn-sm" data-dismiss="fileinput"> Remove </a> 
                            </div>
                        </div>
                        <span class="help-block" style="font-size: 11.5px;">Recommended: Storefront photo (JPG or PNG)</span>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('journey_c3_eyebrow', 'Eyebrow Tag', ['class' => 'bold']) !!}
                                {!! Form::text('journey_c3_eyebrow', null, ['class'=>'form-control', 'id'=>'journey_c3_eyebrow', 'placeholder'=>'For Businesses']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('journey_c3_title', 'Card Title', ['class' => 'bold']) !!}
                                {!! Form::text('journey_c3_title', null, ['class'=>'form-control', 'id'=>'journey_c3_title', 'placeholder'=>'Get Discovered']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('journey_c3_desc', 'Short Description', ['class' => 'bold']) !!}
                        {!! Form::textarea('journey_c3_desc', null, ['class'=>'form-control', 'id'=>'journey_c3_desc', 'rows'=>2, 'placeholder'=>'List your local business, reach nearby customers, and grow your presence.']) !!}
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('journey_c3_btn_text', 'Button Text', ['class' => 'bold']) !!}
                                {!! Form::text('journey_c3_btn_text', null, ['class'=>'form-control', 'id'=>'journey_c3_btn_text', 'placeholder'=>'Find Businesses']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('journey_c3_btn_url', 'Button Link (URL / Route)', ['class' => 'bold']) !!}
                                {!! Form::text('journey_c3_btn_url', null, ['class'=>'form-control', 'id'=>'journey_c3_btn_url', 'placeholder'=>'/businesses']) !!}
                                <span class="help-block" style="font-size: 11.5px;">Leave blank for default /businesses route</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </fieldset>
</div>
