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
        Schema::create('sous_souscategories', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->integer('order_column')->nullable();

            // Relations
            $table->unsignedBigInteger('id_categorie')->nullable();
            $table->unsignedBigInteger('id_produit')->nullable();
            $table->unsignedBigInteger('id_souscategorie')->nullable();

            // Clés étrangères
            $table->foreign('id_categorie')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('id_produit')->references('id')->on('produits')->onDelete('cascade');
            $table->foreign('id_souscategorie')->references('id')->on('souscategories')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sous_souscategories');
    }
};
