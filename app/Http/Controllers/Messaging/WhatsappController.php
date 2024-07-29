<?php

namespace App\Http\Controllers\Messaging;

use App\Http\Controllers\Controller;
use App\Models\WhatsappMessage;
use Illuminate\Http\Request;

class WhatsappController extends Controller
{
    public function index($customerMobile)
    {
        $query = WhatsappMessage::query();
        $query->where("recipient", $customerMobile);
        $query->where("sender", $customerMobile);
        $query->with("order", function ($query){
            $query->select("id", "name", "order_name");
        });
        $query->orderBy("created_at","asc");
        $log = $query->get();

        return $log;
    }

    public function store(Request $request)
    {
        $orderConversation = WhatsappMessage::create([
            "recipient"=> $request->mobile,
            "body" => $request->message,
            "response"=> "",
        ]);

        // TODO: Broadcast Notification to all the other users viewing this order.

        return [
            'status' => 'success'
        ];
    }
}
