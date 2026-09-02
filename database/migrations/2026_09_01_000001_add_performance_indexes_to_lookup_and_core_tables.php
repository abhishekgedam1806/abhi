<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddPerformanceIndexesToLookupAndCoreTables extends Migration
{
    /**
     * Helper to safely add index if not already present
     */
    protected function addIndexSafely(string $table, string $indexName, array $columns)
    {
        if (!Schema::hasTable($table)) return;

        $existingIndexes = collect(DB::select("SHOW INDEX FROM `{$table}`"))->pluck('Key_name')->toArray();
        if (!in_array($indexName, $existingIndexes)) {
            Schema::table($table, function (Blueprint $tableBlueprint) use ($columns, $indexName) {
                $tableBlueprint->index($columns, $indexName);
            });
        }
    }

    /**
     * Helper to safely drop index if exists
     */
    protected function dropIndexSafely(string $table, string $indexName)
    {
        if (!Schema::hasTable($table)) return;

        $existingIndexes = collect(DB::select("SHOW INDEX FROM `{$table}`"))->pluck('Key_name')->toArray();
        if (in_array($indexName, $existingIndexes)) {
            Schema::table($table, function (Blueprint $tableBlueprint) use ($indexName) {
                $tableBlueprint->dropIndex($indexName);
            });
        }
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. CITIES (48,663 rows) - Crucial for location lookups
        $this->addIndexSafely('cities', 'idx_cities_city_id', ['city_id']);
        $this->addIndexSafely('cities', 'idx_cities_state_id', ['state_id']);
        $this->addIndexSafely('cities', 'idx_cities_lang', ['lang']);
        $this->addIndexSafely('cities', 'idx_cities_is_active', ['is_active']);
        $this->addIndexSafely('cities', 'idx_cities_composite', ['city_id', 'lang', 'is_active']);

        // 2. STATES (4,121 rows)
        $this->addIndexSafely('states', 'idx_states_state_id', ['state_id']);
        $this->addIndexSafely('states', 'idx_states_country_id', ['country_id']);
        $this->addIndexSafely('states', 'idx_states_lang', ['lang']);
        $this->addIndexSafely('states', 'idx_states_is_active', ['is_active']);
        $this->addIndexSafely('states', 'idx_states_composite', ['state_id', 'lang', 'is_active']);

        // 3. COUNTRIES
        $this->addIndexSafely('countries', 'idx_countries_country_id', ['country_id']);
        $this->addIndexSafely('countries', 'idx_countries_lang', ['lang']);
        $this->addIndexSafely('countries', 'idx_countries_is_active', ['is_active']);

        // 4. FUNCTIONAL AREAS (Categories)
        $this->addIndexSafely('functional_areas', 'idx_fa_functional_area_id', ['functional_area_id']);
        $this->addIndexSafely('functional_areas', 'idx_fa_lang', ['lang']);
        $this->addIndexSafely('functional_areas', 'idx_fa_is_active', ['is_active']);

        // 5. JOB SKILLS
        $this->addIndexSafely('job_skills', 'idx_js_job_skill_id', ['job_skill_id']);
        $this->addIndexSafely('job_skills', 'idx_js_lang', ['lang']);
        $this->addIndexSafely('job_skills', 'idx_js_is_active', ['is_active']);

        // 6. MANAGE JOB SKILLS (Pivot table for skill filtering)
        $this->addIndexSafely('manage_job_skills', 'idx_mjs_job_id', ['job_id']);
        $this->addIndexSafely('manage_job_skills', 'idx_mjs_skill_id', ['job_skill_id']);
        $this->addIndexSafely('manage_job_skills', 'idx_mjs_composite', ['job_id', 'job_skill_id']);

        // 7. COMPANIES (Foreign Keys)
        $this->addIndexSafely('companies', 'idx_comp_city_id', ['city_id']);
        $this->addIndexSafely('companies', 'idx_comp_state_id', ['state_id']);
        $this->addIndexSafely('companies', 'idx_comp_country_id', ['country_id']);
        $this->addIndexSafely('companies', 'idx_comp_industry_id', ['industry_id']);

        // 8. USERS (Candidate Filtering)
        $this->addIndexSafely('users', 'idx_users_city_id', ['city_id']);
        $this->addIndexSafely('users', 'idx_users_state_id', ['state_id']);
        $this->addIndexSafely('users', 'idx_users_country_id', ['country_id']);
        $this->addIndexSafely('users', 'idx_users_is_active', ['is_active']);
        $this->addIndexSafely('users', 'idx_users_functional_area_id', ['functional_area_id']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropIndexSafely('cities', 'idx_cities_city_id');
        $this->dropIndexSafely('cities', 'idx_cities_state_id');
        $this->dropIndexSafely('cities', 'idx_cities_lang');
        $this->dropIndexSafely('cities', 'idx_cities_is_active');
        $this->dropIndexSafely('cities', 'idx_cities_composite');

        $this->dropIndexSafely('states', 'idx_states_state_id');
        $this->dropIndexSafely('states', 'idx_states_country_id');
        $this->dropIndexSafely('states', 'idx_states_lang');
        $this->dropIndexSafely('states', 'idx_states_is_active');
        $this->dropIndexSafely('states', 'idx_states_composite');

        $this->dropIndexSafely('countries', 'idx_countries_country_id');
        $this->dropIndexSafely('countries', 'idx_countries_lang');
        $this->dropIndexSafely('countries', 'idx_countries_is_active');

        $this->dropIndexSafely('functional_areas', 'idx_fa_functional_area_id');
        $this->dropIndexSafely('functional_areas', 'idx_fa_lang');
        $this->dropIndexSafely('functional_areas', 'idx_fa_is_active');

        $this->dropIndexSafely('job_skills', 'idx_js_job_skill_id');
        $this->dropIndexSafely('job_skills', 'idx_js_lang');
        $this->dropIndexSafely('job_skills', 'idx_js_is_active');

        $this->dropIndexSafely('manage_job_skills', 'idx_mjs_job_id');
        $this->dropIndexSafely('manage_job_skills', 'idx_mjs_skill_id');
        $this->dropIndexSafely('manage_job_skills', 'idx_mjs_composite');

        $this->dropIndexSafely('companies', 'idx_comp_city_id');
        $this->dropIndexSafely('companies', 'idx_comp_state_id');
        $this->dropIndexSafely('companies', 'idx_comp_country_id');
        $this->dropIndexSafely('companies', 'idx_comp_industry_id');

        $this->dropIndexSafely('users', 'idx_users_city_id');
        $this->dropIndexSafely('users', 'idx_users_state_id');
        $this->dropIndexSafely('users', 'idx_users_country_id');
        $this->dropIndexSafely('users', 'idx_users_is_active');
        $this->dropIndexSafely('users', 'idx_users_functional_area_id');
    }
}
