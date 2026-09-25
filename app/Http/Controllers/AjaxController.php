<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Input;
use Form;
use App\Helpers\MiscHelper;
use App\Helpers\DataArrayHelper;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Traits\CountryStateCity;

class AjaxController extends Controller
{

    use CountryStateCity;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function filterDefaultStates(Request $request)
    {
        $country_id = $request->input('country_id');
        $state_id = $request->input('state_id');
        $new_state_id = $request->input('new_state_id', 'state_id');
        $states = DataArrayHelper::defaultStatesArray($country_id);
        $dd = Form::select('state_id', ['' => __('Select State')] + $states, $state_id, array('id' => $new_state_id, 'class' => 'form-control'));
        echo $dd;
    }

    public function filterDefaultCities(Request $request)
    {
        $state_id = $request->input('state_id');
        $city_id = $request->input('city_id');
        $cities = DataArrayHelper::defaultCitiesArray($state_id);
        $dd = Form::select('city_id', ['' => 'Select City'] + $cities, $city_id, array('id' => 'city_id', 'class' => 'form-control'));
        echo $dd;
    }

    /*     * ***************************************** */

    public function filterLangStates(Request $request)
    {
        $country_id = $request->input('country_id');
        $state_id = $request->input('state_id');
        $new_state_id = $request->input('new_state_id', 'state_id');
        $states = DataArrayHelper::langStatesArray($country_id);
        $dd = Form::select('state_id', ['' => __('Select State')] + $states, $state_id, array('id' => $new_state_id, 'class' => 'form-control modern-form-control'));
        echo $dd;
    }

    public function filterLangCities(Request $request)
    {
        $state_id = $request->input('state_id');
        $city_id = $request->input('city_id');
        $cities = DataArrayHelper::langCitiesArray($state_id);

        $dd = Form::select('city_id', ['' => __('Select City')] + $cities, $city_id, array('id' => 'city_id', 'class' => 'form-control modern-form-control'));
        echo $dd;
    }

    /*     * ***************************************** */

    public function filterStates(Request $request)
    {
        $country_id = $request->input('country_id');
        $state_id = $request->input('state_id');
        $new_state_id = $request->input('new_state_id', 'state_id');
        $states = DataArrayHelper::langStatesArray($country_id);
        $dd = Form::select('state_id[]', ['' => __('Select State')] + $states, $state_id, array('id' => $new_state_id, 'class' => 'form-control'));
        echo $dd;
    }

    public function filterCities(Request $request)
    {
        $state_id = $request->input('state_id');
        $city_id = $request->input('city_id');
        $cities = DataArrayHelper::langCitiesArray($state_id);

        $dd = Form::select('city_id[]', ['' => 'Select City'] + $cities, $city_id, array('id' => 'city_id', 'class' => 'form-control'));
        echo $dd;
    }

    /*     * ***************************************** */

    public function filterDegreeTypes(Request $request)
    {
        $degree_level_id = $request->input('degree_level_id');
        $degree_type_id = $request->input('degree_type_id');

        $degreeTypes = DataArrayHelper::langDegreeTypesArray($degree_level_id);
        $dd = Form::select('degree_type_id', ['' => __('Select an option')] + $degreeTypes, $degree_type_id, array('id' => 'degree_type_id', 'class' => 'form-control modern-input'));
        echo $dd;
    }

    /*     * ***************************************** */

    public function searchColleges(Request $request)
    {
        $query = trim($request->input('q', ''));
        if ($query === '') {
            $colleges = DB::table('colleges')->orderBy('name', 'asc')->limit(15)->pluck('name')->toArray();
        } else {
            $colleges = DB::table('colleges')
                ->where('name', 'LIKE', '%' . $query . '%')
                ->orderBy('name', 'asc')
                ->limit(25)
                ->pluck('name')
                ->toArray();

            $userAdded = DB::table('profile_educations')
                ->where('institution', 'LIKE', '%' . $query . '%')
                ->distinct()
                ->limit(10)
                ->pluck('institution')
                ->toArray();

            $colleges = array_values(array_unique(array_merge($colleges, $userAdded)));
        }

        return response()->json($colleges);
    }

