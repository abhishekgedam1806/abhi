{!! APFrmErrHelp::showErrorsNotice($errors) !!}
<div style="display: flex; flex-direction: column; gap: 18px;">
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'seo_title') !!}" style="margin: 0;">
        <label for="seo_title" style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 6px;">
            <i class="fa fa-tag text-primary" style="margin-right: 5px;"></i> SEO Meta Title
        </label>                    
        {!! Form::text('seo_title', null, array('class'=>'form-control', 'id'=>'seo_title', 'placeholder'=>'Page title for search engines (50-60 characters)', 'style'=>'height:42px;border-radius:9px;border:1px solid #CBD5E1;padding:0 14px;font-size:13.5px;')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'seo_title') !!}                                       
    </div>

    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'seo_description') !!}" style="margin: 0;">
        <label for="seo_description" style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 6px;">
            <i class="fa fa-align-left text-info" style="margin-right: 5px;"></i> SEO Meta Description
        </label>                    
        {!! Form::textarea('seo_description', null, array('class'=>'form-control', 'id'=>'seo_description', 'rows'=>'3', 'placeholder'=>'Short summary for Google search results (150-160 characters)', 'style'=>'border-radius:9px;border:1px solid #CBD5E1;padding:10px 14px;font-size:13.5px;resize:vertical;')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'seo_description') !!}                                       
    </div>

    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'seo_keywords') !!}" style="margin: 0;">
        <label for="seo_keywords" style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 6px;">
            <i class="fa fa-key text-warning" style="margin-right: 5px;"></i> SEO Keywords
        </label>                    
        {!! Form::textarea('seo_keywords', null, array('class'=>'form-control', 'id'=>'seo_keywords', 'rows'=>'2', 'placeholder'=>'Comma-separated keywords (e.g. jobs, careers, employment)', 'style'=>'border-radius:9px;border:1px solid #CBD5E1;padding:10px 14px;font-size:13.5px;resize:vertical;')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'seo_keywords') !!}                                       
    </div>

    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'seo_other') !!}" style="margin: 0;">
        <label for="seo_other" style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 6px;">
            <i class="fa fa-code text-muted" style="margin-right: 5px;"></i> Custom Header / Meta Tags
        </label>                    
        {!! Form::textarea('seo_other', null, array('class'=>'form-control', 'id'=>'seo_other', 'rows'=>'2', 'placeholder'=>'<meta name="..." content="..."> or custom script tags', 'style'=>'border-radius:9px;border:1px solid #CBD5E1;padding:10px 14px;font-size:13px;font-family:monospace;resize:vertical;')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'seo_other') !!}                                       
    </div>
</div>