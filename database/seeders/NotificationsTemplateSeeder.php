<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Notification;

class NotificationsTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $notifications = [
            [
                'name' => 'New Task',
                'title' => 'You Have a New Task',
                'template' => ''
            ],
            [
                'name' => 'Process Completion',
                'title' => '[process_name] Tasks Done',
                'template' => ''
            ],
            [
                'name' => 'Order Cancelled',
                'title' => '[order_number] Cancelled',
                'template' => ''
            ],
            [
                'name' => 'Invoice Paid',
                'title' => '[order_number] Cancelled',
                'template' => ''
            ],
        ];

        foreach ($notifications as $notification) {
            Notification::create($notification);
        }
    }
}
