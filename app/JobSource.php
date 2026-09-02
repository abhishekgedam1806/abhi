<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobSource extends Model
{
    protected $table = 'job_sources';

    protected $fillable = [
        'name',
        'source_type',
        'feed_url',
        'is_active',
        'last_synced_at',
        'jobs_collected_count',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_synced_at' => 'datetime',
        'jobs_collected_count' => 'integer',
    ];

    public function rawJobs()
    {
        return $this->hasMany(RawJob::class, 'source_id');
    }
}
