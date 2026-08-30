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
        if (Schema::hasTable('payment_settings')) {
            Schema::table('payment_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('payment_settings', 'journal_fee_amount')) {
                    $table->decimal('journal_fee_amount', 10, 2)->default(1500.00)->after('default_amount');
                }
                if (!Schema::hasColumn('payment_settings', 'conference_fee_amount')) {
                    $table->decimal('conference_fee_amount', 10, 2)->default(2500.00)->after('journal_fee_amount');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('payment_settings')) {
            Schema::table('payment_settings', function (Blueprint $table) {
                if (Schema::hasColumn('payment_settings', 'journal_fee_amount')) {
                    $table->dropColumn('journal_fee_amount');
                }
                if (Schema::hasColumn('payment_settings', 'conference_fee_amount')) {
                    $table->dropColumn('conference_fee_amount');
                }
            });
        }
    }
};
