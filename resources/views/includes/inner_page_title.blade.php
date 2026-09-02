@php
    $bgStyle = '';
    if (isset($siteSetting) && !empty($siteSetting->page_title_bg_image) && file_exists(public_path('sitesetting_images/' . $siteSetting->page_title_bg_image))) {
        $bgStyle = 'background-image: url(' . asset('sitesetting_images/' . $siteSetting->page_title_bg_image) . ') !important; background-size: cover !important; background-position: center !important;';
    }
@endphp
<div class="pageTitle" style="{{ $bgStyle }}">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <h1 class="page-heading">{{$page_title}}</h1>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="breadCrumb"><a href="{{route('index')}}">{{__('Home')}}</a> / <span>{{$page_title}}</span></div>
            </div>
        </div>
    </div>
</div>