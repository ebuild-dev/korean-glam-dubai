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
        Schema::create('souscategories', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->integer("order_column")->default(0);
            $table->unsignedBigInteger('id_categorie')->nullable();
            $table->unsignedBigInteger('id_produit')->nullable();
            $table->timestamps();

            $table->foreign('id_categorie')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('id_produit')->references('id')->on('produits')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('souscategories');
    }
};
