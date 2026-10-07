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
        Schema::table('notifications', function (Blueprint $table) {
            // ⚠️ Pour modifier un ENUM, on utilise change()
            $table->enum("type", ["commande", "message", "stock", "signalment", "appointment"])
                ->default("commande")
                ->change();
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            // ⚠️ Retour en arrière sans "appointment"
            $table->enum("type", ["commande", "message", "stock", "signalment"])
                ->default("commande")
                ->change();
        });
    }
};
