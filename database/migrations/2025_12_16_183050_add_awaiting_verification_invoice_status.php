<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\InvoiceStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add "Awaiting Verification" status
        InvoiceStatus::firstOrCreate(['name' => 'Awaiting Verification']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove "Awaiting Verification" status
        InvoiceStatus::where('name', 'Awaiting Verification')->delete();
    }
};
