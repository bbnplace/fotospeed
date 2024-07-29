<?php

use App\Models\Order;
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
        Schema::create('hosted_sim_messages', function (Blueprint $table) {
            $table->id();
            $table->string('sender')->nullable();
            $table->string('recipient')->index();
            $table->text('body')->fulltext();
            $table->json('response')->nullable();
            $table->foreignIdFor(Order::class)->nullable();
            $table->enum('status', ['Success', 'Failed']);
            $table->json('delivery_status')->nullable();
            $table->enum('direction', ['in','out']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hosted_sim_messages');
    }
};
