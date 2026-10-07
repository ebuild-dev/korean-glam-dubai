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
        Schema::table('produits', function (Blueprint $table) {
            // 🔹 On supprime la clé étrangère explicitement
            $table->dropForeign('produits_id_sous_sous_categorie_foreign');

            // Si ça ne marche pas, essaye l'autre nom :
            // $table->dropForeign('produits_produits_id_sous_sous_categorie_foreign_foreign');

            // 🔹 Puis on supprime la colonne
            $table->dropColumn('id_sous_sous_categorie');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            //
        });
    }
};
