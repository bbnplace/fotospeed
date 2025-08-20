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
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            $table->string('wa_reference')->unique()->nullable()->after('direction');
            $table->enum('status', [
                'read',
                'delivered',
                'sent',
                'accepted',
                'failed',
                'success',
                'pending',
                'queued',
                'scheduled',
                'cancelled',
                // Add your new status values here, for example:
                'expired',
                'processing',
                'on_hold'
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            $table->dropColumn('wa_reference');
            $table->dropColumn('status');
        });
    }
};
