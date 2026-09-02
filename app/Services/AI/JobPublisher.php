<?php

namespace App\Services\AI;

use App\RawJob;
use App\Job;
use App\Company;
use App\JobAIData;
use App\FunctionalArea;
use App\JobSkill;
use App\JobSkillManager;
use App\Country;
use App\City;
use App\JobType;
use App\CareerLevel;
use Exception;
use Illuminate\Support\Str;
use Carbon\Carbon;

class JobPublisher
{
    /**
     * Publish an enriched raw job to the active portal
     *
     * @param RawJob $rawJob
     * @return Job
     * @throws Exception
     */
    public function publish(RawJob $rawJob): Job
    {
        $aiData = $rawJob->aiData;

        // 1. Resolve or Create Company
        $companyName = !empty($rawJob->raw_company) ? trim($rawJob->raw_company) : 'Featured Employer';
        $company = Company::where('name', $companyName)->first();
        if (!$company) {
            $company = new Company();
            $company->name = $companyName;
            $company->email = 'contact@' . Str::slug($companyName) . '.com';
            $company->slug = Str::slug($companyName . '-' . uniqid());
            $company->is_active = 1;
            $company->verified = 1;
            $company->package_id = 1;
            $company->jobs_quota = 100;
            $company->availed_jobs_quota = 1;
            $company->save();
        }

        // 2. Resolve Category / Functional Area
        $functionalAreaId = 1; // Default fallback
        if ($aiData && !empty($aiData->suggested_category)) {
            $area = FunctionalArea::where('functional_area', 'LIKE', '%' . $aiData->suggested_category . '%')->first();
            if ($area) {
                $functionalAreaId = $area->functional_area_id ?: $area->id;
            }
        }

        // 3. Resolve Location (Country, State & City) with Smart Locality Mapping
        $countryId = 101; // India ID default
        $stateId = null;
        $cityId = null;
        $isFreelance = 0;

        if (!empty($rawJob->raw_location)) {
            $rawLoc = strtolower(trim($rawJob->raw_location));

            if (strpos($rawLoc, 'remote') !== false || strpos($rawLoc, 'work from home') !== false || strpos($rawLoc, 'wfh') !== false) {
                $isFreelance = 1;
            } else {
                // Check common locality dictionary
                $localityMap = [
                    'hinjewadi' => 'Pune', 'kothrud' => 'Pune', 'viman nagar' => 'Pune', 'kharadi' => 'Pune',
                    'baner' => 'Pune', 'wakad' => 'Pune', 'hadapsar' => 'Pune', 'magarpatta' => 'Pune',
                    'dharampeth' => 'Nagpur', 'sadar' => 'Nagpur', 'sitabuldi' => 'Nagpur', 'wardha road' => 'Nagpur',
                    'hingna' => 'Nagpur', 'midc' => 'Nagpur', 'butibori' => 'Nagpur', 'manish nagar' => 'Nagpur',
                    'andheri' => 'Mumbai', 'bandra' => 'Mumbai', 'powai' => 'Mumbai', 'goregaon' => 'Mumbai',
                    'borivali' => 'Mumbai', 'thane' => 'Mumbai', 'navi mumbai' => 'Mumbai', 'vashi' => 'Mumbai',
                    'whitefield' => 'Bangalore', 'electronic city' => 'Bangalore', 'koramangala' => 'Bangalore',
                    'indiranagar' => 'Bangalore', 'hsr layout' => 'Bangalore', 'bengaluru' => 'Bangalore',
                    'noida' => 'Delhi', 'greater noida' => 'Delhi', 'gurgaon' => 'Delhi', 'gurugram' => 'Delhi',
                    'connaught place' => 'Delhi', 'south delhi' => 'Delhi', 'new delhi' => 'Delhi',
                    'hitec city' => 'Hyderabad', 'gachibowli' => 'Hyderabad', 'madhapur' => 'Hyderabad'
                ];

                foreach ($localityMap as $locality => $mappedCity) {
                    if (strpos($rawLoc, $locality) !== false) {
                        $city = City::where('city', 'like', $mappedCity)->first();
                        if ($city) {
                            $cityId = $city->city_id ?: $city->id;
                            $stateId = $city->state_id;
                            $countryId = $city->country_id ?: $countryId;
                            break;
                        }
                    }
                }

                if (!$cityId) {
                    $tokens = preg_split('/[\s,\-\/\|]+/', $rawLoc);
                    foreach ($tokens as $token) {
                        $token = trim($token);
                        if (strlen($token) < 3 || in_array($token, ['near', 'road', 'street', 'phase', 'sector', 'block', 'city', 'india', 'state'])) continue;
                        $city = City::where('city', 'like', $token)->first();
                        if ($city) {
                            $cityId = $city->city_id ?: $city->id;
                            $stateId = $city->state_id;
                            $countryId = $city->country_id ?: $countryId;
                            break;
                        }
                    }
                }
            }
        }

        // 4. Resolve Job Type
        $jobTypeId = 1; // Full Time default
        if ($aiData && !empty($aiData->employment_type)) {
            $jType = JobType::where('job_type', 'LIKE', '%' . $aiData->employment_type . '%')->first();
            if ($jType) {
                $jobTypeId = $jType->job_type_id ?: $jType->id;
            }
        }

        // 5. Create Job Record
        $title = $aiData ? ($aiData->seo_title ?: $rawJob->raw_title) : $rawJob->raw_title;
        $slug = $aiData && !empty($aiData->slug) ? $aiData->slug : Str::slug($title . '-' . uniqid());

        // Ensure unique slug
        if (Job::where('slug', $slug)->exists()) {
            $slug .= '-' . rand(1000, 9999);
        }

        $job = new Job();
        $job->company_id = $company->id;
        $job->title = $title;
        $job->description = $rawJob->raw_description ?: $rawJob->raw_title;
        $job->country_id = $countryId;
        $job->state_id = $stateId;
        $job->city_id = $cityId;

        // Auto-match structured area
        $matchedArea = null;
        if (!empty($rawJob->raw_location) && $cityId) {
            $matchedArea = \App\Area::where('city_id', $cityId)
                ->where(function($q) use ($rawJob) {
                    $q->whereRaw('? LIKE CONCAT("%", area_name, "%")', [$rawJob->raw_location]);
                })->first();
        }
        $job->area_id = $matchedArea ? $matchedArea->id : null;
        $job->area_name = $matchedArea ? $matchedArea->area_name : null;

        $job->is_freelance = ($jobTypeId == 4) ? 1 : 0;
        $job->career_level_id = 1;
        $job->salary_from = 0;
        $job->salary_to = 0;
        $job->hide_salary = 1; // Strict truthfulness: hide salary if not explicitly set
        $job->salary_currency = 'INR';
        $job->salary_period_id = 1;
        $job->functional_area_id = $functionalAreaId;
        $job->job_type_id = $jobTypeId;
        $job->job_shift_id = 1;
        $job->num_of_positions = 1;
        $job->gender_id = 3; // No preference
        $job->expiry_date = Carbon::now()->addDays(30);
        $job->degree_level_id = 1;
        $job->job_experience_id = 1;
        $job->is_active = 1;
        $job->is_featured = 0;
        $job->slug = $slug;
        $job->search = $title . ' ' . $rawJob->raw_location . ' ' . $companyName;
        $job->save();

        // 6. Attach Skills
        if ($aiData && !empty($aiData->extracted_skills)) {
            $skills = json_decode($aiData->extracted_skills, true);
            if (is_array($skills)) {
                foreach ($skills as $skillName) {
                    $skillName = trim($skillName);
                    if (empty($skillName)) continue;

                    $skill = JobSkill::where('job_skill', $skillName)->first();
                    if (!$skill) {
                        $skill = new JobSkill();
                        $skill->job_skill = $skillName;
                        $skill->is_active = 1;
                        $skill->is_default = 1;
                        $skill->save();
                    }

                    JobSkillManager::firstOrCreate([
                        'job_id' => $job->id,
                        'job_skill_id' => $skill->job_skill_id ?: $skill->id,
                    ]);
                }
            }
        }

        // 7. Update status and links
        $rawJob->status = 'published';
        $rawJob->job_id = $job->id;
        $rawJob->save();

        if ($aiData) {
            $aiData->job_id = $job->id;
            $aiData->save();
        }

        return $job;
    }
}
