<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->

<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->

<!--[if !IE]><!-->

<html lang="en">

    <!--<![endif]-->

    <!-- BEGIN HEAD -->

    <head>

        <meta charset="utf-8" />

        <title>{{ $siteSetting->site_name }} | Admin Login</title>

        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <meta content="width=device-width, initial-scale=1" name="viewport" />

        <meta content="" name="description" />

        <meta content="" name="author" />

        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />

        <link href="{{ asset('/') }}admin_assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

        <link href="{{ asset('/') }}admin_assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />

        <link href="{{ asset('/') }}admin_assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

        <link href="{{ asset('/') }}admin_assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />

        <link href="{{ asset('/') }}admin_assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />

        <link href="{{ asset('/') }}admin_assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />

        <!-- END GLOBAL MANDATORY STYLES -->

        <!-- BEGIN THEME GLOBAL STYLES -->

        <link href="{{ asset('/') }}admin_assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />

        <link href="{{ asset('/') }}admin_assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />

        <!-- END THEME GLOBAL STYLES -->

        <!-- BEGIN THEME LAYOUT STYLES -->

        <link href="{{ asset('/') }}admin_assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />

        <link href="{{ asset('/') }}admin_assets/layouts/layout/css/themes/default.min.css" rel="stylesheet" type="text/css" id="style_color" />

        <link href="{{ asset('/') }}admin_assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />

        <!-- END THEME LAYOUT STYLES -->

        <!-- BEGIN PAGE LEVEL PLUGINS -->

        <link href="{{ asset('/') }}admin_assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />

        <link href="{{ asset('/') }}admin_assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />

        <link href="{{ asset('/') }}admin_assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />

        <link href="{{ asset('/') }}admin_assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />

        <link href="{{ asset('/') }}admin_assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />

        <link href="{{ asset('/') }}admin_assets/global/plugins/clockface/css/clockface.css" rel="stylesheet" type="text/css" />

        <link href="{{ asset('/') }}admin_assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />

        <link href="{{ asset('/') }}admin_assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />

        <!-- END PAGE LEVEL PLUGINS -->

        <link type="text/css" rel="stylesheet" media="all" href="{{ asset('/') }}admin_assets/custom.css?v={{ time() }}" />

        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v={{ time() }}" />
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ time() }}" />
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v={{ time() }}" />

        <style>
            /* Fix for datatable Action dropdowns and clean scrollbar layout */
            .table-scrollable {
                overflow-x: visible !important;
                overflow-y: visible !important;
                border: none !important;
                margin: 10px 0 !important;
                min-height: auto !important;
                padding-bottom: 0 !important;
            }
            .dataTables_wrapper, .table-container, .portlet-body, .portlet.light {
                overflow: visible !important;
            }
            .table-scrollable .btn-group, .table-container .btn-group {
                position: relative !important;
            }
            /* Default DOWNWARD opening menu (1st row & upper rows) */
            .table-scrollable .btn-group:not(.dropup) .dropdown-menu,
            .table-container .btn-group:not(.dropup) .dropdown-menu {
                top: 100% !important;
                bottom: auto !important;
                margin-top: 4px !important;
                margin-bottom: 0 !important;
                right: 0 !important;
                left: auto !important;
                z-index: 99999 !important;
                border-radius: 8px !important;
                box-shadow: 0 12px 35px rgba(15, 23, 42, 0.2) !important;
                border: 1px solid #E2E8F0 !important;
                background: #FFFFFF !important;
                padding: 6px 0 !important;
                min-width: 175px !important;
            }
            /* UPWARD opening menu (Dropup) for bottom/last rows */
            .table-scrollable .btn-group.dropup .dropdown-menu,
            .table-container .btn-group.dropup .dropdown-menu {
                top: auto !important;
                bottom: 100% !important;
                margin-bottom: 6px !important;
                margin-top: 0 !important;
                right: 0 !important;
                left: auto !important;
                z-index: 99999 !important;
                border-radius: 8px !important;
                box-shadow: 0 12px 35px rgba(15, 23, 42, 0.2) !important;
                border: 1px solid #E2E8F0 !important;
                background: #FFFFFF !important;
                padding: 6px 0 !important;
                min-width: 175px !important;
            }
            .table-scrollable .btn-group .dropdown-menu > li > a,
            .table-container .btn-group .dropdown-menu > li > a {
                padding: 8px 14px !important;
                font-weight: 500 !important;
                font-size: 13px !important;
                color: #1E293B !important;
                display: flex !important;
                align-items: center !important;
                gap: 8px !important;
            }
            .table-scrollable .btn-group .dropdown-menu > li > a:hover,
            .table-container .btn-group .dropdown-menu > li > a:hover {
                background-color: #F1F5F9 !important;
                color: #1B4FD8 !important;
            }
        </style>
        @stack('css')

        <script>

        var APP_URL = "{!!url('/')!!}"

        </script>

    </head>

    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">

        <!-- BEGIN HEADER -->

        <div class="page-header navbar navbar-fixed-top"> 

            <!-- BEGIN HEADER INNER -->

            <div class="page-header-inner "> 

                <!-- BEGIN LOGO -->

                <div class="page-logo"> 
                    <a href="{{ route('admin.home') }}" class="logo-link"> 
                        @php
                            $adminLogoVersion = isset($siteSetting->updated_at) ? strtotime($siteSetting->updated_at) : '1';
                        @endphp
                        @if(!empty($siteSetting->site_logo))
                            <img src="{{ asset('sitesetting_images/thumb/' . $siteSetting->site_logo) }}?v={{ $adminLogoVersion }}" 
                                 alt="{{ $siteSetting->site_name ?? 'Logo' }}" 
                                 class="admin-site-logo" 
                                 onerror="this.onerror=null; this.src='{{ asset('sitesetting_images/' . $siteSetting->site_logo) }}?v={{ $adminLogoVersion }}';" />
                        @else
                            <span class="admin-text-logo">{{ $siteSetting->site_name ?? 'JobNBiz' }}</span>
                        @endif
                    </a>
                    <div class="menu-toggler sidebar-toggler"> </div>
                </div>

                <!-- END LOGO --> 

                <!-- BEGIN RESPONSIVE MENU TOGGLER --> 

                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a> 

                <!-- END RESPONSIVE MENU TOGGLER --> 

                <!-- BEGIN TOP NAVIGATION MENU --> 

                @include('admin.shared.top_menu') 

                <!-- END TOP NAVIGATION MENU --> 

            </div>

            <!-- END HEADER INNER --> 

        </div>

        <!-- END HEADER --> 

        <!-- BEGIN HEADER & CONTENT DIVIDER -->

        <div class="clearfix"> </div>

        <!-- END HEADER & CONTENT DIVIDER --> 

        <!-- BEGIN CONTAINER -->

        <div class="page-container"> 

            <!-- BEGIN SIDEBAR -->

            <div class="page-sidebar-wrapper"> @include('admin.shared.sidebar') </div>

            <!-- END SIDEBAR --> 

            <!-- BEGIN CONTENT --> 

            @yield('content') 

            <!-- END CONTENT --> 

        </div>

        <!-- END CONTAINER --> 

        <!-- BEGIN FOOTER -->

        <div class="page-footer">

            <div class="page-footer-inner"> {{ date('Y')}} © {{ $siteSetting->site_name }}. Admin Panel. </div>

            <div class="scroll-to-top"> <i class="icon-arrow-up"></i> </div>

        </div>

        <!-- END FOOTER --> 

        <!--[if lt IE 9]>

                <script src="{{ asset('/') }}admin_assets/global/plugins/respond.min.js"></script>

                <script src="{{ asset('/') }}admin_assets/global/plugins/excanvas.min.js"></script> 

                <![endif]--> 

        <!-- BEGIN CORE PLUGINS --> 

        <script src="{{ asset('/') }}admin_assets/global/plugins/jquery.min.js" type="text/javascript"></script> 

        <script src="{{ asset('/') }}admin_assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 

        <script src="{{ asset('/') }}admin_assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script> 

        <script src="{{ asset('/') }}admin_assets/pages/scripts/ui-modals.min.js" type="text/javascript"></script> 

        <script src="{{ asset('/') }}admin_assets/global/plugins/js.cookie.min.js" type="text/javascript"></script> 

        <script src="{{ asset('/') }}admin_assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script> 

        <script src="{{ asset('/') }}admin_assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script> 

        <script src="{{ asset('/') }}admin_assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script> 

        <script src="{{ asset('/') }}admin_assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script> 

        <script src="{{ asset('/') }}admin_assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script> 

        <!-- END CORE PLUGINS --> 

        <!-- BEGIN THEME GLOBAL SCRIPTS --> 

        <script src="{{ asset('/') }}admin_assets/global/scripts/app.min.js" type="text/javascript"></script> 

        <!-- END THEME GLOBAL SCRIPTS --> 

        <!-- BEGIN THEME LAYOUT SCRIPTS --> 

        <script src="{{ asset('/') }}admin_assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script> 

        <script src="{{ asset('/') }}admin_assets/layouts/layout/scripts/demo.js" type="text/javascript"></script> 

        <script src="{{ asset('/') }}admin_assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script> 

        <!-- END THEME LAYOUT SCRIPTS --> 

        <!-- BEGIN PAGE LEVEL PLUGINS --> 

        <script src="{{ asset('/') }}admin_assets/global/scripts/datatable.js" type="text/javascript"></script> 

        <script src="{{ asset('/') }}admin_assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script> 

        <script src="{{ asset('/') }}admin_assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script> 

        <script src="{{ asset('/') }}admin_assets/global/plugins/jquery.scrollTo.min.js" type="text/javascript"></script> 

        <script src="{{ asset('/') }}admin_assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script> 

        <script src="{{ asset('/') }}admin_assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script> 

        <script src="{{ asset('/') }}admin_assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script> 

        <script src="{{ asset('/') }}admin_assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script> 

        <script src="{{ asset('/') }}admin_assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script> 

        <script src="{{ asset('/') }}admin_assets/global/plugins/Bootstrap-3-Typeahead/bootstrap3-typeahead.min.js" type="text/javascript"></script> 

        <!-- END PAGE LEVEL PLUGINS --> 

        <script src="{{ asset('/') }}admin_assets/global/scripts/app.min.js" type="text/javascript"></script> 

        <!-- BEGIN PAGE LEVEL SCRIPTS --> 

        <script src="{{ asset('/') }}admin_assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>

        <script src="{{ asset('/') }}admin_assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>

        <!-- END PAGE LEVEL SCRIPTS --> 




        <script>

