<?php

namespace App\Services\AI;

use App\RawJob;
use App\JobAIData;
use App\Services\AI\AIService;
use Illuminate\Support\Str;
use Exception;
use Log;

class JobEnricher
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Enrich a raw job using the active AI Provider
     *
     * @param RawJob $rawJob
     * @return array ['success' => bool, 'ai_data' => JobAIData|null, 'error' => string|null]
     */
    public function enrichRawJob(RawJob $rawJob): array
    {
        $prompt = $this->buildEnrichmentPrompt(
            $rawJob->raw_title,
            $rawJob->raw_company,
            $rawJob->raw_location,
            $rawJob->raw_description
        );

        $response = $this->aiService->generate($prompt, [
            'feature' => 'automated_job_classification',
            'feature_group' => 'automated_jobs',
            'temperature' => 0.2, // Low temperature for high precision & zero hallucination
            'max_tokens' => 1500,
            'system_instruction' => 'You are an expert HR Data Scientist & Job Taxonomy Specialist. Extract structured metadata strictly from the provided job details. NEVER invent or assume facts (like salary or experience) if not stated in the source text. Return ONLY valid JSON format.',
        ]);

        if (!$response['success']) {
            return [
                'success' => false,
                'ai_data' => null,
                'error' => $response['error'] ?? 'AI enrichment failed',
            ];
        }

        $parsedJson = $this->extractJson($response['text']);
        if (!$parsedJson) {
            // Fallback deterministic parsing if JSON decoding fails
            $parsedJson = $this->buildDeterministicFallback(
                $rawJob->raw_title,
                $rawJob->raw_location,
                $rawJob->raw_description
            );
        }

        // Save or update JobAIData
        $aiData = JobAIData::updateOrCreate(
            ['raw_job_id' => $rawJob->id],
            [
                'quality_score' => (int) ($parsedJson['quality_score'] ?? 80),
                'quality_report' => json_encode($parsedJson['quality_report'] ?? ['score' => 80, 'notes' => 'AI enriched']),
                'extracted_skills' => json_encode($parsedJson['extracted_skills'] ?? []),
                'suggested_category' => $parsedJson['functional_area'] ?? 'Information Technology',
                'experience_level' => $parsedJson['experience_level'] ?? 'Not specified',
                'employment_type' => $parsedJson['employment_type'] ?? 'Full Time',
                'seo_title' => $parsedJson['seo_title'] ?? $rawJob->raw_title,
                'seo_description' => $parsedJson['meta_description'] ?? substr(strip_tags($rawJob->raw_description), 0, 160),
                'slug' => $parsedJson['slug'] ?? Str::slug($rawJob->raw_title . '-' . ($rawJob->raw_location ?: 'india')),
                'focus_keywords' => json_encode($parsedJson['focus_keywords'] ?? []),
                'model' => $response['model'] ?? 'gemini-1.5-flash',
                'provider' => $response['provider'] ?? 'gemini',
                'last_analyzed_at' => now(),
            ]
        );

        $rawJob->status = 'enriched';
        $rawJob->save();

        return [
            'success' => true,
            'ai_data' => $aiData,
            'parsed' => $parsedJson,
            'cost_inr' => $response['cost_inr'] ?? 0.0,
            'latency_ms' => $response['response_time_ms'] ?? 0,
        ];
    }

    /**
     * Build strict structured prompt
     */
    protected function buildEnrichmentPrompt(string $title, ?string $company, ?string $location, ?string $description): string
    {
        $descSnippet = substr(strip_tags($description ?: ''), 0, 2500);

        return <<<EOT
Analyze the following job posting and return structured JSON matching this EXACT schema:

JOB TITLE: {$title}
COMPANY: {$company}
LOCATION: {$location}
JOB DESCRIPTION:
{$descSnippet}

CRITICAL RULES:
1. "extracted_skills": Array of 3 to 8 clean, standardized skill tags (e.g. ["PHP", "Laravel", "MySQL", "REST API"]).
2. "experience_level": Extract exact required experience (e.g. "2-4 years" or "Fresher"). If not mentioned in text, return "Not specified". DO NOT INVENT.
3. "quality_score": Integer 0 to 100 based on clarity, responsibilities, and completeness.
4. "functional_area": Suggested standard job category (e.g. "Software & Web Development", "Sales & Marketing", "Human Resources", "Finance & Accounts", "Customer Support").
5. "employment_type": "Full Time", "Part Time", "Contract", "Freelance", or "Internship".
6. "seo_title": SEO optimized headline (e.g. "PHP Laravel Developer Jobs in Nagpur").
7. "meta_description": 150-160 character engaging search description.
8. "slug": Clean URL slug.
9. "focus_keywords": Array of 3 to 5 relevant search keywords.

Return ONLY the JSON object:
{
  "standardized_title": "string",
  "functional_area": "string",
  "extracted_skills": ["string"],
  "experience_level": "string",
  "employment_type": "string",
  "quality_score": 85,
  "quality_report": {
    "has_clear_title": true,
    "has_responsibilities": true,
    "has_skills": true,
    "missing_elements": []
  },
  "seo_title": "string",
  "meta_description": "string",
  "slug": "string",
  "focus_keywords": ["string"]
}
EOT;
    }

    /**
     * Safely extract JSON from model output
     */
    protected function extractJson(string $text): ?array
    {
        $text = trim($text);
        // Remove markdown code fences if present
        if (preg_match('/```json(.*?)```/s', $text, $matches)) {
            $text = trim($matches[1]);
        } elseif (preg_match('/```(.*?)```/s', $text, $matches)) {
            $text = trim($matches[1]);
        }

        $decoded = json_decode($text, true);
        return is_array($decoded) ? $decoded : null;
    }

    /**
     * Fallback deterministic metadata extraction if AI response was unparseable
     */
    protected function buildDeterministicFallback(string $title, ?string $location, ?string $description): array
    {
        $loc = $location ?: 'India';
        return [
            'standardized_title' => $title,
            'functional_area' => 'Information Technology',
            'extracted_skills' => ['General Skills'],
            'experience_level' => 'Not specified',
            'employment_type' => 'Full Time',
            'quality_score' => 75,
            'quality_report' => ['has_clear_title' => true, 'notes' => 'Fallback parsed'],
            'seo_title' => "{$title} Jobs in {$loc}",
            'meta_description' => "Apply for {$title} jobs in {$loc}. View requirements, skills and application details.",
            'slug' => Str::slug("{$title}-jobs-{$loc}"),
            'focus_keywords' => ["{$title} jobs", "{$title} in {$loc}"],
        ];
    }
}
