<?php

use App\Models\Role;
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
            $table->boolean('support_offline_payment')->default(false)->after('payment_email_temp');
            $table->string('who_approves_offline_payment', '64')->nullable()->after('support_offline_payment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('support_offline_payment');
            $table->dropColumn('who_approves_offline_payment');
        });
    }
};
