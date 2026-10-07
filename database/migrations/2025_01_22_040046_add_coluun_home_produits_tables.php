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
        //configs
        Schema::table('configs', function (Blueprint $table) {
            $table->string('home_text_1')->nullable();
            $table->string('home_lien_1')->nullable();
            $table->string('home_photo_1')->nullable();
            $table->string('home_text_2')->nullable();
            $table->string('home_lien_2')->nullable();
            $table->string('home_photo_2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
