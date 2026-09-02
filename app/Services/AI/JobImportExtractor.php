<?php

namespace App\Services\AI;

use App\FunctionalArea;
use App\JobSkill;
use App\JobType;
use App\JobExperience;
use App\CareerLevel;
use App\Country;
use App\State;
use App\City;
use App\Company;
use App\Job;
use App\Services\AI\AIService;
use App\Helpers\MiscHelper;
use Illuminate\Support\Str;
use Exception;
use Log;

class JobImportExtractor
{
    const PROMPT_VERSION = 'job_import_v1';

    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Extract structured job data from text or image
     *
     * @param string|null $rawText
     * @param array|null $image ['mime_type' => string, 'data' => base64_string]
     * @return array
     */
    public function extract(?string $rawText = null, ?array $image = null): array
    {
        $systemPrompt = $this->buildSystemPrompt();
        $userPrompt = $this->buildUserPrompt($rawText, !empty($image));

        $options = [
            'feature' => 'ai_job_import',
            'feature_group' => 'admin',
            'user_type' => 'admin',
            'prompt_version' => self::PROMPT_VERSION,
            'temperature' => 0.1, // High precision, deterministic
            'max_tokens' => 2500,
            'system_instruction' => $systemPrompt,
        ];

        if (!empty($image)) {
            $options['images'] = [$image];
            $options['images_hash'] = hash('sha256', substr($image['data'], 0, 1000) . strlen($image['data']));
        }

        $result = $this->aiService->generate($userPrompt, $options);

        if (!$result['success']) {
            return [
                'success' => false,
                'error' => $result['error'] ?? 'AI Extraction could not be completed. You can enter the job manually.',
                'raw_text' => $rawText,
            ];
        }

        // Clean and parse JSON
        $parsed = $this->parseJsonResponse($result['text']);

        if (!$parsed) {
            return [
                'success' => false,
                'error' => 'AI returned an unparseable response. Please review input or try again.',
                'raw_text' => $rawText,
            ];
        }

        // Map extracted fields against existing database dictionaries
        $mappedData = $this->mapToExistingSystem($parsed);

        // Count extraction metrics
        $confidenceStats = $this->computeConfidenceStats($mappedData['confidence'] ?? []);

        return [
            'success' => true,
            'data' => $mappedData,
            'stats' => $confidenceStats,
            'cached' => $result['cached'] ?? false,
            'cost_inr' => $result['cost_inr'] ?? 0.0,
            'latency_ms' => $result['response_time_ms'] ?? 0,
            'provider' => $result['provider'] ?? 'AI Engine',
        ];
    }

    /**
     * Parse and sanitize JSON returned by AI
     */
    protected function parseJsonResponse(string $text): ?array
    {
        $clean = trim($text);
        if (preg_match('/```json\s*(.*?)\s*```/is', $clean, $matches)) {
            $clean = $matches[1];
        } elseif (preg_match('/```\s*(.*?)\s*```/is', $clean, $matches)) {
            $clean = $matches[1];
        }

        $decoded = json_decode($clean, true);
        return is_array($decoded) ? $decoded : null;
    }

