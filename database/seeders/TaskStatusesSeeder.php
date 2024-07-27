<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                "id"=> TaskStatus::STATUS_TODO,
                "name"=> "Todo",
            ],
            [
                "id"=> TaskStatus::STATUS_DOING,
                "name"=> "Doing",
            ],
            [
                "id"=> TaskStatus::STATUS_DONE,
                "name"=> "Done",
            ],
        ];

        foreach ($statuses as $status) {
            TaskStatus::create($status);
        }
    }
}
