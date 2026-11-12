<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        try {
            Schema::table('editorial_boards', function (Blueprint $table) {
                $table->string('role', 500)->change();
            });
        } catch (\Throwable $e) {
            try {
                DB::statement("ALTER TABLE `editorial_boards` MODIFY `role` VARCHAR(500)");
            } catch (\Throwable $e2) {}
        }
    }

    public function down(): void
    {
        try {
            Schema::table('editorial_boards', function (Blueprint $table) {
                $table->string('role', 100)->change();
            });
        } catch (\Throwable $e) {
            try {
                DB::statement("ALTER TABLE `editorial_boards` MODIFY `role` VARCHAR(100)");
            } catch (\Throwable $e2) {}
        }
    }
};

