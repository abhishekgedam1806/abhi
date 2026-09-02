<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoginOtpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('login_otps')) {
            Schema::create('login_otps', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('email', 191)->index();
                $table->string('otp_code', 10);
                $table->string('user_type', 30)->default('candidate'); // candidate, employer, business
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->integer('attempts')->default(0);
                $table->boolean('is_used')->default(0)->index();
                $table->timestamp('expires_at')->nullable()->index();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('blocked_email_domains')) {
            Schema::create('blocked_email_domains', function (Blueprint $table) {
                $table->increments('id');
                $table->string('domain', 191)->unique();
                $table->string('reason', 191)->default('disposable');
                $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('login_otps');
        Schema::dropIfExists('blocked_email_domains');
    }
}
