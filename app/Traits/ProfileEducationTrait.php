<?php

namespace App\Traits;

use Auth;
use DB;
use Input;
use Carbon\Carbon;
use Redirect;
use App\User;
use App\ProfileEducation;
use App\ProfileEducationMajorSubject;
use App\DegreeLevel;
use App\DegreeType;
use App\ResultType;
use App\MajorSubject;
use App\Country;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\ProfileEducationFormRequest;
use App\Helpers\DataArrayHelper;

trait ProfileEducationTrait
{

    public function showProfileEducation(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $html = '';
        if (isset($user) && count($user->profileEducation)):
            foreach ($user->profileEducation as $education):

                $html .= '<!--education Start-->
            <div class="col-md-12" id="education_' . $education->id . '">
              <div class="mt-element-ribbon bg-grey-steel">
                <div class="ribbon ribbon-color-warning uppercase ">' . $education->getDegreeLevel('degree_level') . ' - ' . $education->getDegreeType('degree_type') . '</div>
                <p class="ribbon-content">
				' . $education->degree_title . '<br />               	
                ' . $education->date_completion . ' | ' . $education->getCity('city') . '<br />
                ' . $education->institution . '<br />
                <a href="javascript:void(0);" onclick="showProfileEducationEditModal(' . $education->id . ',' . $education->state_id . ',' . $education->city_id . ',' . $education->degree_type_id . ');" class="btn btn-warning">' . __('Edit') . '</a>
				<a href="javascript:void(0);" onclick="delete_profile_education(' . $education->id . ');" class="btn btn-danger">' . __('Delete') . '</a>
                </p>
              </div>
            </div>
            <!--education End-->';
            endforeach;
        endif;

        echo $html;
    }

    public function showFrontProfileEducation(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $html = '<div class="profile-education-list">';
        if (isset($user) && count($user->profileEducation)) {
            foreach ($user->profileEducation as $education) {
                $degree = $education->getDegreeLevel('degree_level');
                if ($education->getDegreeType('degree_type')) {
                    $degree .= ' (' . $education->getDegreeType('degree_type') . ')';
                }
                $city = $education->getCity('city');

                $html .= '<div class="profile-timeline-card" id="education_' . $education->id . '">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:10px;">
                        <div>
                            <h5 style="font-size:15.5px; font-weight:700; color:#0F172A; margin:0 0 3px 0;"><i class="fa fa-graduation-cap" style="color:#4F46E5;"></i> ' . e($education->degree_title) . '</h5>
                            <div style="font-size:13.5px; font-weight:600; color:#4F46E5; margin-bottom:4px;">' . e($education->institution) . '</div>
                            <div style="font-size:12.5px; color:#64748B;">' . e($degree) . ($education->date_completion ? ' &bull; Year: ' . e($education->date_completion) : '') . ($city ? ' &bull; ' . e($city) : '') . '</div>
                        </div>
                        <div class="profile-actions-wrap">
                            <a href="javascript:void(0);" onclick="showProfileEducationEditModal(' . $education->id . ',' . $education->state_id . ',' . $education->city_id . ',' . $education->degree_type_id . ');" class="btn-action-edit"><i class="fa fa-pencil"></i> ' . __('Edit') . '</a>
                            <a href="javascript:void(0);" onclick="delete_profile_education(' . $education->id . ');" class="btn-action-delete"><i class="fa fa-trash"></i> ' . __('Delete') . '</a>
                        </div>
                    </div>
                </div>';
            }
        } else {
            $html .= '<div class="profile-empty-state">
                <div class="profile-empty-state-text"><i class="fa fa-graduation-cap"></i> No education added yet. Add your degrees and qualifications to stand out.</div>
            </div>';
        }
        $html .= '</div>';
        echo $html;
    }

