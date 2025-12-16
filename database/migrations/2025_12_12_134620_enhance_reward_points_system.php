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
        // Add new columns to reward_points table
        Schema::table('reward_points', function (Blueprint $table) {
            if (!Schema::hasColumn('reward_points', 'transaction_type')) {
                $table->string('transaction_type')->default('earned')->after('points');
            }
            if (!Schema::hasColumn('reward_points', 'description')) {
                $table->text('description')->nullable()->after('transaction_type');
            }
            if (!Schema::hasColumn('reward_points', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('description');
            }
        });

        // Update settings table - rename column first
        if (Schema::hasColumn('settings', 'loyalty_reward_formula') && !Schema::hasColumn('settings', 'loyalty_reward_multiplier')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->renameColumn('loyalty_reward_formula', 'loyalty_reward_multiplier');
            });
        }
        
        // Then add new settings columns
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'points_to_currency_ratio')) {
                $table->decimal('points_to_currency_ratio', 10, 2)->default(1.00)->after('loyalty_reward_multiplier');
            }
            if (!Schema::hasColumn('settings', 'points_expiry_months')) {
                $table->integer('points_expiry_months')->default(12)->after('points_to_currency_ratio');
            }
        });

        // Add points redemption tracking to invoices
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'points_redeemed')) {
                $table->decimal('points_redeemed', 10, 2)->default(0)->nullable();
            }
            if (!Schema::hasColumn('invoices', 'points_discount_amount')) {
                $table->decimal('points_discount_amount', 10, 2)->default(0)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reward_points', function (Blueprint $table) {
            $table->dropColumn(['transaction_type', 'description', 'expires_at']);
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['points_to_currency_ratio', 'points_expiry_months']);
        });
        
        if (Schema::hasColumn('settings', 'loyalty_reward_multiplier')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->renameColumn('loyalty_reward_multiplier', 'loyalty_reward_formula');
            });
        }

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['points_redeemed', 'points_discount_amount']);
        });
    }
};
