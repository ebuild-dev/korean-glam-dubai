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
        Schema::table('guides', function (Blueprint $table) {

            // 🔹 Rendre user_id nullable
            $table->foreignId('user_id')
                ->nullable()
                ->change();

            // 🔹 Ajouter id_appointment
            $table->foreignId('id_appointment')
                ->nullable()
                ->after('user_id')
                ->constrained('appointments')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guides', function (Blueprint $table) {

            // Supprimer FK appointment
            $table->dropForeign(['id_appointment']);
            $table->dropColumn('id_appointment');

            // Remettre user_id NOT NULL
            $table->foreignId('user_id')
                ->nullable(false)
                ->change();
        });
    }
};
