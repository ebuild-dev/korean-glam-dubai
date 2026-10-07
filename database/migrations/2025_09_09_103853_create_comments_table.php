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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('commentaire')->nullable();
            $table->string('photo')->nullable(); // chemin vers l’image (Storage, S3, etc.)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // relation avec users
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
