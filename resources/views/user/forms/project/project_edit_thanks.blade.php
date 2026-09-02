<div class="modal-dialog modal-dialog-centered" style="max-width:440px; margin:auto;"> 
    <div class="modal-content custom-profile-modal">
        <div class="modal-header">
            <h4 class="modal-title">{{__('Update Project')}}</h4>
            <button type="button" class="btn-modal-close" data-dismiss="modal" onclick="closeActiveModal()">&times;</button>
        </div>
        <div class="modal-body text-center py-4" style="padding:28px 20px 24px;">
            <div style="width:56px; height:56px; border-radius:50%; background:#ECFDF5; color:#03855c; display:inline-flex; align-items:center; justify-content:center; font-size:26px; margin-bottom:14px;">
                <i class="fa fa-check"></i>
            </div>
            <h4 style="font-size:18px; font-weight:700; color:#0F172A; margin:0 0 6px 0;">{{__('Project Updated Successfully')}}</h4>
            <p style="font-size:13.5px; color:#64748B; margin:0;">Your portfolio project changes have been saved.</p>
        </div>
        <div class="modal-footer" style="padding:12px 20px;">
            <button type="button" class="btn-modal-save" data-dismiss="modal" onclick="closeActiveModal()">{{__('Close')}}</button>
        </div>
    </div>
</div>
