<?php

namespace App\Messaging;
use App\Models\Item;
use Illuminate\Support\Facades\Log;

class TemplateManager
{
    private $customer;
    private $order;
    private $nextProcess;
    private $team;
    private $autoSignInUrl;
    private $product;
    private $password;

    public function __construct(array $config)
    {
        $this->customer = $config['customer'] ?? null;
        $this->order = $config['order'] ?? null;
        $this->nextProcess = $config['nextProcess'] ?? null;
        $this->team = $config['team'] ?? null;
        $this->autoSignInUrl = $config['url'] ?? null;
        $this->password = $config['password'] ?? null;
    }

    private function matchTemplateKeyWithValues()
    {
        $matches = [];
        if (!empty($this->customer)) {
            $matches['customer_email'] = $this->customer->email;
            $matches['customer_name'] = $this->customer->name;
            $matches['customer_mobile'] = $this->customer->mobile;
        }

        if (!empty($this->password)) {
            $matches['password'] = $this->password;
        }

        if (!empty($this->order)) {
            $matches['order_name'] = $this->order->name;
            $matches['order_number'] = $this->order->order_number;
            $matches['delivery_address'] = $this->order->delivery_address;
            $matches['order_status'] = $this->order->orderStatus->name;
            $matches['order_branch'] = $this->order->branch->name;
            $matches['price'] = $this->order->total_cost;
            $matches['waybill_number'] = $this->order->waybill_number;

            // Get Items
            $product = Item::where("id", $this->order->item_id)->first();
            if (!empty($product)) {
                $matches['product'] = $product->name;
                $matches['product_name'] = $product->name;
                $matches['item'] = $product->name;
                $matches['item_name'] = $product->name;
            }
        }

        if (!empty($this->nextProcess)) {
            $matches['next_process'] = $this->nextProcess->name;
        }

        if (!empty($this->autoSignInUrl)) {
            $matches['invoice_link'] = $this->autoSignInUrl;
        }

        return $matches;
    }


    /**
     * Prepare Message
     * This method accepts a string template and replaces the template codes with values.
     * @param string|null $template
     * @return string
     */
    public function prepareMessage($template)
    {
        $message = $template ?? "";
        if (!empty($message)) {
            $templateMatches = $this->matchTemplateKeyWithValues();

            foreach ($templateMatches as $key => $value) {
                $searchTerm = sprintf('[%s]', $key);
                if (strstr($message, $searchTerm)) {
                    $message = str_replace($searchTerm, $value, $message);
                }
            }
        }

        return $message;
    }

    public function prepareWhatsappTemplateParams($template)
    {
        $whatsappParameters = [];
        $message = $template ?? "";
        
        if (!empty($message)) {
            $templateMatches = $this->matchTemplateKeyWithValues();
            foreach ($templateMatches as $key => $value) {
                $searchTerm = sprintf('[%s]', $key);
                if (strstr($message, $searchTerm)) {
                    // $message = str_replace($searchTerm, $value, $message);
                    array_push($whatsappParameters, [
                        'type' => 'text',
                        'parameter_name' => $key,
                        'text' => $value
                    ]);
                }
            }
        }

        return $whatsappParameters;
    }


    /**
     * Prepare Text
     * An aliase function to Prepare Message
     * @param string|null $template
     * @return string
     */
    public function prepareText($template)
    {
        return $this->prepareMessage($template);
    }
}
