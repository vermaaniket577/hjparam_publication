<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('editorial_boards', function (Blueprint $table) {
            if (!Schema::hasColumn('editorial_boards', 'editor_uid')) {
                $table->string('editor_uid', 64)->nullable()->index()->after('id');
            }
        });

        try {
            DB::statement("UPDATE editorial_boards SET editor_uid = CONCAT('ed_', id) WHERE editor_uid IS NULL OR editor_uid = ''");
        } catch (\Throwable $e) {}
    }

    public function down(): void
    {
        Schema::table('editorial_boards', function (Blueprint $table) {
            if (Schema::hasColumn('editorial_boards', 'editor_uid')) {
                $table->dropColumn('editor_uid');
            }
        });
    }
};

