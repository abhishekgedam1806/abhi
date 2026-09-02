<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content custom-profile-modal">
        <form class="form" id="add_edit_profile_cv" method="POST" action="{{ route('update.front.profile.cv', [$profileCv->id, $user->id]) }}" enctype="multipart/form-data">
            {{csrf_field()}}
            <input type="hidden" name="id" id="id" value="{{$profileCv->id}}"/>
            <div class="modal-header">
                <h4 class="modal-title">{{__('Edit CV / Resume')}}</h4>
                <button type="button" class="btn-modal-close" data-dismiss="modal" onclick="closeActiveModal()">&times;</button>
            </div>
            @include('user.forms.cv.cv_edit_form')
            <div class="modal-footer" style="padding: 14px 24px; border-top: 1px solid #F1F5F9; background: #FFFFFF; display: flex; justify-content: flex-end; align-items: center; gap: 12px;">
                <button type="button" class="btn-modal-cancel" onclick="closeActiveModal()">{{__('Cancel')}}</button>
                <button type="button" class="btn-modal-save" onclick="submitProfileCvForm();">
                    <i class="fa fa-cloud-upload"></i> {{__('Save & Upload CV')}}
                </button>
            </div>
        </form>
    </div>
</div>