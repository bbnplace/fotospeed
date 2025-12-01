<?php

namespace App\Messaging;

use App\Models\EmailTemplate;

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
            // Check if nextProcess exists and has emailTemplate relationship loaded
            $teamEmailTemplate = $template;
            if (empty($template) && $this->nextProcess && property_exists($this->nextProcess, 'emailTemplate') && $this->nextProcess->emailTemplate) {
                $teamEmailTemplate = $this->nextProcess->emailTemplate->template;
            }
            
            if (empty($teamEmailTemplate)) {
                return false;
            }
            
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
        // Check if nextProcess exists and has customerEmailTemplate relationship loaded
        $customerEmailTemplate = $template;
        if (empty($template) && $this->nextProcess && property_exists($this->nextProcess, 'customerEmailTemplate') && $this->nextProcess->customerEmailTemplate) {
            $customerEmailTemplate = $this->nextProcess->customerEmailTemplate->template;
        }
        
        if (empty($customerEmailTemplate)) {
            return false;
        }
        
        $this->sendEmail($customerEmailTemplate, [$this->customer->email]);
    }

    public function sendEmail(String $templateName, Array $emails)
    {
        if (empty($templateName)) {
            return false;
        }

        if (count($emails)> 0) {
            $emailTemplate = EmailTemplate::where('name', $templateName)->first();
            $message = $this->templateManager->prepareMessage($emailTemplate->template);

            foreach ($emails as $email)
            {
                // TODO: Implement sending of email to each recipient
            }
        }
    }
}
