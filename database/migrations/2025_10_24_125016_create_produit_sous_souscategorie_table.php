<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('produit_sous_souscategorie', function (Blueprint $table) {
            $table->id();

            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->foreignId('sous_souscategorie_id')->constrained('sous_souscategories')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produit_sous_souscategorie');
    }
};
