<?php

namespace App\Http\Controllers;

use App\Models\OrderConversation;
use Illuminate\Http\Request;

class CommunicationLogController extends Controller
{
    public function index($orderId)
    {
        $query = OrderConversation::query();
        $query->where("order_id", $orderId);
        $query->with("user", function ($query){
            $query->select("id", "name", "mobile");
        });
        $query->orderBy("created_at","asc");
        $log = $query->paginate(10);

        return $log;
    }

    public function store(Request $request)
    {
        $orderConversation = OrderConversation::create([
            "order_id"=> $request->orderId,
            "user_id" => auth()->user()->id,
            "message"=> $request->message,
        ]);

        // TODO: Broadcast Notification to all the other users viewing this order.

        return [
            'status' => 'success'
        ];
    }
}
