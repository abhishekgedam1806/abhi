<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RawJob extends Model
{
    protected $table = 'raw_jobs';

    protected $fillable = [
        'source_id',
        'source_name',
        'source_url',
        'content_hash',
        'raw_title',
        'raw_company',
        'raw_location',
        'raw_description',
        'raw_payload',
        'status',
        'job_id',
    ];

    public function source()
    {
        return $this->belongsTo(JobSource::class, 'source_id');
    }

    public function publishedJob()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function aiData()
    {
        return $this->hasOne(JobAIData::class, 'raw_job_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeEnriched($query)
    {
        return $query->where('status', 'enriched');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
