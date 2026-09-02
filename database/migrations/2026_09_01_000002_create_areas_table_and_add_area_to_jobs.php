<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreasTableAndAddAreaToJobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('areas')) {
            Schema::create('areas', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedInteger('city_id')->index();
                $table->string('area_name', 150)->index();
                $table->string('pincode', 20)->nullable()->index();
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                $table->tinyInteger('is_active')->default(1)->index();
                $table->timestamps();

                $table->index(['city_id', 'is_active']);
            });
        }

        if (Schema::hasTable('jobs')) {
            Schema::table('jobs', function (Blueprint $table) {
                if (!Schema::hasColumn('jobs', 'area_id')) {
                    $table->unsignedBigInteger('area_id')->nullable()->after('city_id')->index();
                }
                if (!Schema::hasColumn('jobs', 'area_name')) {
                    $table->string('area_name', 150)->nullable()->after('area_id')->index();
                }
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
        if (Schema::hasTable('jobs')) {
            Schema::table('jobs', function (Blueprint $table) {
                if (Schema::hasColumn('jobs', 'area_id')) {
                    $table->dropColumn('area_id');
                }
                if (Schema::hasColumn('jobs', 'area_name')) {
                    $table->dropColumn('area_name');
                }
            });
        }

        Schema::dropIfExists('areas');
    }
}
