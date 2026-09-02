<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AIUsageLog;
use App\AIProvider;
use Carbon\Carbon;
use DB;

class AICostPerformanceController extends Controller
{
    /**
     * Display the AI Cost & Performance Dashboard
     */
    public function index(Request $request)
    {
        $days = (int) $request->input('days', 30);
        if (!in_array($days, [7, 30, 90])) {
            $days = 30;
        }

        $startDate = Carbon::now()->subDays($days)->startOfDay();

        // 1. Executive Cost Cards
        $todayStart = Carbon::today()->startOfDay();
        $yesterdayStart = Carbon::yesterday()->startOfDay();
        $yesterdayEnd = Carbon::yesterday()->endOfDay();
        $monthStart = Carbon::now()->startOfMonth();

        $costToday = (float) AIUsageLog::where('created_at', '>=', $todayStart)->sum('estimated_cost_inr');
        $costYesterday = (float) AIUsageLog::whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])->sum('estimated_cost_inr');
        $costThisMonth = (float) AIUsageLog::where('created_at', '>=', $monthStart)->sum('estimated_cost_inr');
        
        $presaleFeatures = ['candidate_profile_analysis', 'employer_job_optimization', 'candidate_job_recommendations'];
        $costPresale30d = (float) AIUsageLog::where('created_at', '>=', Carbon::now()->subDays(30))
            ->whereIn('feature', $presaleFeatures)
            ->sum('estimated_cost_inr');

        $totalCallsInPeriod = AIUsageLog::where('created_at', '>=', $startDate)->count();

        // 2. Unit Economics (30 Days window)
        $date30d = Carbon::now()->subDays(30);

        // Per Onboarding (Candidate profile/resume analysis)
        $onboardingStats = AIUsageLog::where('created_at', '>=', $date30d)
            ->whereIn('feature', ['candidate_profile_analysis', 'candidate_resume_analysis'])
            ->selectRaw('COUNT(*) as total_calls, SUM(estimated_cost_inr) as total_cost')
            ->first();
        $costPerOnboarding = ($onboardingStats && $onboardingStats->total_calls > 0) 
            ? ($onboardingStats->total_cost / $onboardingStats->total_calls) 
            : 0.00;

        // Per Replacement Review (Candidate matching / ranking)
        $replacementStats = AIUsageLog::where('created_at', '>=', $date30d)
            ->whereIn('feature', ['employer_candidate_matching', 'employer_candidate_ranking'])
            ->selectRaw('COUNT(*) as total_calls, SUM(estimated_cost_inr) as total_cost')
            ->first();
        $costPerReplacement = ($replacementStats && $replacementStats->total_calls > 0) 
            ? ($replacementStats->total_cost / $replacementStats->total_calls) 
            : 0.039;

        // Per Smart AI Generation (Job optimization & description improvement)
        $generationStats = AIUsageLog::where('created_at', '>=', $date30d)
            ->whereIn('feature', ['employer_job_optimization', 'employer_job_description_improvement', 'automated_job_summarization'])
            ->selectRaw('COUNT(*) as total_calls, SUM(estimated_cost_inr) as total_cost')
            ->first();
        $costPerGeneration = ($generationStats && $generationStats->total_calls > 0) 
            ? ($generationStats->total_cost / $generationStats->total_calls) 
            : 0.069;

        // 3. Performance Metrics for Selected Period
        $periodQuery = AIUsageLog::where('created_at', '>=', $startDate);
        $totalCalls = (int) $periodQuery->count();
        $successfulCalls = (int) (clone $periodQuery)->where('is_success', 1)->count();
        $failedCalls = (int) (clone $periodQuery)->where('is_success', 0)->count();
        $successRate = $totalCalls > 0 ? round(($successfulCalls / $totalCalls) * 100, 1) : 100.0;
        $avgResponseTimeMs = (int) round((clone $periodQuery)->avg('response_time_ms') ?: 0);
        $totalCostInPeriod = (float) (clone $periodQuery)->sum('estimated_cost_inr');

        // 4. Provider Performance Breakdown
        $providerPerformance = AIUsageLog::where('created_at', '>=', $startDate)
            ->select(
                'provider_type',
                'model',
                DB::raw('COUNT(*) as total_calls'),
                DB::raw('SUM(CASE WHEN is_success = 1 THEN 1 ELSE 0 END) as success_calls'),
                DB::raw('AVG(response_time_ms) as avg_response_ms'),
                DB::raw('SUM(estimated_cost_inr) as total_cost_inr')
            )
            ->groupBy('provider_type', 'model')
            ->orderBy('total_calls', 'desc')
            ->get()
            ->map(function ($item) {
                $item->success_rate = $item->total_calls > 0 ? round(($item->success_calls / $item->total_calls) * 100, 1) : 0;
                $item->avg_response_sec = round($item->avg_response_ms / 1000, 2);
                return $item;
            });

        // 5. Feature Groups Breakdown
        $featuresList = AIUsageLog::getFeaturesList();
        $featureStats = AIUsageLog::where('created_at', '>=', $startDate)
            ->select(
                'feature',
                'feature_group',
                DB::raw('COUNT(*) as total_calls'),
                DB::raw('SUM(estimated_cost_inr) as total_cost_inr'),
                DB::raw('AVG(response_time_ms) as avg_response_ms')
            )
            ->groupBy('feature', 'feature_group')
            ->get()
            ->keyBy('feature');

        // 6. Paginated Request Logs
        $recentLogs = AIUsageLog::orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.ai.cost_performance.index', compact(
            'days',
            'costToday',
            'costYesterday',
            'costThisMonth',
            'costPresale30d',
            'totalCallsInPeriod',
            'costPerOnboarding',
            'costPerReplacement',
            'costPerGeneration',
            'totalCalls',
            'successfulCalls',
            'failedCalls',
            'successRate',
            'avgResponseTimeMs',
            'totalCostInPeriod',
            'providerPerformance',
            'featuresList',
            'featureStats',
            'recentLogs'
        ));
    }
}
