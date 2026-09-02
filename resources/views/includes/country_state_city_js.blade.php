<script type="text/javascript">
    $(document).ready(function ($) {
    $('#country_id').on('change', function (e) {
    e.preventDefault();
    filterStates(0);
    });
    $(document).on('change', '#state_id', function (e) {
    e.preventDefault();
    filterCities(0);
    });
    @php
            $state_id_array = Request::get('state_id', array(0 => 0));
            $initial_state_id = (int)($state_id_array[0] ?? 0);
            $has_initial_location = $initial_state_id > 0 || Request::filled('country_id');
    @endphp
        @if($has_initial_location)
            filterStates({{$initial_state_id}});
        @endif
    });
    function filterStates(state_id)
    {
    var country_id = $('#country_id').val();
    if (country_id != ''){
    $.post("{{ route('filter.states.dropdown') }}", {country_id: country_id, state_id: state_id, _method: 'POST', _token: '{{ csrf_token() }}'})
            .done(function (response) {
            $('#state_dd').html(response);
            @php
                    $city_id_array = Request::get('city_id', array(0 => 0));
                    $city_id_val = (int)($city_id_array[0] ?? 0);
            @endphp
                    filterCities({{ $city_id_val }});
            });
    }
    }
    function filterCities(city_id)
    {
    var state_id = $('#state_id').val();
    if (state_id != ''){
    $.post("{{ route('filter.cities.dropdown') }}", {state_id: state_id, city_id: city_id, _method: 'POST', _token: '{{ csrf_token() }}'})
            .done(function (response) {
            $('#city_dd').html(response);
            });
    }
    }
</script>