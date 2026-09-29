<?php

namespace App\Helpers;

/**
 * SeoHelper — Central SEO factory for JobNBiz
 * ─────────────────────────────────────────────────────────────────────────────
 * Every public method returns a plain object with these properties:
 *
 *   ->seo_title        (string)  ← <title>
 *   ->seo_description  (string)  ← <meta name="description">
 *   ->seo_keywords     (string)  ← <meta name="keywords">
 *   ->canonical        (string)  ← <link rel="canonical">
 *   ->robots           (string)  ← <meta name="robots">
 *   ->geo_meta         (string)  ← extra geo tags (Nagpur pages), empty string otherwise
 *   ->seo_other        (string)  ← reserved / extra snippets
 *
 * Header types:
 *   A = "index, follow, max-image-preview:large"  — all indexable public pages
 *   B = "noindex, follow"                          — private / expired pages
 *   C = geo tags for Nagpur:
 *         <meta name="geo.region" content="IN-MH">
 *         <meta name="geo.placename" content="Nagpur">
 * ─────────────────────────────────────────────────────────────────────────────
 */
class SeoHelper
{
    const SITE_NAME      = 'JobNBiz';
    const PROD_DOMAIN    = 'https://jobnbiz.com';
    const STAGING_DOMAIN = 'https://mistyrose-raven-562155.hostingersite.com';

    // Nagpur geo meta block (Header C)
    const GEO_NAGPUR = '<meta name="geo.region" content="IN-MH"><meta name="geo.placename" content="Nagpur">';

    /**
     * Robots: auto-detect staging vs production
     */
    public static function robots(string $override = ''): string
    {
        if ($override !== '') {
            return $override;
        }
        $appUrl = rtrim((string) config('app.url', ''), '/');
        if (
            strpos($appUrl, 'hostingersite.com') !== false ||
            strpos($appUrl, 'localhost') !== false ||
            in_array(config('app.env'), ['local', 'staging', 'testing'])
        ) {
            return 'noindex,nofollow';
        }
        // Header A (default for all indexable pages)
        return 'index, follow, max-image-preview:large';
    }

    /**
     * Canonical URL builder
     */
    public static function canonical(string $path = ''): string
    {
        $base = rtrim(self::PROD_DOMAIN, '/');
        $path = ltrim($path, '/');
        return $path ? "{$base}/{$path}" : $base . '/';
    }

    /**
     * Internal: build the SEO object
     */
    private static function build(
        string $title,
        string $description,
        string $keywords,
        string $canonicalPath = '',
        string $robotsOverride = '',
        string $geoMeta = ''
    ): object {
        return (object) [
            'seo_title'       => $title,
            'seo_description' => $description,
            'seo_keywords'    => $keywords,
            'canonical'       => self::canonical($canonicalPath),
            'robots'          => self::robots($robotsOverride),
            'geo_meta'        => $geoMeta,
            'seo_other'       => '',
        ];
    }

    // ═══════════════════════════════════════════════════════════════════════════
    //  STATIC PAGES
    // ═══════════════════════════════════════════════════════════════════════════

    /**
     * 1. Homepage — /
     */
    public static function homepage(): object
    {
        return self::build(
            'JobNBiz – Latest Jobs in India | Fresher & IT Vacancies',
            'Find the latest jobs in India for freshers and professionals. Search IT, sales, marketing, HR, work from home, and private job opportunities on JobNBiz.',
            'jobs in india, fresher jobs, IT jobs, work from home jobs, job portal india, private jobs india',
            ''
        );
    }

    /**
     * 2. All Jobs — /jobs
     */
    public static function jobList(): object
    {
        return self::build(
            'Latest Jobs in India | Search Job Vacancies | JobNBiz',
            'Search verified job vacancies in India by title, skills, experience, location, and salary. Find and apply for top private company jobs on JobNBiz.',
            'latest jobs india, job search, private job vacancies, hiring in india, urgent job openings',
            'jobs'
        );
    }

