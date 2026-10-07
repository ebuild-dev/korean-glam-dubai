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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id(); // Crée un identifiant unique pour chaque blog
            $table->string('titre'); // Colonne pour le titre
            $table->text('description'); // Colonne pour la description
            $table->string('photo')->nullable(); // Colonne pour la photo (nullable si optionnel)
            $table->timestamp('date')->nullable(); // Colonne pour la date du blog, nullable si non obligatoire
            $table->timestamps(); // Crée les colonnes created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
