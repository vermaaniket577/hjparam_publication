<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add ISSN preliminary details required by ISSN National Centre:
     * Format, Frequency, Subject, Language, Starting Year, and Publisher.
     */
    public function up(): void
    {
        Schema::table('journals', function (Blueprint $table) {
            if (!Schema::hasColumn('journals', 'format')) {
                $table->string('format', 100)->default('Online / Electronic (e-Journal)')->after('issn');
            }
            if (!Schema::hasColumn('journals', 'frequency')) {
                $table->string('frequency', 100)->default('Monthly')->after('format');
            }
            if (!Schema::hasColumn('journals', 'subject')) {
                $table->string('subject', 255)->nullable()->after('frequency');
            }
            if (!Schema::hasColumn('journals', 'language')) {
                $table->string('language', 150)->default('English, Hindi')->after('subject');
            }
            if (!Schema::hasColumn('journals', 'starting_year')) {
                $table->unsignedSmallInteger('starting_year')->default(2024)->after('language');
            }
            if (!Schema::hasColumn('journals', 'publisher')) {
                $table->string('publisher', 255)->default('HJPARAM Publication')->after('starting_year');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journals', function (Blueprint $table) {
            $table->dropColumn(['format', 'frequency', 'subject', 'language', 'starting_year', 'publisher']);
        });
    }
};
