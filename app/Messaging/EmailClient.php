<?php

namespace App\Messaging;

class EmailClient
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

    public function sendTeamEmail($template = null)
    {
        if ($this->team->count() > 0) {
            $teamEmailTemplate = empty($template) ? $this->nextProcess->emailTemplate->template : $template;
            $emails = [];
            foreach($this->team as $teamMember)
            {
                array_push($emails, $teamMember->email);
            }

            $this->sendEmail($teamEmailTemplate, $emails);
        }
    }

    public function sendCustomerEmail($template = null)
    {
        $customerEmailTemplate = empty($template) ? $this->nextProcess->customerEmailTemplate->template : $template;
        $this->sendEmail($customerEmailTemplate, [$this->customer->email]);
    }

    public function sendEmail(String $emailTemplate, Array $emails)
    {
        if (empty($smsTemplate)) {
            return false;
        }
        if (count($emails)> 0) {
            $message = $this->templateManager->prepareMessage($emailTemplate);

            foreach ($emails as $email)
            {
                // TODO: Implement sending of email to each recipient
            }
        }
    }
}
