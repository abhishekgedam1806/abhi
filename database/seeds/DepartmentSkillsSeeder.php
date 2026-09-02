<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\FunctionalArea;
use App\JobSkill;

class DepartmentSkillsSeeder extends Seeder
{
    public function run()
    {
        $departmentSkills = [
            'IT & Software' => [
                'HTML', 'CSS', 'JavaScript', 'TypeScript', 'React', 'React Native', 'Angular', 'Vue.js', 
                'Node.js', 'Express.js', 'PHP', 'Laravel', 'CodeIgniter', 'Python', 'Django', 'Flask', 
                'Java', 'Spring Boot', 'C++', 'C#', '.NET', 'WordPress', 'WooCommerce', 'MySQL', 
                'PostgreSQL', 'MongoDB', 'SQL', 'REST API', 'GraphQL', 'Git', 'GitHub', 'GitLab', 
                'Docker', 'Kubernetes', 'AWS', 'Microsoft Azure', 'Google Cloud', 'DevOps', 'CI/CD', 
                'Linux', 'Cybersecurity', 'Network Administration', 'System Administration', 
                'Software Testing', 'Manual Testing', 'Automation Testing', 'Selenium', 'QA Testing', 
                'Data Analysis', 'Power BI', 'Tableau', 'Machine Learning', 'Artificial Intelligence', 
                'UI/UX Design', 'Figma', 'Adobe XD'
            ],
            'Sales' => [
                'Sales', 'Direct Sales', 'Field Sales', 'Inside Sales', 'B2B Sales', 'B2C Sales', 
                'Corporate Sales', 'Retail Sales', 'Lead Generation', 'Cold Calling', 'Telecalling', 
                'Telesales', 'Business Development', 'Client Acquisition', 'Customer Acquisition', 
                'Relationship Management', 'Account Management', 'Negotiation', 'Communication', 
                'Presentation Skills', 'Product Demonstration', 'Sales Closing', 'Upselling', 
                'Cross-selling', 'Sales Target', 'Sales Reporting', 'CRM', 'Salesforce', 'HubSpot', 
                'Zoho CRM', 'Market Research', 'Territory Management', 'Channel Sales', 
                'Distributor Management', 'Dealer Management', 'Key Account Management'
            ],
            'Marketing & Digital Marketing' => [
                'Digital Marketing', 'SEO', 'SEO Optimization', 'Local SEO', 'Technical SEO', 
                'On-Page SEO', 'Off-Page SEO', 'Keyword Research', 'Link Building', 
                'Google Business Profile', 'Google Search Console', 'Google Analytics', 'GA4', 
                'Social Media Marketing', 'Social Media Management', 'Social Media', 'Content Marketing', 
                'Content Strategy', 'Content Writing', 'Copywriting', 'Blogging', 'Email Marketing', 
                'WhatsApp Marketing', 'Influencer Marketing', 'Affiliate Marketing', 'Performance Marketing', 
                'Paid Media', 'PPC', 'Google Ads', 'Meta Ads', 'Facebook Ads', 'Instagram Ads', 
                'LinkedIn Ads', 'YouTube Ads', 'Lead Generation', 'Marketing Analytics', 
                'Conversion Rate Optimization', 'CRO', 'Google Tag Manager', 'Canva', 'WordPress', 
                'Brand Marketing', 'Product Marketing', 'Market Research', 'Campaign Management', 
                'Marketing Automation'
            ],
            'BPO / Call Center / Customer Support' => [
                'Customer Service', 'Customer Support', 'Customer Care', 'Call Center', 'BPO', 
                'Voice Process', 'Non-Voice Process', 'International Voice Process', 
                'Domestic Voice Process', 'Telecalling', 'Telesales', 'Chat Support', 'Email Support', 
                'Technical Support', 'Help Desk', 'Complaint Resolution', 'Problem Solving', 
                'Communication', 'English Communication', 'Hindi Communication', 'Verbal Communication', 
                'Active Listening', 'CRM', 'Ticket Management', 'Call Handling', 'Inbound Calling', 
                'Outbound Calling', 'Customer Relationship Management', 'Data Entry', 'Typing', 'MS Office'
            ],
            'Accounts & Finance' => [
                'Accounting', 'Bookkeeping', 'Tally', 'Tally Prime', 'GST', 'GST Filing', 'Taxation', 
                'Income Tax', 'Auditing', 'Financial Accounting', 'Cost Accounting', 
                'Management Accounting', 'Accounts Payable', 'Accounts Receivable', 'Billing', 
                'Invoicing', 'Payroll', 'Bank Reconciliation', 'Financial Reporting', 'Balance Sheet', 
                'Profit & Loss', 'Cash Flow', 'Financial Analysis', 'Budgeting', 'Excel', 
                'Advanced Excel', 'MS Excel', 'MS Office', 'TDS', 'Tax Compliance', 'MIS Reporting', 
                'Data Entry', 'ERP', 'SAP', 'QuickBooks', 'Zoho Books'
            ],
            'HR & Recruitment' => [
                'Human Resources', 'HR Operations', 'Recruitment', 'Talent Acquisition', 'Hiring', 
                'Candidate Sourcing', 'Candidate Screening', 'Interviewing', 'Recruitment Marketing', 
                'Employee Relations', 'Employee Engagement', 'Onboarding', 'Offboarding', 'Payroll', 
                'HR Administration', 'HR Policies', 'Performance Management', 'Training & Development', 
                'Learning & Development', 'Compensation & Benefits', 'Attendance Management', 
                'Leave Management', 'HRMS', 'HRIS', 'Employee Database', 'Labour Laws', 'Compliance', 
                'Job Posting', 'Resume Screening', 'LinkedIn Recruitment', 'Communication', 
                'Conflict Resolution'
            ],
            'Driving' => [
                'Driving', 'Car Driving', 'Commercial Driving', 'Personal Driving', 'Taxi Driving', 
                'Truck Driving', 'Bus Driving', 'Heavy Vehicle Driving', 'Light Motor Vehicle', 
                'LMV', 'HMV', 'Valid Driving License', 'Commercial Driving License', 'Route Knowledge', 
                'GPS Navigation', 'Google Maps', 'Vehicle Maintenance', 'Road Safety', 'Traffic Rules', 
                'Local Area Knowledge', 'Vehicle Inspection', 'Delivery Driving', 'School Van Driving', 
                'Corporate Driver', 'Personal Driver'
            ],
            'Logistics & Delivery' => [
                'Logistics', 'Delivery', 'Delivery Management', 'Delivery Executive', 'Last Mile Delivery', 
                'Courier', 'Warehouse Management', 'Inventory Management', 'Stock Management', 
                'Dispatch', 'Order Processing', 'Order Management', 'Supply Chain', 
                'Supply Chain Management', 'Transportation', 'Route Planning', 'Fleet Management', 
                'Shipment Management', 'Tracking', 'Packaging', 'Picking', 'Packing', 'Loading', 
                'Unloading', 'Warehouse Operations', 'Barcode Scanning', 'Inventory Control', 
                'Purchase Management', 'Vendor Management', 'Distribution', 'Logistics Coordination', 
                'GPS Navigation', 'Delivery Tracking', 'ERP', 'Excel'
            ],
            'Graphic Design & Creative' => [
                'Graphic Design', 'Adobe Photoshop', 'Adobe Illustrator', 'CorelDRAW', 'Adobe InDesign', 
                'Canva', 'Figma', 'UI Design', 'UX Design', 'Web Design', 'Logo Design', 'Brand Identity', 
                'Social Media Design', 'Poster Design', 'Banner Design', 'Brochure Design', 
                'Packaging Design', 'Print Design', 'Typography', 'Illustration', 'Photo Editing', 
                'Video Editing', 'Motion Graphics', '2D Animation', '3D Design', 'Creative Design', 
                'Visual Design'
            ],
            'Administration' => [
                'Administration', 'Office Administration', 'Office Management', 'Data Entry', 
                'MS Office', 'MS Word', 'MS Excel', 'MS PowerPoint', 'Google Workspace', 
                'Documentation', 'Filing', 'Record Management', 'Email Management', 'Scheduling', 
                'Calendar Management', 'Reception', 'Front Office', 'Communication', 'Coordination', 
                'Inventory Management', 'Vendor Coordination', 'Customer Handling', 
                'Administrative Support', 'Reporting', 'MIS', 'Typing'
            ]
        ];

        $deptMap = [];

        // 1. Ensure the 10 Canonical Departments exist in functional_areas
        foreach (array_keys($departmentSkills) as $index => $deptName) {
            $existing = DB::table('functional_areas')
                ->where('functional_area', $deptName)
                ->first();

            if (!$existing) {
                // Check if an existing close match exists
                $aliasMap = [
                    'IT & Software' => ['Software & IT', 'Software & Web Development', 'IT Security'],
                    'Sales' => ['Sales', 'Sales & Business Development'],
                    'Marketing & Digital Marketing' => ['Marketing', 'Online Marketing', 'Digital Marketing'],
                    'BPO / Call Center / Customer Support' => ['BPO / Call Center', 'Customer Support', 'Client Services & Customer Support'],
                    'Accounts & Finance' => ['Accounts, Finance & Financial Services', 'Accounts & Finance', 'Accountant'],
                    'HR & Recruitment' => ['HR & Recruitment', 'Human Resources', 'HR', 'Recruitment'],
                    'Driving' => ['Driver Jobs', 'Driving'],
                    'Logistics & Delivery' => ['Delivery & Logistics', 'Distribution & Logistics', 'Logistics & Warehousing'],
                    'Graphic Design & Creative' => ['Graphic Design', 'Creative Design'],
                    'Administration' => ['Administration', 'Admin', 'Admin Operation', 'Office Administration']
                ];

                $matchedId = null;
                if (isset($aliasMap[$deptName])) {
                    foreach ($aliasMap[$deptName] as $alias) {
                        $match = DB::table('functional_areas')->where('functional_area', $alias)->first();
                        if ($match) {
                            $matchedId = $match->functional_area_id ?: $match->id;
                            break;
                        }
                    }
                }

                if ($matchedId) {
                    // Update this functional area name to canonical department name
                    DB::table('functional_areas')
                        ->where('functional_area_id', $matchedId)
                        ->update(['functional_area' => $deptName, 'is_active' => 1]);
                    $deptMap[$deptName] = $matchedId;
                } else {
                    $maxFaId = (int) DB::table('functional_areas')->max('functional_area_id') + 1;
                    $id = DB::table('functional_areas')->insertGetId([
                        'functional_area_id' => $maxFaId,
                        'functional_area' => $deptName,
                        'is_default' => 1,
                        'is_active' => 1,
                        'sort_order' => $index + 1,
                        'lang' => 'en',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    $deptMap[$deptName] = $maxFaId;
                }
            } else {
                $faId = $existing->functional_area_id ?: $existing->id;
                DB::table('functional_areas')->where('id', $existing->id)->update(['is_active' => 1]);
                $deptMap[$deptName] = $faId;
            }
        }

        // 2. Seed Skills into job_skills under respective functional_area_id
        foreach ($departmentSkills as $deptName => $skills) {
            $deptId = $deptMap[$deptName];

            foreach ($skills as $skillName) {
                $trimmedSkill = trim($skillName);
                if (empty($trimmedSkill)) continue;

                // Check if this skill already exists in this department
                $existingSkill = DB::table('job_skills')
                    ->where('functional_area_id', $deptId)
                    ->where('job_skill', $trimmedSkill)
                    ->first();

                if (!$existingSkill) {
                    // Check if skill exists globally without department
                    $unassignedSkill = DB::table('job_skills')
                        ->where('job_skill', $trimmedSkill)
                        ->where(function($q) {
                            $q->whereNull('functional_area_id')->orWhere('functional_area_id', 0);
                        })
                        ->first();

                    if ($unassignedSkill) {
                        DB::table('job_skills')
                            ->where('id', $unassignedSkill->id)
                            ->update([
                                'functional_area_id' => $deptId,
                                'is_active' => 1
                            ]);
                    } else {
                        // Create new skill entry
                        $maxSkillId = (int) DB::table('job_skills')->max('job_skill_id') + 1;
                        $newId = DB::table('job_skills')->insertGetId([
                            'job_skill_id' => $maxSkillId,
                            'functional_area_id' => $deptId,
                            'job_skill' => $trimmedSkill,
                            'is_default' => 1,
                            'is_active' => 1,
                            'sort_order' => $maxSkillId,
                            'lang' => 'en',
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }
        }

        echo "Departments and Skills successfully seeded!\n";
    }
}
