<?php

namespace App\Services\AI;

use App\RawJob;
use App\JobSource;
use App\AIPipelineSetting;
use App\Services\AI\JobDuplicateDetector;
use App\Services\AI\JobEnricher;
use App\Services\AI\JobPublisher;
use Carbon\Carbon;
use Exception;
use Log;

class AdzunaJobFetcher
{
    protected $appId;
    protected $appKey;
    protected $country;

    public function __construct()
    {
        $this->appId = env('ADZUNA_APP_ID', config('services.adzuna.app_id', ''));
        $this->appKey = env('ADZUNA_APP_KEY', config('services.adzuna.app_key', ''));
        $this->country = env('ADZUNA_COUNTRY', 'in'); // India default
    }

    /**
     * Fetch real fresh jobs from Adzuna API, deduplicate, and automatically enrich/publish as configured
     *
     * @param array $cities Optional custom cities list
     * @param int|null $maxDays Maximum age of job in days (default from settings)
     * @param int|null $limit Total number of jobs to ingest (default from settings: e.g. 5)
     * @param bool|null $autoPublish Override auto publish setting
     * @return array
     */
    public function fetchAndIngest(array $cities = [], ?int $maxDays = null, ?int $limit = null, ?bool $autoPublish = null): array
    {
        if (empty($this->appId) || empty($this->appKey)) {
            return [
                'success' => false,
                'fetched_total' => 0,
                'inserted' => 0,
                'enriched' => 0,
                'published' => 0,
                'duplicates' => 0,
                'message' => 'ADZUNA_APP_ID and ADZUNA_APP_KEY are not configured in .env. Please check your API credentials.',
            ];
        }

        // Load pipeline settings
        $settings = AIPipelineSetting::getSettings();
        $targetLimit = $limit !== null ? $limit : ($settings->daily_fetch_limit ?: 5);
        $jobMaxDays = $maxDays !== null ? $maxDays : ($settings->max_job_age_days ?: 7);
        $shouldAutoPublish = $autoPublish !== null ? $autoPublish : (bool)$settings->auto_publish;
        $shouldAutoEnrich = (bool)$settings->auto_enrich;
        $minQuality = $settings->min_quality_score ?: 70;

        $targetCities = !empty($cities) ? $cities : array_filter(array_map('trim', explode(',', $settings->target_cities ?: 'Nagpur, Mumbai, Pune, Delhi, Bangalore')));
        if (empty($targetCities)) {
            $targetCities = ['Nagpur', 'Mumbai', 'Pune', 'Delhi', 'Bangalore'];
        }

        $totalFetched = 0;
        $insertedCount = 0;
        $enrichedCount = 0;
        $publishedCount = 0;
        $duplicatesCount = 0;
        $errors = [];
        $cutoffDate = Carbon::now()->subDays($jobMaxDays);

        // Services for Auto-Enrichment and Auto-Publishing
        $enricher = app(JobEnricher::class);
        $publisher = app(JobPublisher::class);

        foreach ($targetCities as $city) {
            // Stop if we have reached the daily target limit
            if ($insertedCount >= $targetLimit) {
                break;
            }

            try {
                // Determine how many to fetch for this city to reach target limit
                $remainingNeeded = $targetLimit - $insertedCount;
                $resultsPerPage = min(10, max(2, $remainingNeeded * 2)); // slight buffer for duplicate rejection

                $endpoint = "https://api.adzuna.com/v1/api/jobs/{$this->country}/search/1";
                $queryParams = http_build_query([
                    'app_id'           => $this->appId,
                    'app_key'          => $this->appKey,
                    'where'            => trim($city),
                    'results_per_page' => $resultsPerPage,
                    'sort_by'          => 'date',
                    'max_days_old'     => $jobMaxDays,
                ]);

                $url = "{$endpoint}?{$queryParams}";
                $response = $this->sendGetRequest($url);

                if (!empty($response['results']) && is_array($response['results'])) {
                    foreach ($response['results'] as $item) {
                        if ($insertedCount >= $targetLimit) {
                            break 2; // Target limit met across all cities
                        }

                        $totalFetched++;

                        // 1. Check Date Freshness (must be within last 5-7 days)
                        if (!empty($item['created'])) {
                            try {
                                $createdDate = Carbon::parse($item['created']);
                                if ($createdDate->lt($cutoffDate)) {
                                    continue; // Skip older jobs
                                }
                            } catch (Exception $e) {
                                // continue if parse fails
                            }
                        }

                        // 2. Extract and sanitize clean metadata
                        $title = trim(strip_tags($item['title'] ?? ''));
                        if (empty($title)) {
                            continue;
                        }

                        $company = trim($item['company']['display_name'] ?? 'Direct Employer');
                        $location = trim($item['location']['display_name'] ?? $city . ', India');
                        $description = trim(strip_tags($item['description'] ?? ''));
                        $sourceUrl = trim($item['redirect_url'] ?? '');

                        if (empty($description)) {
                            $description = "{$title} vacancy at {$company} located in {$location}.";
                        }

                        // 3. Deduplication Check (Exact SHA-256 Fingerprint)
                        $contentHash = JobDuplicateDetector::generateHash($company, $title, $location);

                        if (JobDuplicateDetector::isDuplicate($contentHash)) {
                            $duplicatesCount++;
                            continue; // Discard duplicate at ₹0 cost
                        }

                        // 4. Ingest into Raw Ingestion Queue
                        $rawJob = new RawJob();
                        $rawJob->source_name = 'Adzuna Job API';
                        $rawJob->source_url = $sourceUrl;
                        $rawJob->content_hash = $contentHash;
                        $rawJob->raw_title = $title;
                        $rawJob->raw_company = $company;
                        $rawJob->raw_location = $location;
                        $rawJob->raw_description = $description;
                        $rawJob->raw_payload = json_encode([
                            'adzuna_id'       => $item['id'] ?? null,
                            'salary_min'      => $item['salary_min'] ?? null,
                            'salary_max'      => $item['salary_max'] ?? null,
                            'contract_time'   => $item['contract_time'] ?? null,
                            'contract_type'   => $item['contract_type'] ?? null,
                            'created_at_api'  => $item['created'] ?? null,
                            'redirect_url'    => $sourceUrl,
                        ]);
                        $rawJob->status = 'pending';
                        $rawJob->save();

                        $insertedCount++;

                        // 5. Auto-Enrichment via Gemini AI (if enabled)
                        if ($shouldAutoEnrich) {
                            try {
                                $enrichResult = $enricher->enrichRawJob($rawJob);
                                if (!empty($enrichResult['success'])) {
                                    $enrichedCount++;
                                    $rawJob->refresh();

                                    // 6. Auto-Publish to Live Job Portal (if enabled and meets min quality score)
                                    if ($shouldAutoPublish) {
                                        $aiData = $rawJob->aiData;
                                        $score = $aiData ? $aiData->quality_score : 80;
                                        if ($score >= $minQuality) {
                                            $publisher->publish($rawJob);
                                            $publishedCount++;
                                        }
                                    }
                                }
                            } catch (Exception $e) {
                                Log::warning("Auto-enrichment error for job #{$rawJob->id}: " . $e->getMessage());
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                $errors[] = "Error fetching {$city}: " . $e->getMessage();
                Log::warning("Adzuna Fetcher error for {$city}: " . $e->getMessage());
            }
        }

        // Update JobSource tracker
        try {
            $source = JobSource::firstOrCreate(
                ['name' => 'Adzuna Job API'],
                ['source_type' => 'api', 'feed_url' => 'https://api.adzuna.com/v1/api/jobs/in/search']
            );
            $source->last_synced_at = Carbon::now();
            $source->jobs_collected_count = $source->jobs_collected_count + $insertedCount;
            $source->save();
        } catch (Exception $e) {
            // ignore
        }

        $summaryMsg = "Fetched target of {$insertedCount} fresh jobs (Target Limit: {$targetLimit}).";
        if ($publishedCount > 0) {
            $summaryMsg .= " {$publishedCount} jobs automatically published live to the Job Portal!";
        } elseif ($enrichedCount > 0) {
            $summaryMsg .= " {$enrichedCount} jobs enriched and ready for review in pipeline.";
        }

        return [
            'success'       => true,
            'fetched_total' => $totalFetched,
            'inserted'      => $insertedCount,
            'enriched'      => $enrichedCount,
            'published'     => $publishedCount,
            'duplicates'    => $duplicatesCount,
            'errors'        => $errors,
            'message'       => $summaryMsg,
        ];
    }

    /**
     * Preview jobs by keyword — NO DB writes, returns raw job data for admin review.
     * Admin selects which jobs to add; addSelectedToQueue() handles the actual save.
     */
    public function previewByKeyword(string $keyword, string $country = 'in', string $location = '', int $limit = 10, int $maxDays = 30, string $jobType = ''): array
    {
        if (empty($this->appId) || empty($this->appKey)) {
            return ['success' => false, 'jobs' => [], 'message' => 'ADZUNA_APP_ID and ADZUNA_APP_KEY are not configured in .env.'];
        }

        $jobs = [];

        try {
            $endpoint = "https://api.adzuna.com/v1/api/jobs/{$country}/search/1";
            $searchWhat = trim($keyword);

            if ($jobType === 'remote' || $jobType === 'work_from_home') {
                if (stripos($searchWhat, 'remote') === false && stripos($searchWhat, 'work from home') === false && stripos($searchWhat, 'wfh') === false) {
                    $searchWhat .= ' remote';
                }
            } elseif ($jobType === 'internship') {
                if (stripos($searchWhat, 'intern') === false) {
                    $searchWhat .= ' internship';
                }
            }

            $params   = [
                'app_id'           => $this->appId,
                'app_key'          => $this->appKey,
                'what'             => $searchWhat,
                'results_per_page' => min(50, max(5, $limit * 2)),
                'sort_by'          => 'date',
                'max_days_old'     => $maxDays,
                'content-type'     => 'application/json',
            ];

            if ($jobType === 'full_time') {
                $params['full_time'] = 1;
            } elseif ($jobType === 'part_time') {
                $params['part_time'] = 1;
            } elseif ($jobType === 'contract') {
                $params['contract'] = 1;
            }

            if (!empty(trim($location))) {
                $params['where'] = trim($location);
            }

            $url      = $endpoint . '?' . http_build_query($params);
            $response = $this->sendGetRequest($url);

            if (!empty($response['results']) && is_array($response['results'])) {
                $count = 0;
                foreach ($response['results'] as $item) {
                    if ($count >= $limit) {
                        break;
                    }

                    $title = trim(strip_tags($item['title'] ?? ''));
                    if (empty($title)) {
                        continue;
                    }

                    $company     = trim($item['company']['display_name'] ?? 'Direct Employer');
                    $loc         = trim($item['location']['display_name'] ?? ($location ?: 'India'));
                    $description = trim(strip_tags($item['description'] ?? ''));
                    $sourceUrl   = trim($item['redirect_url'] ?? '');

                    if (empty($description)) {
                        $description = "{$title} vacancy at {$company} in {$loc}.";
                    }

                    // Mark existing duplicates so admin knows
                    $contentHash = JobDuplicateDetector::generateHash($company, $title, $loc);
                    $isDuplicate = JobDuplicateDetector::isDuplicate($contentHash);

                    // Salary formatting
                    $salaryMin = $item['salary_min'] ?? null;
                    $salaryMax = $item['salary_max'] ?? null;
                    $salary    = '';
                    if ($salaryMin || $salaryMax) {
                        $salary = '₹' . number_format((float)($salaryMin ?: $salaryMax) / 100000, 1) . 'L';
                        if ($salaryMax && $salaryMax != $salaryMin) {
                            $salary .= ' – ₹' . number_format((float)$salaryMax / 100000, 1) . 'L';
                        }
                    }

                    // Posted date
                    $postedDate   = $item['created'] ?? null;
                    $postedLabel  = '';
                    if ($postedDate) {
                        try {
                            $d           = Carbon::parse($postedDate);
                            $postedLabel = $d->diffForHumans();
                        } catch (\Exception $e) {
                            $postedLabel = $postedDate;
                        }
                    }

                    $jobs[] = [
                        'adzuna_id'      => $item['id'] ?? null,
                        'title'          => $title,
                        'company'        => $company,
                        'location'       => $loc,
                        'description'    => $description,
                        'source_url'     => $sourceUrl,
                        'created'        => $postedDate,
                        'posted_label'   => $postedLabel,
                        'salary'         => $salary,
                        'salary_min'     => $salaryMin,
                        'salary_max'     => $salaryMax,
                        'contract_time'  => $item['contract_time'] ?? null,
                        'contract_type'  => $item['contract_type'] ?? null,
                        'content_hash'   => $contentHash,
                        'is_duplicate'   => $isDuplicate,
                        'country'        => $country,
                        'keyword'        => $keyword,
                    ];
                    $count++;
                }
            }
        } catch (\Exception $e) {
            Log::warning("Adzuna preview error [{$keyword}]: " . $e->getMessage());
            return ['success' => false, 'jobs' => [], 'message' => $e->getMessage()];
        }

        return [
            'success' => true,
            'jobs'    => $jobs,
            'total'   => count($jobs),
            'new'     => count(array_filter($jobs, fn($j) => !$j['is_duplicate'])),
        ];
    }

    /**
     * Keyword-targeted job search via Adzuna API

     * Used for manual "Smart Keyword Fetch" (SEO, Software, Digital Marketing etc.)
     *
     * @param string $keyword  e.g. "SEO", "Software Engineer", "Digital Marketing"
     * @param string $country  Adzuna country code: 'in' (India), 'gb', 'us', 'au' etc.
     * @param string $location City/location to target (optional)
     * @param int    $limit    Max jobs to ingest
     * @param int    $maxDays  Max age in days
     * @return array
     */
    public function fetchByKeyword(string $keyword, string $country = 'in', string $location = '', int $limit = 10, int $maxDays = 30, string $jobType = ''): array
    {
        if (empty($this->appId) || empty($this->appKey)) {
            return [
                'success'  => false,
                'inserted' => 0,
                'message'  => 'ADZUNA_APP_ID and ADZUNA_APP_KEY are not configured in .env.',
            ];
        }

        $settings         = AIPipelineSetting::getSettings();
        $shouldAutoEnrich = (bool) $settings->auto_enrich;
        $minQuality       = $settings->min_quality_score ?: 70;

        $insertedCount   = 0;
        $duplicatesCount = 0;
        $enrichedCount   = 0;
        $errors          = [];
        $cutoffDate      = Carbon::now()->subDays($maxDays);

        $enricher  = app(JobEnricher::class);
        $publisher = app(JobPublisher::class);

        try {
            $endpoint    = "https://api.adzuna.com/v1/api/jobs/{$country}/search/1";
            $searchWhat  = trim($keyword);

            if ($jobType === 'remote' || $jobType === 'work_from_home') {
                if (stripos($searchWhat, 'remote') === false && stripos($searchWhat, 'work from home') === false && stripos($searchWhat, 'wfh') === false) {
                    $searchWhat .= ' remote';
                }
            } elseif ($jobType === 'internship') {
                if (stripos($searchWhat, 'intern') === false) {
                    $searchWhat .= ' internship';
                }
            }

            $params      = [
                'app_id'           => $this->appId,
                'app_key'          => $this->appKey,
                'what'             => $searchWhat,
                'results_per_page' => min(50, max(5, $limit * 2)), // buffer for dupes
                'sort_by'          => 'date',
                'max_days_old'     => $maxDays,
            ];

            if ($jobType === 'full_time') {
                $params['full_time'] = 1;
            } elseif ($jobType === 'part_time') {
                $params['part_time'] = 1;
            } elseif ($jobType === 'contract') {
                $params['contract'] = 1;
            }

            if (!empty(trim($location))) {
                $params['where'] = trim($location);
            }

            $url      = $endpoint . '?' . http_build_query($params);
            $response = $this->sendGetRequest($url);

            if (!empty($response['results']) && is_array($response['results'])) {
                foreach ($response['results'] as $item) {
                    if ($insertedCount >= $limit) {
                        break;
                    }

                    // Freshness check
                    if (!empty($item['created'])) {
                        try {
                            if (Carbon::parse($item['created'])->lt($cutoffDate)) {
                                continue;
                            }
                        } catch (\Exception $e) {
                            // skip unparseable date
                        }
                    }

                    $title       = trim(strip_tags($item['title'] ?? ''));
                    if (empty($title)) {
                        continue;
                    }

                    $company     = trim($item['company']['display_name'] ?? 'Direct Employer');
                    $loc         = trim($item['location']['display_name'] ?? ($location ?: 'India'));
                    $description = trim(strip_tags($item['description'] ?? ''));
                    $sourceUrl   = trim($item['redirect_url'] ?? '');

                    if (empty($description)) {
                        $description = "{$title} vacancy at {$company} in {$loc}.";
                    }

                    // Deduplication
                    $contentHash = JobDuplicateDetector::generateHash($company, $title, $loc);
                    if (JobDuplicateDetector::isDuplicate($contentHash)) {
                        $duplicatesCount++;
                        continue;
                    }

                    // Ingest to Raw Queue
                    $rawJob                  = new RawJob();
                    $rawJob->source_name     = 'Adzuna Keyword Search';
                    $rawJob->source_url      = $sourceUrl;
                    $rawJob->content_hash    = $contentHash;
                    $rawJob->raw_title       = $title;
                    $rawJob->raw_company     = $company;
                    $rawJob->raw_location    = $loc;
                    $rawJob->raw_description = $description;
                    $rawJob->raw_payload     = json_encode([
                        'adzuna_id'      => $item['id'] ?? null,
                        'salary_min'     => $item['salary_min'] ?? null,
                        'salary_max'     => $item['salary_max'] ?? null,
                        'contract_time'  => $item['contract_time'] ?? null,
                        'keyword'        => $keyword,
                        'country'        => $country,
                        'redirect_url'   => $sourceUrl,
                        'created_at_api' => $item['created'] ?? null,
                    ]);
                    $rawJob->status = 'pending';
                    $rawJob->save();
                    $insertedCount++;

                    // Auto-Enrich (if enabled in settings)
                    if ($shouldAutoEnrich) {
                        try {
                            $enrichResult = $enricher->enrichRawJob($rawJob);
                            if (!empty($enrichResult['success'])) {
                                $enrichedCount++;
                            }
                        } catch (\Exception $e) {
                            Log::warning("Keyword search enrichment failed for job #{$rawJob->id}: " . $e->getMessage());
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
            Log::warning("Adzuna keyword search error [{$keyword}]: " . $e->getMessage());
        }

        // Update source tracker
        try {
            $source = JobSource::firstOrCreate(
                ['name' => 'Adzuna Keyword Search'],
                ['source_type' => 'api', 'feed_url' => 'https://api.adzuna.com/v1/api/jobs']
            );
            $source->last_synced_at        = Carbon::now();
            $source->jobs_collected_count  = $source->jobs_collected_count + $insertedCount;
            $source->save();
        } catch (\Exception $e) {
            // ignore tracker failure
        }

        $msg = "Keyword search for \"{$keyword}\": {$insertedCount} new job(s) added to Raw Queue.";
        if ($duplicatesCount > 0) {
            $msg .= " {$duplicatesCount} duplicate(s) skipped.";
        }
        if ($enrichedCount > 0) {
            $msg .= " {$enrichedCount} auto-enriched via AI.";
        }
        if (!empty($errors)) {
            $msg .= " Errors: " . implode('; ', $errors);
        }

        return [
            'success'    => true,
            'inserted'   => $insertedCount,
            'enriched'   => $enrichedCount,
            'duplicates' => $duplicatesCount,
            'errors'     => $errors,
            'message'    => $msg,
        ];
    }

    /**
     * Send GET request via cURL
     */

    protected function sendGetRequest(string $url): array
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'User-Agent: JobPortal-AI-Ingester/1.0',
        ]);

        $responseBody = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            throw new Exception("cURL error: {$curlError}");
        }

        if ($httpCode >= 400) {
            throw new Exception("Adzuna API returned HTTP {$httpCode}: {$responseBody}");
        }

        $decoded = json_decode($responseBody, true);
        return is_array($decoded) ? $decoded : [];
    }
}
