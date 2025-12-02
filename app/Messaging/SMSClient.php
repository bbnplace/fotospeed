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

            Log::info('SMS Client: Attempting to send SMS', [
                'sms_type' => $sms_type,
                'recipient_count' => count($processedRecipients),
                'template' => $templateName,
            ]);

            if ($sms_type == 'SIM') {

                // This is assumin every message is desired to be sent with Cecula Sync
                // TODO: Implement multiple channels for sending sms.

                $syncAccount = new SyncAccount();
                $balance = (int) $syncAccount->getCeculaBalance();

                Log::info('SMS Client (SIM): Balance check', ['balance' => $balance]);

                if ($balance < 1) {
                    // TODO: If balance is low, notify Admins that the balance on Cecula Sync is low.
                    Log::warning('SMS Client (SIM): Insufficient balance', ['balance' => $balance]);
                    return false;
                }

                $syncSms = new SyncSms();
                $response = $syncSms->sendSMS($message, $processedRecipients);
                Log::info('SMS Client (SIM): SMS sent', ['response' => $response]);
                // TODO: Do something with the response - like maintaining a log for auditing purpose
            }

            if ($sms_type == 'A2P') {
                $ceculaA2pApiKey = trim($settings->cecula_a2p_api_key);

                Log::info('SMS Client (A2P): Checking configuration', [
                    'api_key_length' => strlen($ceculaA2pApiKey),
                    'identity' => $settings->a2p_identity
                ]);

                if (strlen($ceculaA2pApiKey) < 32) {
                    Log::error('SMS Client (A2P): API Key too short', ['length' => strlen($ceculaA2pApiKey)]);
                    // TODO: Notify Admin that the API Key required for sending A2P SMS is not set
                    return false;
                }

                if (empty($settings->a2p_identity)) {
                    Log::error('SMS Client (A2P): No identity configured');
                    // TODO: If the Identity has not been set, notify Admin
                    return false;
                }

                $ceculaA2pSmsClient = new Messaging([
                    'apiKey' => $ceculaA2pApiKey
                ]);

                // Get balance
                try {
                    $balanceResponse = $ceculaA2pSmsClient->getBalance();
                    Log::info('SMS Client (A2P): Raw balance response', ['response' => $balanceResponse]);
                    
                    // Parse JSON response
                    $balanceData = json_decode($balanceResponse, true);
                    
                    if (isset($balanceData['data']['balance'])) {
                        $balance = (float) $balanceData['data']['balance'];
                    } else {
                        // Fallback: try parsing as direct number
                        $balance = (float) $balanceResponse;
                    }
                    
                    Log::info('SMS Client (A2P): Balance check', ['balance' => $balance]);
                } catch (\Exception $e) {
                    Log::error('SMS Client (A2P): Balance check failed', ['error' => $e->getMessage()]);
                    return false;
                }

                if ($balance >= 1) {
                    $smsparams = [
                        'sender' => $settings->a2p_identity,
                        'recipients' => $processedRecipients,
                        'text' => $message,
                    ];

                    Log::info('SMS Client (A2P): Sending SMS', $smsparams);

                    try {
                        $response = $ceculaA2pSmsClient->sendSms($smsparams);
                        Log::info('SMS Client (A2P): SMS sent successfully', ['response' => $response]);
                    } catch (\Exception $e) {
                        Log::error('SMS Client (A2P): SMS sending failed', ['error' => $e->getMessage()]);
                        return false;
                    }
                } else {
                    Log::warning('SMS Client (A2P): Insufficient balance', ['balance' => $balance]);
                }
            }
        }
    }
}
