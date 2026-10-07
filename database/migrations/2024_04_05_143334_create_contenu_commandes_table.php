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
        Schema::create('contenu_commandes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_commande');
            $table->unsignedBigInteger('id_produit')->nullable();
            $table->enum("type",["produit","pack"])->default("produit");
            $table->integer("quantite");
            $table->decimal('prix_unitaire', 13, 3);
            $table->decimal('benefice', 13, 3);
            $table->decimal('prix_total', 13, 3);
            $table->timestamps();

            //relation
            $table->foreign('id_commande')->references('id')->on('commandes')->onDelete('cascade');
            $table->foreign('id_produit')->references('id')->on('produits')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contenu_commandes');
    }
};
