<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeoAndPublishingFieldsToBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blogs', function (Blueprint $table) {
            if (!Schema::hasColumn('blogs', 'is_published')) {
                $table->tinyInteger('is_published')->default(1)->after('featured');
            }
            if (!Schema::hasColumn('blogs', 'focus_keyword')) {
                $table->string('focus_keyword', 255)->nullable()->after('is_published');
            }
            if (!Schema::hasColumn('blogs', 'canonical_url')) {
                $table->string('canonical_url', 500)->nullable()->after('meta_descriptions');
            }
            if (!Schema::hasColumn('blogs', 'robots_index')) {
                $table->string('robots_index', 20)->default('index')->after('canonical_url');
            }
            if (!Schema::hasColumn('blogs', 'robots_follow')) {
                $table->string('robots_follow', 20)->default('follow')->after('robots_index');
            }
            if (!Schema::hasColumn('blogs', 'og_title')) {
                $table->string('og_title', 255)->nullable()->after('robots_follow');
            }
            if (!Schema::hasColumn('blogs', 'og_description')) {
                $table->text('og_description')->nullable()->after('og_title');
            }
            if (!Schema::hasColumn('blogs', 'og_image')) {
                $table->string('og_image', 255)->nullable()->after('og_description');
            }
            if (!Schema::hasColumn('blogs', 'twitter_card')) {
                $table->string('twitter_card', 50)->default('summary_large_image')->after('og_image');
            }
            if (!Schema::hasColumn('blogs', 'twitter_title')) {
                $table->string('twitter_title', 255)->nullable()->after('twitter_card');
            }
            if (!Schema::hasColumn('blogs', 'twitter_description')) {
                $table->text('twitter_description')->nullable()->after('twitter_title');
            }
            if (!Schema::hasColumn('blogs', 'twitter_image')) {
                $table->string('twitter_image', 255)->nullable()->after('twitter_description');
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
        Schema::table('blogs', function (Blueprint $table) {
            $columns = [
                'is_published',
                'focus_keyword',
                'canonical_url',
                'robots_index',
                'robots_follow',
                'og_title',
                'og_description',
                'og_image',
                'twitter_card',
                'twitter_title',
                'twitter_description',
                'twitter_image'
            ];
            foreach ($columns as $col) {
                if (Schema::hasColumn('blogs', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
}
