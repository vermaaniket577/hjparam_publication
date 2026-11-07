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
        if (!Schema::hasTable('certificates')) {
            Schema::create('certificates', function (Blueprint $table) {
                $table->id();
                $table->string('certificate_number')->unique();
                $table->string('type')->default('publication'); // publication, attendee, presentation
                $table->foreignId('submission_id')->nullable()->constrained('submissions')->nullOnDelete();
                $table->foreignId('article_id')->nullable()->constrained('articles')->nullOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                
                // Recipient details
                $table->string('recipient_name');
                $table->string('recipient_role')->default('author'); // author, co_author, attendee, presenter
                $table->string('recipient_email')->nullable();
                $table->string('designation')->nullable();
                $table->string('department')->nullable();
                $table->string('institution')->nullable();
                $table->string('city_country')->nullable();
                
                // Publication details snapshot
                $table->string('manuscript_id')->nullable();
                $table->text('paper_title');
                $table->string('journal_name')->nullable();
                $table->string('volume')->nullable();
                $table->string('issue')->nullable();
                $table->string('month_year')->nullable();
                $table->string('pages')->nullable();
                $table->string('issn')->nullable();
                $table->string('doi')->nullable();
                $table->date('issue_date')->nullable();
                
                // Verification / security
                $table->string('verification_hash')->nullable()->unique();
                
                $table->timestamps();

                $table->index(['type', 'recipient_role']);
                $table->index(['submission_id', 'article_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
