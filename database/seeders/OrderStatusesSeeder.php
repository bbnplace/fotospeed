<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'id' => 1,
                'name' => 'New'
            ],
            [
                'id' => 2,
                'name' => 'Processing'
            ],
            [
                'id' => 3,
                'name' => 'Completed'
            ],
            [
                'id' => 4,
                'name' => 'Cancelled'
            ],
        ];

        foreach ($statuses as $status) {
            OrderStatus::create($status);
        }
    }
}
