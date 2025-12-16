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
        Schema::table('settings', function (Blueprint $table) {
            $table->integer('min_points_redeemable')->default(100)->after('points_expiry_months');
            $table->integer('max_invoice_percentage_payable_by_points')->default(100)->after('min_points_redeemable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['min_points_redeemable', 'max_invoice_percentage_payable_by_points']);
        });
    }
};
