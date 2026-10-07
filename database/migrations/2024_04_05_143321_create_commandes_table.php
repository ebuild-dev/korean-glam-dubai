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
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->string("trackcode");
            $table->string("statut")->default('créé');
            $table->enum('mode_paiement', ["offline", "online"])->default("offline");
            $table->enum("etat", ["attente", "confirmé", "annulé"])->default("attente");
            $table->string("canal_vente")->default("site web");
            $table->text("note")->nullable()->default(null);
            $table->unsignedBigInteger("by")->nullable()->foreign('by')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger("id_user")->nullable();
            $table->string("paymentRef")->nullable();
            $table->string("token")->nullable();
            $table->string("nom")->nullable();
            $table->string("prenom")->nullable();
            $table->string("adresse")->nullable();
            $table->string("phone")->nullable();
            $table->string("email")->nullable();
            $table->string("postalcode")->nullable();
            $table->string("city")->nullable();
            $table->string("code_in_api")->nullable();
            $table->decimal("frais", 10, 3)->default(0);
            $table->decimal("timbre", 10, 3)->default(0);
            $table->decimal("tva", 10, 3)->default(0);
            $table->string("devise")->default("DT");
            $table->timestamps();


            //relation
            $table->foreign('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
