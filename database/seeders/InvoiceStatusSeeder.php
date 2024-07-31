<?php

namespace Database\Seeders;

use App\Models\InvoiceStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'id' => InvoiceStatus::STATUS_NEW,
                'name' => 'Unpaid'
            ],
            [
                'id' => InvoiceStatus::STATUS_PAID,
                'name' => 'Paid'
            ],
            [
                'id' => InvoiceStatus::STATUS_FAILED,
                'name' => 'Failed'
            ],
            [
                'id' => InvoiceStatus::STATUS_CANCELLED,
                'name' => 'Cancelled'
            ],
        ];

        foreach ($statuses as $status) {
            InvoiceStatus::create($status);
        }
    }
}
