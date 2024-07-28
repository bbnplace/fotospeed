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
            $table->string('wa_phone_id')->nullable()->after('cecula_sync_api_key');
            $table->string('wa_access_token')->nullable()->after('wa_phone_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('wa_phone_id');
            $table->dropColumn('wa_access_token');
        });
    }
};
