<?php

namespace App\Messaging;

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

    public function sendTeamSms($template=null)
    {
        if ($this->team->count() > 0) {
            $teamSms = empty($template) ? $this->nextProcess->smsTemplate->template : $template;
            $contacts = [];
            foreach($this->team as $teamMember)
            {
                array_push($contacts, $teamMember->mobile);
            }
            $this->sendSms($teamSms, $contacts);
        }
    }

    public function sendCustomerSms($template=null)
    {
        $customerSmsTemplate = empty($template) ? $this->nextProcess->customerSmsTemplate->template : $template;
        $this->sendSms($customerSmsTemplate, [$this->customer->mobile]);
    }


    public function sendSms(String $smsTemplate, $recipient)
    {
        if (count($recipient) > 0) {
            Log::info('SMS Template');
            Log::info($smsTemplate);
            $message = $this->templateManager->prepareMessage($smsTemplate);
            Log::info('SMS Body');
            Log::info($message);

            // This is assumin every message is desired to be sent with Cecula Sync
            // TODO: Implement multiple channels for sending sms.

            $syncAccount = new SyncAccount();
            // dd($syncAccount->getCeculaBalance());
            // TODO: If balance is low, notify Admins that the balance on Cecula Sync is low.

            $syncSms = new SyncSms();
            $response = $syncSms->sendSMS($message, $recipient);
            // TODO: Do something with the response - like maintaining a log for auditing purpose
        }
    }
}