$('#flash-overlay-modal').modal();

        </script> 

        @stack('scripts') 

        <script type="text/JavaScript">

            $(document).ready(function(){

            $(document).scrollTo('.msg_cls_for_focus', 2000);

            $(document).on('show.bs.dropdown', '.table-scrollable .btn-group, .table-container .btn-group, .dataTables_wrapper .btn-group', function() {
                var $btnGroup = $(this);
                var offset = $btnGroup.offset();
                var windowHeight = $(window).height();
                var scrollTop = $(window).scrollTop();
                var spaceBelow = windowHeight - (offset.top - scrollTop) - $btnGroup.outerHeight();

                $btnGroup.closest('.table-scrollable, .table-container, .portlet-body').css('overflow', 'visible');

                // If space below button is less than 240px (last row / bottom rows), open UPWARDS (.dropup), otherwise open DOWNWARDS
                if (spaceBelow < 240) {
                    $btnGroup.addClass('dropup');
                    $btnGroup.find('.fa-angle-down').removeClass('fa-angle-down').addClass('fa-angle-up');
                } else {
                    $btnGroup.removeClass('dropup');
                    $btnGroup.find('.fa-angle-up').removeClass('fa-angle-up').addClass('fa-angle-down');
                }
            });

            });

            function showProcessingForm(btn_id){		

            $("#"+btn_id).val( 'Processing .....' );

            $("#"+btn_id).attr('disabled','disabled');

            }

        </script>

    </body>

</html>

