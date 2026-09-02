<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHeroSectionToSiteSettings extends Migration
{
    public function up()
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('hero_badge_text', 150)->nullable()->default('INDIA #1 JOB PLATFORM');
            $table->string('hero_title_line1', 150)->nullable()->default('Your job search');
            $table->string('hero_title_line2', 150)->nullable()->default('ends here');
            $table->string('hero_subtitle', 255)->nullable()->default('Discover 50 lakh+ career opportunities across India');
            $table->string('hero_stat1_number', 30)->nullable()->default('50L+');
            $table->string('hero_stat1_label', 60)->nullable()->default('Jobs');
            $table->string('hero_stat2_number', 30)->nullable()->default('1Cr+');
            $table->string('hero_stat2_label', 60)->nullable()->default('Job Seekers');
            $table->string('hero_stat3_number', 30)->nullable()->default('10K+');
            $table->string('hero_stat3_label', 60)->nullable()->default('Companies');
            $table->string('hero_hired_text', 100)->nullable()->default('Rahul got placed at TCS');
            $table->string('hero_image', 150)->nullable();
        });
    }

    public function down()
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'hero_badge_text', 'hero_title_line1', 'hero_title_line2',
                'hero_subtitle', 'hero_stat1_number', 'hero_stat1_label',
                'hero_stat2_number', 'hero_stat2_label', 'hero_stat3_number',
                'hero_stat3_label', 'hero_hired_text', 'hero_image'
            ]);
        });
    }
}
