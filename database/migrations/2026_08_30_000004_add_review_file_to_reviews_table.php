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
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'review_file_path')) {
                $table->string('review_file_path')->nullable()->after('comments');
            }
            if (!Schema::hasColumn('reviews', 'role_type')) {
                $table->string('role_type')->default('reviewer')->after('reviewer_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'review_file_path')) {
                $table->dropColumn('review_file_path');
            }
            if (Schema::hasColumn('reviews', 'role_type')) {
                $table->dropColumn('role_type');
            }
        });
    }
};
