<?php

namespace App\Traits;

use Auth;
use DB;
use Input;
use Carbon\Carbon;
use Redirect;
use App\User;
use App\ProfileSkill;
use App\JobSkill;
use App\JobExperience;
use App\Country;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\ProfileSkillFormRequest;
use App\Helpers\DataArrayHelper;

trait ProfileSkillTrait
{

    public function showProfileSkills(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $html = '';
        if (isset($user) && count($user->profileSkills)) {
            $html .= '<div class="profile-skills-chips-wrapper" style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;">';
            foreach ($user->profileSkills as $skill) {
                $skillName = $skill->getJobSkill('job_skill');
                if (empty($skillName)) continue;
                $html .= '<div class="profile-skill-chip-item" id="skill_' . $skill->id . '" style="display: inline-flex; align-items: center; gap: 8px; background: #ECFDF5; border: 1.5px solid #10B981; color: #065F46; padding: 7px 16px; border-radius: 9999px; font-size: 13.5px; font-weight: 600; box-shadow: 0 1px 2px rgba(16,185,129,0.08); transition: all 0.2s ease;">
                    <i class="fa fa-check-circle" style="color: #03855c; font-size: 14px;"></i>
                    <span>' . e($skillName) . '</span>
                    <a href="javascript:;" onclick="delete_profile_skill(' . $skill->id . ');" title="' . __('Remove Skill') . '" style="color: #03855c; margin-left: 2px; font-size: 15px; font-weight: bold; text-decoration: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; border-radius: 50%;">&times;</a>
                </div>';
            }
            $html .= '</div>';
        } else {
            $html .= '<div class="profile-empty-state">
                <div class="profile-empty-state-text"><i class="fa fa-code"></i> ' . __('No skills added yet. Add your key skills to attract recruiters and match top jobs.') . '</div>
            </div>';
        }

        echo $html;
    }

    public function showApplicantProfileSkills(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $html = '';
        if (isset($user) && count($user->profileSkills)) {
            $html .= '<div class="col-12"><div style="display: flex; flex-wrap: wrap; gap: 8px;">';
            foreach ($user->profileSkills as $skill) {
                $skillName = $skill->getJobSkill('job_skill');
                if (empty($skillName)) continue;
                $html .= '<span style="display: inline-flex; align-items: center; gap: 6px; background: #ECFDF5; border: 1.5px solid #10B981; color: #065F46; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 700;">
                    <i class="fa fa-check-circle text-success"></i> ' . e($skillName) . '
                </span>';
            }
            $html .= '</div></div>';
        } else {
            $html .= '<p class="text-muted" style="padding: 10px 0; font-size: 13.5px;"><i class="fa fa-cogs text-muted"></i> ' . __('No skills added yet.') . '</p>';
        }

        echo $html;
    }