    /*     * ***************************************** */

    public function getSkillsByDepartment(Request $request)
    {
        $functional_area_id = $request->input('functional_area_id');
        $query_str = trim($request->input('q', ''));

        $skillsQuery = \App\JobSkill::select('job_skills.job_skill_id as id', 'job_skills.job_skill as name')
            ->lang()
            ->active()
            ->sorted();

        if (!empty($functional_area_id)) {
            $skillsQuery->where('job_skills.functional_area_id', $functional_area_id);
        }

        if (!empty($query_str)) {
            $skillsQuery->where('job_skills.job_skill', 'LIKE', '%' . $query_str . '%');
        }

        $skills = $skillsQuery->get();

        if ($skills->isEmpty() && !empty($functional_area_id)) {
            // Fallback to default language skills
            $skillsQuery = \App\JobSkill::select('job_skills.job_skill_id as id', 'job_skills.job_skill as name')
                ->isDefault()
                ->active()
                ->where('job_skills.functional_area_id', $functional_area_id)
                ->sorted();

            if (!empty($query_str)) {
                $skillsQuery->where('job_skills.job_skill', 'LIKE', '%' . $query_str . '%');
            }
            $skills = $skillsQuery->get();
        }

        return response()->json([
            'success' => true,
            'functional_area_id' => $functional_area_id,
            'skills' => $skills
        ]);
    }

    public function filterSkillsDropdown(Request $request)
    {
        $functional_area_id = $request->input('functional_area_id');
        $selected_skills = (array) $request->input('selected_skills', []);
        
        $skills = DataArrayHelper::langJobSkillsArray($functional_area_id);
        $options = '';
        foreach ($skills as $id => $name) {
            $selected = in_array($id, $selected_skills) ? 'selected="selected"' : '';
            $options .= '<option value="' . $id . '" ' . $selected . '>' . e($name) . '</option>';
        }
        return response()->json(['options' => $options, 'skills' => $skills]);
    }

