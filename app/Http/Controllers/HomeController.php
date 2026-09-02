<?php

namespace App\Http\Controllers;

use App\Traits\Cron;
use App\Job;
use App\FavouriteCompany;

use Illuminate\Http\Request;

class HomeController extends Controller
{

    use Cron;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->runCheckPackageValidity();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        // Redirect incomplete candidate profile to onboarding
        if ($user->isJobSeeker() && !(bool)$user->onboarding_completed && !request()->has('skip_onboarding')) {
            return redirect()->route('onboarding');
        }

        // Recommended matching jobs powered by Smart Engine
        $recResult = \App\Services\JobRecommendationEngine::searchAndRankJobs('', [], $user, null, 4);
        $matchingJobs = $recResult['ranked_jobs'];
        if (count($matchingJobs) > 4) {
            $matchingJobs = array_slice($matchingJobs, 0, 4);
        }

        // Metrics
        $appliedCount = \App\JobApply::where('user_id', $user->id)->count();
        $shortlistedCount = \App\JobApply::where('user_id', $user->id)
            ->where(function($q) use ($user) {
                $q->where('status', 'shortlisted')
                  ->orWhereIn('job_id', function($sub) use ($user) {
                      $sub->select('job_id')->from('favourite_applicants')->where('user_id', $user->id);
                  });
            })->count();
        $interviewInvitesCount = \App\JobApply::where('user_id', $user->id)
            ->whereIn('status', ['interview_scheduled', 'interview_completed', 'selected'])
            ->count();
        $messagesCount = $user->countUserMessages();
        $profileViews = $user->num_profile_views ?: 0;
        $followingsCount = $user->countFollowings();

        // Profile sections data
        $experiences = $user->profileExperience()->orderBy('date_start', 'desc')->get();
        $educations = $user->profileEducation()->orderBy('date_completion', 'desc')->get();
        $skills = $user->profileSkills()->with('jobSkill')->get();
        $defaultCv = $user->getDefaultCv();
        $summary = $user->getProfileSummary('summary');

        // Dynamic Profile Strength Calculation
        $strengthScore = 0;
        $strengthChecks = [
            'basic_info' => false,
            'experience' => false,
            'education' => false,
            'skills' => false,
            'resume' => false,
            'preferences' => false,
            'mobile' => false,
        ];

        // 1. Basic Info (Name, Email, Location) -> 20%
        if (!empty($user->first_name) && !empty($user->email) && !empty($user->city_id)) {
            $strengthScore += 20;
            $strengthChecks['basic_info'] = true;
        } elseif (!empty($user->first_name) && !empty($user->email)) {
            $strengthScore += 10;
        }

        // 2. Experience -> 20%
        if ($experiences->count() > 0) {
            $strengthScore += 20;
            $strengthChecks['experience'] = true;
        }

        // 3. Education -> 15%
        if ($educations->count() > 0) {
            $strengthScore += 15;
            $strengthChecks['education'] = true;
        }

        // 4. Skills -> 15%
        if ($skills->count() > 0) {
            $strengthScore += 15;
            $strengthChecks['skills'] = true;
        }

        // 5. Resume -> 15%
        if ($defaultCv) {
            $strengthScore += 15;
            $strengthChecks['resume'] = true;
        }

        // 6. Mobile present -> 15%
        if (!empty($user->phone)) {
            $strengthScore += 15;
            $strengthChecks['mobile'] = true;
        }

        $strengthScore = min(100, $strengthScore);

        // Designation / Headline - Strictly from user data (No fake fallbacks)
        $designation = null;
        if ($experiences->first() && !empty($experiences->first()->title)) {
            $designation = $experiences->first()->title;
        } elseif (!empty($user->getFunctionalArea('functional_area'))) {
            $designation = $user->getFunctionalArea('functional_area');
        } elseif (!empty($summary)) {
            $words = explode(' ', trim($summary));
            if (count($words) <= 4) {
                $designation = $summary;
            }
        }

        return view('home', compact(
            'user',
            'matchingJobs',
            'appliedCount',
            'shortlistedCount',
            'interviewInvitesCount',
            'messagesCount',
            'profileViews',
            'followingsCount',
            'experiences',
            'educations',
            'skills',
            'defaultCv',
            'summary',
            'designation',
            'strengthScore',
            'strengthChecks'
        ));
    }

}
