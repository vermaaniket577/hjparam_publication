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
        if (Schema::hasTable('submissions')) {
            Schema::table('submissions', function (Blueprint $table) {
                if (!Schema::hasColumn('submissions', 'conference_id')) {
                    $table->foreignId('conference_id')->nullable()->after('journal_id')->constrained('conferences')->nullOnDelete();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('submissions')) {
            Schema::table('submissions', function (Blueprint $table) {
                if (Schema::hasColumn('submissions', 'conference_id')) {
                    $table->dropForeign(['conference_id']);
                    $table->dropColumn('conference_id');
                }
            });
        }
    }
};
