<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHrContactAndStatusToApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('companies', 'hr_name')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('hr_name', 155)->nullable()->after('ceo');
                $table->string('hr_avatar', 255)->nullable()->after('hr_name');
                $table->string('whatsapp_number', 30)->nullable()->after('phone');
                $table->tinyInteger('allow_phone_contact')->default(1)->after('whatsapp_number');
                $table->tinyInteger('allow_whatsapp_contact')->default(1)->after('allow_phone_contact');
            });
        }

        if (!Schema::hasColumn('job_apply', 'status')) {
            Schema::table('job_apply', function (Blueprint $table) {
                $table->string('status', 50)->default('applied')->after('salary_currency');
            });
        }

        if (!Schema::hasTable('application_contact_activities')) {
            Schema::create('application_contact_activities', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('application_id')->unsigned()->index();
                $table->integer('job_id')->unsigned()->index();
                $table->integer('candidate_id')->unsigned()->index();
                $table->integer('company_id')->unsigned()->index();
                $table->string('contact_type', 20); // 'phone' or 'whatsapp'
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
        Schema::dropIfExists('application_contact_activities');

        if (Schema::hasColumn('job_apply', 'status')) {
            Schema::table('job_apply', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }

        if (Schema::hasColumn('companies', 'hr_name')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->dropColumn([
                    'hr_name',
                    'hr_avatar',
                    'whatsapp_number',
                    'allow_phone_contact',
                    'allow_whatsapp_contact'
                ]);
            });
        }
    }
}
