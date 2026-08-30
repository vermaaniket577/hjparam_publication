<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('payment_settings')) {
            Schema::table('payment_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('payment_settings', 'online_payment_enabled')) {
                    $table->boolean('online_payment_enabled')->default(true)->after('default_amount');
                }
                if (!Schema::hasColumn('payment_settings', 'journal_fee_amount')) {
                    $table->decimal('journal_fee_amount', 10, 2)->default(1500)->after('online_payment_enabled');
                }
                if (!Schema::hasColumn('payment_settings', 'conference_fee_amount')) {
                    $table->decimal('conference_fee_amount', 10, 2)->default(2500)->after('journal_fee_amount');
                }
                if (!Schema::hasColumn('payment_settings', 'gateway_provider')) {
                    $table->string('gateway_provider', 50)->default('razorpay')->after('conference_fee_amount');
                }
                if (!Schema::hasColumn('payment_settings', 'currency')) {
                    $table->string('currency', 10)->default('INR')->after('gateway_provider');
                }
                if (!Schema::hasColumn('payment_settings', 'gateway_key_id')) {
                    $table->string('gateway_key_id')->nullable()->after('currency');
                }
                if (!Schema::hasColumn('payment_settings', 'gateway_key_secret')) {
                    $table->string('gateway_key_secret')->nullable()->after('gateway_key_id');
                }
                if (!Schema::hasColumn('payment_settings', 'gateway_merchant_id')) {
                    $table->string('gateway_merchant_id')->nullable()->after('gateway_key_secret');
                }
                if (!Schema::hasColumn('payment_settings', 'gateway_payment_link')) {
                    $table->string('gateway_payment_link', 1000)->nullable()->after('gateway_merchant_id');
                }
                if (!Schema::hasColumn('payment_settings', 'gateway_instructions')) {
                    $table->text('gateway_instructions')->nullable()->after('gateway_payment_link');
                }
            });
        }

        if (Schema::hasTable('payment_submissions')) {
            Schema::table('payment_submissions', function (Blueprint $table) {
                if (Schema::hasColumn('payment_submissions', 'screenshot_path')) {
                    $table->string('screenshot_path')->nullable()->change();
                }
                if (!Schema::hasColumn('payment_submissions', 'gateway_order_id')) {
                    $table->string('gateway_order_id')->nullable()->after('transaction_id');
                }
                if (!Schema::hasColumn('payment_submissions', 'gateway_payment_id')) {
                    $table->string('gateway_payment_id')->nullable()->after('gateway_order_id');
                }
                if (!Schema::hasColumn('payment_submissions', 'gateway_signature')) {
                    $table->string('gateway_signature')->nullable()->after('gateway_payment_id');
                }
                if (!Schema::hasColumn('payment_submissions', 'gateway_response')) {
                    $table->text('gateway_response')->nullable()->after('gateway_signature');
                }
                if (!Schema::hasColumn('payment_submissions', 'fee_type')) {
                    $table->string('fee_type', 50)->default('journal')->after('paper_title');
                }
                if (!Schema::hasColumn('payment_submissions', 'currency')) {
                    $table->string('currency', 10)->default('INR')->after('payment_amount');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('payment_settings')) {
            Schema::table('payment_settings', function (Blueprint $table) {
                $columns = [
                    'online_payment_enabled',
                    'gateway_provider',
                    'currency',
                    'gateway_key_id',
                    'gateway_key_secret',
                    'gateway_merchant_id',
                    'gateway_payment_link',
                    'gateway_instructions',
                ];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('payment_settings', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        if (Schema::hasTable('payment_submissions')) {
            Schema::table('payment_submissions', function (Blueprint $table) {
                $columns = [
                    'gateway_order_id',
                    'gateway_payment_id',
                    'gateway_signature',
                    'gateway_response',
                    'currency',
                ];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('payment_submissions', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
