<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('volumes', function (Blueprint $table) {
            if (!Schema::hasColumn('volumes', 'month')) {
                $table->string('month', 50)->nullable()->after('volume_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('volumes', function (Blueprint $table) {
            if (Schema::hasColumn('volumes', 'month')) {
                $table->dropColumn('month');
            }
        });
    }
};
