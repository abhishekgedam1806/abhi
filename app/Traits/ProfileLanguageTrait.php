<?php

namespace App\Traits;

use Auth;
use DB;
use Input;
use Carbon\Carbon;
use Redirect;
use App\User;
use App\ProfileLanguage;
use App\Language;
use App\LanguageLevel;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\ProfileLanguageFormRequest;
use App\Helpers\DataArrayHelper;

trait ProfileLanguageTrait
{

    public function showProfileLanguages(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $html = '';
        if (isset($user) && count($user->profileLanguages)) {
            $html .= '<div class="profile-table-container"><table class="table align-middle">';
            $html .= '<thead><tr><th>Language Name</th><th>Proficiency Level</th><th style="width:160px; text-align:center;">Action</th></tr></thead><tbody>';
            foreach ($user->profileLanguages as $language) {
                $langName = $language->getLanguage('lang');
                $langLevel = $language->getLanguageLevel('language_level') ?: 'Intermediate';
                $html .= '<tr id="language_' . $language->id . '">
                    <td style="vertical-align:middle; font-weight:700; color:#0F172A;">
                        <span style="display:inline-flex; align-items:center; gap:8px;">
                            <i class="fa fa-language" style="color:#DB2777; font-size:16px;"></i>
                            ' . e($langName) . '
                        </span>
                    </td>
                    <td style="vertical-align:middle;"><span class="badge-lang-level">' . e($langLevel) . '</span></td>
                    <td style="vertical-align:middle; text-align:center;">
                        <div class="profile-actions-wrap">
                            <a href="javascript:;" onclick="showProfileLanguageEditModal(' . $language->id . ');" class="btn-action-edit"><i class="fa fa-pencil"></i> ' . __('Edit') . '</a>
                            <a href="javascript:;" onclick="delete_profile_language(' . $language->id . ');" class="btn-action-delete"><i class="fa fa-trash"></i> ' . __('Delete') . '</a>
                        </div>
                    </td>
                </tr>';
            }
            $html .= '</tbody></table></div>';
        } else {
            $html .= '<div class="profile-empty-state">
                <div class="profile-empty-state-text"><i class="fa fa-language"></i> No languages added yet. Add languages you speak and write to improve recruiter reach.</div>
            </div>';
        }

        echo $html;
    }

    public function showApplicantProfileLanguages(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $html = '';
        if (isset($user) && count($user->profileLanguages)) {
            $html .= '<div class="col-12"><div style="display: flex; flex-wrap: wrap; gap: 8px;">';
            foreach ($user->profileLanguages as $language) {
                $langName  = $language->getLanguage('lang');
                $langLevel = $language->getLanguageLevel('language_level');
                if (empty($langName)) continue;
                $html .= '<span style="display: inline-flex; align-items: center; gap: 6px; background: #F1F5F9; border: 1px solid #CBD5E1; color: #334155; padding: 5px 12px; border-radius: 16px; font-size: 13px; font-weight: 600;">
                    <i class="fa fa-language text-primary"></i> ' . e($langName) . ($langLevel ? ' <span style="font-size: 11px; color: #64748B;">(' . e($langLevel) . ')</span>' : '') . '
                </span>';
            }
            $html .= '</div></div>';
        } else {
            $html .= '<p class="text-muted" style="padding: 10px 0; font-size: 13.5px;"><i class="fa fa-language text-muted"></i> ' . __('No languages added yet.') . '</p>';
        }

        echo $html;
    }

