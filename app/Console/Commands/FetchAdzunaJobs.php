<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AI\AdzunaJobFetcher;

class FetchAdzunaJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:fetch-adzuna {--cities= : Comma-separated list of target cities} {--days= : Maximum age of job postings in days} {--limit= : Target number of jobs to fetch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch real fresh jobs from Adzuna API, deduplicate, enrich with AI and publish to portal';

    /**
     * Execute the console command.
     *
     * @param AdzunaJobFetcher $fetcher
     * @return int
     */
    public function handle(AdzunaJobFetcher $fetcher)
    {
        $this->info('Starting automated Adzuna Job Ingestion & AI Pipeline...');

        $citiesArg = $this->option('cities');
        $cities = $citiesArg ? array_filter(array_map('trim', explode(',', $citiesArg))) : [];
        $days = $this->option('days') ? (int) $this->option('days') : null;
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;

        $result = $fetcher->fetchAndIngest($cities, $days, $limit);

        if ($result['success']) {
            $this->info($result['message']);
            $this->table(
                ['Fetched Total', 'New Ingested', 'AI Enriched', 'Auto-Published to Portal', 'Duplicates Skipped'],
                [[$result['fetched_total'], $result['inserted'], $result['enriched'] ?? 0, $result['published'] ?? 0, $result['duplicates']]]
            );
            return 0;
        } else {
            $this->warn($result['message']);
            return 1;
        }
    }
}
