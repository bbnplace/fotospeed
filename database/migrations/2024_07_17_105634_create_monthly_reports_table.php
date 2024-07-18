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
        Schema::dropIfExists('monthly_reports');

        Schema::create('monthly_reports', function (Blueprint $table) {
            $table->id();
            $table->string('month', 7)->unique();
            $table->integer('received')->default(0); // To be incremented once order is created
            $table->integer('processing')->default(0); // To be incremented once payment is received by paystack
            $table->integer('produced')->default(0); // To be incremented once item is moved to Production.
            $table->integer('delivered')->default(0); // To be incremented once item status is updated to Delivered.
            $table->integer('cancelled')->default(0); // To be incremented once an order is cancelled.
            $table->integer('dispatched')->default(0); // To be incremented once an order has been dispatched.
            $table->integer('completed')->default(0); // To be incremented once an order has passed through the finishing stage.
            $table->integer('packaged')->default(0); // To be incremented once an order is packaged.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_reports');
    }
};
