<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessTables extends Migration
{
    public function up()
    {
        // 1. business_categories
        if (!Schema::hasTable('business_categories')) {
            Schema::create('business_categories', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 150);
                $table->string('slug', 180)->unique()->nullable();
                $table->string('icon', 100)->nullable();
                $table->string('image', 255)->nullable();
                $table->tinyInteger('is_active')->default(1);
                $table->tinyInteger('is_featured')->default(0);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // 2. businesses
        if (!Schema::hasTable('businesses')) {
            Schema::create('businesses', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id')->nullable()->index();
                $table->unsignedInteger('company_id')->nullable()->index();
                $table->unsignedInteger('category_id')->nullable()->index();

                // Core Info
                $table->string('name', 200);
                $table->string('slug', 220)->unique()->nullable();
                $table->text('description')->nullable();
                $table->string('tagline', 255)->nullable();

                // Contact & Location
                $table->string('phone', 30)->nullable();
                $table->string('whatsapp_number', 30)->nullable();
                $table->string('email', 150)->nullable();
                $table->string('website', 255)->nullable();
                $table->string('address_line1', 255)->nullable();
                $table->string('address_line2', 255)->nullable();
                $table->string('area_locality', 150)->nullable();
                $table->unsignedInteger('city_id')->nullable();
                $table->unsignedInteger('state_id')->nullable();
                $table->unsignedInteger('country_id')->nullable();
                $table->string('postal_code', 20)->nullable();
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();

                // Media
                $table->string('logo', 255)->nullable();
                $table->string('cover_image', 255)->nullable();

                // Social
                $table->string('facebook_url', 255)->nullable();
                $table->string('instagram_url', 255)->nullable();
                $table->string('twitter_url', 255)->nullable();
                $table->string('linkedin_url', 255)->nullable();
                $table->string('youtube_url', 255)->nullable();

                // Business Details
                $table->string('established_year', 10)->nullable();
                $table->string('employee_count', 50)->nullable();
                $table->string('business_type', 50)->nullable();

                // Status & Flags
                $table->tinyInteger('is_active')->default(1)->index();
                $table->tinyInteger('is_featured')->default(0)->index();
                $table->tinyInteger('is_claimed')->default(0);
                $table->string('verification_status', 30)->default('pending'); // pending, verified, rejected
                $table->integer('views_count')->default(0);

                // SEO
                $table->string('meta_title', 255)->nullable();
                $table->text('meta_description')->nullable();

                $table->timestamps();
            });
        }

        // 3. business_services
        if (!Schema::hasTable('business_services')) {
            Schema::create('business_services', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('business_id')->index();
                $table->string('name', 200);
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2)->nullable();
                $table->string('price_label', 50)->nullable(); // e.g. "per hour", "starting from"
                $table->string('image', 255)->nullable();
                $table->tinyInteger('is_active')->default(1);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // 4. business_hours
        if (!Schema::hasTable('business_hours')) {
            Schema::create('business_hours', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('business_id')->index();
                $table->tinyInteger('day'); // 0=Mon ... 6=Sun
                $table->time('open_time')->nullable();
                $table->time('close_time')->nullable();
                $table->tinyInteger('is_closed')->default(0);
                $table->tinyInteger('is_24_hours')->default(0);
                $table->timestamps();
            });
        }

        // 5. business_media
        if (!Schema::hasTable('business_media')) {
            Schema::create('business_media', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('business_id')->index();
                $table->string('file_path', 255);
                $table->string('media_type', 20)->default('image'); // image, video
                $table->string('caption', 255)->nullable();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // 6. business_leads
        if (!Schema::hasTable('business_leads')) {
            Schema::create('business_leads', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('business_id')->index();
                $table->string('name', 150)->nullable();
                $table->string('email', 150)->nullable();
                $table->string('phone', 30)->nullable();
                $table->text('message')->nullable();
                $table->string('lead_type', 30)->default('contact'); // contact, quote, whatsapp
                $table->tinyInteger('is_read')->default(0);
                $table->timestamps();
            });
        }

        // 7. business_claims
        if (!Schema::hasTable('business_claims')) {
            Schema::create('business_claims', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('business_id')->index();
                $table->unsignedInteger('user_id')->nullable()->index();
                $table->string('claimant_name', 150)->nullable();
                $table->string('claimant_email', 150)->nullable();
                $table->string('claimant_phone', 30)->nullable();
                $table->text('proof_details')->nullable();
                $table->string('status', 20)->default('pending'); // pending, approved, rejected
                $table->text('admin_notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('business_claims');
        Schema::dropIfExists('business_leads');
        Schema::dropIfExists('business_media');
        Schema::dropIfExists('business_hours');
        Schema::dropIfExists('business_services');
        Schema::dropIfExists('businesses');
        Schema::dropIfExists('business_categories');
    }
}
