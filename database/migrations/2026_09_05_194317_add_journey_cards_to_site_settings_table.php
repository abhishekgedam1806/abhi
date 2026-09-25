<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJourneyCardsToSiteSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('journey_badge_text')->nullable()->default('What Are You Looking For?');
            $table->string('journey_main_title')->nullable()->default('Choose Your Path on JobNBiz');
            
            // Card 1: Job Seekers
            $table->string('journey_c1_image')->nullable();
            $table->string('journey_c1_eyebrow')->nullable()->default('For Job Seekers');
            $table->string('journey_c1_title')->nullable()->default('Find Jobs');
            $table->text('journey_c1_desc')->nullable();
            $table->string('journey_c1_btn_text')->nullable()->default('Browse Jobs');
            $table->string('journey_c1_btn_url')->nullable();
            
            // Card 2: Employers
            $table->string('journey_c2_image')->nullable();
            $table->string('journey_c2_eyebrow')->nullable()->default('For Employers');
            $table->string('journey_c2_title')->nullable()->default('Hire Talent');
            $table->text('journey_c2_desc')->nullable();
            $table->string('journey_c2_btn_text')->nullable()->default('Post a Job');
            $table->string('journey_c2_btn_url')->nullable();
            
            // Card 3: Businesses
            $table->string('journey_c3_image')->nullable();
            $table->string('journey_c3_eyebrow')->nullable()->default('For Businesses');
            $table->string('journey_c3_title')->nullable()->default('Get Discovered');
            $table->text('journey_c3_desc')->nullable();
            $table->string('journey_c3_btn_text')->nullable()->default('Find Businesses');
            $table->string('journey_c3_btn_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'journey_badge_text', 'journey_main_title',
                'journey_c1_image', 'journey_c1_eyebrow', 'journey_c1_title', 'journey_c1_desc', 'journey_c1_btn_text', 'journey_c1_btn_url',
                'journey_c2_image', 'journey_c2_eyebrow', 'journey_c2_title', 'journey_c2_desc', 'journey_c2_btn_text', 'journey_c2_btn_url',
                'journey_c3_image', 'journey_c3_eyebrow', 'journey_c3_title', 'journey_c3_desc', 'journey_c3_btn_text', 'journey_c3_btn_url',
            ]);
        });
    }
}
