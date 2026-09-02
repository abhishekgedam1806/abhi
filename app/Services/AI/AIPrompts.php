<?php

namespace App\Services\AI;

class AIPrompts
{
    // Prompt Versions
    const VERSION_JOB_QUALITY = 'job_quality_v1';
    const VERSION_CANDIDATE_PROFILE = 'candidate_profile_v3';
    const VERSION_CANDIDATE_POLISH = 'candidate_polish_v3';
    const VERSION_EMPLOYER_OPTIMIZER = 'employer_optimizer_v1';
    const VERSION_MATCH_EXPLANATION = 'match_explanation_v1';

    /**
     * Final Production Candidate "Write with AI" Prompt
     */
    public static function candidateWritePrompt(string $role, string $exp, string $skills, string $edu, array $ctx = []): string
    {
        $profileType    = $ctx['profile_type']    ?? 'not specified';
        $location       = $ctx['location']        ?? 'Not specified';
        $specialization = $ctx['specialization']  ?? '';
        $institution    = $ctx['institution_name']?? '';
        $percentage     = $ctx['degree_percentage']?? '';
        $degreeYears    = $ctx['degree_years']    ?? '';
        $prefRoles      = $ctx['preferred_roles'] ?? '';
        $workType       = $ctx['work_type']       ?? '';
        $workMode       = $ctx['work_mode']       ?? '';
        $completeness   = (int)($ctx['completeness'] ?? 0);

        // Strength instruction based on profile completeness
        $strengthNote = $completeness >= 60
            ? 'The candidate has provided rich profile information. Write a comprehensive, strong 3-paragraph summary using all provided details.'
            : ($completeness >= 30
                ? 'The candidate has moderate profile information. Write a solid 2-3 paragraph summary using what is available.'
                : 'The candidate has limited profile information. Write an honest, concise 1-2 paragraph summary without fabricating details.');

        return <<<EOT
You are a professional career profile writer for an Indian job portal. Write a strong, ATS-optimized profile summary using ONLY the verified information below.

{$strengthNote}

CANDIDATE PROFILE DATA:
Profile Type: {$profileType}
Target Role / Functional Area: {$role}
Location: {$location}
Preferred Job Roles: {$prefRoles}
Preferred Work Type: {$workType}
Work Mode: {$workMode}

EDUCATION:
{$edu}
College / University: {$institution}
Specialization: {$specialization}
Degree Years: {$degreeYears}
Percentage / Score: {$percentage}%

EXPERIENCE:
{$exp}

SKILLS:
{$skills}

STRICT RULES:
1. Use ONLY the information above — NEVER invent experience, companies, years, certifications, or achievements.
2. If a field is empty or "Not specified", skip it naturally — do NOT fabricate.
3. Do NOT write in first person (no "I", "my", "me").
4. The college name and percentage (if provided) MUST be mentioned naturally in the summary.
5. Skills MUST be included as ATS job-matching keywords in paragraph 2.

WRITING FORMAT:
- Paragraph 1 (2-3 sentences): Who the candidate is, their educational background (degree, college, percentage if available), and their profile type (fresher/experienced).
- Paragraph 2 (2-3 sentences): Technical skills and competencies using natural phrasing like "proficient in", "skilled in", "experienced with" — include ALL listed skills.
- Paragraph 3 (1-2 sentences, only if preferred roles or experience exist): Career objective — what kind of role/opportunity they are looking for, including preferred work type/mode if available.

Tone: Professional, natural, confident. No bullet points. No headings. No prohibited phrases: "results-driven", "passionate about", "dynamic professional", "proven track record", "team player", "detail-oriented", "leverage", "seamlessly", "highly motivated".

Total: 100-180 words. Paragraphs separated by \n\n.

Return ONLY valid JSON:
{
  "summary": "paragraph 1\n\nparagraph 2\n\nparagraph 3"
}
EOT;
    }

    /**
     * Final Production Candidate "Enhance / Polish" Prompt
     */
    public static function candidatePolishPrompt(string $existingSummary, string $role, string $exp, string $skills, string $edu, array $ctx = []): string
    {
        $institution  = $ctx['institution_name']  ?? '';
        $percentage   = $ctx['degree_percentage'] ?? '';
        $prefRoles    = $ctx['preferred_roles']   ?? '';
        $workType     = $ctx['work_type']         ?? '';
        $workMode     = $ctx['work_mode']         ?? '';
        $specialization = $ctx['specialization']  ?? '';

        return <<<EOT
You are a professional career profile editor for an Indian job portal. Enhance the existing summary using the full verified candidate context.

CANDIDATE CONTEXT:
Target Role: {$role}
Education: {$edu}
College: {$institution}
Specialization: {$specialization}
Percentage: {$percentage}%
Experience: {$exp}
Skills: {$skills}
Preferred Roles: {$prefRoles}
Work Type: {$workType} | Work Mode: {$workMode}

EXISTING SUMMARY TO ENHANCE:
"{$existingSummary}"

INSTRUCTIONS:
1. Rewrite into 2-3 paragraphs (\n\n separated).
2. Paragraph 1: Introduction with education, college name and percentage (if available) — mention them naturally.
3. Paragraph 2: Weave ALL skills from the Skills field as ATS keywords using "proficient in", "skilled in", "experienced with".
4. Paragraph 3: Career goal — include preferred roles and work type/mode if available.
5. Keep all original facts — do NOT invent companies, years, or certifications.
6. Improve grammar, clarity, ATS readability.
7. Banned phrases: "results-driven", "passionate about", "dynamic professional", "proven track record", "team player", "detail-oriented", "leverage", "seamlessly", "highly motivated".
8. No first person. Total 100-180 words.

Return ONLY valid JSON:
{
  "summary": "paragraph 1\n\nparagraph 2\n\nparagraph 3"
}
EOT;
    }

