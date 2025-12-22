<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Item;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use App\Models\Process;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderCreationTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_tasks_are_created_when_order_is_created()
    {
        // 1. Setup Data
        \Cecula\Flow\Models\OrderStatus::create(['id' => 1, 'name' => 'Pending']);
        $state = \App\Models\State::create(['name' => 'Lagos']);
        $branch = Branch::create(['name' => 'Main Branch', 'address' => 'Test Address', 'contacts' => '1234567890', 'state_id' => $state->id]);
        $role = Role::create(['name' => 'Production']);
        $user = User::factory()->create(['role_id' => $role->id, 'branch_id' => $branch->id, 'mobile' => '08012345678']);
        $customer = User::factory()->create(['role_id' => Role::create(['name' => 'Customer'])->id, 'mobile' => '08087654321']);

        $processName = 'Printing';
        $process = Process::create(['name' => $processName, 'description' => 'Printing Process']);

        $processData = [
            'processes' => [
                [
                    'name' => $processName,
                    'canCancelOrder' => true,
                    'orderStatus' => 'Pending',
                    'autoStartNextProcess' => false,
                    'whoCoordinates' => 'None',
                    'smsTemplate' => 'None',
                    'emailTemplate' => 'None',
                    'whatsappTemplate' => 'None',
                ]
            ],
            'tasks' => [
                $processName => [
                    [
                        'name' => 'Print Photo',
                        'description' => 'Print the photo',
                        'team' => 'Production',
                        'audit' => false
                    ]
                ]
            ]
        ];

        $category = \App\Models\Category::create(['name' => 'Prints', 'slug' => 'prints']);
        $item = Item::create([
            'name' => 'Photo Print',
            'category_id' => $category->id,
            'primary_order_processing_branch' => 'Main Branch',
            'order_processing_branches' => json_encode(['Main Branch']),
            'process_data' => json_encode($processData),
            'slug' => 'photo-print'
        ]);

        // 2. Act - Create Order (Simulating OrdersController logic)
        $this->actingAs($user);
        
        // We can hit the endpoint or call the logic directly. Let's hit the endpoint to be sure.
        // But first we need to mock the request data.
        $this->withoutExceptionHandling();
        $response = $this->post(route('order.add'), [
            'item' => $item->name,
            'name' => 'Test Order',
            'customerMobile' => '1234567890',
            'customerName' => 'New Customer',
            'newCustomer' => true,
            'branch' => 'Main Branch',
            'date' => '2023-10-27',
            'deliveryAddress' => 'Test Address',
            'quantity' => 1,
            'price' => 1000,
            'orderNumber' => '12345',
            'note' => 'Test Note',
        ]);

        // 3. Assert
        $response->assertRedirect();
        
        $order = Order::where('name', 'Test Order')->first();
        $this->assertNotNull($order, 'Order was not created');

        // Check if tasks were created
        $tasks = Task::where('order_id', $order->id)->get();
        $this->assertGreaterThan(0, $tasks->count(), 'No tasks were created for the order');
        
        $task = $tasks->first();
        $this->assertEquals('Print Photo', $task->name);
        $this->assertEquals($process->id, $task->process_id);
    }
}
