<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\JobSkill;
use App\ProfileSkill;
use App\Services\AI\AIService;
use App\Services\AI\AIPrompts;
use Exception;

class CandidateAIController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->middleware('auth');
        $this->aiService = $aiService;
    }

    /**
     * Generate or Enhance Candidate Profile Summary using AI with server-side validation
     */
    public function generateSummary(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $mode = $request->input('mode', 'generate');
        $currentSummary = trim($request->input('current_summary', ''));

        // ═══════════════════════════════════════════════════════════════
        // 1. COLLECT ALL AVAILABLE PROFILE DATA — Maximum Context
        // ═══════════════════════════════════════════════════════════════

        // Target Role / Functional Area
        $role = 'Not specified';
        if ($user->functionalArea) {
            $fa = $user->functionalArea->lang()->first() ?? $user->functionalArea()->first();
            if ($fa) $role = $fa->functional_area;
        } elseif ($user->careerLevel) {
            $role = $user->careerLevel->career_level ?? 'Professional';
        }

        // Profile Type (Fresher / Experienced)
        $profileType = $user->profile_type ?? 'not specified';

        // Location
        $location = 'Not specified';
        if ($user->city_id) {
            $city = \App\City::find($user->city_id);
            if ($city) $location = $city->city . (isset($city->state) ? ', ' . $city->state : '');
        }

        // ── Education ──────────────────────────────────────────────────
        $highestQual    = $user->highest_qualification ?? '';
        $courseDegree   = $user->course_degree ?? '';
        $courseType     = $user->course_type ?? '';
        $specialization = '';
        if (!empty($user->specialization)) {
            $decoded = json_decode($user->specialization, true);
            $specialization = is_array($decoded) ? implode(', ', $decoded) : $user->specialization;
        }
        $institutionName  = $user->institution_name ?? '';
        $degreeStartYear  = $user->degree_start_year ?? '';
        $degreeEndYear    = $user->degree_end_year ?? '';
        $degreePercentage = $user->degree_percentage ?? '';

        // Build readable education summary
        $educationLines = [];
        $degreeLabel = $courseDegree ?: $highestQual;
        if (!empty($degreeLabel)) {
            $eduLine = $degreeLabel;
            if (!empty($specialization))   $eduLine .= ' in ' . $specialization;
            if (!empty($courseType))        $eduLine .= ' (' . $courseType . ')';
            if (!empty($institutionName))   $eduLine .= ' from ' . $institutionName;
            if (!empty($degreeEndYear))     $eduLine .= ', Batch ' . $degreeEndYear;
            if (!empty($degreePercentage))  $eduLine .= ', Score/Percentage: ' . $degreePercentage . '%';
            $educationLines[] = $eduLine;
        }
        // Also pull from ProfileEducation table
        if ($user->profileEducation && count($user->profileEducation)) {
            foreach ($user->profileEducation as $edu) {
                $line = '';
                if (!empty($edu->degree_title)) $line .= $edu->degree_title;
                if (!empty($edu->institution))  $line .= ' from ' . $edu->institution;
                if (!empty($edu->degree_result)) $line .= ', ' . $edu->degree_result . '%';
                if (!empty($line) && !in_array($line, $educationLines)) {
                    $educationLines[] = $line;
                }
            }
        }
        $eduText = !empty($educationLines) ? implode(' | ', $educationLines) : 'Not specified';

        // ── Experience ────────────────────────────────────────────────
        $experiences = [];
        if ($user->profileExperience && count($user->profileExperience)) {
            foreach ($user->profileExperience as $exp) {
                $comp   = $exp->company ?: 'a company';
                $title  = $exp->title   ?: 'Professional';
                $period = $exp->is_currently_working
                    ? 'Present'
                    : ($exp->date_end ? date('M Y', strtotime($exp->date_end)) : '');
                $start  = $exp->date_start ? date('M Y', strtotime($exp->date_start)) : '';
                $dur    = ($start && $period) ? " ({$start} – {$period})" : '';
                $experiences[] = "{$title} at {$comp}{$dur}";
            }
        }
        $expText = !empty($experiences) ? implode('; ', $experiences) : 'No experience listed (Fresher)';

        // ── Skills ───────────────────────────────────────────────────
        $skills = [];
        if ($user->profileSkills && count($user->profileSkills)) {
            foreach ($user->profileSkills as $sk) {
                $sName = $sk->getJobSkill('job_skill');
                if (!empty($sName)) $skills[] = $sName;
            }
        }
        $skillsText = !empty($skills) ? implode(', ', $skills) : 'Not specified';

        // ── Preferred Roles ──────────────────────────────────────────
        $preferredRoles = [];
        if (!empty($user->preferred_job_roles)) {
            $decoded = json_decode($user->preferred_job_roles, true);
            $preferredRoles = is_array($decoded) ? $decoded : explode(',', $user->preferred_job_roles);
            $preferredRoles = array_filter(array_map('trim', $preferredRoles));
        }
        $preferredRolesText = !empty($preferredRoles) ? implode(', ', $preferredRoles) : '';

        // ── Work Preferences ─────────────────────────────────────────
        $workType = $user->preferred_work_type ?? '';
        $workMode = $user->preferred_work_mode ?? '';

        // ── Resume CV File (for context flag) ────────────────────────
        $hasResume = false;
        $resumeText = '';
        $cv = $user->getDefaultCv();
        if ($cv && !empty($cv->cv_path ?? $cv->file_path ?? '')) {
            $hasResume = true;
        }

        // ── Profile Completeness Score (determines summary strength) ──
        $completenessScore = 0;
        if (!empty($skills))            $completenessScore += 30;
        if (!empty($institutionName))   $completenessScore += 15;
        if (!empty($degreePercentage))  $completenessScore += 10;
        if (!empty($specialization))    $completenessScore += 10;
        if (!empty($experiences) && $expText !== 'No experience listed (Fresher)') $completenessScore += 20;
        if (!empty($preferredRoles))    $completenessScore += 10;
        if (!empty($location) && $location !== 'Not specified') $completenessScore += 5;

        // ═══════════════════════════════════════════════════════════════
        // 2. SELECT PROMPT — pass FULL context
        // ═══════════════════════════════════════════════════════════════
        $extraContext = [
            'profile_type'     => $profileType,
            'location'         => $location,
            'specialization'   => $specialization,
            'institution_name' => $institutionName,
            'degree_percentage'=> $degreePercentage,
            'degree_years'     => ($degreeStartYear && $degreeEndYear) ? "{$degreeStartYear} – {$degreeEndYear}" : '',
            'preferred_roles'  => $preferredRolesText,
            'work_type'        => $workType,
            'work_mode'        => $workMode,
            'completeness'     => $completenessScore,
        ];

        if ($mode === 'polish' && !empty($currentSummary)) {
            $prompt = AIPrompts::candidatePolishPrompt($currentSummary, $role, $expText, $skillsText, $eduText, $extraContext);
            $promptVersion = AIPrompts::VERSION_CANDIDATE_POLISH;
        } else {
            $prompt = AIPrompts::candidateWritePrompt($role, $expText, $skillsText, $eduText, $extraContext);
            $promptVersion = AIPrompts::VERSION_CANDIDATE_PROFILE;
        }

        // ═══════════════════════════════════════════════════════════════
        // 3. CALL AI — max_tokens scaled to completeness
        // ═══════════════════════════════════════════════════════════════
        $maxTokens = $completenessScore >= 60 ? 700 : ($completenessScore >= 30 ? 550 : 400);

        $response = $this->aiService->generate($prompt, [
            'feature'            => 'candidate_profile_optimization',
            'feature_group'      => 'candidate',
            'temperature'        => 0.55,
            'max_tokens'         => $maxTokens,
            'response_mime_type' => 'application/json',
            'prompt_version'     => $promptVersion,
            'user_type'          => 'candidate',
            'user_id'            => $user->id,
        ]);

        // ═══════════════════════════════════════════════════════════════
        // 4. PARSE & RETURN
        // ═══════════════════════════════════════════════════════════════
        if ($response['success'] && !empty($response['text'])) {
            $parsed = $this->parseSummaryJson($response['text']);
            if (!empty($parsed)) {
                return response()->json([
                    'success'     => true,
                    'summary'     => $parsed,
                    'cached'      => $response['cached'] ?? false,
                    'cost_inr'    => $response['cost_inr'] ?? 0,
                    'latency_ms'  => $response['response_time_ms'] ?? 0,
                    'completeness'=> $completenessScore,
                ]);
            }
        }

        // 5. Natural Fallback
        $fallback = $this->generateNaturalFallback($role, $experiences, $skills, $institutionName, $degreePercentage, $specialization, $preferredRoles);
        return response()->json([
            'success'      => true,
            'summary'      => $fallback,
            'note'         => 'Generated via natural template',
            'completeness' => $completenessScore,
        ]);
    }

    /**
     * Get AI Smart Skill Recommendations for Candidate
     */
    public function recommendSkills(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Existing skills array
        $existingSkills = [];
        if ($user->profileSkills) {
            foreach ($user->profileSkills as $sk) {
                $name = $sk->getJobSkill('job_skill');
                if (!empty($name)) {
                    $existingSkills[] = mb_strtolower(trim($name));
                }
            }
        }

        $userRole = 'Software Developer';
        if ($user->profileExperience && count($user->profileExperience)) {
            $userRole = $user->profileExperience->first()->title;
        } elseif ($user->functionalArea) {
            $userRole = $user->functionalArea->functional_area;
        }

        $allDbSkills = JobSkill::where('is_active', 1)->pluck('job_skill')->toArray();

        $prompt = <<<EOT
Candidate target role is "{$userRole}".
Their current verified skills are: [{$this->arrayToString($existingSkills)}].

Suggest 3 to 5 trending, high-value technical or professional skills that are MISSING from their profile for this role.
Return ONLY valid JSON:
{
  "skills": ["Skill1", "Skill2", "Skill3", "Skill4"]
}
EOT;

        $response = $this->aiService->generate($prompt, [
            'feature' => 'candidate_skill_recommendation',
            'feature_group' => 'candidate',
            'temperature' => 0.4,
            'max_tokens' => 200,
            'response_mime_type' => 'application/json',
            'prompt_version' => 'candidate_skills_v1',
            'user_type' => 'candidate',
            'user_id' => $user->id,
        ]);

        $suggested = [];
        if ($response['success']) {
            $text = $response['text'];
            if (preg_match('/```json(.*?)```/s', $text, $matches)) {
                $text = trim($matches[1]);
            }
            $json = json_decode($text, true);
            if (isset($json['skills']) && is_array($json['skills'])) {
                foreach ($json['skills'] as $s) {
                    $sClean = trim($s);
                    if (!in_array(mb_strtolower($sClean), $existingSkills)) {
                        $suggested[] = $sClean;
                    }
                }
            }
        }

        // Deterministic Fallback if AI returned empty
        if (empty($suggested)) {
            $fallbacks = ['Git', 'MySQL', 'REST APIs', 'Laravel', 'Agile Methodologies', 'Docker'];
            foreach ($fallbacks as $f) {
                if (!in_array(mb_strtolower($f), $existingSkills)) {
                    $suggested[] = $f;
                }
            }
        }

        return response()->json([
            'success' => true,
            'role' => $userRole,
            'suggestions' => array_values(array_unique(array_slice($suggested, 0, 6))),
        ]);
    }

    /**
     * 1-Click Add Recommended Skill to Profile
     */
    public function addRecommendedSkill(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $skillName = trim($request->input('skill_name'));
        if (empty($skillName)) {
            return response()->json(['success' => false, 'message' => 'Skill name is required'], 400);
        }

        // Find or create JobSkill record in master table
        $jobSkill = JobSkill::where('job_skill', 'like', $skillName)->first();
        if (!$jobSkill) {
            $jobSkill = JobSkill::create([
                'job_skill' => $skillName,
                'is_active' => 1,
                'is_default' => 0,
            ]);
        }

        // Check if user already has this skill
        $exists = ProfileSkill::where('user_id', $user->id)
            ->where('job_skill_id', $jobSkill->id)
            ->first();

        if ($exists) {
            return response()->json([
                'success' => true,
                'message' => "Skill '{$skillName}' is already in your profile.",
                'already_exists' => true,
            ]);
        }

        // Insert into profile_skills
        $profileSkill = new ProfileSkill();
        $profileSkill->user_id = $user->id;
        $profileSkill->job_skill_id = $jobSkill->id;
        $profileSkill->save();

        return response()->json([
            'success' => true,
            'message' => "Skill '{$skillName}' added to your profile successfully!",
            'skill_id' => $profileSkill->id,
            'skill_name' => $jobSkill->job_skill,
        ]);
    }

    /**
     * Parse and sanitize summary JSON returned from AI
     */
    protected function parseSummaryJson(string $text): ?string
    {
        $text = trim($text);
        
        // 1. Strip markdown fences if present
        if (preg_match('/```json(.*?)```/s', $text, $matches)) {
            $text = trim($matches[1]);
        } elseif (preg_match('/```(.*?)```/s', $text, $matches)) {
            $text = trim($matches[1]);
        }

        // 2. Try to extract JSON object if surrounded by preamble/postamble
        $summary = null;
        if (preg_match('/\{[\s\S]*?"summary"\s*:\s*([\s\S]*?)\}/', $text, $matches)) {
            $json = json_decode($matches[0], true);
            if (!empty($json['summary']) && is_string($json['summary'])) {
                $summary = $json['summary'];
            }
        }

        if (!$summary) {
            $json = json_decode($text, true);
            if (isset($json['summary']) && is_string($json['summary'])) {
                $summary = $json['summary'];
            }
        }

        // 3. If still plain text without JSON
        if (!$summary && strlen($text) > 30 && strlen($text) < 2000) {
            $summary = $text;
        }

        if (!empty($summary)) {
            // Strip out AI drafting / chain-of-thought headers (e.g. "4. **Drafting...**", "*Paragraph 1*:", "# Summary")
            $summary = preg_replace('/\*{0,2}Paragraph\s*\d+\*{0,2}:?\s*/mi', '', $summary);
            $summary = preg_replace('/^\s*(?:\d+[\.\)]\s*)?\*\*[^*]+\*\*:?\s*$/mi', '', $summary);
            $summary = preg_replace('/^\s*\*(?:\s*\*Paragraph \d+\*|[^*]+)\*:?\s*$/mi', '', $summary);
            $summary = preg_replace('/^#{1,6}\s+.*$/m', '', $summary);
            $summary = preg_replace('/^\s*(?:Here is|Below is|Drafting|Note:|Summary:).*$/mi', '', $summary);
            $summary = preg_replace('/^\s*format\.\s*$/mi', '', $summary);
            $summary = preg_replace('/^\s*[\*\-]\s+/m', '', $summary);

            // Filter out prohibited buzzwords
            $prohibited = [
                'results-driven', 'Results-driven',
                'passionate about', 'Passionate about',
                'dynamic professional', 'Dynamic professional',
                'proven track record', 'Proven track record',
                'team player', 'Team player',
                'detail-oriented', 'Detail-oriented',
                'leverage', 'seamlessly', 'highly motivated'
            ];
            $summary = str_ireplace($prohibited, '', $summary);

            // Normalize paragraphs and spaces
            $paragraphs = preg_split('/\n{2,}/', $summary);
            $cleaned = [];
            foreach ($paragraphs as $para) {
                $para = preg_replace('/[ \t]+/', ' ', trim($para));
                // Discard leftover fragments or drafting lines shorter than 20 chars if they look like labels
                if (!empty($para) && strlen($para) > 15) {
                    $cleaned[] = $para;
                }
            }

            if (count($cleaned) > 0) {
                return implode("\n\n", $cleaned);
            }
        }

        return null;
    }

    /**
     * Generate a natural, truthful fallback summary without prohibited buzzwords
     */
    protected function generateNaturalFallback(
        string $role,
        array  $experiences,
        array  $skills,
        string $institution = '',
        string $percentage  = '',
        string $specialization = '',
        array  $preferredRoles = []
    ): string {
        // Paragraph 1: Education + Introduction
        $eduParts = [];
        if (!empty($specialization)) $eduParts[] = $specialization;
        $eduStr = !empty($eduParts) ? ' in ' . implode(', ', $eduParts) : '';
        $instStr = !empty($institution) ? " from {$institution}" : '';
        $percStr = !empty($percentage) ? " with {$percentage}% academic score" : '';
        $expLabel = !empty($experiences) ? 'experienced' : 'fresher';
        $para1 = "A {$expLabel} graduate{$eduStr}{$instStr}{$percStr}, seeking opportunities in {$role} to apply technical knowledge and educational background in a professional environment.";

        // Paragraph 2: Skills (ATS keywords)
        if (!empty($skills)) {
            $skillList = implode(', ', array_slice($skills, 0, 8));
            $para2 = "Proficient in {$skillList}. Skilled in applying these competencies to develop effective, scalable solutions that align with project goals and organisational requirements.";
        } else {
            $para2 = "Committed to continuous learning and applying industry best practices to contribute meaningfully to team goals and organisational growth.";
        }

        // Paragraph 3: Career objective
        if (!empty($preferredRoles)) {
            $rolesStr = implode(', ', array_slice($preferredRoles, 0, 3));
            $para3 = "Actively seeking roles such as {$rolesStr}, where acquired skills and educational foundation can be applied to real-world challenges.";
        } elseif (!empty($experiences)) {
            $expLine = implode('; ', array_slice($experiences, 0, 2));
            $para3 = "With experience as {$expLine}, looking to build further on this foundation in a growth-oriented role.";
        } else {
            $para3 = "Open to entry-level and fresher opportunities in {$role} where skills can be developed and applied to impactful projects.";
        }

        return $para1 . "\n\n" . $para2 . "\n\n" . $para3;
    }

    private function arrayToString(array $arr): string
    {
        return implode(', ', array_map(function($i) {
            return is_string($i) ? $i : json_encode($i);
        }, $arr));
    }
}
