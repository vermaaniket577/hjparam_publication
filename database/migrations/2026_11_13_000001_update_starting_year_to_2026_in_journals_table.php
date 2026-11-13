<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Update starting year for every journal to 2026.
     */
    public function up(): void
    {
        if (Schema::hasColumn('journals', 'starting_year')) {
            try {
                DB::statement("ALTER TABLE `journals` MODIFY `starting_year` SMALLINT UNSIGNED NOT NULL DEFAULT 2026");
            } catch (\Throwable $e) {}

            try {
                DB::statement("UPDATE `journals` SET `starting_year` = 2026");
            } catch (\Throwable $e) {}
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('journals', 'starting_year')) {
            try {
                DB::statement("ALTER TABLE `journals` MODIFY `starting_year` SMALLINT UNSIGNED NOT NULL DEFAULT 2024");
            } catch (\Throwable $e) {}
        }
    }
};
