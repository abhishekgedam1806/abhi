<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeoTrackingCodesToSiteSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('site_settings', 'google_search_console_code')) {
                $table->mediumText('google_search_console_code')->nullable();
            }
            if (!Schema::hasColumn('site_settings', 'google_tag_manager_head')) {
                $table->mediumText('google_tag_manager_head')->nullable();
            }
            if (!Schema::hasColumn('site_settings', 'google_tag_manager_body')) {
                $table->mediumText('google_tag_manager_body')->nullable();
            }
            if (!Schema::hasColumn('site_settings', 'meta_pixel_code')) {
                $table->mediumText('meta_pixel_code')->nullable();
            }
            if (!Schema::hasColumn('site_settings', 'header_custom_scripts')) {
                $table->mediumText('header_custom_scripts')->nullable();
            }
            if (!Schema::hasColumn('site_settings', 'footer_custom_scripts')) {
                $table->mediumText('footer_custom_scripts')->nullable();
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
        Schema::table('site_settings', function (Blueprint $table) {
            $cols = [
                'google_search_console_code',
                'google_tag_manager_head',
                'google_tag_manager_body',
                'meta_pixel_code',
                'header_custom_scripts',
                'footer_custom_scripts'
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('site_settings', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
}
