<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAiProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ai_providers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('provider_type')->index(); // gemini, openai, azure_openai, claude, grok, glm
            $table->string('model')->index(); // e.g. gemini-1.5-flash, gpt-4o-mini
            $table->text('api_key'); // Encrypted
            $table->string('base_url')->nullable();
            $table->integer('timeout_sec')->default(30);
            $table->boolean('is_active')->default(0);
            $table->boolean('is_default')->default(0)->index();
            $table->string('status')->default('inactive'); // active, inactive, connection_error, config_error
            $table->timestamp('last_tested_at')->nullable();
            $table->integer('last_test_response_ms')->nullable();
            $table->text('last_test_error')->nullable();
            $table->text('settings')->nullable();
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
        Schema::dropIfExists('ai_providers');
    }
}
