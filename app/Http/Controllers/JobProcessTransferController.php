<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use CeculaSyncApiClient\SyncAccount;
use CeculaSyncApiClient\SyncSms;
use Illuminate\Http\Request;

class JobProcessTransferController extends Controller
{
    private $customer;
    private $order;
    private $nextProcess;
    private $team;

    public function forward(Request $request)
    {
        // TODO: Get the ID of the order
        $this->order = Order::where('id', $request->orderId)->first();
        $this->nextProcess = $this->order->orderStatus->nextProcess;

        $this->customer = $this->order->user; // Get the customer's data

        // Get the Team for the Next Process
        $this->team = User::where('branch_id', $this->order->branch_id)->where('role_id', $this->nextProcess->role_id)->get();

        // Get Team SMS for the Next Process
        if ($this->nextProcess->sms_team) {
            $this->sendTeamSms();
        }

        if ($this->nextProcess->email_team) {
            $this->sendTeamEmail();
        }

        if ($this->nextProcess->sms_customer) {
            $this->sendCustomerSms();
        }

        if ($this->nextProcess->email_customer) {
            $this->sendCustomerEmail();
        }

        // TODO: Send Push Notification to members of the targetted team

        $this->order->order_status_id = $this->nextProcess->id;
        $this->order->save();

        return redirect(route('order.view', [$request->orderId]))->with('note', 'Order moved to ' . $this->nextProcess->name);
    }

    private function sendTeamSms()
    {
        $teamSms = $this->nextProcess->smsTemplate->template;
        $contacts = [];
        if (!empty($this->team)) {
            foreach($this->team as $teamMember)
            {
                array_push($contacts, $teamMember->mobile);
            }
            $this->sendSms($teamSms, $contacts);
        }

    }

    private function sendCustomerSms()
    {
        $customerSmsTemplate = $this->nextProcess->customerSmsTemplate->template;
        $this->sendSms($customerSmsTemplate, [$this->customer->mobile]);
    }

    private function sendSms(String $smsTemplate, $recipient)
    {

        // TODO: Prepare and send sms notification to customer
        $message = $this->prepareSms($smsTemplate);

        // $syncAccount = new SyncAccount();
        // dd($syncAccount->getCeculaBalance());

        $syncSms = new SyncSms();
        $response = $syncSms->sendSMS($message, $recipient);
    }

    private function sendTeamEmail()
    {
        $teamEmailTemplate = $this->nextProcess->emailTemplate->template;
    }

    private function sendCustomerEmail()
    {
        $customerEmailTemplate = $this->nextProcess->customerEmailTemplate->template;
    }

    private function sendEmail(String $template)
    {

    }

    private function sendPush()
    {

    }

    private function matchTemplateKeyWithValues()
    {
        return [
            'customer_name' => $this->customer->name,
            'order_name' => $this->order->name,
            'order_number' => '#' . $this->order->id,
            'delivery_address' => $this->order->delivery_address,
            'order_status' => $this->order->orderStatus->name,
            'next_process' => $this->nextProcess->name,
            'customer_email' => $this->customer->email,
            'customer_mobile' => $this->customer->mobile,
            'order_branch' => $this->order->branch->name,
        ];
    }

    private function prepareSms(String $template)
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
