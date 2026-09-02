<div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content custom-profile-modal">
        <form class="form" id="add_edit_profile_skill" method="POST" action="{{ route('store.front.profile.skill', [$user->id]) }}" onsubmit="event.preventDefault(); submitProfileSkillForm(); return false;">
            {{ csrf_field() }}
            <div class="modal-header">
                <h4 class="modal-title">{{__('Edit skills')}}</h4>
                <button type="button" class="btn-modal-close" data-dismiss="modal" onclick="closeActiveModal()">&times;</button>
            </div>
            @include('user.forms.skill.skill_form')
            <div class="modal-footer" style="padding: 14px 24px; border-top: 1px solid #F1F5F9; background: #FFFFFF; display: flex; justify-content: flex-end; align-items: center; gap: 12px;">
                <button type="button" class="btn-modal-cancel" onclick="closeActiveModal()">{{__('Cancel')}}</button>
                <button type="button" class="btn-modal-save" onclick="submitProfileSkillForm();">
                    <i class="fa fa-check"></i> {{__('Save Skills')}}
                </button>
            </div>
        </form>
    </div>
</div>