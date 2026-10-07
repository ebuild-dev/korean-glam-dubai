<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checkout_submissions', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->text('redirect_url')->nullable();
            $table->unsignedSmallInteger('status')->default(302);
            $table->boolean('clear_cart')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkout_submissions');
    }
};