    /**
     * Job Ingestion & Normalization Prompt (Strict Factuality)
     */
    public static function jobIngestionPrompt(string $title, ?string $company, ?string $location, ?string $description, array $categories = []): string
    {
        $desc = substr(strip_tags($description ?: ''), 0, 3000);
        $companyName = $company ?: 'Not specified';
        $loc = $location ?: 'India';
        $catStr = !empty($categories) ? implode(', ', $categories) : 'Software & Web Development, Sales & Marketing, Human Resources, Finance & Accounts';

        return <<<EOT
You are a job listing quality analyst for an Indian job portal. Your task is to select ONLY genuine information and structure it cleanly.

STRICT FACTUAL RULES:
1. Never invent or assume facts (salary, experience, company). If not in text, use "Not specified".
2. Reject spam/scam patterns (e.g. "earn from home", "no interview").
3. Clean and normalize all fields.

Return ONLY JSON matching:
{
  "standardized_title": "Clean professional title",
  "company_name": "{$companyName}",
  "location": "{$loc}",
  "category": "Best match from [{$catStr}]",
  "job_type": "Full-time / Part-time / Contract / Internship / Not specified",
  "experience_required": "Extracted experience or Not specified",
  "salary_range": "Extracted salary or Not specified",
  "skills": ["skill1", "skill2", "skill3"],
  "seo_title": "SEO title under 60 chars",
  "seo_description": "Meta description under 155 chars",
  "clean_description": "Structured 100-150 word factual summary",
  "quality_score": 85
}

RAW JOB:
TITLE: {$title}
COMPANY: {$companyName}
LOCATION: {$loc}
DESCRIPTION:
{$desc}
EOT;
    }

    /**
     * Employer Job Description Optimizer Prompt
     */
    public static function employerOptimizerPrompt(string $title, string $company, string $location, string $rawRequirements, ?string $salary, ?string $jobType): string
    {
        $sal = $salary ?: 'Not specified';
        $type = $jobType ?: 'Full-time';

        return <<<EOT
You are a recruitment content specialist helping Indian employers write compelling job postings.

Employer Input:
- Job Title: {$title}
- Company: {$company}
- Location: {$location}
- Work Type: {$type}
- Salary: {$sal}
- Requirements / Details: {$rawRequirements}

Task:
1. Rewrite the job description into clean sections (Role Overview, Key Responsibilities, Requirements) in 120-180 words.
2. STRICT RULE: Do NOT invent perks, benefits, or requirements not mentioned.
3. Suggest 3-4 skill tags for applicant matching.
4. Suggest 2-3 improvements the employer should consider adding.

Return ONLY JSON:
{
  "optimized_description": "Formatted description with headings",
  "skill_tags": ["tag1", "tag2", "tag3"],
  "improvement_suggestions": ["Suggestion 1", "Suggestion 2"]
}
EOT;
    }

    /**
     * Grammarly / Wordtune Style Job Description Proofreader Prompt
     */
    public static function grammarlyProofreadPrompt(string $rawDescription, string $category): string
    {
        return <<<EOT
You are analyzing a job posting for grammar, spelling, and keyword completeness — like a proofreading tool. You are NOT rewriting or rephrasing the content.

Employer's job description:
{$rawDescription}

Job category: {$category}

TASK:
1. Find grammar and spelling mistakes ONLY. Do not touch sentences that are already correct.
2. For each mistake found, provide: the original wrong text, the corrected text, and a one-line reason.
3. Check if these important job-related keywords are missing (based on category "{$category}"): relevant skill names, job type (Full-time/Part-time), experience level, location detail. Suggest keywords to add — do NOT insert them automatically into the description.
4. Do NOT change sentence structure, word choice, or tone if there is no actual error. Correct grammar is not something to "improve" — leave it as is.
5. Do NOT summarize or shorten anything. Preserve 100% of the content length and structure.

Return ONLY this JSON:
{
  "corrections": [
    {
      "original": "the wrong text as written",
      "corrected": "the fixed version",
      "reason": "short reason, e.g. 'spelling mistake' or 'subject-verb agreement'"
    }
  ],
  "missing_keywords_suggestions": [
    "Consider adding required skill: React.js",
    "Consider specifying: Full-time or Part-time"
  ],
  "corrected_full_description": "the complete description with ONLY the corrections applied, structure and wording otherwise unchanged"
}
EOT;
    }
}
