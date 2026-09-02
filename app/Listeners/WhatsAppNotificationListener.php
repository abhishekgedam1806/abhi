<?php

namespace App\Listeners;

use App\Events\JobApplied;
use App\Events\JobPosted;
use App\Events\UserRegistered;
use App\Events\CompanyRegistered;
use App\Services\WhatsApp\WhatsAppNotificationService;
use App\User;
use App\Company;
use Illuminate\Support\Facades\Log;
use Exception;

class WhatsAppNotificationListener
{
    protected $whatsAppService;

    public function __construct(WhatsAppNotificationService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    /**
     * Handle JobApplied Event
     */
    public function handleJobApplied(JobApplied $event)
    {
        try {
            $job = $event->job;
            $jobApply = $event->jobApply;
            $candidate = User::find($jobApply->user_id);
            $company = $job ? $job->getCompany() : null;

            if (!$job || !$candidate) {
                return;
            }

            $companyName = $company ? $company->name : 'Direct Employer';
            $jobTitle = $job->title;

            // 1. Candidate Application Confirmation
            $candidatePortalUrl = route('my.job.applications');
            $this->whatsAppService->send(
                'application_confirmation',
                'user',
                $candidate->id,
                [
                    'name' => $candidate->getName(),
                    'job_title' => $jobTitle,
                    'company' => $companyName,
                    'action_url' => $candidatePortalUrl,
                ],
                null,
                "app_confirm_{$jobApply->id}_{$candidate->id}"
            );

            // 2. Employer New Applicant Alert
            if ($company) {
                $employerPortalUrl = route('list.applied.users', $job->id);
                $this->whatsAppService->send(
                    'job_applied',
                    'company',
                    $company->id,
                    [
                        'company_name' => $company->name,
                        'candidate_name' => $candidate->getName(),
                        'job_title' => $jobTitle,
                        'action_url' => $employerPortalUrl,
                    ],
                    null,
                    "app_alert_{$jobApply->id}_{$company->id}"
                );
            }
        } catch (Exception $e) {
            Log::warning("WhatsApp JobApplied listener error: " . $e->getMessage());
        }
    }

    /**
     * Handle JobPosted Event
     */
    public function handleJobPosted(JobPosted $event)
    {
        try {
            $job = $event->job;
            if (!$job) return;

            $company = $job->getCompany();
            if (!$company) return;

            $statusText = $job->is_active ? 'Live & Published' : 'Saved as Draft';
            $portalUrl = route('posted.jobs');

            $this->whatsAppService->send(
                'job_published',
                'company',
                $company->id,
                [
                    'company_name' => $company->name,
                    'job_title' => $job->title,
                    'status' => $statusText,
                    'action_url' => $portalUrl,
                ],
                null,
                "job_posted_{$job->id}"
            );
        } catch (Exception $e) {
            Log::warning("WhatsApp JobPosted listener error: " . $e->getMessage());
        }
    }

    /**
     * Handle User Registered Event
     */
    public function handleUserRegistered(UserRegistered $event)
    {
        try {
            $user = $event->user;
            if (!$user) return;

            $this->whatsAppService->send(
                'otp_verification',
                'user',
                $user->id,
                [
                    'name' => $user->getName(),
                    'code' => $user->verification_token ?: 'VERIFIED',
                    'action_url' => route('home'),
                ],
                null,
                "user_reg_{$user->id}"
            );
        } catch (Exception $e) {
            Log::warning("WhatsApp UserRegistered listener error: " . $e->getMessage());
        }
    }

    /**
     * Handle Company Registered Event
     */
    public function handleCompanyRegistered(CompanyRegistered $event)
    {
        try {
            $company = $event->company;
            if (!$company) return;

            $this->whatsAppService->send(
                'job_published',
                'company',
                $company->id,
                [
                    'company_name' => $company->name,
                    'job_title' => 'Employer Account Verification',
                    'status' => 'Pending Verification',
                    'action_url' => route('company.home'),
                ],
                null,
                "comp_reg_{$company->id}"
            );
        } catch (Exception $e) {
            Log::warning("WhatsApp CompanyRegistered listener error: " . $e->getMessage());
        }
    }
}
