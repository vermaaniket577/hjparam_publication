<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('articles')) {
            Schema::table('articles', function (Blueprint $table) {
                if (!Schema::hasColumn('articles', 'manuscript_path')) {
                    $table->string('manuscript_path')->nullable()->after('pdf_path');
                }
                if (!Schema::hasColumn('articles', 'original_filename')) {
                    $table->string('original_filename')->nullable()->after('manuscript_path');
                }
                if (!Schema::hasColumn('articles', 'file_extension')) {
                    $table->string('file_extension', 16)->nullable()->after('original_filename');
                }
                if (!Schema::hasColumn('articles', 'file_size')) {
                    $table->unsignedBigInteger('file_size')->nullable()->after('file_extension');
                }
                if (!Schema::hasColumn('articles', 'mime_type')) {
                    $table->string('mime_type')->nullable()->after('file_size');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('articles')) {
            Schema::table('articles', function (Blueprint $table) {
                $columns = ['manuscript_path', 'original_filename', 'file_extension', 'file_size', 'mime_type'];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('articles', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
