<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRawJobsAndSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Job Sources Table
        if (!Schema::hasTable('job_sources')) {
            Schema::create('job_sources', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('source_type')->default('feed')->index(); // feed, api, partner, manual
                $table->string('feed_url')->nullable();
                $table->boolean('is_active')->default(1)->index();
                $table->timestamp('last_synced_at')->nullable();
                $table->integer('jobs_collected_count')->default(0);
                $table->text('settings')->nullable();
                $table->timestamps();
            });
        }

        // 2. Raw Jobs Table
        if (!Schema::hasTable('raw_jobs')) {
            Schema::create('raw_jobs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('source_id')->nullable()->index();
                $table->string('source_name', 100)->nullable();
                $table->text('source_url')->nullable();
                $table->string('content_hash', 64)->unique()->index(); // SHA-256 fingerprint for fast duplicate detection
                $table->string('raw_title');
                $table->string('raw_company')->nullable();
                $table->string('raw_location')->nullable();
                $table->longText('raw_description')->nullable();
                $table->longText('raw_payload')->nullable(); // JSON raw data
                $table->string('status', 30)->default('pending')->index(); // pending, enriched, duplicate, rejected, published
                $table->unsignedBigInteger('job_id')->nullable()->index(); // Linked published Job ID
                $table->timestamps();
            });
        }

        // 3. Job AI Data Table (Cached enrichment, quality score & SEO)
        if (!Schema::hasTable('job_ai_data')) {
            Schema::create('job_ai_data', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('job_id')->nullable()->index();
                $table->unsignedBigInteger('raw_job_id')->nullable()->index();
                $table->integer('quality_score')->default(0)->index(); // 0 to 100
                $table->text('quality_report')->nullable(); // JSON breakdown
                $table->text('extracted_skills')->nullable(); // JSON array
                $table->string('suggested_category')->nullable();
                $table->string('experience_level')->nullable();
                $table->string('employment_type')->nullable();
                $table->string('seo_title')->nullable();
                $table->text('seo_description')->nullable();
                $table->string('slug')->nullable();
                $table->text('focus_keywords')->nullable(); // JSON array
                $table->string('model', 60)->nullable();
                $table->string('provider', 60)->nullable();
                $table->timestamp('last_analyzed_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_ai_data');
        Schema::dropIfExists('raw_jobs');
        Schema::dropIfExists('job_sources');
    }
}
