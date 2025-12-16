<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add cancellation audit fields to orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('cancelled_by')->nullable()->after('order_status_id');
            $table->timestamp('cancelled_at')->nullable()->after('cancelled_by');
            $table->text('cancellation_reason')->nullable()->after('cancelled_at');
            
            $table->foreign('cancelled_by')->references('id')->on('users')->onDelete('set null');
        });

        // Add order cancellation roles setting
        Schema::table('settings', function (Blueprint $table) {
            $table->json('order_cancel_roles')->nullable()->after('order_view_roles');
        });

        // Set default roles that can cancel orders (Admin and Reception)
        DB::table('settings')->update([
            'order_cancel_roles' => json_encode(["Administrator", "Reception"])
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['cancelled_by']);
            $table->dropColumn(['cancelled_by', 'cancelled_at', 'cancellation_reason']);
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('order_cancel_roles');
        });
    }
};
