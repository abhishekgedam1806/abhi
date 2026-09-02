<div class="profile-sub-section" id="skills">
    <div class="profile-section-header">
        <div class="profile-section-title-wrap">
            <div class="profile-section-icon" style="background:#ECFDF5; color:#03855c;">
                <i class="fa fa-code"></i>
            </div>
            <div>
                <h5 onclick="showSkills();" class="profile-section-title" style="cursor:pointer;">{{__('Skills & Competencies')}}</h5>
                <p class="profile-section-subtitle">Add technical, functional and domain skills</p>
            </div>
        </div>
        <div class="profile-section-actions">
            <button type="button" class="btn-add-section btn-ai-suggest" onclick="loadAISkillRecommendations();" title="{{__('Get AI Skill Suggestions')}}">
                <i class="fa fa-magic"></i> <span>{{__('AI Suggestions')}}</span>
            </button>
            <button type="button" class="btn-add-section btn-add-primary" onclick="showProfileSkillModal();">
                <i class="fa fa-plus"></i> <span>{{__('Add Skill')}}</span>
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div id="skill_div">
                @if(isset($user) && count($user->profileSkills))
                    <div class="profile-skills-chips-wrapper" style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;">
                        @foreach($user->profileSkills as $skill)
                            @php
                                $skillName = $skill->getJobSkill('job_skill');
                            @endphp
                            @if(!empty($skillName))
                                <div class="profile-skill-chip-item" id="skill_{{ $skill->id }}" style="display: inline-flex; align-items: center; gap: 8px; background: #ECFDF5; border: 1.5px solid #10B981; color: #065F46; padding: 7px 16px; border-radius: 9999px; font-size: 13.5px; font-weight: 600; box-shadow: 0 1px 2px rgba(16,185,129,0.08); transition: all 0.2s ease;">
                                    <i class="fa fa-check-circle" style="color: #03855c; font-size: 14px;"></i>
                                    <span>{{ $skillName }}</span>
                                    <a href="javascript:;" onclick="delete_profile_skill({{ $skill->id }});" title="{{ __('Remove Skill') }}" style="color: #03855c; margin-left: 2px; font-size: 15px; font-weight: bold; text-decoration: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; border-radius: 50%;">&times;</a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="profile-empty-state">
                        <div class="profile-empty-state-text"><i class="fa fa-code"></i> {{ __('No skills added yet. Add your key skills to attract recruiters and match top jobs.') }}</div>
                    </div>
                @endif
            </div>

            <!-- AI Skill Suggestions Container -->
            <div id="ai_skill_suggestions_box" style="margin-top: 15px; background: #F8FAFC; border: 1px dashed #93C5FD; border-radius: 12px; padding: 14px 18px; display: none;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                    <div style="font-size: 13px; font-weight: 700; color: #1E40AF; display: flex; align-items: center; gap: 6px;">
                        <i class="fa fa-lightbulb-o" style="color: #F59E0B; font-size: 16px;"></i>
                        <span>AI Recommended Skills for Your Experience</span>
                    </div>
                    <span style="font-size: 11.5px; color: #64748B;">Click <strong>+</strong> to add instantly</span>
                </div>
                <div id="ai_skill_chips_container" style="display: flex; flex-wrap: wrap; gap: 8px;">
                    <!-- Dynamically populated via AJAX -->
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts') 
<script type="text/javascript">
    function loadAISkillRecommendations() {
        var box = $('#ai_skill_suggestions_box');
        var container = $('#ai_skill_chips_container');
        box.slideDown();
        container.html('<div style="color: #2563EB; font-size: 12.5px; padding: 6px;"><i class="fa fa-circle-o-notch fa-spin"></i> AI analyzing profile and searching missing skills...</div>');

        $.ajax({
            url: "{{ route('candidate.ai.recommend_skills') }}",
            type: "POST",
            data: {"_token": "{{ csrf_token() }}"},
            dataType: 'json',
            success: function(res) {
                if (res.success && res.skills && res.skills.length > 0) {
                    var html = '';
                    $.each(res.skills, function(index, skill) {
                        html += '<button type="button" onclick="addAISkill(this, \'' + skill.replace(/'/g, "\\'") + '\');" style="display: inline-flex; align-items: center; gap: 6px; background: #FFFFFF; border: 1.5px solid #CBD5E1; color: #334155; font-size: 12.5px; font-weight: 600; padding: 5px 12px; border-radius: 9999px; cursor: pointer; transition: all 0.15s ease;" onmouseover="this.style.borderColor=\'#2563EB\'; this.style.color=\'#2563EB\';" onmouseout="this.style.borderColor=\'#CBD5E1\'; this.style.color=\'#334155\';">' +
                                '<i class="fa fa-plus" style="font-size: 10px; color: #2563EB;"></i> ' + skill +
                                '</button>';
                    });
                    container.html(html);
                } else {
                    container.html('<div style="font-size: 12px; color: #64748B;">All top recommended skills are already in your profile!</div>');
                }
            },
            error: function() {
                container.html('<div style="font-size: 12px; color: #DC2626;">Could not fetch recommendations right now. Please try again.</div>');
            }
        });
    }

    function addAISkill(btnElement, skillName) {
        var btn = $(btnElement);
        btn.prop('disabled', true).html('<i class="fa fa-circle-o-notch fa-spin"></i> Adding...');

        $.ajax({
            url: "{{ route('candidate.ai.add_skill') }}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                "skill_name": skillName
            },
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    btn.fadeOut(300, function(){ $(this).remove(); });
                    showSkills();
                } else {
                    alert(res.message || 'Error adding skill');
                    btn.prop('disabled', false).html('<i class="fa fa-plus"></i> ' + skillName);
                }
            },
            error: function() {
                alert('Request failed. Please try again.');
                btn.prop('disabled', false).html('<i class="fa fa-plus"></i> ' + skillName);
            }
        });
    }

    function showProfileSkillModal(){
        loadProfileSkillForm();
    }

    function loadProfileSkillForm(){
        $.ajax({
            type: "POST",
            url: "{{ route('get.front.profile.skill.form', $user->id) }}",
            data: {"_token": "{{ csrf_token() }}"},
            dataType: 'json',
            success: function (json) {
                $("#add_skill_modal").html(json.html);
                openProfileModal("#add_skill_modal");
            }
        });
    }

    function showProfileSkillEditModal(skill_id){
        loadProfileSkillEditForm(skill_id);
    }

    function loadProfileSkillEditForm(skill_id){
        $.ajax({
            type: "POST",
            url: "{{ route('get.front.profile.skill.edit.form', $user->id) }}",
            data: {"skill_id": skill_id, "_token": "{{ csrf_token() }}"},
            dataType: 'json',
            success: function (json) {
                $("#add_skill_modal").html(json.html);
                openProfileModal("#add_skill_modal");
            }
        });
    }

    function submitProfileSkillForm() {
        var form = $('#add_edit_profile_skill');
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
                $("#add_skill_modal").html(json.html);
                showSkills();
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

    function delete_profile_skill(id) {
        var msg = "{{__('Are you sure you want to delete this skill?')}}";
        if (confirm(msg)) {
            $.post("{{ route('delete.front.profile.skill') }}", {id: id, _method: 'DELETE', _token: '{{ csrf_token() }}'})
                .done(function (response) {
                    if (response == 'ok') {
                        $('#skill_' + id).remove();
                        showSkills();
                    } else {
                        alert('Request Failed!');
                    }
                });
        }
    }

    $(document).ready(function(){
        showSkills();
    });

    function showSkills() {
        $.post("{{ route('show.front.profile.skills', $user->id) }}", {user_id: {{$user->id}}, _method: 'POST', _token: '{{ csrf_token() }}'})
            .done(function (response) {
                $('#skill_div').html(response);
            });
    }
</script> 
@endpush