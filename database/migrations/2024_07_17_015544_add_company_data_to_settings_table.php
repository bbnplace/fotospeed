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
            $table->string('org_name')->nullable()->after('id');
            $table->string('org_address')->nullable()->after('org_name');
            $table->string('org_email')->nullable()->after('org_address');
            $table->string('org_phone')->nullable()->after('org_email');
            $table->string('org_url')->nullable()->after('org_phone');
            $table->string('payment_sms_temp')->nullable()->after('paystack_public_key');
            $table->string('payment_email_temp')->nullable()->after('payment_sms_temp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('org_name');
            $table->dropColumn('org_address');
            $table->dropColumn('org_email');
            $table->dropColumn('org_phone');
            $table->dropColumn('org_url');
            $table->dropColumn('payment_sms_temp');
            $table->dropColumn('payment_email_temp');
        });
    }
};
