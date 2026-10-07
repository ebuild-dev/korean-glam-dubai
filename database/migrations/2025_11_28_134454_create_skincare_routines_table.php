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
        Schema::create('skincare_routines', function (Blueprint $table) {
            $table->id();

            // Relation avec guides
            $table->foreignId('guide_id')
                ->constrained('guides')
                ->onDelete('cascade');

            // morning / night
            $table->enum('type', ['morning', 'night']);

            // Common steps
            $table->string('cleanser')->nullable();
            $table->string('toner')->nullable();
            $table->string('serum')->nullable();
            $table->string('eye_cream')->nullable();
            $table->string('cream')->nullable();
            $table->string('spf')->nullable();            // for morning
            $table->string('others')->nullable();

            // Night only
            $table->string('second_cleanser')->nullable();
            $table->string('spot_treatment')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skincare_routines');
    }
};