    /**
     * Map extracted raw fields to existing DB models and dropdown IDs
     */
    protected function mapToExistingSystem(array $raw): array
    {
        $mapped = $raw;

        // 1. Functional Area / Category Mapping
        $categoryName = $raw['category'] ?? '';
        $matchedCategory = $this->matchFunctionalArea($categoryName, $raw['title'] ?? '');
        $mapped['functional_area_id'] = $matchedCategory ? $matchedCategory->functional_area_id : null;
        $mapped['matched_category_name'] = $matchedCategory ? $matchedCategory->functional_area : ($categoryName ?: 'Not specified');

        // 2. Location / Country / State / City Mapping
        $locationStr = $raw['location'] ?? '';
        $locationResult = $this->matchLocation($locationStr);
        $mapped['country_id'] = $locationResult['country_id'];
        $mapped['state_id'] = $locationResult['state_id'];
        $mapped['city_id'] = $locationResult['city_id'];
        $mapped['matched_location_name'] = $locationResult['name'];
        $mapped['is_freelance'] = (stripos($raw['work_mode'] ?? '', 'remote') !== false || stripos($locationStr, 'remote') !== false) ? 1 : 0;

        // 3. Job Type Mapping
        $jobTypeStr = $raw['job_type'] ?? '';
        $matchedJobType = $this->matchJobType($jobTypeStr);
        $mapped['job_type_id'] = $matchedJobType ? $matchedJobType->job_type_id : null;

        // 4. Job Experience Mapping
        $expStr = $raw['experience'] ?? '';
        $matchedExp = $this->matchJobExperience($expStr);
        $mapped['job_experience_id'] = $matchedExp ? $matchedExp->job_experience_id : null;

        // 5. Skills Mapping (IDs & Tags)
        $extractedSkills = is_array($raw['skills'] ?? null) ? $raw['skills'] : [];
        $skillsResult = $this->matchSkills($extractedSkills);
        $mapped['skills_array'] = $extractedSkills;
        $mapped['matched_skill_ids'] = $skillsResult['matched_ids'];
        $mapped['all_skills_display'] = $skillsResult['display_names'];

        // 6. Duplicate check against existing active jobs
        $mapped['is_duplicate'] = false;
        $mapped['duplicate_job_id'] = null;
        if (!empty($raw['title']) && !empty($raw['company_name'])) {
            $existing = Job::where('title', 'like', "%{$raw['title']}%")->first();
            if ($existing) {
                $mapped['is_duplicate'] = true;
                $mapped['duplicate_job_id'] = $existing->id;
                $mapped['duplicate_job_slug'] = $existing->slug;
            }
        }

        return $mapped;
    }

    /**
     * Match Category / Functional Area against DB
     */
    protected function matchFunctionalArea(?string $category, ?string $title): ?FunctionalArea
    {
        if (!empty($category) && strtolower($category) !== 'not specified') {
            $fa = FunctionalArea::where('functional_area', 'like', "%{$category}%")->first();
            if ($fa) return $fa;
        }

        // Fuzzy match via title keywords
        if (!empty($title)) {
            $words = explode(' ', str_replace(['-', '|', '/', ','], ' ', $title));
            foreach ($words as $word) {
                $cleanWord = trim($word);
                if (strlen($cleanWord) > 3) {
                    $fa = FunctionalArea::where('functional_area', 'like', "%{$cleanWord}%")->first();
                    if ($fa) return $fa;
                }
            }
        }

        return null;
    }

