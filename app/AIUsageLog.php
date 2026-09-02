<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AIUsageLog extends Model
{
    protected $table = 'ai_usage_logs';

    protected $fillable = [
        'provider_id',
        'provider_type',
        'model',
        'feature',
        'feature_group',
        'input_tokens',
        'output_tokens',
        'total_tokens',
        'response_time_ms',
        'estimated_cost_inr',
        'currency',
        'is_success',
        'error_message',
        'user_type',
        'user_id',
        'metadata',
    ];

    protected $casts = [
        'is_success' => 'boolean',
        'input_tokens' => 'integer',
        'output_tokens' => 'integer',
        'total_tokens' => 'integer',
        'response_time_ms' => 'integer',
        'estimated_cost_inr' => 'float',
    ];

    /**
     * Relationship to Provider
     */
    public function provider()
    {
        return $this->belongsTo(AIProvider::class, 'provider_id');
    }

    /**
     * Feature Definitions
     */
    public static function getFeaturesList()
    {
        return [
            // Candidate side
            'candidate_profile_analysis' => ['name' => 'Profile Analysis', 'group' => 'candidate', 'icon' => 'fa-user'],
            'candidate_resume_analysis' => ['name' => 'Resume Analysis', 'group' => 'candidate', 'icon' => 'fa-file-text-o'],
            'candidate_skill_analysis' => ['name' => 'Skill Analysis', 'group' => 'candidate', 'icon' => 'fa-cogs'],
            'candidate_job_recommendations' => ['name' => 'Job Recommendations', 'group' => 'candidate', 'icon' => 'fa-lightbulb-o'],
            'candidate_match_explanation' => ['name' => 'Match Explanation', 'group' => 'candidate', 'icon' => 'fa-percent'],

            // Employer side
            'employer_job_optimization' => ['name' => 'Job Optimization', 'group' => 'employer', 'icon' => 'fa-magic'],
            'employer_job_quality_score' => ['name' => 'Job Quality Score', 'group' => 'employer', 'icon' => 'fa-star-half-o'],
            'employer_skill_suggestions' => ['name' => 'Skill Suggestions', 'group' => 'employer', 'icon' => 'fa-tags'],
            'employer_candidate_matching' => ['name' => 'Candidate Matching', 'group' => 'employer', 'icon' => 'fa-users'],
            'employer_candidate_ranking' => ['name' => 'Candidate Ranking', 'group' => 'employer', 'icon' => 'fa-sort-amount-desc'],
            'employer_job_description_improvement' => ['name' => 'Job Description Polish', 'group' => 'employer', 'icon' => 'fa-pencil-square-o'],

            // Automated Jobs System
            'automated_job_classification' => ['name' => 'Job Classification', 'group' => 'automated_jobs', 'icon' => 'fa-sitemap'],
            'automated_skill_extraction' => ['name' => 'Skill Extraction', 'group' => 'automated_jobs', 'icon' => 'fa-wrench'],
            'automated_job_summarization' => ['name' => 'Job Summarization', 'group' => 'automated_jobs', 'icon' => 'fa-compress'],
            'automated_seo_optimization' => ['name' => 'SEO Optimization', 'group' => 'automated_jobs', 'icon' => 'fa-line-chart'],
            'automated_quality_analysis' => ['name' => 'Job Quality Analysis', 'group' => 'automated_jobs', 'icon' => 'fa-check-circle-o'],

            // System Test
            'system_connection_test' => ['name' => 'Connection Test', 'group' => 'system', 'icon' => 'fa-plug'],
        ];
    }

    /**
     * Scope filter by days (e.g. 7, 30, 90)
     */
    public function scopeWithinDays($query, $days = 30)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }
}
