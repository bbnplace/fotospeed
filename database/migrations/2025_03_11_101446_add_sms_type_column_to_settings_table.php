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
            $table->enum('sms_type', ['SIM', 'A2P'])->nullable()->after('cecula_a2p_api_key');
            $table->string('a2p_identity', 14)->nullable()->after('sms_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('sms_type');
            $table->dropColumn('a2p_identity');
        });
    }
};