    public function addCustomSkill(Request $request)
    {
        $skillName = trim($request->input('skill_name', ''));
        $functionalAreaId = $request->input('functional_area_id');

        if (empty($skillName)) {
            return response()->json(['success' => false, 'message' => 'Skill name cannot be empty.'], 422);
        }

        // Check if skill already exists in this department or globally
        $existing = \App\JobSkill::where('job_skill', 'LIKE', $skillName)
            ->where(function($q) use ($functionalAreaId) {
                if (!empty($functionalAreaId)) {
                    $q->where('functional_area_id', $functionalAreaId);
                }
            })
            ->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'skill' => [
                    'id' => $existing->job_skill_id ?: $existing->id,
                    'name' => $existing->job_skill,
                    'functional_area_id' => $existing->functional_area_id
                ],
                'is_new' => false
            ]);
        }

        // Create new skill entry
        $maxId = (int) \App\JobSkill::max('job_skill_id') + 1;
        $jobSkill = new \App\JobSkill();
        $jobSkill->job_skill_id = $maxId;
        $jobSkill->functional_area_id = $functionalAreaId ?: null;
        $jobSkill->job_skill = $skillName;
        $jobSkill->is_default = 1;
        $jobSkill->is_active = 1;
        $jobSkill->sort_order = $maxId;
        $jobSkill->lang = 'en';
        $jobSkill->save();

        return response()->json([
            'success' => true,
            'skill' => [
                'id' => $jobSkill->job_skill_id,
                'name' => $jobSkill->job_skill,
                'functional_area_id' => $jobSkill->functional_area_id
            ],
            'is_new' => true
        ]);
    }

    public function trackHrContact(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $applicationId = (int) $request->input('application_id');
        $contactType = $request->input('contact_type'); // 'phone' or 'whatsapp'

        $application = \App\JobApply::where('id', $applicationId)->where('user_id', $user->id)->first();
        if (!$application) {
            return response()->json(['success' => false, 'message' => 'Application not found'], 404);
        }

        $job = $application->getJob();
        $company = $job ? $job->getCompany() : null;

        if (!$company) {
            return response()->json(['success' => false, 'message' => 'Company not found'], 404);
        }

        // Log contact activity
        \DB::table('application_contact_activities')->insert([
            'application_id' => $application->id,
            'job_id' => $job->id,
            'candidate_id' => $user->id,
            'company_id' => $company->id,
            'contact_type' => in_array($contactType, ['phone', 'whatsapp']) ? $contactType : 'phone',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'contact_type' => $contactType
        ]);
    }

    public function reportJobAbuseAjax(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Please login to report.'], 401);
        }

        $user = Auth::user();
        $jobId = (int) $request->input('job_id');
        $reason = $request->input('reason', 'Other');
        $details = $request->input('details', '');

        $job = \App\Job::find($jobId);
        if (!$job) {
            return response()->json(['success' => false, 'message' => 'Job not found.'], 404);
        }

        \App\ReportAbuseMessage::create([
            'your_name' => $user->getName() . ' [Reason: ' . $reason . ' - ' . $details . ']',
            'your_email' => $user->email,
            'job_url' => route('job.detail', [$job->slug]),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you. Your report has been submitted to the moderation team.'
        ]);
    }

    public function searchLocations(Request $request)
    {
        $q = trim($request->input('q', ''));
        $lang = \App::getLocale() ?: 'en';
        $country_id = $request->input('country_id');
        $state_id = $request->input('state_id');
        $results = [];

        // Common Indian city / state abbreviations mapping
        $aliases = [
            'NGP' => 'Nagpur',
            'NAG' => 'Nagpur',
            'BOM' => 'Mumbai',
            'DEL' => 'Delhi',
            'BLR' => 'Bangalore',
            'BLRU' => 'Bangalore',
            'PNQ' => 'Pune',
            'HYD' => 'Hyderabad',
            'CCU' => 'Kolkata',
            'MAA' => 'Chennai',
            'AMD' => 'Ahmedabad',
            'IDR' => 'Indore',
            'JAI' => 'Jaipur',
            'LKO' => 'Lucknow',
            'MH'  => 'Maharashtra',
            'MP'  => 'Madhya Pradesh',
            'UP'  => 'Uttar Pradesh',
            'KA'  => 'Karnataka',
            'TN'  => 'Tamil Nadu',
            'DL'  => 'Delhi',
            'GJ'  => 'Gujarat',
        ];

        $type = $request->input('type');
        $upperQ = strtoupper($q);
        if (isset($aliases[$upperQ])) {
            $q = $aliases[$upperQ];
        }

        // Dedicated search for Country dropdown
        if ($type === 'country') {
            $countriesQuery = \App\Country::where('lang', $lang);
            if (!empty($q)) {
                $countriesQuery->where('country', 'like', "%{$q}%")
                    ->orderByRaw("CASE WHEN country = ? THEN 1 WHEN country LIKE ? THEN 2 ELSE 3 END", [$q, "{$q}%"])
                    ->orderBy('country', 'asc');
            } else {
                $countriesQuery->orderByRaw("CASE WHEN country_id = 101 THEN 1 ELSE 2 END")->orderBy('country', 'asc');
            }
            $countries = $countriesQuery->limit(15)->get();
            foreach ($countries as $c) {
                $results[] = [
                    'id' => $c->country_id,
                    'name' => $c->country,
                    'display' => $c->country,
                ];
            }
            return response()->json($results);
        }

        // Dedicated search for State dropdown
        if ($type === 'state') {
            $statesQuery = \App\State::where('lang', $lang);
            if (!empty($country_id)) {
                $statesQuery->where('country_id', $country_id);
            } else {
                $statesQuery->where('country_id', 101);
            }
            if (!empty($q)) {
                $statesQuery->where('state', 'like', "%{$q}%")
                    ->orderByRaw("CASE WHEN state = ? THEN 1 WHEN state LIKE ? THEN 2 ELSE 3 END", [$q, "{$q}%"])
                    ->orderBy('state', 'asc');
            } else {
                $statesQuery->orderByRaw("FIELD(state, 'Maharashtra', 'Delhi', 'Karnataka', 'Tamil Nadu', 'Uttar Pradesh', 'Gujarat') DESC")
                    ->orderBy('state', 'asc');
            }
            $states = $statesQuery->limit(15)->get();
            foreach ($states as $s) {
                $results[] = [
                    'id' => $s->state_id,
                    'name' => $s->state,
                    'display' => $s->state,
                    'country_id' => $s->country_id,
                ];
            }
            return response()->json($results);
        }

        // Dedicated search for City dropdown
        if ($type === 'city') {
            $citiesQuery = \App\City::where('lang', $lang);
            if (!empty($state_id)) {
                $citiesQuery->where('state_id', $state_id);
                if (!empty($q)) {
                    $citiesQuery->where('city', 'like', "%{$q}%")
                        ->orderByRaw("CASE WHEN city = ? THEN 1 WHEN city LIKE ? THEN 2 ELSE 3 END", [$q, "{$q}%"])
                        ->orderBy('city', 'asc');
                } else {
                    if ($state_id == 22) {
                        $citiesQuery->orderByRaw("FIELD(city, 'Nagpur', 'Mumbai', 'Pune', 'Nashik', 'Thane', 'Aurangabad') DESC")
                            ->orderBy('city', 'asc');
                    } else {
                        $citiesQuery->orderBy('city', 'asc');
                    }
                }
            } else {
                if (!empty($country_id)) {
                    $stateIdsInCountry = \App\State::where('country_id', $country_id)->where('lang', $lang)->pluck('state_id')->toArray();
                    $citiesQuery->whereIn('state_id', $stateIdsInCountry);
                }
                if (!empty($q)) {
                    $citiesQuery->where('city', 'like', "%{$q}%")
                        ->orderByRaw("CASE WHEN city = ? THEN 1 WHEN city LIKE ? THEN 2 ELSE 3 END", [$q, "{$q}%"])
                        ->orderBy('city', 'asc');
                } else {
                    $citiesQuery->orderByRaw("FIELD(city, 'Nagpur', 'Mumbai', 'Pune', 'Bangalore', 'Delhi') DESC")
                        ->orderBy('city', 'asc');
                }
            }
            $cities = $citiesQuery->limit(15)->get();
            foreach ($cities as $c) {
                $st = \App\State::where('state_id', $c->state_id)->where('lang', $lang)->first();
                $results[] = [
                    'id' => $c->city_id,
                    'name' => $c->city,
                    'display' => $c->city . ($st ? ', ' . $st->state : ''),
                    'state_id' => $c->state_id,
                ];
            }
            return response()->json($results);
        }

        // Case 1: If user already selected a state, suggest cities in that state
        if (!empty($state_id)) {
            $state = \App\State::where('state_id', $state_id)->first();
            $country = $state ? \App\Country::where('country_id', $state->country_id)->first() : null;
            $countryName = $country ? $country->country : 'India';
            $stateName = $state ? $state->state : '';

            $citiesQuery = \App\City::where('state_id', $state_id)->where('lang', $lang);
            if (!empty($q)) {
                $citiesQuery->where('city', 'like', "%{$q}%")
                    ->orderByRaw("CASE WHEN city = ? THEN 1 WHEN city LIKE ? THEN 2 ELSE 3 END", [$q, "{$q}%"])
                    ->orderBy('city', 'asc');
            } else {
                if ($state_id == 22) {
                    $citiesQuery->orderByRaw("FIELD(city, 'Nagpur', 'Mumbai', 'Pune', 'Nashik', 'Thane', 'Aurangabad') DESC")
                        ->orderBy('city', 'asc');
                } else {
                    $citiesQuery->orderBy('city', 'asc');
                }
            }
            $cities = $citiesQuery->limit(12)->get();

            foreach ($cities as $city) {
                $results[] = [
                    'type' => 'city',
                    'country_id' => $state ? $state->country_id : 101,
                    'state_id' => $city->state_id,
                    'city_id' => $city->city_id,
                    'name' => $city->city,
                    'display' => $city->city . ', ' . $stateName . ', ' . $countryName,
                    'subtitle' => 'City in ' . $stateName,
                    'badge' => 'City',
                ];
            }
            return response()->json($results);
        }

        // Case 2: If user selected a country, suggest states in that country
        if (!empty($country_id)) {
            $country = \App\Country::where('country_id', $country_id)->where('lang', $lang)->first();
            $countryName = $country ? $country->country : 'India';

            $statesQuery = \App\State::where('country_id', $country_id)->where('lang', $lang);
            if (!empty($q)) {
                $statesQuery->where('state', 'like', "%{$q}%")
                    ->orderByRaw("CASE WHEN state = ? THEN 1 WHEN state LIKE ? THEN 2 ELSE 3 END", [$q, "{$q}%"])
                    ->orderBy('state', 'asc');
            } else {
                if ($country_id == 101) {
                    $statesQuery->orderByRaw("FIELD(state, 'Maharashtra', 'Delhi', 'Karnataka', 'Tamil Nadu', 'Uttar Pradesh', 'Gujarat') DESC")
                        ->orderBy('state', 'asc');
                } else {
                    $statesQuery->orderBy('state', 'asc');
                }
            }
            $states = $statesQuery->limit(10)->get();

            foreach ($states as $state) {
                $results[] = [
                    'type' => 'state',
                    'country_id' => $country_id,
                    'state_id' => $state->state_id,
                    'city_id' => null,
                    'name' => $state->state,
                    'display' => $state->state . ', ' . $countryName,
                    'subtitle' => 'State in ' . $countryName,
                    'badge' => 'State',
                ];
            }

            if (!empty($q)) {
                $stateIdsInCountry = \App\State::where('country_id', $country_id)->where('lang', $lang)->pluck('state_id')->toArray();
                $cities = \App\City::whereIn('state_id', $stateIdsInCountry)
                    ->where('lang', $lang)
                    ->where('city', 'like', "%{$q}%")
                    ->orderByRaw("CASE WHEN city = ? THEN 1 WHEN city LIKE ? THEN 2 ELSE 3 END", [$q, "{$q}%"])
                    ->orderBy('city', 'asc')
                    ->limit(8)
                    ->get();
                foreach ($cities as $city) {
                    $st = \App\State::where('state_id', $city->state_id)->where('lang', $lang)->first();
                    $stName = $st ? $st->state : '';
                    $results[] = [
                        'type' => 'city',
                        'country_id' => $country_id,
                        'state_id' => $city->state_id,
                        'city_id' => $city->city_id,
                        'name' => $city->city,
                        'display' => $city->city . ', ' . $stName . ', ' . $countryName,
                        'subtitle' => 'City in ' . $stName,
                        'badge' => 'City',
                    ];
                }
            }

            return response()->json($results);
        }

        // Case 3: General search (User types any query or focuses)
        if (empty($q)) {
            $results[] = [
                'type' => 'country',
                'country_id' => 101,
                'state_id' => null,
                'city_id' => null,
                'name' => 'India',
                'display' => 'India',
                'subtitle' => 'Country',
                'badge' => 'Country',
            ];
            $results[] = [
                'type' => 'state',
                'country_id' => 101,
                'state_id' => 22,
                'city_id' => null,
                'name' => 'Maharashtra',
                'display' => 'Maharashtra, India',
                'subtitle' => 'State in India',
                'badge' => 'State',
            ];
            
            $topCities = ['Nagpur', 'Mumbai', 'Pune', 'Bangalore', 'Delhi'];
            foreach ($topCities as $tc) {
                $c = \App\City::where('city', $tc)->where('lang', $lang)->first();
                if ($c) {
                    $st = \App\State::where('state_id', $c->state_id)->where('lang', $lang)->first();
                    $results[] = [
                        'type' => 'city',
                        'country_id' => 101,
                        'state_id' => $c->state_id,
                        'city_id' => $c->city_id,
                        'name' => $c->city,
                        'display' => $c->city . ', ' . ($st ? $st->state : '') . ', India',
                        'subtitle' => 'Popular City',
                        'badge' => 'City',
                    ];
                }
            }
            return response()->json($results);
        }

        // Search Countries matching query (exact / starts-with first)
        $countries = \App\Country::where('country', 'like', "%{$q}%")
            ->where('lang', $lang)
            ->orderByRaw("CASE WHEN country = ? THEN 1 WHEN country LIKE ? THEN 2 ELSE 3 END", [$q, "{$q}%"])
            ->orderBy('country', 'asc')
            ->limit(2)
            ->get();
        foreach ($countries as $country) {
            $results[] = [
                'type' => 'country',
                'country_id' => $country->country_id,
                'state_id' => null,
                'city_id' => null,
                'name' => $country->country,
                'display' => $country->country,
                'subtitle' => 'Country',
                'badge' => 'Country',
            ];
        }

        // Search States matching query (exact / starts-with first, India priority)
        $states = \App\State::where('state', 'like', "%{$q}%")
            ->where('lang', $lang)
            ->orderByRaw("CASE WHEN country_id = 101 THEN 1 ELSE 2 END")
            ->orderByRaw("CASE WHEN state = ? THEN 1 WHEN state LIKE ? THEN 2 ELSE 3 END", [$q, "{$q}%"])
            ->orderBy('state', 'asc')
            ->limit(5)
            ->get();
        foreach ($states as $state) {
            $co = \App\Country::where('country_id', $state->country_id)->where('lang', $lang)->first();
            $results[] = [
                'type' => 'state',
                'country_id' => $state->country_id,
                'state_id' => $state->state_id,
                'city_id' => null,
                'name' => $state->state,
                'display' => $state->state . ', ' . ($co ? $co->country : 'India'),
                'subtitle' => 'State in ' . ($co ? $co->country : 'India'),
                'badge' => 'State',
            ];
        }

        // Search Cities matching query (exact / starts-with first, India priority)
        $cities = \App\City::join('states', 'cities.state_id', '=', 'states.state_id')
            ->where('cities.city', 'like', "%{$q}%")
            ->where('cities.lang', $lang)
            ->where('states.lang', $lang)
            ->orderByRaw("CASE WHEN states.country_id = 101 THEN 1 ELSE 2 END")
            ->orderByRaw("CASE WHEN cities.city = ? THEN 1 WHEN cities.city LIKE ? THEN 2 ELSE 3 END", [$q, "{$q}%"])
            ->orderBy('cities.city', 'asc')
            ->select('cities.*', 'states.state as state_name', 'states.country_id as state_country_id')
            ->limit(10)
            ->get();
        foreach ($cities as $city) {
            $co = \App\Country::where('country_id', $city->state_country_id)->where('lang', $lang)->first();
            $results[] = [
                'type' => 'city',
                'country_id' => $city->state_country_id ?: 101,
                'state_id' => $city->state_id,
                'city_id' => $city->city_id,
                'name' => $city->city,
                'display' => $city->city . ', ' . $city->state_name . ', ' . ($co ? $co->country : 'India'),
                'subtitle' => 'City in ' . $city->state_name,
                'badge' => 'City',
            ];
        }

        return response()->json($results);
    }
}
