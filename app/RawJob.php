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

    protected $appends = [
        'company_website',
        'company_email',
    ];

    public function getCompanyWebsiteAttribute()
    {
        if ($this->publishedJob && $this->publishedJob->company && !empty($this->publishedJob->company->website)) {
            return $this->publishedJob->company->website;
        }

        if (!empty($this->raw_payload)) {
            $payload = json_decode($this->raw_payload, true);
            if (is_array($payload) && !empty($payload['website'])) {
                return $payload['website'];
            }
        }

        if (!empty($this->raw_company) && $this->raw_company !== 'Direct Employer') {
            $company = Company::where('name', trim($this->raw_company))->first();
            if ($company && !empty($company->website)) {
                return $company->website;
            }
            $cleanDomain = str_replace('-', '', \Illuminate\Support\Str::slug($this->raw_company));
            if (!empty($cleanDomain)) {
                return 'https://www.' . $cleanDomain . '.com';
            }
        }

        return '';
    }

    public function getCompanyEmailAttribute()
    {
        if ($this->publishedJob && $this->publishedJob->company && !empty($this->publishedJob->company->email)) {
            return $this->publishedJob->company->email;
        }

        if (!empty($this->raw_payload)) {
            $payload = json_decode($this->raw_payload, true);
            if (is_array($payload) && !empty($payload['email'])) {
                return $payload['email'];
            }
        }

        if (!empty($this->raw_company) && $this->raw_company !== 'Direct Employer') {
            $company = Company::where('name', trim($this->raw_company))->first();
            if ($company && !empty($company->email) && !\Illuminate\Support\Str::contains($company->email, ['@company.com', '@featured.com'])) {
                return $company->email;
            }
            $cleanDomain = str_replace('-', '', \Illuminate\Support\Str::slug($this->raw_company));
            if (!empty($cleanDomain)) {
                return 'careers@' . $cleanDomain . '.com';
            }
        }

        return '';
    }

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
