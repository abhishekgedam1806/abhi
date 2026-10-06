<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\RawJob;
use App\JobSource;
use App\JobAIData;
use App\Job;
use App\AIPipelineSetting;
use App\Services\AI\JobDuplicateDetector;
use App\Services\AI\JobEnricher;
use App\Services\AI\JobPublisher;
use App\Services\AI\AdzunaJobFetcher;
use Carbon\Carbon;
use Exception;

class AIJobPipelineController extends Controller
{
    protected $enricher;
    protected $publisher;

    public function __construct(JobEnricher $enricher, JobPublisher $publisher)
    {
        $this->enricher = $enricher;
        $this->publisher = $publisher;
    }

    /**
     * Display the AI Job Pipeline Dashboard
     */
    public function index(Request $request)
    {
        $tab    = $request->input('tab', 'enriched');
        $search = trim($request->input('search', ''));

        // Target: 4–5 published jobs today
        $todayStart          = Carbon::today()->startOfDay();
        $publishedTodayCount = RawJob::where('status', 'published')
            ->where('updated_at', '>=', $todayStart)
            ->count();

        $rawCount       = RawJob::where('status', 'pending')->count();
        $enrichedCount  = RawJob::where('status', 'enriched')->count();
        $totalPublished = RawJob::where('status', 'published')->count();

        // Query based on tab — search filter applied when $search is present
        if ($tab == 'raw') {
            $query = RawJob::where('status', 'pending')->with(['publishedJob.company', 'aiData']);
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('raw_title',    'like', "%{$search}%")
                      ->orWhere('raw_company',  'like', "%{$search}%")
                      ->orWhere('raw_location', 'like', "%{$search}%");
                });
            }
            $jobs    = $query->orderBy('id', 'desc')->paginate(15);
            $sources = collect();


        } elseif ($tab == 'published') {
            $query = RawJob::where('status', 'published')->with(['publishedJob.company', 'aiData']);
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('raw_title',    'like', "%{$search}%")
                      ->orWhere('raw_company',  'like', "%{$search}%")
                      ->orWhere('raw_location', 'like', "%{$search}%")
                      ->orWhereHas('aiData', function ($ai) use ($search) {
                          $ai->where('seo_title',          'like', "%{$search}%")
                             ->orWhere('suggested_category', 'like', "%{$search}%");
                      });
                });
            }
            $jobs    = $query->orderBy('updated_at', 'desc')->paginate(15);
            $sources = collect();

        } elseif ($tab == 'sources') {
            $sources = JobSource::orderBy('id', 'desc')->get();
            $jobs    = collect();

        } else {
            // Default: enriched & ready to publish
            $query = RawJob::where('status', 'enriched')->with(['publishedJob.company', 'aiData']);
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('raw_title',    'like', "%{$search}%")
                      ->orWhere('raw_company',  'like', "%{$search}%")
                      ->orWhere('raw_location', 'like', "%{$search}%")
                      ->orWhereHas('aiData', function ($ai) use ($search) {
                          $ai->where('seo_title',          'like', "%{$search}%")
                             ->orWhere('suggested_category', 'like', "%{$search}%");
                      });
                });
            }
            $jobs    = $query->orderBy('id', 'desc')->paginate(15);
            $tab     = 'enriched';
            $sources = collect();
        }

        $sourcesList      = JobSource::all();
        $pipelineSettings = AIPipelineSetting::getSettings();

        return view('admin.ai.pipeline.index', compact(
            'tab',
            'search',
            'jobs',
            'rawCount',
            'enrichedCount',
            'publishedTodayCount',
            'totalPublished',
            'sourcesList',
            'pipelineSettings'
        ));
    }

    /**
     * Update AI Job Pipeline & Ingestion Settings
     */
    public function updateSettings(Request $request)
    {
        $settings = AIPipelineSetting::getSettings();
        $settings->daily_fetch_limit = max(1, (int)$request->input('daily_fetch_limit', 5));
        $settings->auto_publish = ($request->input('auto_publish') == '1' || $request->input('auto_publish') === 1) ? 1 : 0;
        $settings->auto_enrich = $request->input('auto_enrich', 1) ? 1 : 0;
        $settings->min_quality_score = max(1, min(100, (int)$request->input('min_quality_score', 70)));
        $settings->target_cities = $request->input('target_cities', 'Nagpur, Mumbai, Pune, Delhi, Bangalore');
        $settings->max_job_age_days = max(1, (int)$request->input('max_job_age_days', 7));
        $settings->save();

        flash('✓ Automation & Daily Job Fetch settings updated successfully.')->success();
        return back();
    }

    /**
     * Fetch real fresh jobs via Adzuna API
     */
    public function fetchAdzunaJobs(Request $request)
    {
        $cities = array_filter(array_map('trim', explode(',', $request->input('cities', ''))));
        $days = $request->has('days') ? (int)$request->input('days') : null;
        $limit = $request->has('limit') ? (int)$request->input('limit') : null;
        $autoPublish = $request->has('auto_publish') ? (bool)$request->input('auto_publish') : null;

        $fetcher = app(AdzunaJobFetcher::class);
        $result = $fetcher->fetchAndIngest($cities, $days, $limit, $autoPublish);

        if ($result['success']) {
            flash($result['message'])->success();
        } else {
            flash($result['message'])->error();
        }

        $settings  = AIPipelineSetting::getSettings();
        $targetTab = (!empty($result['published']) && $result['published'] > 0) ? 'published' : ($settings->auto_publish ? 'published' : 'raw');
        return redirect()->route('admin.ai.pipeline', ['tab' => $targetTab]);
    }

    /**
     * Smart Keyword Job Search — fetches jobs by keyword from Adzuna
     * for manual targeted ingestion (SEO, Software Engineer, Digital Marketing etc.)
     */
    public function keywordSearch(Request $request)
    {
        $request->validate([
            'keyword'  => 'required|string|max:100',
            'country'  => 'required|string|max:5',
            'location' => 'nullable|string|max:100',
            'limit'    => 'nullable|integer|min:1|max:50',
            'max_days' => 'nullable|integer|min:1|max:90',
            'job_type' => 'nullable|string|max:50',
        ]);

        $keyword  = trim($request->input('keyword'));
        $country  = trim($request->input('country', 'in'));
        $location = trim($request->input('location', ''));
        $limit    = (int) $request->input('limit', 10);
        $maxDays  = (int) $request->input('max_days', 30);
        $jobType  = trim($request->input('job_type', ''));

        $fetcher = app(AdzunaJobFetcher::class);
        $result  = $fetcher->fetchByKeyword($keyword, $country, $location, $limit, $maxDays, $jobType);

        if ($result['success']) {
            if ($result['inserted'] > 0) {
                flash('✓ Keyword Search Complete! "' . $keyword . '" → ' . $result['message'])->success();
            } else {
                flash('No new jobs found for "' . $keyword . '". All results were duplicates or the API returned no data.')->warning();
            }
        } else {
            flash($result['message'])->error();
        }

        return redirect()->route('admin.ai.pipeline', ['tab' => 'raw']);
    }

    /**
     * Preview keyword jobs from Adzuna — returns JSON, NO DB writes.
     * Admin sees results first, selects which to add.
     */
    public function previewKeywordJobs(Request $request)
    {
        $request->validate([
            'keyword'  => 'required|string|max:100',
            'country'  => 'required|string|max:5',
            'location' => 'nullable|string|max:100',
            'limit'    => 'nullable|integer|min:1|max:50',
            'max_days' => 'nullable|integer|min:1|max:90',
            'job_type' => 'nullable|string|max:50',
        ]);

        $fetcher = app(AdzunaJobFetcher::class);
        $result  = $fetcher->previewByKeyword(
            trim($request->input('keyword')),
            trim($request->input('country', 'in')),
            trim($request->input('location', '')),
            (int) $request->input('limit', 10),
            (int) $request->input('max_days', 30),
            trim($request->input('job_type', ''))
        );

        return response()->json($result);
    }

    /**
     * Add admin-selected jobs (from preview) to Raw Ingestion Queue.
     * Each job goes through dedup check — already existing ones are skipped.
     */
    public function addSelectedToQueue(Request $request)
    {
        $jobs = $request->input('jobs', []);

        if (empty($jobs) || !is_array($jobs)) {
            return response()->json(['success' => false, 'message' => 'No jobs received.']);
        }

        $added      = 0;
        $duplicates = 0;

        foreach ($jobs as $jobData) {
            $title   = trim($jobData['title']   ?? '');
            $company = trim($jobData['company']  ?? 'Direct Employer');
            $loc     = trim($jobData['location'] ?? 'India');

            if (empty($title)) {
                continue;
            }

            $contentHash = \App\Services\AI\JobDuplicateDetector::generateHash($company, $title, $loc);
            if (\App\Services\AI\JobDuplicateDetector::isDuplicate($contentHash)) {
                $duplicates++;
                continue;
            }

            $rawJob                  = new \App\RawJob();
            $rawJob->source_name     = 'Adzuna Keyword Search';
            $rawJob->source_url      = $jobData['source_url']    ?? '';
            $rawJob->content_hash    = $contentHash;
            $rawJob->raw_title       = $title;
            $rawJob->raw_company     = $company;
            $rawJob->raw_location    = $loc;
            $rawJob->raw_description = $jobData['description']   ?? "{$title} at {$company}.";
            $rawJob->raw_payload     = json_encode([
                'adzuna_id'      => $jobData['adzuna_id']     ?? null,
                'salary_min'     => $jobData['salary_min']    ?? null,
                'salary_max'     => $jobData['salary_max']    ?? null,
                'contract_time'  => $jobData['contract_time'] ?? null,
                'keyword'        => $jobData['keyword']       ?? '',
                'country'        => $jobData['country']       ?? 'in',
                'redirect_url'   => $jobData['source_url']    ?? '',
                'created_at_api' => $jobData['created']       ?? null,
            ]);
            $rawJob->status = 'pending';
            $rawJob->save();
            $added++;
        }

        $msg = "{$added} job(s) added to Raw Ingestion Queue.";
        if ($duplicates > 0) {
            $msg .= " {$duplicates} duplicate(s) already existed — skipped.";
        }

        return response()->json([
            'success'    => true,
            'added'      => $added,
            'duplicates' => $duplicates,
            'message'    => $msg,
        ]);
    }

    /**
     * Ingest a sample/custom raw job into pipeline with duplicate detection
     */

    public function ingestRawJob(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'company' => 'nullable|string|max:150',
            'location' => 'nullable|string|max:100',
            'description' => 'required|string',
            'source_name' => 'nullable|string|max:100',
        ]);

        $company = $request->input('company', 'Employer Submission');
        $title = $request->input('title');
        $location = $request->input('location', 'Nagpur, India');

        // Deterministic SHA-256 duplicate fingerprint
        $contentHash = JobDuplicateDetector::generateHash($company, $title, $location);

        if (JobDuplicateDetector::isDuplicate($contentHash)) {
            flash('Duplicate Detected! A job with identical company, title, and location already exists in the system. Discarded at ₹0 cost.')->warning();
            return redirect()->route('admin.ai.pipeline', ['tab' => 'raw']);
        }

        $rawJob = new RawJob();
        $rawJob->source_name = $request->input('source_name', 'Manual Feed / Partner Import');
        $rawJob->content_hash = $contentHash;
        $rawJob->raw_title = $title;
        $rawJob->raw_company = $company;
        $rawJob->raw_location = $location;
        $rawJob->raw_description = $request->input('description');
        $rawJob->status = 'pending';
        $rawJob->save();

        flash('Job "' . $rawJob->raw_title . '" added to the raw queue. Content Hash generated: ' . substr($contentHash, 0, 12) . '...')->success();
        return redirect()->route('admin.ai.pipeline', ['tab' => 'raw']);
    }

    /**
     * Trigger AI enrichment on a single raw job
     */
    public function enrichSingle($id)
    {
        $rawJob = RawJob::findOrFail($id);

        try {
            $result = $this->enricher->enrichRawJob($rawJob);

            if ($result['success']) {
                $score = $result['ai_data']->quality_score ?? 80;
                flash('✓ AI Enrichment Complete for "' . $rawJob->raw_title . '"! Quality Score: ' . $score . '/100 | Cost: ₹' . number_format($result['cost_inr'] ?? 0, 4) . ' | Latency: ' . ($result['latency_ms'] ?? 0) . 'ms')->success();
                return redirect()->route('admin.ai.pipeline', ['tab' => 'enriched']);
            } else {
                flash('AI Enrichment failed: ' . ($result['error'] ?? 'Unknown error'))->error();
                return redirect()->route('admin.ai.pipeline', ['tab' => 'raw']);
            }
        } catch (Exception $e) {
            flash('Error enriching job: ' . $e->getMessage())->error();
            return redirect()->route('admin.ai.pipeline', ['tab' => 'raw']);
        }
    }

    /**
     * Update an existing raw job
     */
    public function updateRawJob(Request $request, $id)
    {
        $rawJob = RawJob::findOrFail($id);

        $request->validate([
            'title'           => 'required|string|max:200',
            'company'         => 'nullable|string|max:150',
            'location'        => 'nullable|string|max:100',
            'source_url'      => 'nullable|string|max:1500',
            'company_website' => 'nullable|string|max:255',
            'company_email'   => 'nullable|string|max:150',
            'description'     => 'required|string',
        ]);

        $companyName = trim($request->input('company', $rawJob->raw_company ?: 'Direct Employer'));
        $title       = trim($request->input('title'));
        $location    = trim($request->input('location', $rawJob->raw_location ?: 'Nagpur, India'));
        $sourceUrl   = trim($request->input('source_url', ''));
        $website     = trim($request->input('company_website', ''));
        $email       = trim($request->input('company_email', ''));

        // Format website URL if missing protocol
        if (!empty($website) && !preg_match("~^(?:f|ht)tps?://~i", $website)) {
            $website = "https://" . $website;
        }

        // Regenerate Hash
        $contentHash = JobDuplicateDetector::generateHash($companyName, $title, $location);

        $rawJob->raw_title       = $title;
        $rawJob->raw_company     = $companyName;
        $rawJob->raw_location    = $location;
        $rawJob->source_url      = $sourceUrl;
        $rawJob->raw_description = $request->input('description');
        $rawJob->content_hash    = $contentHash;

        // Update raw_payload with website & email
        $payload = !empty($rawJob->raw_payload) ? json_decode($rawJob->raw_payload, true) : [];
        if (!is_array($payload)) {
            $payload = [];
        }
        if (!empty($website)) {
            $payload['website'] = $website;
        }
        if (!empty($email)) {
            $payload['email'] = $email;
        }
        $rawJob->raw_payload = json_encode($payload);
        $rawJob->save();

        // If job is already published to live portal, synchronize live Job & Company
        if ($rawJob->job_id) {
            $job = Job::find($rawJob->job_id);
            if ($job) {
                $job->title       = $title;
                $job->description = $rawJob->raw_description;
                $job->search      = $title . ' ' . $location . ' ' . $companyName;
                $job->save();

                $company = $job->company;
                if ($company) {
                    $company->name = $companyName;
                    if (!empty($website)) {
                        $company->website = $website;
                    }
                    if (!empty($email)) {
                        $company->email = $email;
                    }
                    $company->save();
                }
            }
        } else {
            // Also check if company exists in DB with this name, update website/email if provided
            $company = Company::where('name', $companyName)->first();
            if ($company) {
                if (!empty($website)) {
                    $company->website = $website;
                }
                if (!empty($email)) {
                    $company->email = $email;
                }
                $company->save();
            }
        }

        $currentTab = $rawJob->status === 'published' ? 'published' : ($rawJob->status === 'enriched' ? 'enriched' : 'raw');

        flash('✓ Job "' . $rawJob->raw_title . '" updated successfully.')->success();
        return redirect()->route('admin.ai.pipeline', ['tab' => $currentTab]);
    }

    /**
     * Delete a single raw or published job from pipeline
     */
    public function deleteRawJob($id)
    {
        $rawJob = RawJob::findOrFail($id);
        $title = $rawJob->raw_title;

        // Clean up linked published job if present
        if ($rawJob->job_id) {
            $job = Job::find($rawJob->job_id);
            if ($job) {
                \App\JobSkillManager::where('job_id', $job->id)->delete();
                $job->delete();
            }
        }

        // Delete associated AI data if present
        JobAIData::where('raw_job_id', $id)->delete();
        $rawJob->delete();

        flash('✓ Job "' . $title . '" deleted successfully.')->success();
        return back();
    }

    /**
     * Bulk Delete Selected Jobs across tabs
     */
    public function bulkDeleteRawJobs(Request $request)
    {
        $ids = $request->input('selected_ids', []);

        if (empty($ids) || !is_array($ids)) {
            flash('No jobs were selected for deletion.')->warning();
            return back();
        }

        $count = count($ids);
        $rawJobs = RawJob::whereIn('id', $ids)->get();
        $jobIds = $rawJobs->pluck('job_id')->filter()->toArray();

        if (!empty($jobIds)) {
            \App\JobSkillManager::whereIn('job_id', $jobIds)->delete();
            Job::whereIn('id', $jobIds)->delete();
        }

        JobAIData::whereIn('raw_job_id', $ids)->delete();
        RawJob::whereIn('id', $ids)->delete();

        flash("✓ Successfully deleted {$count} selected jobs.")->success();
        return back();
    }

    /**
     * Publish an enriched job to the live portal
     */
    public function publishSingle($id)
    {
        $rawJob = RawJob::findOrFail($id);

        try {
            $job = $this->publisher->publish($rawJob);
            flash('🚀 Job "' . $job->title . '" has been published live to the portal! Google Schema.org JSON-LD is active.')->success();
            return redirect()->route('admin.ai.pipeline', ['tab' => 'published']);
        } catch (Exception $e) {
            flash('Failed to publish job: ' . $e->getMessage())->error();
            return redirect()->route('admin.ai.pipeline', ['tab' => 'enriched']);
        }
    }

    /**
     * Bulk Publish Selected Jobs to the Live Portal
     */
    public function bulkPublishJobs(Request $request)
    {
        $ids = $request->input('selected_ids', []);

        if (empty($ids) || !is_array($ids)) {
            flash('No jobs were selected for publishing.')->warning();
            return back();
        }

        $published = 0;
        $failed = 0;

        foreach ($ids as $id) {
            $rawJob = RawJob::find($id);
            if (!$rawJob) continue;

            try {
                // If not yet enriched, auto-enrich first with Gemini
                if ($rawJob->status === 'pending' || !$rawJob->aiData) {
                    $this->enricher->enrichRawJob($rawJob);
                    $rawJob->refresh();
                }

                $this->publisher->publish($rawJob);
                $published++;
            } catch (Exception $e) {
                $failed++;
            }
        }

        if ($published > 0) {
            $msg = "🚀 Successfully published {$published} selected job(s) live to the portal!";
            if ($failed > 0) {
                $msg .= " ({$failed} job(s) encountered errors and were skipped).";
            }
            flash($msg)->success();
        } else {
            flash("Failed to publish selected jobs. Please check API settings or job data.")->error();
        }

        return redirect()->route('admin.ai.pipeline', ['tab' => 'published']);
    }

    /**
     * Bulk Enrich Selected Raw Jobs with Gemini AI
     */
    public function bulkEnrichJobs(Request $request)
    {
        $ids = $request->input('selected_ids', []);

        if (empty($ids) || !is_array($ids)) {
            flash('No jobs were selected for AI enrichment.')->warning();
            return back();
        }

        $enriched = 0;
        $failed = 0;

        foreach ($ids as $id) {
            $rawJob = RawJob::find($id);
            if (!$rawJob || $rawJob->status === 'published') continue;

            try {
                $res = $this->enricher->enrichRawJob($rawJob);
                if (!empty($res['success'])) {
                    $enriched++;
                } else {
                    $failed++;
                }
            } catch (Exception $e) {
                $failed++;
            }
        }

        if ($enriched > 0) {
            flash("✓ Successfully enriched {$enriched} selected job(s) with Gemini AI!")->success();
        } else {
            flash("AI enrichment could not be completed for selected jobs.")->error();
        }

        return redirect()->route('admin.ai.pipeline', ['tab' => 'enriched']);
    }

    /**
     * Seed 4–5 sample quality raw jobs for demonstration
     */
    public function seedSampleJobs()
    {
        $samples = [
            [
                'title' => 'Senior Laravel & PHP Developer',
                'company' => 'TechSprint Solutions',
                'location' => 'Nagpur, Maharashtra',
                'description' => 'Looking for Senior Laravel Backend Developer with 3+ years experience. Strong in PHP, MySQL, RESTful APIs, Redis caching, and microservices architecture. Responsible for leading backend architectural design and database optimization.',
            ],
            [
                'title' => 'Digital Marketing & SEO Executive',
                'company' => 'GrowthPeak Agency',
                'location' => 'Pune, Maharashtra',
                'description' => 'We are hiring a result-oriented SEO Executive to manage technical on-page, off-page backlinks, Google Search Console, Google Analytics 4, and keyword research. Min 1-2 years experience required.',
            ],
            [
                'title' => 'Flutter Mobile App Engineer',
                'company' => 'AppVision Infotech',
                'location' => 'Nagpur, Maharashtra',
                'description' => 'We need a passionate Flutter Developer to build high performance cross-platform iOS & Android mobile applications. Experience with Dart, State Management (Bloc / Provider), REST APIs, and Firebase integration.',
            ],
            [
                'title' => 'HR Talent Acquisition Specialist',
                'company' => 'Nexus Global Corp',
                'location' => 'Mumbai, Maharashtra',
                'description' => 'Responsible for end-to-end recruitment lifecycle, candidate screening, scheduling interviews, salary negotiations, and onboarding documentation for IT and Non-IT hiring.',
            ],
        ];

        $added = 0;
        foreach ($samples as $sample) {
            $hash = JobDuplicateDetector::generateHash($sample['company'], $sample['title'], $sample['location']);
            if (!JobDuplicateDetector::isDuplicate($hash)) {
                $raw = new RawJob();
                $raw->source_name = 'Partner Feed Ingestion';
                $raw->content_hash = $hash;
                $raw->raw_title = $sample['title'];
                $raw->raw_company = $sample['company'];
                $raw->raw_location = $sample['location'];
                $raw->raw_description = $sample['description'];
                $raw->status = 'pending';
                $raw->save();
                $added++;
            }
        }

        flash('Ingested ' . $added . ' quality raw jobs into pipeline. Duplicates were automatically skipped.')->success();
        return redirect()->route('admin.ai.pipeline', ['tab' => 'raw']);
    }
}
