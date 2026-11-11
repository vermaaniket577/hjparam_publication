<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('editorial_boards', function (Blueprint $table) {
            $table->string('role', 500)->change();
        });
    }

    public function down(): void
    {
        Schema::table('editorial_boards', function (Blueprint $table) {
            $table->string('role', 100)->change();
        });
    }
};
