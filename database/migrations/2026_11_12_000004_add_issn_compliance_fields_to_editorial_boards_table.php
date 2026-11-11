<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add ISSN compliance fields required by ISSN National Centre:
     * Institutional designation, Department, Complete official postal address, Country, and is_international flag.
     */
    public function up(): void
    {
        Schema::table('editorial_boards', function (Blueprint $table) {
            if (!Schema::hasColumn('editorial_boards', 'designation')) {
                $table->string('designation', 255)->nullable()->after('name');
            }
            if (!Schema::hasColumn('editorial_boards', 'department')) {
                $table->string('department', 255)->nullable()->after('designation');
            }
            if (!Schema::hasColumn('editorial_boards', 'postal_address')) {
                $table->text('postal_address')->nullable()->after('affiliation');
            }
            if (!Schema::hasColumn('editorial_boards', 'country')) {
                $table->string('country', 150)->default('India')->after('postal_address');
            }
            if (!Schema::hasColumn('editorial_boards', 'is_international')) {
                $table->boolean('is_international')->default(false)->after('country');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('editorial_boards', function (Blueprint $table) {
            $table->dropColumn(['designation', 'department', 'postal_address', 'country', 'is_international']);
        });
    }
};
