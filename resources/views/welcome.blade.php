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

<!-- 2. Browse Jobs by Category start -->
@include('includes.popular_searches')
<!-- Browse Jobs by Category ends --> 

<!-- 3. Featured Jobs start -->
@include('includes.featured_jobs')
<!-- Featured Jobs ends -->

<!-- 4. Featured Companies start -->
@include('includes.top_employers')
<!-- Featured Companies ends --> 

<!-- 5. Local Businesses start -->
@include('includes.home_businesses')
<!-- Local Businesses ends -->

<!-- 6. Latest Jobs start -->
@include('includes.latest_jobs')
<!-- Latest Jobs ends --> 

<!-- Login box start -->
@include('includes.login_text')
<!-- Login box ends --> 

<!-- How it Works start -->
@include('includes.how_it_works')
<!-- How it Works Ends -->

<!-- Testimonials start -->
@include('includes.testimonials')
<!-- Testimonials End -->

<!-- Video start -->
@include('includes.video')
<!-- Video end --> 

<!-- Login box start -->
@include('includes.employer_login_text')
<!-- Login box ends --> 

<!-- Blogs start -->
@include('includes.home_blogs')
<!-- Blogs End -->

<!-- Subscribe start -->
@include('includes.subscribe')
<!-- Subscribe End -->

@include('includes.footer')
@endsection
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
