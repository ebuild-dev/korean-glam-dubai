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
        Schema::create('autres_prix', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_produit");
            $table->decimal('prix', 13, 3);
            $table->integer("quantite");
            $table->timestamps();



            //relation
            $table->foreign('id_produit')->references('id')->on('produits')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autres_prix');
    }
};
