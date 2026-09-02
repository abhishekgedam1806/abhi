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
        $tab = $request->input('tab', 'enriched');

        // Target: 4–5 published jobs today
        $todayStart = Carbon::today()->startOfDay();
        $publishedTodayCount = RawJob::where('status', 'published')
            ->where('updated_at', '>=', $todayStart)
            ->count();

        $rawCount = RawJob::where('status', 'pending')->count();
        $enrichedCount = RawJob::where('status', 'enriched')->count();
        $totalPublished = RawJob::where('status', 'published')->count();

        // Query based on tab
        if ($tab == 'raw') {
            $jobs = RawJob::where('status', 'pending')->orderBy('id', 'desc')->paginate(15);
        } elseif ($tab == 'published') {
            $jobs = RawJob::where('status', 'published')->with('publishedJob', 'aiData')->orderBy('updated_at', 'desc')->paginate(15);
        } elseif ($tab == 'sources') {
            $sources = JobSource::orderBy('id', 'desc')->get();
            $jobs = collect();
        } else {
            // Default: enriched & ready to publish
            $jobs = RawJob::where('status', 'enriched')->with('aiData')->orderBy('id', 'desc')->paginate(15);
            $tab = 'enriched';
        }

        $sourcesList = JobSource::all();
        $pipelineSettings = AIPipelineSetting::getSettings();

        return view('admin.ai.pipeline.index', compact(
            'tab',
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

        $settings = AIPipelineSetting::getSettings();
        $targetTab = (!empty($result['published']) && $result['published'] > 0) ? 'published' : ($settings->auto_publish ? 'published' : 'raw');
        return redirect()->route('admin.ai.pipeline', ['tab' => $targetTab]);
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
            'title' => 'required|string|max:200',
            'company' => 'nullable|string|max:150',
            'location' => 'nullable|string|max:100',
            'description' => 'required|string',
        ]);

        $company = $request->input('company', $rawJob->raw_company ?: 'Direct Employer');
        $title = $request->input('title');
        $location = $request->input('location', $rawJob->raw_location ?: 'Nagpur, India');

        // Regenerate Hash
        $contentHash = JobDuplicateDetector::generateHash($company, $title, $location);

        $rawJob->raw_title = $title;
        $rawJob->raw_company = $company;
        $rawJob->raw_location = $location;
        $rawJob->raw_description = $request->input('description');
        $rawJob->content_hash = $contentHash;
        $rawJob->save();

        flash('✓ Raw Job "' . $rawJob->raw_title . '" updated successfully.')->success();
        return redirect()->route('admin.ai.pipeline', ['tab' => 'raw']);
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
