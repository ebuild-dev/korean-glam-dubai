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
        Schema::table('produits', function (Blueprint $table) {
            $table->foreignId('id_sous_sous_categorie') // nom cohérent avec ton Livewire
                ->nullable()
                ->constrained('sous_souscategories') // table cible
                ->onDelete('set null'); // ou cascade selon ton besoin
        });
    }

    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            // Supprimer la contrainte
            $table->dropForeign('produits_id_sous_sous_categorie_foreign');

            // Supprimer la colonne
            $table->dropColumn('id_sous_sous_categorie');
        });
    }



};
