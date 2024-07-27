<?php

use App\Models\Branch;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Role;
use App\Models\TaskStatus;
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignIdFor(Order::class);
            $table->foreignIdFor(Branch::class);
            $table->foreignIdFor(Role::class);
            $table->foreignIdFor(User::class)->nullable(); // User ID to be attached when a user claims the task.
            $table->foreignIdFor(TaskStatus::class)->nullable();
            $table->timestamp('time_accepted')->nullable();
            $table->timestamp('time_completed')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
