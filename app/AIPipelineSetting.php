<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AIPipelineSetting extends Model
{
    protected $table = 'ai_pipeline_settings';

    protected $fillable = [
        'daily_fetch_limit',
        'auto_publish',
        'auto_enrich',
        'min_quality_score',
        'target_cities',
        'max_job_age_days',
        'target_categories',
    ];

    protected $casts = [
        'daily_fetch_limit' => 'integer',
        'auto_publish' => 'boolean',
        'auto_enrich' => 'boolean',
        'min_quality_score' => 'integer',
        'max_job_age_days' => 'integer',
    ];

    /**
     * Get or create default pipeline settings
     */
    public static function getSettings()
    {
        $settings = self::first();
        if (!$settings) {
            $settings = self::create([
                'daily_fetch_limit' => 5,
                'auto_publish' => 1,
                'auto_enrich' => 1,
                'min_quality_score' => 70,
                'target_cities' => 'Nagpur, Mumbai, Pune, Delhi, Bangalore',
                'max_job_age_days' => 7,
            ]);
        }
        return $settings;
    }
}
