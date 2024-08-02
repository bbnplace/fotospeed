<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderTasksController extends Controller
{
    public function index($orderId)
    {
        $order = Order::where("id", $orderId )->first(["id","name", "order_number"]);
        
        return Inertia::render('Backend/Task/AdminDashboard', [
            'order' => $order,
            'summary' => [], // Summary of tasks on user's desk [Todo, Doing and Done].
            'endpoints' => [
                'accepted' => route('tasks.order', $orderId),
                'updateTask' => route('task.update'),
                'newTasks' => route('tasks.order.unassigned', $orderId),
                'pickTask' => route('task.pick'),
            ]
        ]);
    }
}
