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
        // Drop the foreign key first
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        
        // Rename the table - this preserves all data
        Schema::rename('notifications', 'custom_notifications');
        
        // Re-add the foreign key with the new table name
        Schema::table('custom_notifications', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the foreign key
        Schema::table('custom_notifications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        
        // Rename back
        Schema::rename('custom_notifications', 'notifications');
        
        // Re-add the foreign key
        Schema::table('notifications', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }
};
