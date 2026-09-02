<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EnhanceInstitutionsAndUserInstitutionFields extends Migration
{
    public function up()
    {
        // 1. Enhance institutions table
        Schema::table('institutions', function (Blueprint $table) {
            if (!Schema::hasColumn('institutions', 'source')) {
                $table->string('source', 100)->default('UGC / AISHE / MoE')->after('city');
            }
            if (!Schema::hasColumn('institutions', 'verification_status')) {
                $table->string('verification_status', 50)->default('verified')->after('source');
            }
        });

        // 2. Enhance users table for institution tracking
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'institution_id')) {
                $table->unsignedInteger('institution_id')->nullable()->after('institution_name');
            }
            if (!Schema::hasColumn('users', 'institution_type')) {
                $table->string('institution_type', 50)->nullable()->default('official')->after('institution_id');
            }
            if (!Schema::hasColumn('users', 'institution_verification_status')) {
                $table->string('institution_verification_status', 50)->nullable()->default('verified')->after('institution_type');
            }
        });
    }

    public function down()
    {
        Schema::table('institutions', function (Blueprint $table) {
            if (Schema::hasColumn('institutions', 'source')) {
                $table->dropColumn('source');
            }
            if (Schema::hasColumn('institutions', 'verification_status')) {
                $table->dropColumn('verification_status');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'institution_id')) {
                $table->dropColumn('institution_id');
            }
            if (Schema::hasColumn('users', 'institution_type')) {
                $table->dropColumn('institution_type');
            }
            if (Schema::hasColumn('users', 'institution_verification_status')) {
                $table->dropColumn('institution_verification_status');
            }
        });
    }
}
