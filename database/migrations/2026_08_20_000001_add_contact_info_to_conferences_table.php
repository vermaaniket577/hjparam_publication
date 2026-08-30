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
        Schema::table('conferences', function (Blueprint $table) {
            if (!Schema::hasColumn('conferences', 'contact_email')) {
                $table->string('contact_email')->nullable()->after('organizer_name');
            }
            if (!Schema::hasColumn('conferences', 'contact_phone')) {
                $table->string('contact_phone')->nullable()->after('contact_email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conferences', function (Blueprint $table) {
            if (Schema::hasColumn('conferences', 'contact_email')) {
                $table->dropColumn('contact_email');
            }
            if (Schema::hasColumn('conferences', 'contact_phone')) {
                $table->dropColumn('contact_phone');
            }
        });
    }
};
