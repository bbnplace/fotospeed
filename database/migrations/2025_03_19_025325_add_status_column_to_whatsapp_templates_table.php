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
        Schema::table('whatsapp_templates', function (Blueprint $table) {
            $table->string('status')->nullable()->after('template_detail');
            $table->string('language', 6)->nullable()->after('status');
            $table->string('category', 16)->nullable()->after('language');
            $table->string('sub_category', 16)->nullable()->after('category');
            $table->string('parameter_format', 16)->nullable()->after('sub_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whatsapp_templates', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('language');
            $table->dropColumn('category');
            $table->dropColumn('sub_category');
            $table->dropColumn('parameter_format');
        });
    }
};
