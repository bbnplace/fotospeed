<?php

namespace App\Http\Controllers\Messaging;

use App\Http\Controllers\Controller;
use App\Models\HostedSimMessage;
use Illuminate\Http\Request;

class HostedSimController extends Controller
{
    public function index($customerMobile)
    {
        $query = HostedSimMessage::query();
        $query->where("recipient", $customerMobile);
        $query->where("sender", $customerMobile);
        $query->with("order", function ($query){
            $query->select("id", "name", "order_number");
        });
        $query->orderBy("created_at","asc");
        $log = $query->get();

        return $log;
    }

    public function store(Request $request)
    {
        // TODO: Handle Actual Sending of Message

        $orderConversation = HostedSimMessage::create([
            "recipient"=> $request->customerMobile,
            "body" => $request->message,
            "response"=> "",
        ]);

        return [
            'status' => 'success'
        ];
    }
}
