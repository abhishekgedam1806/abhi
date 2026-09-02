<div class="profile-sub-section" id="experience">
    <div class="profile-section-header">
        <div class="profile-section-title-wrap">
            <div class="profile-section-icon" style="background:#FFFBEB; color:#D97706;">
                <i class="fa fa-briefcase"></i>
            </div>
            <div>
                <h5 onclick="showExperience();" class="profile-section-title" style="cursor:pointer;">{{__('Work Experience')}}</h5>
                <p class="profile-section-subtitle">Add your current and previous job roles, responsibilities and tenure</p>
            </div>
        </div>
        <button type="button" class="btn-add-section" onclick="showProfileExperienceModal();">
            <i class="fa fa-plus"></i> {{__('Add Experience')}}
        </button>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div id="experience_div">
                <div class="text-center py-3 text-muted" style="font-size:13px;"><i class="fa fa-spinner fa-spin"></i> Loading Experience...</div>
            </div>
        </div>
    </div>
</div>

@push('scripts') 
<script type="text/javascript">
    function showProfileExperienceModal(){
        loadProfileExperienceForm();
    }

    function loadProfileExperienceForm(){
        $.ajax({
            type: "POST",
            url: "{{ route('get.front.profile.experience.form', $user->id) }}",
            data: {"_token": "{{ csrf_token() }}"},
            dataType: 'json',
            success: function (json) {
                $("#add_experience_modal").html(json.html);
                initdatepicker();
                filterDefaultStatesExperience(0, 0);
                openProfileModal("#add_experience_modal");
            }
        });
    }

    function showProfileExperienceEditModal(profile_experience_id, state_id, city_id){
        loadProfileExperienceEditForm(profile_experience_id, state_id, city_id);
    }

    function loadProfileExperienceEditForm(profile_experience_id, state_id, city_id){
        $.ajax({
            type: "POST",
            url: "{{ route('get.front.profile.experience.edit.form', $user->id) }}",
            data: {"profile_experience_id": profile_experience_id, "_token": "{{ csrf_token() }}"},
            dataType: 'json',
            success: function (json) {
                $("#add_experience_modal").html(json.html);
                initdatepicker();
                filterDefaultStatesExperience(state_id, city_id);
                openProfileModal("#add_experience_modal");
            }
        });
    }

    function submitProfileExperienceForm() {
        var form = $('#add_edit_profile_experience');
        var btn = form.find('.btn-modal-save');
        var origText = btn.html();
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $('.help-block').html('');
        $('.form-group').removeClass('has-error');

        $.ajax({
            url     : form.attr('action'),
            type    : form.attr('method'),
            data    : form.serialize(),
            dataType: 'json',
            success : function (json){
                $("#add_experience_modal").html(json.html);
                showExperience();
                setTimeout(function(){
                    closeActiveModal();
                }, 1300);
            },
            error: function(json){
                btn.prop('disabled', false).html(origText);
                if (json.status === 422) {
                    var resJSON = json.responseJSON;
                    $.each(resJSON.errors, function (key, value) {
                        $('.' + key + '-error').html('<strong>' + value + '</strong>');
                        $('#div_' + key).addClass('has-error');
                    });
                }
            }
        });
    }

    function delete_profile_experience(id) {
        var msg = "{{__('Are you sure you want to delete this experience?')}}";
        if (confirm(msg)) {
            $.post("{{ route('delete.front.profile.experience') }}", {id: id, _method: 'DELETE', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    if (response == 'ok') {
                        $('#experience_' + id).remove();
                        showExperience();
                    } else {
                        alert('Request Failed!');
                    }
                });
        }
    }

    function initdatepicker(){
        $(".datepicker").datepicker({
            autoclose: true,
            format:'yyyy-m-d'
        });
    }

    $(document).ready(function(){
        showExperience();
        initdatepicker();
        $(document).on('change', '#experience_country_id', function (e) {
            e.preventDefault();
            filterDefaultStatesExperience(0, 0);
        });
        $(document).on('change', '#experience_state_id', function (e) {
            e.preventDefault();
            filterDefaultCitiesExperience(0);
        });
    });

    function showExperience() {
        $.post("{{ route('show.front.profile.experience', $user->id) }}", {user_id: {{$user->id}}, _method: 'POST', _token: '{{ csrf_token() }}'})
            .done(function (response) {
                $('#experience_div').html(response);
            });
    }

    function filterDefaultStatesExperience(state_id, city_id) {
        var country_id = $('#experience_country_id').val();
        if (country_id != ''){
            $.post("{{ route('filter.lang.states.dropdown') }}", {country_id: country_id, state_id: state_id, new_state_id: 'experience_state_id', _method: 'POST', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    $('#default_state_experience_dd').html(response);
                    filterDefaultCitiesExperience(city_id);
                });
        }
    }

    function filterDefaultCitiesExperience(city_id) {
        var state_id = $('#experience_state_id').val();
        if (state_id != ''){
            $.post("{{ route('filter.lang.cities.dropdown') }}", {state_id: state_id, city_id: city_id, _method: 'POST', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    $('#default_city_experience_dd').html(response);
                });
        }
    }
</script> 
@endpush