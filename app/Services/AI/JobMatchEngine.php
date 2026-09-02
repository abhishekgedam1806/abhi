<?php

namespace App\Services\AI;

use App\User;
use App\Job;

class JobMatchEngine
{
    /**
     * Calculate hybrid deterministic match score between a candidate and a job
     *
     * @param User $candidate
     * @param Job $job
     * @return array ['score' => int, 'is_hot_lead' => bool, 'reasons' => array, 'missing' => array]
     */
    public static function calculateMatch(User $candidate, Job $job): array
    {
        $score = 0;
        $reasons = [];
        $missing = [];

        // 1. Skill Matching (Weight: 35%)
        $candidateSkills = [];
        if ($candidate->profileSkills) {
            foreach ($candidate->profileSkills as $ps) {
                $skName = $ps->getJobSkill('job_skill');
                if (!empty($skName)) {
                    $candidateSkills[] = mb_strtolower(trim($skName));
                }
            }
        }

        $jobSkills = [];
        if ($job->jobSkills) {
            foreach ($job->jobSkills as $jsm) {
                $jsName = $jsm->getJobSkill('job_skill');
                if (!empty($jsName)) {
                    $jobSkills[] = mb_strtolower(trim($jsName));
                }
            }
        }

        if (count($jobSkills) > 0 && count($candidateSkills) > 0) {
            $matchingSkills = array_intersect($candidateSkills, $jobSkills);
            $skillRatio = count($matchingSkills) / count($jobSkills);
            $skillPoints = (int) round($skillRatio * 35);
            $score += $skillPoints;

            if (count($matchingSkills) > 0) {
                $reasons[] = '✓ Key skills match: ' . implode(', ', array_map('ucwords', array_slice($matchingSkills, 0, 3)));
            }

            $missingSkills = array_diff($jobSkills, $candidateSkills);
            if (count($missingSkills) > 0) {
                $missing[] = 'Missing skills: ' . implode(', ', array_map('ucwords', array_slice($missingSkills, 0, 2)));
            }
        } else {
            // Baseline skills points if job has no explicit skills
            $score += 20;
        }

        // 2. Experience Level (Weight: 20%)
        if ($candidate->job_experience_id && $job->job_experience_id) {
            if ($candidate->job_experience_id >= $job->job_experience_id) {
                $score += 20;
                $reasons[] = '✓ Experience requirement matches your background';
            } else {
                $score += 10;
            }
        } else {
            $score += 15;
        }

        // 3. Location Matching (Weight: 15%)
        if ($candidate->city_id && $job->city_id) {
            if ($candidate->city_id == $job->city_id) {
                $score += 15;
                $cityName = $job->getCity('city') ?: 'City';
                $reasons[] = "✓ Job is in your location ({$cityName})";
            } elseif ($candidate->state_id == $job->state_id) {
                $score += 10;
                $reasons[] = "✓ Job is in your state";
            } else {
                $score += 5;
            }
        } else {
            $score += 10;
        }

        // 4. Functional Area / Role Matching (Weight: 15%)
        if ($candidate->functional_area_id && $job->functional_area_id) {
            if ($candidate->functional_area_id == $job->functional_area_id) {
                $score += 15;
                $reasons[] = '✓ Matches your preferred functional area';
            } else {
                $score += 5;
            }
        } else {
            $score += 10;
        }

        // 5. Job Type (Weight: 15%)
        if ($candidate->job_type_id && $job->job_type_id) {
            if ($candidate->job_type_id == $job->job_type_id) {
                $score += 15;
                $reasons[] = '✓ Employment type matches your preference';
            } else {
                $score += 8;
            }
        } else {
            $score += 12;
        }

        // Cap score between 30 and 99
        $finalScore = min(99, max(35, $score));
        $isHotLead = ($finalScore >= 80);

        return [
            'score' => $finalScore,
            'is_hot_lead' => $isHotLead,
            'reasons' => $reasons,
            'missing' => $missing,
        ];
    }
}
