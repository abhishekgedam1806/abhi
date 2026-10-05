<?php

namespace App\Traits;

use DB;
use File;
use ImgUploader;
use App\Company;
use Carbon\Carbon;

trait CompanyTrait
{

    private function deleteCompanyLogo($id)
    {
        try {
            $company = Company::findOrFail($id);
            $image = $company->logo;
            if (!empty($image)) {
                File::delete(ImgUploader::real_public_path() . 'company_logos/thumb/' . $image);
                File::delete(ImgUploader::real_public_path() . 'company_logos/mid/' . $image);
                File::delete(ImgUploader::real_public_path() . 'company_logos/' . $image);
            }
            return 'ok';
        } catch (ModelNotFoundException $e) {
            return 'notok';
        }
    }

    private function getCompanyIdsAndNumJobs($limit = 16)
    {
        return DB::table('jobs')
                        ->select('company_id', DB::raw('COUNT(jobs.company_id) AS num_jobs'))
                        ->groupBy('company_id')
                        ->orderBy('num_jobs', 'DESC')
                        ->limit($limit)
                        ->get();
    }

    private function getIndustryIdsFromCompanies($limit = 16)
    {
        $companies = Company::select('industry_id')->active()->whereHas('jobs', function ($query) {
                    $query->active()->notExpire();
                })->withCount(['jobs' => function ($query) {
                        $query->active()->notExpire();
                    }])->get();

        $industries_array = [];
        foreach ($companies as $company) {
            if (isset($industries_array[$company->industry_id])) {
                $industries_array[$company->industry_id] = $industries_array[$company->industry_id] + $company->jobs_count;
            } else {
                $industries_array[$company->industry_id] = $company->jobs_count;
            }
        }
        arsort($industries_array);
        return array_slice($industries_array, 0, $limit - 1, true);
    }

    private function getCompanySEO($company)
    {
        $city    = $company->getCity('city') ?? '';
        $industry = $company->getIndustry('industry') ?? '';
        $slug    = $company->slug ?? '';

        return \App\Helpers\SeoHelper::companyDetail(
            $company->name,
            $city,
            $industry,
            $slug
        );
    }

}
