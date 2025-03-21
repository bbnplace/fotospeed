<?php

namespace App\Messaging;

use App\Models\Setting;
use App\Models\WhatsappMessage;
use App\Models\WhatsappTemplate;
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
            $teamSms = empty($template) ? $this->nextProcess->whatsAppTemplate->template : $template;
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
        $customerWhatsAppTemplate = empty($template) ? $this->nextProcess->customerWhatsAppTemplate->template : $template;
        $this->sendMessage($customerWhatsAppTemplate, [$this->customer->mobile]);
    }


    public function sendMessage(string $templateName, array $recipients)
    {
        if (count($recipients) > 0) {

            $whatsAppTemplate = WhatsappTemplate::where('name', $templateName)->first();
            $messageBodyPreview = $this->templateManager->prepareMessage($whatsAppTemplate->template);
            $whatsAppTemplateBodyParams = $this->templateManager->prepareWhatsappTemplateParams($whatsAppTemplate->template);

            $settings = Setting::first(['wa_access_token','wa_phone_id']);
            if (!empty($settings->wa_access_token) && !empty($settings->wa_phone_id)) {
                $url = "https://graph.facebook.com/v22.0/{$settings->wa_phone_id}/messages";
                // Log::info('WA URL: '. $url);
                $processedRecipients = [];
                foreach ($recipients as $mobile) {
                    array_push($processedRecipients, MobileValidator::formatNigerianNumberToInternationalFormat($mobile, true));
                }

                foreach ($processedRecipients as $recipient) {
                    // Log::info('Phone: ' . $recipient);
                    // Log::info('Access Token: ' . $settings->wa_access_token);
                    // Log::info('Body: ' . json_encode($whatsAppTemplateBodyParams));
                    // Send WhatsappMessage
                    $response = Http::withToken($settings->wa_access_token)->post($url, [
                        'messaging_product' => 'whatsapp',
                        'recipient_type' => 'individual',
                        'to' => $recipient,
                        'type' => 'template',
                        'template' => [
                            'name' => $templateName,
                            'language' => [
                                'code' => $whatsAppTemplate->language,
                            ],
                            'components' => [
                                [
                                    'type' => 'body',
                                    'parameters' => $whatsAppTemplateBodyParams
                                ]
                            ]
                        ],
                    ]);

                    // Log::info('Response: '.json_encode($response->json()));
                    $waResponse = $response->json();
                    $contacts = $waResponse['contacts'];
                    $messages = $waResponse['messages'];
                    for ($i=0; $i < count($contacts); $i++) {
                        $contactRef = $contacts[$i];
                        $messageRef = $messages[$i];
                        WhatsappMessage::create([
                            'customer_id' => $this->customer->id,
                            'recipient' => $contactRef['wa_id'],
                            'body' => $messageBodyPreview,
                            'wa_reference' => $messageRef['id'],
                            'direction' => 'out',
                            'status' => $messageRef['message_status'],
                            'response' => $response->successful() ? json_encode($response->json()) : $response->body()
                        ]);
                    }

                    // Log Response on WhatsApp Messages Table
                    // WhatsappMessage::create([
                    //     'recipient'=> $recipient,
                    //     'body' => $messageBodyPreview,
                    //     'status' => $response->successful() ? 'Success' : 'Failed',
                    //     'response' => $response->successful() ? json_encode($response->json()) : $response->body(),
                    // ]);
                }
            } else {
                // Todo: Notify Admin of attempt to send WhatsApp Message while WhatsApp Accesskey and Phone ID not set
            }
        }
    }
}
