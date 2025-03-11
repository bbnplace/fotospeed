<?php

namespace App\Messaging;

use App\Models\Setting;
use App\Models\SmsTemplate;
use Cecula\MessagingApi\Messaging;
use CeculaSyncApiClient\SyncAccount;
use CeculaSyncApiClient\SyncSms;
use Illuminate\Support\Facades\Log;

class SMSClient
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

    public function sendTeamSms($template = null)
    {
        if ($this->team->count() > 0) {
            $teamSmsTemplate = empty($template) ? $this->nextProcess->smsTemplate->template : $template;
            $contacts = [];
            foreach ($this->team as $teamMember) {
                array_push($contacts, $teamMember->mobile);
            }
            $this->sendSms($teamSmsTemplate, $contacts);
        }
    }

    public function sendCustomerSms($template = null)
    {
        $customerSmsTemplate = empty($template) ? $this->nextProcess->customerSmsTemplate->template : $template;
        $this->sendSms($customerSmsTemplate, [$this->customer->mobile]);
    }

    

    public function sendSms(String $templateName, $recipient)
    {
        if (count($recipient) > 0) {
            $processedRecipients = [];
            foreach ($recipient as $mobile) {
                array_push($processedRecipients, MobileValidator::formatNigerianNumberToInternationalFormat($mobile));
            }

            $smsTemplate = SmsTemplate::where('name', $templateName)->first();
            
            $message = $this->templateManager->prepareMessage($smsTemplate->template);

            // Check SMS Method that has been selected by the customer
            $settings = Setting::first();
            $sms_type = $settings->sms_type;

            if ($sms_type == 'SIM') {

                // This is assumin every message is desired to be sent with Cecula Sync
                // TODO: Implement multiple channels for sending sms.

                $syncAccount = new SyncAccount();
                $balance = (int) $syncAccount->getCeculaBalance();

                if ($balance < 1) {
                    // TODO: If balance is low, notify Admins that the balance on Cecula Sync is low.
                    return false;
                }

                $syncSms = new SyncSms();
                $response = $syncSms->sendSMS($message, $processedRecipients);
                // TODO: Do something with the response - like maintaining a log for auditing purpose
            }

            if ($sms_type == 'A2P') {
                $ceculaA2pApiKey = trim($settings->cecula_a2p_api_key);

                if (strlen($ceculaA2pApiKey) < 32) {
                    // TODO: Notify Admin that the API Key required for sending A2P SMS is not set
                    return false;
                }

                if (empty($settings->a2p_identity)) {
                    // TODO: If the Identity has not been set, notify Admin
                    return false;
                }

                $ceculaA2pSmsClient = new Messaging([
                    'apiKey' => $ceculaA2pApiKey
                ]);

                // Get balance
                $balance = (int) $ceculaA2pSmsClient->getBalance();
                if ($balance < 1) {
                    $smsparams = [
                        'sender' => $settings->a2p_identity,
                        'recipients' => $processedRecipients,
                        'text' => $message,
                    ];

                    $response = $ceculaA2pSmsClient->sendSms($smsparams);
                }
            }
        }
    }
}
