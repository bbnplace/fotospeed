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
            $table->json('reportables')->default(json_encode(["Received"]))->after('payment_email_temp'); // This field contains an array of reportable data
            $table->json('reports_permission')->default(json_encode(['Administrator', 'System Admin']))->after('reportables');
        });
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
