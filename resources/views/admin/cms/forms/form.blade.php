{!! APFrmErrHelp::showErrorsNotice($errors) !!}
<div style="display: flex; flex-direction: column; gap: 20px;">	
    {{-- Page Title --}}
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'page_title') !!}" style="margin: 0;">
        <label for="page_title" style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 6px;">
            Page Title (Heading) <span style="color: #EF4444;">*</span>
        </label>                    
        {!! Form::text('page_title', old('page_title', isset($cmsContent) ? $cmsContent->page_title : ''), array('class'=>'form-control', 'id'=>'page_title', 'placeholder'=>'e.g. About Us, Privacy Policy, Terms Of Use', 'style'=>'height:42px;border-radius:9px;border:1px solid #CBD5E1;padding:0 14px;font-size:13.5px;font-weight:600;')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'page_title') !!}                                       
    </div>

    {{-- Page Content / Description Body --}}
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'page_content') !!}" style="margin: 0;">
        <label for="page_content" style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 6px;">
            Page Content / Body (HTML & Text)
        </label>                    
        {!! Form::textarea('page_content', old('page_content', isset($cmsContent) ? $cmsContent->page_content : ''), array('class'=>'form-control', 'id'=>'page_content', 'placeholder'=>'Write your page description, paragraphs, and content here...', 'style'=>'border-radius:9px;border:1px solid #CBD5E1;min-height:220px;')) !!}
        {!! APFrmErrHelp::showErrors($errors, 'page_content') !!}                                       
    </div>

    {{-- Page Slug --}}
    <div class="form-group {!! APFrmErrHelp::hasError($errors, 'page_slug') !!}" style="margin: 0;">
        <label for="page_slug" style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 6px;">
            Page URL Slug <span style="color: #EF4444;">*</span>
        </label>                    
        {!! Form::text('page_slug', null, array('class'=>'form-control', 'id'=>'page_slug', 'placeholder'=>'e.g. about-us, terms-of-use, privacy-policy', 'style'=>'height:42px;border-radius:9px;border:1px solid #CBD5E1;padding:0 14px;font-size:13.5px;')) !!}
        <small style="color: #94A3B8; font-size: 12px; margin-top: 4px; display: block;">Live Page URL: {{ url('/cms') }}/<strong>[slug]</strong></small>
        {!! APFrmErrHelp::showErrors($errors, 'page_slug') !!}                                       
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'show_in_top_menu') !!}" style="margin: 0; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 14px 16px;">
            <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 8px;">
                <i class="fa fa-bars text-primary" style="margin-right: 5px;"></i> Show in Top Header Menu
            </label>
            <?php
            $radio_1 = 'checked="checked"';
            $radio_2 = '';
            if (old('show_in_top_menu', ((isset($cms)) ? $cms->show_in_top_menu : 1)) == 0) {
                $radio_1 = '';
                $radio_2 = 'checked="checked"';
            }
            ?>
            <div style="display: flex; gap: 16px;">
                <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 13.5px; font-weight: 600; color: #334155; cursor: pointer;">
                    <input id="show_in_top_menu" name="show_in_top_menu" type="radio" value="1" {{$radio_1}}> Yes
                </label>
                <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 13.5px; font-weight: 600; color: #64748B; cursor: pointer;">
                    <input id="not_show_in_top_menu" name="show_in_top_menu" type="radio" value="0" {{$radio_2}}> No
                </label>
            </div>
            {!! APFrmErrHelp::showErrors($errors, 'show_in_top_menu') !!}
        </div>

        <div class="form-group {!! APFrmErrHelp::hasError($errors, 'show_in_footer_menu') !!}" style="margin: 0; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 14px 16px;">
            <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 8px;">
                <i class="fa fa-sitemap text-info" style="margin-right: 5px;"></i> Show in Footer Menu
            </label>
            <?php
            $radio_1 = 'checked="checked"';
            $radio_2 = '';
            if (old('show_in_footer_menu', ((isset($cms)) ? $cms->show_in_footer_menu : 1)) == 0) {
                $radio_1 = '';
                $radio_2 = 'checked="checked"';
            }
            ?>
            <div style="display: flex; gap: 16px;">
                <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 13.5px; font-weight: 600; color: #334155; cursor: pointer;">
                    <input id="show_in_footer_menu" name="show_in_footer_menu" type="radio" value="1" {{$radio_1}}> Yes
                </label>
                <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 13.5px; font-weight: 600; color: #64748B; cursor: pointer;">
                    <input id="not_show_in_footer_menu" name="show_in_footer_menu" type="radio" value="0" {{$radio_2}}> No
                </label>
            </div>
            {!! APFrmErrHelp::showErrors($errors, 'show_in_footer_menu') !!}
        </div>
    </div>
</div>