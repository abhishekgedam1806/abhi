<div class="form-body">
    <div style="background: linear-gradient(135deg, #EFF6FF 0%, #F8FAFC 100%); border: 1.5px solid #BFDBFE; border-radius: 14px; padding: 18px 22px; margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
            <i class="fa fa-line-chart" style="font-size: 20px; color: #2563EB;"></i>
            <h4 style="margin: 0; font-size: 16px; font-weight: 800; color: #1E3A8A;">SEO, Webmaster & Global Tracking Codes</h4>
        </div>
        <p style="margin: 0; font-size: 13px; color: #475569; line-height: 1.5;">
            Manage all your webmaster verification codes and analytics tracking pixels in one centralized place. Codes added here will automatically load across all pages of your website.
        </p>
    </div>

    <!-- 1. Google Search Console -->
    <div class="form-group" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
        <label for="google_search_console_code" style="font-size: 14px; font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
            <span style="display: inline-flex; width: 24px; height: 24px; background: #EEF2FF; color: #4F46E5; border-radius: 6px; align-items: center; justify-content: center; font-size: 12px; font-weight: 900;">G</span>
            Google Search Console (GSC) Verification Meta Tag
        </label>
        <span class="help-block" style="font-size: 12px; color: #64748B; margin-bottom: 8px; display: block;">
            Paste your HTML Verification tag from Google Search Console (e.g. <code>&lt;meta name="google-site-verification" content="XYZ123..." /&gt;</code>)
        </span>
        {!! Form::textarea('google_search_console_code', null, [
            'class' => 'form-control',
            'id' => 'google_search_console_code',
            'rows' => '3',
            'placeholder' => '<meta name="google-site-verification" content="your-verification-code-here" />',
            'style' => 'font-family: monospace; font-size: 12.5px; border-radius: 8px; border-color: #CBD5E1; padding: 10px 12px;'
        ]) !!}
    </div>

    <!-- 2. Google Tag Manager / GA4 Head -->
    <div class="row">
        <div class="col-md-6">
            <div class="form-group" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                <label for="google_tag_manager_head" style="font-size: 14px; font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                    <i class="fa fa-google text-danger"></i>
                    Google Tag Manager / GA4 (Head Script)
                </label>
                <span class="help-block" style="font-size: 12px; color: #64748B; margin-bottom: 8px; display: block;">
                    Paste the primary <code>&lt;script&gt;</code> snippet for Google Tag Manager (GTM) or Google Analytics (gtag.js) to be loaded in <code>&lt;head&gt;</code>.
                </span>
                {!! Form::textarea('google_tag_manager_head', null, [
                    'class' => 'form-control',
                    'id' => 'google_tag_manager_head',
                    'rows' => '6',
                    'placeholder' => "<!-- Google Tag Manager / Analytics -->\n<script async src=\"https://www.googletagmanager.com/gtag/js?id=G-...\"></script>\n<script>\n  window.dataLayer = window.dataLayer || [];\n  ...\n</script>",
                    'style' => 'font-family: monospace; font-size: 12px; border-radius: 8px; border-color: #CBD5E1; padding: 10px 12px;'
                ]) !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                <label for="google_tag_manager_body" style="font-size: 14px; font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                    <i class="fa fa-code text-warning"></i>
                    Google Tag Manager (Body NoScript)
                </label>
                <span class="help-block" style="font-size: 12px; color: #64748B; margin-bottom: 8px; display: block;">
                    Paste the secondary GTM <code>&lt;noscript&gt;</code> snippet that loads immediately after the opening <code>&lt;body&gt;</code> tag.
                </span>
                {!! Form::textarea('google_tag_manager_body', null, [
                    'class' => 'form-control',
                    'id' => 'google_tag_manager_body',
                    'rows' => '6',
                    'placeholder' => "<!-- Google Tag Manager (noscript) -->\n<noscript><iframe src=\"https://www.googletagmanager.com/ns.html?id=GTM-...\" height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript>",
                    'style' => 'font-family: monospace; font-size: 12px; border-radius: 8px; border-color: #CBD5E1; padding: 10px 12px;'
                ]) !!}
            </div>
        </div>
    </div>

    <!-- 3. Meta (Facebook) Pixel -->
    <div class="form-group" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
        <label for="meta_pixel_code" style="font-size: 14px; font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
            <i class="fa fa-facebook-square" style="color: #1877F2; font-size: 17px;"></i>
            Meta Pixel (Facebook Ads Pixel Code)
        </label>
        <span class="help-block" style="font-size: 12px; color: #64748B; margin-bottom: 8px; display: block;">
            Paste your full Meta / Facebook Pixel base script (e.g. <code>fbq('init', '1234567890'); fbq('track', 'PageView');</code>)
        </span>
        {!! Form::textarea('meta_pixel_code', null, [
            'class' => 'form-control',
            'id' => 'meta_pixel_code',
            'rows' => '6',
            'placeholder' => "<!-- Meta Pixel Code -->\n<script>\n!function(f,b,e,v,n,t,s){...}(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');\nfbq('init', 'YOUR_PIXEL_ID');\nfbq('track', 'PageView');\n</script>",
            'style' => 'font-family: monospace; font-size: 12px; border-radius: 8px; border-color: #CBD5E1; padding: 10px 12px;'
        ]) !!}
    </div>

    <!-- 4. Additional Header & Footer Scripts -->
    <div class="row">
        <div class="col-md-6">
            <div class="form-group" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                <label for="header_custom_scripts" style="font-size: 14px; font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                    <i class="fa fa-header text-primary"></i>
                    Other Custom &lt;head&gt; Scripts
                </label>
                <span class="help-block" style="font-size: 12px; color: #64748B; margin-bottom: 8px; display: block;">
                    Any other head scripts: Microsoft Clarity, LinkedIn Insight Tag, Pinterest, custom meta tags, font links, etc.
                </span>
                {!! Form::textarea('header_custom_scripts', null, [
                    'class' => 'form-control',
                    'id' => 'header_custom_scripts',
                    'rows' => '5',
                    'placeholder' => "<!-- Custom Head Scripts -->\n<script>...</script>",
                    'style' => 'font-family: monospace; font-size: 12px; border-radius: 8px; border-color: #CBD5E1; padding: 10px 12px;'
                ]) !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                <label for="footer_custom_scripts" style="font-size: 14px; font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                    <i class="fa fa-terminal text-success"></i>
                    Custom &lt;/body&gt; / Footer Scripts
                </label>
                <span class="help-block" style="font-size: 12px; color: #64748B; margin-bottom: 8px; display: block;">
                    Scripts to run at the bottom of the page: Live chat widgets (Tawk.to, Crisp), WhatsApp floating widgets, etc.
                </span>
                {!! Form::textarea('footer_custom_scripts', null, [
                    'class' => 'form-control',
                    'id' => 'footer_custom_scripts',
                    'rows' => '5',
                    'placeholder' => "<!-- Live Chat / Footer Widget Scripts -->\n<script>...</script>",
                    'style' => 'font-family: monospace; font-size: 12px; border-radius: 8px; border-color: #CBD5E1; padding: 10px 12px;'
                ]) !!}
            </div>
        </div>
    </div>
</div>
