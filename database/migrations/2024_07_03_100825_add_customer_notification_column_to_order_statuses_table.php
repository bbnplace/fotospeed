<?php

use App\Models\EmailTemplate;
use App\Models\SmsTemplate;
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
        Schema::table('order_statuses', function (Blueprint $table) {
            $table->boolean('sms_team')->default(false)->after('role_id');
            $table->boolean('email_team')->default(false)->after('sms_template_id');
            $table->boolean('sms_customer')->default(false);
            $table->foreignIdFor(SmsTemplate::class, 'customer_sms_template_id')->nullable();
            $table->boolean('email_customer')->default(false);
            $table->foreignIdFor(EmailTemplate::class, 'customer_email_template_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_statuses', function (Blueprint $table) {
            //
        });
    }
};
