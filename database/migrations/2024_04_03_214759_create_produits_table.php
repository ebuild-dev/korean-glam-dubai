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
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('reference')->nullable();
            $table->decimal('prix', 13, 3);
            $table->text("description")->nullable();
            $table->text("howtouse")->nullable();
            $table->string('photo')->nullable();
            $table->unsignedBigInteger('id_promotion')->nullable()->default(null);
            $table->integer("stock")->default(0);
            $table->unsignedBigInteger("id_categorie")->nullable();
            $table->enum("statut",["disponible","indisponible"])->default("indisponible");
            $table->unsignedBigInteger("id_brand")->nullable();
            $table->json('photos')->nullable();
            $table->boolean('top')->default(false);
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('id_promotion')->references('id')->on('promotions')->onDelete('set null');
            $table->foreign('id_categorie')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('id_brand')->references('id')->on('brands')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
