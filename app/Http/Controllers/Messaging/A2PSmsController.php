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
        $request->validate([
            'customerMobile' => 'required|string',
            'message' => 'required|string|max:1000',
        ]);

        // Fetch settings
        $settings = \App\Models\Setting::first();
        
        // Check if A2P is configured
        if (!$settings || !$settings->cecula_a2p_api_key || strlen($settings->cecula_a2p_api_key) < 32) {
            return response()->json([
                'status' => 'error',
                'message' => 'A2P SMS is not configured. Please set up the API key in settings.'
            ], 500);
        }

        if (!$settings->a2p_identity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sender identity is not set. Please configure it in settings.'
            ], 500);
        }

        try {
            // Initialize Cecula Messaging API
            $ceculaA2pSmsClient = new \Cecula\MessagingApi\Messaging([
                'apiKey' => trim($settings->cecula_a2p_api_key)
            ]);

            // Format phone number to international format
            $formattedMobile = \App\Messaging\MobileValidator::formatNigerianNumberToInternationalFormat($request->customerMobile);

            // Check balance
            $balanceResponse = $ceculaA2pSmsClient->getBalance();
            $balanceData = json_decode($balanceResponse, true);
            
            if (isset($balanceData['data']['balance'])) {
                $balance = (float) $balanceData['data']['balance'];
            } else {
                $balance = (float) $balanceResponse;
            }

            if ($balance < 1) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient SMS balance. Please recharge your account.'
                ], 500);
            }

            // Send SMS
            $smsparams = [
                'sender' => $settings->a2p_identity,
                'recipients' => [$formattedMobile],
                'text' => $request->message,
            ];

            $response = $ceculaA2pSmsClient->sendSms($smsparams);

            // Log the message to database
            A2pSmsMessage::create([
                "recipient" => $request->customerMobile,
                "body" => $request->message,
            ]);

            // TODO: Broadcast Notification to all the other users viewing this order.

            return [
                'status' => 'success',
                'message' => 'SMS sent successfully',
                'response' => $response
            ];

        } catch (\Exception $e) {
            \Log::error('A2P SMS sending failed: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send SMS: ' . $e->getMessage()
            ], 500);
        }
    }
}
