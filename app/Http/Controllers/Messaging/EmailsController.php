<?php

namespace App\Http\Controllers\Messaging;

use App\Http\Controllers\Controller;
use App\Models\EmailMessage;
use Illuminate\Http\Request;

class EmailsController extends Controller
{
    public function index($customerEmail)
    {
        $query = EmailMessage::query();
        $query->where("email", $customerEmail);
        $query->where("sender", $customerEmail);
        $query->with("order", function ($query){
            $query->select("id", "name", "order_name");
        });
        $query->orderBy("created_at","asc");
        $log = $query->get();

        return $log;
    }

    public function store(Request $request)
    {
        $orderConversation = EmailMessage::create([
            "email"=> $request->customerEmail,
            "body" => $request->message,
            "subject" => $request->subject,
            "response"=> "",
        ]);

        // TODO: Broadcast Notification to all the other users viewing this order.

        return [
            'status' => 'success'
        ];
    }
}
