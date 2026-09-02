@php
if (!isset($functionalAreas)) {
    $functionalAreas = \App\Helpers\DataArrayHelper::langFunctionalAreasArray();
}
if (!isset($countries)) {
    $countries = \App\Helpers\DataArrayHelper::langCountriesArray();
}
@endphp
@if(Auth::guard('company')->check())
<form action="{{route('job.seeker.list')}}" method="get">
    <div class="searchbar">
		<div class="srchbox">
            <div class="form-group mb-2">
                <label for="empsearch">{{__('Keywords / Job Seeker Details')}}</label>
                <input type="text" name="search" id="empsearch" value="{{Request::get('search', '')}}" class="form-control" placeholder="{{__('Enter Skills or Job Seeker Details')}}" autocomplete="off" />
            </div>

            <div class="srcsubfld additional_fields">
                <div class="row">
                    <div class="col-lg-{{((bool)$siteSetting->country_specific_site)? 6:3}} col-md-6 col-12 mb-2">
                        <label for="functional_area_id_c">{{__('Select Functional Area')}}</label>
                        {!! Form::select('functional_area_id[]', ['' => __('Select Functional Area')]+$functionalAreas, Request::get('functional_area_id', null), array('class'=>'form-control', 'id'=>'functional_area_id_c')) !!}
                    </div>

                    @if((bool)$siteSetting->country_specific_site)
                    {!! Form::hidden('country_id[]', Request::get('country_id[]', $siteSetting->default_country_id), array('id'=>'country_id_c')) !!}
                    @else
                    <div class="col-lg-3 col-md-6 col-12 mb-2">
                        <label for="country_id_c">{{__('Select Country')}}</label>
                        {!! Form::select('country_id[]', ['' => __('Select Country')]+$countries, Request::get('country_id', $siteSetting->default_country_id), array('class'=>'form-control', 'id'=>'country_id_c')) !!}
                    </div>
                    @endif

                    <div class="col-lg-3 col-md-6 col-12 mb-2">
                        <label for="state_id">{{__('Select State')}}</label>
                        <span id="state_dd">
                            {!! Form::select('state_id[]', ['' => __('Select State')], Request::get('state_id', null), array('class'=>'form-control', 'id'=>'state_id')) !!}
                        </span>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mb-2">
                        <label for="city_id">{{__('Select City')}}</label>
                        <span id="city_dd">
                            {!! Form::select('city_id[]', ['' => __('Select City')], Request::get('city_id', null), array('class'=>'form-control', 'id'=>'city_id')) !!}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="search-btn-wrap mt-2">
                <input type="submit" class="btn btn-search-main" value="{{__('Search Job Seeker')}}">
            </div>
		</div>
    </div>
</form>
@else
<form action="{{route('job.list')}}" method="get">
    <div class="searchbar">
		<div class="srchbox">
            <div class="form-group mb-2">
                <label for="jbsearch">{{__('Keywords / Job Title')}}</label>
                <input type="text" name="search" id="jbsearch" value="{{Request::get('search', '')}}" class="form-control" placeholder="{{__('Enter Skills or job title')}}" autocomplete="off" />
            </div>
			
            <div class="srcsubfld additional_fields">
                <div class="row">
                    <div class="col-lg-{{((bool)$siteSetting->country_specific_site)? 6:3}} col-md-6 col-12 mb-2">
                        <label for="functional_area_id">{{__('Select Functional Area')}}</label>
                        {!! Form::select('functional_area_id[]', ['' => __('Select Functional Area')]+$functionalAreas, Request::get('functional_area_id', null), array('class'=>'form-control', 'id'=>'functional_area_id')) !!}
                    </div>

                    @if((bool)$siteSetting->country_specific_site)
                    {!! Form::hidden('country_id[]', Request::get('country_id[]', $siteSetting->default_country_id), array('id'=>'country_id')) !!}
                    @else
                    <div class="col-lg-3 col-md-6 col-12 mb-2">
                        <label for="country_id">{{__('Select Country')}}</label>
                        {!! Form::select('country_id[]', ['' => __('Select Country')]+$countries, Request::get('country_id', $siteSetting->default_country_id), array('class'=>'form-control', 'id'=>'country_id')) !!}
                    </div>
                    @endif

                    <div class="col-lg-3 col-md-6 col-12 mb-2">
                        <label for="state_id">{{__('Select State')}}</label>
                        <span id="state_dd">
                            {!! Form::select('state_id[]', ['' => __('Select State')], Request::get('state_id', null), array('class'=>'form-control', 'id'=>'state_id')) !!}
                        </span>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mb-2">
                        <label for="city_id">{{__('Select City')}}</label>
                        <span id="city_dd">
                            {!! Form::select('city_id[]', ['' => __('Select City')], Request::get('city_id', null), array('class'=>'form-control', 'id'=>'city_id')) !!}
                        </span>
                    </div>
                </div>
            </div>	

            <div class="search-btn-wrap mt-2">
                <input type="submit" class="btn btn-search-main" value="{{__('Search Job')}}">
            </div>
		</div>
    </div>
</form>
@endif