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
            if (!Schema::hasColumn('conferences', 'city')) {
                $table->string('city')->nullable()->after('venue');
            }
            if (!Schema::hasColumn('conferences', 'organizer_name')) {
                $table->string('organizer_name')->nullable()->after('organizer_id');
            }
            if (!Schema::hasColumn('conferences', 'external_link')) {
                $table->string('external_link')->nullable()->after('organizer_name');
            }
            if (!Schema::hasColumn('conferences', 'banner_image')) {
                $table->string('banner_image')->nullable()->after('external_link');
            }
            if (!Schema::hasColumn('conferences', 'type')) {
                $table->enum('type', ['online', 'offline', 'hybrid'])->default('offline')->after('banner_image');
            }
            if (!Schema::hasColumn('conferences', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('type');
            }
            if (!Schema::hasColumn('conferences', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('status');
            }
            if (!Schema::hasColumn('conferences', 'early_bird_deadline')) {
                $table->dateTime('early_bird_deadline')->nullable()->after('is_featured');
            }
            if (!Schema::hasColumn('conferences', 'invitation_letter_support')) {
                $table->boolean('invitation_letter_support')->default(false)->after('early_bird_deadline');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conferences', function (Blueprint $table) {
            $columnsToDrop = [];
            $checkColumns = [
                'city', 'organizer_name', 'external_link', 'banner_image',
                'type', 'status', 'is_featured', 'early_bird_deadline', 'invitation_letter_support'
            ];
            foreach ($checkColumns as $column) {
                if (Schema::hasColumn('conferences', $column)) {
                    $columnsToDrop[] = $column;
                }
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