    /**
     * 7. Companies Listing — /companies
     */
    public static function companiesListing(): object
    {
        return self::build(
            'Top Companies Hiring in India | Employer List | JobNBiz',
            'Explore top companies and hiring employers in India. View verified company profiles, current job openings, and apply directly to employers on JobNBiz.',
            'top hiring companies, employers in india, company directory, recruiter profiles india',
            'companies'
        );
    }

    /**
     * 9. Contact Us — /contact-us
     */
    public static function contactUs(): object
    {
        return self::build(
            'Contact Us | JobNBiz Support & Helpdesk India',
            'Have questions or need support? Contact the JobNBiz team for job posting assistance, candidate inquiries, employer solutions, or technical help.',
            'contact jobnbiz, job portal support, customer care, recruiter helpdesk',
            'contact-us'
        );
    }

    /**
     * 10. FAQ — /faq
     */
    public static function faq(): object
    {
        return self::build(
            'FAQ – Frequently Asked Questions | JobNBiz India',
            'Find answers to common questions about JobNBiz, including candidate profile setup, applying for jobs, employer packages, job postings, and payments.',
            'jobnbiz faq, job portal questions, how to apply for jobs, employer faq',
            'faq'
        );
    }

    /**
     * 11. Pricing — /pricing
     */
    public static function pricing(): object
    {
        return self::build(
            'Pricing Plans & Job Posting Packages | JobNBiz India',
            'Affordable job posting packages for recruiters and employers in India. Post jobs, access verified candidate resumes, and hire talent fast on JobNBiz.',
            'job posting plans, recruiter packages, hire talent india, post a job price, resume database access',
            'pricing'
        );
    }

    /**
     * 12. Blog Listing — /blog
     */
    public static function blogListing(): object
    {
        return self::build(
            'Career Tips, Resume & Interview Advice | JobNBiz Blog',
            'Read actionable career guides, resume tips, interview preparation advice, and HR insights to accelerate your job search and professional career growth.',
            'career tips, interview advice, resume building guide, career growth, job search tips',
            'blog'
        );
    }

    /**
     * 15. Businesses Listing — /businesses
     */
    public static function businessListing(): object
    {
        return self::build(
            'Business Directory India | Local Services & Shops | JobNBiz',
            'Discover verified local businesses, stores, and service providers across India. Connect with local companies and grow your business network on JobNBiz.',
            'business directory india, local businesses, find services near me, b2b directory india',
            'businesses'
        );
    }

    /**
     * Business Directory by City — /businesses/{city}
     */
    public static function businessCity(string $city): object
    {
        $slug = \Illuminate\Support\Str::slug($city);
        $isNagpur = (strtolower($city) === 'nagpur');
        return self::build(
            "Businesses & Local Services in {$city} | JobNBiz",
            "Discover verified local businesses, shops, and services in {$city}. Browse the JobNBiz business directory for trusted services and professionals.",
            "businesses in {$city}, local services {$city}, shops in {$city}, {$city} business directory",
            "businesses/{$slug}",
            '',
            $isNagpur ? self::GEO_NAGPUR : ''
        );
    }

    /**
     * Business Directory by Category — /businesses/category/{slug}
     */
    public static function businessCategory(string $categoryName, string $slug = ''): object
    {
        return self::build(
            "Top {$categoryName} Services in India | Business Directory | JobNBiz",
            "Find verified {$categoryName} services, companies and professionals in India. View contact numbers, reviews and addresses on JobNBiz.",
            "{$categoryName}, top {$categoryName} services, find {$categoryName} india, best {$categoryName}",
            $slug ? "businesses/{$slug}" : 'businesses'
        );
    }

