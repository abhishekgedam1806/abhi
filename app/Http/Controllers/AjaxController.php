<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Input;
use Form;
use App\Helpers\MiscHelper;
use App\Helpers\DataArrayHelper;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Traits\CountryStateCity;

class AjaxController extends Controller
{

    use CountryStateCity;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function filterDefaultStates(Request $request)
    {
        $country_id = $request->input('country_id');
        $state_id = $request->input('state_id');
        $new_state_id = $request->input('new_state_id', 'state_id');
        $states = DataArrayHelper::defaultStatesArray($country_id);
        $dd = Form::select('state_id', ['' => __('Select State')] + $states, $state_id, array('id' => $new_state_id, 'class' => 'form-control'));
        echo $dd;
    }

    public function filterDefaultCities(Request $request)
    {
        $state_id = $request->input('state_id');
        $city_id = $request->input('city_id');
        $cities = DataArrayHelper::defaultCitiesArray($state_id);
        $dd = Form::select('city_id', ['' => 'Select City'] + $cities, $city_id, array('id' => 'city_id', 'class' => 'form-control'));
        echo $dd;
    }

    /*     * ***************************************** */

    public function filterLangStates(Request $request)
    {
        $country_id = $request->input('country_id');
        $state_id = $request->input('state_id');
        $new_state_id = $request->input('new_state_id', 'state_id');
        $states = DataArrayHelper::langStatesArray($country_id);
        $dd = Form::select('state_id', ['' => __('Select State')] + $states, $state_id, array('id' => $new_state_id, 'class' => 'form-control'));
        echo $dd;
    }

    public function filterLangCities(Request $request)
    {
        $state_id = $request->input('state_id');
        $city_id = $request->input('city_id');
        $cities = DataArrayHelper::langCitiesArray($state_id);

        $dd = Form::select('city_id', ['' => 'Select City'] + $cities, $city_id, array('id' => 'city_id', 'class' => 'form-control'));
        echo $dd;
    }

    /*     * ***************************************** */

    public function filterStates(Request $request)
    {
        $country_id = $request->input('country_id');
        $state_id = $request->input('state_id');
        $new_state_id = $request->input('new_state_id', 'state_id');
        $states = DataArrayHelper::langStatesArray($country_id);
        $dd = Form::select('state_id[]', ['' => __('Select State')] + $states, $state_id, array('id' => $new_state_id, 'class' => 'form-control'));
        echo $dd;
    }

    public function filterCities(Request $request)
    {
        $state_id = $request->input('state_id');
        $city_id = $request->input('city_id');
        $cities = DataArrayHelper::langCitiesArray($state_id);

        $dd = Form::select('city_id[]', ['' => 'Select City'] + $cities, $city_id, array('id' => 'city_id', 'class' => 'form-control'));
        echo $dd;
    }

    /*     * ***************************************** */

    public function filterDegreeTypes(Request $request)
    {
        $degree_level_id = $request->input('degree_level_id');
        $degree_type_id = $request->input('degree_type_id');

        $degreeTypes = DataArrayHelper::langDegreeTypesArray($degree_level_id);
        $dd = Form::select('degree_type_id', ['' => __('Select an option')] + $degreeTypes, $degree_type_id, array('id' => 'degree_type_id', 'class' => 'form-control modern-input'));
        echo $dd;
    }

    /*     * ***************************************** */

    public function searchColleges(Request $request)
    {
        $query = trim($request->input('q', ''));
        if ($query === '') {
            $colleges = DB::table('colleges')->orderBy('name', 'asc')->limit(15)->pluck('name')->toArray();
        } else {
            $colleges = DB::table('colleges')
                ->where('name', 'LIKE', '%' . $query . '%')
                ->orderBy('name', 'asc')
                ->limit(25)
                ->pluck('name')
                ->toArray();

            $userAdded = DB::table('profile_educations')
                ->where('institution', 'LIKE', '%' . $query . '%')
                ->distinct()
                ->limit(10)
                ->pluck('institution')
                ->toArray();

            $colleges = array_values(array_unique(array_merge($colleges, $userAdded)));
        }

        return response()->json($colleges);
    }

    /*     * ***************************************** */

