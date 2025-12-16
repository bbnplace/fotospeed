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
        Schema::table('invoices', function (Blueprint $table) {
            $table->boolean('refunded')->default(false)->after('invoice_status_id');
            $table->string('refund_account_name', 128)->nullable()->after('refunded');
            $table->string('refund_account_number', 20)->nullable()->after('refund_account_name');
            $table->string('refund_bank_name', 128)->nullable()->after('refund_account_number');
            $table->string('refund_transaction_reference', 128)->nullable()->after('refund_bank_name');
            $table->unsignedBigInteger('refunded_by')->nullable()->after('refund_transaction_reference');
            $table->timestamp('refunded_at')->nullable()->after('refunded_by');
            
            $table->foreign('refunded_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['refunded_by']);
            $table->dropColumn([
                'refunded',
                'refund_account_name',
                'refund_account_number',
                'refund_bank_name',
                'refund_transaction_reference',
                'refunded_by',
                'refunded_at'
            ]);
        });
    }
};
