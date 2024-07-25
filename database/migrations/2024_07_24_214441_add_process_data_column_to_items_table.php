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
            $table->json('process_data')->nullable()->after('description');
            $table->json('order_processing_branches')->nullable()->after('cover_print_price'); // If order is from any of these branches they will process for themselves.
            $table->string('primary_order_processing_branch')->nullable()->after('order_processing_branches'); // In the event where order originates from a branch that doesn't process order, the item will be sent to this branch
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('process_data');
            $table->dropColumn('order_processing_branches');
            $table->dropColumn('primary_order_processing_branch');
        });
    }
};
