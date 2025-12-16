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
        // Add HELD status to task_statuses table
        DB::table('task_statuses')->insert([
            'id' => 5,
            'name' => 'Held',
        ]);
        
        // Add previous_status_id column to tasks table to track status before hold
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('previous_status_id')->nullable()->after('task_status_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('previous_status_id');
        });
        
        DB::table('task_statuses')->where('id', 5)->delete();
    }
};
