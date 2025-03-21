<?php

namespace App\Http\Controllers\Messaging;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
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
        // $from = $request->input('From');
        // $body = $request->input('Body');

        // Log::info('Whatsapp From: '. $from);
        // Log::info('Body: '. $body);

        Log::info('Incoming Webhook Payload:', $request->all());
        Log::info('Message Part:', $request->input('entry'));

        // $messageObject = json_decode($request->input('entry'));

        $object = $request->input('object');
        $entries = $request->input('entry');

        if (is_array($entries)) {
            foreach ($entries as $entry) {
                $entryId = $entry['id'];
                $changes = $entry['changes'];

                if (is_array($changes) && !empty($changes)) {
                    foreach ($changes as $change) {
                        // Note: $change->field usually indicates the type of feedback that is being sent over the webhook
                        // Note: $change->value usually contains the changed data
                        switch($change['field'])
                        {
                            case 'messages':
                                $this->processMessage($change['value']);
                                break;
                            case 'message_template_status_update':
                                $this->updateTemplateStatus($change['value']);
                                break;
                        }
                    }
                }
            }
        }

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


    private function processMessage($messageData)
    {
        $settings = Setting::first();
        $metaData = $messageData['metadata'];

        if ($settings->wa_phone_id == $metaData['phone_number_id']) {
            // Process as delivery report
            if (isset($messageData['statuses'])) {
                $this->processDeliveryReport($messageData['statuses']);
            }

            // Process as inbound message
            if (isset($messageData['messages'])) {
                $this->processInboundMessage($messageData);
            }
        } else {
            Log::info('This request is not for Phone ID: ' . $settings->wa_phone_id);
        }
    }

    private function processDeliveryReport($statuses)
    {
        if (is_array($statuses)) {
            foreach ($statuses as $receivedStatus) {
                $whatsappMessage = WhatsappMessage::where('wa_reference', $receivedStatus['id'])->first();
                if (!empty($whatsappMessage)) {
                    $whatsappMessage->status = $receivedStatus['status'];
                    $whatsappMessage->save();
                }
            }
        }
    }

    private function processInboundMessage($messageData)
    {
        $contacts = $messageData['contacts'];
        $messages = $messageData['messages'];
        $metaData = $messageData['metadata'];
        // Todo: When building multi-tenant version, look in metadata for phone number ID

        if (is_array($messages)) {
            for ($i=0; $i < count($messages); $i++) { 
                $message = $messages[$i];
                $contact = $contacts[$i];

                $mobile = $message['from'];
                $localNumber = $message['from'];
                if (substr($mobile, 0, 3) == '234' && strlen($mobile) === 13) {
                    $localNumber = '0'.substr($mobile, 3);
                }

                $user = User::where('mobile', $mobile)->first();
                if (empty($user) && $mobile != $localNumber) {
                    $user = User::where('mobile', $localNumber)->first();
                }

                $waMessageData = [
                    'customer_wa_profile' => json_encode($contact),
                    'sender' => $message['from'],
                    'recipient' => $metaData['display_phone_number'],
                    'body' => $message['text']['body'],
                    'direction' => 'in',
                    'wa_reference' => $message['id'],
                ];

                if (!empty($user)) {
                    $waMessageData['customer_id'] = $user->id;
                }

                WhatsappMessage::create($waMessageData);
            }
        }
    }

    private function updateTemplateStatus($templateData)
    {

    }
}
