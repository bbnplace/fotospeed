<?php

use App\Models\Category;
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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Category::class)->constrained('categories')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name')->index();
            $table->text('description')->index()->nullable();
            $table->string('height')->index();
            $table->string('weight')->index();
            $table->integer('print_price')->index();
            $table->integer('sheet_price')->index();
            $table->integer('cover_print_price')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
