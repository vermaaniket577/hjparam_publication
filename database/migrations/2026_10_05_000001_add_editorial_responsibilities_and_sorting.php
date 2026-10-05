<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('journals', function (Blueprint $table) {
            if (!Schema::hasColumn('journals', 'editorial_responsibilities')) {
                $table->longText('editorial_responsibilities')->nullable()->after('aims_and_scope');
            }
        });

        Schema::table('editorial_boards', function (Blueprint $table) {
            if (!Schema::hasColumn('editorial_boards', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('photo');
            }
            if (!Schema::hasColumn('editorial_boards', 'email')) {
                $table->string('email')->nullable()->after('affiliation');
            }
        });
    }

    public function down(): void
    {
        Schema::table('journals', function (Blueprint $table) {
            if (Schema::hasColumn('journals', 'editorial_responsibilities')) {
                $table->dropColumn('editorial_responsibilities');
            }
        });

        Schema::table('editorial_boards', function (Blueprint $table) {
            if (Schema::hasColumn('editorial_boards', 'sort_order')) {
                $table->dropColumn('sort_order');
            }
            if (Schema::hasColumn('editorial_boards', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
};
