@php
    $company = $job->getCompany();
    $companyName = $company ? $company->name : 'SolocomDigi';
    $companyWebsite = ($company && !empty($company->website)) ? $company->website : url('/');
    $companyLogo = ($company && !empty($company->logo)) ? asset('company_logos/' . $company->logo) : asset('images/logo.png');
    
    $city = $job->getCity('city') ?: 'Nagpur';
    $state = $job->getState('state') ?: 'Maharashtra';
    $country = 'IN';
    
    // Map employment type to Google standard
    $empType = 'FULL_TIME';
    if ($job->job_type_id == 2) $empType = 'PART_TIME';
    if ($job->job_type_id == 3) $empType = 'CONTRACTOR';
    if ($job->job_type_id == 4) $empType = 'TEMPORARY';
    if ($job->job_type_id == 5) $empType = 'INTERN';

    // HTML / Formatted clean description
    $fullDescription = !empty($job->description) ? $job->description : $job->title;

    $schemaData = [
        "@context" => "https://schema.org/",
        "@type" => "JobPosting",
        "title" => $job->title,
        "description" => $fullDescription,
        "identifier" => [
            "@type" => "PropertyValue",
            "name" => "SolocomDigi",
            "value" => (string) $job->id
        ],
        "datePosted" => $job->created_at ? $job->created_at->toIso8601String() : now()->toIso8601String(),
        "validThrough" => $job->expiry_date ? \Carbon\Carbon::parse($job->expiry_date)->toIso8601String() : now()->addDays(30)->toIso8601String(),
        "employmentType" => $empType,
        "hiringOrganization" => [
            "@type" => "Organization",
            "name" => $companyName,
            "sameAs" => $companyWebsite,
            "logo" => $companyLogo
        ],
        "jobLocation" => [
            "@type" => "Place",
            "address" => [
                "@type" => "PostalAddress",
                "streetAddress" => $company ? ($company->location ?: '') : '',
                "addressLocality" => $city,
                "addressRegion" => $state,
                "postalCode" => $job->postal_code ?: '',
                "addressCountry" => "IN"
            ]
        ],
        "directApply" => true
    ];

    // Salary Structure
    if ($job->hide_salary == 0 && ($job->salary_to > 0 || $job->salary_from > 0)) {
        $minSal = (float) ($job->salary_from ?: $job->salary_to);
        $maxSal = (float) ($job->salary_to ?: $job->salary_from);
        $schemaData["baseSalary"] = [
            "@type" => "MonetaryAmount",
            "currency" => $job->salary_currency ?: "INR",
            "value" => [
                "@type" => "QuantitativeValue",
                "minValue" => $minSal,
                "maxValue" => $maxSal,
                "unitText" => "MONTH"
            ]
        ];
    }
@endphp

<!-- Google for Jobs Schema.org JSON-LD (SolocomDigi Verified) -->
<script type="application/ld+json">
{!! json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
</script>
