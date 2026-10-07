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
        Schema::table('configs', function (Blueprint $table) {
            $table->string('text_header_center')->nullable()->after('text_header');
        });
    }

    public function down(): void
    {
        Schema::table('configs', function (Blueprint $table) {
            $table->dropColumn('text_header_center');
        });
    }
};
