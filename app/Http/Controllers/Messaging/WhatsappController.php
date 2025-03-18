<?php

namespace App\Http\Controllers\Messaging;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\WhatsappMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    public function inbound(Request $request)
    {
        // Handle verification request
        if ($request->isMethod('get')) {
            return $this->verifyWebhook($request);
        }

        // Handle incoming messages
        if ($request->isMethod('post')) {
            Log::info('Incoming Webhook Payload:', $request->all());

            return $this->processRequest($request);
        }

        return response('Invalid request', 400);
    }

    public function processRequest(Request $request)
    {
        $from = $request->input('From');
        $body = $request->input('Body');

        Log::info('Whatsapp From: '. $from);
        Log::info('Body: '. $body);

        Log::info('Incoming Webhook Payload:', $request->all());

        return response('Received', 200);
    }


    private function verifyWebhook(Request $request)
    {
        // Get the verify token and challenge from the request
        $verifyToken = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        $settings = Setting::first();
        // Verify the token
        if ($verifyToken === $settings->wa_webhook_verification_token) {
            Log::info("Challenge: " . json_encode($challenge));
            return response($challenge, 200);
        }

        // If the token is invalid, return an error
        return response('Invalid verify token', 403);
    }
}
