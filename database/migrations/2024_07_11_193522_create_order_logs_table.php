<?php

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;
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
        Schema::create('order_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Order::class); // Identifies the order
            $table->foreignIdFor(User::class, 'staff_id'); // Identifies the staff that has worked on the order at this stage
            $table->foreignIdFor(OrderStatus::class, 'process_id'); // Identifies the process the staff worked on
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_logs');
    }
};
