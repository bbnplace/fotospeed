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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->integer('max_file_size')->default(10240); // This defines the max size of an uploaded file.
            $table->integer('thumbnail_size')->default(150); // The thumbnail size for uploaded image
            $table->string('file_mime_types')->default('jpeg,png,jpg,gif'); // The upload file size supported by the system
            $table->string('cecula_sync_api_key')->nullable();
            $table->string('email_sender_name')->nullable();
            $table->string('from_email')->nullable();
            $table->string('replyto_email')->nullable();
            $table->string('email_host')->nullable();
            $table->integer('email_port')->nullable();
            $table->string('email_password')->nullable();
            $table->integer('min_order_processing_days')->default(0); // This setting defines the minimum number of days a client would wait before product is delivered
            $table->integer('max_order_processing_days')->default(0); // This setting defines the number of days a user will have to wait before item is delivered
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