    /**
     * Common Indian Locality to Major City Mapping Dictionary
     */
    protected static $localityToCityMap = [
        // Pune Localities
        'hinjewadi' => 'Pune', 'kothrud' => 'Pune', 'viman nagar' => 'Pune', 'kharadi' => 'Pune',
        'baner' => 'Pune', 'wakad' => 'Pune', 'hadapsar' => 'Pune', 'magarpatta' => 'Pune',
        'shivajinagar' => 'Pune', 'pimpri' => 'Pune', 'chinchwad' => 'Pune', 'bhosari' => 'Pune',
        'yerwada' => 'Pune', 'aundh' => 'Pune', 'kalyani nagar' => 'Pune', 'katraj' => 'Pune',
        'kondhwa' => 'Pune', 'bavdhan' => 'Pune', 'hinjawadi' => 'Pune', 'chakan' => 'Pune',
        
        // Nagpur Localities
        'dharampeth' => 'Nagpur', 'sadar' => 'Nagpur', 'sitabuldi' => 'Nagpur', 'wardha road' => 'Nagpur',
        'hingna' => 'Nagpur', 'midc' => 'Nagpur', 'butibori' => 'Nagpur', 'mahal' => 'Nagpur',
        'itwari' => 'Nagpur', 'ramdaspeth' => 'Nagpur', 'shankar nagar' => 'Nagpur', 'manish nagar' => 'Nagpur',
        'nandanvan' => 'Nagpur', 'wadi' => 'Nagpur', 'trimurti nagar' => 'Nagpur', 'khamla' => 'Nagpur',
        
        // Mumbai / MMR Localities
        'andheri' => 'Mumbai', 'bandra' => 'Mumbai', 'powai' => 'Mumbai', 'goregaon' => 'Mumbai',
        'borivali' => 'Mumbai', 'thane' => 'Mumbai', 'navi mumbai' => 'Mumbai', 'vashi' => 'Mumbai',
        'bkc' => 'Mumbai', 'malad' => 'Mumbai', 'kurla' => 'Mumbai', 'dadar' => 'Mumbai',
        'worli' => 'Mumbai', 'nariman point' => 'Mumbai', 'kandivali' => 'Mumbai', 'ghatkopar' => 'Mumbai',
        'lower parel' => 'Mumbai', 'juhu' => 'Mumbai', 'chembur' => 'Mumbai', 'mulund' => 'Mumbai',
        
        // Bangalore Localities
        'whitefield' => 'Bangalore', 'electronic city' => 'Bangalore', 'koramangala' => 'Bangalore',
        'indiranagar' => 'Bangalore', 'hsr layout' => 'Bangalore', 'marathahalli' => 'Bangalore',
        'bellandur' => 'Bangalore', 'jp nagar' => 'Bangalore', 'btm layout' => 'Bangalore',
        'manyata' => 'Bangalore', 'hebbal' => 'Bangalore', 'yelahanka' => 'Bangalore', 'bengaluru' => 'Bangalore',
        
        // Delhi NCR Localities
        'noida' => 'Delhi', 'greater noida' => 'Delhi', 'gurgaon' => 'Delhi', 'gurugram' => 'Delhi',
        'connaught place' => 'Delhi', 'okhla' => 'Delhi', 'saket' => 'Delhi', 'nehru place' => 'Delhi',
        'dwarka' => 'Delhi', 'karol bagh' => 'Delhi', 'laxmi nagar' => 'Delhi', 'ghaziabad' => 'Delhi',
        'faridabad' => 'Delhi', 'new delhi' => 'Delhi', 'south delhi' => 'Delhi', 'north delhi' => 'Delhi',
        
        // Hyderabad Localities
        'hitec city' => 'Hyderabad', 'gachibowli' => 'Hyderabad', 'madhapur' => 'Hyderabad',
        'kondapur' => 'Hyderabad', 'kukatpally' => 'Hyderabad', 'secunderabad' => 'Hyderabad',
        'banjara hills' => 'Hyderabad', 'jubilee hills' => 'Hyderabad', 'begumpet' => 'Hyderabad',
        
        // Other Major Cities
        'ahmedabad' => 'Ahmedabad', 'surat' => 'Surat', 'vadodara' => 'Vadodara', 'jaipur' => 'Jaipur',
        'indore' => 'Indore', 'bhopal' => 'Bhopal', 'lucknow' => 'Lucknow', 'kanpur' => 'Kanpur',
        'chandigarh' => 'Chandigarh', 'kolkata' => 'Kolkata', 'chennai' => 'Chennai', 'kochi' => 'Kochi',
        'patna' => 'Patna', 'ranchi' => 'Ranchi', 'raipur' => 'Raipur', 'bhubaneswar' => 'Bhubaneswar',
        'nashik' => 'Nashik', 'aurangabad' => 'Aurangabad', 'chhatrapati sambhajinagar' => 'Aurangabad',
        'kolhapur' => 'Kolhapur', 'solapur' => 'Solapur', 'amravati' => 'Amravati', 'akola' => 'Akola',
        'chandrapur' => 'Chandrapur', 'gondia' => 'Gondia', 'wardha' => 'Wardha', 'yavatmal' => 'Yavatmal'
    ];

