@php
$defaultCountryId = (int) ($siteSetting->default_country_id ?? 101);
$reqCountryId = Request::get('country_id');
if (is_array($reqCountryId)) $reqCountryId = reset($reqCountryId);
if (empty($reqCountryId)) $reqCountryId = $defaultCountryId;

$reqStateId = Request::get('state_id');
if (is_array($reqStateId)) $reqStateId = reset($reqStateId);

$reqCityId = Request::get('city_id');
if (is_array($reqCityId)) $reqCityId = reset($reqCityId);

// Resolve country name
$countryName = 'India';
if (!empty($reqCountryId)) {
    $cObj = \App\Country::where('country_id', $reqCountryId)->first();
    if ($cObj) $countryName = $cObj->country;
}

// Resolve state name
$stateName = '';
if (!empty($reqStateId)) {
    $sObj = \App\State::where('state_id', $reqStateId)->first();
    if ($sObj) $stateName = $sObj->state;
}

// Resolve city name
$cityName = '';
if (!empty($reqCityId)) {
    $ciObj = \App\City::where('city_id', $reqCityId)->first();
    if ($ciObj) $cityName = $ciObj->city;
}
@endphp

@if(Auth::guard('company')->check())
<form action="{{route('job.seeker.list')}}" method="get" class="search-location-form" data-form-type="seeker">
    <div class="searchbar">
		<div class="srchbox">
            {{-- 1. Keywords / Job Seeker Details (Leave As It Is) --}}
            <div class="form-group mb-2">
                <label for="empsearch">{{__('Keywords / Job Seeker Details')}}</label>
                <input type="text" name="search" id="empsearch" value="{{Request::get('search', '')}}" class="form-control" placeholder="{{__('Enter Skills or Job Seeker Details')}}" autocomplete="off" />
            </div>

            {{-- 2. 3 Searchable Inputs: Country, State, City (Function Removed) --}}
            <div class="srcsubfld">
                <div class="row">
                    {{-- SELECT COUNTRY --}}
                    <div class="col-lg-4 col-md-4 col-12 mb-2">
                        <label for="searchable_country_c">{{__('Select Country')}}</label>
                        <div class="searchable-select-wrap" data-type="country">
                            <input type="text" id="searchable_country_c" class="form-control searchable-input" placeholder="{{__('Select Country')}}" value="{{$countryName}}" autocomplete="off" />
                            <i class="fa fa-chevron-down searchable-arrow"></i>
                            <input type="hidden" name="country_id[]" class="searchable-hidden hid-country-id" value="{{$reqCountryId}}">
                            <div class="searchable-dropdown-list" style="display:none;"></div>
                        </div>
                    </div>

                    {{-- SELECT STATE --}}
                    <div class="col-lg-4 col-md-4 col-12 mb-2">
                        <label for="searchable_state_c">{{__('Select State')}}</label>
                        <div class="searchable-select-wrap" data-type="state">
                            <input type="text" id="searchable_state_c" class="form-control searchable-input" placeholder="{{__('Select State')}}" value="{{$stateName}}" autocomplete="off" />
                            <i class="fa fa-chevron-down searchable-arrow"></i>
                            <input type="hidden" name="state_id[]" class="searchable-hidden hid-state-id" value="{{$reqStateId}}">
                            <div class="searchable-dropdown-list" style="display:none;"></div>
                        </div>
                    </div>

                    {{-- SELECT CITY --}}
                    <div class="col-lg-4 col-md-4 col-12 mb-2">
                        <label for="searchable_city_c">{{__('Select City')}}</label>
                        <div class="searchable-select-wrap" data-type="city">
                            <input type="text" id="searchable_city_c" class="form-control searchable-input" placeholder="{{__('Select City')}}" value="{{$cityName}}" autocomplete="off" />
                            <i class="fa fa-chevron-down searchable-arrow"></i>
                            <input type="hidden" name="city_id[]" class="searchable-hidden hid-city-id" value="{{$reqCityId}}">
                            <div class="searchable-dropdown-list" style="display:none;"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- 3. Submit Button --}}
            <div class="search-btn-wrap mt-2">
                <input type="submit" class="btn btn-search-main" value="{{__('Search Job Seeker')}}">
            </div>
		</div>
    </div>
