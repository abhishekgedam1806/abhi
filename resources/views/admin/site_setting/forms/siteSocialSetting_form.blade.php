{!! APFrmErrHelp::showErrorsNotice($errors) !!}
@include('flash::message')
<div class="form-body">        
    <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 10px; padding: 14px 18px; margin-bottom: 22px;">
        <div style="font-weight: 700; color: #1E40AF; font-size: 14px; margin-bottom: 4px;">
            <i class="fa fa-info-circle"></i> Official Social Media Profiles
        </div>
        <div style="font-size: 12.5px; color: #3B82F6;">
            Enter your official social media profile URLs. Active URLs will automatically display as clickable icon links in the website footer.
        </div>
    </div>

    <!-- 1. LinkedIn -->
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'linkedin_address') !!}">
        <label class="bold" style="color: #0F172A; display: flex; align-items: center; gap: 6px;">
            <i class="fa fa-linkedin-square text-primary" style="font-size: 16px;"></i> LinkedIn Profile URL
        </label>
        {!! Form::text('linkedin_address', null, array('class'=>'form-control', 'id'=>'linkedin_address', 'placeholder'=>'https://linkedin.com/company/your-page')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'linkedin_address') !!}                                       
    </div>

    <!-- 2. Instagram -->
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'instagram_address') !!}">
        <label class="bold" style="color: #0F172A; display: flex; align-items: center; gap: 6px;">
            <i class="fa fa-instagram text-danger" style="font-size: 16px;"></i> Instagram Profile URL
        </label>
        {!! Form::text('instagram_address', null, array('class'=>'form-control', 'id'=>'instagram_address', 'placeholder'=>'https://instagram.com/your-handle')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'instagram_address') !!}                                       
    </div>

    <!-- 3. Facebook -->
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'facebook_address') !!}">
        <label class="bold" style="color: #0F172A; display: flex; align-items: center; gap: 6px;">
            <i class="fa fa-facebook-square text-primary" style="font-size: 16px;"></i> Facebook Page URL
        </label>
        {!! Form::text('facebook_address', null, array('class'=>'form-control', 'id'=>'facebook_address', 'placeholder'=>'https://facebook.com/your-page')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'facebook_address') !!}                                       
    </div>

    <!-- 4. Twitter / X -->
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'twitter_address') !!}">
        <label class="bold" style="color: #0F172A; display: flex; align-items: center; gap: 6px;">
            <i class="fa fa-twitter text-info" style="font-size: 16px;"></i> Twitter / X Profile URL
        </label>
        {!! Form::text('twitter_address', null, array('class'=>'form-control', 'id'=>'twitter_address', 'placeholder'=>'https://twitter.com/your-handle')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'twitter_address') !!}                                       
    </div>

    <!-- 5. YouTube -->
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'youtube_address') !!}">
        <label class="bold" style="color: #0F172A; display: flex; align-items: center; gap: 6px;">
            <i class="fa fa-youtube-play text-danger" style="font-size: 16px;"></i> YouTube Channel URL
        </label>
        {!! Form::text('youtube_address', null, array('class'=>'form-control', 'id'=>'youtube_address', 'placeholder'=>'https://youtube.com/@your-channel')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'youtube_address') !!}                                       
    </div>

    <!-- Optional / Legacy Platforms -->
    <div style="margin-top: 24px; border-top: 1px solid #E2E8F0; padding-top: 16px;">
        <a href="#legacySocialLinks" data-toggle="collapse" style="font-size: 13px; font-weight: 700; color: #64748B; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa fa-chevron-down"></i> Other Platforms (Pinterest, Tumblr, Flickr)
        </a>
        <div id="legacySocialLinks" class="collapse" style="margin-top: 14px;">
            <div class="form-group {!! APFrmErrHelp::hasError($errors, 'pinterest_address') !!}">
                {!! Form::label('pinterest_address', 'Pinterest Address', ['class' => 'bold']) !!}                    
                {!! Form::text('pinterest_address', null, array('class'=>'form-control', 'id'=>'pinterest_address', 'placeholder'=>'Pinterest Address')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'pinterest_address') !!}                                       
            </div>
            <div class="form-group {!! APFrmErrHelp::hasError($errors, 'tumblr_address') !!}">
                {!! Form::label('tumblr_address', 'Tumblr Address', ['class' => 'bold']) !!}                    
                {!! Form::text('tumblr_address', null, array('class'=>'form-control', 'id'=>'tumblr_address', 'placeholder'=>'Tumblr Address')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'tumblr_address') !!}                                       
            </div>
            <div class="form-group {!! APFrmErrHelp::hasError($errors, 'flickr_address') !!}">
                {!! Form::label('flickr_address', 'Flickr Address', ['class' => 'bold']) !!}                    
                {!! Form::text('flickr_address', null, array('class'=>'form-control', 'id'=>'flickr_address', 'placeholder'=>'Flickr Address')) !!}
                {!! APFrmErrHelp::showErrors($errors, 'flickr_address') !!}                                       
            </div>
        </div>
    </div>
</div>
