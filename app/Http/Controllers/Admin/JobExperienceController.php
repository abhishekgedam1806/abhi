<?php

namespace App\Http\Controllers\Admin;

use Auth;
use DB;
use Input;
use File;
use Carbon\Carbon;
use ImgUploader;
use Redirect;
use App\JobExperience;
use App\Language;
use App\Helpers\MiscHelper;
use App\Helpers\DataArrayHelper;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use DataTables;
use App\Http\Requests\JobExperienceFormRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class JobExperienceController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function indexJobExperiences()
    {
        $languages = DataArrayHelper::languagesNativeCodeArray();
        return view('admin.job_experience.index')->with('languages', $languages);
    }

    public function createJobExperience()
    {
        $languages = DataArrayHelper::languagesNativeCodeArray();
        $jobExperiences = DataArrayHelper::defaultJobExperiencesArray();
        return view('admin.job_experience.add')
                        ->with('languages', $languages)
                        ->with('jobExperiences', $jobExperiences);
    }

    public function storeJobExperience(JobExperienceFormRequest $request)
    {
        $jobExperience = new JobExperience();
        $jobExperience->job_experience = $request->input('job_experience');
        $jobExperience->is_active = $request->input('is_active');
        $jobExperience->lang = $request->input('lang');
        $jobExperience->is_default = $request->input('is_default');
        $jobExperience->save();
        /*         * ************************************ */
        $jobExperience->sort_order = $jobExperience->id;
        if ((int) $request->input('is_default') == 1) {
            $jobExperience->job_experience_id = $jobExperience->id;
        } else {
            $jobExperience->job_experience_id = $request->input('job_experience_id');
        }
        $jobExperience->update();
        flash('Job Experience has been added!')->success();
        return \Redirect::route('edit.job.experience', array($jobExperience->id));
    }

    public function editJobExperience($id)
    {
        $languages = DataArrayHelper::languagesNativeCodeArray();
        $jobExperiences = DataArrayHelper::defaultJobExperiencesArray();
        $jobExperience = JobExperience::findOrFail($id);
        return view('admin.job_experience.edit')
                        ->with('languages', $languages)
                        ->with('jobExperience', $jobExperience)
                        ->with('jobExperiences', $jobExperiences);
    }

    public function updateJobExperience($id, JobExperienceFormRequest $request)
    {
        $jobExperience = JobExperience::findOrFail($id);
        $jobExperience->job_experience = $request->input('job_experience');
        $jobExperience->is_active = $request->input('is_active');
        $jobExperience->lang = $request->input('lang');
        $jobExperience->is_default = $request->input('is_default');
        if ((int) $request->input('is_default') == 1) {
            $jobExperience->job_experience_id = $jobExperience->id;
        } else {
            $jobExperience->job_experience_id = $request->input('job_experience_id');
        }
        $jobExperience->update();
        flash('Job Experience has been updated!')->success();
        return \Redirect::route('edit.job.experience', array($jobExperience->id));
    }

    public function deleteJobExperience(Request $request)
    {
        $id = $request->input('id');
        try {
            $jobExperience = JobExperience::findOrFail($id);
            if ((bool) $jobExperience->is_default) {
                JobExperience::where('job_experience_id', '=', $jobExperience->job_experience_id)->delete();
            } else {
                $jobExperience->delete();
            }
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }

    public function fetchJobExperiencesData(Request $request)
    {
        $jobExperiences = JobExperience::select(['job_experiences.id', 'job_experiences.job_experience', 'job_experiences.is_active', 'job_experiences.lang', 'job_experiences.is_default', 'job_experiences.created_at', 'job_experiences.updated_at'])->sorted();
        return Datatables::of($jobExperiences)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('job_experience') && !empty($request->job_experience)) {
                                $query->where('job_experiences.job_experience', 'like', "%{$request->get('job_experience')}%");
                            }
                            if ($request->has('lang') && !empty($request->get('lang'))) {
                                $query->where('job_experiences.lang', 'like', "%{$request->get('lang')}%");
                            }
                            if ($request->has('is_active') && $request->get('is_active') != -1) {
                                $query->where('job_experiences.is_active', '=', "{$request->get('is_active')}");
                            }
                        })
                        ->addColumn('checkbox', function ($jobExperiences) {
                            return '<input type="checkbox" class="row-checkbox" value="' . $jobExperiences->id . '" style="cursor:pointer;width:17px;height:17px;" />';
                        })
                        ->addColumn('job_experience', function ($jobExperiences) {
                            $jobExperience = Str::limit($jobExperiences->job_experience, 100, '...');
                            $direction = MiscHelper::getLangDirection($jobExperiences->lang);
                            return '<span dir="' . $direction . '">' . $jobExperience . '</span>';
                        })
                        ->addColumn('action', function ($jobExperiences) {
                            /*                             * ************************* */
                            $activeTxt = 'Make Active';
                            $activeHref = 'makeActive(' . $jobExperiences->id . ');';
                            $activeIcon = 'square-o';
                            if ((int) $jobExperiences->is_active == 1) {
                                $activeTxt = 'Make InActive';
                                $activeHref = 'makeNotActive(' . $jobExperiences->id . ');';
                                $activeIcon = 'check-square-o';
                            }
                            return '
				<div class="btn-group" style="white-space:nowrap;">
					<button class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background:#4F46E5;border-color:#4F46E5;color:#fff;font-size:12px;font-weight:600;padding:5px 11px;border-radius:5px;display:inline-flex;align-items:center;gap:5px;">
                        Action <i class="fa fa-angle-down" aria-hidden="true"></i>
					</button>
					<ul class="dropdown-menu dropdown-menu-right" style="min-width:150px;padding:6px 0;border-radius:8px;box-shadow:0 10px 25px -5px rgba(0,0,0,0.12);border:1px solid #e2e8f0;z-index:10050;">
						<li>
							<a href="' . route('edit.job.experience', ['id' => $jobExperiences->id]) . '" style="padding:8px 14px;display:flex;align-items:center;gap:8px;font-size:12.5px;color:#334155;"><i class="fa fa-pencil text-primary" style="width:16px;"></i> Edit</a>
						</li>						
						<li>
							<a href="javascript:void(0);" onclick="deleteJobExperience(' . $jobExperiences->id . ', ' . $jobExperiences->is_default . ');" style="padding:8px 14px;display:flex;align-items:center;gap:8px;font-size:12.5px;color:#dc2626;"><i class="fa fa-trash-o text-danger" style="width:16px;"></i> Delete</a>
						</li>
                        <li class="divider" style="margin:4px 0;"></li>
						<li>
						<a href="javascript:void(0);" onClick="' . $activeHref . '" id="onclickActive' . $jobExperiences->id . '" style="padding:8px 14px;display:flex;align-items:center;gap:8px;font-size:12.5px;color:#334155;"><i class="fa fa-' . $activeIcon . '" style="width:16px;"></i> ' . $activeTxt . '</a>
						</li>																																		
					</ul>
				</div>';
                        })
                        ->rawColumns(['checkbox', 'job_experience', 'action'])
                        ->setRowId(function($jobExperiences) {
                            return 'jobExperienceDtRow' . $jobExperiences->id;
                        })
                        ->make(true);
        //$query = $dataTable->getQuery()->get();
        //return $query;
    }

    public function bulkDeleteJobExperiences(Request $request)
    {
        $ids = $request->input('ids');
        if (!empty($ids) && is_array($ids)) {
            try {
                $je_ids = JobExperience::whereIn('id', $ids)->pluck('job_experience_id')->filter()->toArray();
                if (!empty($je_ids)) {
                    JobExperience::whereIn('job_experience_id', $je_ids)->delete();
                }
                JobExperience::whereIn('id', $ids)->delete();
                return response()->json(['status' => 'ok', 'count' => count($ids)]);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
        return response()->json(['status' => 'error', 'message' => 'No items selected']);
    }

    public function makeActiveJobExperience(Request $request)
    {
        $id = $request->input('id');
        try {
            $jobExperience = JobExperience::findOrFail($id);
            $jobExperience->is_active = 1;
            $jobExperience->update();
            echo 'ok';
        } catch (ModelNotFoundException $e) {
            echo 'notok';
        }
    }

    public function makeNotActiveJobExperience(Request $request)
    {
        $id = $request->input('id');
        try {
            $jobExperience = JobExperience::findOrFail($id);
            $jobExperience->is_active = 0;
            $jobExperience->update();
            echo 'ok';
        } catch (ModelNotFoundException $e) {
            echo 'notok';
        }
    }

    public function sortJobExperiences()
    {
        $languages = DataArrayHelper::languagesNativeCodeArray();
        return view('admin.job_experience.sort')->with('languages', $languages);
    }

    public function jobExperienceSortData(Request $request)
    {
        $lang = $request->input('lang');
        $jobExperiences = JobExperience::select('job_experiences.id', 'job_experiences.job_experience', 'job_experiences.sort_order')
                ->where('job_experiences.lang', 'like', $lang)
                ->orderBy('job_experiences.sort_order')
                ->get();
        $str = '<ul id="sortable">';
        if ($jobExperiences != null) {
            foreach ($jobExperiences as $jobExperience) {
                $str .= '<li id="' . $jobExperience->id . '"><i class="fa fa-sort"></i>' . $jobExperience->job_experience . '</li>';
            }
        }
        echo $str . '</ul>';
    }

    public function jobExperienceSortUpdate(Request $request)
    {
        $jobExperienceOrder = $request->input('jobExperienceOrder');
        $jobExperienceOrderArray = explode(',', $jobExperienceOrder);
        $count = 1;
        foreach ($jobExperienceOrderArray as $jobExperienceId) {
            $jobExperience = JobExperience::find($jobExperienceId);
            $jobExperience->sort_order = $count;
            $jobExperience->update();
            $count++;
        }
    }

}
