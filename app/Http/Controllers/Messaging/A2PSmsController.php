<?php

namespace App\Http\Controllers\Messaging;

use App\Http\Controllers\Controller;
use App\Models\A2pSmsMessage;
use Illuminate\Http\Request;

class A2PSmsController extends Controller
{
    public function index($customerMobile)
    {
        $query = A2pSmsMessage::query();
        $query->where("recipient", $customerMobile);
        $query->with("order", function ($query){
            $query->select("id", "name", "order_number");
        });
        $query->orderBy("created_at","asc");
        $log = $query->get();

        return $log;
    }

    public function store(Request $request)
    {
        A2pSmsMessage::create([
            "recipient"=> $request->customerMobile,
            "body" => $request->message,
        ]);

        // TODO: Broadcast Notification to all the other users viewing this order.

        return [
            'status' => 'success'
        ];
    }
}
