<?php

namespace App\Http\Controllers;

use App\Models\CustomerFeedback;
use Illuminate\Http\Request;

class CustomerFeedbacksController extends Controller
{
    public function index($customerId, $orderId = null)
    {
        $query = CustomerFeedback::query();
        $query->where("customer_id", $customerId);

        if (!is_null($orderId)) {
            $query->where('order_id', $orderId);
        }
        
        $query->with("customer", function ($query){
            $query->select("id", "name", "mobile");
        });
        $query->with("staff", function ($query){
            $query->select("id", "name", "mobile");
        });
        $query->orderBy("created_at","asc");
        $log = $query->get();

        return $log;
    }


    public function store(Request $request)
    {
        $orderConversation = CustomerFeedback::create([
            "customer_id"=> $request->customerId,
            "staff_id" => auth()->user()->id,
            "order_id" => $request->orderId,
            "note"=> $request->message,
        ]);

        // TODO: Broadcast Notification to all the other users viewing this order.

        return [
            'status' => 'success'
        ];
    }
}