    public function getProfileSkillForm(Request $request, $user_id)
    {

        $jobSkills = DataArrayHelper::defaultJobSkillsArray();
        $jobExperiences = DataArrayHelper::defaultJobExperiencesArray();

        $user = User::find($user_id);
        $returnHTML = view('admin.user.forms.skill.skill_modal')
                ->with('user', $user)
                ->with('jobSkills', $jobSkills)
                ->with('jobExperiences', $jobExperiences)
                ->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function getFrontProfileSkillForm(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $canonicalDeptNames = [
            'IT & Software',
            'Sales',
            'Marketing & Digital Marketing',
            'BPO / Call Center / Customer Support',
            'Accounts & Finance',
            'HR & Recruitment',
            'Driving',
            'Logistics & Delivery',
            'Graphic Design & Creative',
            'Administration'
        ];

        $functionalAreas = \App\FunctionalArea::whereIn('functional_area', $canonicalDeptNames)
            ->where('is_active', 1)
            ->lang()
            ->get();

        if ($functionalAreas->isEmpty()) {
            $functionalAreas = \App\FunctionalArea::whereIn('functional_area', $canonicalDeptNames)
                ->where('is_active', 1)
                ->isDefault()
                ->get();
        }

        $functionalAreas = $functionalAreas->sortBy(function($item) use ($canonicalDeptNames) {
            return array_search($item->functional_area, $canonicalDeptNames);
        })->values();

        // Determine active department
        $activeDepartmentId = 148;
        if ($user->functional_area_id && $functionalAreas->where('functional_area_id', $user->functional_area_id)->count()) {
            $activeDepartmentId = $user->functional_area_id;
        } elseif ($functionalAreas->first()) {
            $activeDepartmentId = $functionalAreas->first()->functional_area_id;
        }

        // Get skills for the active department
        $departmentSkills = \App\JobSkill::select('job_skill_id as id', 'job_skill as name')
            ->where('functional_area_id', $activeDepartmentId)
            ->lang()
            ->active()
            ->sorted()
            ->get();

        if ($departmentSkills->isEmpty()) {
            $departmentSkills = \App\JobSkill::select('job_skill_id as id', 'job_skill as name')
                ->where('functional_area_id', $activeDepartmentId)
                ->isDefault()
                ->active()
                ->sorted()
                ->get();
        }

        // Get already selected user skill IDs
        $userSkillIds = $user->profileSkills->pluck('job_skill_id')->toArray();
        $userSkills = \App\JobSkill::whereIn('job_skill_id', $userSkillIds)->lang()->active()->get();
        if ($userSkills->isEmpty() && !empty($userSkillIds)) {
            $userSkills = \App\JobSkill::whereIn('job_skill_id', $userSkillIds)->isDefault()->active()->get();
        }

        $jobExperiences = DataArrayHelper::langJobExperiencesArray();

        $returnHTML = view('user.forms.skill.skill_modal')
                ->with('user', $user)
                ->with('functionalAreas', $functionalAreas)
                ->with('activeDepartmentId', $activeDepartmentId)
                ->with('departmentSkills', $departmentSkills)
                ->with('userSkillIds', $userSkillIds)
                ->with('userSkills', $userSkills)
                ->with('jobExperiences', $jobExperiences)
                ->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function storeProfileSkill(ProfileSkillFormRequest $request, $user_id)
    {

        $profileSkill = new ProfileSkill();
        $profileSkill->user_id = $user_id;
        $profileSkill->job_skill_id = $request->input('job_skill_id');
        $profileSkill->job_experience_id = $request->input('job_experience_id');
        $profileSkill->save();
        /*         * ************************************ */
        $returnHTML = view('admin.user.forms.skill.skill_thanks')->render();
        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);
    }

    public function storeFrontProfileSkill(ProfileSkillFormRequest $request, $user_id)
    {
        $user = User::find($user_id);
        $jobExperienceId = $request->input('job_experience_id') ?: (\App\JobExperience::first() ? \App\JobExperience::first()->job_experience_id : 1);

        if ($request->has('functional_area_id') && !empty($request->input('functional_area_id'))) {
            $user->functional_area_id = $request->input('functional_area_id');
            $user->save();
        }

        if ($request->has('skill_ids')) {
            $skillIds = (array) $request->input('skill_ids', []);
            $validSkillIds = [];
            foreach ($skillIds as $sId) {
                $sId = (int) $sId;
                if ($sId > 0) {
                    $validSkillIds[] = $sId;
                }
            }
            $validSkillIds = array_values(array_unique($validSkillIds));

            // Sync user skills: delete removed, add newly selected
            if (!empty($validSkillIds)) {
                ProfileSkill::where('user_id', $user_id)->whereNotIn('job_skill_id', $validSkillIds)->delete();
                foreach ($validSkillIds as $sId) {
                    $exists = ProfileSkill::where('user_id', $user_id)->where('job_skill_id', $sId)->first();
                    if (!$exists) {
                        $ps = new ProfileSkill();
                        $ps->user_id           = $user_id;
                        $ps->job_skill_id      = $sId;
                        $ps->job_experience_id = $jobExperienceId;
                        $ps->save();
                    }
                }
            } else {
                ProfileSkill::where('user_id', $user_id)->delete();
            }
        } elseif ($request->has('job_skill_id')) {
            $skillId = (int) $request->input('job_skill_id');
            if ($skillId > 0) {
                $exists = ProfileSkill::where('user_id', $user_id)->where('job_skill_id', $skillId)->first();
                if (!$exists) {
                    $profileSkill = new ProfileSkill();
                    $profileSkill->user_id           = $user_id;
                    $profileSkill->job_skill_id      = $skillId;
                    $profileSkill->job_experience_id = $jobExperienceId;
                    $profileSkill->save();
                }
            }
        }

        if (method_exists($this, 'updateUserFullTextSearch')) {
            try {
                $this->updateUserFullTextSearch($user);
            } catch (\Exception $e) {}
        }

        $returnHTML = view('user.forms.skill.skill_thanks')->render();
        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);
    }

    public function getProfileSkillEditForm(Request $request, $user_id)
    {
        $skill_id = $request->input('skill_id');
        $jobSkills = DataArrayHelper::defaultJobSkillsArray();
        $jobExperiences = DataArrayHelper::defaultJobExperiencesArray();

        $profileSkill = ProfileSkill::find($skill_id);
        $user = User::find($user_id);

        $returnHTML = view('admin.user.forms.skill.skill_edit_modal')
                ->with('user', $user)
                ->with('profileSkill', $profileSkill)
                ->with('jobSkills', $jobSkills)
                ->with('jobExperiences', $jobExperiences)
                ->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function getFrontProfileSkillEditForm(Request $request, $user_id)
    {
        $skill_id = $request->input('skill_id');

        $jobSkills = DataArrayHelper::langJobSkillsArray();
        $jobExperiences = DataArrayHelper::langJobExperiencesArray();

        $profileSkill = ProfileSkill::find($skill_id);
        $user = User::find($user_id);

        $returnHTML = view('user.forms.skill.skill_edit_modal')
                ->with('user', $user)
                ->with('profileSkill', $profileSkill)
                ->with('jobSkills', $jobSkills)
                ->with('jobExperiences', $jobExperiences)
                ->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function updateProfileSkill(ProfileSkillFormRequest $request, $skill_id, $user_id)
    {

        $profileSkill = ProfileSkill::find($skill_id);
        $profileSkill->user_id = $user_id;
        $profileSkill->job_skill_id = $request->input('job_skill_id');
        $profileSkill->job_experience_id = $request->input('job_experience_id');
        $profileSkill->update();
        /*         * ************************************ */

        $returnHTML = view('admin.user.forms.skill.skill_edit_thanks')->render();
        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);
    }

    public function updateFrontProfileSkill(ProfileSkillFormRequest $request, $skill_id, $user_id)
    {

        $profileSkill = ProfileSkill::find($skill_id);
        $profileSkill->user_id = $user_id;
        $profileSkill->job_skill_id = $request->input('job_skill_id');
        $profileSkill->job_experience_id = $request->input('job_experience_id');
        $profileSkill->update();
        /*         * ************************************ */

        $returnHTML = view('user.forms.skill.skill_edit_thanks')->render();
        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);
    }

    public function deleteProfileSkill(Request $request)
    {
        $id = $request->input('id');
        try {
            $profileSkill = ProfileSkill::findOrFail($id);
            $profileSkill->delete();
            echo 'ok';
        } catch (ModelNotFoundException $e) {
            echo 'notok';
        }
    }

}