</form>
@else
<form action="{{route('job.list')}}" method="get" class="search-location-form" data-form-type="job">
    <div class="searchbar">
		<div class="srchbox">
            {{-- 1. Keywords / Job Title (Leave As It Is) --}}
            <div class="form-group mb-2">
                <label for="jbsearch">{{__('Keywords / Job Title')}}</label>
                <input type="text" name="search" id="jbsearch" value="{{Request::get('search', '')}}" class="form-control" placeholder="{{__('Enter Skills or job title')}}" autocomplete="off" />
            </div>
			
            {{-- 2. 3 Searchable Inputs: Country, State, City (Function Removed) --}}
            <div class="srcsubfld">
                <div class="row">
                    {{-- SELECT COUNTRY --}}
                    <div class="col-lg-4 col-md-4 col-12 mb-2">
                        <label for="searchable_country_j">{{__('Select Country')}}</label>
                        <div class="searchable-select-wrap" data-type="country">
                            <input type="text" id="searchable_country_j" class="form-control searchable-input" placeholder="{{__('Select Country')}}" value="{{$countryName}}" autocomplete="off" />
                            <i class="fa fa-chevron-down searchable-arrow"></i>
                            <input type="hidden" name="country_id[]" class="searchable-hidden hid-country-id" value="{{$reqCountryId}}">
                            <div class="searchable-dropdown-list" style="display:none;"></div>
                        </div>
                    </div>

                    {{-- SELECT STATE --}}
                    <div class="col-lg-4 col-md-4 col-12 mb-2">
                        <label for="searchable_state_j">{{__('Select State')}}</label>
                        <div class="searchable-select-wrap" data-type="state">
                            <input type="text" id="searchable_state_j" class="form-control searchable-input" placeholder="{{__('Select State')}}" value="{{$stateName}}" autocomplete="off" />
                            <i class="fa fa-chevron-down searchable-arrow"></i>
                            <input type="hidden" name="state_id[]" class="searchable-hidden hid-state-id" value="{{$reqStateId}}">
                            <div class="searchable-dropdown-list" style="display:none;"></div>
                        </div>
                    </div>

                    {{-- SELECT CITY --}}
                    <div class="col-lg-4 col-md-4 col-12 mb-2">
                        <label for="searchable_city_j">{{__('Select City')}}</label>
                        <div class="searchable-select-wrap" data-type="city">
                            <input type="text" id="searchable_city_j" class="form-control searchable-input" placeholder="{{__('Select City')}}" value="{{$cityName}}" autocomplete="off" />
                            <i class="fa fa-chevron-down searchable-arrow"></i>
                            <input type="hidden" name="city_id[]" class="searchable-hidden hid-city-id" value="{{$reqCityId}}">
                            <div class="searchable-dropdown-list" style="display:none;"></div>
                        </div>
                    </div>
                </div>
            </div>	

            {{-- 3. Submit Button --}}
            <div class="search-btn-wrap mt-2">
                <input type="submit" class="btn btn-search-main" value="{{__('Search Job')}}">
            </div>
		</div>
    </div>
</form>
@endif

