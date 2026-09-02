@php
$selected_level = isset($profileEducation) ? $profileEducation->degree_level_id : 9;
$selected_type = isset($profileEducation) ? $profileEducation->degree_type_id : null;
$selected_year = isset($profileEducation) ? $profileEducation->date_completion : null;
$current_institution = isset($profileEducation) ? $profileEducation->institution : '';
@endphp

<div class="modal-body" style="padding: 24px 24px 16px;">
    <div class="form-body">
        
        {{-- 1. Education Level Quick Selector Chips --}}
        <div class="form-group mb-4" id="div_degree_level_id">
            <label class="font-weight-bold mb-2" style="font-size:14px; font-weight:700; color:#0F172A; display:block;">
                {{__('Your current or highest completed level of education')}}
            </label>
            
            <div class="edu-chips-container" style="display:flex; flex-wrap:wrap; gap:8px; margin-top:6px;">
                <span class="edu-level-chip {{ ($selected_level == 9) ? 'active' : '' }}" onclick="selectEduLevel(9, this)">
                    Diploma
                </span>
                <span class="edu-level-chip {{ ($selected_level == 8) ? 'active' : '' }}" onclick="selectEduLevel(8, this)">
                    ITI
                </span>
                <span class="edu-level-chip {{ ($selected_level == 4) ? 'active' : '' }}" onclick="selectEduLevel(4, this)">
                    Graduate
                </span>
                <span class="edu-level-chip {{ ($selected_level == 5) ? 'active' : '' }}" onclick="selectEduLevel(5, this)">
                    Post Graduate
                </span>
                <span class="edu-level-chip {{ ($selected_level == 3) ? 'active' : '' }}" onclick="selectEduLevel(3, this)">
                    12th Pass
                </span>
                <span class="edu-level-chip {{ ($selected_level == 2) ? 'active' : '' }}" onclick="selectEduLevel(2, this)">
                    10th Pass
                </span>
            </div>

            <select name="degree_level_id" id="degree_level_id" class="form-control" style="display:none;">
                @foreach($degreeLevels as $lvlId => $lvlName)
                    <option value="{{$lvlId}}" {{ ($selected_level == $lvlId) ? 'selected' : '' }}>{{$lvlName}}</option>
                @endforeach
            </select>
            <span class="help-block degree_level_id-error text-danger font-weight-bold" style="font-size:12px;"></span>
        </div>

        {{-- 2. College Name with Smart Search + Manual Add Option (Matching User Screenshot) --}}
        <div class="form-group mb-3 position-relative" id="div_institution" style="position:relative;">
            <label for="institution" style="font-size:13.5px; font-weight:700; color:#334155; margin-bottom:6px; display:block;">
                {{__('College Name')}} <span class="text-danger">*</span>
            </label>
            
            <div class="college-input-wrapper" style="position:relative;">
                <input class="form-control modern-input college-search-input" id="institution" placeholder="Search or type college name (e.g. St. Stephens, G H Raisoni)" name="institution" type="text" autocomplete="off" value="{{$current_institution}}" style="padding-right: 36px;">
                <button type="button" class="btn-clear-college" onclick="clearCollegeInput()" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; color:#94A3B8; font-size:18px; line-height:1; cursor:pointer; padding:4px; display:{{$current_institution ? 'block' : 'none'}};" title="Clear">&times;</button>
            </div>
            
            {{-- Autocomplete Dropdown List --}}
            <div id="college_suggestions_box" class="college-suggestions-dropdown" style="display:none; position:absolute; top:100%; left:0; right:0; background:#FFFFFF; border:1.5px solid #CBD5E1; border-radius:8px; box-shadow:0 12px 28px rgba(0,0,0,0.12); max-height:230px; overflow-y:auto; z-index:1055; margin-top:4px;">
            </div>

            <span class="help-block institution-error text-danger font-weight-bold" style="font-size:12px;"></span>
        </div>

        {{-- 3. Degree (Searchable Dropdown) --}}
        <div class="form-group mb-3" id="div_degree_type_id">
            <label for="degree_type_id" style="font-size:13.5px; font-weight:700; color:#334155; margin-bottom:6px; display:block;">
                {{__('Degree')}}
            </label>
            <div id="degree_types_dd">
                <select name="degree_type_id" id="degree_type_id" class="form-control modern-input select2-degree">
                    <option value="">{{__('Select an option')}}</option>
                    @if(isset($degreeTypes) && count($degreeTypes))
                        @foreach($degreeTypes as $dId => $dName)
                            <option value="{{$dId}}" {{ ($selected_type == $dId) ? 'selected' : '' }}>{{$dName}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <input type="text" class="form-control modern-input mt-2" id="degree_title" name="degree_title" placeholder="{{__('Or enter custom degree title (e.g. B.Tech Computer Science)')}}" value="{{(isset($profileEducation)? $profileEducation->degree_title:'')}}" style="display:none;">
            <span class="help-block degree_type_id-error text-danger font-weight-bold" style="font-size:12px;"></span>
            <span class="help-block degree_title-error text-danger font-weight-bold" style="font-size:12px;"></span>
        </div>

        {{-- 4. Specialisation --}}
        <div class="form-group mb-3" id="div_major_subjects">
            <label for="major_subjects" style="font-size:13.5px; font-weight:700; color:#334155; margin-bottom:6px; display:block;">
                {{__('Specialisation')}}
            </label>
            @php
            $profileEducationMajorSubjectIds = old('major_subjects', isset($profileEducationMajorSubjectIds) ? $profileEducationMajorSubjectIds : []);
            @endphp
            {!! Form::select('major_subjects[]', $majorSubjects, $profileEducationMajorSubjectIds, array('class'=>'form-control modern-input select2-multiple', 'id'=>'major_subjects', 'multiple'=>'multiple', 'data-placeholder'=>'Select an option')) !!}
            <span class="help-block major_subjects-error text-danger font-weight-bold" style="font-size:12px;"></span>
        </div>

        {{-- 5. Start Date & End Date --}}
        <div class="row">
            <div class="col-md-6 col-12 mb-3">
                <label style="font-size:13.5px; font-weight:700; color:#334155; margin-bottom:6px; display:block;">
                    {{__('Start date')}}
                </label>
                <div class="d-flex gap-2" style="display:flex; gap:8px;">
                    <select class="form-control modern-input" id="start_month" name="start_month">
                        <option value="">Month</option>
                        <option value="01">Jan</option>
                        <option value="02">Feb</option>
                        <option value="03">Mar</option>
                        <option value="04">Apr</option>
                        <option value="05">May</option>
                        <option value="06">Jun</option>
                        <option value="07">Jul</option>
                        <option value="08">Aug</option>
                        <option value="09">Sep</option>
                        <option value="10">Oct</option>
                        <option value="11">Nov</option>
                        <option value="12">Dec</option>
                    </select>
                    <select class="form-control modern-input" id="start_year" name="start_year">
                        <option value="">Year</option>
                        @for($y = (int)date('Y'); $y >= 1975; $y--)
                            <option value="{{$y}}">{{$y}}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="col-md-6 col-12 mb-3">
                <label style="font-size:13.5px; font-weight:700; color:#334155; margin-bottom:6px; display:block;">
                    {{__('End date (or expected)')}}
                </label>
                <div class="d-flex gap-2" style="display:flex; gap:8px;">
                    <select class="form-control modern-input" id="end_month" name="end_month">
                        <option value="">Month</option>
                        <option value="01">Jan</option>
                        <option value="02">Feb</option>
                        <option value="03">Mar</option>
                        <option value="04">Apr</option>
                        <option value="05">May</option>
                        <option value="06">Jun</option>
                        <option value="07">Jul</option>
                        <option value="08">Aug</option>
                        <option value="09">Sep</option>
                        <option value="10">Oct</option>
                        <option value="11">Nov</option>
                        <option value="12">Dec</option>
                    </select>
                    <select class="form-control modern-input" id="date_completion" name="date_completion">
                        <option value="">Year</option>
                        @for($y = (int)date('Y') + 6; $y >= 1975; $y--)
                            <option value="{{$y}}" {{ ($selected_year == $y) ? 'selected' : '' }}>{{$y}}</option>
                        @endfor
                    </select>
                </div>
                <span class="help-block date_completion-error text-danger font-weight-bold" style="font-size:12px;"></span>
            </div>
        </div>

    </div>
</div>

<style>
/* Modern Chips Styling */
.edu-level-chip {
    background: #FFFFFF !important;
    border: 1.5px solid #CBD5E1 !important;
    color: #334155 !important;
    border-radius: 50px !important;
    padding: 6px 18px !important;
    font-size: 13.5px !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    outline: none !important;
}

.edu-level-chip:hover {
    border-color: #03855c !important;
    color: #03855c !important;
    background: #F0FDF4 !important;
}

.edu-level-chip.active {
    background: #ECFDF5 !important;
    border-color: #03855c !important;
    color: #03855c !important;
    font-weight: 700 !important;
    box-shadow: 0 1px 3px rgba(3, 133, 92, 0.15) !important;
}

/* Clean Input Styling */
.modern-input {
    border: 1.5px solid #CBD5E1 !important;
    border-radius: 8px !important;
    padding: 10px 14px !important;
    font-size: 14px !important;
    color: #0F172A !important;
    background: #FFFFFF !important;
    width: 100% !important;
    transition: all 0.15s ease !important;
}

.modern-input:focus {
    border-color: #03855c !important;
    box-shadow: 0 0 0 3px rgba(3, 133, 92, 0.12) !important;
    outline: none !important;
}

/* College Autocomplete Dropdown List */
.college-suggestions-dropdown {
    scrollbar-width: thin;
    scrollbar-color: #CBD5E1 #F8FAFC;
}

.college-item-row {
    padding: 10px 14px !important;
    font-size: 13.5px !important;
    color: #1E293B !important;
    cursor: pointer !important;
    border-bottom: 1px solid #F1F5F9 !important;
    transition: background 0.1s ease !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
}

.college-item-row:hover {
    background: #F1F5F9 !important;
    color: #03855c !important;
}

.college-item-manual {
    padding: 11px 14px !important;
    font-size: 13.5px !important;
    color: #03855c !important;
    font-weight: 700 !important;
    background: #F0FDF4 !important;
    cursor: pointer !important;
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
}

.college-item-manual:hover {
    background: #DCFCE7 !important;
    text-decoration: underline !important;
}
</style>

<script>
var collegeSearchTimeout = null;

function selectEduLevel(levelId, btn) {
    $('.edu-level-chip').removeClass('active');
    $(btn).addClass('active');
    $('#degree_level_id').val(levelId).trigger('change');
    filterDegreeTypes(0);
}

function clearCollegeInput() {
    $('#institution').val('').focus();
    $('.btn-clear-college').hide();
    $('#college_suggestions_box').hide().html('');
}

function selectCollegeName(name) {
    $('#institution').val(name);
    $('.btn-clear-college').show();
    $('#college_suggestions_box').hide().html('');
}

$(document).ready(function() {
    if (typeof $.fn.select2 === 'function' && $('.select2-multiple').length > 0) {
        $('.select2-multiple').select2({
            placeholder: "Select an option",
            allowClear: true
        });
    }

    // College search autocomplete typing listener
    $(document).on('input focus', '#institution', function() {
        var q = $(this).val().trim();
        if (q.length > 0) {
            $('.btn-clear-college').show();
        } else {
            $('.btn-clear-college').hide();
        }

        clearTimeout(collegeSearchTimeout);
        collegeSearchTimeout = setTimeout(function() {
            fetchCollegeSuggestions(q);
        }, 150);
    });

    // Close suggestions dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#div_institution').length) {
            $('#college_suggestions_box').hide();
        }
    });

    // Auto-fill degree_title if user chooses degree_type
    $(document).on('change', '#degree_type_id', function() {
        var selectedText = $(this).find("option:selected").text();
        if (selectedText && selectedText !== 'Select an option' && selectedText !== 'Select degree type') {
            $('#degree_title').val(selectedText);
        }
    });
});

function fetchCollegeSuggestions(query) {
    $.ajax({
        url: "{{ route('search.colleges') }}",
        type: "GET",
        data: { q: query },
        dataType: "json",
        success: function(colleges) {
            var html = '';
            var queryLower = query.toLowerCase();

            if (colleges && colleges.length > 0) {
                $.each(colleges, function(idx, name) {
                    html += '<div class="college-item-row" onclick="selectCollegeName(\'' + name.replace(/'/g, "\\'") + '\')">';
                    html += '<span>' + name + '</span>';
                    html += '</div>';
                });
            }

            // Always show manual add option if user typed something
            if (query.length > 0) {
                html += '<div class="college-item-manual" onclick="selectCollegeName(\'' + query.replace(/'/g, "\\'") + '\')">';
                html += '<i class="fa fa-plus-circle"></i> Use "<strong>' + query + '</strong>" as College Name';
                html += '</div>';
            } else if (!colleges || colleges.length === 0) {
                html = '<div style="padding:12px; color:#64748B; font-size:13px; text-align:center;">Type to search or enter college name</div>';
            }

            $('#college_suggestions_box').html(html).show();
        }
    });
}
</script>