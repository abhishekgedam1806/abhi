<?php
if (!isset($seo)) {
    $seo = (object)array('seo_title' => $siteSetting->site_name, 'seo_description' => $siteSetting->site_name, 'seo_keywords' => $siteSetting->site_name, 'seo_other' => '');
}
?>
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="{{ (session('localeDir', 'ltr'))}}" dir="{{ (session('localeDir', 'ltr'))}}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{__($seo->seo_title) }}</title>
    <meta name="Description" content="{!! $seo->seo_description !!}">
    <link rel="canonical" href="{{ url()->current() . (request()->filled('lang') ? '?lang=' . request('lang') : '') }}" />

    {{-- Multilingual SEO hreflang Alternate Tags --}}
    @if(isset($siteLanguages) && count($siteLanguages) > 1)
        @foreach($siteLanguages as $sLang)
    <link rel="alternate" hreflang="{{ $sLang->iso_code }}" href="{{ url()->current() }}?lang={{ $sLang->iso_code }}" />
        @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ url()->current() }}" />
    @endif

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ __($seo->seo_title) }}">
    <meta property="og:description" content="{!! strip_tags($seo->seo_description) !!}">
    <meta property="og:site_name" content="{{ $siteSetting->site_name }}">
    <meta property="og:image" content="{{ asset('sitesetting_images/thumb/' . ($siteSetting->site_logo ?? '')) }}">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ __($seo->seo_title) }}">
    <meta name="twitter:description" content="{!! strip_tags($seo->seo_description) !!}">
    <meta name="twitter:image" content="{{ asset('sitesetting_images/thumb/' . ($siteSetting->site_logo ?? '')) }}">

    <!-- Global WebSite & Organization JSON-LD Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "WebSite",
          "@id": "{{ url('/') }}/#website",
          "url": "{{ url('/') }}",
          "name": {!! json_encode($siteSetting->site_name) !!},
          "description": {!! json_encode(strip_tags($seo->seo_description)) !!},
          "potentialAction": {
            "@type": "SearchAction",
            "target": {
              "@type": "EntryPoint",
              "urlTemplate": "{{ url('/jobs') }}?search={search_term_string}"
            },
            "query-input": "required name=search_term_string"
          },
          "inLanguage": "{{ app()->getLocale() }}"
        },
        {
          "@type": "Organization",
          "@id": "{{ url('/') }}/#organization",
          "name": {!! json_encode($siteSetting->site_name) !!},
          "url": "{{ url('/') }}",
          "logo": {
            "@type": "ImageObject",
            "url": "{{ asset('sitesetting_images/thumb/' . ($siteSetting->site_logo ?? '')) }}"
          },
          "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "{{ $siteSetting->site_phone_primary }}",
            "contactType": "customer service",
            "email": "{{ $siteSetting->mail_to_address }}"
          }
        }
      ]
    }
    </script>

    <!-- Fav Icon & PWA Manifest -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#2563EB">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ $siteSetting->site_name ?? 'Jobs Portal' }}">
    <link rel="apple-touch-icon" href="{{ asset('images/pwa/apple-touch-icon.png') }}">
    <!-- Preconnect to Font Servers -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">

    <!-- Fonts (Non-render-blocking with display=swap) -->
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@500;600;700&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet"></noscript>

    <!-- Core Critical Stylesheets -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/apna-theme.css') }}" rel="stylesheet">

    <!-- Secondary / Component Styles (Asynchronous) -->
    <link href="{{ asset('css/owl.carousel.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="{{ asset('admin_assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" media="print" onload="this.media='all'" />
    <link href="{{ asset('admin_assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" media="print" onload="this.media='all'" />
    @if((session('localeDir', 'ltr') == 'rtl'))
    <link href="{{ asset('css/rtl-style.css') }}" rel="stylesheet">
    @endif
    @stack('styles')
</head>

<body>
    @yield('content')

    @php
        $isBusinessUser = Auth::check() && Auth::user()->isBusinessUser();
        $isJobSeeker = Auth::check() && Auth::user()->isJobSeeker();
        $isCompany = Auth::guard('company')->check();
        $isBusinessSection = Request::is('*business*') || Request::is('my-businesses*') || Request::is('add-business*') || Request::is('edit-business*') || Request::is('my-business-leads*');
    @endphp

    <!-- Mobile Bottom Navigation (Apna App Style) -->
    <div class="mobile-bottom-nav d-block d-md-none">
        @if($isBusinessUser || (!$isJobSeeker && !$isCompany && $isBusinessSection))
            {{-- DEDICATED BUSINESS PORTAL MOBILE BAR --}}
            <a href="{{url('/')}}" class="{{ Request::is('/') ? 'active' : '' }}">
                <i class="fa fa-home"></i><span>Home</span>
            </a>
            <a href="{{route('business.list')}}" class="{{ Request::is('businesses*') ? 'active' : '' }}">
                <i class="fa fa-compass"></i><span>Directory</span>
            </a>
            <a href="{{route('add.business')}}" class="{{ Request::is('add-business') ? 'active' : '' }}" style="color:#2563EB;">
                <i class="fa fa-plus-circle" style="font-size:20px;color:#2563EB;"></i><span>+ Business</span>
            </a>
            @if($isBusinessUser)
            <a href="{{route('business.dashboard')}}" class="{{ Request::is('business-dashboard') ? 'active' : '' }}">
                <i class="fa fa-tachometer"></i><span>Dashboard</span>
            </a>
            <a href="{{route('business.packages')}}" class="{{ Request::is('business-packages*') ? 'active' : '' }}">
                <i class="fa fa-credit-card"></i><span>Packages</span>
            </a>
            @else
            <a href="{{route('business.login')}}" class="{{ Request::is('login') ? 'active' : '' }}">
                <i class="fa fa-sign-in"></i><span>Login</span>
            </a>
            @endif
        @else
            {{-- JOB SEEKER & EMPLOYER PORTAL MOBILE BAR --}}
            <a href="{{url('/')}}" class="{{ Request::is('/') ? 'active' : '' }}">
                <i class="fa fa-home"></i><span>Home</span>
            </a>
            <a href="{{url('/jobs')}}" class="{{ Request::is('jobs') ? 'active' : '' }}">
                <i class="fa fa-briefcase"></i><span>Jobs</span>
            </a>
            @if($isJobSeeker)
            <a href="{{route('home')}}" class="{{ Request::is('home') ? 'active' : '' }}">
                <i class="fa fa-tachometer"></i><span>Dashboard</span>
            </a>
            @elseif($isCompany)
            <a href="{{route('post.job')}}">
                <i class="fa fa-plus-circle"></i><span>Post Job</span>
            </a>
            @else
            <a href="{{route('login')}}" class="{{ Request::is('login') ? 'active' : '' }}">
                <i class="fa fa-sign-in"></i><span>Login</span>
            </a>
            @endif
            <a href="{{url('/companies')}}">
                <i class="fa fa-building"></i><span>Companies</span>
            </a>
        @endif
    </div>

    <!-- Scroll to Top Button -->
    <div class="scroll-top-btn" id="scrollTopBtn" onclick="window.scrollTo({top:0,behavior:'smooth'})">
        <i class="fa fa-arrow-up"></i>
    </div>
    <!-- Optimized Core JavaScript -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/popper.js') }}" defer></script>
    <script src="{{ asset('js/bootstrap.min.js') }}" defer></script>
    <script src="{{ asset('js/owl.carousel.js') }}" defer></script>
    <script src="{{ asset('admin_assets/global/plugins/Bootstrap-3-Typeahead/bootstrap3-typeahead.min.js') }}" defer></script>
    <script src="{{ asset('admin_assets/global/plugins/select2/js/select2.full.min.js') }}" defer></script>
    {!! NoCaptcha::renderJs() !!}
    @stack('scripts')
    <!-- Custom js -->
    <script src="{{ asset('js/script.js') }}" defer></script>

    <style>
    .pwd-field-wrap {
        position: relative !important;
        display: block !important;
        width: 100% !important;
    }
    .pwd-field-wrap input.form-control {
        padding-right: 44px !important;
    }
    /* Hide native browser password reveal eye icon (Edge, Chrome, Safari) */
    input[type="password"]::-ms-reveal,
    input[type="password"]::-ms-clear {
        display: none !important;
    }
    input[type="password"]::-webkit-contacts-auto-fill-button,
    input[type="password"]::-webkit-credentials-auto-fill-button {
        visibility: hidden !important;
        display: none !important;
        pointer-events: none !important;
        height: 0 !important;
        width: 0 !important;
        margin: 0 !important;
    }
    .btn-pwd-eye {
        position: absolute !important;
        right: 12px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        background: transparent !important;
        border: none !important;
        padding: 6px !important;
        color: #94A3B8 !important;
        cursor: pointer !important;
        font-size: 16px !important;
        line-height: 1 !important;
        outline: none !important;
        z-index: 10 !important;
        transition: color 0.15s ease, transform 0.15s ease !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    .btn-pwd-eye:hover {
        color: #2563EB !important;
        transform: translateY(-50%) scale(1.1) !important;
    }
    .btn-pwd-eye.active {
        color: #2563EB !important;
    }
    </style>

    <script type="text/JavaScript">
        function togglePasswordVisibility(btn) {
            var $btn = btn ? $(btn) : $('.btn-pwd-eye, #password_toggle_icon').first();
            var wrapper = $btn.closest('.pwd-field-wrap');
            if (!wrapper.length) wrapper = $btn.parent();
            var input = wrapper.find('input').first();
            if (!input.length) input = $('#password_field, input[type="password"], input[type="text"]').first();
            var icon = $btn.is('i') ? $btn : $btn.find('i');
            
            if (input.length) {
                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                    $btn.addClass('active').attr('title', 'Hide Password');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                    $btn.removeClass('active').attr('title', 'Show Password');
                }
            }
        }

        function initPasswordToggles() {
            $('input[type="password"]').each(function() {
                var $input = $(this);
                // Avoid double wrapping if already wrapped or already has any eye button
                if ($input.parent().hasClass('pwd-field-wrap') || 
                    $input.siblings('.btn-pwd-eye').length > 0 || 
                    $input.parent().find('button, i.fa-eye, i.fa-eye-slash').length > 0) {
                    return;
                }
                $input.wrap('<div class="pwd-field-wrap"></div>');
                var $eyeBtn = $('<button type="button" class="btn-pwd-eye" onclick="togglePasswordVisibility(this)" tabindex="-1" title="Show Password"><i class="fa fa-eye-slash"></i></button>');
                $input.after($eyeBtn);
            });
        }

        $(document).ready(function(){
            initPasswordToggles();
            if (typeof $.fn.scrollTo === 'function' && $('.has-error').length > 0) {
                $(document).scrollTo('.has-error', 1000);
            }

            // Scroll to top button
            window.addEventListener('scroll', function() {
                var btn = document.getElementById('scrollTopBtn');
                if (btn) {
                    if (window.scrollY > 300) {
                        btn.classList.add('visible');
                    } else {
                        btn.classList.remove('visible');
                    }
                }
            });
        });
        function showProcessingForm(btn_id){
            $("#"+btn_id).val( 'Processing .....' );
            $("#"+btn_id).attr('disabled','disabled');
        }

        // ✅ Scroll to top on every page load & navigation
        window.addEventListener('load', function () {
            window.scrollTo({ top: 0, behavior: 'instant' });
        });

        // ✅ History back/forward button pe bhi top pe jao
        window.addEventListener('pageshow', function (e) {
            window.scrollTo({ top: 0, behavior: 'instant' });
        });

        // 📲 PWA Service Worker Registration & Smart Install Banner
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js').then(function(reg) {
                    console.log('JobsPortal PWA ServiceWorker registered: ', reg.scope);
                }).catch(function(err) {
                    console.log('JobsPortal PWA ServiceWorker registration failed: ', err);
                });
            });
        }

        // PWA Install Prompt Handler
        var deferredPrompt;
        window.addEventListener('beforeinstallprompt', function(e) {
            e.preventDefault();
            deferredPrompt = e;
            
            // Show Mobile Bottom Banner after 1.5s
            if (!sessionStorage.getItem('pwa_prompt_dismissed')) {
                setTimeout(function() {
                    var installBanner = document.getElementById('pwaInstallBanner');
                    if (installBanner) installBanner.style.display = 'flex';
                }, 1500);
            }
        });

        // Detect iOS
        var isIos = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        var isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone;

        if (isIos && !isStandalone && !sessionStorage.getItem('pwa_prompt_dismissed')) {
            setTimeout(function() {
                var installBanner = document.getElementById('pwaInstallBanner');
                if (installBanner) installBanner.style.display = 'flex';
            }, 2000);
        }

        function installPwaApp() {
            var installBanner = document.getElementById('pwaInstallBanner');
            if (installBanner) installBanner.style.display = 'none';

            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then(function(choiceResult) {
                    deferredPrompt = null;
                });
            } else if (isIos) {
                alert("📲 To install on iPhone/iPad:\n1. Tap the Share button at the bottom of Safari (⎋)\n2. Tap 'Add to Home Screen' (+)");
            } else {
                alert("📲 To install this App:\nTap the 3 dots (⋮) in your browser menu and tap 'Install app' or 'Add to Home screen'.");
            }
        }

        function dismissPwaPrompt() {
            var installBanner = document.getElementById('pwaInstallBanner');
            if (installBanner) installBanner.style.display = 'none';
            sessionStorage.setItem('pwa_prompt_dismissed', '1');
        }
    </script>

    <!-- PWA Install Banner on Mobile -->
    <div id="pwaInstallBanner" style="display: none; position: fixed; bottom: 16px; left: 16px; right: 16px; max-width: 480px; margin: 0 auto; background: #FFFFFF; border: 1.5px solid #2563EB; border-radius: 14px; padding: 12px 16px; box-shadow: 0 10px 30px rgba(37,99,235,0.2); z-index: 999999; align-items: center; justify-content: space-between; gap: 12px; font-family: 'Inter', sans-serif;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <img src="{{ asset('images/pwa/icon-192.png') }}" alt="App Icon" style="width: 40px; height: 40px; border-radius: 8px; flex-shrink: 0;">
            <div>
                <div style="font-size: 13.5px; font-weight: 700; color: #0F172A; line-height: 1.2;">Install Jobs Portal App</div>
                <div style="font-size: 11.5px; color: #64748B; margin-top: 2px;">Fast access & instant job notifications</div>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 6px;">
            <button type="button" onclick="installPwaApp()" style="background: #2563EB; color: #FFFFFF; border: none; border-radius: 6px; padding: 6px 12px; font-size: 12.5px; font-weight: 700; cursor: pointer;">Install</button>
            <button type="button" onclick="dismissPwaPrompt()" style="background: transparent; color: #94A3B8; border: none; font-size: 16px; cursor: pointer; padding: 4px 6px;">&times;</button>
        </div>
    </div>
</body>

</html>