    /**
     * Match Location against Cities/Countries DB with Smart Locality Resolution
     */
    protected function matchLocation(string $locationStr): array
    {
        $defaultCountryId = 101; // India default
        $cityId = null;
        $stateId = null;
        $name = $locationStr ?: 'Remote / India';
        $normalizedLoc = strtolower(trim($locationStr));

        if (!empty($locationStr) && $normalizedLoc !== 'not specified') {
            // 1. Check if explicitly remote / work from home
            if (strpos($normalizedLoc, 'remote') !== false || strpos($normalizedLoc, 'work from home') !== false || strpos($normalizedLoc, 'wfh') !== false) {
                return [
                    'country_id' => $defaultCountryId,
                    'state_id' => null,
                    'city_id' => null,
                    'name' => 'Remote / Work From Home',
                ];
            }

            // 2. Check locality dictionary first
            foreach (self::$localityToCityMap as $locality => $mappedCity) {
                if (strpos($normalizedLoc, $locality) !== false) {
                    $city = City::where('city', 'like', $mappedCity)->first();
                    if ($city) {
                        return [
                            'country_id' => $defaultCountryId,
                            'state_id' => $city->state_id,
                            'city_id' => $city->city_id ?? $city->id,
                            'name' => $city->city . ', India',
                        ];
                    }
                }
            }

            // 3. Check direct tokens & parts
            $cleanTokens = preg_split('/[\s,\-\/\|]+/', $normalizedLoc);
            foreach ($cleanTokens as $token) {
                $token = trim($token);
                if (strlen($token) < 3 || in_array($token, ['near', 'road', 'street', 'phase', 'sector', 'block', 'city', 'india', 'state'])) {
                    continue;
                }

                $city = City::where('city', 'like', $token)->first();
                if ($city) {
                    $cityId = $city->city_id ?? $city->id;
                    $stateId = $city->state_id;
                    $name = $city->city . ', India';
                    break;
                }
            }

            // 4. State Fallback if no specific city matched
            if (!$cityId) {
                foreach ($cleanTokens as $token) {
                    if (strlen($token) < 3) continue;
                    $state = State::where('state', 'like', $token)->first();
                    if ($state) {
                        $stateId = $state->state_id ?? $state->id;
                        $name = $state->state . ', India';
                        break;
                    }
                }
            }
        }

        return [
            'country_id' => $defaultCountryId,
            'state_id' => $stateId,
            'city_id' => $cityId,
            'name' => $name,
        ];
    }

    /**
     * Match Job Type against DB
     */
    protected function matchJobType(?string $jobTypeStr): ?JobType
    {
        if (empty($jobTypeStr) || strtolower($jobTypeStr) === 'not specified') {
            return null;
        }

        if (stripos($jobTypeStr, 'full') !== false) {
            return JobType::where('job_type', 'like', '%Full%')->first();
        }
        if (stripos($jobTypeStr, 'part') !== false) {
            return JobType::where('job_type', 'like', '%Part%')->first();
        }
        if (stripos($jobTypeStr, 'contract') !== false) {
            return JobType::where('job_type', 'like', '%Contract%')->first();
        }
        if (stripos($jobTypeStr, 'intern') !== false) {
            return JobType::where('job_type', 'like', '%Intern%')->first();
        }
        if (stripos($jobTypeStr, 'freelance') !== false) {
            return JobType::where('job_type', 'like', '%Freelance%')->first();
        }

        return JobType::where('job_type', 'like', "%{$jobTypeStr}%")->first();
    }

    /**
     * Match Experience against DB
     */
    protected function matchJobExperience(?string $expStr): ?JobExperience
    {
        if (empty($expStr) || strtolower($expStr) === 'not specified') {
            return null;
        }

        if (stripos($expStr, 'fresher') !== false || stripos($expStr, '0 year') !== false || stripos($expStr, 'entry') !== false) {
            return JobExperience::where('job_experience', 'like', '%Fresh%')->first();
        }

        preg_match('/(\d+)/', $expStr, $matches);
        if (!empty($matches[1])) {
            $num = (int)$matches[1];
            $exp = JobExperience::where('job_experience', 'like', "%{$num}%")->first();
            if ($exp) return $exp;
        }

        return JobExperience::where('job_experience', 'like', "%{$expStr}%")->first();
    }

    /**
     * Match Skills against DB
     */
    protected function matchSkills(array $skillNames): array
    {
        $matchedIds = [];
        $displayNames = [];

        foreach ($skillNames as $name) {
            $cleanName = trim($name);
            if (empty($cleanName)) continue;

            $displayNames[] = $cleanName;
            $dbSkill = JobSkill::where('job_skill', 'like', $cleanName)->first();
            if ($dbSkill) {
                $matchedIds[] = $dbSkill->job_skill_id;
            }
        }

        return [
            'matched_ids' => array_unique($matchedIds),
            'display_names' => array_unique($displayNames),
        ];
    }

