<?php

namespace App\Traits;

use File;
use ImgUploader;
use Auth;
use DB;
use Input;
use Carbon\Carbon;
use Redirect;
use App\User;
use App\ProfileCv;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\ProfileCvFormRequest;
use App\Http\Requests\ProfileCvFileFormRequest;

trait ProfileCvsTrait
{

    public function showProfileCvs(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $html = '';
        if (isset($user) && count($user->profileCvs)) {
            $html .= '<div class="profile-cvs-list-container">';
            foreach ($user->profileCvs as $cv) {
                $defaultBadge = ($cv->is_default == 1) 
                    ? '<span class="badge-default-cv"><i class="fa fa-check-circle"></i> ' . __('Default CV') . '</span>' 
                    : '<span class="badge-additional-cv">' . __('Additional') . '</span>';

                $fileExists = (!empty($cv->cv_file) && file_exists(ImgUploader::real_public_path() . "cvs/$cv->cv_file"));
                $fileUrl = $fileExists ? ImgUploader::public_path() . "cvs/$cv->cv_file" : 'javascript:;';
                $cvTitle = e($cv->title ?: 'My Resume');

                $html .= '<div class="profile-cv-card-item" id="cv_' . $cv->id . '">
                    <div class="cv-card-main-info">
                        <div class="cv-card-icon">
                            <i class="fa fa-file-pdf-o"></i>
                        </div>
                        <div class="cv-card-details">
                            <a href="' . $fileUrl . '" ' . ($fileExists ? 'target="_blank"' : '') . ' class="cv-card-title-link">
                                ' . $cvTitle . '
                            </a>
                            <div class="cv-card-subtitle">' . ($fileExists ? '<i class="fa fa-download"></i> ' . __('Click to view & download') : __('File attached')) . '</div>
                        </div>
                    </div>
                    <div class="cv-card-meta-actions">
                        <div class="cv-card-status">' . $defaultBadge . '</div>
                        <div class="profile-actions-wrap">
                            <a href="javascript:;" onclick="showProfileCvEditModal(' . $cv->id . ');" class="btn-action-edit"><i class="fa fa-pencil"></i> ' . __('Edit') . '</a>
                            <a href="javascript:;" onclick="delete_profile_cv(' . $cv->id . ');" class="btn-action-delete"><i class="fa fa-trash"></i> ' . __('Delete') . '</a>
                        </div>
                    </div>
                </div>';
            }
            $html .= '</div>';
        } else {
            $html .= '<div class="profile-empty-state">
                <div class="profile-empty-state-text"><i class="fa fa-file-text-o"></i> ' . __('No resume uploaded yet. Upload your CV to apply for jobs with 1-click.') . '</div>
            </div>';
        }

        echo $html;
    }

    public function uploadCvFile($request)
    {
        $fileName = '';
        if ($request->hasFile('cv_file')) {
            $cv_file = $request->file('cv_file');
            $title = $request->input('title');
            if (empty($title)) {
                $title = pathinfo($cv_file->getClientOriginalName(), PATHINFO_FILENAME);
            }
            $fileName = ImgUploader::UploadDoc('cvs', $cv_file, $title);
        }
        return $fileName;
    }

    public function getFrontProfileCvForm(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $returnHTML = view('user.forms.cv.cv_modal')->with('user', $user)->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function getProfileCvForm(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $returnHTML = view('admin.user.forms.cv.cv_modal')->with('user', $user)->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function storeProfileCv(ProfileCvFormRequest $request, $user_id)
    {

        $profileCv = new ProfileCv();
        $profileCv = $this->assignValues($profileCv, $request, $user_id);
        $profileCv->save();

        $returnHTML = view('admin.user.forms.cv.cv_thanks')->render();
        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);
    }

    public function storeFrontProfileCv(ProfileCvFormRequest $request, $user_id)
    {

        $profileCv = new ProfileCv();
        $profileCv = $this->assignValues($profileCv, $request, $user_id);
        $profileCv->save();

        $returnHTML = view('user.forms.cv.cv_thanks')->render();
        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);
    }

