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
        Schema::create('conferences', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->string('venue')->nullable();
            $table->string('city')->nullable();
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('topics')->onDelete('set null');
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
            $table->string('organizer_name')->nullable();
            $table->string('external_link')->nullable();
            $table->string('banner_image')->nullable();
            $table->enum('type', ['online', 'offline', 'hybrid'])->default('offline');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('is_featured')->default(false);
            $table->dateTime('early_bird_deadline')->nullable();
            $table->boolean('invitation_letter_support')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conferences');
    }
};
