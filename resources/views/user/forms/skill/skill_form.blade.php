<div class="modal-body" style="padding: 24px;">
    <div class="form-body">
        <!-- Department Selector -->
        <div class="formrow mb-3" id="div_functional_area_id">
            <label for="department_select" style="font-weight: 700; color: #1E293B; font-size: 14px; margin-bottom: 6px; display: block;">
                {{__('Select Department / Functional Area')}} <span class="text-danger">*</span>
            </label>
            <select name="functional_area_id" id="department_select" class="form-control" style="border: 1.5px solid #CBD5E1; border-radius: 10px; height: 44px; font-weight: 600; color: #0F172A; font-size: 14px;" onchange="onDepartmentChanged(this.value)">
                @foreach($functionalAreas as $fa)
                    <option value="{{ $fa->functional_area_id }}" {{ ($activeDepartmentId == $fa->functional_area_id) ? 'selected="selected"' : '' }}>
                        {{ $fa->functional_area }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Reference Design Header -->
        <div class="skills-header-box" style="margin-top: 18px; margin-bottom: 12px;">
            <h4 style="font-size: 18px; font-weight: 800; color: #0F172A; margin: 0 0 4px 0; letter-spacing: -0.2px;">
                {{__('What skills do you have?')}}
            </h4>
            <p style="font-size: 13.5px; color: #64748B; margin: 0;">
                {{__('Get noticed for the right job by adding your skills')}}
            </p>
        </div>

        <!-- Professional Search Skills Input with Live Dropdown & Manual Add -->
        <div class="skill-search-wrapper" style="position: relative; margin-bottom: 16px;">
            <i class="fa fa-search skill-search-icon"></i>
            <input type="text" id="skill_search_input" class="form-control" placeholder="{{__('Search Skills')}}" autocomplete="off"
                   onkeydown="handleSearchKeydown(event)"
                   oninput="onSearchInputChanged(this.value)"
                   onfocus="onSearchInputFocused()">
            <button type="button" id="clear_search_btn" onclick="clearSkillSearch()" title="{{__('Clear')}}">&times;</button>

            <!-- Floating Autocomplete Suggestions Dropdown -->
            <div id="skills_suggestions_dropdown" class="skills-suggestions-dropdown" 
                 style="display: none; position: absolute; top: calc(100% + 4px); left: 0; right: 0; background: #FFFFFF; border: 1.5px solid #03855c; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.12); max-height: 240px; overflow-y: auto; z-index: 1050;">
                <!-- Suggestions rendered dynamically -->
            </div>
        </div>

        <!-- Selected Skills Section (Matching Reference Design Chips) -->
        <div class="selected-skills-section" style="margin-bottom: 18px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <label style="font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">
                    {{__('Selected Skills')}} (<span id="selected_skills_count">{{ count($userSkillIds) }}</span>)
                </label>
                <span id="selected_counter_badge" class="badge-chip-counter" style="display: {{ count($userSkillIds) > 0 ? 'inline-block' : 'none' }}; background: #F1F5F9; color: #334155; font-size: 12px; font-weight: 700; padding: 3px 10px; border-radius: 20px; border: 1px solid #CBD5E1;">
                    + <span id="counter_number">{{ count($userSkillIds) }}</span>
                </span>
            </div>
            <div id="selected_chips_container" style="display: flex; flex-wrap: wrap; gap: 8px; min-height: 44px; padding: 8px 12px; background: #F8FAFC; border: 1.5px dashed #CBD5E1; border-radius: 12px; align-items: center;">
                @if(isset($userSkills) && count($userSkills))
                    @foreach($userSkills as $uskill)
                        <div class="skill-chip-selected" id="selected_chip_{{ $uskill->job_skill_id }}" data-id="{{ $uskill->job_skill_id }}" data-name="{{ $uskill->job_skill }}" style="display: inline-flex; align-items: center; gap: 6px; background: #FFFFFF; border: 1.5px solid #03855c; color: #065F46; padding: 6px 14px; border-radius: 9999px; font-size: 13.5px; font-weight: 600; cursor: pointer; box-shadow: 0 1px 3px rgba(3,133,92,0.1); transition: all 0.15s ease;">
                            <span>{{ $uskill->job_skill }}</span>
                            <span class="chip-remove-btn" onclick="removeSkillChip({{ $uskill->job_skill_id }})" style="font-size: 16px; font-weight: bold; margin-left: 2px; color: #03855c; line-height: 1;">&times;</span>
                        </div>
                    @endforeach
                @else
                    <div id="empty_selected_notice" style="font-size: 13px; color: #94A3B8; font-style: italic;">
                        {{__('No skills selected yet. Search above or click suggestions below.')}}
                    </div>
                @endif
            </div>
        </div>

        <!-- Available Department Skills (Selectable Library) -->
        <div class="available-skills-section">
            <label style="font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; display: block;">
                {{__('Available Skills in this Department')}}
            </label>
            <div id="available_skills_cloud" style="display: flex; flex-wrap: wrap; gap: 8px; max-height: 180px; overflow-y: auto; padding: 4px; border-radius: 8px;">
                @foreach($departmentSkills as $dskill)
                    @php
                        $isSelected = in_array($dskill->id, $userSkillIds);
                    @endphp
                    <div class="skill-chip-available {{ $isSelected ? 'is-selected' : '' }}" 
                         id="avail_skill_{{ $dskill->id }}" 
                         data-id="{{ $dskill->id }}" 
                         data-name="{{ $dskill->name }}"
                         onclick="addSkillChip({{ $dskill->id }}, '{{ addslashes($dskill->name) }}')"
                         style="display: {{ $isSelected ? 'none' : 'inline-flex' }}; align-items: center; gap: 6px; background: #F1F5F9; border: 1.5px solid #E2E8F0; color: #334155; padding: 6px 14px; border-radius: 9999px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.15s ease;">
                        <i class="fa fa-plus" style="font-size: 10px; color: #64748B;"></i>
                        <span class="skill-name-text">{{ $dskill->name }}</span>
                    </div>
                @endforeach
            </div>
            <div id="no_skills_match" style="display: none; padding: 15px; text-align: center; color: #64748B; font-size: 13px;">
                {{__('No matching skills found in this department.')}}
            </div>
        </div>

        <!-- Hidden input container for form submission -->
        <div id="hidden_skill_inputs">
            @foreach($userSkillIds as $sId)
                <input type="hidden" name="skill_ids[]" value="{{ $sId }}" id="input_skill_{{ $sId }}">
            @endforeach
        </div>

        <span class="help-block skill_ids-error text-danger" style="display: block; margin-top: 8px; font-size: 13px;"></span>
    </div>
</div>

<style>
.skill-search-wrapper {
    position: relative !important;
    margin-bottom: 16px !important;
    width: 100% !important;
}
.skill-search-wrapper .skill-search-icon {
    position: absolute !important;
    left: 15px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    color: #94A3B8 !important;
    font-size: 15px !important;
    z-index: 5 !important;
    pointer-events: none !important;
    margin: 0 !important;
    padding: 0 !important;
}
.skill-search-wrapper #skill_search_input {
    padding-left: 44px !important;
    padding-right: 38px !important;
    height: 48px !important;
    border: 1.5px solid #CBD5E1 !important;
    border-radius: 10px !important;
    font-size: 14.5px !important;
    width: 100% !important;
    background: #FFFFFF !important;
    box-shadow: 0 1px 2px rgba(0,0,0,0.03) !important;
}
.skill-search-wrapper #skill_search_input:focus {
    border-color: #03855c !important;
    box-shadow: 0 0 0 3px rgba(3, 133, 92, 0.15) !important;
    outline: none !important;
}
.skill-search-wrapper #clear_search_btn {
    position: absolute !important;
    right: 14px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    border: none !important;
    background: transparent !important;
    color: #94A3B8 !important;
    font-size: 20px !important;
    line-height: 1 !important;
    cursor: pointer !important;
    z-index: 5 !important;
    display: none;
}
.skills-suggestions-dropdown .suggestion-item {
    display: flex;
    align-items: center;
    padding: 11px 16px;
    font-size: 14px;
    color: #1E293B;
    cursor: pointer;
    border-bottom: 1px solid #F1F5F9;
    transition: all 0.15s ease;
}
.skills-suggestions-dropdown .suggestion-item:last-child {
    border-bottom: none;
}
.skills-suggestions-dropdown .suggestion-item:hover,
.skills-suggestions-dropdown .suggestion-item.active {
    background: #ECFDF5;
    color: #065F46;
}
.skills-suggestions-dropdown .suggestion-item.add-custom-item {
    background: #F8FAFC;
    color: #0F172A;
    border-top: 1px solid #E2E8F0;
    font-weight: 600;
}
.skills-suggestions-dropdown .suggestion-item.add-custom-item:hover {
    background: #ECFDF5;
    color: #03855c;
}
.skill-chip-selected:hover {
    background: #F0FDF4 !important;
    border-color: #047857 !important;
    transform: translateY(-1px);
}
.skill-chip-available:hover {
    background: #E2E8F0 !important;
    border-color: #03855c !important;
    color: #065F46 !important;
    transform: translateY(-1px);
}
.skill-chip-available:hover i {
    color: #03855c !important;
}
#available_skills_cloud::-webkit-scrollbar,
#skills_suggestions_dropdown::-webkit-scrollbar {
    width: 6px;
}
#available_skills_cloud::-webkit-scrollbar-thumb,
#skills_suggestions_dropdown::-webkit-scrollbar-thumb {
    background: #CBD5E1;
    border-radius: 10px;
}
</style>

