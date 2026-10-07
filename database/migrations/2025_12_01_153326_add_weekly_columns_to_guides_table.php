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
        Schema::table('guides', function (Blueprint $table) {
            $table->string('weekly_exfoliator')->nullable();
            $table->string('weekly_face_mask')->nullable();
            $table->string('weekly_wash_mask')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guides', function (Blueprint $table) {
            $table->dropColumn(['weekly_exfoliator', 'weekly_face_mask', 'weekly_wash_mask']);

        });
    }
};
