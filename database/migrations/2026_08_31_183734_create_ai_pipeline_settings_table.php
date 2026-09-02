<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAiPipelineSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ai_pipeline_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('daily_fetch_limit')->default(5);
            $table->boolean('auto_publish')->default(1);
            $table->boolean('auto_enrich')->default(1);
            $table->integer('min_quality_score')->default(70);
            $table->string('target_cities', 500)->default('Nagpur, Mumbai, Pune, Delhi, Bangalore');
            $table->integer('max_job_age_days')->default(7);
            $table->string('target_categories', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ai_pipeline_settings');
    }
}
