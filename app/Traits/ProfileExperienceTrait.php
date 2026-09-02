<?php

namespace App\Traits;

use Auth;
use DB;
use Input;
use Carbon\Carbon;
use Redirect;
use App\User;
use App\ProfileExperience;
use App\Country;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\ProfileExperienceFormRequest;
use App\Helpers\DataArrayHelper;

trait ProfileExperienceTrait
{

    public function showProfileExperience(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $html = '';
        if (isset($user) && count($user->profileExperience)):
            foreach ($user->profileExperience as $experience):
                if ($experience->is_currently_working == 1)
                    $date_end = 'Currently working';
                else
                    $date_end = $experience->date_end->format('d M, Y');

                $html .= '<!--experience Start-->
            <div class="col-md-12" id="experience_' . $experience->id . '">
              <div class="mt-element-ribbon bg-grey-steel">
                <div class="ribbon ribbon-color-warning uppercase ">' . $experience->title . '</div>
                <p class="ribbon-content">
				' . $experience->company . '<br />               	
                ' . $experience->date_start->format('d M, Y') . ' - ' . $date_end . ' | ' . $experience->getCity('city') . '<br />
                ' . $experience->description . '<br />
                <a href="javascript:void(0);" onclick="showProfileExperienceEditModal(' . $experience->id . ',' . $experience->state_id . ',' . $experience->city_id . ');" class="btn btn-warning">' . __('Edit') . '</a>
				<a href="javascript:void(0);" onclick="delete_profile_experience(' . $experience->id . ');" class="btn btn-danger">' . __('Delete') . '</a>
                </p>
              </div>
            </div>
            <!--experience End-->';
            endforeach;
        endif;

        echo $html;
    }

    public function showFrontProfileExperience(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $html = '<div class="profile-experience-list">';
        if (isset($user) && count($user->profileExperience)) {
            foreach ($user->profileExperience as $experience) {
                $date_end = ($experience->is_currently_working == 1) ? '<span class="badge-default-cv" style="font-size:11px; padding:2px 8px;"><i class="fa fa-dot-circle-o"></i> Present</span>' : ($experience->date_end ? $experience->date_end->format('M Y') : '');
                $date_start = $experience->date_start ? $experience->date_start->format('M Y') : '';
                $city = $experience->getCity('city');

                $html .= '<div class="profile-timeline-card" id="experience_' . $experience->id . '">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:10px;">
                        <div>
                            <h5 style="font-size:15.5px; font-weight:700; color:#0F172A; margin:0 0 3px 0;">' . e($experience->title) . '</h5>
                            <div style="font-size:13.5px; font-weight:600; color:#03855c; margin-bottom:4px;"><i class="fa fa-building-o"></i> ' . e($experience->company) . '</div>
                            <div style="font-size:12.5px; color:#64748B;"><i class="fa fa-calendar"></i> ' . $date_start . ' - ' . $date_end . ($city ? ' &bull; <i class="fa fa-map-marker"></i> ' . e($city) : '') . '</div>
                        </div>
                        <div class="profile-actions-wrap">
                            <a href="javascript:void(0);" onclick="showProfileExperienceEditModal(' . $experience->id . ',' . $experience->state_id . ',' . $experience->city_id . ');" class="btn-action-edit"><i class="fa fa-pencil"></i> ' . __('Edit') . '</a>
                            <a href="javascript:void(0);" onclick="delete_profile_experience(' . $experience->id . ');" class="btn-action-delete"><i class="fa fa-trash"></i> ' . __('Delete') . '</a>
                        </div>
                    </div>
                    ' . (!empty($experience->description) ? '<div style="margin-top:10px; padding-top:10px; border-top:1px dashed #F1F5F9; font-size:13px; color:#475569; line-height:1.5;">' . nl2br(e($experience->description)) . '</div>' : '') . '
                </div>';
            }
        } else {
            $html .= '<div class="profile-empty-state">
                <div class="profile-empty-state-text"><i class="fa fa-briefcase"></i> No work experience added yet. Add past or current roles to build recruiter trust.</div>
            </div>';
        }
        $html .= '</div>';
        echo $html;
    }

