<?php

namespace App\Http\Controllers\Admin;

use Auth;
use DB;
use Input;
use Redirect;
use App\Job;
use App\Company;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use DataTables;
use App\Http\Controllers\Controller;
use App\Traits\JobTrait;
use App\Helpers\MiscHelper;
use App\Helpers\DataArrayHelper;
use Illuminate\Support\Str;

class JobController extends Controller
{

    use JobTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function indexJobs()
    {
        $companies = DataArrayHelper::companiesArray();
        $countries = DataArrayHelper::defaultCountriesArray();
        return view('admin.job.index')
                        ->with('companies', $companies)
                        ->with('countries', $countries);
    }

    public function fetchJobsData(Request $request)
    {
        $jobs = Job::select([
                    'jobs.id', 'jobs.company_id', 'jobs.title', 'jobs.description', 'jobs.country_id', 'jobs.state_id', 'jobs.city_id', 'jobs.is_freelance', 'jobs.career_level_id', 'jobs.salary_from', 'jobs.salary_to', 'jobs.hide_salary', 'jobs.functional_area_id', 'jobs.job_type_id', 'jobs.job_shift_id', 'jobs.num_of_positions', 'jobs.gender_id', 'jobs.expiry_date', 'jobs.degree_level_id', 'jobs.job_experience_id', 'jobs.is_active', 'jobs.is_featured', 'jobs.slug',
        ]);
        return Datatables::of($jobs)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('company_id') && !empty($request->company_id)) {
                                $query->where('jobs.company_id', '=', "{$request->get('company_id')}");
                            }
                            if ($request->has('title') && !empty($request->title)) {
                                $query->where('jobs.title', 'like', "%{$request->get('title')}%");
                            }
                            if ($request->has('description') && !empty($request->description)) {
                                $query->where('jobs.description', 'like', "%{$request->get('description')}%");
                            }
                            if ($request->has('country_id') && !empty($request->country_id)) {
                                $query->where('jobs.country_id', '=', "{$request->get('country_id')}");
                            }
                            if ($request->has('state_id') && !empty($request->state_id)) {
                                $query->where('jobs.state_id', '=', "{$request->get('state_id')}");
                            }
                            if ($request->has('city_id') && !empty($request->city_id)) {
                                $query->where('jobs.city_id', '=', "{$request->get('city_id')}");
                            }
                            if ($request->has('is_active') && $request->is_active != -1) {
                                $query->where('jobs.is_active', '=', "{$request->get('is_active')}");
                            }
                            if ($request->has('is_featured') && $request->is_featured != -1) {
                                $query->where('jobs.is_featured', '=', "{$request->get('is_featured')}");
                            }
                        })
                        ->addColumn('checkbox', function ($jobs) {
                            return '<input type="checkbox" class="row-checkbox" value="' . $jobs->id . '" style="cursor:pointer;width:17px;height:17px;accent-color:#1B4FD8;" />';
                        })
                        ->addColumn('company_id', function ($jobs) {
                            $companyName = $jobs->getCompany('name') ?: 'Unknown Company';
                            return '<div style="display:flex;align-items:center;gap:8px;">
                                <span style="width:30px;height:30px;border-radius:8px;background:#EEF2FF;color:#1B4FD8;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;flex-shrink:0;">
                                    <i class="fa fa-building" style="font-size:13px;"></i>
                                </span>
                                <span style="font-weight:600;color:#0F172A;font-size:13px;">' . e($companyName) . '</span>
                            </div>';
                        })
                        ->addColumn('title', function ($jobs) {
                            $title = e(Str::limit($jobs->title, 60, '...'));
                            $isFeatured = (int) $jobs->is_featured == 1;
                            $isActive = (int) $jobs->is_active == 1;
                            
                            $badges = '';
                            if ($isFeatured) {
                                $badges .= ' <span class="label label-warning" style="font-size:10.5px;padding:2px 7px;"><i class="fa fa-star"></i> Featured</span>';
                            }
                            if ($isActive) {
                                $badges .= ' <span class="label label-success" style="font-size:10.5px;padding:2px 7px;">Active</span>';
                            } else {
                                $badges .= ' <span class="label label-danger" style="font-size:10.5px;padding:2px 7px;">Inactive</span>';
                            }
                            
                            return '<div style="font-weight:600;color:#1E293B;font-size:13.5px;line-height:1.4;">' . $title . '</div><div style="margin-top:4px;display:flex;gap:5px;flex-wrap:wrap;">' . $badges . '</div>';
                        })
                        ->addColumn('city_id', function ($jobs) {
                            $city = $jobs->getCity('city');
                            $state = $jobs->getState('state');
                            $country = $jobs->getCountry('country');
                            
                            $locParts = array_filter([$city, $state, $country]);
                            $locStr = !empty($locParts) ? implode(', ', $locParts) : 'All India / Remote';
                            
                            return '<div style="color:#475569;font-size:12.5px;display:flex;align-items:center;gap:5px;">
                                <i class="fa fa-map-marker" style="color:#E11D48;font-size:14px;"></i>
                                <span>' . e($locStr) . '</span>
                            </div>';
                        })
                        ->addColumn('description', function ($jobs) {
                            $desc = strip_tags($jobs->description);
                            return '<span style="color:#64748B;font-size:12.5px;line-height:1.4;display:inline-block;max-width:240px;">' . e(Str::limit($desc, 65, '...')) . '</span>';
                        })
                        ->addColumn('action', function ($jobs) {
                            /*                             * ************************* */
                            $activeTxt = 'Make Active';
                            $activeHref = 'makeActive(' . $jobs->id . ');';
                            $activeIcon = 'check-circle-o';
                            if ((int) $jobs->is_active == 1) {
                                $activeTxt = 'Make InActive';
                                $activeHref = 'makeNotActive(' . $jobs->id . ');';
                                $activeIcon = 'ban';
                            }
                            $featuredTxt = 'Make Featured';
                            $featuredHref = 'makeFeatured(' . $jobs->id . ');';
                            $featuredIcon = 'star-o';
                            if ((int) $jobs->is_featured == 1) {
                                $featuredTxt = 'Remove Featured';
                                $featuredHref = 'makeNotFeatured(' . $jobs->id . ');';
                                $featuredIcon = 'star';
                            }
                            $jobUrl = !empty($jobs->slug) ? route('job.detail', [$jobs->slug]) : url('job/' . $jobs->id);
                            return '
				<div style="display:inline-flex;align-items:center;justify-content:center;gap:6px;white-space:nowrap;">
                    <a href="' . $jobUrl . '" target="_blank" class="btn-job-view" title="View Job Live on Website">
                        <i class="fa fa-eye" aria-hidden="true"></i> View
                    </a>
					<div class="btn-group">
						<button type="button" class="btn-job-action dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Action <i class="fa fa-angle-down" aria-hidden="true"></i>
						</button>
						<ul class="dropdown-menu dropdown-menu-right job-action-menu" role="menu">
							<li>
								<a href="' . $jobUrl . '" target="_blank">
									<i class="fa fa-external-link" style="color:#0284C7;width:16px;"></i> View Job Live
								</a>
							</li>
							<li>
								<a href="' . route('edit.job', ['id' => $jobs->id]) . '">
									<i class="fa fa-pencil" style="color:#2563EB;width:16px;"></i> Edit Job
								</a>
							</li>						
							<li>
								<a href="javascript:void(0);" onclick="deleteJob(' . $jobs->id . ', ' . ($jobs->is_default ?? 0) . ');" style="color:#DC2626;">
									<i class="fa fa-trash-o" style="color:#DC2626;width:16px;"></i> Delete Job
								</a>
							</li>
							<li class="divider" style="margin:4px 0;"></li>
							<li>
								<a href="javascript:void(0);" onClick="' . $activeHref . '" id="onclickActive' . $jobs->id . '">
									<i class="fa fa-' . $activeIcon . '" style="width:16px;"></i> ' . $activeTxt . '
								</a>
							</li>
							<li>
								<a href="javascript:void(0);" onClick="' . $featuredHref . '" id="onclickFeatured' . $jobs->id . '">
									<i class="fa fa-' . $featuredIcon . '" style="width:16px;"></i> ' . $featuredTxt . '
								</a>
							</li>																																		
						</ul>
					</div>
				</div>';
                        })
                        ->rawColumns(['checkbox', 'company_id', 'title', 'city_id', 'description', 'action'])
                        ->setRowId(function($jobs) {
                            return 'jobDtRow' . $jobs->id;
                        })
                        ->make(true);
        //$query = $dataTable->getQuery()->get();
        //return $query;
    }

    public function bulkDeleteJobs(Request $request)
    {
        $ids = $request->input('ids');
        if (!empty($ids) && is_array($ids)) {
            try {
                DB::table('manage_job_skills')->whereIn('job_id', $ids)->delete();
                DB::table('job_apply')->whereIn('job_id', $ids)->delete();
                DB::table('favourites_job')->whereIn('job_id', $ids)->delete();
                Job::whereIn('id', $ids)->delete();
                return response()->json(['status' => 'ok', 'count' => count($ids)]);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
        return response()->json(['status' => 'error', 'message' => 'No items selected']);
    }

    public function makeActiveJob(Request $request)
    {
        $id = $request->input('id');
        try {
            $job = Job::findOrFail($id);
            $job->is_active = 1;
            $job->update();
            echo 'ok';
        } catch (ModelNotFoundException $e) {
            echo 'notok';
        }
    }

    public function makeNotActiveJob(Request $request)
    {
        $id = $request->input('id');
        try {
            $job = Job::findOrFail($id);
            $job->is_active = 0;
            $job->update();
            echo 'ok';
        } catch (ModelNotFoundException $e) {
            echo 'notok';
        }
    }

    public function makeFeaturedJob(Request $request)
    {
        $id = $request->input('id');
        try {
            $job = Job::findOrFail($id);
            $job->is_featured = 1;
            $job->update();
            echo 'ok';
        } catch (ModelNotFoundException $e) {
            echo 'notok';
        }
    }

    public function makeNotFeaturedJob(Request $request)
    {
        $id = $request->input('id');
        try {
            $job = Job::findOrFail($id);
            $job->is_featured = 0;
            $job->update();
            echo 'ok';
        } catch (ModelNotFoundException $e) {
            echo 'notok';
        }
    }

}
