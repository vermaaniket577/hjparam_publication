<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('articles') && !Schema::hasColumn('articles', 'user_id')) {
            Schema::table('articles', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('submission_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('articles') && Schema::hasColumn('articles', 'user_id')) {
            Schema::table('articles', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }
    }
};
