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
        Schema::table('submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('submissions', 'copyright_file_path')) {
                $table->string('copyright_file_path')->nullable()->after('file_path');
            }
            if (!Schema::hasColumn('submissions', 'copyright_filename')) {
                $table->string('copyright_filename')->nullable()->after('copyright_file_path');
            }
            if (!Schema::hasColumn('submissions', 'copyright_uploaded_at')) {
                $table->timestamp('copyright_uploaded_at')->nullable()->after('copyright_filename');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            if (Schema::hasColumn('submissions', 'copyright_file_path')) {
                $table->dropColumn('copyright_file_path');
            }
            if (Schema::hasColumn('submissions', 'copyright_filename')) {
                $table->dropColumn('copyright_filename');
            }
            if (Schema::hasColumn('submissions', 'copyright_uploaded_at')) {
                $table->dropColumn('copyright_uploaded_at');
            }
        });
    }
};
