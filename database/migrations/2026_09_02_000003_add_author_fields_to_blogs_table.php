<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuthorFieldsToBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blogs', function (Blueprint $table) {
            if (!Schema::hasColumn('blogs', 'author_name')) {
                $table->string('author_name', 255)->nullable()->default('Abhishek Sharma')->after('lang');
            }
            if (!Schema::hasColumn('blogs', 'author_title')) {
                $table->string('author_title', 255)->nullable()->default('Career Consultant & Lead Editor')->after('author_name');
            }
            if (!Schema::hasColumn('blogs', 'author_bio')) {
                $table->text('author_bio')->nullable()->after('author_title');
            }
            if (!Schema::hasColumn('blogs', 'author_avatar')) {
                $table->string('author_avatar', 255)->nullable()->after('author_bio');
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
            $columns = ['author_name', 'author_title', 'author_bio', 'author_avatar'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('blogs', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
}
