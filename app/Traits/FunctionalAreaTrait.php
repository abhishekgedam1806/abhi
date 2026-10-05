<?php

namespace App\Traits;

use DB;
use App\Job;
use Carbon\Carbon;

trait FunctionalAreaTrait
{

    private function getFunctionalAreaIdsAndNumJobs($limit = 16)
    {
        return Job::select('functional_area_id', DB::raw('COUNT(jobs.functional_area_id) AS num_jobs'))
                        ->notExpire()
                        ->active()
                        ->groupBy('functional_area_id')
                        ->orderBy('num_jobs', 'DESC')
                        ->limit($limit)
                        ->get();
    }

}
