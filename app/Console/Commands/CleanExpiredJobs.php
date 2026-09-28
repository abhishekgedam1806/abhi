<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CleanExpiredJobs extends Command
{
    /**
     * The name and signature of the console command.
     * Usage: php artisan jobs:clean-expired
     */
    protected $signature = 'jobs:clean-expired';

    /**
     * The console command description.
     */
    protected $description = 'Delete expired jobs (older than 30 days past expiry) and clean related data. Keeps DB lean.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Only delete jobs expired MORE than 30 days ago
        // This gives a grace period — fresh expired jobs stay for 30 days
        // So employers/seekers don't hit 404 on recently expired jobs
        $cutoffDate = Carbon::now()->subDays(30);

        // Step 1: Get IDs and slugs of jobs to delete
        $expiredJobs = DB::table('jobs')
            ->where('expiry_date', '<', $cutoffDate)
            ->select('id', 'slug')
            ->get();

        if ($expiredJobs->isEmpty()) {
            $this->info('[' . now() . '] No expired jobs to clean. Database is clean.');
            return 0;
        }

        $ids   = $expiredJobs->pluck('id')->toArray();
        $slugs = $expiredJobs->pluck('slug')->filter()->toArray();

        $this->info('[' . now() . '] Found ' . count($ids) . ' expired jobs to remove...');

        // Step 2: Delete related records first (foreign key safe)
        DB::table('manage_job_skills')->whereIn('job_id', $ids)->delete();
        DB::table('job_apply')->whereIn('job_id', $ids)->delete();
        DB::table('job_ai_data')->whereIn('job_id', $ids)->delete();
        DB::table('job_alerts')->whereIn('job_id', $ids)->delete();

        if (!empty($slugs)) {
            DB::table('favourites_job')->whereIn('job_slug', $slugs)->delete();
        }

        // Step 3: Delete the expired jobs
        $deleted = DB::table('jobs')->whereIn('id', $ids)->delete();

        $this->info('[' . now() . '] Deleted ' . $deleted . ' expired jobs successfully.');
        $this->info('[' . now() . '] Related records (skills, applications, favourites) also cleaned.');

        return 0;
    }
}
