<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->string('mobile_number', 30);
            $table->string('affiliation')->nullable();
            $table->string('paper_title');
            $table->decimal('payment_amount', 10, 2);
            $table->string('payment_method', 30);
            $table->string('transaction_id');
            $table->string('screenshot_path');
            $table->string('screenshot_original_name')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('admin_note')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['email', 'transaction_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_submissions');
    }
};
