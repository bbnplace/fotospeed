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
            $table->json('order_file_delible_states')->nullable()->after('reports_permission');
            $table->string('auto_delete_order_files_after')->nullable()->default('Two Weeks')->after('order_file_delible_states');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('order_file_delible_states');
            $table->dropColumn('auto_delete_order_files_after');
        });
    }
};
