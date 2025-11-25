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
            $table->string('email_method', 16)->nullable()->default('SMTP')->after('file_mime_types');
            $table->string('email_api_provider', 64)->nullable()->after('email_method');
            $table->string('email_api_key', 255)->nullable()->after('email_api_provider');
            $table->string('email_api_secret', 255)->nullable()->after('email_api_key');
            $table->string('email_api_endpoint', 255)->nullable()->after('email_api_secret');
            $table->string('email_api_region', 64)->nullable()->after('email_api_endpoint');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['email_method', 'email_api_provider', 'email_api_key', 'email_api_secret', 'email_api_endpoint', 'email_api_region']);
        });
    }
};
