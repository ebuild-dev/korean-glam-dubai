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
            $table->foreignId('gamme_id')
                ->nullable()
                ->constrained('gammes')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropForeign(['gamme_id']);
            $table->dropColumn('gamme_id');
        });
    }
};
