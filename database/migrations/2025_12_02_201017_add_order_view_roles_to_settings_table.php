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
            $table->json('order_view_roles')->nullable()->after('customer_creation_whatsapp_template');
        });

        // Setting default values programmatically after altering the table
        DB::table('settings')->update([
            'order_view_roles' => json_encode(["Reception", "Management", "Administrator", "System Admin"])
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('order_view_roles');
        });
    }
};
