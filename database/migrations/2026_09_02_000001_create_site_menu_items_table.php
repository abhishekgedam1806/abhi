<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteMenuItemsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('site_menu_items')) {
            Schema::create('site_menu_items', function (Blueprint $table) {
                $table->increments('id');
                $table->string('menu_type', 50)->default('header')->index(); // 'header', 'footer_col1', 'footer_col2', 'footer_col3', 'footer_cities'
                $table->string('title', 190);
                $table->string('url', 255);
                $table->string('icon', 100)->nullable();
                $table->string('target', 20)->default('_self'); // '_self', '_blank'
                $table->integer('order_num')->default(0)->index();
                $table->tinyInteger('is_active')->default(1)->index();
                $table->string('audience', 30)->default('all'); // 'all', 'seeker', 'company', 'guest'
                $table->string('custom_class', 100)->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('site_settings')) {
            Schema::table('site_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('site_settings', 'footer_col1_title')) {
                    $table->string('footer_col1_title', 150)->nullable()->default('Quick Links');
                }
                if (!Schema::hasColumn('site_settings', 'footer_col2_title')) {
                    $table->string('footer_col2_title', 150)->nullable()->default('Jobs By Functional Area');
                }
                if (!Schema::hasColumn('site_settings', 'footer_col3_title')) {
                    $table->string('footer_col3_title', 150)->nullable()->default('Jobs By Industry');
                }
                if (!Schema::hasColumn('site_settings', 'footer_col4_title')) {
                    $table->string('footer_col4_title', 150)->nullable()->default('Contact Us');
                }
                if (!Schema::hasColumn('site_settings', 'footer_show_popular_cities')) {
                    $table->tinyInteger('footer_show_popular_cities')->default(1);
                }
                if (!Schema::hasColumn('site_settings', 'footer_show_payment_icons')) {
                    $table->tinyInteger('footer_show_payment_icons')->default(1);
                }
                if (!Schema::hasColumn('site_settings', 'footer_copyright_text')) {
                    $table->text('footer_copyright_text')->nullable();
                }
                if (!Schema::hasColumn('site_settings', 'header_show_post_job')) {
                    $table->tinyInteger('header_show_post_job')->default(1);
                }
                if (!Schema::hasColumn('site_settings', 'header_show_notifications')) {
                    $table->tinyInteger('header_show_notifications')->default(1);
                }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('site_menu_items');
        if (Schema::hasTable('site_settings')) {
            Schema::table('site_settings', function (Blueprint $table) {
                $columns = [
                    'footer_col1_title', 'footer_col2_title', 'footer_col3_title', 'footer_col4_title',
                    'footer_show_popular_cities', 'footer_show_payment_icons', 'footer_copyright_text',
                    'header_show_post_job', 'header_show_notifications'
                ];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('site_settings', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
}