    public function getProfileLanguageForm(Request $request, $user_id)
    {

        $languages = DataArrayHelper::languagesArray();
        $languageLevels = DataArrayHelper::defaultLanguageLevelsArray();

        $user = User::find($user_id);
        $returnHTML = view('admin.user.forms.language.language_modal')
                ->with('user', $user)
                ->with('languages', $languages)
                ->with('languageLevels', $languageLevels)
                ->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function getFrontProfileLanguageForm(Request $request, $user_id)
    {

        $languages = DataArrayHelper::languagesArray();
        $languageLevels = DataArrayHelper::langLanguageLevelsArray();

        $user = User::find($user_id);
        $returnHTML = view('user.forms.language.language_modal')
                ->with('user', $user)
                ->with('languages', $languages)
                ->with('languageLevels', $languageLevels)
                ->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function storeProfileLanguage(ProfileLanguageFormRequest $request, $user_id)
    {

        $profileLanguage = new ProfileLanguage();
        $profileLanguage = $this->assignLanguageValues($profileLanguage, $request, $user_id);
        $profileLanguage->save();
        /*         * ************************************ */
        $returnHTML = view('admin.user.forms.language.language_thanks')->render();
        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);
    }

    public function storeFrontProfileLanguage(ProfileLanguageFormRequest $request, $user_id)
    {
        // Multi-select mode: language_ids[] array
        if ($request->has('language_ids')) {
            $language_ids       = $request->input('language_ids', []);
            $language_level_ids = $request->input('language_level_ids', []);

            // Validate every level is selected
            foreach ($language_level_ids as $lvl) {
                if (empty($lvl)) {
                    return response()->json([
                        'success' => false,
                        'status'  => 422,
                        'errors'  => ['language_level_ids' => ['Please select proficiency level for each selected language.']]
                    ], 422);
                }
            }

            foreach ($language_ids as $idx => $lang_id) {
                $level_id = isset($language_level_ids[$idx]) ? $language_level_ids[$idx] : null;
                if (empty($lang_id)) continue;

                // Skip duplicate (same language already saved for this user)
                $exists = ProfileLanguage::where('user_id', $user_id)
                            ->where('language_id', $lang_id)->exists();
                if ($exists) continue;

                $profileLanguage = new ProfileLanguage();
                $profileLanguage->user_id           = $user_id;
                $profileLanguage->language_id       = $lang_id;
                $profileLanguage->language_level_id = $level_id;
                $profileLanguage->save();
            }
        } else {
            // Legacy single mode
            $profileLanguage = new ProfileLanguage();
            $profileLanguage = $this->assignLanguageValues($profileLanguage, $request, $user_id);
            $profileLanguage->save();
        }

        $returnHTML = view('user.forms.language.language_thanks')->render();
        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);
    }


    public function getProfileLanguageEditForm(Request $request, $user_id)
    {
        $profile_language_id = $request->input('profile_language_id');

        $languages = DataArrayHelper::languagesArray();
        $languageLevels = DataArrayHelper::defaultLanguageLevelsArray();

        $profileLanguage = ProfileLanguage::find($profile_language_id);
        $user = User::find($user_id);

        $returnHTML = view('admin.user.forms.language.language_edit_modal')
                ->with('user', $user)
                ->with('profileLanguage', $profileLanguage)
                ->with('languages', $languages)
                ->with('languageLevels', $languageLevels)
                ->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function getFrontProfileLanguageEditForm(Request $request, $user_id)
    {
        $profile_language_id = $request->input('profile_language_id');

        $languages = DataArrayHelper::languagesArray();
        $languageLevels = DataArrayHelper::langLanguageLevelsArray();

        $profileLanguage = ProfileLanguage::find($profile_language_id);
        $user = User::find($user_id);

        $returnHTML = view('user.forms.language.language_edit_modal')
                ->with('user', $user)
                ->with('profileLanguage', $profileLanguage)
                ->with('languages', $languages)
                ->with('languageLevels', $languageLevels)
                ->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function updateProfileLanguage(ProfileLanguageFormRequest $request, $profile_language_id, $user_id)
    {

        $profileLanguage = ProfileLanguage::find($profile_language_id);
        $profileLanguage = $this->assignLanguageValues($profileLanguage, $request, $user_id);
        $profileLanguage->update();
        /*         * ************************************ */

        $returnHTML = view('admin.user.forms.language.language_edit_thanks')->render();
        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);
    }

    public function updateFrontProfileLanguage(ProfileLanguageFormRequest $request, $profile_language_id, $user_id)
    {

        $profileLanguage = ProfileLanguage::find($profile_language_id);
        $profileLanguage = $this->assignLanguageValues($profileLanguage, $request, $user_id);
        $profileLanguage->update();
        /*         * ************************************ */

        $returnHTML = view('user.forms.language.language_edit_thanks')->render();
        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);
    }

    public function assignLanguageValues($profileLanguage, $request, $user_id)
    {
        $profileLanguage->user_id = $user_id;
        $profileLanguage->language_id = $request->input('language_id');
        $profileLanguage->language_level_id = $request->input('language_level_id');
        return $profileLanguage;
    }

    public function deleteProfileLanguage(Request $request)
    {
        $id = $request->input('id');
        try {
            $profileLanguage = ProfileLanguage::findOrFail($id);
            $profileLanguage->delete();
            echo 'ok';
        } catch (ModelNotFoundException $e) {
            echo 'notok';
        }
    }

}