<script type="text/javascript">
    var currentDepartmentSkills = <?php echo json_encode($departmentSkills); ?>;

    // Department Change Handler
    function onDepartmentChanged(deptId) {
        if (!deptId) return;
        
        var cloud = $('#available_skills_cloud');
        cloud.html('<div style="padding: 10px; color: #64748B; font-size: 13px;"><i class="fa fa-spinner fa-spin"></i> Loading department skills...</div>');
        
        $.ajax({
            type: "GET",
            url: "{{ route('get.skills.by.department') }}",
            data: { functional_area_id: deptId },
            dataType: "json",
            success: function(res) {
                cloud.empty();
                currentDepartmentSkills = res.skills || [];
                if (currentDepartmentSkills.length > 0) {
                    $('#no_skills_match').hide();
                    $.each(currentDepartmentSkills, function(idx, item) {
                        var isSelected = $('#input_skill_' + item.id).length > 0;
                        var displayStyle = isSelected ? 'none' : 'inline-flex';
                        var chipHtml = '<div class="skill-chip-available ' + (isSelected ? 'is-selected' : '') + '" ' +
                            'id="avail_skill_' + item.id + '" ' +
                            'data-id="' + item.id + '" ' +
                            'data-name="' + item.name + '" ' +
                            'onclick="addSkillChip(' + item.id + ', \'' + item.name.replace(/'/g, "\\'") + '\')" ' +
                            'style="display: ' + displayStyle + '; align-items: center; gap: 6px; background: #F1F5F9; border: 1.5px solid #E2E8F0; color: #334155; padding: 6px 14px; border-radius: 9999px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.15s ease;">' +
                            '<i class="fa fa-plus" style="font-size: 10px; color: #64748B;"></i> ' +
                            '<span class="skill-name-text">' + item.name + '</span>' +
                            '</div>';
                        cloud.append(chipHtml);
                    });
                } else {
                    $('#no_skills_match').show();
                }

                // If user currently has search text typed, update suggestions
                var term = $('#skill_search_input').val();
                if (term) {
                    renderSuggestionsDropdown(term);
                }
            }
        });
    }

    // Input changed handler for search bar
    function onSearchInputChanged(term) {
        var query = term.trim();
        if (query.length > 0) {
            $('#clear_search_btn').show();
            renderSuggestionsDropdown(query);
        } else {
            $('#clear_search_btn').hide();
            $('#skills_suggestions_dropdown').hide().empty();
        }
        filterSkillsList(query);
    }

    function onSearchInputFocused() {
        var query = $('#skill_search_input').val().trim();
        if (query.length > 0) {
            renderSuggestionsDropdown(query);
        }
    }

    // Clear Search Input
    function clearSkillSearch() {
        $('#skill_search_input').val('').focus();
        $('#clear_search_btn').hide();
        $('#skills_suggestions_dropdown').hide().empty();
        filterSkillsList('');
    }

    // Render Floating Suggestions Dropdown with Autocomplete & Manual Add Option
    function renderSuggestionsDropdown(query) {
        var dropdown = $('#skills_suggestions_dropdown');
        dropdown.empty();
        var qLower = query.toLowerCase();
        var matched = [];
        var exactMatch = false;

        // Search in department skills list
        if (currentDepartmentSkills && currentDepartmentSkills.length > 0) {
            $.each(currentDepartmentSkills, function(idx, item) {
                var nameLower = item.name.toLowerCase();
                if (nameLower === qLower) {
                    exactMatch = true;
                }
                if (nameLower.indexOf(qLower) !== -1) {
                    matched.push(item);
                }
            });
        }

        // Render matching suggestions
        if (matched.length > 0) {
            $.each(matched, function(idx, item) {
                var isSelected = $('#input_skill_' + item.id).length > 0;
                var safeName = item.name.replace(/'/g, "\\'");
                var iconHtml = isSelected ? '<i class="fa fa-check text-success" style="margin-right: 8px;"></i>' : '<i class="fa fa-plus text-muted" style="margin-right: 8px;"></i>';
                var itemHtml = '<div class="suggestion-item ' + (isSelected ? 'already-selected' : '') + '" onclick="selectSkillFromSuggestion(' + item.id + ', \'' + safeName + '\')">' +
                    iconHtml +
                    '<span>' + item.name + '</span>' +
                    (isSelected ? ' <span style="font-size:11px; color:#03855c; margin-left:auto;">(' + "{{__('Added')}}" + ')</span>' : '') +
                    '</div>';
                dropdown.append(itemHtml);
            });
        }

        // Always show the "+ Add: query" manual option if not exact match or if custom skill
        if (!exactMatch && query.length > 0) {
            var safeQuery = query.replace(/'/g, "\\'");
            var customItemHtml = '<div class="suggestion-item add-custom-item" onclick="createAndSelectCustomSkill(\'' + safeQuery + '\')">' +
                '<i class="fa fa-plus-circle" style="color: #03855c; font-size: 15px; margin-right: 8px;"></i> ' +
                '<span><strong>+ Add:</strong> "' + query + '"</span>' +
                '</div>';
            dropdown.append(customItemHtml);
        }

        dropdown.show();
    }

    // Keydown handler (Prevent form submit on Enter, select top item)
    function handleSearchKeydown(e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            e.preventDefault();
            var firstItem = $('#skills_suggestions_dropdown .suggestion-item:first');
            if (firstItem.length > 0) {
                firstItem.trigger('click');
            } else {
                var term = $('#skill_search_input').val().trim();
                if (term) {
                    createAndSelectCustomSkill(term);
                }
            }
            return false;
        } else if (e.key === 'Escape' || e.keyCode === 27) {
            $('#skills_suggestions_dropdown').hide();
        }
    }

    // Select Skill from Dropdown
    function selectSkillFromSuggestion(skillId, skillName) {
        addSkillChip(skillId, skillName);
        clearSkillSearch();
    }

    // Create & Select Custom Skill Manually
    function createAndSelectCustomSkill(skillName) {
        var deptId = $('#department_select').val();
        var searchInput = $('#skill_search_input');
        searchInput.prop('disabled', true);

        $.ajax({
            type: "POST",
            url: "{{ route('add.custom.skill') }}",
            data: {
                skill_name: skillName,
                functional_area_id: deptId,
                _token: "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(res) {
                searchInput.prop('disabled', false);
                if (res.success && res.skill) {
                    addSkillChip(res.skill.id, res.skill.name);
                    
                    // Add to currentDepartmentSkills array if new
                    if (res.is_new) {
                        currentDepartmentSkills.push({ id: res.skill.id, name: res.skill.name });
                    }
                    clearSkillSearch();
                }
            },
            error: function() {
                searchInput.prop('disabled', false);
                alert("Error adding custom skill. Please try again.");
            }
        });
    }

    // Add Skill to Selected List
    function addSkillChip(skillId, skillName) {
        if ($('#input_skill_' + skillId).length > 0) return; // already added

        $('#empty_selected_notice').hide();
        $('.skill_ids-error').empty();

        // Add to selected UI container
        var chipHtml = '<div class="skill-chip-selected" id="selected_chip_' + skillId + '" data-id="' + skillId + '" data-name="' + skillName + '" style="display: inline-flex; align-items: center; gap: 6px; background: #FFFFFF; border: 1.5px solid #03855c; color: #065F46; padding: 6px 14px; border-radius: 9999px; font-size: 13.5px; font-weight: 600; cursor: pointer; box-shadow: 0 1px 3px rgba(3,133,92,0.1); transition: all 0.15s ease;">' +
            '<span>' + skillName + '</span>' +
            '<span class="chip-remove-btn" onclick="removeSkillChip(' + skillId + ')" style="font-size: 16px; font-weight: bold; margin-left: 2px; color: #03855c; line-height: 1;">&times;</span>' +
            '</div>';
        $('#selected_chips_container').append(chipHtml);

        // Add hidden input
        $('#hidden_skill_inputs').append('<input type="hidden" name="skill_ids[]" value="' + skillId + '" id="input_skill_' + skillId + '">');

        // Hide from available cloud
        $('#avail_skill_' + skillId).addClass('is-selected').hide();

        updateCounters();
    }

    // Remove Skill from Selected List
    function removeSkillChip(skillId) {
        $('#selected_chip_' + skillId).remove();
        $('#input_skill_' + skillId).remove();

        // Show back in available cloud if matches current department
        var availChip = $('#avail_skill_' + skillId);
        if (availChip.length > 0) {
            availChip.removeClass('is-selected').css('display', 'inline-flex');
        }

        if ($('#hidden_skill_inputs input').length === 0) {
            $('#empty_selected_notice').show();
        }

        updateCounters();
    }

    // Filter Skills Search in Available Cloud
    function filterSkillsList(term) {
        var query = term.toLowerCase().trim();
        var visibleCount = 0;

        $('#available_skills_cloud .skill-chip-available').each(function() {
            var skillName = $(this).attr('data-name').toLowerCase();
            var isSelected = $(this).hasClass('is-selected');

            if (!isSelected && (query === '' || skillName.indexOf(query) !== -1)) {
                $(this).css('display', 'inline-flex');
                visibleCount++;
            } else {
                $(this).hide();
            }
        });

        if (visibleCount === 0 && query !== '') {
            $('#no_skills_match').show();
        } else {
            $('#no_skills_match').hide();
        }
    }

    // Counter Helper
    function updateCounters() {
        var count = $('#hidden_skill_inputs input').length;
        $('#selected_skills_count').text(count);
        $('#counter_number').text(count);
        if (count > 0) {
            $('#selected_counter_badge').show();
        } else {
            $('#selected_counter_badge').hide();
        }
    }

    // Close suggestions dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.skill-search-wrapper').length) {
            $('#skills_suggestions_dropdown').hide();
        }
    });
</script>
