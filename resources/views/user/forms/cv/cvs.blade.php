<div class="profile-sub-section" id="cvs">
    <div class="profile-section-header">
        <div class="profile-section-title-wrap">
            <div class="profile-section-icon" style="background:#EFF6FF; color:#2563EB;">
                <i class="fa fa-file-text-o"></i>
            </div>
            <div>
                <h5 class="profile-section-title">{{__('Curriculum Vitae (Resume)')}}</h5>
                <p class="profile-section-subtitle">Upload your latest resume in PDF or Word format</p>
            </div>
        </div>
        <button type="button" class="btn-add-section" onclick="showProfileCvModal();">
            <i class="fa fa-plus"></i> {{__('Add CV')}}
        </button>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div id="cvs_div">
                <div class="text-center py-3 text-muted" style="font-size:13px;"><i class="fa fa-spinner fa-spin"></i> Loading CVs...</div>
            </div>
        </div>
    </div>
</div>

@push('scripts') 
<script type="text/javascript">
    $(document).ready(function(){
        showCvs();
    });

    function showProfileCvModal(){
        loadProfileCvForm();
    }

    function loadProfileCvForm(){
        $.ajax({
            type: "POST",
            url: "{{ route('get.front.profile.cv.form', $user->id) }}",
            data: {"_token": "{{ csrf_token() }}"},
            dataType: 'json',
            success: function (json) {
                $("#add_cv_modal").html(json.html);
                openProfileModal("#add_cv_modal");
            }
        });
    }

    function submitProfileCvForm() {
        var form = $('#add_edit_profile_cv');
        var btn = form.find('.btn-modal-save');
        var origText = btn.html();

        var title = $('#title').val();
        var fileInput = document.getElementById("cv_file");
        if ((!title || title.trim() === '') && fileInput && fileInput.files && fileInput.files[0]) {
            title = fileInput.files[0].name.replace(/\.[^/.]+$/, "");
            $('#title').val(title);
        }

        var formData = new FormData();
        formData.append("id", $('#id').val());
        formData.append("_token", $('input[name=_token]').val());
        formData.append("title", title || 'My Resume');
        formData.append("is_default", $('input[name=is_default]:checked').val() || 1);

        if (fileInput && fileInput.value != "") {
            formData.append("cv_file", fileInput.files[0]);
        }

        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $('.help-block').html('');
        $('.form-group').removeClass('has-error');

        $.ajax({
            url     : form.attr('action'),
            type    : 'POST',
            data    : formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            success : function (json){
                $("#add_cv_modal").html(json.html);
                showCvs();
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
                } else {
                    alert('Error saving CV. Please verify file format (PDF, DOC, DOCX).');
                }
            }
        });
    }

    function showProfileCvEditModal(cv_id){
        loadProfileCvEditForm(cv_id);
    }

    function loadProfileCvEditForm(cv_id){
        $.ajax({
            type: "POST",
            url: "{{ route('get.front.profile.cv.edit.form', $user->id) }}",
            data: {"cv_id": cv_id, "_token": "{{ csrf_token() }}"},
            dataType: 'json',
            success: function (json) {
                $("#add_cv_modal").html(json.html);
                openProfileModal("#add_cv_modal");
            }
        });
    }

    function showCvs() {
        $.post("{{ route('show.front.profile.cvs', $user->id) }}", {user_id: {{$user->id}}, _method: 'POST', _token: '{{ csrf_token() }}'})
            .done(function (response) {
                $('#cvs_div').html(response);
            });
    }

    function delete_profile_cv(id) {
        var msg = "{{__('Are you sure you want to delete this CV?')}}";
        if (confirm(msg)) {
            $.post("{{ route('delete.front.profile.cv') }}", {id: id, _method: 'DELETE', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    if (response == 'ok') {
                        $('#cv_' + id).remove();
                        showCvs();
                    } else {
                        alert('Request Failed!');
                    }
                });
        }
    }
</script>
@endpush
