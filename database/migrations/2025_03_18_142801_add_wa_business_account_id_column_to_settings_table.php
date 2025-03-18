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
            $table->string('wa_business_account_id', 64)->nullable()->after('a2p_identity');
            $table->string('wa_webhook_verification_token', 64)->nullable()->after('wa_access_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('wa_business_account_id');
            $table->dropColumn('wa_webhook_verification_token');
        });
    }
};