    /**
     * Business Directory by Category + City
     */
    public static function businessCategoryCity(string $categoryName, string $city, string $slug = ''): object
    {
        $isNagpur = (strtolower($city) === 'nagpur');
        return self::build(
            "Top {$categoryName} in {$city} | Local Business Directory | JobNBiz",
            "Find verified {$categoryName} in {$city}. Check contact numbers, address, WhatsApp, directions, reviews and business hours on JobNBiz.",
            "{$categoryName} in {$city}, best {$categoryName} {$city}, {$city} {$categoryName} services",
            $slug ? "businesses/{$slug}" : 'businesses',
            '',
            $isNagpur ? self::GEO_NAGPUR : ''
        );
    }

    // ═══════════════════════════════════════════════════════════════════════════
    //  DYNAMIC PAGE FORMULAS
    // ═══════════════════════════════════════════════════════════════════════════

    /**
     * 3. City Jobs — /jobs-in-{city}
     */
    public static function city(string $cityName): object
    {
        $slug     = \Illuminate\Support\Str::slug($cityName);
        $isNagpur = (strtolower($cityName) === 'nagpur');

        if ($isNagpur) {
            return self::build(
                'Jobs in Nagpur | Latest Vacancies & Hiring | JobNBiz',
                'Find the latest job openings in Nagpur for freshers and experienced candidates. Explore IT, sales, back office, accounts, and part-time jobs in Nagpur.',
                'jobs in nagpur, nagpur job vacancies, fresher jobs nagpur, private jobs in nagpur, IT jobs nagpur',
                'jobs-in-nagpur',
                '',
                self::GEO_NAGPUR
            );
        }

        return self::build(
            "Jobs in {$cityName} | Latest Vacancies & Hiring | JobNBiz",
            "Find the latest jobs in {$cityName} for freshers and experienced candidates. Explore IT, sales, marketing, accounts, and private job vacancies on JobNBiz.",
            "jobs in {$cityName}, {$cityName} jobs, latest jobs in {$cityName}, job vacancy in {$cityName}, fresher jobs in {$cityName}",
            "jobs-in-{$slug}"
        );
    }

    /**
     * 4. Category Jobs — /jobs/category/{slug}
     */
    public static function category(string $categoryName, string $categorySlug = ''): object
    {
        $slug = $categorySlug ?: \Illuminate\Support\Str::slug($categoryName);

        return self::build(
            "{$categoryName} Jobs in India | Hiring Now | JobNBiz",
            "Find the latest {$categoryName} jobs in India for freshers and experienced candidates. Browse verified {$categoryName} vacancies on JobNBiz.",
            "{$categoryName} jobs, {$categoryName} jobs india, {$categoryName} vacancies, {$categoryName} hiring",
            "jobs/category/{$slug}"
        );
    }

