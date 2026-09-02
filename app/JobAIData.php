<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobAIData extends Model
{
    protected $table = 'job_ai_data';

    protected $fillable = [
        'job_id',
        'raw_job_id',
        'quality_score',
        'quality_report',
        'extracted_skills',
        'suggested_category',
        'experience_level',
        'employment_type',
        'seo_title',
        'seo_description',
        'slug',
        'focus_keywords',
        'model',
        'provider',
        'last_analyzed_at',
    ];

    protected $casts = [
        'quality_score' => 'integer',
        'last_analyzed_at' => 'datetime',
    ];

    public function rawJob()
    {
        return $this->belongsTo(RawJob::class, 'raw_job_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function getSkillsArrayAttribute()
    {
        return !empty($this->extracted_skills) ? json_decode($this->extracted_skills, true) : [];
    }

    public function getKeywordsArrayAttribute()
    {
        return !empty($this->focus_keywords) ? json_decode($this->focus_keywords, true) : [];
    }

    public function getQualityReportArrayAttribute()
    {
        return !empty($this->quality_report) ? json_decode($this->quality_report, true) : [];
    }
}
