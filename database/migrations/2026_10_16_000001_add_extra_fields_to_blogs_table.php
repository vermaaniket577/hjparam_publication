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
        Schema::table('blogs', function (Blueprint $table) {
            if (!Schema::hasColumn('blogs', 'category')) {
                $table->string('category')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('blogs', 'tags')) {
                $table->string('tags')->nullable()->after('summary');
            }
            if (!Schema::hasColumn('blogs', 'author_name')) {
                $table->string('author_name')->nullable()->after('author_id');
            }
            if (!Schema::hasColumn('blogs', 'views_count')) {
                $table->unsignedBigInteger('views_count')->default(0)->after('is_published');
            }
        });

        // Ensure author_id is nullable if allowed or keep foreign key
        try {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE blogs MODIFY author_id BIGINT UNSIGNED NULL");
        } catch (\Throwable $e) {}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            if (Schema::hasColumn('blogs', 'category')) {
                $table->dropColumn('category');
            }
            if (Schema::hasColumn('blogs', 'tags')) {
                $table->dropColumn('tags');
            }
            if (Schema::hasColumn('blogs', 'author_name')) {
                $table->dropColumn('author_name');
            }
            if (Schema::hasColumn('blogs', 'views_count')) {
                $table->dropColumn('views_count');
            }
        });
    }
};
