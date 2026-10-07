<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('scraped_products', function (Blueprint $table) {
            $table->id();
            $table->string('source', 100);
            $table->unsignedBigInteger('source_product_id');
            $table->text('source_url');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->longText('how_to_use')->nullable();
            $table->decimal('price', 13, 3);
            $table->decimal('original_price', 13, 3)->nullable();
            $table->string('currency', 3)->default('TND');
            $table->text('brand')->nullable();
            $table->integer('stock')->nullable();
            $table->string('availability')->nullable();
            $table->json('images');
            $table->json('structured_data')->nullable();
            $table->timestamp('scraped_at');
            $table->timestamps();
            $table->unique(['source', 'source_product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scraped_products');
    }
};
