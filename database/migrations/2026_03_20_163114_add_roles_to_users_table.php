<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Already added in previous migration as string, 
            // but we can ensure it has a default for our new roles if needed.
            // For now, we'll just leave it or modify it if it's missing.
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('subscriber');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