    public function showApplicantProfileEducation(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $html = '';
        if (isset($user) && count($user->profileEducation)) {
            $html .= '<div style="display: flex; flex-direction: column; gap: 14px;">';
            foreach ($user->profileEducation as $education) {
                $degLevel = $education->getDegreeLevel('degree_level');
                $degType  = $education->getDegreeType('degree_type');
                $parts = array_filter([$degLevel, $degType]);
                $degreeHeading = !empty($parts) ? implode(' - ', $parts) : ($education->degree_title ?: __('Education'));

                $majorSubjects = $education->getProfileEducationMajorSubjectsStr();
                $city = $education->getCity('city');
                $dateText = !empty($education->date_completion) ? $education->date_completion : '';

                $html .= '<div style="display: flex; gap: 16px; align-items: flex-start; padding: 16px; background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 14px; transition: all 0.2s ease;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: #F3E8FF; color: #7C3AED; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                        <i class="fa fa-graduation-cap"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 8px; margin-bottom: 4px;">
                            <h4 style="font-size: 15px; font-weight: 800; color: #0F172A; margin: 0; line-height: 1.3;">' . e($degreeHeading) . '</h4>
                            ' . (!empty($dateText) ? '<span style="background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; font-size: 11.5px; font-weight: 700; padding: 2px 10px; border-radius: 12px;"><i class="fa fa-calendar-check-o"></i> ' . e($dateText) . '</span>' : '') . '
                        </div>
                        ' . (!empty($education->degree_title) && $degreeHeading !== $education->degree_title ? '<div style="font-size: 13.5px; font-weight: 700; color: #2563EB; margin-bottom: 2px;">' . e($education->degree_title) . '</div>' : '') . '
                        <div style="font-size: 13px; font-weight: 600; color: #475569;">' . e($education->institution) . (!empty($city) ? ' &bull; <span style="color: #64748B;">' . e($city) . '</span>' : '') . '</div>
                        ' . (!empty($majorSubjects) ? '<div style="font-size: 12.5px; color: #64748B; margin-top: 4px;"><i class="fa fa-book"></i> ' . e($majorSubjects) . '</div>' : '') . '
                    </div>
                </div>';
            }
            $html .= '</div>';
        } else {
            $html .= '<p class="text-muted" style="padding: 6px 0; font-size: 13.5px; margin: 0;"><i class="fa fa-graduation-cap text-muted"></i> ' . __('No education records added yet.') . '</p>';
        }

        echo $html;
    }

    public function getProfileEducationForm(Request $request, $user_id)
    {

        $degreeLevels = DataArrayHelper::defaultDegreelevelsArray();
        $resultTypes = DataArrayHelper::defaultResultTypesArray();
        $majorSubjects = DataArrayHelper::defaultMajorSubjectsArray();
        $countries = DataArrayHelper::defaultCountriesArray();

        $profileEducationMajorSubjectIds = array();

        $user = User::find($user_id);
        $returnHTML = view('admin.user.forms.education.education_modal')
                ->with('user', $user)
                ->with('degreeLevels', $degreeLevels)
                ->with('resultTypes', $resultTypes)
                ->with('majorSubjects', $majorSubjects)
                ->with('profileEducationMajorSubjectIds', $profileEducationMajorSubjectIds)
                ->with('countries', $countries)
                ->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function getFrontProfileEducationForm(Request $request, $user_id)
    {

        $degreeLevels = DataArrayHelper::langDegreelevelsArray();
        $resultTypes = DataArrayHelper::langResultTypesArray();
        $majorSubjects = DataArrayHelper::langMajorSubjectsArray();
        $countries = DataArrayHelper::langCountriesArray();
        $degreeTypes = DataArrayHelper::langDegreeTypesArray(9); // default to Diploma or first level
        $profileEducationMajorSubjectIds = array();

        $user = User::find($user_id);
        $returnHTML = view('user.forms.education.education_modal')
                ->with('user', $user)
                ->with('degreeLevels', $degreeLevels)
                ->with('degreeTypes', $degreeTypes)
                ->with('resultTypes', $resultTypes)
                ->with('majorSubjects', $majorSubjects)
                ->with('profileEducationMajorSubjectIds', $profileEducationMajorSubjectIds)
                ->with('countries', $countries)
                ->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function storeProfileEducation(ProfileEducationFormRequest $request, $user_id)
    {

        $profileEducation = new ProfileEducation();
        $profileEducation = $this->assignEducationValues($profileEducation, $request, $user_id);
        $profileEducation->save();
        /*         * ************************************ */
        $this->storeprofileEducationMajorSubjects($request, $profileEducation->id);
        /*         * ************************************ */
        $returnHTML = view('admin.user.forms.education.education_thanks')->render();
        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);
    }

    public function storeFrontProfileEducation(ProfileEducationFormRequest $request, $user_id)
    {

        $profileEducation = new ProfileEducation();
        $profileEducation = $this->assignEducationValues($profileEducation, $request, $user_id);
        $profileEducation->save();
        /*         * ************************************ */
        $this->storeprofileEducationMajorSubjects($request, $profileEducation->id);
        /*         * ************************************ */
        $returnHTML = view('user.forms.education.education_thanks')->render();
        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);
    }

    private function assignEducationValues($profileEducation, $request, $user_id)
    {
        $profileEducation->user_id = $user_id;
        $profileEducation->degree_level_id = $request->input('degree_level_id');
        $profileEducation->degree_type_id = $request->input('degree_type_id');
        $profileEducation->degree_title = $request->input('degree_title') ?: ($request->input('institution') ? $request->input('degree_title', '') : '');
        $profileEducation->country_id = $request->input('country_id') ?: $this->getDefaultCountryId();
        $profileEducation->state_id = $request->input('state_id');
        $profileEducation->city_id = $request->input('city_id');
        $profileEducation->date_completion = $request->input('date_completion');
        $profileEducation->institution = $request->input('institution');
        $profileEducation->degree_result = $request->input('degree_result');
        $profileEducation->result_type_id = $request->input('result_type_id');
        return $profileEducation;
    }

