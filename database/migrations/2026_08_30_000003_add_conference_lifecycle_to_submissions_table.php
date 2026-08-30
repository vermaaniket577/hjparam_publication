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
            if (!Schema::hasColumn('submissions', 'article_type')) {
                $table->string('article_type')->nullable()->after('title');
            }
            if (!Schema::hasColumn('submissions', 'scholars_data')) {
                $table->json('scholars_data')->nullable()->after('abstract');
            }
            if (!Schema::hasColumn('submissions', 'editor_id')) {
                $table->foreignId('editor_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('submissions', 'revision_comments')) {
                $table->text('revision_comments')->nullable()->after('status');
            }
            if (!Schema::hasColumn('submissions', 'payment_status')) {
                $table->enum('payment_status', ['unpaid', 'pending_verification', 'verified', 'rejected'])->default('unpaid')->after('revision_comments');
            }
            if (!Schema::hasColumn('submissions', 'payment_fee_type')) {
                $table->string('payment_fee_type')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('submissions', 'payment_amount')) {
                $table->string('payment_amount')->nullable()->after('payment_fee_type');
            }
            if (!Schema::hasColumn('submissions', 'payment_transaction_id')) {
                $table->string('payment_transaction_id')->nullable()->after('payment_amount');
            }
            if (!Schema::hasColumn('submissions', 'payment_receipt_path')) {
                $table->string('payment_receipt_path')->nullable()->after('payment_transaction_id');
            }
            if (!Schema::hasColumn('submissions', 'payment_date')) {
                $table->date('payment_date')->nullable()->after('payment_receipt_path');
            }
            if (!Schema::hasColumn('submissions', 'conference_link')) {
                $table->string('conference_link')->nullable()->after('payment_date');
            }
            if (!Schema::hasColumn('submissions', 'presentation_day')) {
                $table->string('presentation_day')->nullable()->after('conference_link');
            }
            if (!Schema::hasColumn('submissions', 'presentation_time')) {
                $table->string('presentation_time')->nullable()->after('presentation_day');
            }
            if (!Schema::hasColumn('submissions', 'attendance_status')) {
                $table->enum('attendance_status', ['not_marked', 'present', 'absent'])->default('not_marked')->after('presentation_time');
            }
            if (!Schema::hasColumn('submissions', 'presentation_status')) {
                $table->enum('presentation_status', ['pending', 'presented', 'no_show'])->default('pending')->after('attendance_status');
            }
            if (!Schema::hasColumn('submissions', 'certificate_attendee_code')) {
                $table->string('certificate_attendee_code')->nullable()->unique()->after('presentation_status');
            }
            if (!Schema::hasColumn('submissions', 'certificate_presentation_code')) {
                $table->string('certificate_presentation_code')->nullable()->unique()->after('certificate_attendee_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $columns = [
                'article_type', 'scholars_data', 'editor_id', 'revision_comments',
                'payment_status', 'payment_fee_type', 'payment_amount', 'payment_transaction_id',
                'payment_receipt_path', 'payment_date', 'conference_link', 'presentation_day',
                'presentation_time', 'attendance_status', 'presentation_status',
                'certificate_attendee_code', 'certificate_presentation_code'
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('submissions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
