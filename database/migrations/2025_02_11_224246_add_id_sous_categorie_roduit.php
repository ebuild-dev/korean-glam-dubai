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
            $table->unsignedBigInteger('id_sous_categorie')->nullable();
            $table->foreign('id_sous_categorie')->references('id')->on('souscategories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropForeign(['id_sous_categorie']);
            $table->dropColumn('id_sous_categorie');
        });
    }
};
