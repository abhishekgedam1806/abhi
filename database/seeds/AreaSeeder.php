<?php

use Illuminate\Database\Seeder;
use App\Area;
use App\City;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cityAreas = [
            // Pune (City ID: 2763)
            'Pune' => [
                'Hinjewadi Phase 1', 'Hinjewadi Phase 2', 'Hinjewadi Phase 3', 'Kothrud', 'Baner',
                'Wakad', 'Viman Nagar', 'Kharadi', 'Hadapsar', 'Magarpatta City', 'Shivajinagar',
                'Pimpri', 'Chinchwad', 'Bhosari', 'Yerwada', 'Aundh', 'Kalyani Nagar', 'Katraj',
                'Kondhwa', 'Bavdhan', 'Chakan MIDC', 'Talawade IT Park', 'Senapati Bapat Road',
                'Deccan Gymkhana', 'Swargate', 'Dighi', 'Ravet', 'Pimple Saudagar', 'Warje',
            ],
            // Nagpur (City ID: 2715)
            'Nagpur' => [
                'Dharampeth', 'Sadar', 'Sitabuldi', 'MIDC Hingna', 'Wardha Road', 'Ramdaspeth',
                'Shankar Nagar', 'Manish Nagar', 'Mahal', 'Itwari', 'Nandanvan', 'Butibori MIDC',
                'Trimurti Nagar', 'Khamla', 'Wadi', 'Civil Lines', 'Pratap Nagar', 'Dhantoli',
                'Laxmi Nagar', 'Gandhibagh', 'Jaripatka', 'Mihan SEZ', 'Besan', 'Pardi',
            ],
            // Mumbai (City ID: 2707)
            'Mumbai' => [
                'Andheri East', 'Andheri West', 'Bandra West', 'Bandra Kurla Complex (BKC)', 'Powai',
                'Goregaon East', 'Goregaon West', 'Borivali West', 'Borivali East', 'Thane West',
                'Navi Mumbai (Vashi)', 'Navi Mumbai (Airoli)', 'Navi Mumbai (Belapur)', 'Malad West',
                'Malad East', 'Lower Parel', 'Worli', 'Dadar West', 'Kurla West', 'Nariman Point',
                'Kandivali East', 'Kandivali West', 'Ghatkopar East', 'Ghatkopar West', 'Juhu',
                'Chembur', 'Mulund West', 'Kanjurmarg', 'Charni Road',
            ],
            // Bengaluru (City ID: 1558)
            'Bengaluru' => [
                'Whitefield', 'Electronic City Phase 1', 'Electronic City Phase 2', 'Koramangala',
                'Indiranagar', 'HSR Layout', 'Marathahalli', 'Bellandur', 'BTM Layout', 'JP Nagar',
                'Jayanagar', 'Hebbal', 'Manyata Tech Park', 'Yelahanka', 'Malleshwaram', 'Rajajinagar',
                'Bannerghatta Road', 'Sarjapur Road', 'MG Road', 'CV Raman Nagar', 'Domlur',
            ],
            // Delhi NCR (Delhi City ID: 706)
            'Delhi' => [
                'Noida Sector 62', 'Noida Sector 18', 'Noida Sector 63', 'Greater Noida', 'Gurgaon Cyber City',
                'Gurgaon Sector 29', 'Gurgaon Golf Course Road', 'Gurgaon Sohna Road', 'Connaught Place',
                'Okhla Industrial Area', 'Saket', 'Nehru Place', 'Dwarka', 'Karol Bagh', 'Laxmi Nagar',
                'Ghaziabad', 'Faridabad', 'Jasola', 'Janakpuri', 'Pitampura', 'Netaji Subhash Place',
            ],
            // Hyderabad (City ID: 4460)
            'Hyderabad' => [
                'HITEC City', 'Gachibowli', 'Madhapur', 'Kondapur', 'Kukatpally', 'Secunderabad',
                'Banjara Hills', 'Jubilee Hills', 'Begumpet', 'Ameerpet', 'Somajiguda', 'Manikonda',
                'Financial District', 'Uppal', 'Dilsukhnagar',
            ],
            // Ahmedabad (City ID: 1045)
            'Ahmedabad' => [
                'SG Highway', 'Prahlad Nagar', 'Vastrapur', 'Navrangpura', 'Bodakdev', 'Satellite',
                'Ashram Road', 'Maninagar', 'Sanand GIDC', 'Gandhinagar Infocity',
            ],
            // Kolkata (City ID: 5122)
            'Kolkata' => [
                'Salt Lake Sector V', 'New Town', 'Rajarhat', 'Park Street', 'Bhowanipore',
                'Howrah', 'Gariahat', 'Dalhousie', 'Camac Street', 'Tollygunge',
            ],
            // Chennai (City ID: 4053)
            'Chennai' => [
                'OMR (Old Mahabalipuram Rd)', 'Guindy', 'T Nagar', 'Velachery', 'Anna Nagar',
                'Ambattur Industrial Estate', 'Porur', 'Nungambakkam', 'Mount Road', 'Perungudi',
            ]
        ];

        $totalInserted = 0;

        foreach ($cityAreas as $cityName => $areas) {
            $city = City::where('city', $cityName)->orWhere('city', 'like', $cityName)->first();
            if (!$city) continue;

            foreach ($areas as $areaName) {
                Area::firstOrCreate(
                    [
                        'city_id' => $city->city_id,
                        'area_name' => $areaName,
                    ],
                    [
                        'is_active' => 1
                    ]
                );
                $totalInserted++;
            }
        }

        echo "Seeded {$totalInserted} localities successfully across top Indian cities!\n";
    }
}