    /**
     * Calculate extraction summary metrics
     */
    protected function computeConfidenceStats(array $confidences): array
    {
        $extracted = 0;
        $review = 0;
        $notFound = 0;

        foreach ($confidences as $field => $status) {
            $statusLower = strtolower($status);
            if ($statusLower === 'high') {
                $extracted++;
            } elseif ($statusLower === 'review' || $statusLower === 'medium' || $statusLower === 'low') {
                $review++;
            } elseif ($statusLower === 'not_found' || $statusLower === 'missing') {
                $notFound++;
            } else {
                $extracted++;
            }
        }

        return [
            'extracted_count' => $extracted,
            'review_count' => $review,
            'not_found_count' => $notFound,
        ];
    }

    /**
     * System Prompt for strict factual extraction
     */
    protected function buildSystemPrompt(): string
    {
        return <<<PROMPT
You are a specialized Enterprise Job Information Extraction Engine for a professional Recruitment Portal.

CRITICAL EXTRACTION RULES (STRICT FACTUAL ACCURACY ONLY):
1. Extract information ONLY if explicitly and visibly stated in the provided text or image advertisement.
2. DO NOT GUESS OR INVENT ANY INFORMATION:
   - Salary: If salary amount is not visibly stated, return salary_from: null, salary_to: null, and confidence: "not_found". NEVER infer salary from title, company, or market rates.
   - Experience: If experience is not clearly stated, return experience: "Not specified" and confidence: "not_found".
   - Job Type: Only return "Full Time", "Part Time", "Contract", or "Internship" if explicitly written. Otherwise return "Not specified".
   - Contact Info: Only return emails, phones, or URLs if visibly present in the source.
3. Clean and format the description into professional markdown / HTML with standard headers:
   - <h4>Role Overview</h4>
   - <h4>Key Responsibilities</h4>
   - <h4>Requirements & Skills</h4>
   - <h4>How to Apply</h4>
   (Include only sections that have source information. Do not invent duties or requirements).
4. Assign confidence ratings for each field: "high", "review", or "not_found".

OUTPUT FORMAT:
Return ONLY valid JSON matching this schema:
{
  "title": "String (Job title)",
  "company_name": "String (Company or employer name)",
  "category": "String (Suggested functional category, e.g. 'SEO / Digital Marketing', 'Software Development')",
  "location": "String (City, State, or 'Remote / Online')",
  "job_type": "String or 'Not specified'",
  "work_mode": "String ('Remote', 'Hybrid', 'On-site', or 'Not specified')",
  "experience": "String (e.g. '3+ years', 'Fresher', or 'Not specified')",
  "salary_from": "Number or null",
  "salary_to": "Number or null",
  "salary_currency": "INR",
  "salary_period": "Monthly",
  "hide_salary": 0,
  "skills": ["Skill1", "Skill2", "Skill3"],
  "contact_email": "String (email if present or 'Not specified')",
  "contact_phone": "String (phone if present or 'Not specified')",
  "application_url": "String (URL if present or 'Not specified')",
  "description": "HTML formatted clean job description",
  "confidence": {
    "title": "high|review|not_found",
    "company_name": "high|review|not_found",
    "category": "high|review|not_found",
    "location": "high|review|not_found",
    "job_type": "high|review|not_found",
    "work_mode": "high|review|not_found",
    "experience": "high|review|not_found",
    "salary": "high|review|not_found",
    "skills": "high|review|not_found",
    "contact_email": "high|review|not_found",
    "contact_phone": "high|review|not_found",
    "application_url": "high|review|not_found"
  }
}
PROMPT;
    }

    /**
     * User Prompt
     */
    protected function buildUserPrompt(?string $rawText, bool $hasImage): string
    {
        $prompt = "Please extract the job advertisement information into the specified JSON format.";
        if ($hasImage) {
            $prompt .= "\nRead all visible text in the attached job image/poster accurately (OCR & visual comprehension).";
        }
        if (!empty($rawText)) {
            $prompt .= "\n\nRAW JOB CONTENT:\n" . trim($rawText);
        }
        return $prompt;
    }
}
