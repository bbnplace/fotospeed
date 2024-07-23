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
            $table->json('reportables')->nullable()->after('payment_email_temp'); // This field contains an array of reportable data
            $table->json('reports_permission')->nullable()->after('reportables');
        });

        // Setting default values programmatically after altering the table
        DB::table('settings')->update([
            'reportables' => json_encode(["Received"]),
            'reports_permission' => json_encode(["Administrator", "System Admin"])
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('reportables');
            $table->dropColumn('reports_permission');
        });
    }
};
