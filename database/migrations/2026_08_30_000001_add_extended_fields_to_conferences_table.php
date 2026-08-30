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
            if (!Schema::hasColumn('conferences', 'aim_scope')) {
                $table->longText('aim_scope')->nullable()->after('description');
            }
            if (!Schema::hasColumn('conferences', 'guidelines')) {
                $table->longText('guidelines')->nullable()->after('aim_scope');
            }
            if (!Schema::hasColumn('conferences', 'paper_format_1')) {
                $table->string('paper_format_1')->nullable()->after('guidelines');
            }
            if (!Schema::hasColumn('conferences', 'paper_format_2')) {
                $table->string('paper_format_2')->nullable()->after('paper_format_1');
            }
            if (!Schema::hasColumn('conferences', 'fee_attendee')) {
                $table->string('fee_attendee')->nullable()->after('paper_format_2');
            }
            if (!Schema::hasColumn('conferences', 'fee_presentation')) {
                $table->string('fee_presentation')->nullable()->after('fee_attendee');
            }
            if (!Schema::hasColumn('conferences', 'fee_publication')) {
                $table->string('fee_publication')->nullable()->after('fee_presentation');
            }
            if (!Schema::hasColumn('conferences', 'fee_extra_certificate')) {
                $table->string('fee_extra_certificate')->nullable()->after('fee_publication');
            }
            if (!Schema::hasColumn('conferences', 'custom_fees')) {
                $table->json('custom_fees')->nullable()->after('fee_extra_certificate');
            }
            if (!Schema::hasColumn('conferences', 'committee_members')) {
                $table->json('committee_members')->nullable()->after('custom_fees');
            }
            if (!Schema::hasColumn('conferences', 'abstract_submission_start_date')) {
                $table->date('abstract_submission_start_date')->nullable()->after('committee_members');
            }
            if (!Schema::hasColumn('conferences', 'abstract_submission_end_date')) {
                $table->date('abstract_submission_end_date')->nullable()->after('abstract_submission_start_date');
            }
            if (!Schema::hasColumn('conferences', 'paper_submission_start_date')) {
                $table->date('paper_submission_start_date')->nullable()->after('abstract_submission_end_date');
            }
            if (!Schema::hasColumn('conferences', 'paper_submission_end_date')) {
                $table->date('paper_submission_end_date')->nullable()->after('paper_submission_start_date');
            }
            if (!Schema::hasColumn('conferences', 'registration_start_date')) {
                $table->date('registration_start_date')->nullable()->after('paper_submission_end_date');
            }
            if (!Schema::hasColumn('conferences', 'registration_end_date')) {
                $table->date('registration_end_date')->nullable()->after('registration_start_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conferences', function (Blueprint $table) {
            $columns = [
                'aim_scope', 'guidelines', 'paper_format_1', 'paper_format_2',
                'fee_attendee', 'fee_presentation', 'fee_publication', 'fee_extra_certificate',
                'custom_fees', 'committee_members',
                'abstract_submission_start_date', 'abstract_submission_end_date',
                'paper_submission_start_date', 'paper_submission_end_date',
                'registration_start_date', 'registration_end_date'
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('conferences', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
