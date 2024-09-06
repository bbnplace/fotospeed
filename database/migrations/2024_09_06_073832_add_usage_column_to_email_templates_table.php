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
        Schema::table('email_templates', function (Blueprint $table) {
            $table->string('usage', 64)->nullable()->after('template');
            $table->string('timing', 64)->nullable()->after('usage');
            $table->string('target', 64)->nullable()->after('timing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->dropColumn('usage');
            $table->dropColumn('timing');
            $table->dropColumn('target');
        });
    }
};
