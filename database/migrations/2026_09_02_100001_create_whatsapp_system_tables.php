<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhatsappSystemTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. WhatsApp Global Configuration & Settings
        if (!Schema::hasTable('whatsapp_settings')) {
            Schema::create('whatsapp_settings', function (Blueprint $table) {
                $table->id();
                $table->string('provider', 50)->default('log'); // meta, gupshup, twilio, ultramsg, log
                $table->boolean('is_enabled')->default(true);
                $table->boolean('test_mode')->default(false);
                $table->string('phone_number_id')->nullable();
                $table->string('business_account_id')->nullable();
                $table->string('sender_number', 50)->nullable();
                $table->text('api_key')->nullable(); // encrypted
                $table->text('api_secret')->nullable(); // encrypted
                $table->string('api_endpoint')->nullable();
                $table->string('webhook_verify_token')->nullable();
                $table->unsignedInteger('daily_limit')->default(500);

                // Feature toggles
                $table->boolean('enable_candidate_notifications')->default(true);
                $table->boolean('enable_employer_notifications')->default(true);
                $table->boolean('enable_matching_alerts')->default(true);
                $table->boolean('enable_application_alerts')->default(true);
                $table->boolean('enable_status_alerts')->default(true);
                $table->boolean('enable_message_alerts')->default(true);
                $table->boolean('enable_payment_alerts')->default(true);

                // Health & Diagnostics
                $table->timestamp('last_tested_at')->nullable();
                $table->string('last_test_status', 50)->nullable();
                $table->text('last_test_message')->nullable();
                $table->json('settings')->nullable();

                $table->timestamps();
            });
        }

        // 2. WhatsApp Pre-Approved Templates Registry
        if (!Schema::hasTable('whatsapp_templates')) {
            Schema::create('whatsapp_templates', function (Blueprint $table) {
                $table->id();
                $table->string('template_key', 80)->unique();
                $table->string('title');
                $table->string('category', 50)->default('UTILITY'); // UTILITY, MARKETING, AUTHENTICATION
                $table->string('provider_template_name')->nullable();
                $table->string('language', 10)->default('en');
                $table->string('header_text')->nullable();
                $table->text('body_text');
                $table->string('footer_text')->nullable();
                $table->json('buttons_json')->nullable();
                $table->json('variables_schema')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 3. WhatsApp Notification Delivery & Audit Log
        if (!Schema::hasTable('whatsapp_notifications')) {
            Schema::create('whatsapp_notifications', function (Blueprint $table) {
                $table->id();
                $table->string('notifiable_type', 50)->index(); // user, company, admin
                $table->unsignedBigInteger('notifiable_id')->index();
                $table->string('recipient_phone', 50)->index();
                $table->string('event_type', 80)->index();
                $table->string('template_key', 80)->index();
                $table->string('idempotency_key', 191)->nullable()->index();
                $table->string('provider', 50)->default('log');
                $table->string('provider_message_id', 191)->nullable()->index();
                $table->string('status', 30)->default('queued')->index(); // queued, sent, delivered, read, failed
                $table->json('payload')->nullable();
                $table->text('rendered_message')->nullable();
                $table->unsignedSmallInteger('attempts')->default(0);
                $table->unsignedSmallInteger('max_attempts')->default(3);
                $table->text('error_message')->nullable();
                $table->timestamp('sent_at')->nullable();
                $table->timestamp('delivered_at')->nullable();
                $table->timestamp('read_at')->nullable();
                $table->timestamp('failed_at')->nullable();
                $table->timestamps();

                $table->index(['created_at', 'status']);
            });
        }

        // 4. Candidate & Employer Notification Preferences
        if (!Schema::hasTable('user_whatsapp_preferences')) {
            Schema::create('user_whatsapp_preferences', function (Blueprint $table) {
                $table->id();
                $table->string('notifiable_type', 50); // user, company
                $table->unsignedBigInteger('notifiable_id');
                $table->string('whatsapp_number', 50)->nullable();
                $table->boolean('is_verified')->default(false);
                $table->timestamp('verified_at')->nullable();

                // Preferences
                $table->boolean('allow_matching_jobs')->default(true);
                $table->boolean('allow_application_updates')->default(true);
                $table->boolean('allow_messages')->default(true);
                $table->boolean('allow_job_status')->default(true);
                $table->boolean('allow_candidate_matches')->default(true);
                $table->boolean('allow_account_payments')->default(true);
                $table->boolean('allow_promotional')->default(false);

                $table->timestamps();

                $table->unique(['notifiable_type', 'notifiable_id'], 'user_wa_pref_unique');
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
        Schema::dropIfExists('user_whatsapp_preferences');
        Schema::dropIfExists('whatsapp_notifications');
        Schema::dropIfExists('whatsapp_templates');
        Schema::dropIfExists('whatsapp_settings');
    }
}
