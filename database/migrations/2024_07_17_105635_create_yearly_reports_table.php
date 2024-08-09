<?php

use App\Models\OrderStatus;
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
        Schema::dropIfExists('yearly_reports');

        Schema::create('yearly_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('year')->unique();

            $statuses = OrderStatus::list();
            foreach ($statuses as $status) {
                $table->integer(strtolower(str_replace(' ', '_', $status['name'])))->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yearly_reports');
    }
};