    private function getDefaultCountryId()
    {
        return 101; // India or default country
    }

    public function getProfileEducationEditForm(Request $request, $user_id)
    {
        $education_id = $request->input('education_id');

        $degreeLevels = DataArrayHelper::defaultDegreelevelsArray();
        $resultTypes = DataArrayHelper::defaultResultTypesArray();
        $majorSubjects = DataArrayHelper::defaultMajorSubjectsArray();
        $countries = DataArrayHelper::defaultCountriesArray();

        $profileEducation = ProfileEducation::find($education_id);
        $profileEducationMajorSubjectIds = $profileEducation->getProfileEducationMajorSubjectsArray();

        $user = User::find($user_id);
        $returnHTML = view('admin.user.forms.education.education_edit_modal')
                ->with('user', $user)
                ->with('profileEducation', $profileEducation)
                ->with('degreeLevels', $degreeLevels)
                ->with('resultTypes', $resultTypes)
                ->with('majorSubjects', $majorSubjects)
                ->with('profileEducationMajorSubjectIds', $profileEducationMajorSubjectIds)
                ->with('countries', $countries)
                ->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function getFrontProfileEducationEditForm(Request $request, $user_id)
    {
        $education_id = $request->input('education_id');

        $degreeLevels = DataArrayHelper::langDegreelevelsArray();
        $resultTypes = DataArrayHelper::langResultTypesArray();
        $majorSubjects = DataArrayHelper::langMajorSubjectsArray();
        $countries = DataArrayHelper::langCountriesArray();

        $profileEducation = ProfileEducation::find($education_id);
        $degreeTypes = DataArrayHelper::langDegreeTypesArray($profileEducation->degree_level_id);
        $profileEducationMajorSubjectIds = $profileEducation->getProfileEducationMajorSubjectsArray();
        $user = User::find($user_id);

        $returnHTML = view('user.forms.education.education_edit_modal')
                ->with('user', $user)
                ->with('profileEducation', $profileEducation)
                ->with('degreeLevels', $degreeLevels)
                ->with('degreeTypes', $degreeTypes)
                ->with('resultTypes', $resultTypes)
                ->with('majorSubjects', $majorSubjects)
                ->with('profileEducationMajorSubjectIds', $profileEducationMajorSubjectIds)
                ->with('countries', $countries)
                ->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function updateProfileEducation(ProfileEducationFormRequest $request, $education_id, $user_id)
    {

        $profileEducation = ProfileEducation::find($education_id);
        $profileEducation = $this->assignEducationValues($profileEducation, $request, $user_id);
        $profileEducation->update();
        /*         * ************************************ */
        $this->storeprofileEducationMajorSubjects($request, $profileEducation->id);
        /*         * ************************************ */

        $returnHTML = view('admin.user.forms.education.education_edit_thanks')->render();
        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);
    }

    public function updateFrontProfileEducation(ProfileEducationFormRequest $request, $education_id, $user_id)
    {

        $profileEducation = ProfileEducation::find($education_id);
        $profileEducation = $this->assignEducationValues($profileEducation, $request, $user_id);
        $profileEducation->update();
        /*         * ************************************ */
        $this->storeprofileEducationMajorSubjects($request, $profileEducation->id);
        /*         * ************************************ */

        $returnHTML = view('user.forms.education.education_edit_thanks')->render();
        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);
    }

    private function storeprofileEducationMajorSubjects($request, $profile_education_id)
    {
        if ($request->has('major_subjects')) {
            ProfileEducationMajorSubject::where('profile_education_id', '=', $profile_education_id)->delete();
            $major_subjects = $request->input('major_subjects');
            foreach ($major_subjects as $major_subject_id) {
                $profileEducationMajorSubject = new ProfileEducationMajorSubject();
                $profileEducationMajorSubject->profile_education_id = $profile_education_id;
                $profileEducationMajorSubject->major_subject_id = $major_subject_id;
                $profileEducationMajorSubject->save();
            }
        }
    }

    public function deleteAllProfileEducation($user_id)
    {
        $profileEducations = ProfileEducation::where('user_id', '=', $user_id)->get();
        foreach ($profileEducations as $profileEducation) {
            echo $this->removeProfileEducation($profileEducation->id);
        }
    }

    public function deleteProfileEducation(Request $request)
    {
        $id = $request->input('id');
        echo $this->removeProfileEducation($id);
    }

    private function removeProfileEducation($id)
    {
        try {
            $profileEducation = ProfileEducation::findOrFail($id);
            ProfileEducationMajorSubject::where('profile_education_id', '=', $id)->delete();
            $profileEducation->delete();
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }

}
