<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddUserTypeToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('users', 'user_type')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('user_type', 30)->default('jobseeker')->after('is_active');
            });
        }

        // Set users who already own a business to 'business'
        $businessUserIds = DB::table('businesses')->whereNotNull('user_id')->pluck('user_id')->toArray();
        if (!empty($businessUserIds)) {
            DB::table('users')->whereIn('id', $businessUserIds)->update(['user_type' => 'business']);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('users', 'user_type')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('user_type');
            });
        }
    }
}
