<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use App\User;
use App\City;
use App\JobSkill;
use App\ProfileSkill;
use App\ProfileEducation;
use App\ProfileExperience;
use App\DegreeLevel;
use App\DegreeType;
use App\FunctionalArea;
use App\Traits\CommonUserFunctions;

class OnboardingController extends Controller
{
    use CommonUserFunctions;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the 12-Step Onboarding Flow
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->isJobSeeker()) {
            if ($user->isBusinessUser()) {
                return redirect()->route('business.dashboard');
            }
            return redirect()->route('company.home');
        }

        if ((bool)$user->onboarding_completed && !$request->has('edit')) {
            return redirect()->route('job.list');
        }

        // Dynamic suggested skills/roles — loaded via AJAX on step 10/11.
        // We pass the saved user education data as context so JS can request the right suggestions.
        $userSkills = [];
        $savedProfileSkills = ProfileSkill::where('user_id', $user->id)->get();
        foreach ($savedProfileSkills as $ps) {
            $skillName = $ps->getJobSkill('job_skill');
            if (!empty($skillName)) {
                $userSkills[] = $skillName;
            }
        }

        $userPreferredRoles = [];
        if (!empty($user->preferred_job_roles)) {
            $decoded = json_decode($user->preferred_job_roles, true);
            $userPreferredRoles = is_array($decoded)
                ? $decoded
                : array_filter(array_map('trim', explode(',', $user->preferred_job_roles)));
        }

        // Saved specializations (multi-select, stored as JSON)
        $savedSpecializations = [];
        if (!empty($user->specialization)) {
            $decoded = json_decode($user->specialization, true);
            if (is_array($decoded)) {
                $savedSpecializations = $decoded;
            } elseif (!empty($user->specialization)) {
                $savedSpecializations = [$user->specialization];
            }
        }

        $userExperience = ProfileExperience::where('user_id', $user->id)->first();
        $currentStep    = $user->onboarding_step ?: 1;

        return view('user.onboarding', compact(
            'user',
            'userSkills',
            'userPreferredRoles',
            'savedSpecializations',
            'userExperience',
            'currentStep'
        ));
    }

    /**
     * Save Step via AJAX
     */
    public function saveStep(Request $request)
    {
        $user = Auth::user();
        $step = (int)$request->input('step', 1);

        try {
            switch ($step) {

                case 1:
                    $user->onboarding_step = max($user->onboarding_step, 2);
                    $user->save();
                    return response()->json(['status' => 'success', 'next_step' => 2]);

                case 2:
                    $user->profile_type    = $request->input('profile_type') ?: 'fresher';
                    $user->onboarding_step = max($user->onboarding_step, 3);
                    $user->save();
                    return response()->json(['status' => 'success', 'next_step' => 3]);

                case 3:
                    // City is REQUIRED
                    $cityName = trim($request->input('city_name', ''));
                    if (empty($cityName)) {
                        return response()->json(['status' => 'error', 'message' => 'Please enter your city.'], 422);
                    }
                    $distance = $request->input('preferred_job_distance', '');
                    if (empty($distance)) {
                        return response()->json(['status' => 'error', 'message' => 'Please select a preferred work distance.'], 422);
                    }

                    $city = City::where('city', 'like', "%{$cityName}%")->first();
                    if ($city) {
                        $user->city_id    = $city->city_id;
                        $user->state_id   = $city->state_id;
                        $user->country_id = isset($city->country_id) ? $city->country_id : null;
                    }
                    $user->preferred_job_distance = $distance;
                    $user->onboarding_step        = max($user->onboarding_step, 4);
                    $user->save();
                    return response()->json(['status' => 'success', 'next_step' => 4]);

                case 4:
                    $qualification = $request->input('highest_qualification', '');
                    if (empty($qualification)) {
                        return response()->json(['status' => 'error', 'message' => 'Please select your highest qualification.'], 422);
                    }
                    $user->highest_qualification = $qualification;
                    $user->onboarding_step       = max($user->onboarding_step, 5);
                    $user->save();
                    return response()->json(['status' => 'success', 'next_step' => 5]);

                case 5:
                    $degree = $request->input('course_degree', '');
                    if (empty($degree)) {
                        return response()->json(['status' => 'error', 'message' => 'Please select your course / degree.'], 422);
                    }
                    $user->course_degree   = $degree;
                    $user->onboarding_step = max($user->onboarding_step, 6);
                    $user->save();
                    return response()->json(['status' => 'success', 'next_step' => 6]);

                case 6:
                    $courseType = $request->input('course_type', '');
                    if (empty($courseType)) {
                        return response()->json(['status' => 'error', 'message' => 'Please select your course type.'], 422);
                    }
                    $user->course_type     = $courseType;
                    $user->onboarding_step = max($user->onboarding_step, 7);
                    $user->save();
                    return response()->json(['status' => 'success', 'next_step' => 7]);

                case 7:
                    // Multi-select specializations — stored as JSON
                    $specs = $request->input('specialization', []);
                    if (is_string($specs)) {
                        $specs = json_decode($specs, true) ?: [$specs];
                    }
                    $specs = array_values(array_filter(array_map('trim', (array)$specs)));
                    $user->specialization  = json_encode($specs);
                    $user->onboarding_step = max($user->onboarding_step, 8);
                    $user->save();
                    return response()->json(['status' => 'success', 'next_step' => 8]);

                case 8:
                    $college            = trim($request->input('institution_name', ''));
                    $institutionId      = $request->input('institution_id');
                    $institutionType    = $request->input('institution_type');
                    $verificationStatus = $request->input('institution_verification_status');

                    if (empty($college)) {
                        return response()->json(['status' => 'error', 'message' => 'Please enter or select your college / university.'], 422);
                    }

                    // Duplicate / Official match check
                    $matchedOfficial = null;
                    if (!empty($institutionId)) {
                        $matchedOfficial = DB::table('institutions')->where('id', (int)$institutionId)->where('is_active', 1)->first();
                    }
                    if (!$matchedOfficial && !empty($college)) {
                        // Check exact or trimmed match in official list to associate verified ID
                        $matchedOfficial = DB::table('institutions')
                            ->where('is_active', 1)
                            ->whereRaw('LOWER(TRIM(name)) = ?', [strtolower(trim($college))])
                            ->first();
                    }

                    if ($matchedOfficial) {
                        $user->institution_id                  = $matchedOfficial->id;
                        $user->institution_name                = $matchedOfficial->name;
                        $user->institution_type                = 'official';
                        $user->institution_verification_status = $matchedOfficial->verification_status ?: 'verified';
                    } else {
                        // Manual unverified entry
                        $user->institution_id                  = null;
                        $user->institution_name                = $college;
                        $user->institution_type                = 'manual';
                        $user->institution_verification_status = 'unverified';
                    }

                    $user->onboarding_step  = max($user->onboarding_step, 9);
                    $user->save();

                    // Sync into ProfileEducation
                    $edu = ProfileEducation::where('user_id', $user->id)->first();
                    if (!$edu) {
                        $edu = new ProfileEducation();
                        $edu->user_id = $user->id;
                    }
                    $edu->institution  = $user->institution_name;
                    $edu->degree_title = $user->course_degree ?: $user->highest_qualification;
                    $edu->save();

                    return response()->json([
                        'status'              => 'success',
                        'next_step'           => 9,
                        'institution_type'    => $user->institution_type,
                        'verification_status' => $user->institution_verification_status
                    ]);

                case 9:
                    $startYear  = $request->input('degree_start_year', '');
                    $passYear   = $request->input('degree_end_year', '');
                    $percentage = $request->input('degree_percentage', '');

                    $user->degree_start_year  = $startYear;
                    $user->degree_end_year    = $passYear;
                    $user->degree_percentage  = $percentage;
                    $user->onboarding_step    = max($user->onboarding_step, 10);
                    $user->save();

                    // Sync into ProfileEducation
                    $edu = ProfileEducation::where('user_id', $user->id)->first();
                    if (!$edu) {
                        $edu = new ProfileEducation();
                        $edu->user_id = $user->id;
                    }
                    $degreeText = $user->course_degree ?: $user->highest_qualification;
                    $specsArr   = json_decode($user->specialization, true);
                    if (!empty($specsArr) && is_array($specsArr)) {
                        $degreeText .= ' (' . implode(', ', $specsArr) . ')';
                    } elseif (!empty($user->specialization)) {
                        $degreeText .= ' (' . $user->specialization . ')';
                    }
                    $edu->degree_title    = substr($degreeText, 0, 150);
                    $edu->institution     = $user->institution_name;
                    $edu->date_completion = !empty($passYear) ? $passYear . '-06-01' : null;
                    $edu->degree_result   = $percentage;
                    $edu->city_id         = $user->city_id;
                    $edu->state_id        = $user->state_id;
                    $edu->country_id      = $user->country_id;
                    $edu->save();

                    return response()->json(['status' => 'success', 'next_step' => 10]);

                case 10:
                    $rawSkills = $request->input('skills', []);

                    // Handle all possible formats from AJAX
                    if (is_string($rawSkills)) {
                        // Try JSON first, then comma-separated
                        $decoded = json_decode($rawSkills, true);
                        if (is_array($decoded)) {
                            $rawSkills = $decoded;
                        } else {
                            $rawSkills = preg_split('/[,;\n]+/', $rawSkills);
                        }
                    }

                    // Flatten and clean all skill names
                    $skillsList = [];
                    foreach ((array)$rawSkills as $sItem) {
                        if (is_array($sItem)) {
                            foreach ($sItem as $subItem) {
                                $parts = preg_split('/[,;\n]+/', (string)$subItem);
                                foreach ($parts as $p) {
                                    $p = trim($p);
                                    if (!empty($p)) $skillsList[] = $p;
                                }
                            }
                        } elseif (is_string($sItem)) {
                            $parts = preg_split('/[,;\n]+/', $sItem);
                            foreach ($parts as $p) {
                                $p = trim($p);
                                if (!empty($p)) $skillsList[] = $p;
                            }
                        }
                    }
                    $skillsList = array_values(array_unique(array_filter($skillsList)));

                    // Require at least 1 skill
                    if (empty($skillsList)) {
                        return response()->json(['status' => 'error', 'message' => 'Please add at least one skill to continue.'], 422);
                    }

                    // Delete existing skills and re-save fresh
                    ProfileSkill::where('user_id', $user->id)->delete();

                    foreach ($skillsList as $skillName) {
                        $skillName = trim($skillName);
                        if (empty($skillName)) continue;

                        // Find or create JobSkill record
                        $jobSkill = JobSkill::whereRaw('LOWER(TRIM(job_skill)) = ?', [strtolower($skillName)])->first();

                        if (!$jobSkill) {
                            $maxId = (int) JobSkill::max('job_skill_id') + 1;
                            $jobSkill = new JobSkill();
                            $jobSkill->job_skill_id = $maxId;
                            $jobSkill->job_skill    = $skillName;
                            $jobSkill->is_default   = 1;
                            $jobSkill->is_active    = 1;
                            $jobSkill->lang         = 'en';
                            $jobSkill->sort_order   = $maxId;
                            $jobSkill->save();
                        } else {
                            if (empty($jobSkill->job_skill_id)) {
                                $jobSkill->job_skill_id = $jobSkill->id;
                                $jobSkill->save();
                            }
                        }

                        $ps = new ProfileSkill();
                        $ps->user_id           = $user->id;
                        $ps->job_skill_id      = $jobSkill->job_skill_id ?: $jobSkill->id;
                        $ps->job_experience_id = 1;
                        $ps->save();
                    }

                    $user->onboarding_step = max($user->onboarding_step, 11);
                    $user->save();
                    try {
                        $this->updateUserFullTextSearch($user);
                    } catch (\Exception $e) {}

                    return response()->json([
                        'status'        => 'success',
                        'next_step'     => 11,
                        'skills_saved'  => count($skillsList),
                    ]);

                case 11:
                    $rawRoles = $request->input('preferred_job_roles', []);
                    if (is_string($rawRoles)) {
                        $rawRoles = json_decode($rawRoles, true) ?: explode(',', $rawRoles);
                    }
                    $rolesList = [];
                    foreach ((array)$rawRoles as $rItem) {
                        if (is_string($rItem)) {
                            $parts = preg_split('/[,;\n]+/', $rItem);
                            foreach ($parts as $p) {
                                $p = trim($p);
                                if (!empty($p)) {
                                    $rolesList[] = $p;
                                }
                            }
                        }
                    }
                    $rolesList = array_values(array_unique($rolesList));
                    $user->preferred_job_roles  = json_encode($rolesList);
                    $user->preferred_work_type  = $request->input('preferred_work_type', 'Full Time');
                    $user->preferred_work_mode  = $request->input('preferred_work_mode', 'On-site');

                    if ($user->profile_type === 'experienced' || $request->has('company_name')) {
                        $companyName = trim($request->input('company_name', ''));
                        $jobTitle    = trim($request->input('job_title', ''));
                        $isCurrent   = (int)$request->input('is_currently_working', 1);
                        if (!empty($companyName) || !empty($jobTitle)) {
                            $exp = ProfileExperience::where('user_id', $user->id)->first();
                            if (!$exp) {
                                $exp = new ProfileExperience();
                                $exp->user_id = $user->id;
                            }
                            $exp->company             = $companyName;
                            $exp->title               = $jobTitle ?: ($roles[0] ?? 'Executive');
                            $exp->is_currently_working = $isCurrent;
                            $exp->date_start          = $request->input('exp_start_date') ?: date('Y-m-01', strtotime('-1 year'));
                            $exp->date_end            = $isCurrent ? null : ($request->input('exp_end_date') ?: date('Y-m-01'));
                            $exp->city_id             = $user->city_id;
                            $exp->state_id            = $user->state_id;
                            $exp->country_id          = $user->country_id;
                            $exp->save();
                        }
                    }
                    // Final step completed — mark onboarding complete and populate sensible defaults
                    if (empty($user->job_experience_id)) {
                        $user->job_experience_id = ($user->profile_type === 'experienced') ? 1 : 11;
                    }
                    if (empty($user->career_level_id)) {
                        $user->career_level_id = ($user->profile_type === 'experienced') ? 3 : 2;
                    }
                    if (empty($user->country_id)) {
                        $user->country_id = 101; // India / Default
                    }
                    if (empty($user->salary_currency)) {
                        $user->salary_currency = 'INR';
                    }
                    $user->onboarding_completed   = 1;
                    $user->is_immediate_available = 1;
                    $user->onboarding_step        = 12;
                    $user->save();

                    // Auto-populate ProfileSummary if not already existing
                    $userSummary = \App\ProfileSummary::where('user_id', $user->id)->first();
                    if (!$userSummary) {
                        $summaryText = 'Career-driven ' . ($user->profile_type === 'experienced' ? 'experienced professional' : 'candidate');
                        if (!empty($user->course_degree) || !empty($user->highest_qualification)) {
                            $summaryText .= ' with education in ' . ($user->course_degree ?: $user->highest_qualification);
                        }
                        if (!empty($user->institution_name)) {
                            $summaryText .= ' from ' . $user->institution_name;
                        }
                        if (!empty($rolesList)) {
                            $summaryText .= '. Looking for opportunities as ' . implode(', ', array_slice($rolesList, 0, 3));
                        }
                        $summaryText .= '.';

                        $newSummary = new \App\ProfileSummary();
                        $newSummary->user_id = $user->id;
                        $newSummary->summary = $summaryText;
                        $newSummary->save();
                    }

                    try {
                        $this->updateUserFullTextSearch($user);
                    } catch (\Exception $e) {
                        // ignore search index sync errors if any
                    }
                    return response()->json([
                        'status'       => 'success',
                        'completed'    => true,
                        'message'      => 'Profile onboarding completed!',
                        'redirect_url' => route('job.list'),
                    ]);

                case 12:
                    if (empty($user->job_experience_id)) {
                        $user->job_experience_id = ($user->profile_type === 'experienced') ? 1 : 11;
                    }
                    if (empty($user->career_level_id)) {
                        $user->career_level_id = ($user->profile_type === 'experienced') ? 3 : 2;
                    }
                    if (empty($user->country_id)) {
                        $user->country_id = 101;
                    }
                    $user->onboarding_completed   = 1;
                    $user->is_immediate_available = 1;
                    $user->onboarding_step        = 12;
                    $user->save();
                    try {
                        $this->updateUserFullTextSearch($user);
                    } catch (\Exception $e) {}
                    return response()->json([
                        'status'       => 'success',
                        'message'      => 'Profile onboarding completed!',
                        'redirect_url' => route('job.list'),
                    ]);

                default:
                    return response()->json(['status' => 'error', 'message' => 'Invalid step.'], 422);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Complete Onboarding
     */
    public function completeOnboarding(Request $request)
    {
        $user = Auth::user();
        if (empty($user->job_experience_id)) {
            $user->job_experience_id = ($user->profile_type === 'experienced') ? 1 : 11;
        }
        if (empty($user->career_level_id)) {
            $user->career_level_id = ($user->profile_type === 'experienced') ? 3 : 2;
        }
        if (empty($user->country_id)) {
            $user->country_id = 101;
        }
        $user->onboarding_completed = 1;
        $user->onboarding_step      = 12;
        $user->save();
        try {
            $this->updateUserFullTextSearch($user);
        } catch (\Exception $e) {}
        return redirect()->route('job.list');
    }

    /**
     * API: Get courses for a given qualification
     */
    public function getCourses(Request $request)
    {
        $qual = trim($request->input('qualification', ''));
        $edu  = config('education_data.qualifications', []);
        if (isset($edu[$qual])) {
            return response()->json([
                'courses'      => $edu[$qual]['courses'],
                'course_types' => $edu[$qual]['course_types'],
            ]);
        }
        return response()->json(['courses' => [], 'course_types' => ['Full Time', 'Part Time']]);
    }

    /**
     * API: Get specializations for a given course
     */
    public function getSpecializations(Request $request)
    {
        $course = trim($request->input('course', ''));
        $specs  = config('education_data.specializations', []);
        $list   = $specs[$course] ?? [];
        return response()->json(['specializations' => $list]);
    }

    /**
     * API: Get skill suggestions for given course + specialization
     */
    public function getSkillSuggestions(Request $request)
    {
        $course = trim($request->input('course', ''));
        $spec   = trim($request->input('specialization', ''));
        $key    = $course . '|||' . $spec;
        $map    = config('education_data.skill_suggestions', []);
        $skills = $map[$key] ?? config('education_data.default_skill_suggestions', []);
        return response()->json(['skills' => $skills]);
    }

    /**
     * API: Get role suggestions for given course + specialization
     */
    public function getRoleSuggestions(Request $request)
    {
        $course = trim($request->input('course', ''));
        $spec   = trim($request->input('specialization', ''));
        $key    = $course . '|||' . $spec;
        $map    = config('education_data.role_suggestions', []);
        $roles  = $map[$key] ?? config('education_data.default_role_suggestions', []);
        return response()->json(['roles' => $roles]);
    }

    /**
     * API: Search institutions (colleges/universities) by name
     */
    public function searchInstitutions(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        // Synonym / keyword expansion for intelligent search (e.g. engg -> engineering)
        $synonyms = [
            'engg'  => 'Engineering',
            'eng'   => 'Engineering',
            'tech'  => 'Technology',
            'univ'  => 'University',
            'inst'  => 'Institute',
            'mgmt'  => 'Management',
            'mgt'   => 'Management',
            'govt'  => 'Government',
            'med'   => 'Medical',
            'vnit'  => 'Visvesvaraya',
            'iit'   => 'Indian Institute of Technology',
            'nit'   => 'National Institute of Technology',
            'iim'   => 'Indian Institute of Management',
            'aiims' => 'All India Institute of Medical Sciences',
            'rcoem' => 'Ramdeobaba',
            'ycce'  => 'Yeshwantrao Chavan College of Engineering',
            'coep'  => 'COEP',
            'vjti'  => 'Veermata Jijabai',
        ];

        $lowerQ = strtolower($q);
        $searchTerms = [$q];
        if (isset($synonyms[$lowerQ])) {
            $searchTerms[] = $synonyms[$lowerQ];
        }

        try {
            $query = DB::table('institutions')
                ->where('is_active', 1)
                ->where(function ($sub) use ($searchTerms) {
                    foreach ($searchTerms as $term) {
                        $sub->orWhere('name', 'like', "%{$term}%")
                            ->orWhere('city', 'like', "%{$term}%")
                            ->orWhere('state', 'like', "%{$term}%")
                            ->orWhere('university', 'like', "%{$term}%")
                            ->orWhere('type', 'like', "%{$term}%");
                    }
                });

            $results = $query
                ->select('id', 'name', 'type', 'university', 'city', 'state', 'source', 'verification_status')
                ->orderByRaw("CASE 
                    WHEN name LIKE ? THEN 0 
                    WHEN name LIKE ? THEN 1
                    WHEN city LIKE ? THEN 2
                    WHEN state LIKE ? THEN 3
                    ELSE 4 END", 
                    [$q . '%', '%' . $q . '%', $q . '%', $q . '%']
                )
                ->limit(15)
                ->get();

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    /**
     * API: Search skills
     */
    public function searchSkills(Request $request)
    {
        $term   = trim($request->input('q', ''));
        $skills = JobSkill::where(function($q) {
                $q->where('lang', 'en')->orWhereNull('lang');
            })
            ->where('is_active', 1)
            ->where('job_skill', 'like', "%{$term}%")
            ->limit(15)
            ->pluck('job_skill');
        return response()->json($skills);
    }

    /**
     * API: Search cities (for step 3 autocomplete)
     */
    public function searchCities(Request $request)
    {
        $term   = trim($request->input('q', ''));
        $cities = City::where('city', 'like', "%{$term}%")
            ->limit(10)
            ->select('city_id', 'city')
            ->get();
        return response()->json($cities);
    }
}
