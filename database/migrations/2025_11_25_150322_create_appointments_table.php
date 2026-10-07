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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->string('telephone');
            $table->string('file')->nullable(); // fichier uploadé
            $table->text('notes')->nullable(); // notes internes
            $table->boolean('availability')->default(false); // dispo oui/non
            $table->dateTime('date_appointment'); // date du rendez-vous
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