    /**
     * 5. Category + City — /jobs-in-{city}/{category}
     */
    public static function categoryCity(string $categoryName, string $cityName): object
    {
        $citySlug = \Illuminate\Support\Str::slug($cityName);
        $catSlug  = \Illuminate\Support\Str::slug($categoryName);
        $isNagpur = (strtolower($cityName) === 'nagpur');

        $nagpurKeywords = [
            'data entry'      => 'data entry jobs in nagpur, data entry work, part time data entry nagpur, back office data entry',
            'telecaller'      => 'telecaller jobs in nagpur, telecalling jobs for freshers, call center jobs nagpur, tele sales jobs nagpur',
            'accountant'      => 'accountant jobs in nagpur, tally accountant jobs, accounts executive jobs nagpur',
            'accounts'        => 'accountant jobs in nagpur, tally accountant jobs, accounts executive jobs nagpur',
            'receptionist'    => 'receptionist jobs in nagpur, front office executive jobs, female receptionist jobs nagpur',
            'delivery'        => 'delivery boy jobs in nagpur, delivery executive jobs, courier jobs nagpur',
            'driver'          => 'driver jobs in nagpur, car driver jobs, truck driver jobs nagpur',
            'sales executive' => 'sales executive jobs in nagpur, field sales jobs, marketing jobs nagpur',
            'sales'           => 'sales executive jobs in nagpur, field sales jobs, marketing jobs nagpur',
            'back office'     => 'back office jobs in nagpur, back office executive, computer operator jobs nagpur',
            'teacher'         => 'teacher jobs in nagpur, school teacher vacancy, tutor jobs nagpur',
            'security guard'  => 'security guard jobs in nagpur, security supervisor jobs, guard vacancy nagpur',
            'security'        => 'security guard jobs in nagpur, security supervisor jobs, guard vacancy nagpur',
            'hr recruiter'    => 'hr recruiter jobs in nagpur, hr executive jobs, recruitment jobs nagpur',
            'hr'              => 'hr recruiter jobs in nagpur, hr executive jobs, recruitment jobs nagpur',
            'it & software'   => 'it jobs in nagpur, software jobs in nagpur, developer jobs in nagpur, it fresher jobs nagpur',
            'it'              => 'it jobs in nagpur, software jobs in nagpur, developer jobs in nagpur, it fresher jobs nagpur',
        ];

        $catKey = strtolower($categoryName);
        $nagKw  = $nagpurKeywords[$catKey] ?? "{$categoryName} jobs in nagpur, {$categoryName} jobs, fresher {$categoryName} nagpur";

        if ($isNagpur) {
            return self::build(
                "{$categoryName} Jobs in Nagpur | Latest Vacancies | JobNBiz",
                "Explore and apply for latest {$categoryName} jobs in Nagpur. Check salary, requirements and apply online on JobNBiz.",
                $nagKw,
                "jobs-in-{$citySlug}/{$catSlug}",
                '',
                self::GEO_NAGPUR
            );
        }

        return self::build(
            "{$categoryName} Jobs in {$cityName} | Latest Vacancies | JobNBiz",
            "Explore and apply for latest {$categoryName} jobs in {$cityName}. Check salary, eligibility and vacancies on JobNBiz.",
            "{$categoryName} jobs in {$cityName}, {$cityName} {$categoryName} vacancies, {$categoryName} fresher jobs {$cityName}",
            "jobs-in-{$citySlug}/{$catSlug}"
        );
    }

    /**
     * 6. Job Detail — /job/{slug}
     */
    public static function jobDetail(
        string $jobTitle,
        string $companyName,
        string $cityName  = '',
        string $jobSlug   = '',
        string $jobType   = '',
        string $salary    = '',
        string $lastDate  = '',
        bool   $isExpired = false
    ): object {
        $locationPart = $cityName ? " in {$cityName}" : '';
        $rawTitle     = "{$jobTitle} at {$companyName}{$locationPart} | JobNBiz";
        $title        = (mb_strlen($rawTitle) > 60)
                        ? "{$jobTitle} at {$companyName}{$locationPart}"
                        : $rawTitle;

        $desc = "Apply for {$jobTitle} at {$companyName}{$locationPart}.";
        if ($jobType)    $desc .= " {$jobType}.";
        if ($salary)     $desc .= " Salary: {$salary}.";
        $desc .= ' View job requirements and apply online on JobNBiz.';

        $catSuffix = $cityName ? " {$cityName}" : ' india';
        $keywords  = "{$jobTitle} jobs in{$catSuffix}, {$jobTitle} vacancy, {$companyName} jobs, {$companyName} vacancy";
        $robotsVal = $isExpired ? 'noindex, follow' : self::robots();
        $isNagpur  = (strtolower($cityName) === 'nagpur');

        return self::build(
            $title,
            $desc,
            $keywords,
            $jobSlug ? "job/{$jobSlug}" : 'jobs',
            $robotsVal,
            $isNagpur ? self::GEO_NAGPUR : ''
        );
    }

