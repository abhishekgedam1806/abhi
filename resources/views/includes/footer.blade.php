<!--Footer-->
<div class="largebanner shadow3">
<div class="adin">
{!! $siteSetting->above_footer_ad !!}
</div>
<div class="clearfix"></div>
</div>

@php
    $footerCol1Items   = App\SiteMenuItem::getFooterCol1Items();
    $footerCol2Items   = App\SiteMenuItem::getFooterCol2Items();
    $footerCol3Items   = App\SiteMenuItem::getFooterCol3Items();
    $footerCitiesItems = App\SiteMenuItem::getFooterCitiesItems();
    $showPopularCities = $siteSetting->footer_show_popular_cities ?? 1;
    $showPaymentIcons  = $siteSetting->footer_show_payment_icons ?? 1;
@endphp

<div class="footerWrap"> 
    <div class="container">
        <div class="row"> 

            <!--Column 1: Quick Links-->
            <div class="col-md-3 col-sm-6">
                <h5>{{ __($siteSetting->footer_col1_title ?: 'Quick Links') }}</h5>
                <ul class="quicklinks">
                    @if($footerCol1Items && count($footerCol1Items) > 0)
                        @foreach($footerCol1Items as $item)
                            <li class="{{ Request::url() == $item->getFormattedUrl() ? 'active' : '' }}">
                                <a href="{{ $item->getFormattedUrl() }}" target="{{ $item->target ?? '_self' }}">{{ __($item->title) }}</a>
                            </li>
                        @endforeach
                    @else
                        <li><a href="{{ route('index') }}">{{__('Home')}}</a></li>
                        <li><a href="{{ route('job.list') }}">{{__('Jobs')}}</a></li>
                        <li><a href="{{ url('/companies') }}">{{__('Companies')}}</a></li>
                        <li><a href="{{ route('business.list') }}">{{__('Businesses')}}</a></li>
                        <li><a href="{{ route('blogs') }}">{{__('Blog')}}</a></li>
                        <li><a href="{{ route('contact.us') }}">{{__('Contact Us')}}</a></li>
                        <li><a href="{{ route('faq') }}">{{__('FAQs')}}</a></li>
                        @if(isset($show_in_footer_menu))
                            @foreach($show_in_footer_menu as $footer_menu)
                                @php $cmsContent = App\CmsContent::getContentBySlug($footer_menu->page_slug); @endphp
                                @if($cmsContent)
                                <li class="{{ Request::url() == route('cms', $footer_menu->page_slug) ? 'active' : '' }}"><a href="{{ route('cms', $footer_menu->page_slug) }}">{{ $cmsContent->page_title }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endif
                </ul>
            </div>

            <!--Column 2: Categories-->
            <div class="col-md-3 col-sm-6">
                <h5>{{ __($siteSetting->footer_col2_title ?: 'Jobs By Functional Area') }}</h5>
                <ul class="quicklinks">
                    @if($footerCol2Items && count($footerCol2Items) > 0)
                        @foreach($footerCol2Items as $item)
                            <li class="{{ Request::url() == $item->getFormattedUrl() ? 'active' : '' }}">
                                <a href="{{ $item->getFormattedUrl() }}" target="{{ $item->target ?? '_self' }}">{{ __($item->title) }}</a>
                            </li>
                        @endforeach
                    @else
                        @php $functionalAreas = App\FunctionalArea::getUsingFunctionalAreas(10); @endphp
                        @foreach($functionalAreas as $functionalArea)
                        <li><a href="{{ route('job.list', ['functional_area_id[]'=>$functionalArea->functional_area_id]) }}">{{$functionalArea->functional_area}}</a></li>
                        @endforeach
                    @endif
                </ul>
            </div>

            <!--Column 3: Industries-->
            <div class="col-md-3 col-sm-6">
                <h5>{{ __($siteSetting->footer_col3_title ?: 'Jobs By Industry') }}</h5>
                <ul class="quicklinks">
                    @if($footerCol3Items && count($footerCol3Items) > 0)
                        @foreach($footerCol3Items as $item)
                            <li class="{{ Request::url() == $item->getFormattedUrl() ? 'active' : '' }}">
                                <a href="{{ $item->getFormattedUrl() }}" target="{{ $item->target ?? '_self' }}">{{ __($item->title) }}</a>
                            </li>
                        @endforeach
                    @else
                        @php $industries = App\Industry::getUsingIndustries(10); @endphp
                        @foreach($industries as $industry)
                        <li><a href="{{ route('job.list', ['industry_id[]'=>$industry->industry_id]) }}">{{$industry->industry}}</a></li>
                        @endforeach
                    @endif
                </ul>
                <div class="clear"></div>
            </div>

            <!--Column 4: Contact & Social Info-->
            <div class="col-md-3 col-sm-12">
                <h5>{{ __($siteSetting->footer_col4_title ?: 'Contact Us') }}</h5>
                <div class="address">{{ $siteSetting->site_street_address }}</div>
                <div class="email"> <a href="mailto:{{ $siteSetting->mail_to_address }}">{{ $siteSetting->mail_to_address }}</a> </div>
                <div class="phone"> <a href="tel:{{ $siteSetting->site_phone_primary }}">{{ $siteSetting->site_phone_primary }}</a></div>
                <!-- Social Icons -->
                <div class="social">@include('includes.footer_social')</div>
            </div>

        </div>

        {{-- Popular Cities Bar --}}
        @if($showPopularCities)
        <div style="border-top: 1px solid rgba(255,255,255,0.08); padding: 18px 0 6px 0; margin-top: 25px;">
            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px 16px;">
                <span style="font-size: 13px; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px;"><i class="fa fa-map-marker text-primary"></i> {{__('Popular Cities')}}:</span>
                @if($footerCitiesItems && count($footerCitiesItems) > 0)
                    @foreach($footerCitiesItems as $cIndex => $cityItem)
                        @if($cIndex > 0) <span style="color: rgba(255,255,255,0.2);">•</span> @endif
                        <a href="{{ $cityItem->getFormattedUrl() }}" target="{{ $cityItem->target ?? '_self' }}" style="color: #CBD5E1; font-size: 13px; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#60A5FA'" onmouseout="this.style.color='#CBD5E1'">{{ __($cityItem->title) }}</a>
                    @endforeach
                @else
                    <a href="{{ url('/jobs-in-nagpur') }}" style="color: #CBD5E1; font-size: 13px; text-decoration: none;">Jobs in Nagpur</a>
                    <span style="color: rgba(255,255,255,0.2);">•</span>
                    <a href="{{ url('/jobs-in-pune') }}" style="color: #CBD5E1; font-size: 13px; text-decoration: none;">Jobs in Pune</a>
                    <span style="color: rgba(255,255,255,0.2);">•</span>
                    <a href="{{ url('/jobs-in-mumbai') }}" style="color: #CBD5E1; font-size: 13px; text-decoration: none;">Jobs in Mumbai</a>
                    <span style="color: rgba(255,255,255,0.2);">•</span>
                    <a href="{{ url('/jobs-in-bangalore') }}" style="color: #CBD5E1; font-size: 13px; text-decoration: none;">Jobs in Bangalore</a>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
<!--Footer end--> 

<!--Copyright-->
<div class="copyright">
    <div class="container">
        <div class="row">
            <div class="{{ $showPaymentIcons ? 'col-md-8' : 'col-md-12 text-center' }}">
                <div class="bttxt">
                    @if(!empty($siteSetting->footer_copyright_text))
                        {!! $siteSetting->footer_copyright_text !!}
                    @else
                        {{__('Copyright')}} &copy; {{date('Y')}} {{ $siteSetting->site_name }}. {{__('All Rights Reserved')}}. {{__('Design by')}}: <a href="{{url('/')}}https://www.phpsoftwarestore.com/" target="_blank">PHP Software Store</a>
                    @endif
                </div>
            </div>
            @if($showPaymentIcons)
            <div class="col-md-4">
                <div class="paylogos"><img src="{{asset('/')}}images/payment-icons.png" alt="Payment Methods" width="220" height="28" loading="lazy" /></div>	
            </div>
            @endif
        </div>
    </div>
</div>
