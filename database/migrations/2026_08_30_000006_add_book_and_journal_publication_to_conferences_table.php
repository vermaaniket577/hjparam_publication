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
            if (!Schema::hasColumn('conferences', 'book_publication_title')) {
                $table->string('book_publication_title')->nullable()->after('publication_info');
            }
            if (!Schema::hasColumn('conferences', 'book_publication_author')) {
                $table->string('book_publication_author')->nullable()->after('book_publication_title');
            }
            if (!Schema::hasColumn('conferences', 'book_publication_abstract')) {
                $table->text('book_publication_abstract')->nullable()->after('book_publication_author');
            }
            if (!Schema::hasColumn('conferences', 'book_publication_content')) {
                $table->longText('book_publication_content')->nullable()->after('book_publication_abstract');
            }
            if (!Schema::hasColumn('conferences', 'journal_publication_title')) {
                $table->string('journal_publication_title')->nullable()->after('book_publication_content');
            }
            if (!Schema::hasColumn('conferences', 'journal_publication_content')) {
                $table->longText('journal_publication_content')->nullable()->after('journal_publication_title');
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
                'book_publication_title',
                'book_publication_author',
                'book_publication_abstract',
                'book_publication_content',
                'journal_publication_title',
                'journal_publication_content',
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('conferences', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
