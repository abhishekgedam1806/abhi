<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\DataArrayHelper;
use App\Services\AI\JobImportExtractor;
use App\Job;
use App\Company;
use App\JobSkill;
use App\JobSkillManager;
use App\FunctionalArea;
use App\City;
use App\Country;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;
use Exception;
use Log;

class AIJobImportController extends Controller
{
    protected $extractor;

    public function __construct(JobImportExtractor $extractor)
    {
        $this->extractor = $extractor;
    }

    /**
     * Display the AI Job Import & Auto-Fill Page
     */
    public function index()
    {
        $companies = DataArrayHelper::companiesArray();
        $countries = DataArrayHelper::defaultCountriesArray();
        $currencies = DataArrayHelper::currenciesArray();
        $careerLevels = DataArrayHelper::defaultCareerLevelsArray();
        $functionalAreas = DataArrayHelper::defaultFunctionalAreasArray();
        $jobTypes = DataArrayHelper::defaultJobTypesArray();
        $jobShifts = DataArrayHelper::defaultJobShiftsArray();
        $genders = DataArrayHelper::defaultGendersArray();
        $jobExperiences = DataArrayHelper::defaultJobExperiencesArray();
        $jobSkills = DataArrayHelper::defaultJobSkillsArray();
        $degreeLevels = DataArrayHelper::defaultDegreeLevelsArray();
        $salaryPeriods = DataArrayHelper::defaultSalaryPeriodsArray();

        return view('admin.ai.import.index', compact(
            'companies',
            'countries',
            'currencies',
            'careerLevels',
            'functionalAreas',
            'jobTypes',
            'jobShifts',
            'genders',
            'jobExperiences',
            'jobSkills',
            'degreeLevels',
            'salaryPeriods'
        ));
    }

    /**
     * AJAX Endpoint: Extract structured job data from text or image
     */
    public function extract(Request $request)
    {
        $request->validate([
            'raw_text' => 'nullable|string|max:20000',
            'job_image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120', // 5MB limit
        ]);

        $rawText = $request->input('raw_text');
        $imagePayload = null;

        if ($request->hasFile('job_image')) {
            $file = $request->file('job_image');
            $mimeType = $file->getMimeType();
            $base64Data = base64_encode(file_get_contents($file->getRealPath()));
            $imagePayload = [
                'mime_type' => $mimeType,
                'data' => $base64Data,
            ];
        }

        if (empty($rawText) && empty($imagePayload)) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide either raw job text or upload an image file.',
            ], 422);
        }

        try {
            $result = $this->extractor->extract($rawText, $imagePayload);

            if ($result['success']) {
                return response()->json($result);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['error'] ?? 'AI Extraction could not be completed. You can enter the job manually.',
                ], 400);
            }
        } catch (Exception $e) {
            Log::error('AI Job Import extraction error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Extraction Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Save/Publish the extracted and reviewed job into the database
     */
    public function saveJob(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'company_name' => 'required|string|max:150',
            'functional_area_id' => 'required|integer',
            'description' => 'required|string',
            'action_type' => 'required|in:draft,publish',
        ]);

        try {
            DB::beginTransaction();

            // 1. Resolve or Create Company
            $companyName = trim($request->input('company_name'));
            $company = Company::where('name', $companyName)->first();

            if (!$company) {
                $company = new Company();
                $company->name = $companyName;
                $company->email = $request->input('contact_email') && filter_var($request->input('contact_email'), FILTER_VALIDATE_EMAIL) ? $request->input('contact_email') : 'contact@' . Str::slug($companyName, '') . '.com';
                $company->password = bcrypt(Str::random(16));
                $company->is_active = 1;
                $company->slug = Str::slug($companyName, '-') . '-' . Str::random(5);
                $company->country_id = $request->input('country_id', 101);
                $company->city_id = $request->input('city_id');
                $company->website = $request->input('application_url') ?: null;
                $company->phone = $request->input('contact_phone') ?: null;
                $company->save();
            }

            // 2. Create Job Record
            $job = new Job();
            $job->company_id = $company->id;
            $job->title = $request->input('title');
            $job->description = $request->input('description');
            $job->functional_area_id = (int) $request->input('functional_area_id');
            $job->country_id = (int) $request->input('country_id', 101);
            $job->state_id = $request->input('state_id') ? (int)$request->input('state_id') : null;
            $job->city_id = $request->input('city_id') ? (int)$request->input('city_id') : null;
            $job->job_type_id = $request->input('job_type_id') ? (int)$request->input('job_type_id') : 1;
            $job->job_shift_id = $request->input('job_shift_id') ? (int)$request->input('job_shift_id') : 1;
            $job->career_level_id = $request->input('career_level_id') ? (int)$request->input('career_level_id') : 1;
            $job->job_experience_id = $request->input('job_experience_id') ? (int)$request->input('job_experience_id') : 1;
            $job->gender_id = $request->input('gender_id') ? (int)$request->input('gender_id') : 3; // No preference
            $job->degree_level_id = $request->input('degree_level_id') ? (int)$request->input('degree_level_id') : 1;
            $job->salary_period_id = $request->input('salary_period_id') ? (int)$request->input('salary_period_id') : 1;
            $job->salary_currency = $request->input('salary_currency', 'INR');
            $job->salary_from = $request->input('salary_from') ? (float)$request->input('salary_from') : null;
            $job->salary_to = $request->input('salary_to') ? (float)$request->input('salary_to') : null;
            $job->hide_salary = $request->input('hide_salary', 0);
            $job->is_freelance = $request->input('is_freelance', 0);
            $job->num_of_positions = (int)$request->input('num_of_positions', 1);
            $job->expiry_date = Carbon::now()->addDays(30);

            // Draft vs Publish
            $isPublish = $request->input('action_type') === 'publish';
            $job->is_active = $isPublish ? 1 : 0;
            $job->is_featured = 0;
            $job->save();

            // Set Slug
            $job->slug = Str::slug($job->title, '-') . '-' . $job->id;
            $job->save();

            // 3. Attach Skills
            $skills = $request->input('skills', []);
            if (!empty($skills)) {
                if (is_string($skills)) {
                    $skills = array_filter(array_map('trim', explode(',', $skills)));
                }

                foreach ($skills as $skillItem) {
                    $skillId = null;
                    if (is_numeric($skillItem)) {
                        $skillId = (int)$skillItem;
                    } else {
                        $skillModel = JobSkill::firstOrCreate([
                            'job_skill' => $skillItem,
                            'lang' => 'en',
                            'is_default' => 1,
                            'is_active' => 1,
                        ]);
                        $skillId = $skillModel->job_skill_id ?: $skillModel->id;
                    }

                    if ($skillId) {
                        JobSkillManager::firstOrCreate([
                            'job_id' => $job->id,
                            'job_skill_id' => $skillId,
                        ]);
                    }
                }
            }

            DB::commit();

            $statusText = $isPublish ? 'published live to the Job Portal' : 'saved as a draft';
            return response()->json([
                'success' => true,
                'message' => "✓ Job \"{$job->title}\" has been successfully {$statusText}!",
                'job_id' => $job->id,
                'job_slug' => $job->slug,
                'live_url' => route('job.detail', [$job->slug]),
                'edit_url' => route('edit.job', [$job->id]),
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to save AI imported job: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save job: ' . $e->getMessage(),
            ], 500);
        }
    }
}
