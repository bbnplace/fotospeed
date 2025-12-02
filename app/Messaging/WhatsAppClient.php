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
    private $order;
    private $templateManager;

    private $password;

    public function __construct(array $config)
    {
        $this->customer = $config['customer'] ?? null;
        $this->nextProcess = $config['nextProcess'] ?? null;
        $this->team = $config['team'] ?? null;
        $this->order = $config['order'] ?? null; 
        $this->password = $config['password'] ?? null;

        // Load the template into the template manager
        $this->templateManager = new TemplateManager($config);
    }

    public function sendTeamMessage($template=null)
    {
        if ($this->team->count() > 0) {
            $teamSms = $template;
            if (empty($teamSms) && !empty($this->nextProcess)) {
                 $teamSms = property_exists($this->nextProcess, 'whatsappTemplate') ? $this->nextProcess->whatsappTemplate : null;
            }

            if (!empty($teamSms)) {
                $contacts = [];
                foreach($this->team as $teamMember)
                {
                    array_push($contacts, $teamMember->mobile);
                }
                $this->sendMessage($teamSms, $contacts);
            }
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

            // See if the template has a header.
            
            $fullTemplate = json_decode($whatsAppTemplate->template_detail, true);
            $componentHeaderFormat = $this->getComponentHeaderFormat($fullTemplate['components']);
            // $footerText = $this->getComponentFooter($fullTemplate['components']);

            $components = [];
            // Set the Header
            if(strtolower($componentHeaderFormat) == 'image' && !empty($this->order->item->wa_media_id))
            {
                array_push($components, [
                    'type' => 'header',
                    'parameters' => [
                        [
                            'type' => 'image',
                            'image' => [
                                'id' => $this->order->item->wa_media_id
                            ]
                        ]
                    ],
                    
                ]);
            }

            // Set the Body
            array_push($components, [
                'type' => 'body',
                'parameters' => $whatsAppTemplateBodyParams
            ]);

            // The Media ID for the product

            $settings = Setting::first(['wa_access_token','wa_phone_id']);
            if (!empty($settings->wa_access_token) && !empty($settings->wa_phone_id)) {
                $url = "https://graph.facebook.com/v22.0/{$settings->wa_phone_id}/messages";
                // Log::info('WA URL: '. $url);
                $processedRecipients = [];
                foreach ($recipients as $mobile) {
                    array_push($processedRecipients, MobileValidator::formatNigerianNumberToInternationalFormat($mobile, true));
                }

                foreach ($processedRecipients as $recipient) {
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
                            'components' => $components
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
                }
            } else {
                // Todo: Notify Admin of attempt to send WhatsApp Message while WhatsApp Accesskey and Phone ID not set
            }
        }
    }

    public function sendTextMessage(string $messageBody, array $recipients)
    {
        if (count($recipients) > 0) {
            $settings = Setting::first(['wa_access_token','wa_phone_id']);
            
            if (!empty($settings->wa_access_token) && !empty($settings->wa_phone_id)) {
                $url = "https://graph.facebook.com/v22.0/{$settings->wa_phone_id}/messages";
                
                $processedRecipients = [];
                foreach ($recipients as $mobile) {
                    array_push($processedRecipients, MobileValidator::formatNigerianNumberToInternationalFormat($mobile, true));
                }

                foreach ($processedRecipients as $recipient) {
                    // Send Whatsapp Text Message
                    $response = Http::withToken($settings->wa_access_token)->post($url, [
                        'messaging_product' => 'whatsapp',
                        'recipient_type' => 'individual',
                        'to' => $recipient,
                        'type' => 'text',
                        'text' => [
                            'preview_url' => false,
                            'body' => $messageBody
                        ]
                    ]);

                    $waResponse = $response->json();
                    
                    // Handle success response
                    if (isset($waResponse['messages'])) {
                        $contacts = $waResponse['contacts'] ?? [];
                        $messages = $waResponse['messages'];
                        
                        for ($i=0; $i < count($messages); $i++) {
                            $contactWaId = isset($contacts[$i]['wa_id']) ? $contacts[$i]['wa_id'] : $recipient;
                            $messageRef = $messages[$i];
                            
                            WhatsappMessage::create([
                                'customer_id' => $this->customer ? $this->customer->id : null,
                                'recipient' => $contactWaId,
                                'body' => $messageBody,
                                'wa_reference' => $messageRef['id'],
                                'direction' => 'out',
                                'status' => $messageRef['message_status'] ?? 'sent',
                                'response' => json_encode($waResponse)
                            ]);
                        }
                    } else {
                        // Log error response
                        Log::error('WhatsApp Text Message Failed: ' . $response->body());
                    }
                }
            } else {
                Log::error('WhatsApp Configuration Missing: Access Token or Phone ID not set.');
            }
        }
    }

    private function getComponentHeaderFormat($componentParts)
    {
        $componentFormat = null;
        foreach ($componentParts as $componentPart) {
            if (strtolower($componentPart['type']) == 'header') {
                $componentFormat = $componentPart['format'];
                break;
            }
        }

        return $componentFormat;
    }

    private function getComponentFooter($componentParts)
    {
        $componentFooter = null;
        foreach ($componentParts as $componentPart) {
            if (strtolower($componentPart['type']) == 'footer') {
                $componentFooter = $componentPart['text'];
                break;
            }
        }

        return $componentFooter;
    }
}
