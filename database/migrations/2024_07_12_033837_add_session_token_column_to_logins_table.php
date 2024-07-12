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
        Schema::table('logins', function (Blueprint $table) {
            $table->string('session_token', 64)->index()->after('user_id');
            $table->ipAddress('ip_address')->after('session_token');
            $table->boolean('logged_out')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logins', function (Blueprint $table) {
            $table->dropColumn('session_token');
            $table->dropColumn('ip_address');
            $table->dropColumn('logged_out');
        });
    }
};
