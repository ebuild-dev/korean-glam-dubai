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
            $table->integer('points_achat')->nullable()->after('prix'); // ou le champ que tu veux
            $table->integer('points_vente')->nullable()->after('points_achat');
        });
    }

    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn(['points_achat', 'points_vente']);
        });
    }
};
