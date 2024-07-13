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
        Schema::table('items', function (Blueprint $table) {
            $table->integer('print_price')->nullable()->change();
            $table->integer('sheet_price')->nullable()->change();
            $table->integer('cover_print_price')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->integer('print_price')->nullable(false)->change();
            $table->integer('sheet_price')->nullable(false)->change();
            $table->integer('cover_print_price')->nullable(false)->change();
        });
    }
};
