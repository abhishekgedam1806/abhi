<div class="modal-body" style="padding: 24px 28px;">
    <div class="form-body">

        <!-- Header -->
        <div style="margin-bottom: 16px;">
            <h4 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0 0 4px 0;">
                {{__('What languages do you know?')}}
            </h4>
            <p style="font-size: 13px; color: #64748B; margin: 0;">
                {{__('Select one or more languages and set proficiency level for each')}}
            </p>
        </div>

        <!-- Language Search Input -->
        <div class="lang-search-wrapper" style="position: relative; margin-bottom: 14px;">
            <i class="fa fa-search" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#94A3B8; font-size:14px; z-index:5; pointer-events:none;"></i>
            <input type="text" id="lang_search_input" class="form-control"
                   placeholder="{{__('Search language (e.g. Hindi, English, French)')}}"
                   autocomplete="off"
                   onkeydown="handleLangSearchKeydown(event)"
                   oninput="onLangSearchChanged(this.value)"
                   style="padding-left:42px; padding-right:36px; height:46px; border:1.5px solid #CBD5E1; border-radius:10px; font-size:14px; width:100%; box-shadow:none;">
            <button type="button" id="lang_clear_btn" onclick="clearLangSearch()"
                    style="position:absolute; right:12px; top:50%; transform:translateY(-50%); border:none; background:transparent; color:#94A3B8; font-size:20px; cursor:pointer; display:none;">&times;</button>

            <!-- Autocomplete Dropdown -->
            <div id="lang_suggestions_dropdown"
                 style="display:none; position:absolute; top:calc(100% + 4px); left:0; right:0; background:#FFFFFF; border:1.5px solid #DB2777; border-radius:10px; box-shadow:0 10px 25px rgba(0,0,0,0.12); max-height:200px; overflow-y:auto; z-index:1055;">
            </div>
        </div>

        <!-- Selected Languages Section (with individual level dropdowns) -->
        <div id="selected_langs_section" style="margin-bottom: 14px; display: none;">
            <label style="font-size:12px; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px; display:block;">
                {{__('Selected Languages')}} (<span id="selected_lang_count">0</span>)
            </label>
            <div id="selected_langs_list" style="display:flex; flex-direction:column; gap:8px; max-height:220px; overflow-y:auto; padding-right:2px;">
                <!-- Dynamically populated -->
            </div>
        </div>

        <!-- Available Languages Cloud -->
        <div class="available-languages-section" style="margin-bottom: 10px;">
            <label style="font-size:12px; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px; display:block;">
                {{__('Available Languages')}}
            </label>
            <div id="available_langs_cloud"
                 style="display:flex; flex-wrap:wrap; gap:7px; max-height:170px; overflow-y:auto; padding:4px 2px; border-radius:10px;">
                @foreach($languages as $langId => $langName)
                    <div class="lang-chip-available"
                         id="avail_lang_{{ $langId }}"
                         data-id="{{ $langId }}"
                         data-name="{{ $langName }}"
                         onclick="addLangChip({{ $langId }}, '{{ addslashes($langName) }}')"
                         style="display:inline-flex; align-items:center; gap:5px; background:#F8FAFC; border:1.5px solid #E2E8F0; color:#334155; padding:5px 13px; border-radius:9999px; font-size:13px; font-weight:600; cursor:pointer; transition:all 0.15s ease; user-select:none;">
                        <i class="fa fa-plus" style="font-size:10px; color:#94A3B8;"></i>
                        <span>{{ $langName }}</span>
                    </div>
                @endforeach
            </div>
            <div id="no_lang_match" style="display:none; padding:12px; text-align:center; color:#64748B; font-size:13px;">
                {{__('No matching languages found.')}}
            </div>
        </div>

        <!-- Validation Error -->
        <span class="help-block language_id-error text-danger" style="font-size:13px; display:block;"></span>
        <span class="help-block language_level_id-error text-danger" style="font-size:13px; display:block;"></span>

        <!-- Hidden container for form data -->
        <div id="lang_hidden_inputs"></div>
    </div>
