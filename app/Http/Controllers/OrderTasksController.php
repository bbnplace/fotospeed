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
        $query = Task::query();
        $query->where('order_id', $orderId); // Ensure user is from the production branch
        $query->whereNull('user_id'); // Task has not been claimed
        $query->orderBy('id', 'desc');
        $query->with("role", function ($query){
            $query->select("id", "name");
        });
        $query->with("branch", function ($query){
            $query->select("id", "name");
        });
        $unclaimedTasks = $query->get([
            'id', 'name', 'description', 'created_at'                
        ]);

        $order = Order::where("id", $orderId )->first(["id","name", "order_number"]);
        
        return Inertia::render('Backend/Task/AdminDashboard', [
            'unclaimedTasks' => $unclaimedTasks,
            'order' => $order,
            'summary' => [], // Summary of tasks on user's desk [Todo, Doing and Done].
            'endpoints' => [
                'accepted' => route('tasks.order', $orderId),
                'updateTask' => route('task.update'),
                'newTasks' => route('tasks.team'),
                'pickTask' => route('task.pick'),
            ]
        ]);
    }
}
