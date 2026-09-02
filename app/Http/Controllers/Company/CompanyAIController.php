<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\JobSkill;
use App\FunctionalArea;
use App\Services\AI\AIService;
use App\Services\AI\AIPrompts;
use Exception;

class CompanyAIController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->middleware('auth:company');
        $this->aiService = $aiService;
    }

    /**
     * Grammarly / Wordtune Style Proofreader (Preserves 100% of Employer's Original Content)
     */
    public function proofreadJobDescription(Request $request)
    {
        $company = Auth::guard('company')->user();
        if (!$company) {
            return response()->json(['success' => false, 'message' => 'Unauthorized employer access'], 401);
        }

        $rawDescription = trim(strip_tags($request->input('description', '')));
        $category = trim($request->input('category', ''));
        if (empty($category) && $request->has('functional_area_id')) {
            $fa = FunctionalArea::find($request->input('functional_area_id'));
            if ($fa) {
                $category = $fa->functional_area;
            }
        }
        $category = $category ?: 'General Business';

        if (empty($rawDescription)) {
            return response()->json([
                'success' => false,
                'message' => 'Please type your job description first so AI can proofread and check keywords.'
            ], 422);
        }

        $prompt = AIPrompts::grammarlyProofreadPrompt($rawDescription, $category);

        $response = $this->aiService->generate($prompt, [
            'feature' => 'employer_job_proofread',
            'feature_group' => 'employer',
            'temperature' => 0.2, // Strict deterministic grammar checking
            'max_tokens' => 2048,
            'response_mime_type' => 'application/json',
            'prompt_version' => 'grammarly_proofread_v1',
            'user_type' => 'employer',
            'user_id' => $company->id,
        ]);

        $corrections = [];
        $missingKeywords = [];
        $correctedFullDescription = '';

        if ($response['success'] && !empty($response['text'])) {
            $text = trim($response['text']);
            if (preg_match('/```json(.*?)```/s', $text, $matches)) {
                $text = trim($matches[1]);
            }
            $json = json_decode($text, true);

            if (isset($json['corrections']) && is_array($json['corrections'])) {
                $corrections = $json['corrections'];
            }

            if (isset($json['missing_keywords_suggestions']) && is_array($json['missing_keywords_suggestions'])) {
                $missingKeywords = $json['missing_keywords_suggestions'];
            }

            if (isset($json['corrected_full_description']) && !empty($json['corrected_full_description'])) {
                $correctedFullDescription = nl2br(e($json['corrected_full_description']));
                $correctedFullDescription = str_replace(["\n", "\r"], '', $correctedFullDescription);
            }
        }

        // Fallback if no errors detected or API returned clean text
        if (empty($correctedFullDescription)) {
            $correctedFullDescription = nl2br(e($rawDescription));
        }

        return response()->json([
            'success' => true,
            'has_corrections' => count($corrections) > 0,
            'corrections' => $corrections,
            'missing_keywords' => $missingKeywords,
            'corrected_full_description' => $correctedFullDescription,
            'original_word_count' => str_word_count($rawDescription),
            'cost_inr' => $response['cost_inr'] ?? 0,
            'latency_ms' => $response['response_time_ms'] ?? 0,
        ]);
    }

    /**
     * AI Job Description & Requirements Optimizer for Employers
     */
    public function optimizeJobDescription(Request $request)
    {
        $company = Auth::guard('company')->user();
        if (!$company) {
            return response()->json(['success' => false, 'message' => 'Unauthorized employer access'], 401);
        }

        $title = trim($request->input('title', ''));
        $rawDescription = trim($request->input('description', ''));
        $location = trim($request->input('location', $company->getLocation() ?: 'Nagpur, India'));
        $salary = trim($request->input('salary', ''));
        $jobType = trim($request->input('job_type', 'Full-time'));

        if (empty($title) && empty($rawDescription)) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter at least a Job Title or short description to optimize with AI.'
            ], 422);
        }

        $companyName = $company->name ?: 'Our Company';
        $prompt = AIPrompts::employerOptimizerPrompt(
            $title ?: 'Professional Role',
            $companyName,
            $location,
            $rawDescription ?: $title,
            $salary,
            $jobType
        );

        $response = $this->aiService->generate($prompt, [
            'feature' => 'employer_job_optimization',
            'feature_group' => 'employer',
            'temperature' => 0.4,
            'max_tokens' => 2048,
            'response_mime_type' => 'application/json',
            'prompt_version' => AIPrompts::VERSION_EMPLOYER_OPTIMIZER,
            'user_type' => 'employer',
            'user_id' => $company->id,
        ]);

        $optimizedHtml = '';
        $skillTags = [];
        $suggestions = [];

        if ($response['success'] && !empty($response['text'])) {
            $text = trim($response['text']);
            if (preg_match('/```json(.*?)```/s', $text, $matches)) {
                $text = trim($matches[1]);
            }
            $json = json_decode($text, true);

            if (isset($json['optimized_description'])) {
                $rawOpt = $json['optimized_description'];
                $optimizedHtml = nl2br(e($rawOpt));
                $optimizedHtml = str_replace(["\n", "\r"], '', $optimizedHtml);
                $optimizedHtml = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $optimizedHtml);
            }

            if (isset($json['skill_tags']) && is_array($json['skill_tags'])) {
                $skillTags = $json['skill_tags'];
            }

            if (isset($json['improvement_suggestions']) && is_array($json['improvement_suggestions'])) {
                $suggestions = $json['improvement_suggestions'];
            }
        }

        if (empty($optimizedHtml)) {
            $optimizedHtml = "<strong>Role Overview:</strong><br>We are looking for a dedicated <strong>" . e($title ?: 'Professional') . "</strong> to join our team in " . e($location) . ".<br><br><strong>Key Responsibilities:</strong><br>• Manage and execute daily operations and project goals.<br>• Collaborate with cross-functional team members to achieve company milestones.<br><br><strong>Requirements:</strong><br>• Relevant knowledge or practical background in the field.<br>• Strong communication and organizational skills.";
            $skillTags = ['Communication', 'Teamwork', 'Problem Solving'];
        }

        $matchedSkillIds = [];
        if (!empty($skillTags)) {
            foreach ($skillTags as $st) {
                $dbSkill = JobSkill::where('job_skill', 'like', '%' . trim($st) . '%')->first();
                if ($dbSkill) {
                    $matchedSkillIds[] = (string) $dbSkill->id;
                }
            }
        }

        return response()->json([
            'success' => true,
            'optimized_description' => $optimizedHtml,
            'skill_tags' => $skillTags,
            'matched_skill_ids' => array_values(array_unique($matchedSkillIds)),
            'suggestions' => $suggestions,
            'cost_inr' => $response['cost_inr'] ?? 0,
            'latency_ms' => $response['response_time_ms'] ?? 0,
        ]);
    }
}
