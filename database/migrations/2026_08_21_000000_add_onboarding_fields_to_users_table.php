<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOnboardingFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'onboarding_completed')) {
                $table->boolean('onboarding_completed')->default(0)->index();
            }
            if (!Schema::hasColumn('users', 'onboarding_step')) {
                $table->integer('onboarding_step')->default(1);
            }
            if (!Schema::hasColumn('users', 'profile_type')) {
                $table->string('profile_type', 30)->nullable(); // fresher, experienced
            }
            if (!Schema::hasColumn('users', 'preferred_job_distance')) {
                $table->string('preferred_job_distance', 30)->nullable(); // 5 km, 10 km, 20 km, 50 km, Anywhere
            }
            if (!Schema::hasColumn('users', 'preferred_work_type')) {
                $table->string('preferred_work_type', 50)->nullable(); // Full Time, Part Time, Contract, Internship
            }
            if (!Schema::hasColumn('users', 'preferred_work_mode')) {
                $table->string('preferred_work_mode', 50)->nullable(); // On-site, Hybrid, Work From Home
            }
            if (!Schema::hasColumn('users', 'preferred_job_roles')) {
                $table->text('preferred_job_roles')->nullable(); // JSON or comma-separated
            }
            if (!Schema::hasColumn('users', 'highest_qualification')) {
                $table->string('highest_qualification', 100)->nullable();
            }
            if (!Schema::hasColumn('users', 'course_degree')) {
                $table->string('course_degree', 100)->nullable();
            }
            if (!Schema::hasColumn('users', 'course_type')) {
                $table->string('course_type', 50)->nullable();
            }
            if (!Schema::hasColumn('users', 'specialization')) {
                $table->string('specialization', 150)->nullable();
            }
            if (!Schema::hasColumn('users', 'institution_name')) {
                $table->string('institution_name', 191)->nullable();
            }
            if (!Schema::hasColumn('users', 'degree_start_year')) {
                $table->string('degree_start_year', 10)->nullable();
            }
            if (!Schema::hasColumn('users', 'degree_end_year')) {
                $table->string('degree_end_year', 10)->nullable();
            }
            if (!Schema::hasColumn('users', 'degree_percentage')) {
                $table->string('degree_percentage', 20)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'onboarding_completed',
                'onboarding_step',
                'profile_type',
                'preferred_job_distance',
                'preferred_work_type',
                'preferred_work_mode',
                'preferred_job_roles',
                'highest_qualification',
                'course_degree',
                'course_type',
                'specialization',
                'institution_name',
                'degree_start_year',
                'degree_end_year',
                'degree_percentage',
            ];
            foreach ($columns as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
}
