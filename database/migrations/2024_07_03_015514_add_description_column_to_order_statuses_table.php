<?php

use App\Models\EmailTemplate;
use App\Models\OrderStatus;
use App\Models\Role;
use App\Models\SmsTemplate;
use App\Models\Team;
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
            $table->text('description')->fulltext()->nullable();
            $table->foreignIdFor(Role::class);
            $table->foreignIdFor(SmsTemplate::class)->nullable();
            $table->foreignIdFor(EmailTemplate::class)->nullable();
            $table->foreignIdFor(OrderStatus::class, 'next_process')->nullable();
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