</div>

<style>
.lang-chip-available { transition: all 0.15s ease !important; }
.lang-chip-available:hover {
    background: #FDF2F8 !important; border-color: #DB2777 !important;
    color: #9D174D !important; transform: translateY(-1px);
}
.lang-chip-available:hover i { color: #DB2777 !important; }
.lang-chip-available.is-selected { display: none !important; }

.lang-selected-row {
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
    background: #FDF2F8; border: 1.5px solid #FBCFE8; border-radius: 10px;
    padding: 9px 14px;
}
.lang-selected-row .lang-name-badge {
    display: inline-flex; align-items: center; gap: 7px;
    color: #9D174D; font-size: 13.5px; font-weight: 700; flex-shrink: 0; min-width: 120px;
}
.lang-selected-row .lang-level-select {
    flex: 1; min-width: 160px; height: 36px; border: 1.5px solid #FBCFE8;
    border-radius: 8px; font-size: 13px; font-weight: 600; color: #0F172A;
    padding: 0 10px; background: #FFFFFF; cursor: pointer;
}
.lang-selected-row .lang-remove-btn {
    width: 28px; height: 28px; border-radius: 50%; border: none;
    background: #FCE7F3; color: #DB2777; font-size: 17px; font-weight: bold;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; line-height:1; transition: background 0.15s;
}
.lang-selected-row .lang-remove-btn:hover { background: #DB2777; color: #fff; }

#lang_suggestions_dropdown .lang-sugg-item {
    display: flex; align-items: center; padding: 10px 16px;
    font-size: 14px; color: #1E293B; cursor: pointer;
    border-bottom: 1px solid #F1F5F9; transition: background 0.12s;
}
#lang_suggestions_dropdown .lang-sugg-item:hover { background: #FDF2F8; color: #9D174D; }
#lang_suggestions_dropdown .lang-sugg-item:last-child { border-bottom: none; }

#available_langs_cloud::-webkit-scrollbar,
#selected_langs_list::-webkit-scrollbar,
#lang_suggestions_dropdown::-webkit-scrollbar { width: 5px; }
#available_langs_cloud::-webkit-scrollbar-thumb,
#selected_langs_list::-webkit-scrollbar-thumb,
#lang_suggestions_dropdown::-webkit-scrollbar-thumb { background: #FBCFE8; border-radius: 10px; }
</style>

<script type="text/javascript">
    var allLangs = @json($languages);
    var selectedLangIds = [];

    @php
        $levelOptions = ['' => __('Select level')] + $languageLevels;
    @endphp
    var levelOptionsHtml = '<option value="">{{__("Select level")}}</option>';
    @foreach($languageLevels as $lvlId => $lvlName)
        levelOptionsHtml += '<option value="{{ $lvlId }}">{{ $lvlName }}</option>';
    @endforeach

    function onLangSearchChanged(term) {
        var q = term.trim();
        if (q.length > 0) {
            $('#lang_clear_btn').show();
            renderLangSuggestions(q);
        } else {
            $('#lang_clear_btn').hide();
            $('#lang_suggestions_dropdown').hide().empty();
        }
        filterLangCloud(q);
    }

    function clearLangSearch() {
        $('#lang_search_input').val('').focus();
        $('#lang_clear_btn').hide();
        $('#lang_suggestions_dropdown').hide().empty();
        filterLangCloud('');
    }

    function handleLangSearchKeydown(e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            e.preventDefault();
            var first = $('#lang_suggestions_dropdown .lang-sugg-item:first');
            if (first.length) first.trigger('click');
            return false;
        } else if (e.key === 'Escape') {
            $('#lang_suggestions_dropdown').hide();
        }
    }

    function renderLangSuggestions(query) {
        var dropdown = $('#lang_suggestions_dropdown');
        dropdown.empty();
        var q = query.toLowerCase();
        var matched = [];

        $.each(allLangs, function(id, name) {
            if (typeof name === 'string' && name.toLowerCase().indexOf(q) !== -1) {
                matched.push({ id: id, name: name });
            }
        });

        if (matched.length > 0) {
            $.each(matched.slice(0, 25), function(i, item) {
                var isSelected = selectedLangIds.indexOf(parseInt(item.id)) !== -1;
                var iconHtml = isSelected
                    ? '<i class="fa fa-check" style="margin-right:8px; color:#DB2777;"></i>'
                    : '<i class="fa fa-plus" style="margin-right:8px; color:#94A3B8;"></i>';
                var html = '<div class="lang-sugg-item" onclick="addLangChip(' + item.id + ', \'' + item.name.replace(/'/g, "\\'") + '\')">' +
                    iconHtml + '<span>' + item.name + '</span>' +
                    (isSelected ? '<span style="font-size:11px; color:#DB2777; margin-left:auto;">(Added)</span>' : '') +
                    '</div>';
                dropdown.append(html);
            });
            dropdown.show();
        } else {
            dropdown.hide();
        }
    }

    function addLangChip(langId, langName) {
        langId = parseInt(langId);
        if (selectedLangIds.indexOf(langId) !== -1) return; // already added

        selectedLangIds.push(langId);
        $('.language_id-error').html('');

        // Build selected row with individual level dropdown
        var rowHtml = '<div class="lang-selected-row" id="sel_lang_row_' + langId + '">' +
            '<span class="lang-name-badge">' +
                '<i class="fa fa-language"></i>' +
                '<span>' + langName + '</span>' +
            '</span>' +
            '<select class="lang-level-select" id="level_sel_' + langId + '" onchange="updateLangLevel(' + langId + ', this.value)" name="lang_levels[' + langId + ']">' +
                levelOptionsHtml +
            '</select>' +
            '<button type="button" class="lang-remove-btn" onclick="removeLangChip(' + langId + ')" title="Remove">&times;</button>' +
        '</div>';

        $('#selected_langs_list').append(rowHtml);
        $('#lang_hidden_inputs').append('<input type="hidden" name="language_ids[]" value="' + langId + '" id="hidden_lang_' + langId + '">');
        $('#hidden_level_' + langId).remove();
        $('#lang_hidden_inputs').append('<input type="hidden" name="language_level_ids[]" value="" id="hidden_level_' + langId + '">');

        // Hide from cloud
        $('#avail_lang_' + langId).addClass('is-selected').hide();

        updateSelectedCount();
        $('#lang_suggestions_dropdown').hide().empty();
        clearLangSearch();
    }

    function updateLangLevel(langId, levelVal) {
        $('#hidden_level_' + langId).val(levelVal);
    }

    function removeLangChip(langId) {
        langId = parseInt(langId);
        var idx = selectedLangIds.indexOf(langId);
        if (idx > -1) selectedLangIds.splice(idx, 1);

        $('#sel_lang_row_' + langId).remove();
        $('#hidden_lang_' + langId).remove();
        $('#hidden_level_' + langId).remove();

        $('#avail_lang_' + langId).removeClass('is-selected').css('display', 'inline-flex');
        updateSelectedCount();
        filterLangCloud($('#lang_search_input').val());
    }

    function updateSelectedCount() {
        var count = selectedLangIds.length;
        $('#selected_lang_count').text(count);
        if (count > 0) {
            $('#selected_langs_section').show();
        } else {
            $('#selected_langs_section').hide();
        }
    }

    function filterLangCloud(term) {
        var q = term.toLowerCase().trim();
        var visible = 0;
        $('#available_langs_cloud .lang-chip-available').each(function() {
            var name = $(this).data('name').toLowerCase();
            if (!$(this).hasClass('is-selected') && (q === '' || name.indexOf(q) !== -1)) {
                $(this).css('display', 'inline-flex');
                visible++;
            } else {
                $(this).hide();
            }
        });
        if (visible === 0 && q !== '') {
            $('#no_lang_match').show();
        } else {
            $('#no_lang_match').hide();
        }
    }

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.lang-search-wrapper').length) {
            $('#lang_suggestions_dropdown').hide();
        }
    });
</script>
