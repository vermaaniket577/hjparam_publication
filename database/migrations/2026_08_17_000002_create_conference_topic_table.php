<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('conference_topic')) {
            Schema::create('conference_topic', function (Blueprint $table) {
                $table->id();
                $table->foreignId('conference_id')->constrained('conferences')->onDelete('cascade');
                $table->foreignId('topic_id')->constrained('topics')->onDelete('cascade');
                $table->unique(['conference_id', 'topic_id']);
                $table->timestamps();
            });

            // Migrate existing single category_id data into the new pivot table
            if (Schema::hasTable('conferences') && Schema::hasColumn('conferences', 'category_id')) {
                $conferences = DB::table('conferences')->whereNotNull('category_id')->get();
                foreach ($conferences as $conf) {
                    $topicExists = DB::table('topics')->where('id', $conf->category_id)->exists();
                    if ($topicExists) {
                        DB::table('conference_topic')->insertOrIgnore([
                            'conference_id' => $conf->id,
                            'topic_id' => $conf->category_id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conference_topic');
    }
};