<style>
.searchable-select-wrap {
    position: relative;
    width: 100%;
}
.searchable-select-wrap .searchable-input {
    width: 100% !important;
    height: 48px !important;
    padding: 10px 36px 10px 14px !important;
    border: 1.5px solid #E2E8F0 !important;
    border-radius: 10px !important;
    font-size: 14px !important;
    color: #0F172A !important;
    background: #FFFFFF !important;
    cursor: text !important;
    transition: border-color 0.2s ease, box-shadow 0.2s ease !important;
    margin-bottom: 0 !important;
}
.searchable-select-wrap .searchable-input:focus {
    border-color: #2563EB !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.14) !important;
    outline: none !important;
}
.searchable-select-wrap .searchable-arrow {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 12px;
    color: #64748B;
    pointer-events: none;
    transition: transform 0.2s ease;
}
.searchable-select-wrap.is-open .searchable-arrow {
    transform: translateY(-50%) rotate(180deg);
    color: #2563EB;
}
.searchable-dropdown-list {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    right: 0;
    background: #FFFFFF;
    border-radius: 10px;
    box-shadow: 0 12px 28px rgba(15, 23, 42, 0.16), 0 2px 6px rgba(0,0,0,0.06);
    border: 1px solid #CBD5E1;
    max-height: 220px;
    overflow-y: auto;
    z-index: 1060;
    padding: 4px 0;
}
.searchable-dropdown-item {
    padding: 8px 14px;
    font-size: 13.5px;
    color: #1E293B;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: background 0.15s ease, color 0.15s ease;
}
.searchable-dropdown-item:hover,
.searchable-dropdown-item.active {
    background: #EFF6FF;
    color: #2563EB;
    font-weight: 600;
}
.searchable-dropdown-item.is-selected {
    background: #F1F5F9;
    color: #1D4ED8;
    font-weight: 700;
}
.searchable-loading,
.searchable-empty {
    padding: 12px 14px;
    text-align: center;
    font-size: 12.5px;
    color: #64748B;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initSearchableLocationDropdowns();
});

function initSearchableLocationDropdowns() {
    var forms = document.querySelectorAll('.search-location-form');
    forms.forEach(function(form) {
        setupFormSearchableDropdowns(form);
    });
}

function setupFormSearchableDropdowns(form) {
    var countryWrap = form.querySelector('.searchable-select-wrap[data-type="country"]');
    var stateWrap = form.querySelector('.searchable-select-wrap[data-type="state"]');
    var cityWrap = form.querySelector('.searchable-select-wrap[data-type="city"]');

    if (!countryWrap || !stateWrap || !cityWrap) return;

    var wraps = [countryWrap, stateWrap, cityWrap];

    wraps.forEach(function(wrap) {
        initSingleSearchableWrap(wrap, form);
    });
}

