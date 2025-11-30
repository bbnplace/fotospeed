<?php

namespace App\Http\Controllers;

use App\Events\NewCommunicationMessage;
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
        $log = $query->get();

        return $log;
    }

    public function store(Request $request)
    {
        $orderConversation = OrderConversation::create([
            "order_id"=> $request->orderId,
            "user_id" => auth()->user()->id,
            "message"=> $request->message,
        ]);

        // Load the user relationship for broadcasting
        $orderConversation->load('user');

        // Broadcast the new message to all users viewing this order
        broadcast(new NewCommunicationMessage($orderConversation))->toOthers();

        return [
            'status' => 'success',
            'data' => $orderConversation
        ];
    }
}
