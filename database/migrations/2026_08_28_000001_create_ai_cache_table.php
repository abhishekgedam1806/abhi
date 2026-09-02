<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAiCacheTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('ai_cache')) {
            Schema::create('ai_cache', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('input_hash', 64)->unique()->index(); // SHA-256 (feature + input + prompt_version + model)
                $table->string('feature', 60)->index();
                $table->string('prompt_version', 30)->default('v1');
                $table->string('provider', 40);
                $table->string('model', 60);
                $table->longText('response_text');
                $table->text('response_json')->nullable();
                $table->integer('input_tokens')->default(0);
                $table->integer('output_tokens')->default(0);
                $table->integer('hit_count')->default(1);
                $table->timestamp('last_accessed_at')->nullable();
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
        Schema::dropIfExists('ai_cache');
    }
}
