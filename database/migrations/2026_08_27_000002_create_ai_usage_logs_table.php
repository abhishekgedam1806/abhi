<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAiUsageLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ai_usage_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('provider_id')->nullable()->index();
            $table->string('provider_type')->index();
            $table->string('model')->index();
            $table->string('feature')->index();
            $table->string('feature_group')->default('system')->index(); // candidate, employer, automated_jobs, system
            $table->integer('input_tokens')->default(0);
            $table->integer('output_tokens')->default(0);
            $table->integer('total_tokens')->default(0);
            $table->integer('response_time_ms')->default(0);
            $table->decimal('estimated_cost_inr', 10, 4)->default(0.0000);
            $table->string('currency', 10)->default('INR');
            $table->boolean('is_success')->default(1)->index();
            $table->text('error_message')->nullable();
            $table->string('user_type', 30)->nullable(); // candidate, company, admin, system
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('metadata')->nullable();
            $table->timestamps();

            $table->index(['created_at', 'feature']);
            $table->index(['created_at', 'provider_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ai_usage_logs');
    }
}
