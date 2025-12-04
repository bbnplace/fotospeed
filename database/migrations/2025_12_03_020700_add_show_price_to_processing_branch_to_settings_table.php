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
            $table->boolean('processing_branch_show_price')->default(true)->after('order_view_roles');
            $table->boolean('processing_branch_show_invoice')->default(true)->after('processing_branch_show_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['processing_branch_show_price', 'processing_branch_show_invoice']);
        });
    }
};
