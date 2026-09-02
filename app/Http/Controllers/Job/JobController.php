<?php

namespace App\Http\Controllers\Job;

use Auth;
use DB;
use Input;
use Redirect;
use Carbon\Carbon;
use App\Job;
use App\JobApply;
use App\FavouriteJob;
use App\Company;
use App\JobSkill;
use App\JobSkillManager;
use App\Country;
use App\CountryDetail;
use App\State;
use App\City;
use App\CareerLevel;
use App\FunctionalArea;
use App\JobType;
use App\JobShift;
use App\Gender;
use App\JobExperience;
use App\DegreeLevel;
use App\ProfileCv;
use App\Helpers\MiscHelper;
use App\Helpers\DataArrayHelper;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use DataTables;
use App\Http\Requests\JobFormRequest;
use App\Http\Requests\Front\ApplyJobFormRequest;
use App\Http\Controllers\Controller;
use App\Traits\FetchJobs;
use App\Events\JobApplied;

class JobController extends Controller
{

    //use Skills;
    use FetchJobs;

    private $functionalAreas = '';
    private $countries = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['jobsBySearch', 'jobDetail', 'jobsByCity', 'jobsByCategory', 'jobsByCategoryAndCity', 'applyJob', 'postApplyJob', 'getAreasByCity']]);

        $this->functionalAreas = DataArrayHelper::langFunctionalAreasArray();
        $this->countries = DataArrayHelper::langCountriesArray();
    }

    public function jobsBySearch(Request $request)
    {
        $search = $request->input('search', '');
        $job_titles = (array) $request->input('job_title', array());
        $company_ids = (array) $request->input('company_id', array());
        $industry_ids = (array) $request->input('industry_id', array());
        $job_skill_ids = (array) $request->input('job_skill_id', array());
        $functional_area_ids = (array) $request->input('functional_area_id', array());
        $country_ids = (array) $request->input('country_id', array());
        $state_ids = (array) $request->input('state_id', array());
        $city_ids = (array) $request->input('city_id', array());
        $is_freelance = (array) $request->input('is_freelance', array());
        $career_level_ids = (array) $request->input('career_level_id', array());
        $job_type_ids = (array) $request->input('job_type_id', array());
        $job_shift_ids = (array) $request->input('job_shift_id', array());
        $gender_ids = (array) $request->input('gender_id', array());
        $degree_level_ids = (array) $request->input('degree_level_id', array());
        $job_experience_ids = (array) $request->input('job_experience_id', array());
        $salary_from = $request->input('salary_from', '');
        $salary_to = $request->input('salary_to', '');
        $salary_currency = $request->input('salary_currency', '');
        $is_featured = $request->input('is_featured', 2);
        $order_by = $request->input('order_by', 'id');
        $limit = 15;
        
        $jobs = $this->fetchJobs($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, $order_by, $limit);

        $facets = $this->fetchFacetSummary($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured);

        $jobTitlesArray = $facets['jobTitlesArray'];
        $jobIdsArray = $facets['jobIdsArray'];
        $skillIdsArray = $this->fetchSkillIdsArray($jobIdsArray);
        $countryIdsArray = $facets['countryIdsArray'];
        $stateIdsArray = $facets['stateIdsArray'];
        $cityIdsArray = $facets['cityIdsArray'];
        $companyIdsArray = $facets['companyIdsArray'];
        $industryIdsArray = $this->fetchIndustryIdsArray($companyIdsArray);
        $functionalAreaIdsArray = $facets['functionalAreaIdsArray'];
        $careerLevelIdsArray = $facets['careerLevelIdsArray'];
        $jobTypeIdsArray = $facets['jobTypeIdsArray'];
        $jobShiftIdsArray = $facets['jobShiftIdsArray'];
        $genderIdsArray = $facets['genderIdsArray'];
        $degreeLevelIdsArray = $facets['degreeLevelIdsArray'];
        $jobExperienceIdsArray = $facets['jobExperienceIdsArray'];

        /*         * ************************************************** */

        $seoArray = $this->getSEO($functional_area_ids, $country_ids, $state_ids, $city_ids, $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids);

        /*         * ************************************************** */

        $currencies = DataArrayHelper::currenciesArray();

        /*         * ************************************************** */

        $seoTitle = $seoArray['description'];
        $seoDesc = $seoArray['description'];
        $seoKeywords = $seoArray['keywords'];

        if ($request->filled('seo_category_name') && $request->filled('seo_city_name')) {
            $cat = $request->input('seo_category_name');
            $cit = $request->input('seo_city_name');
            $seoTitle = "{$cat} Jobs in {$cit} | Latest Vacancies & Hiring";
            $seoDesc = "Explore and apply for the latest {$cat} jobs in {$cit}. View salary, eligibility, hiring companies and job vacancies.";
            $seoKeywords = "{$cat} jobs in {$cit}, {$cit} {$cat} vacancies, careers in {$cit}, employment in {$cit}";
        } elseif ($request->filled('seo_city_name')) {
            $cit = $request->input('seo_city_name');
            $seoTitle = "Jobs in {$cit} | Latest Job Vacancies & Careers";
            $seoDesc = "Find and apply for top jobs in {$cit}. Browse fresher, experienced, work from home, and full-time vacancies.";
            $seoKeywords = "jobs in {$cit}, vacancies in {$cit}, careers {$cit}, hiring in {$cit}";
        } elseif ($request->filled('seo_category_name')) {
            $cat = $request->input('seo_category_name');
            $seoTitle = "{$cat} Jobs | Latest Careers & Vacancies";
            $seoDesc = "Browse the latest {$cat} jobs and vacancies. Apply now to top hiring employers and companies.";
            $seoKeywords = "{$cat} jobs, {$cat} vacancies, {$cat} careers, {$cat} hiring";
        }

        $seo = (object) array(
                    'seo_title' => $seoTitle,
                    'seo_description' => $seoDesc,
                    'seo_keywords' => $seoKeywords,
                    'seo_other' => ''
        );
        return view('job.list')
                        ->with('functionalAreas', $this->functionalAreas)
                        ->with('countries', $this->countries)
                        ->with('currencies', array_unique($currencies))
                        ->with('jobs', $jobs)
                        ->with('jobTitlesArray', $jobTitlesArray)
                        ->with('skillIdsArray', $skillIdsArray)
                        ->with('countryIdsArray', $countryIdsArray)
                        ->with('stateIdsArray', $stateIdsArray)
                        ->with('cityIdsArray', $cityIdsArray)
                        ->with('companyIdsArray', $companyIdsArray)
                        ->with('industryIdsArray', $industryIdsArray)
                        ->with('functionalAreaIdsArray', $functionalAreaIdsArray)
                        ->with('careerLevelIdsArray', $careerLevelIdsArray)
                        ->with('jobTypeIdsArray', $jobTypeIdsArray)
                        ->with('jobShiftIdsArray', $jobShiftIdsArray)
                        ->with('genderIdsArray', $genderIdsArray)
                        ->with('degreeLevelIdsArray', $degreeLevelIdsArray)
                        ->with('jobExperienceIdsArray', $jobExperienceIdsArray)
                        ->with('seo', $seo);
    }

    public function jobsByCity($city_slug, Request $request)
    {
        $cityInfo = $this->resolveCityInfo($city_slug);
        
        if (!empty($cityInfo['city_ids'])) {
            $request->merge(['city_id' => $cityInfo['city_ids']]);
        } else {
            $request->merge(['search' => $cityInfo['name']]);
        }
        $request->merge(['seo_city_name' => $cityInfo['name']]);
        return $this->jobsBySearch($request);
    }

    public function jobsByCategory($category_slug, Request $request)
    {
        $resolved = $this->resolveCategoryInfo($category_slug);
        if (!empty($resolved['functional_area_id'])) {
            $request->merge(['functional_area_id' => $resolved['functional_area_id']]);
        }
        if (!empty($resolved['search'])) {
            $request->merge(['search' => $resolved['search']]);
        }
        $request->merge(['seo_category_name' => $resolved['name']]);
        return $this->jobsBySearch($request);
    }

    public function jobsByCategoryAndCity($param1, $param2, Request $request)
    {
        $cityInfo1 = $this->resolveCityInfo($param1);
        $cityInfo2 = $this->resolveCityInfo($param2);

        if (!empty($cityInfo1['city_ids'])) {
            $cityInfo = $cityInfo1;
            $catParam = $param2;
        } elseif (!empty($cityInfo2['city_ids'])) {
            $cityInfo = $cityInfo2;
            $catParam = $param1;
        } else {
            $cityInfo = $cityInfo1;
            $catParam = $param2;
        }

        $resolved = $this->resolveCategoryInfo($catParam);

        if (!empty($cityInfo['city_ids'])) {
            $request->merge(['city_id' => $cityInfo['city_ids']]);
        } else {
            $request->merge(['search' => $cityInfo['name']]);
        }

        if (!empty($resolved['functional_area_id'])) {
            $request->merge(['functional_area_id' => $resolved['functional_area_id']]);
        }
        if (!empty($resolved['search'])) {
            $request->merge(['search' => $resolved['search']]);
        }

        $request->merge([
            'seo_city_name' => $cityInfo['name'],
            'seo_category_name' => $resolved['name']
        ]);
        return $this->jobsBySearch($request);
    }

    private function resolveCityInfo($slug)
    {
        $clean = str_replace(['-', '_'], ' ', strtolower(trim($slug)));

        // City synonyms & popular metro aliases
        $synonyms = [
            'bangalore' => ['name' => 'Bengaluru', 'slugs' => ['bengaluru', 'bangalore']],
            'bengaluru' => ['name' => 'Bengaluru', 'slugs' => ['bengaluru', 'bangalore']],
            'mumbai' => ['name' => 'Mumbai', 'slugs' => ['mumbai', 'bombay']],
            'bombay' => ['name' => 'Mumbai', 'slugs' => ['mumbai', 'bombay']],
            'pune' => ['name' => 'Pune', 'slugs' => ['pune', 'poona']],
            'nagpur' => ['name' => 'Nagpur', 'slugs' => ['nagpur']],
            'delhi' => ['name' => 'Delhi', 'slugs' => ['delhi', 'new delhi']],
            'new delhi' => ['name' => 'New Delhi', 'slugs' => ['new delhi', 'delhi']],
            'hyderabad' => ['name' => 'Hyderabad', 'slugs' => ['hyderabad']],
            'chennai' => ['name' => 'Chennai', 'slugs' => ['chennai', 'madras']],
            'kolkata' => ['name' => 'Kolkata', 'slugs' => ['kolkata', 'calcutta']],
            'gurgaon' => ['name' => 'Gurugram', 'slugs' => ['gurugram', 'gurgaon']],
            'gurugram' => ['name' => 'Gurugram', 'slugs' => ['gurugram', 'gurgaon']],
            'noida' => ['name' => 'Noida', 'slugs' => ['noida']],
            'ahmedabad' => ['name' => 'Ahmedabad', 'slugs' => ['ahmedabad']],
        ];

        if (isset($synonyms[$clean])) {
            $info = $synonyms[$clean];
            $cityIds = City::where(function($q) use ($info) {
                foreach ($info['slugs'] as $s) {
                    $q->orWhere('city', 'like', "%{$s}%");
                }
            })->pluck('city_id')->toArray();

            return [
                'city_ids' => !empty($cityIds) ? $cityIds : [],
                'name' => $info['name']
            ];
        }

        // Direct lookup in City table
        $cities = City::where('city', 'like', "%{$clean}%")->get();
        if ($cities->isNotEmpty()) {
            return [
                'city_ids' => $cities->pluck('city_id')->toArray(),
                'name' => $cities->first()->city
            ];
        }

        return [
            'city_ids' => [-999999], // Unmatched city returns 0 jobs cleanly instead of all jobs
            'name' => ucwords($clean)
        ];
    }

    private function resolveCategoryInfo($slug)
    {
        $clean = str_replace(['-', '_'], ' ', strtolower(trim($slug)));
        
        // 1. Direct slug / name match
        $fa = FunctionalArea::where('functional_area', 'like', "%{$clean}%")->first();
        if ($fa) {
            return [
                'functional_area_id' => [$fa->functional_area_id ?? $fa->id],
                'name' => $fa->functional_area
            ];
        }

        // 2. Keyword check for popular industries/roles
        $keywords = [
            'software' => ['name' => 'IT & Software', 'fa_ids' => [587, 431, 365, 126, 127], 'kw' => 'Software'],
            'it' => ['name' => 'IT & Software', 'fa_ids' => [587, 431, 365, 126, 127], 'kw' => 'IT'],
            'developer' => ['name' => 'Developer', 'fa_ids' => [587, 332, 126], 'kw' => 'Developer'],
            'accounts' => ['name' => 'Accounts & Finance', 'fa_ids' => [589, 1, 594], 'kw' => 'Account'],
            'finance' => ['name' => 'Accounts & Finance', 'fa_ids' => [589, 1, 594], 'kw' => 'Finance'],
            'sales' => ['name' => 'Sales & Marketing', 'fa_ids' => [118, 439, 440, 377, 70], 'kw' => 'Sales'],
            'marketing' => ['name' => 'Marketing', 'fa_ids' => [377, 69, 70, 378], 'kw' => 'Marketing'],
            'bpo' => ['name' => 'BPO & Customer Support', 'fa_ids' => [588, 17, 428], 'kw' => 'Customer Support'],
            'customer' => ['name' => 'Customer Support', 'fa_ids' => [588, 17, 428], 'kw' => 'Customer'],
            'telecaller' => ['name' => 'Telecaller', 'fa_ids' => [588, 434, 17], 'kw' => 'Telecaller'],
            'hr' => ['name' => 'HR & Recruitment', 'fa_ids' => [590, 411, 413], 'kw' => 'HR'],
            'delivery' => ['name' => 'Logistics & Delivery', 'fa_ids' => [592, 376], 'kw' => 'Delivery'],
            'driver' => ['name' => 'Driving', 'fa_ids' => [591], 'kw' => 'Driver'],
            'back office' => ['name' => 'Back Office', 'fa_ids' => [345, 398, 3], 'kw' => 'Back Office'],
        ];

        foreach ($keywords as $kw => $info) {
            if (strpos($clean, $kw) !== false) {
                return [
                    'functional_area_id' => $info['fa_ids'],
                    'search' => $info['kw'],
                    'name' => $info['name']
                ];
            }
        }

        return [
            'search' => $clean,
            'name' => ucwords($clean)
        ];
    }

    public function jobDetail(Request $request, $job_slug)
    {

        $job = Job::where('slug', 'like', $job_slug)->firstOrFail();
        /*         * ************************************************** */
        $search = '';
        $job_titles = array();
        $company_ids = array();
        $industry_ids = array();
        $job_skill_ids = (array) $job->getJobSkillsArray();
        $functional_area_ids = (array) $job->getFunctionalArea('functional_area_id');
        $country_ids = (array) $job->getCountry('country_id');
        $state_ids = (array) $job->getState('state_id');
        $city_ids = (array) $job->getCity('city_id');
        $is_freelance = $job->is_freelance;
        $career_level_ids = (array) $job->getCareerLevel('career_level_id');
        $job_type_ids = (array) $job->getJobType('job_type_id');
        $job_shift_ids = (array) $job->getJobShift('job_shift_id');
        $gender_ids = (array) $job->getGender('gender_id');
        $degree_level_ids = (array) $job->getDegreeLevel('degree_level_id');
        $job_experience_ids = (array) $job->getJobExperience('job_experience_id');
        $salary_from = 0;
        $salary_to = 0;
        $salary_currency = '';
        $is_featured = 2;
        $order_by = 'id';
        $limit = 5;

        $relatedJobs = $this->fetchJobs($search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, $order_by, $limit);
        /*         * ***************************************** */

        $seoArray = $this->getSEO((array) $job->functional_area_id, (array) $job->country_id, (array) $job->state_id, (array) $job->city_id, (array) $job->career_level_id, (array) $job->job_type_id, (array) $job->job_shift_id, (array) $job->gender_id, (array) $job->degree_level_id, (array) $job->job_experience_id);
        /*         * ************************************************** */
        $seo = (object) array(
                    'seo_title' => $job->title,
                    'seo_description' => $seoArray['description'],
                    'seo_keywords' => $seoArray['keywords'],
                    'seo_other' => ''
        );
        return view('job.detail')
                        ->with('job', $job)
                        ->with('relatedJobs', $relatedJobs)
                        ->with('seo', $seo);
    }

    /*     * ************************************************** */

    public function addToFavouriteJob(Request $request, $job_slug)
    {
        $data['job_slug'] = $job_slug;
        $data['user_id'] = Auth::user()->id;
        $data_save = FavouriteJob::create($data);
        flash(__('Job has been added in favorites list'))->success();
        return \Redirect::route('job.detail', $job_slug);
    }

    public function removeFromFavouriteJob(Request $request, $job_slug)
    {
        $user_id = Auth::user()->id;
        FavouriteJob::where('job_slug', 'like', $job_slug)->where('user_id', $user_id)->delete();

        flash(__('Job has been removed from favorites list'))->success();
        return \Redirect::route('job.detail', $job_slug);
    }

    public function applyJob(Request $request, $job_slug)
    {
        if (Auth::guard('company')->check()) {
            flash(__('You are currently logged in with a Company/Employer account. Only Job Seekers / Candidates can apply for jobs.'))->warning();
            return \Redirect::route('job.detail', $job_slug);
        }

        if (!Auth::check()) {
            flash(__('Please login as a Candidate / Job Seeker to apply for this job.'))->info();
            return \Redirect::route('login');
        }

        $user = Auth::user();
        $job = Job::where('slug', 'like', $job_slug)->first();
        
        if (!$job) {
            flash(__('Job not found'))->error();
            return \Redirect::route('job.list');
        }

        if ((bool)$user->is_active === false) {
            flash(__('Your account is inactive contact site admin to activate it'))->error();
            return \Redirect::route('job.detail', $job_slug);
        }
        
        if ((bool) config('jobseeker.is_jobseeker_package_active')) {
            if (
                    ($user->jobs_quota <= $user->availed_jobs_quota) ||
                    ($user->package_end_date && $user->package_end_date->lt(Carbon::now()))
            ) {
                flash(__('Your daily job application quota has been reached. Please upgrade your plan for higher or unlimited applications.'))->error();
                return \Redirect::route('pricing', ['tab' => 'candidates']);
            }
        }
        if ($user->isAppliedOnJob($job->id)) {
            flash(__('You have already applied for this job'))->success();
            return \Redirect::route('job.detail', $job_slug);
        }

        $myCvs = ProfileCv::where('user_id', '=', $user->id)->pluck('title', 'id')->toArray();

        return view('job.apply_job_form')
                        ->with('job_slug', $job_slug)
                        ->with('job', $job)
                        ->with('myCvs', $myCvs);
    }

    public function postApplyJob(ApplyJobFormRequest $request, $job_slug)
    {
        if (Auth::guard('company')->check()) {
            flash(__('You are currently logged in with a Company/Employer account. Only Job Seekers / Candidates can apply for jobs.'))->warning();
            return \Redirect::route('job.detail', $job_slug);
        }

        if (!Auth::check()) {
            flash(__('Please login as a Candidate / Job Seeker to apply for this job.'))->info();
            return \Redirect::route('login');
        }

        $user = Auth::user();
        $user_id = $user->id;
        $job = Job::where('slug', 'like', $job_slug)->first();

        $jobApply = new JobApply();
        $jobApply->user_id = $user_id;
        $jobApply->job_id = $job->id;
        $jobApply->cv_id = $request->post('cv_id');
        $jobApply->current_salary = $request->post('current_salary');
        $jobApply->expected_salary = $request->post('expected_salary');
        $jobApply->salary_currency = $request->post('salary_currency');
        $jobApply->save();

        /* Send instant notification to Employer */
        if (!empty($job->company_id)) {
            \App\AppNotification::sendNotification(
                'job_applied',
                'company',
                $job->company_id,
                __('New Applicant: :name applied for :job', ['name' => $user->getName(), 'job' => $job->title]),
                __(':name has applied for your job opening ":job". Click to review their profile and resume.', ['name' => $user->getName(), 'job' => $job->title]),
                route('list.applied.users', $job->id),
                'fa-briefcase',
                '#10B981'
            );
        }

        /*         * ******************************* */
        if ((bool) config('jobseeker.is_jobseeker_package_active')) {
            $user->availed_jobs_quota = $user->availed_jobs_quota + 1;
            $user->update();
        }
        /*         * ******************************* */
        event(new JobApplied($job, $jobApply));

        flash(__('You have successfully applied for this job'))->success();
        return \Redirect::route('job.detail', $job_slug);
    }

    public function myJobApplications(Request $request)
    {
        $user = Auth::user();
        $tab = $request->input('tab', 'applied');

        $appliedCount = JobApply::where('user_id', $user->id)->count();

        // Interview Invites count (applications with shortlisted status or listed in favourite_applicants)
        $interviewInvitesCount = JobApply::where('user_id', $user->id)
            ->where(function($q) use ($user) {
                $q->whereIn('status', ['shortlisted', 'interview_scheduled', 'interview_completed', 'selected'])
                  ->orWhereIn('job_id', function($sub) use ($user) {
                      $sub->select('job_id')->from('favourite_applicants')->where('user_id', $user->id);
                  });
            })->count();

        if ($tab === 'invites') {
            $applications = JobApply::where('user_id', $user->id)
                ->where(function($q) use ($user) {
                    $q->whereIn('status', ['shortlisted', 'interview_scheduled', 'interview_completed', 'selected'])
                      ->orWhereIn('job_id', function($sub) use ($user) {
                          $sub->select('job_id')->from('favourite_applicants')->where('user_id', $user->id);
                      });
                })
                ->with(['job.company', 'job.jobShift', 'job.city'])
                ->orderBy('updated_at', 'desc')
                ->paginate(10);
        } else {
            $applications = JobApply::where('user_id', $user->id)
                ->with(['job.company', 'job.jobShift', 'job.city'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('job.my_applied_jobs')
            ->with('applications', $applications)
            ->with('appliedCount', $appliedCount)
            ->with('interviewInvitesCount', $interviewInvitesCount)
            ->with('activeTab', $tab);
    }

    public function myFavouriteJobs(Request $request)
    {
        $myFavouriteJobSlugs = Auth::user()->getFavouriteJobSlugsArray();
        $jobs = Job::whereIn('slug', $myFavouriteJobSlugs)->paginate(10);
        return view('job.my_favourite_jobs')
                        ->with('jobs', $jobs);
    }

    /**
     * Get Areas by City ID (JSON)
     */
    public function getAreasByCity(Request $request)
    {
        $cityId = $request->input('city_id');
        if (empty($cityId)) {
            return response()->json([]);
        }
        
        $areas = \App\Area::where('city_id', $cityId)
            ->where('is_active', 1)
            ->orderBy('area_name', 'asc')
            ->get(['id', 'area_name', 'pincode']);
            
        return response()->json($areas);
    }

}

