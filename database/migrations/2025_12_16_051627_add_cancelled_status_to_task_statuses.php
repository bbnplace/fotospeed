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
        // Insert the CANCELLED status into task_statuses table
        DB::table('task_statuses')->insert([
            'id' => 4,
            'name' => 'Cancelled',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the CANCELLED status
        DB::table('task_statuses')->where('id', 4)->delete();
    }
};
