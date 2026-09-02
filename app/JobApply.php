<?php



namespace App;



use DB;

use App;

use Illuminate\Database\Eloquent\Model;



class JobApply extends Model

{



    protected $table = 'job_apply';

    public $timestamps = true;

    protected $guarded = ['id'];

    //protected $dateFormat = 'U';

    protected $dates = ['created_at', 'updated_at'];



    public function user()

    {

        return $this->belongsTo('App\User', 'user_id', 'id');

    }



    public function getUser($field = '')

    {

        if (null !== $user = $this->user()->first()) {

            if (!empty($field)) {

                return $user->$field;

            } else {

                return $user;

            }

        }

    }



    public function job()

    {

        return $this->belongsTo('App\Job', 'job_id', 'id');

    }



    public function getJob($field = '')

    {

        if (null !== $job = $this->job()->first()) {

            if (!empty($field)) {

                return $job->$field;

            } else {

                return $job;

            }

        }

    }



    public function profileCv()

    {

        return $this->belongsTo('App\ProfileCv', 'cv_id', 'id');

    }



    public function getProfileCv($field = '')

    {

        if (null !== $profileCv = $this->profileCv()->first()) {
            if (!empty($field)) {
                return $profileCv->$field;
            } else {
                return $profileCv;
            }
        }
    }

    public function getApplicationStatus()
    {
        // Check favourite_applicants table first
        $isShortlisted = \App\FavouriteApplicant::where('user_id', $this->user_id)
            ->where('job_id', $this->job_id)
            ->exists();

        if ($isShortlisted) {
            return 'shortlisted';
        }

        return $this->status ?: 'applied';
    }

    public function getStatusBadgeInfo()
    {
        $status = $this->getApplicationStatus();
        switch($status) {
            case 'shortlisted':
                return ['label' => __('Shortlisted'), 'class' => 'badge-shortlisted', 'color' => '#03855c', 'bg' => '#ECFDF5', 'border' => '#A7F3D0'];
            case 'interview_scheduled':
                return ['label' => __('Interview Scheduled'), 'class' => 'badge-interview', 'color' => '#2563EB', 'bg' => '#EFF6FF', 'border' => '#BFDBFE'];
            case 'interview_completed':
                return ['label' => __('Interview Completed'), 'class' => 'badge-interview-done', 'color' => '#4F46E5', 'bg' => '#EEF2FF', 'border' => '#C7D2FE'];
            case 'selected':
                return ['label' => __('Selected'), 'class' => 'badge-selected', 'color' => '#03855c', 'bg' => '#D1FAE5', 'border' => '#6EE7B7'];
            case 'rejected':
                return ['label' => __('Rejected'), 'class' => 'badge-rejected', 'color' => '#DC2626', 'bg' => '#FEF2F2', 'border' => '#FECACA'];
            case 'withdrawn':
                return ['label' => __('Withdrawn'), 'class' => 'badge-withdrawn', 'color' => '#64748B', 'bg' => '#F1F5F9', 'border' => '#E2E8F0'];
            case 'under_review':
                return ['label' => __('Under Review'), 'class' => 'badge-review', 'color' => '#D97706', 'bg' => '#FFFBEB', 'border' => '#FDE68A'];
            case 'applied':
            default:
                return ['label' => __('Job Applied'), 'class' => 'badge-applied', 'color' => '#E11D48', 'bg' => '#FFF1F2', 'border' => '#FECDD3'];
        }
    }

    public function getFormattedJobId()
    {
        $job = $this->getJob();
        if (!$job) return 'JOB-' . str_pad($this->job_id, 6, '0', STR_PAD_LEFT);
        
        $prefix = 'JOB';
        if (!empty($job->title)) {
            $cleanTitle = preg_replace('/[^a-zA-Z\s]/', '', $job->title);
            $words = preg_split("/\s+/", trim($cleanTitle));
            if (!empty($words[0])) {
                $code = strtoupper(substr($words[0], 0, 3));
                if (ctype_alpha($code) && strlen($code) >= 2) {
                    $prefix = str_pad($code, 3, 'X');
                }
            }
        }
        return $prefix . '-' . str_pad($job->id, 6, '0', STR_PAD_LEFT);
    }

}

