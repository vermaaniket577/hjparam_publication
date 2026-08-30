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
            if (!Schema::hasColumn('conferences', 'venue_details')) {
                $table->text('venue_details')->nullable()->after('venue');
            }
            if (!Schema::hasColumn('conferences', 'meeting_link')) {
                $table->string('meeting_link')->nullable()->after('venue_details');
            }
            if (!Schema::hasColumn('conferences', 'publication_info')) {
                $table->longText('publication_info')->nullable()->after('meeting_link');
            }
            if (!Schema::hasColumn('conferences', 'paper_template')) {
                $table->string('paper_template')->nullable()->after('publication_info');
            }
            if (!Schema::hasColumn('conferences', 'sample_certificate')) {
                $table->string('sample_certificate')->nullable()->after('paper_template');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conferences', function (Blueprint $table) {
            $columns = ['venue_details', 'meeting_link', 'publication_info', 'paper_template', 'sample_certificate'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('conferences', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
