<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstitutionsAndAddGeoToCities extends Migration
{
    public function up()
    {
        // 1. Add latitude/longitude to cities for geo-radius filtering
        if (!Schema::hasColumn('cities', 'latitude')) {
            Schema::table('cities', function (Blueprint $table) {
                $table->decimal('latitude', 10, 7)->nullable()->after('lang');
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            });
        }

        // 2. Institutions table for college/university autocomplete
        if (!Schema::hasTable('institutions')) {
            Schema::create('institutions', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 200)->index();
                $table->string('type', 50)->default('College');
                $table->string('university', 200)->nullable();
                $table->string('state', 100)->nullable()->index();
                $table->string('district', 100)->nullable();
                $table->string('city', 100)->nullable()->index();
                $table->boolean('is_active')->default(1);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('institutions');
        if (Schema::hasColumn('cities', 'latitude')) {
            Schema::table('cities', function (Blueprint $table) {
                $table->dropColumn(['latitude', 'longitude']);
            });
        }
    }
}
