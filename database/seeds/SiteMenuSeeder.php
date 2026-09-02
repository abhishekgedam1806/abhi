<?php

use Illuminate\Database\Seeder;
use App\SiteMenuItem;

class SiteMenuSeeder extends Seeder
{
    public function run()
    {
        // 1. Header Menu Items
        $headerItems = [
            ['menu_type' => 'header', 'title' => 'Home', 'url' => '/', 'icon' => 'fa fa-home', 'order_num' => 1, 'audience' => 'all'],
            ['menu_type' => 'header', 'title' => 'Jobs', 'url' => '/jobs', 'icon' => 'fa fa-briefcase', 'order_num' => 2, 'audience' => 'all'],
            ['menu_type' => 'header', 'title' => 'Companies', 'url' => '/companies', 'icon' => 'fa fa-building-o', 'order_num' => 3, 'audience' => 'all'],
            ['menu_type' => 'header', 'title' => 'Businesses', 'url' => '/businesses', 'icon' => 'fa fa-handshake-o', 'order_num' => 4, 'audience' => 'all'],
            ['menu_type' => 'header', 'title' => 'Pricing', 'url' => '/pricing', 'icon' => 'fa fa-tags', 'order_num' => 5, 'audience' => 'all'],
            ['menu_type' => 'header', 'title' => 'Blog', 'url' => '/blog', 'icon' => 'fa fa-newspaper-o', 'order_num' => 6, 'audience' => 'all'],
            ['menu_type' => 'header', 'title' => 'Contact Us', 'url' => '/contact-us', 'icon' => 'fa fa-envelope-o', 'order_num' => 7, 'audience' => 'all'],
        ];

        // 2. Footer Column 1: Quick Links
        $footerCol1 = [
            ['menu_type' => 'footer_col1', 'title' => 'Home', 'url' => '/', 'order_num' => 1],
            ['menu_type' => 'footer_col1', 'title' => 'Jobs', 'url' => '/jobs', 'order_num' => 2],
            ['menu_type' => 'footer_col1', 'title' => 'Companies', 'url' => '/companies', 'order_num' => 3],
            ['menu_type' => 'footer_col1', 'title' => 'Businesses', 'url' => '/businesses', 'order_num' => 4],
            ['menu_type' => 'footer_col1', 'title' => 'Blog', 'url' => '/blog', 'order_num' => 5],
            ['menu_type' => 'footer_col1', 'title' => 'Contact Us', 'url' => '/contact-us', 'order_num' => 6],
            ['menu_type' => 'footer_col1', 'title' => 'FAQs', 'url' => '/faq', 'order_num' => 7],
            ['menu_type' => 'footer_col1', 'title' => 'About Us', 'url' => '/cms/about-us', 'order_num' => 8],
            ['menu_type' => 'footer_col1', 'title' => 'Terms of Use', 'url' => '/cms/terms-of-use', 'order_num' => 9],
            ['menu_type' => 'footer_col1', 'title' => 'Privacy Policy', 'url' => '/cms/privacy-policy', 'order_num' => 10],
        ];

        // 3. Footer Column 2: Popular Categories
        $footerCol2 = [
            ['menu_type' => 'footer_col2', 'title' => 'IT & Software Jobs', 'url' => '/jobs?functional_area_id[]=1', 'order_num' => 1],
            ['menu_type' => 'footer_col2', 'title' => 'Sales & Marketing', 'url' => '/jobs?functional_area_id[]=2', 'order_num' => 2],
            ['menu_type' => 'footer_col2', 'title' => 'Accounts & Finance', 'url' => '/jobs?functional_area_id[]=3', 'order_num' => 3],
            ['menu_type' => 'footer_col2', 'title' => 'Human Resources (HR)', 'url' => '/jobs?functional_area_id[]=4', 'order_num' => 4],
            ['menu_type' => 'footer_col2', 'title' => 'Customer Support / BPO', 'url' => '/jobs?functional_area_id[]=5', 'order_num' => 5],
            ['menu_type' => 'footer_col2', 'title' => 'Digital Marketing', 'url' => '/jobs?functional_area_id[]=6', 'order_num' => 6],
            ['menu_type' => 'footer_col2', 'title' => 'Graphic & UI/UX Design', 'url' => '/jobs?functional_area_id[]=7', 'order_num' => 7],
            ['menu_type' => 'footer_col2', 'title' => 'Operations & Logistics', 'url' => '/jobs?functional_area_id[]=8', 'order_num' => 8],
        ];

        // 4. Footer Column 3: Industries
        $footerCol3 = [
            ['menu_type' => 'footer_col3', 'title' => 'Information Technology', 'url' => '/jobs?industry_id[]=1', 'order_num' => 1],
            ['menu_type' => 'footer_col3', 'title' => 'Banking & Financial', 'url' => '/jobs?industry_id[]=2', 'order_num' => 2],
            ['menu_type' => 'footer_col3', 'title' => 'Healthcare & Pharma', 'url' => '/jobs?industry_id[]=3', 'order_num' => 3],
            ['menu_type' => 'footer_col3', 'title' => 'Manufacturing & Engineering', 'url' => '/jobs?industry_id[]=4', 'order_num' => 4],
            ['menu_type' => 'footer_col3', 'title' => 'Education & Training', 'url' => '/jobs?industry_id[]=5', 'order_num' => 5],
            ['menu_type' => 'footer_col3', 'title' => 'Retail & E-commerce', 'url' => '/jobs?industry_id[]=6', 'order_num' => 6],
            ['menu_type' => 'footer_col3', 'title' => 'Real Estate & Construction', 'url' => '/jobs?industry_id[]=7', 'order_num' => 7],
            ['menu_type' => 'footer_col3', 'title' => 'Automobile & Aviation', 'url' => '/jobs?industry_id[]=8', 'order_num' => 8],
        ];

        // 5. Popular Cities Bar
        $footerCities = [
            ['menu_type' => 'footer_cities', 'title' => 'Jobs in Nagpur', 'url' => '/jobs-in-nagpur', 'order_num' => 1],
            ['menu_type' => 'footer_cities', 'title' => 'Jobs in Pune', 'url' => '/jobs-in-pune', 'order_num' => 2],
            ['menu_type' => 'footer_cities', 'title' => 'Jobs in Mumbai', 'url' => '/jobs-in-mumbai', 'order_num' => 3],
            ['menu_type' => 'footer_cities', 'title' => 'Jobs in Bangalore', 'url' => '/jobs-in-bangalore', 'order_num' => 4],
            ['menu_type' => 'footer_cities', 'title' => 'Jobs in Delhi', 'url' => '/jobs-in-delhi', 'order_num' => 5],
            ['menu_type' => 'footer_cities', 'title' => 'Jobs in Hyderabad', 'url' => '/jobs-in-hyderabad', 'order_num' => 6],
        ];

        $allItems = array_merge($headerItems, $footerCol1, $footerCol2, $footerCol3, $footerCities);

        foreach ($allItems as $item) {
            SiteMenuItem::firstOrCreate(
                [
                    'menu_type' => $item['menu_type'],
                    'title' => $item['title'],
                ],
                [
                    'url' => $item['url'],
                    'icon' => $item['icon'] ?? null,
                    'order_num' => $item['order_num'] ?? 0,
                    'audience' => $item['audience'] ?? 'all',
                    'target' => '_self',
                    'is_active' => 1,
                ]
            );
        }

        SiteMenuItem::clearMenuCache();
    }
}
