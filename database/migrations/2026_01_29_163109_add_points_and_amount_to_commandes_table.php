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
        Schema::table('commandes', function (Blueprint $table) {
// 🔥 points utilisés pour la commande
            $table->integer('points_utilises')
                ->default(0)
                ->after('is_point'); // adapte si besoin


// 🔥 montant payé en argent (DT)
            $table->decimal('montant_restant', 10, 2)
                ->default(0)
                ->after('points_utilises');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn(['points_utilises', 'montant_restant']);
        });
    }
};
