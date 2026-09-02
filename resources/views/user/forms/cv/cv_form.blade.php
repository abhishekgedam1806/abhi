<div class="modal-body">
    <div class="form-body">
        <div class="form-group mb-3" id="div_title">
            <label for="title" class="font-weight-bold mb-1" style="font-weight:600; color:#1E293B;">{{__('CV Title')}} <span style="font-size:12px; color:#64748B; font-weight:normal;">(Optional - e.g. Updated Resume)</span></label>
            <input class="form-control" id="title" placeholder="{{__('Enter CV title or leave blank to auto-fill')}}" name="title" type="text" value="{{(isset($profileCv)? $profileCv->title:'')}}" style="border-radius:8px; border:1px solid #CBD5E1; padding:10px 14px;">
            <span class="help-block title-error text-danger font-weight-bold" style="font-size:12px; margin-top:4px; display:block;"></span>
        </div>

        @if(isset($profileCv))
        <div class="form-group mb-3 p-2" style="background:#F1F5F9; border-radius:8px;">
            <label class="font-weight-bold mb-1" style="font-weight:600; color:#1E293B;">{{__('Current Attached CV')}}:</label>
            <div>
                <i class="fa fa-file-pdf-o text-danger"></i> {{ImgUploader::print_doc("cvs/$profileCv->cv_file", $profileCv->title, $profileCv->title)}}
            </div>
        </div>
        @endif

        <div class="form-group mb-3" id="div_cv_file">
            <label for="cv_file" class="font-weight-bold mb-1" style="font-weight:600; color:#1E293B;">{{__('Select CV / Resume File')}} <span class="text-danger">*</span> <span style="font-size:12px; color:#64748B; font-weight:normal;">(PDF, DOC, DOCX)</span></label>
            <input name="cv_file" id="cv_file" type="file" accept=".pdf,.doc,.docx" class="form-control" onchange="autoFillCvTitle(this)" style="border-radius:8px; border:1px solid #CBD5E1; padding:8px;" />
            <span class="help-block cv_file-error text-danger font-weight-bold" style="font-size:12px; margin-top:4px; display:block;"></span>
        </div>

        <div class="form-group mb-2" id="div_is_default">
            <label class="font-weight-bold mb-1" style="font-weight:600; color:#1E293B;">{{__('Set as Default CV?')}}</label>
            <div class="d-flex align-items-center gap-3" style="display:flex; gap:16px;">
                @php
                $val_1_checked = 'checked="checked"';
                $val_2_checked = '';

                if (isset($profileCv)) {
                    if ($profileCv->is_default == 1) {
                        $val_1_checked = 'checked="checked"';
                        $val_2_checked = '';
                    } else {
                        $val_1_checked = '';
                        $val_2_checked = 'checked="checked"';
                    }
                }
                @endphp

                <label class="radio-inline mr-3" style="cursor:pointer; display:inline-flex; align-items:center; gap:6px; margin-right:15px;">
                    <input id="default" name="is_default" type="radio" value="1" {{$val_1_checked}}> {{__('Yes')}}
                </label>
                <label class="radio-inline" style="cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
                    <input id="not_default" name="is_default" type="radio" value="0" {{$val_2_checked}}> {{__('No')}}
                </label>
            </div>
            <span class="help-block is_default-error text-danger" style="font-size:12px;"></span>
        </div>
    </div>
</div>

<script>
function autoFillCvTitle(input) {
    if (input.files && input.files[0]) {
        var fileName = input.files[0].name;
        var cleanName = fileName.replace(/\.[^/.]+$/, ""); // strip extension
        var titleInput = document.getElementById('title');
        if (titleInput && (!titleInput.value || titleInput.value.trim() === '')) {
            titleInput.value = cleanName;
        }
    }
}
</script>