    public function getSkillsByDepartment(Request $request)
    {
        $functional_area_id = $request->input('functional_area_id');
        $query_str = trim($request->input('q', ''));

        $skillsQuery = \App\JobSkill::select('job_skills.job_skill_id as id', 'job_skills.job_skill as name')
            ->lang()
            ->active()
            ->sorted();

        if (!empty($functional_area_id)) {
            $skillsQuery->where('job_skills.functional_area_id', $functional_area_id);
        }

        if (!empty($query_str)) {
            $skillsQuery->where('job_skills.job_skill', 'LIKE', '%' . $query_str . '%');
        }

        $skills = $skillsQuery->get();

        if ($skills->isEmpty() && !empty($functional_area_id)) {
            // Fallback to default language skills
            $skillsQuery = \App\JobSkill::select('job_skills.job_skill_id as id', 'job_skills.job_skill as name')
                ->isDefault()
                ->active()
                ->where('job_skills.functional_area_id', $functional_area_id)
                ->sorted();

            if (!empty($query_str)) {
                $skillsQuery->where('job_skills.job_skill', 'LIKE', '%' . $query_str . '%');
            }
            $skills = $skillsQuery->get();
        }

        return response()->json([
            'success' => true,
            'functional_area_id' => $functional_area_id,
            'skills' => $skills
        ]);
    }

    public function filterSkillsDropdown(Request $request)
    {
        $functional_area_id = $request->input('functional_area_id');
        $selected_skills = (array) $request->input('selected_skills', []);
        
        $skills = DataArrayHelper::langJobSkillsArray($functional_area_id);
        $options = '';
        foreach ($skills as $id => $name) {
            $selected = in_array($id, $selected_skills) ? 'selected="selected"' : '';
            $options .= '<option value="' . $id . '" ' . $selected . '>' . e($name) . '</option>';
        }
        return response()->json(['options' => $options, 'skills' => $skills]);
    }

    public function addCustomSkill(Request $request)
    {
        $skillName = trim($request->input('skill_name', ''));
        $functionalAreaId = $request->input('functional_area_id');

        if (empty($skillName)) {
            return response()->json(['success' => false, 'message' => 'Skill name cannot be empty.'], 422);
        }

        // Check if skill already exists in this department or globally
        $existing = \App\JobSkill::where('job_skill', 'LIKE', $skillName)
            ->where(function($q) use ($functionalAreaId) {
                if (!empty($functionalAreaId)) {
                    $q->where('functional_area_id', $functionalAreaId);
                }
            })
            ->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'skill' => [
                    'id' => $existing->job_skill_id ?: $existing->id,
                    'name' => $existing->job_skill,
                    'functional_area_id' => $existing->functional_area_id
                ],
                'is_new' => false
            ]);
        }

        // Create new skill entry
        $maxId = (int) \App\JobSkill::max('job_skill_id') + 1;
        $jobSkill = new \App\JobSkill();
        $jobSkill->job_skill_id = $maxId;
        $jobSkill->functional_area_id = $functionalAreaId ?: null;
        $jobSkill->job_skill = $skillName;
        $jobSkill->is_default = 1;
        $jobSkill->is_active = 1;
        $jobSkill->sort_order = $maxId;
        $jobSkill->lang = 'en';
        $jobSkill->save();

        return response()->json([
            'success' => true,
            'skill' => [
                'id' => $jobSkill->job_skill_id,
                'name' => $jobSkill->job_skill,
                'functional_area_id' => $jobSkill->functional_area_id
            ],
            'is_new' => true
        ]);
    }

    public function trackHrContact(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $applicationId = (int) $request->input('application_id');
        $contactType = $request->input('contact_type'); // 'phone' or 'whatsapp'

        $application = \App\JobApply::where('id', $applicationId)->where('user_id', $user->id)->first();
        if (!$application) {
            return response()->json(['success' => false, 'message' => 'Application not found'], 404);
        }

        $job = $application->getJob();
        $company = $job ? $job->getCompany() : null;

        if (!$company) {
            return response()->json(['success' => false, 'message' => 'Company not found'], 404);
        }

        // Log contact activity
        \DB::table('application_contact_activities')->insert([
            'application_id' => $application->id,
            'job_id' => $job->id,
            'candidate_id' => $user->id,
            'company_id' => $company->id,
            'contact_type' => in_array($contactType, ['phone', 'whatsapp']) ? $contactType : 'phone',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'contact_type' => $contactType
        ]);
    }

    public function reportJobAbuseAjax(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Please login to report.'], 401);
        }

        $user = Auth::user();
        $jobId = (int) $request->input('job_id');
        $reason = $request->input('reason', 'Other');
        $details = $request->input('details', '');

        $job = \App\Job::find($jobId);
        if (!$job) {
            return response()->json(['success' => false, 'message' => 'Job not found.'], 404);
        }

        \App\ReportAbuseMessage::create([
            'your_name' => $user->getName() . ' [Reason: ' . $reason . ' - ' . $details . ']',
            'your_email' => $user->email,
            'job_url' => route('job.detail', [$job->slug]),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you. Your report has been submitted to the moderation team.'
        ]);
    }

}
