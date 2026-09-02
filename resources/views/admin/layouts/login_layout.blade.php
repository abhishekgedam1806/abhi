<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <head>
        <meta charset="utf-8" />
        <title>{{ $siteSetting->site_name }} | Admin Portal</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="{{ $siteSetting->site_name }} Admin Portal" name="description" />
        
        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        
        <!-- Mandatory Styles -->
        <link href="{{ asset('/') }}admin_assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset('/') }}admin_assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset('/') }}admin_assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset('/') }}admin_assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{ asset('/') }}admin_assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <link type="text/css" rel="stylesheet" media="all" href="{{ asset('/') }}admin_assets/custom.css" />
        <link rel="shortcut icon" href="{{ asset('/') }}favicon.ico" /> 
    </head>
    <body class="admin-login-body">
        <div class="admin-auth-wrapper">
            <div class="admin-auth-glow-1"></div>
            <div class="admin-auth-glow-2"></div>
            
            <div class="admin-auth-inner">
                @yield('content')
                
                <div class="admin-auth-footer">
                    <p>{{ date('Y') }} &copy; {{ $siteSetting->site_name }}. All rights reserved.</p>
                </div>
            </div>
        </div>

        <!-- Core Plugins -->
        <script src="{{ asset('/') }}admin_assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="{{ asset('/') }}admin_assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="{{ asset('/') }}admin_assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="{{ asset('/') }}admin_assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <script src="{{ asset('/') }}admin_assets/pages/scripts/login.js" type="text/javascript"></script>
        @stack('scripts')
    </body>
</html>
