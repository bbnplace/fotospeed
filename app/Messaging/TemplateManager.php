<?php

namespace App\Messaging;

class TemplateManager
{
    private $customer;
    private $order;
    private $nextProcess;
    private $team;
    private $autoSignInUrl;

    public function __construct(array $config)
    {
        $this->customer = $config['customer'] ?? null;
        $this->order = $config['order'] ?? null;
        $this->nextProcess = $config['nextProcess'] ?? null;
        $this->team = $config['team'] ?? null;
        $this->autoSignInUrl = $config['url'] ?? null;
    }

    private function matchTemplateKeyWithValues()
    {
        $matches = [];
        if (!empty($this->customer)) {
            $matches['customer_email'] = $this->customer->email;
            $matches['customer_name'] = $this->customer->name;
            $matches['customer_mobile'] = $this->customer->mobile;
        }

        if (!empty($this->order)) {
            $matches['order_name'] = $this->order->name;
            $matches['order_number'] = $this->order->order_number;
            $matches['delivery_address'] = $this->order->delivery_address;
            $matches['order_status'] = $this->order->orderStatus->name;
            $matches['order_branch'] = $this->order->branch->name;
            $matches['price'] = $this->order->total_cost;
        }

        if (!empty($this->nextProcess)) {
            $matches['next_process'] = $this->nextProcess->name;
        }

        if (!empty($this->autoSignInUrl)) {
            $matches['invoice_link'] = $this->autoSignInUrl;
        }
        
        return $matches;
    }

    public function prepareMessage(String $template)
    {
        $message = $template;
        $templateMatches = $this->matchTemplateKeyWithValues();

        foreach ($templateMatches as $key => $value) {
            $searchTerm = sprintf('[%s]', $key);
            if (strstr($message, $searchTerm)) {
                $message = str_replace($searchTerm, $value, $message);
            }
        }

        return $message;
    }

    public function prepareText(string $template)
    {
        return $this->prepareMessage($template);
    }
}
