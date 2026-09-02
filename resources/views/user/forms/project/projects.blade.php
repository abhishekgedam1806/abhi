<div class="profile-sub-section" id="projects">
    <div class="profile-section-header">
        <div class="profile-section-title-wrap">
            <div class="profile-section-icon" style="background:#FAF5FF; color:#8B5CF6;">
                <i class="fa fa-folder-open-o"></i>
            </div>
            <div>
                <h5 onclick="showProjects();" class="profile-section-title" style="cursor:pointer;">{{__('Projects & Portfolio')}}</h5>
                <p class="profile-section-subtitle">Showcase your past projects, portfolio links and freelance work</p>
            </div>
        </div>
        <button type="button" class="btn-add-section" onclick="showProfileProjectModal();">
            <i class="fa fa-plus"></i> {{__('Add Project')}}
        </button>
    </div>

    <div class="row" id="projects_div">
        <div class="col-12 text-center py-3 text-muted" style="font-size:13px;"><i class="fa fa-spinner fa-spin"></i> Loading Projects...</div>
    </div>
</div>

@push('styles')
<link href="{{ asset('/') }}dropzone/dropzone.min.css" rel="stylesheet">
@endpush

@push('scripts') 
<script src="{{ asset('/') }}dropzone/dropzone.min.js"></script> 
<script type="text/javascript">
    function initdatepicker(){
        $(".datepicker").datepicker({
            autoclose: true,
            format:'yyyy-m-d'
        });
    }

    $(document).ready(function(){
        showProjects();
        initdatepicker();
    });

    function createDropZone(){
        var myDropzone = new Dropzone("div#dropzone", {
            url: "{{ route('upload.front.project.temp.image') }}",
            paramName: "image",
            uploadMultiple: false,
            ignoreHiddenFiles: true,
            maxFilesize: <?php echo $upload_max_filesize; ?>,
            acceptedFiles: 'image/*'
        });
        myDropzone.on("complete", function (file) {
            imageUploadedFlag = false;
        });
    }

    function showProfileProjectModal(){
        loadProfileProjectForm();
    }

    function loadProfileProjectForm(){
        $.ajax({
            type: "POST",
            url: "{{ route('get.front.profile.project.form', $user->id) }}",
            data: {"_token": "{{ csrf_token() }}"},
            dataType: 'json',
            success: function (json) {
                $("#add_project_modal").html(json.html);
                createDropZone();
                initdatepicker();
                openProfileModal("#add_project_modal");
            }
        });
    }

    function submitProfileProjectForm() {
        var form = $('#add_edit_profile_project');
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
                $("#add_project_modal").html(json.html);
                showProjects();
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

    function showProfileProjectEditModal(project_id){
        loadProfileProjectEditForm(project_id);
    }

    function loadProfileProjectEditForm(project_id){
        $.ajax({
            type: "POST",
            url: "{{ route('get.front.profile.project.edit.form', $user->id) }}",
            data: {"project_id": project_id, "_token": "{{ csrf_token() }}"},
            dataType: 'json',
            success: function (json) {
                $("#add_project_modal").html(json.html);
                createDropZone();
                initdatepicker();
                openProfileModal("#add_project_modal");
            }
        });
    }

    function showProjects() {
        $.post("{{ route('show.front.profile.projects', $user->id) }}", {user_id: {{$user->id}}, _method: 'POST', _token: '{{ csrf_token() }}'})
            .done(function (response) {
                $('#projects_div').html(response);
            });
    }

    function delete_profile_project(id) {
        var msg = "{{__('Are you sure you want to delete this project?')}}";
        if (confirm(msg)) {
            $.post("{{ route('delete.front.profile.project') }}", {id: id, _method: 'DELETE', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    if (response == 'ok') {
                        $('#project_' + id).remove();
                        showProjects();
                    } else {
                        alert('Request Failed!');
                    }
                });
        }
    }
</script>
@endpush