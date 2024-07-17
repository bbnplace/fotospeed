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
        return [
            'customer_name' => $this->customer->name,
            'order_name' => $this->order->name,
            'order_number' => '#' . $this->order->order_number,
            'delivery_address' => $this->order->delivery_address,
            'order_status' => $this->order->orderStatus->name,
            'next_process' => $this->nextProcess->name,
            'customer_email' => $this->customer->email,
            'customer_mobile' => $this->customer->mobile,
            'order_branch' => $this->order->branch->name,
            'price' => $this->order->total_cost,
            'invoice_link' => $this->autoSignInUrl,
        ];
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
}
