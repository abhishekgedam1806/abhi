@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end --> 

<!-- 1. Hero / Search start -->
@include('includes.search')
<!-- Search End --> 

<!-- Promotional & Offer Banners Slider start -->
@include('includes.home_promotional_slider')
<!-- Promotional & Offer Banners Slider end --> 

<!-- What Are You Looking For? (3 Core Paths) start -->
@include('includes.demo_journey_cards')
<!-- What Are You Looking For? ends -->

<!-- 2. Browse Jobs by Category & Top Cities start -->
@include('includes.demo_popular_searches')
<!-- Browse Jobs by Category ends --> 

<!-- 3. Featured Jobs start -->
@include('includes.demo_featured_jobs')
<!-- Featured Jobs ends -->

<!-- 4. Featured Companies Slider start -->
@include('includes.demo_top_employers')
<!-- Featured Companies ends --> 

<!-- 5. Local Businesses start -->
@include('includes.demo_home_businesses')
<!-- Local Businesses ends -->

<!-- 6. Latest Jobs start -->
@include('includes.demo_latest_jobs')
<!-- Latest Jobs ends --> 

<!-- 7. How it Works start -->
@include('includes.demo_how_it_works')
<!-- How it Works Ends -->

<!-- 8. Blogs start -->
@include('includes.home_blogs')
<!-- Blogs End -->

<!-- 9. Subscribe start -->
@include('includes.subscribe')
<!-- Subscribe End -->

@include('includes.demo_footer')
@endsection

@push('styles')
<style>
/* 100% Mobile-Friendly Adjustments for Homepage */
html, body {
    overflow-x: hidden !important;
    max-width: 100vw !important;
    width: 100% !important;
}

@media (max-width: 767px) {
    /* Responsive Grid & Containers */
    .container {
        padding-left: 14px !important;
        padding-right: 14px !important;
        max-width: 100% !important;
    }
    .row {
        margin-left: -7px !important;
        margin-right: -7px !important;
    }
    .col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12,
    .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12,
    .col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12 {
        padding-left: 7px !important;
        padding-right: 7px !important;
    }

    /* Section Headings */
    h2, .titleTop h3, .demo-fj-title, .naukri-cat-title, .demo-sec-title {
        font-size: 21px !important;
        line-height: 1.25 !important;
        letter-spacing: -0.3px !important;
    }

    /* Subtitles */
    p.fj-subtitle, p.demo-fj-subtitle, p.naukri-cat-subtitle, p.demo-sec-subtitle {
        font-size: 13px !important;
        line-height: 1.4 !important;
    }

    /* Business Actions Bar on Mobile */
    .home-biz-actions {
        flex-wrap: wrap !important;
        gap: 6px !important;
    }
    .btn-home-biz-call, .btn-home-biz-wa {
        flex: 1 1 auto !important;
        padding: 6px 10px !important;
        font-size: 11.5px !important;
        justify-content: center !important;
    }
    .btn-home-biz-view {
        width: 100% !important;
        margin-top: 4px !important;
        justify-content: center !important;
        font-size: 12px !important;
    }

    /* Section paddings */
    .section {
        padding: 28px 0 !important;
    }

    /* Video section */
    .videowraper iframe {
        height: 210px !important;
    }

    /* Safe overflow handling */
    .largebanner {
        overflow: hidden !important;
        max-width: 100% !important;
    }
}
</style>
@endpush
@push('scripts') 
<script>
    $(document).ready(function ($) {
        $("form").submit(function () {
            $(this).find(":input").filter(function () {
                return !this.value;
            }).attr("disabled", "disabled");
            return true;
        });
        $("form").find(":input").prop("disabled", false);
    });
</script>
@include('includes.country_state_city_js')
@endpush
