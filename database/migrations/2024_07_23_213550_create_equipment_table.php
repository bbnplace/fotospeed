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
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('type')->nullable();
            $table->string('capability')->nullable();
            $table->string('specification')->nullable();
            $table->string('model_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('physical_location')->nullable();
            $table->string('department')->nullable();
            $table->string('status')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};
