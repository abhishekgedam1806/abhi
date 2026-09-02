<?php

namespace App;

use DB;
use App;
use App\Traits\Active;
use App\Traits\Featured;
use App\Traits\JobTrait;
use App\Traits\CountryStateCity;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{

    use Active;
    use featured;
    use JobTrait;
    use CountryStateCity;

    protected $table = 'jobs';
    public $timestamps = true;
    protected $guarded = ['id'];
    //protected $dateFormat = 'U';
    protected $dates = ['created_at', 'updated_at', 'expiry_date'];

    public function company()
    {
        return $this->belongsTo('App\Company', 'company_id', 'id');
    }

    public function getCompany($field = '')
    {
        $company = $this->relationLoaded('company') ? $this->company : $this->company()->first();
        if (null !== $company) {
            if (!empty($field)) {
                return $company->$field;
            } else {
                return $company;
            }
        }
    }

    public function jobSkills()
    {
        return $this->hasMany('App\JobSkillManager', 'job_id', 'id');
    }

    public function getJobSkillsArray()
    {
        return $this->jobSkills->pluck('job_skill_id')->toArray();
    }

    public function getJobSkillsStr()
    {
        $str = '';
        if ($this->jobSkills->count()) {
            $jobSkills = $this->jobSkills;
            foreach ($jobSkills as $jobSkillManager) {
                $str .= ' ' . $jobSkillManager->getJobSkill('job_skill');
            }
        }
        return $str;
    }

    public function getJobSkillsList()
    {
        $str = '';
        if ($this->jobSkills->count()) {
            $jobSkills = $this->jobSkills;
            foreach ($jobSkills as $jobSkillManager) {
                $skill = $jobSkillManager->getJobSkill();
                $str .= '<li><a href="' . route('job.list', ['job_skill_id[]' => $skill->job_skill_id]) . '">' . $skill->job_skill . '</a></li>';
            }
        }
        return $str;
    }

    public function careerLevel()
    {
        return $this->belongsTo('App\CareerLevel', 'career_level_id', 'career_level_id');
    }

    public function getCareerLevel($field = '')
    {
        $locale = \App::getLocale() ?: 'en';
        $careerLevel = null;
        if ($this->relationLoaded('careerLevel') && $this->careerLevel && $this->careerLevel->lang === $locale) {
            $careerLevel = $this->careerLevel;
        }
        if (null === $careerLevel) {
            $careerLevel = \App\CareerLevel::where('career_level_id', $this->career_level_id)->where('lang', $locale)->first();
            if (null === $careerLevel) {
                $careerLevel = \App\CareerLevel::where('career_level_id', $this->career_level_id)->where('lang', 'en')->first();
            }
            if (null === $careerLevel) {
                $careerLevel = $this->careerLevel()->first();
            }
        }
        if (null !== $careerLevel) {
            if (!empty($field)) {
                return $careerLevel->$field;
            } else {
                return $careerLevel;
            }
        }
        return '';
    }

    public function functionalArea()
    {
        return $this->belongsTo('App\FunctionalArea', 'functional_area_id', 'functional_area_id');
    }

    public function getFunctionalArea($field = '')
    {
        $locale = \App::getLocale() ?: 'en';
        $functionalArea = null;
        if ($this->relationLoaded('functionalArea') && $this->functionalArea && $this->functionalArea->lang === $locale) {
            $functionalArea = $this->functionalArea;
        }
        if (null === $functionalArea) {
            $functionalArea = \App\FunctionalArea::where('functional_area_id', $this->functional_area_id)->where('lang', $locale)->first();
            if (null === $functionalArea) {
                $functionalArea = \App\FunctionalArea::where('functional_area_id', $this->functional_area_id)->where('lang', 'en')->first();
            }
            if (null === $functionalArea) {
                $functionalArea = $this->functionalArea()->first();
            }
        }
        if (null !== $functionalArea) {
            if (!empty($field)) {
                return $functionalArea->$field;
            } else {
                return $functionalArea;
            }
        }
        return '';
    }

    public function jobType()
    {
        return $this->belongsTo('App\JobType', 'job_type_id', 'job_type_id');
    }

    public function getJobType($field = '')
    {
        $locale = \App::getLocale() ?: 'en';
        $jobType = null;
        if ($this->relationLoaded('jobType') && $this->jobType && $this->jobType->lang === $locale) {
            $jobType = $this->jobType;
        }
        if (null === $jobType) {
            $jobType = \App\JobType::where('job_type_id', $this->job_type_id)->where('lang', $locale)->first();
            if (null === $jobType) {
                $jobType = \App\JobType::where('job_type_id', $this->job_type_id)->where('lang', 'en')->first();
            }
            if (null === $jobType) {
                $jobType = $this->jobType()->first();
            }
        }
        if (null !== $jobType) {
            if (!empty($field)) {
                return $jobType->$field;
            } else {
                return $jobType;
            }
        }
        return '';
    }

    public function jobShift()
    {
        return $this->belongsTo('App\JobShift', 'job_shift_id', 'job_shift_id');
    }

    public function getJobShift($field = '')
    {
        $locale = \App::getLocale() ?: 'en';
        $jobShift = null;
        if ($this->relationLoaded('jobShift') && $this->jobShift && $this->jobShift->lang === $locale) {
            $jobShift = $this->jobShift;
        }
        if (null === $jobShift) {
            $jobShift = \App\JobShift::where('job_shift_id', $this->job_shift_id)->where('lang', $locale)->first();
            if (null === $jobShift) {
                $jobShift = \App\JobShift::where('job_shift_id', $this->job_shift_id)->where('lang', 'en')->first();
            }
            if (null === $jobShift) {
                $jobShift = $this->jobShift()->first();
            }
        }
        if (null !== $jobShift) {
            if (!empty($field)) {
                return $jobShift->$field;
            } else {
                return $jobShift;
            }
        }
        return '';
    }

    public function salaryPeriod()
    {
        return $this->belongsTo('App\SalaryPeriod', 'salary_period_id', 'salary_period_id');
    }

    public function getSalaryPeriod($field = '')
    {
        $locale = \App::getLocale() ?: 'en';
        $salaryPeriod = null;
        if ($this->relationLoaded('salaryPeriod') && $this->salaryPeriod && $this->salaryPeriod->lang === $locale) {
            $salaryPeriod = $this->salaryPeriod;
        }
        if (null === $salaryPeriod) {
            $salaryPeriod = \App\SalaryPeriod::where('salary_period_id', $this->salary_period_id)->where('lang', $locale)->first();
            if (null === $salaryPeriod) {
                $salaryPeriod = \App\SalaryPeriod::where('salary_period_id', $this->salary_period_id)->where('lang', 'en')->first();
            }
            if (null === $salaryPeriod) {
                $salaryPeriod = $this->salaryPeriod()->first();
            }
        }
        if (null !== $salaryPeriod) {
            if (!empty($field)) {
                return $salaryPeriod->$field;
            } else {
                return $salaryPeriod;
            }
        }
        return '';
    }

    public function gender()
    {
        return $this->belongsTo('App\Gender', 'gender_id', 'gender_id');
    }

    public function getGender($field = '')
    {
        $locale = \App::getLocale() ?: 'en';
        $gender = null;
        if ($this->relationLoaded('gender') && $this->gender && $this->gender->lang === $locale) {
            $gender = $this->gender;
        }
        if (null === $gender) {
            $gender = \App\Gender::where('gender_id', $this->gender_id)->where('lang', $locale)->first();
            if (null === $gender) {
                $gender = \App\Gender::where('gender_id', $this->gender_id)->where('lang', 'en')->first();
            }
            if (null === $gender) {
                $gender = $this->gender()->first();
            }
        }
        if (null !== $gender) {
            if (!empty($field)) {
                return $gender->$field;
            } else {
                return $gender;
            }
        } else {
            return __('No Preference');
        }
    }

    public function degreeLevel()
    {
        return $this->belongsTo('App\DegreeLevel', 'degree_level_id', 'degree_level_id');
    }

    public function getDegreeLevel($field = '')
    {
        $locale = \App::getLocale() ?: 'en';
        $degreeLevel = null;
        if ($this->relationLoaded('degreeLevel') && $this->degreeLevel && $this->degreeLevel->lang === $locale) {
            $degreeLevel = $this->degreeLevel;
        }
        if (null === $degreeLevel) {
            $degreeLevel = \App\DegreeLevel::where('degree_level_id', $this->degree_level_id)->where('lang', $locale)->first();
            if (null === $degreeLevel) {
                $degreeLevel = \App\DegreeLevel::where('degree_level_id', $this->degree_level_id)->where('lang', 'en')->first();
            }
            if (null === $degreeLevel) {
                $degreeLevel = $this->degreeLevel()->first();
            }
        }
        if (null !== $degreeLevel) {
            if (!empty($field)) {
                return $degreeLevel->$field;
            } else {
                return $degreeLevel;
            }
        }
        return '';
    }

    public function jobExperience()
    {
        return $this->belongsTo('App\JobExperience', 'job_experience_id', 'job_experience_id');
    }

    public function getJobExperience($field = '')
    {
        $locale = \App::getLocale() ?: 'en';
        $jobExperience = null;
        if ($this->relationLoaded('jobExperience') && $this->jobExperience && $this->jobExperience->lang === $locale) {
            $jobExperience = $this->jobExperience;
        }
        if (null === $jobExperience) {
            $jobExperience = \App\JobExperience::where('job_experience_id', $this->job_experience_id)->where('lang', $locale)->first();
            if (null === $jobExperience) {
                $jobExperience = \App\JobExperience::where('job_experience_id', $this->job_experience_id)->where('lang', 'en')->first();
            }
            if (null === $jobExperience) {
                $jobExperience = $this->jobExperience()->first();
            }
        }
        if (null !== $jobExperience) {
            if (!empty($field)) {
                return $jobExperience->$field;
            } else {
                return $jobExperience;
            }
        }
        return '';
    }

    /*     * ****************************** */

    public function appliedUsers()
    {
        return $this->hasMany('App\JobApply', 'job_id', 'id');
    }

    public function getAppliedUserIdsArray()
    {
        return $this->appliedUsers->pluck('user_id')->toArray();
    }

    /*     * ***************************** */
}