function initSingleSearchableWrap(wrap, form) {
    var type = wrap.getAttribute('data-type');
    var input = wrap.querySelector('.searchable-input');
    var hidden = wrap.querySelector('.searchable-hidden');
    var list = wrap.querySelector('.searchable-dropdown-list');

    var debounceTimer = null;
    var activeIdx = -1;

    function getCountryId() {
        var hidC = form.querySelector('.hid-country-id');
        return hidC ? (hidC.value || '101') : '101';
    }

    function getStateId() {
        var hidS = form.querySelector('.hid-state-id');
        return hidS ? (hidS.value || '') : '';
    }

    function fetchOptions(query) {
        var params = new URLSearchParams();
        params.append('type', type);
        if (query) params.append('q', query);

        if (type === 'state') {
            params.append('country_id', getCountryId());
        } else if (type === 'city') {
            params.append('country_id', getCountryId());
            var sId = getStateId();
            if (sId) params.append('state_id', sId);
        }

        var url = '{{ route("search.locations.autocomplete") }}?' + params.toString();

        list.innerHTML = '<div class="searchable-loading"><i class="fa fa-spinner fa-spin"></i> Loading...</div>';
        list.style.display = 'block';
        wrap.classList.add('is-open');

        fetch(url)
            .then(function(res) { return res.json(); })
            .then(function(data) {
                renderList(data);
            })
            .catch(function() {
                list.innerHTML = '<div class="searchable-empty">Failed to load options.</div>';
            });
    }

    function renderList(items) {
        activeIdx = -1;
        if (!items || items.length === 0) {
            list.innerHTML = '<div class="searchable-empty">No options found.</div>';
            return;
        }

        var html = '';
        items.forEach(function(item, i) {
            var isSelected = (hidden.value && String(hidden.value) === String(item.id)) ? ' is-selected' : '';
            html += '<div class="searchable-dropdown-item' + isSelected + '" data-id="' + item.id + '" data-name="' + escapeHtml(item.name) + '" data-index="' + i + '">';
            html += '<span>' + escapeHtml(item.display || item.name) + '</span>';
            if (isSelected) {
                html += '<i class="fa fa-check text-primary" style="font-size:11px;"></i>';
            }
            html += '</div>';
        });

        list.innerHTML = html;

        var optionEls = list.querySelectorAll('.searchable-dropdown-item');
        optionEls.forEach(function(el) {
            el.addEventListener('mousedown', function(e) {
                e.preventDefault(); // prevent blur before click
                var id = el.getAttribute('data-id');
                var name = el.getAttribute('data-name');
                selectOption(id, name);
            });
        });
    }

    function selectOption(id, name) {
        hidden.value = id;
        input.value = name;
        closeDropdown();

        // Handle cascading reset
        if (type === 'country') {
            var stateInput = form.querySelector('.searchable-select-wrap[data-type="state"] .searchable-input');
            var stateHidden = form.querySelector('.hid-state-id');
            var cityInput = form.querySelector('.searchable-select-wrap[data-type="city"] .searchable-input');
            var cityHidden = form.querySelector('.hid-city-id');

            if (stateInput) stateInput.value = '';
            if (stateHidden) stateHidden.value = '';
            if (cityInput) cityInput.value = '';
            if (cityHidden) cityHidden.value = '';

        } else if (type === 'state') {
            var cityInput = form.querySelector('.searchable-select-wrap[data-type="city"] .searchable-input');
            var cityHidden = form.querySelector('.hid-city-id');

            if (cityInput) cityInput.value = '';
            if (cityHidden) cityHidden.value = '';
        }
    }

    function closeDropdown() {
        list.style.display = 'none';
        wrap.classList.remove('is-open');
        activeIdx = -1;
    }

    input.addEventListener('focus', function() {
        closeAllDropdowns(wrap);
        fetchOptions('');
    });

    input.addEventListener('click', function() {
        if (list.style.display !== 'block') {
            closeAllDropdowns(wrap);
            fetchOptions(input.value.trim());
        }
    });

    input.addEventListener('input', function() {
        var val = input.value.trim();
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
            fetchOptions(val);
        }, 150);
    });

    input.addEventListener('keydown', function(e) {
        var items = list.querySelectorAll('.searchable-dropdown-item');
        if (!items || items.length === 0 || list.style.display === 'none') return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            activeIdx++;
            if (activeIdx >= items.length) activeIdx = 0;
            updateHighlight(items);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            activeIdx--;
            if (activeIdx < 0) activeIdx = items.length - 1;
            updateHighlight(items);
        } else if (e.key === 'Enter') {
            if (activeIdx >= 0 && activeIdx < items.length) {
                e.preventDefault();
                var selected = items[activeIdx];
                selectOption(selected.getAttribute('data-id'), selected.getAttribute('data-name'));
            }
        } else if (e.key === 'Escape') {
            closeDropdown();
        }
    });

    function updateHighlight(items) {
        items.forEach(function(el, i) {
            if (i === activeIdx) {
                el.classList.add('active');
                el.scrollIntoView({ block: 'nearest' });
            } else {
                el.classList.remove('active');
            }
        });
    }

    document.addEventListener('click', function(e) {
        if (!wrap.contains(e.target)) {
            closeDropdown();
        }
    });

    function closeAllDropdowns(exceptWrap) {
        var allWraps = form.querySelectorAll('.searchable-select-wrap');
        allWraps.forEach(function(w) {
            if (w !== exceptWrap) {
                var l = w.querySelector('.searchable-dropdown-list');
                if (l) l.style.display = 'none';
                w.classList.remove('is-open');
            }
        });
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
}
</script>