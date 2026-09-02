<div class="profile-sub-section" style="border-bottom:none; padding-bottom:0;">
    <div class="profile-section-header">
        <div class="profile-section-title-wrap">
            <div class="profile-section-icon" style="background:#FDF2F8; color:#DB2777;">
                <i class="fa fa-language"></i>
            </div>
            <div>
                <h5 onclick="showLanguages();" class="profile-section-title" style="cursor:pointer;">{{__('Languages Known')}}</h5>
                <p class="profile-section-subtitle">Add languages you can speak, read, write and understand fluently</p>
            </div>
        </div>
        <button type="button" class="btn-add-section" onclick="showProfileLanguageModal();">
            <i class="fa fa-plus"></i> {{__('Add Language')}}
        </button>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div id="language_div">
                <div class="text-center py-3 text-muted" style="font-size:13px;"><i class="fa fa-spinner fa-spin"></i> Loading Languages...</div>
            </div>
        </div>
    </div>
</div>

@push('scripts') 
<script type="text/javascript">
    function showProfileLanguageModal(){
        loadProfileLanguageForm();
    }

    function loadProfileLanguageForm(){
        $.ajax({
            type: "POST",
            url: "{{ route('get.front.profile.language.form', $user->id) }}",
            data: {"_token": "{{ csrf_token() }}"},
            dataType: 'json',
            success: function (json) {
                $("#add_language_modal").html(json.html);
                openProfileModal("#add_language_modal");
            }
        });
    }

    function showProfileLanguageEditModal(profile_language_id){
        loadProfileLanguageEditForm(profile_language_id);
    }

    function loadProfileLanguageEditForm(profile_language_id){
        $.ajax({
            type: "POST",
            url: "{{ route('get.front.profile.language.edit.form', $user->id) }}",
            data: {"profile_language_id": profile_language_id, "_token": "{{ csrf_token() }}"},
            dataType: 'json',
            success: function (json) {
                $("#add_language_modal").html(json.html);
                openProfileModal("#add_language_modal");
            }
        });
    }

    function submitProfileLanguageForm() {
        var form = $('#add_edit_profile_language');
        var btn = form.find('.btn-modal-save');
        var origText = btn.html();

        // Client-side validation for multi-select
        var langIds = form.find('input[name="language_ids[]"]');
        if (langIds.length > 0) {
            // Multi-select mode
            if (langIds.length === 0) {
                $('.language_id-error').html('<strong>Please select at least one language.</strong>');
                return;
            }
            var allLevelsSelected = true;
            form.find('select[name^="lang_levels"]').each(function() {
                if (!$(this).val()) {
                    allLevelsSelected = false;
                    return false;
                }
            });
            if (!allLevelsSelected) {
                $('.language_level_id-error').html('<strong>Please select proficiency level for each selected language.</strong>');
                return;
            }
            // Build language_level_ids[] from individual dropdowns
            $('#lang_hidden_inputs input[id^="hidden_level_"]').each(function() {
                var langId = this.id.replace('hidden_level_', '');
                var levelVal = $('#level_sel_' + langId).val();
                $(this).val(levelVal);
            });
        }

        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $('.help-block').html('');
        $('.form-group').removeClass('has-error');

        $.ajax({
            url     : form.attr('action'),
            type    : form.attr('method'),
            data    : form.serialize(),
            dataType: 'json',
            success : function (json){
                $("#add_language_modal").html(json.html);
                showLanguages();
                setTimeout(function(){
                    closeActiveModal();
                }, 1300);
            },
            error: function(json){
                btn.prop('disabled', false).html(origText);
                if (json.status === 422) {
                    var resJSON = json.responseJSON;
                    if (resJSON.errors) {
                        $.each(resJSON.errors, function (key, value) {
                            var cleanKey = key.replace(/\./g, '_').replace(/\*/g, '');
                            $('.' + cleanKey + '-error').html('<strong>' + value + '</strong>');
                            $('#div_' + cleanKey).addClass('has-error');
                        });
                    } else if (resJSON.message) {
                        $('.language_id-error').html('<strong>' + resJSON.message + '</strong>');
                    }
                }
            }
        });
    }


    function delete_profile_language(id) {
        var msg = "{{__('Are you sure you want to delete this language?')}}";
        if (confirm(msg)) {
            $.post("{{ route('delete.front.profile.language') }}", {id: id, _method: 'DELETE', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    if (response == 'ok') {
                        $('#language_' + id).remove();
                        showLanguages();
                    } else {
                        alert('Request Failed!');
                    }
                });
        }
    }

    $(document).ready(function(){
        showLanguages();
    });

    function showLanguages() {
        $.post("{{ route('show.front.profile.languages', $user->id) }}", {user_id: {{$user->id}}, _method: 'POST', _token: '{{ csrf_token() }}'})
            .done(function (response) {
                $('#language_div').html(response);
            });
    }
</script> 
@endpush