    private function assignValues($profileCv, $request, $user_id)
    {
        $profileCv->user_id = $user_id;
        $title = $request->input('title');
        if (empty($title) && $request->hasFile('cv_file')) {
            $origName = $request->file('cv_file')->getClientOriginalName();
            $title = pathinfo($origName, PATHINFO_FILENAME);
        }
        if (empty($title)) {
            $user = User::find($user_id);
            $title = ($user ? $user->getName() : 'My') . ' Resume';
        }
        $profileCv->title = $title;
        $profileCv->is_default = (int) $request->input('is_default', 1);

        /*         * ************************************ */
        if ((int) $profileCv->is_default == 1 && $profileCv->id > 0) {
            $this->updateDefaultCv($profileCv->id);
        }
        /*         * ************************************ */

        if ($request->hasFile('cv_file')) {
            if ($profileCv->id > 0) {
                $this->deleteCv($profileCv->id);
            }
            $profileCv->cv_file = $this->uploadCvFile($request);
        }

        return $profileCv;
    }

    public function getProfileCvEditForm(Request $request, $user_id)
    {
        $cv_id = $request->input('cv_id');
        $profileCv = ProfileCv::find($cv_id);
        $user = User::find($user_id);
        $returnHTML = view('admin.user.forms.cv.cv_edit_modal')
                ->with('user', $user)
                ->with('profileCv', $profileCv)
                ->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function getFrontProfileCvEditForm(Request $request, $user_id)
    {
        $cv_id = $request->input('cv_id');
        $profileCv = ProfileCv::find($cv_id);
        $user = User::find($user_id);
        $returnHTML = view('user.forms.cv.cv_edit_modal')
                ->with('user', $user)
                ->with('profileCv', $profileCv)
                ->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    public function updateProfileCv(ProfileCvFormRequest $request, $cv_id, $user_id)
    {

        $profileCv = ProfileCv::find($cv_id);
        $profileCv = $this->assignValues($profileCv, $request, $user_id);
        $profileCv->update();

        $returnHTML = view('admin.user.forms.cv.cv_edit_thanks')->render();
        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);
    }

    public function updateFrontProfileCv(ProfileCvFormRequest $request, $cv_id, $user_id)
    {

        $profileCv = ProfileCv::find($cv_id);
        $profileCv = $this->assignValues($profileCv, $request, $user_id);
        $profileCv->update();

        $returnHTML = view('user.forms.cv.cv_edit_thanks')->render();
        return response()->json(array('success' => true, 'status' => 200, 'html' => $returnHTML), 200);
    }

    public function makeDefaultCv(Request $request)
    {
        $id = $request->input('id');
        try {
            $profileCv = ProfileCv::findOrFail($id);
            $profileCv->is_default = 1;
            $profileCv->update();
            $this->updateDefaultCv($id);
            echo 'ok';
        } catch (ModelNotFoundException $e) {
            echo 'notok';
        }
    }

    private function updateDefaultCv($cv_id)
    {
        ProfileCv::where('is_default', '=', 1)->where('id', '<>', $cv_id)->update(['is_default' => 0]);
    }

    public function deleteAllProfileCvs($user_id)
    {
        $profileCvs = ProfileCv::where('user_id', '=', $user_id)->get();
        foreach ($profileCvs as $profileCv) {
            echo $this->removeProfileCv($profileCv->id);
        }
    }

    public function deleteProfileCv(Request $request)
    {
        $id = $request->input('id');
        echo $this->removeProfileCv($id);
    }

    private function removeProfileCv($id)
    {
        try {
            $this->deleteCv($id);
            $profileCv = ProfileCv::findOrFail($id);
            $profileCv->delete();
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }

    private function deleteCv($id)
    {
        try {
            $profileCv = ProfileCv::findOrFail($id);
            $file = $profileCv->cv_file;
            if (!empty($file)) {
                File::delete(ImgUploader::real_public_path() . 'cvs/' . $file);
            }
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }

}
