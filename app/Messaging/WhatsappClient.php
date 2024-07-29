<?php

namespace App\Messaging;

use App\Models\Setting;
use App\Models\WhatsAppMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppClient
{
    private $customer;
    private $nextProcess;
    private $team;
    private $templateManager;

    public function __construct(array $config)
    {
        $this->customer = $config['customer'] ?? null;
        $this->nextProcess = $config['nextProcess'] ?? null;
        $this->team = $config['team'] ?? null;

        // Load the template into the template manager
        $this->templateManager = new TemplateManager($config);
    }

    public function sendTeamMessage($template=null)
    {
        if ($this->team->count() > 0) {
            $teamSms = empty($template) ? $this->nextProcess->smsTemplate->template : $template;
            $contacts = [];
            foreach($this->team as $teamMember)
            {
                array_push($contacts, $teamMember->mobile);
            }
            $this->sendMessage($teamSms, $contacts);
        }
    }

    public function sendCustomerMessage($template=null)
    {
        $customerSmsTemplate = empty($template) ? $this->nextProcess->customerSmsTemplate->template : $template;
        $this->sendMessage($customerSmsTemplate, [$this->customer->mobile]);
    }


    public function sendMessage(string $smsTemplate, array $recipients)
    {
        if (count($recipients) > 0) {
            $message = $this->templateManager->prepareMessage($smsTemplate);

            $settings = Setting::first(['wa_access_token','wa_phone_id']);
            if (!empty($settings->wa_access_token) && !empty($settings->wa_phone_id)) {
                $url = "https://graph.facebook.com/v13.0/{$settings->wa_phone_id}/messages";

                foreach ($recipients as $recipient) {
                    // Send WhatsAppMessage
                    $response = Http::withToken($$settings->wa_access_token)->post($url, [
                        'messaging_product' => 'whatsapp',
                        'to' => $recipient,
                        'text' => ['body' => $message],
                    ]);

                    // Log Response on WhatsApp Messages Table
                    WhatsAppMessage::create([
                        'recipient'=> $recipient,
                        'message' => $message,
                        'status' => $response->successful() ? 'Success' : 'Failed',
                        'response' => $response->successful() ? $response->json() : $response->body(),
                    ]);
                }
            } else {
                // Todo: Notify Admin of attempt to send WhatsApp Message while WhatsApp Accesskey and Phone ID not set
            }
        }
    }
}