    /**
     * 8. Company Detail — /company/{slug}
     */
    public static function companyDetail(
        string $companyName,
        string $city     = '',
        string $industry = '',
        string $slug     = '',
        int    $openJobs = 0
    ): object {
        $locationPart = $city ? " in {$city}" : ' in India';
        $jobCount     = $openJobs > 0 ? "See {$openJobs} open jobs" : 'Explore job openings';
        $isNagpur     = (strtolower($city) === 'nagpur');

        return self::build(
            "{$companyName} Jobs & Profile{$locationPart} | JobNBiz",
            "{$jobCount} at {$companyName}{$locationPart}. Read company profile, workplace details and apply online on JobNBiz.",
            "{$companyName} careers, {$companyName} jobs, {$companyName} jobs{$locationPart}, {$companyName} vacancy, {$companyName} hiring",
            $slug ? "company/{$slug}" : 'companies',
            '',
            $isNagpur ? self::GEO_NAGPUR : ''
        );
    }

    /**
     * 13. Blog Detail — /blog/{slug}
     */
    public static function blogDetail(
        string $rawTitle,
        string $description,
        string $keywords = '',
        string $slug     = ''
    ): object {
        $withSuffix = $rawTitle . ' | JobNBiz Blog';
        $title      = (mb_strlen($withSuffix) <= 60) ? $withSuffix : $rawTitle;

        return self::build(
            $title,
            $description ?: 'Read this career advice and hiring guide on JobNBiz Blog.',
            $keywords,
            $slug ? "blog/{$slug}" : 'blog'
        );
    }

    /**
     * 14. Blog Category — /blog/category/{slug}
     */
    public static function blogCategory(string $categoryName, string $slug = ''): object
    {
        return self::build(
            "{$categoryName} – Career Articles & Guides | JobNBiz Blog",
            "Read {$categoryName} articles, career tips and guides on JobNBiz Blog to find the right job in India.",
            "{$categoryName} tips, {$categoryName} guide, career advice, job search tips",
            $slug ? "blog/category/{$slug}" : 'blog'
        );
    }

    /**
     * 16. Business Detail — /business/{slug}
     */
    public static function businessDetail(
        string $businessName,
        string $category   = '',
        string $city       = '',
        string $area       = '',
        string $slug       = '',
        bool   $incomplete = false
    ): object {
        $catPart  = $category ? " – {$category}" : '';
        $cityPart = $city     ? " in {$city}"    : '';
        $areaPart = $area     ? " in {$area}, {$city}" : ($city ? " in {$city}" : '');

        $title = "{$businessName}{$catPart}{$cityPart} | JobNBiz";

        $desc  = "{$businessName} is a";
        $desc .= $category ? " {$category}" : ' business';
        $desc .= $areaPart ? "{$areaPart}." : '.';
        $desc .= ' Find address, phone number, working hours and services on JobNBiz.';

        $kw   = "{$businessName}";
        if ($category && $city) $kw .= ", {$category} in {$city}";
        if ($category && $area) $kw .= ", {$category} near {$area}";
        $kw  .= ", {$businessName} contact number, {$businessName} address";

        $robotsVal = $incomplete ? 'noindex, follow' : self::robots();
        $isNagpur  = (strtolower($city) === 'nagpur');

        return self::build(
            $title,
            $desc,
            $kw,
            $slug ? "business/{$slug}" : 'businesses',
            $robotsVal,
            $isNagpur ? self::GEO_NAGPUR : ''
        );
    }

    // ═══════════════════════════════════════════════════════════════════════════
    //  UTILITY / PRIVATE PAGES
    // ═══════════════════════════════════════════════════════════════════════════

    /**
     * Header B — noindex pages (login, register, dashboard, etc.)
     */
    public static function noindex(string $pageLabel = 'JobNBiz'): object
    {
        return self::build(
            "JobNBiz – {$pageLabel}",
            '',
            '',
            '',
            'noindex, follow'
        );
    }

    /**
     * Custom / pass-through for CMS pages
     */
    public static function custom(
        string $title,
        string $description,
        string $keywords      = '',
        string $canonicalPath = '',
        string $robots        = '',
        string $geoMeta       = ''
    ): object {
        return self::build($title, $description, $keywords, $canonicalPath, $robots, $geoMeta);
    }
}
