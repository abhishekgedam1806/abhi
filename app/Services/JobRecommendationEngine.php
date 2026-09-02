<?php

namespace App\Services;

use DB;
use App\Job;
use App\User;
use App\Company;
use App\Country;
use App\State;
use App\City;
use App\JobSkill;
use App\JobSkillManager;
use App\FunctionalArea;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class JobRecommendationEngine
{
    /**
     * City Coordinates Database for Accurate KM Distance (Haversine)
     */
    protected static $cityCoordinates = [
        'nagpur' => ['lat' => 21.1458, 'lon' => 79.0882],
        'wardha' => ['lat' => 20.7453, 'lon' => 78.6022],
        'bhandara' => ['lat' => 21.1714, 'lon' => 79.6547],
        'gondia' => ['lat' => 21.4604, 'lon' => 80.1961],
        'chandrapur' => ['lat' => 19.9615, 'lon' => 79.2961],
        'yavatmal' => ['lat' => 20.3888, 'lon' => 78.1204],
        'amravati' => ['lat' => 20.9320, 'lon' => 77.7523],
        'akola' => ['lat' => 20.7002, 'lon' => 77.0082],
        'pune' => ['lat' => 18.5204, 'lon' => 73.8567],
        'mumbai' => ['lat' => 19.0760, 'lon' => 72.8777],
        'navi mumbai' => ['lat' => 19.0330, 'lon' => 73.0297],
        'thane' => ['lat' => 19.2183, 'lon' => 72.9781],
        'nashik' => ['lat' => 19.9975, 'lon' => 73.7898],
        'aurangabad' => ['lat' => 19.8762, 'lon' => 75.3433],
        'chhatrapati sambhajinagar' => ['lat' => 19.8762, 'lon' => 75.3433],
        'kolhapur' => ['lat' => 16.7050, 'lon' => 74.2433],
        'solapur' => ['lat' => 17.6599, 'lon' => 75.9064],
        'satara' => ['lat' => 17.6805, 'lon' => 74.0183],
        'delhi' => ['lat' => 28.6139, 'lon' => 77.2090],
        'new delhi' => ['lat' => 28.6139, 'lon' => 77.2090],
        'noida' => ['lat' => 28.5355, 'lon' => 77.3910],
        'gurgaon' => ['lat' => 28.4595, 'lon' => 77.0266],
        'gurugram' => ['lat' => 28.4595, 'lon' => 77.0266],
        'bangalore' => ['lat' => 12.9716, 'lon' => 77.5946],
        'bengaluru' => ['lat' => 12.9716, 'lon' => 77.5946],
        'hyderabad' => ['lat' => 17.3850, 'lon' => 78.4867],
        'chennai' => ['lat' => 13.0827, 'lon' => 80.2707],
        'kolkata' => ['lat' => 22.5726, 'lon' => 88.3639],
        'ahmedabad' => ['lat' => 23.0225, 'lon' => 72.5714],
        'surat' => ['lat' => 21.1702, 'lon' => 72.8311],
        'jaipur' => ['lat' => 26.9124, 'lon' => 75.7873],
        'indore' => ['lat' => 22.7196, 'lon' => 75.8577],
        'bhopal' => ['lat' => 23.2599, 'lon' => 77.4126],
        'lucknow' => ['lat' => 26.8467, 'lon' => 80.9462],
        'kanpur' => ['lat' => 26.4499, 'lon' => 80.3319],
        'chandigarh' => ['lat' => 30.7333, 'lon' => 76.7794],
        'kochi' => ['lat' => 9.9312, 'lon' => 76.2673],
        'thiruvananthapuram' => ['lat' => 8.5241, 'lon' => 76.9366],
        'visakhapatnam' => ['lat' => 17.6868, 'lon' => 83.2185],
        'vijayawada' => ['lat' => 16.5062, 'lon' => 80.6480],
        'bhubaneswar' => ['lat' => 20.2961, 'lon' => 85.8245],
        'patna' => ['lat' => 25.5941, 'lon' => 85.1376],
        'ranchi' => ['lat' => 23.3441, 'lon' => 85.3096],
        'raipur' => ['lat' => 21.2514, 'lon' => 81.6296],
        'jabalpur' => ['lat' => 23.1815, 'lon' => 79.9864],
        'gwalior' => ['lat' => 26.2183, 'lon' => 78.1828],
    ];

    /**
     * Domain Taxonomy & Synonym Expansion Map
     */
    protected static $synonymGroups = [
        'web_design' => [
            'terms'            => ['website', 'web design', 'web designer', 'website designer', 'web development', 'web developer', 'frontend developer', 'frontend', 'ui designer', 'wordpress developer', 'wordpress', 'html', 'css', 'javascript', 'ui/ux', 'website developer'],
            'functional_areas' => [23, 40, 128, 144, 145, 29],  // Creative Design, Graphic Design & Creative, Software & Web Dev, Web Developer, Web Marketing, Development
            'skills'           => ['web design', 'html', 'css', 'javascript', 'wordpress', 'frontend', 'bootstrap', 'ui/ux', 'photoshop', 'responsive design', 'website']
        ],
        'seo_digital_marketing' => [
            'terms'            => ['seo', 'seo executive', 'seo specialist', 'seo expert', 'search engine optimization', 'digital marketing', 'digital marketer', 'online marketing', 'performance marketing', 'social media marketing', 'smo', 'sem', 'google ads', 'ppc', 'content marketing'],
            'functional_areas' => [69, 70, 80, 81, 121, 126, 127, 138, 145, 7, 8, 72, 40],  // Digital Marketing, Marketing, Online Advertising, Online Marketing, SEO, SEM, SMO, Telemarketing, Web Marketing, Advertising, Media & Advertising, Creative Design (FA 40)
            'skills'           => ['seo', 'google ads', 'digital marketing', 'social media', 'google analytics', 'on-page seo', 'off-page seo', 'link building', 'keyword research', 'sem']
        ],
        'software_development' => [
            'terms'            => ['php', 'php developer', 'laravel', 'laravel developer', 'backend developer', 'software engineer', 'software developer', 'full stack developer', 'node', 'react', 'python', 'java', 'dot net', 'ios developer', 'android developer', 'programmer'],
            'functional_areas' => [128, 129, 144, 148, 57, 58, 78, 79, 15, 134], // Software & Web Dev, Software Engineer, Web Developer, IT & Software, IT Security, IT Systems Analyst, Network Admin, Network Operation, Business Systems Analyst, Systems Analyst
            'skills'           => ['php', 'laravel', 'mysql', 'api', 'javascript', 'backend', 'full stack', 'git', 'oop', 'mvc']
        ],
        'graphic_design' => [
            'terms'            => ['graphic designer', 'graphics', 'graphic design', 'photoshop', 'illustrator', 'corel draw', 'banner design', 'visual designer', 'canva', 'logo design', 'creative design'],
            'functional_areas' => [23, 40, 71, 72, 86, 87], // Creative Design, Graphic Design & Creative, Media - Print & Electronic, Media & Advertising, Print Media, Printing
            'skills'           => ['photoshop', 'illustrator', 'graphic design', 'corel draw', 'creatives', 'typography', 'branding']
        ],
        'accounting_finance' => [
            'terms'            => ['accountant', 'accounts executive', 'accounting', 'tally', 'tally erp', 'gst', 'taxation', 'finance', 'chartered accountant', 'ca', 'auditor', 'bookkeeper', 'billing'],
            'functional_areas' => [1, 2, 150, 155, 12, 56], // Accountant, Accounts Finance & Financial Services, Accounts & Finance, Banking & Finance, Bank Operation, Investment Operations
            'skills'           => ['tally', 'gst', 'accounting', 'ms excel', 'taxation', 'billing', 'balance sheet', 'tds']
        ],
        'driver_logistics' => [
            'terms'            => ['driver', 'car driver', 'cab driver', 'chauffeur', 'delivery boy', 'delivery executive', 'rider', 'logistics', 'transport', 'courier'],
            'functional_areas' => [30, 62, 140, 152, 153, 131, 132, 133, 143], // Distribution & Logistics, Logistics & Warehousing, Transportation & Warehousing, Driving, Logistics & Delivery, Stores & Warehousing, Supply Chain, Supply Chain Mgmt, Warehousing
            'skills'           => ['driving', 'commercial license', 'route knowledge', 'delivery', 'navigation', 'vehicle maintenance']
        ],
        'sales_bpo' => [
            'terms'            => ['sales', 'sales executive', 'telecaller', 'telemarketing', 'bpo', 'call center', 'customer support', 'business development', 'bde', 'inside sales'],
            'functional_areas' => [118, 119, 120, 137, 138, 141, 149, 13, 17, 25], // Sales, Sales & Business Dev, Sales Support, Tele Sale Rep, Telemarketing, TSR, BPO/Call Center, Business Dev, Client Services, Customer Support
            'skills'           => ['sales', 'telecalling', 'communication', 'lead generation', 'cold calling', 'customer service', 'negotiation']
        ],
        'hr_recruitment' => [
            'terms'            => ['hr', 'human resources', 'recruitment', 'recruiter', 'talent acquisition', 'hr executive', 'payroll'],
            'functional_areas' => [48, 49, 104, 105, 151], // HR, Human Resources, Recruiting, Recruitment, HR & Recruitment
            'skills'           => ['recruitment', 'hr', 'payroll', 'talent acquisition', 'employee relations', 'onboarding']
        ],
        'data_entry' => [
            'terms'            => ['data entry', 'data entry operator', 'typing', 'data entry executive', 'mis executive'],
            'functional_areas' => [26, 27, 65, 142], // Data Entry, Data Entry Operator, MIS, Typing
            'skills'           => ['data entry', 'ms excel', 'ms word', 'typing', 'mis', 'computer']
        ],
        'work_from_home' => [
            'terms'            => ['work from home', 'wfh', 'remote work', 'online work', 'home based', 'freelance work'],
            'functional_areas' => [156], // Work From Home
            'skills'           => ['remote work', 'communication', 'computer', 'internet']
        ],
        'internship_fresher' => [
            'terms'            => ['internship', 'intern', 'fresher', 'trainee', 'entry level', 'fresh graduate'],
            'functional_areas' => [54, 55, 157, 31, 139], // Intern, Internship, Internship/Fresher, Education & Training, Training & Development
            'skills'           => ['communication', 'computer basics', 'ms office', 'learning']
        ],
    ];

    /**
     * Common Indian Locality to Major City Mapping Dictionary for Area Searches
     */
    public static $localityToCityMap = [
        // Pune Localities
        'hinjewadi' => 'Pune', 'kothrud' => 'Pune', 'viman nagar' => 'Pune', 'kharadi' => 'Pune',
        'baner' => 'Pune', 'wakad' => 'Pune', 'hadapsar' => 'Pune', 'magarpatta' => 'Pune',
        'shivajinagar' => 'Pune', 'pimpri' => 'Pune', 'chinchwad' => 'Pune', 'bhosari' => 'Pune',
        'yerwada' => 'Pune', 'aundh' => 'Pune', 'kalyani nagar' => 'Pune', 'katraj' => 'Pune',
        'kondhwa' => 'Pune', 'bavdhan' => 'Pune', 'hinjawadi' => 'Pune', 'chakan' => 'Pune',
        
        // Nagpur Localities
        'dharampeth' => 'Nagpur', 'sadar' => 'Nagpur', 'sitabuldi' => 'Nagpur', 'wardha road' => 'Nagpur',
        'hingna' => 'Nagpur', 'midc' => 'Nagpur', 'butibori' => 'Nagpur', 'mahal' => 'Nagpur',
        'itwari' => 'Nagpur', 'ramdaspeth' => 'Nagpur', 'shankar nagar' => 'Nagpur', 'manish nagar' => 'Nagpur',
        'nandanvan' => 'Nagpur', 'wadi' => 'Nagpur', 'trimurti nagar' => 'Nagpur', 'khamla' => 'Nagpur',
        
        // Mumbai / MMR Localities
        'andheri' => 'Mumbai', 'bandra' => 'Mumbai', 'powai' => 'Mumbai', 'goregaon' => 'Mumbai',
        'borivali' => 'Mumbai', 'thane' => 'Mumbai', 'navi mumbai' => 'Mumbai', 'vashi' => 'Mumbai',
        'bkc' => 'Mumbai', 'malad' => 'Mumbai', 'kurla' => 'Mumbai', 'dadar' => 'Mumbai',
        'worli' => 'Mumbai', 'nariman point' => 'Mumbai', 'kandivali' => 'Mumbai', 'ghatkopar' => 'Mumbai',
        'lower parel' => 'Mumbai', 'juhu' => 'Mumbai', 'chembur' => 'Mumbai', 'mulund' => 'Mumbai',
        
        // Bangalore Localities
        'whitefield' => 'Bengaluru', 'electronic city' => 'Bengaluru', 'koramangala' => 'Bengaluru',
        'indiranagar' => 'Bengaluru', 'hsr layout' => 'Bengaluru', 'marathahalli' => 'Bengaluru',
        'bellandur' => 'Bengaluru', 'jp nagar' => 'Bengaluru', 'btm layout' => 'Bengaluru',
        'manyata' => 'Bengaluru', 'hebbal' => 'Bengaluru', 'yelahanka' => 'Bengaluru', 'bangalore' => 'Bengaluru',
        'bengaluru' => 'Bengaluru',
        
        // Delhi NCR Localities
        'noida' => 'Delhi', 'greater noida' => 'Delhi', 'gurgaon' => 'Delhi', 'gurugram' => 'Delhi',
        'connaught place' => 'Delhi', 'okhla' => 'Delhi', 'saket' => 'Delhi', 'nehru place' => 'Delhi',
        'dwarka' => 'Delhi', 'karol bagh' => 'Delhi', 'laxmi nagar' => 'Delhi', 'ghaziabad' => 'Delhi',
        'faridabad' => 'Delhi', 'new delhi' => 'Delhi', 'south delhi' => 'Delhi', 'north delhi' => 'Delhi',
        
        // Hyderabad Localities
        'hitec city' => 'Hyderabad', 'gachibowli' => 'Hyderabad', 'madhapur' => 'Hyderabad',
        'kondapur' => 'Hyderabad', 'kukatpally' => 'Hyderabad', 'secunderabad' => 'Hyderabad',
        'banjara hills' => 'Hyderabad', 'jubilee hills' => 'Hyderabad', 'begumpet' => 'Hyderabad',
        
        // Ahmedabad Localities
        'sg highway' => 'Ahmedabad', 'prahlad nagar' => 'Ahmedabad', 'vastrapur' => 'Ahmedabad',
        'navrangpura' => 'Ahmedabad', 'sanand' => 'Ahmedabad', 'gandhinagar' => 'Ahmedabad',
        
        // Kolkata Localities
        'salt lake' => 'Kolkata', 'sector v' => 'Kolkata', 'new town' => 'Kolkata', 'rajarhat' => 'Kolkata',
        'park street' => 'Kolkata', 'howrah' => 'Kolkata',
        
        // Chennai Localities
        'omr' => 'Chennai', 'guindy' => 'Chennai', 't nagar' => 'Chennai', 'velachery' => 'Chennai',
        'anna nagar' => 'Chennai', 'ambattur' => 'Chennai', 'porur' => 'Chennai',
    ];

    // ──────────────────────────────────────────────────────────────────
    // FUZZY / TYPO-TOLERANT MATCHING HELPERS
    // ──────────────────────────────────────────────────────────────────

    /**
     * Build a flat vocabulary list from all synonym groups.
     * Cached statically so it is only computed once per request.
     */
    protected static function buildVocabulary(): array
    {
        static $vocab = null;
        if ($vocab !== null) return $vocab;

        $vocab = [];
        foreach (self::$synonymGroups as $group) {
            foreach ($group['terms'] as $term) {
                $t = strtolower($term);
                $vocab[] = $t;
                // Also index individual words of multi-word terms
                if (strpos($t, ' ') !== false) {
                    foreach (explode(' ', $t) as $w) {
                        if (strlen($w) >= 4) $vocab[] = $w;
                    }
                }
            }
        }
        return $vocab = array_unique($vocab);
    }

    /**
     * Find the closest matching vocabulary word for a single query word.
     * Returns the known word when similarity >= $threshold%, else original.
     */
    protected static function fuzzyCorrectWord(string $word, array $vocab, float $threshold = 72.0): string
    {
        $len = strlen($word);
        if ($len < 4) return $word; // too short to fuzzy-correct safely

        $best      = $word;
        $bestScore = 0.0;

        foreach ($vocab as $knownWord) {
            // Skip if length difference is too large (performance guard)
            if (abs($len - strlen($knownWord)) > max(3, (int)($len * 0.45))) continue;
            similar_text($word, $knownWord, $pct);
            if ($pct >= $threshold && $pct > $bestScore) {
                $bestScore = $pct;
                $best      = $knownWord;
            }
        }
        return $best;
    }

    /**
     * Fuzzy-correct an entire multi-word query string.
     * Strategy:
     *   1. Try full-phrase similarity against known multi-word terms.
     *   2. Fall back to word-by-word correction.
     *
     * Returns [corrected_query, was_corrected].
     */
    protected static function fuzzyCorrectQuery(string $query): array
    {
        $normalized = strtolower(trim($query));
        if (mb_strlen($normalized) < 4) return [$normalized, false];

        $vocab = self::buildVocabulary();

        // ── Pass 1: full-phrase similarity (catches "digitle marketang" → "digital marketing") ──
        $bestPhraseScore = 0.0;
        $bestPhrase      = null;
        foreach ($vocab as $term) {
            if (strpos($term, ' ') === false) continue; // multi-word only
            similar_text($normalized, $term, $pct);
            if ($pct >= 68 && $pct > $bestPhraseScore) {
                $bestPhraseScore = $pct;
                $bestPhrase      = $term;
            }
        }
        if ($bestPhrase !== null && $bestPhraseScore >= 68) {
            return [$bestPhrase, true];
        }

        // ── Pass 2: word-by-word correction ──
        $words     = preg_split('/\s+/', $normalized);
        $corrected = [];
        $changed   = false;
        foreach ($words as $word) {
            $fixed = self::fuzzyCorrectWord($word, $vocab, 72.0);
            if ($fixed !== $word) $changed = true;
            $corrected[] = $fixed;
        }
        return [implode(' ', $corrected), $changed];
    }

    /**
     * Parse Search Intent from Query String
     */
    public static function extractSearchIntent($query)
    {
        $raw = trim($query);
        $normalized = strtolower($raw);

        $intent = [
            'raw_query' => $raw,
            'normalized_query' => $normalized,
            'keywords' => [],
            'synonyms' => [],
            'target_functional_areas' => [],
            'target_skills' => [],
            'work_mode' => null, // 'remote', 'office', 'hybrid'
            'job_type' => null,  // 'full_time', 'part_time', 'internship', 'freelance'
            'experience_level' => null, // 'fresher', 'experienced'
            'location' => null,
            'location_name' => null,
            'detected_city_ids' => [],
            'detected_state_ids' => [],
            'detected_country_ids' => [],
            'is_location_only' => false,
            'max_distance_km' => null,
            'is_empty' => empty($normalized)
        ];

        if ($intent['is_empty']) {
            return $intent;
        }

        // 1. Detect Work Mode Intent
        if (preg_match('/\b(work from home|wfh|remote|remote work|online work|home based)\b/i', $normalized)) {
            $intent['work_mode'] = 'remote';
        } elseif (preg_match('/\b(office|on-site|onsite|in-office)\b/i', $normalized)) {
            $intent['work_mode'] = 'office';
        } elseif (preg_match('/\b(hybrid)\b/i', $normalized)) {
            $intent['work_mode'] = 'hybrid';
        }

        // 2. Detect Job Type Intent
        if (preg_match('/\b(part time|part-time|parttime|flexible work|half day)\b/i', $normalized)) {
            $intent['job_type'] = 'part_time';
        } elseif (preg_match('/\b(internship|intern|training|trainee)\b/i', $normalized)) {
            $intent['job_type'] = 'internship';
        } elseif (preg_match('/\b(freelance|freelancer|contract|contractual)\b/i', $normalized)) {
            $intent['job_type'] = 'freelance';
        } elseif (preg_match('/\b(full time|full-time|fulltime|permanent)\b/i', $normalized)) {
            $intent['job_type'] = 'full_time';
        }

        // 3. Detect Experience Intent
        if (preg_match('/\b(fresher|freshers|entry level|0 exp|0 years|no experience|beginner)\b/i', $normalized)) {
            $intent['experience_level'] = 'fresher';
        } elseif (preg_match('/\b(senior|lead|manager|expert|experienced|5\+ years|10\+ years)\b/i', $normalized)) {
            $intent['experience_level'] = 'senior';
        }

        // 4. Detect Distance / Radius Intent (e.g. "within 5 km", "5 km", "10 km")
        if (preg_match('/\b(within|in|radius of)?\s*(\d+)\s*(km|kms|kilometer|kilometers)\b/i', $normalized, $distMatches)) {
            $intent['max_distance_km'] = (float)$distMatches[2];
        }

        // 5. Clean query and Expand Keywords and Synonyms FIRST
        $cleanQuery = preg_replace('/\b(work from home|wfh|remote|part time|internship|full time|fresher|jobs|job|within|km|kms|near me|in|at)\b/i', '', $normalized);
        $cleanQuery = trim(preg_replace('/\s+/', ' ', $cleanQuery));
        $intent['clean_query'] = $cleanQuery;

        if (!empty($cleanQuery)) {
            $words = explode(' ', $cleanQuery);
            $intent['keywords'] = array_values(array_filter($words, function($w) {
                return strlen($w) >= 2;
            }));

            // Match against Synonym Groups
            foreach (self::$synonymGroups as $groupKey => $group) {
                $matched = false;
                foreach ($group['terms'] as $term) {
                    if (stripos($cleanQuery, $term) !== false || stripos($term, $cleanQuery) !== false) {
                        $matched = true;
                        break;
                    }
                }
                if ($matched) {
                    $intent['synonyms'] = array_unique(array_merge($intent['synonyms'], $group['terms']));
                    $intent['target_functional_areas'] = array_unique(array_merge($intent['target_functional_areas'], $group['functional_areas']));
                    $intent['target_skills'] = array_unique(array_merge($intent['target_skills'], $group['skills']));
                }
            }

            // ── Fuzzy / Typo Correction ──────────────────────────────────────────
            // If no synonyms matched, try to fuzzy-correct the query and re-match.
            // Handles cases like "Digitle marketang" → "digital marketing".
            if (empty($intent['synonyms']) && mb_strlen($cleanQuery) >= 4) {
                [$fuzzyQuery, $wasCorrected] = self::fuzzyCorrectQuery($cleanQuery);
                if ($wasCorrected && $fuzzyQuery !== $cleanQuery) {
                    $intent['fuzzy_query']     = $fuzzyQuery;
                    $intent['fuzzy_corrected'] = true;
                    // Re-run synonym matching with corrected query
                    foreach (self::$synonymGroups as $gk => $grp) {
                        $fm = false;
                        foreach ($grp['terms'] as $t) {
                            if (stripos($fuzzyQuery, $t) !== false || stripos($t, $fuzzyQuery) !== false) {
                                $fm = true;
                                break;
                            }
                        }
                        if ($fm) {
                            $intent['synonyms']               = array_unique(array_merge($intent['synonyms'], $grp['terms']));
                            $intent['target_functional_areas'] = array_unique(array_merge($intent['target_functional_areas'], $grp['functional_areas']));
                            $intent['target_skills']           = array_unique(array_merge($intent['target_skills'], $grp['skills']));
                        }
                    }
                }
            }
        }

        // 6. Detect Location Intent (Country, State, City, Area)
        $searchTermsToCheck = [];
        if (preg_match('/\b(in|near|at|around)\s+([a-zA-Z\s]+)/i', $normalized, $locMatches)) {
            $extractedLoc = trim($locMatches[2]);
            $cleanedLoc = preg_replace('/\b(5|10|20|50|km|kms|wfh|remote|part time|full time|jobs|job|work)\b/i', '', $extractedLoc);
            $cleanedLoc = trim($cleanedLoc);
            if (!empty($cleanedLoc)) {
                $intent['location'] = $cleanedLoc;
                $searchTermsToCheck[] = $cleanedLoc;
            }
        }

        // Only check standalone terms for location IF no role/synonym was matched
        if (empty($intent['synonyms']) && empty($intent['target_functional_areas'])) {
            $searchTermsToCheck[] = $raw;
            $searchTermsToCheck[] = $normalized;
            $wordsList = explode(' ', $normalized);
            foreach ($wordsList as $w) {
                $w = trim($w);
                if (strlen($w) >= 3 && !in_array($w, ['jobs', 'job', 'work', 'from', 'home', 'with', 'full', 'part', 'time'])) {
                    $searchTermsToCheck[] = $w;
                }
            }
        }

        $detectedLocality = null;
        $locationName = null;
        foreach (self::$localityToCityMap as $locality => $mappedCity) {
            if (strpos($normalized, $locality) !== false) {
                $detectedLocality = ucwords($locality);
                $searchTermsToCheck[] = strtolower($mappedCity);
                $locationName = ucwords($locality) . ' (' . $mappedCity . ')';
                break;
            }
        }
        $intent['detected_locality'] = $detectedLocality;

        $detectedCityIds = [];
        $detectedStateIds = [];
        $detectedCountryIds = [];

        foreach (array_unique($searchTermsToCheck) as $term) {
            if (empty($term) || strlen($term) < 2) continue;

            // Exact or exact-prefix City check
            $cityMatches = City::where('city', $term)->orWhere('city', 'like', $term)->pluck('city_id')->toArray();
            if (!empty($cityMatches)) {
                $detectedCityIds = array_unique(array_merge($detectedCityIds, $cityMatches));
                if (!$locationName) $locationName = ucfirst($term);
            }

            // Check State
            $stateMatches = State::where('state', $term)->orWhere('state', 'like', $term)->pluck('state_id')->toArray();
            if (!empty($stateMatches)) {
                $detectedStateIds = array_unique(array_merge($detectedStateIds, $stateMatches));
                if (!$locationName) $locationName = ucfirst($term);
            }

            // Check Country
            $countryMatches = Country::where('country', $term)->orWhere('country', 'like', $term)->pluck('country_id')->toArray();
            if (!empty($countryMatches)) {
                $detectedCountryIds = array_unique(array_merge($detectedCountryIds, $countryMatches));
                if (!$locationName) $locationName = ucfirst($term);
            }
        }

        $intent['detected_city_ids'] = $detectedCityIds;
        $intent['detected_state_ids'] = $detectedStateIds;
        $intent['detected_country_ids'] = $detectedCountryIds;
        $intent['location_name'] = $locationName;

        // Check if Location-Only search (e.g. user typed purely "Nagpur", "Maharashtra", "India")
        $hasRoleOrSynonym = (!empty($intent['synonyms']) || !empty($intent['target_skills']) || !empty($intent['target_functional_areas']) || !empty($cleanQuery));
        $hasExplicitRole = (!empty($intent['synonyms']) || !empty($intent['target_skills']) || !empty($intent['target_functional_areas']));
        
        $intent['is_location_only'] = (!empty($locationName) && !$hasExplicitRole && empty($intent['work_mode']) && empty($intent['job_type']));


        return $intent;
    }

    /**
     * Calculate Geographic Distance in KM (Haversine Formula)
     */
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        if (empty($lat1) || empty($lon1) || empty($lat2) || empty($lon2)) {
            return null;
        }

        $earthRadius = 6371; // Earth radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return round($distance, 1);
    }

    /**
     * Get Coordinates for a City Name or City Model
     */
    public static function getCityCoordinates($cityNameOrId)
    {
        if (empty($cityNameOrId)) {
            return null;
        }

        $cityName = '';
        if (is_numeric($cityNameOrId)) {
            $cityObj = City::getCityById((int)$cityNameOrId);
            if ($cityObj) {
                $cityName = strtolower(trim($cityObj->city));
            }
        } else {
            $cityName = strtolower(trim($cityNameOrId));
        }

        if (empty($cityName)) {
            return null;
        }

        // Clean city name (e.g. "Nagpur, Maharashtra" -> "nagpur")
        $parts = explode(',', $cityName);
        $cleanCity = trim($parts[0]);

        if (isset(self::$cityCoordinates[$cleanCity])) {
            return self::$cityCoordinates[$cleanCity];
        }

        // Approximate match
        foreach (self::$cityCoordinates as $cName => $coords) {
            if (strpos($cleanCity, $cName) !== false || strpos($cName, $cleanCity) !== false) {
                return $coords;
            }
        }

        // Fallback default: Nagpur reference coordinates
        return ['lat' => 21.1458, 'lon' => 79.0882];
    }

    /**
     * Core Relevance & Recommendation Scoring Engine (0-100)
     */
    public static function calculateJobRelevance($job, $intent, $user = null, $userCoords = null)
    {
        $score = 0;
        $reasons = [];
        $hasQuery = !empty($intent['normalized_query']);
        $jobTitle = strtolower($job->title ?? '');
        $jobDesc = strtolower(strip_tags($job->description ?? ''));
        $jobSkills = $job->jobSkills ? $job->jobSkills->map(function($js) {
            return strtolower($js->getJobSkill('job_skill') ?: '');
        })->filter()->toArray() : [];

        $jobCoords = self::getCityCoordinates($job->city_id);
        $distanceKm = null;
        if ($userCoords && $jobCoords) {
            $distanceKm = self::calculateDistance($userCoords['lat'], $userCoords['lon'], $jobCoords['lat'], $jobCoords['lon']);
        }

        // If specific distance limit was requested (e.g., "within 5 km")
        if (!empty($intent['max_distance_km']) && $distanceKm !== null) {
            if ($distanceKm > $intent['max_distance_km']) {
                return [
                    'score' => 0,
                    'tier' => 4,
                    'is_excluded' => true,
                    'distance_km' => $distanceKm,
                    'match_percent' => 0,
                    'reasons' => []
                ];
            }
        }

        // ==========================================
        // 1. EXPLICIT QUERY INTENT MATCHING
        // ==========================================
        if ($hasQuery) {
            $cleanQuery = $intent['clean_query'] ?? '';
            $titleMatched = false;
            $faMatched = false;
            $skillMatched = false;
            $locationMatched = false;

            // A. Exact & Partial Title Match
            if (!empty($cleanQuery)) {
                if ($jobTitle === $cleanQuery) {
                    $score += 45;
                    $titleMatched = true;
                    $reasons[] = 'Exact role match for "' . $intent['raw_query'] . '"';
                } elseif (strpos($jobTitle, $cleanQuery) !== false) {
                    $score += 35;
                    $titleMatched = true;
                    $reasons[] = 'Matches job title "' . $job->title . '"';
                } else {
                    // Check synonyms in title
                    foreach ($intent['synonyms'] as $syn) {
                        if (strpos($jobTitle, $syn) !== false) {
                            $score += 30;
                            $titleMatched = true;
                            $reasons[] = 'Related title match (' . ucfirst($syn) . ')';
                            break;
                        }
                    }
                }

                // Check individual keywords in title
                if (!$titleMatched && !empty($intent['keywords'])) {
                    $kwMatches = 0;
                    foreach ($intent['keywords'] as $kw) {
                        if (strpos($jobTitle, $kw) !== false) {
                            $kwMatches++;
                        }
                    }
                    if ($kwMatches > 0) {
                        $score += min(25, $kwMatches * 15);
                        $titleMatched = true;
                    }
                }
            }

            // ── Fuzzy-corrected title match (handles typos in search) ─────────
            if (!$titleMatched && !empty($intent['fuzzy_query'])) {
                $fuzzyQ = strtolower($intent['fuzzy_query']);
                if (strpos($jobTitle, $fuzzyQ) !== false) {
                    $score += 28;
                    $titleMatched = true;
                    $reasons[] = 'Matches "' . $job->title . '" (smart search)';
                } else {
                    // Check fuzzy synonyms against title
                    foreach ($intent['synonyms'] as $syn) {
                        if (strpos($jobTitle, strtolower($syn)) !== false) {
                            $score += 26;
                            $titleMatched = true;
                            $reasons[] = 'Matches related title (smart search)';
                            break;
                        }
                    }
                }
            }

            // B. Functional Area Match
            if (!empty($intent['target_functional_areas'])) {
                if (in_array((int)$job->functional_area_id, $intent['target_functional_areas'])) {
                    $score += 25;
                    $faMatched = true;
                    $reasons[] = 'Matches category ' . ($job->getFunctionalArea('functional_area') ?: '');
                }
            }

            // C. Skill Match
            if (!empty($intent['target_skills']) && count($jobSkills)) {
                $matchedSkills = array_intersect($jobSkills, $intent['target_skills']);
                if (count($matchedSkills) > 0) {
                    $skillRatio = count($matchedSkills) / max(1, count($intent['target_skills']));
                    $score += min(20, round($skillRatio * 20));
                    $skillMatched = true;
                    $reasons[] = 'Matches required skills (' . implode(', ', array_slice($matchedSkills, 0, 2)) . ')';
                }
            }

            // D. Description Intent (Lower weight than title)
            if (!empty($cleanQuery) && strpos($jobDesc, $cleanQuery) !== false) {
                $score += 8;
            }

            // E. Work Mode Intent Match (WFH / Remote)
            if (!empty($intent['work_mode'])) {
                $isJobRemote = (bool)$job->is_freelance || (int)$job->job_shift_id == 3 || stripos($jobTitle, 'remote') !== false || stripos($jobTitle, 'wfh') !== false || stripos($jobDesc, 'work from home') !== false;
                if ($intent['work_mode'] === 'remote') {
                    if ($isJobRemote) {
                        $score += 25;
                        $reasons[] = '✓ Work From Home / Remote eligible';
                    } else {
                        $score -= 20; // Penalize non-remote when user explicitly asked for remote
                    }
                }
            }

            // F. Job Type Intent Match (Part time / Internship)
            if (!empty($intent['job_type'])) {
                if ($intent['job_type'] === 'part_time' && ((int)$job->job_type_id == 2 || stripos($jobTitle, 'part time') !== false)) {
                    $score += 15;
                    $reasons[] = '✓ Part Time role';
                } elseif ($intent['job_type'] === 'internship' && ((int)$job->job_type_id == 3 || stripos($jobTitle, 'intern') !== false)) {
                    $score += 20;
                    $reasons[] = '✓ Internship opening';
                }
            }

            // G. Experience Intent Match (Fresher)
            if (!empty($intent['experience_level'])) {
                if ($intent['experience_level'] === 'fresher' && ((int)$job->job_experience_id == 1 || (int)$job->career_level_id == 1 || stripos($jobTitle, 'fresher') !== false)) {
                    $score += 15;
                    $reasons[] = '✓ Suitable for Freshers';
                }
            }

            // H. Location Intent Match (Country / State / City)
            if (!empty($intent['detected_city_ids']) && in_array((int)$job->city_id, $intent['detected_city_ids'])) {
                $score += 30;
                $locationMatched = true;
                $reasons[] = '✓ Located in ' . ($job->getCity('city') ?: $intent['location_name']);
            } elseif (!empty($intent['detected_state_ids']) && in_array((int)$job->state_id, $intent['detected_state_ids'])) {
                $score += 25;
                $locationMatched = true;
                $reasons[] = '✓ Located in ' . ($job->getState('state') ?: $intent['location_name']);
            } elseif (!empty($intent['detected_country_ids']) && in_array((int)$job->country_id, $intent['detected_country_ids'])) {
                $score += 20;
                $locationMatched = true;
                $reasons[] = '✓ Located in ' . ($job->getCountry('country') ?: $intent['location_name']);
            } elseif (!empty($intent['location'])) {
                $jobLocation = strtolower($job->getLocation() ?: '');
                if (stripos($jobLocation, $intent['location']) !== false) {
                    $score += 20;
                    $locationMatched = true;
                    $reasons[] = '✓ Located in ' . ucfirst($intent['location']);
                }
            }

            // H.2 Exact Locality / Area Intent Match (e.g. Hinjewadi, Dharampeth, Whitefield, Andheri)
            if (!empty($intent['detected_locality'])) {
                $locLower = strtolower($intent['detected_locality']);
                if (stripos($jobTitle, $locLower) !== false || stripos($jobDesc, $locLower) !== false || stripos((string)$job->location, $locLower) !== false) {
                    $score += 25;
                    $reasons[] = '✓ Locality Match: ' . $intent['detected_locality'];
                }
            }

            // ==========================================
            // STRICT RELEVANCE GATE (CORE RULE #1 & #7)
            // ==========================================
            if ($intent['is_location_only']) {
                // If user searched purely a location (e.g. "Nagpur", "Maharashtra", "India"):
                if ($locationMatched) {
                    $score += 45; // Base relevance boost for being in the target location
                } else {
                    return [
                        'score' => 0,
                        'tier' => 4,
                        'is_excluded' => true,
                        'distance_km' => $distanceKm,
                        'match_percent' => 0,
                        'reasons' => []
                    ];
                }
            } else {
                // User searched a specific role/skill/query:
                // If neither Title, nor FA, nor Skill matched, and no WFH was requested, exclude!
                if (!$titleMatched && !$faMatched && !$skillMatched && empty($intent['work_mode'])) {
                    return [
                        'score' => 0,
                        'tier' => 4,
                        'is_excluded' => true,
                        'distance_km' => $distanceKm,
                        'match_percent' => 0,
                        'reasons' => []
                    ];
                }
            }

        } else {
            // ==========================================
            // 2. NO-QUERY PERSONALIZED RECOMMENDATION
            // ==========================================
            // Base starting score for browsing
            $score += 20;
        }

        // ==========================================
        // 3. CANDIDATE PROFILE PERSONALIZATION
        // ==========================================
        if ($user) {
            $userSkills = $user->profileSkills ? $user->profileSkills->map(function($ps) {
                return strtolower($ps->getJobSkill('job_skill') ?: '');
            })->filter()->toArray() : [];

            // A. Candidate Profile Skills Overlap
            if (count($userSkills) && count($jobSkills)) {
                $common = array_intersect($userSkills, $jobSkills);
                if (count($common) > 0) {
                    $skillMatchPercent = round((count($common) / max(1, count($jobSkills))) * 100);
                    $score += min(20, round(($skillMatchPercent / 100) * 20));
                    $reasons[] = 'Matches your ' . implode(', ', array_slice($common, 0, 2)) . ' skills (' . $skillMatchPercent . '% match)';
                }
            }

            // B. Functional Area Match with Profile
            if ((int)$user->functional_area_id > 0 && (int)$user->functional_area_id === (int)$job->functional_area_id) {
                $score += 15;
                $reasons[] = 'Matches your preferred category ' . ($job->getFunctionalArea('functional_area') ?: '');
            }

            // C. Experience Match
            if ((int)$user->job_experience_id > 0 && (int)$user->job_experience_id >= (int)$job->job_experience_id) {
                $score += 10;
                $reasons[] = 'Matches your experience level';
            }

            // D. Education Match
            if ((int)$user->degree_level_id > 0 && (int)$user->degree_level_id >= (int)$job->degree_level_id) {
                $score += 8;
            }

            // E. Deduct duplicate applied jobs
            if ($user->isAppliedOnJob($job->id)) {
                $score -= 15; // De-prioritize already applied jobs
            }
        }

        // ==========================================
        // 4. DISTANCE / GEOLOCATION SCORING
        // ==========================================
        if ($distanceKm !== null) {
            if ($distanceKm <= 5) {
                $score += 20;
                $reasons[] = 'Within 5 km from you (' . $distanceKm . ' km)';
            } elseif ($distanceKm <= 10) {
                $score += 15;
                $reasons[] = $distanceKm . ' km away from your location';
            } elseif ($distanceKm <= 20) {
                $score += 10;
                $reasons[] = $distanceKm . ' km from your location';
            } elseif ($distanceKm <= 40) {
                $score += 5;
            } else {
                $score += 2;
            }
        }

        // ==========================================
        // 5. FRESHNESS DECAY
        // ==========================================
        if ($job->created_at) {
            $hoursOld = Carbon::now()->diffInHours($job->created_at);
            if ($hoursOld <= 24) {
                $score += 6; // Very fresh (<24 hours)
            } elseif ($hoursOld <= 168) {
                $score += 4; // Fresh (<7 days)
            } elseif ($hoursOld <= 720) {
                $score += 2; // (<30 days)
            }
        }

        // Normalize final score to 0–100 range
        $normalizedScore = min(100, max(0, (int)round($score)));

        // Determine Relevance Tier
        $tier = 4;
        if ($normalizedScore >= 80) {
            $tier = 1; // Highly Relevant
        } elseif ($normalizedScore >= 60) {
            $tier = 2; // Relevant
        } elseif ($normalizedScore >= 25) {
            $tier = 3; // Related — lowered from 40 so title-matched jobs (score ~35) are not excluded
        } else {
            $tier = 4; // Irrelevant
        }

        // If user searched explicitly and final score is below Tier 3 threshold, exclude!
        $isExcluded = ($hasQuery && $tier === 4);

        return [
            'score' => $normalizedScore,
            'tier' => $tier,
            'is_excluded' => $isExcluded,
            'distance_km' => $distanceKm,
            'match_percent' => $normalizedScore,
            'reasons' => array_values(array_unique($reasons))
        ];
    }

    /**
     * Primary Recommendation & Ranked Search Entrypoint
     */
    public static function searchAndRankJobs($search = '', $filters = [], $user = null, $userCoords = null, $limit = 10)
    {
        $intent = self::extractSearchIntent($search);

        // 1. Resolve User Location Coordinates
        if (!$userCoords) {
            if (!empty($intent['location_name'])) {
                $userCoords = self::getCityCoordinates($intent['location_name']);
            } elseif ($user && !empty($user->city_id)) {
                $userCoords = self::getCityCoordinates($user->city_id);
            } elseif (!empty($filters['city_id'][0])) {
                $userCoords = self::getCityCoordinates($filters['city_id'][0]);
            } else {
                $userCoords = self::getCityCoordinates('nagpur'); // Default reference
            }
        }

        // 2. Fetch all candidate active jobs from DB
        $query = Job::with(['jobSkills.jobSkill', 'company', 'city', 'state', 'country', 'functionalArea', 'jobType', 'jobShift', 'jobExperience'])
            ->where('is_active', 1)
            ->notExpire();

        // Apply Hard SQL Filters if explicitly selected in sidebar
        if (!empty($filters['country_id'][0])) {
            $query->whereIn('country_id', (array)$filters['country_id']);
        }
        if (!empty($filters['state_id'][0])) {
            $query->whereIn('state_id', (array)$filters['state_id']);
        }
        if (!empty($filters['city_id'][0])) {
            $query->whereIn('city_id', (array)$filters['city_id']);
        }
        if (!empty($filters['job_type_id'][0])) {
            $query->whereIn('job_type_id', (array)$filters['job_type_id']);
        }
        if (!empty($filters['job_shift_id'][0])) {
            $query->whereIn('job_shift_id', (array)$filters['job_shift_id']);
        }
        if (!empty($filters['career_level_id'][0])) {
            $query->whereIn('career_level_id', (array)$filters['career_level_id']);
        }
        if (!empty($filters['job_experience_id'][0])) {
            $query->whereIn('job_experience_id', (array)$filters['job_experience_id']);
        }
        if (!empty($filters['functional_area_id'][0])) {
            $query->whereIn('functional_area_id', (array)$filters['functional_area_id']);
        }
        if (!empty($filters['salary_from']) && (int)$filters['salary_from'] > 0) {
            $query->where('salary_from', '>=', (int)$filters['salary_from']);
        }
        if (!empty($filters['salary_to']) && (int)$filters['salary_to'] > 0) {
            $query->where('salary_to', '<=', (int)$filters['salary_to']);
        }

        $allCandidateJobs = $query->get();

        // 3. Score, Rank, and Filter Jobs
        $rankedJobs = [];
        foreach ($allCandidateJobs as $job) {
            $relevance = self::calculateJobRelevance($job, $intent, $user, $userCoords);

            // Filter out Tier 4 (Irrelevant)
            if ($relevance['is_excluded']) {
                continue;
            }

            // Attach dynamic attributes to Job model for blade rendering
            $job->relevance_score = $relevance['score'];
            $job->relevance_tier = $relevance['tier'];
            $job->distance_km = $relevance['distance_km'];
            $job->match_percent = $relevance['match_percent'];
            $job->match_reasons = $relevance['reasons'];

            $rankedJobs[] = $job;
        }

        // 4. Sort by: Score (DESC) -> Distance (ASC) -> Freshness (DESC)
        usort($rankedJobs, function ($a, $b) {
            if ($a->relevance_score !== $b->relevance_score) {
                return $b->relevance_score <=> $a->relevance_score;
            }
            if ($a->distance_km !== null && $b->distance_km !== null && $a->distance_km !== $b->distance_km) {
                return $a->distance_km <=> $b->distance_km;
            }
            return $b->id <=> $a->id;
        });

        // 5. Paginate Ranked Collection
        $page = (int)request()->get('page', 1);
        $total = count($rankedJobs);
        $offset = ($page - 1) * $limit;
        $items = array_slice($rankedJobs, $offset, $limit);

        $paginator = new LengthAwarePaginator(
            $items,
            $total,
            $limit,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query()
            ]
        );

        return [
            'paginator' => $paginator,
            'intent' => $intent,
            'total_count' => $total,
            'ranked_jobs' => $rankedJobs
        ];
    }
}