    public function showApplicantProfileExperience(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $html = '';
        if (isset($user) && count($user->profileExperience)) {
            $html .= '<div style="display: flex; flex-direction: column; gap: 14px;">';
            foreach ($user->profileExperience as $experience) {
                if ($experience->is_currently_working == 1) {
                    $date_end = '<span style="background: #ECFDF5; color: #03855c; border: 1px solid #A7F3D0; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 10px;">' . __('Present') . '</span>';
                } else {
                    $date_end = $experience->date_end ? $experience->date_end->format('M Y') : '';
                }

                $cityStr = $experience->getCity('city') ? $experience->getCity('city') : '';
                $startDate = $experience->date_start ? $experience->date_start->format('M Y') : '';

                $html .= '<div style="display: flex; gap: 16px; align-items: flex-start; padding: 16px; background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 14px; transition: all 0.2s ease;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: #ECFDF5; color: #03855c; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                        <i class="fa fa-briefcase"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 8px; margin-bottom: 3px;">
                            <h4 style="font-size: 15px; font-weight: 800; color: #0F172A; margin: 0; line-height: 1.3;">' . e($experience->title) . '</h4>
                            <div style="font-size: 12px; color: #64748B; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="fa fa-calendar text-primary"></i> ' . $startDate . ' - ' . $date_end . '
                            </div>
                        </div>
                        <div style="font-size: 13.5px; font-weight: 700; color: #2563EB; margin-bottom: 2px;">' . e($experience->company) . (!empty($cityStr) ? ' &bull; <span style="font-weight: 500; color: #64748B;">' . e($cityStr) . '</span>' : '') . '</div>
                        ' . (!empty($experience->description) ? '<p style="font-size: 13px; color: #475569; margin: 6px 0 0 0; line-height: 1.5;">' . e($experience->description) . '</p>' : '') . '
                    </div>
                </div>';
            }
            $html .= '</div>';
        } else {
            $isFresher = ($user && $user->profile_type === 'fresher');
            $html .= '<p class="text-muted" style="padding: 6px 0; font-size: 13.5px; margin: 0;"><i class="fa fa-briefcase text-muted"></i> ' . ($isFresher ? __('Fresher / No prior work experience.') : __('No experience records added yet.')) . '</p>';
        }

        echo $html;
    }

    public function getProfileExperienceForm(Request $request, $user_id)
    {
        $countries = DataArrayHelper::defaultCountriesArray();

        $user = User::find($user_id);
        $returnHTML = view('admin.user.forms.experience.experience_modal')
                ->with('user', $user)
                ->with('countries', $countries)
                ->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function getFrontProfileExperienceForm(Request $request, $user_id)
    {
        $countries = DataArrayHelper::langCountriesArray();

        $user = User::find($user_id);
        $returnHTML = view('user.forms.experience.experience_modal')
                ->with('user', $user)
                ->with('countries', $countries)
                ->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function storeProfileExperience(ProfileExperienceFormRequest $request, $user_id)
    {

        $profileExperience = new ProfileExperience();
        $profileExperience = $this->assignExperienceValues($profileExperience, $request, $user_id);
        $profileExperience->save();

        $returnHTML = view('admin.user.forms.experience.experience_thanks')->render();
        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);
    }

    public function storeFrontProfileExperience(ProfileExperienceFormRequest $request, $user_id)
    {

        $profileExperience = new ProfileExperience();
        $profileExperience = $this->assignExperienceValues($profileExperience, $request, $user_id);
        $profileExperience->save();

        $returnHTML = view('user.forms.experience.experience_thanks')->render();
        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);
    }

    private function assignExperienceValues($profileExperience, $request, $user_id)
    {
        $profileExperience->user_id = $user_id;
        $profileExperience->title = $request->input('title');
        $profileExperience->company = $request->input('company');
        $profileExperience->country_id = $request->input('country_id');
        $profileExperience->state_id = $request->input('state_id');
        $profileExperience->city_id = $request->input('city_id');
        $profileExperience->date_start = $request->input('date_start');
        $profileExperience->date_end = $request->input('date_end');
        $profileExperience->is_currently_working = $request->input('is_currently_working');
        $profileExperience->description = $request->input('description');
        return $profileExperience;
    }

    public function getProfileExperienceEditForm(Request $request, $user_id)
    {
        $profile_experience_id = $request->input('profile_experience_id');

        $countries = DataArrayHelper::defaultCountriesArray();

        $profileExperience = ProfileExperience::find($profile_experience_id);
        $user = User::find($user_id);

        $returnHTML = view('admin.user.forms.experience.experience_edit_modal')
                ->with('user', $user)
                ->with('profileExperience', $profileExperience)
                ->with('countries', $countries)
                ->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function getFrontProfileExperienceEditForm(Request $request, $user_id)
    {
        $profile_experience_id = $request->input('profile_experience_id');
        $countries = DataArrayHelper::langCountriesArray();

        $profileExperience = ProfileExperience::find($profile_experience_id);
        $user = User::find($user_id);

        $returnHTML = view('user.forms.experience.experience_edit_modal')
                ->with('user', $user)
                ->with('profileExperience', $profileExperience)
                ->with('countries', $countries)
                ->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function updateProfileExperience(ProfileExperienceFormRequest $request, $profile_experience_id, $user_id)
    {

        $profileExperience = ProfileExperience::find($profile_experience_id);
        $profileExperience = $this->assignExperienceValues($profileExperience, $request, $user_id);
        $profileExperience->update();

        $returnHTML = view('admin.user.forms.experience.experience_edit_thanks')->render();
        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);
    }

    public function updateFrontProfileExperience(ProfileExperienceFormRequest $request, $profile_experience_id, $user_id)
    {

        $profileExperience = ProfileExperience::find($profile_experience_id);
        $profileExperience = $this->assignExperienceValues($profileExperience, $request, $user_id);
        $profileExperience->update();

        $returnHTML = view('user.forms.experience.experience_edit_thanks')->render();
        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);
    }

    public function deleteProfileExperience(Request $request)
    {
        $id = $request->input('id');
        try {
            $profileExperience = ProfileExperience::findOrFail($id);
            $profileExperience->delete();
            echo 'ok';
        } catch (ModelNotFoundException $e) {
            echo 'notok';
        }
    }

}
