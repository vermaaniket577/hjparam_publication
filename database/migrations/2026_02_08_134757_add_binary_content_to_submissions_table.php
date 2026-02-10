<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->string('filename')->nullable()->after('file_path');
            $table->string('extension', 10)->nullable()->after('filename');
            $table->string('mime_type')->nullable()->after('extension');
            $table->unsignedBigInteger('file_size')->nullable()->after('mime_type');
        });

        // Use raw SQL for LONGBLOB
        DB::statement("ALTER TABLE submissions ADD binary_content LONGBLOB AFTER file_size");
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn(['filename', 'extension', 'mime_type', 'file_size', 'binary_content']);
        });
    }
};
