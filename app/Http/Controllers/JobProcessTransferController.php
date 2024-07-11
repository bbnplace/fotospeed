<?php

namespace App\Http\Controllers;

use App\Events\JobReceived;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\User;
use AshAllenDesign\ShortURL\Facades\ShortURL;
use CeculaSyncApiClient\SyncAccount;
use CeculaSyncApiClient\SyncSms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Inertia\Inertia;

class JobProcessTransferController extends Controller
{
    private $customer;
    private $order;
    private $nextProcess;
    private $team;
    private $autoSignInUrl;

    public function completed($id)
    {
        $order = Order::where('id', $id)->first();

        return Inertia::render('Backend/Process/Completed', [
            'newProcess' => $order->orderStatus->name,
            'orderNumber' => $order->order_number,
        ]);
    }

    public function forward(Request $request)
    {
        $this->order = Order::where('id', $request->orderId)->first();
        $this->nextProcess = $this->order->orderStatus->nextProcess;

        $this->customer = $this->order->user; // Get the customer's data

        // Get the Team for the Next Process
        $this->team = User::where('branch_id', $this->order->branch_id)->where('role_id', $this->nextProcess->role_id)->get();

        if ($this->nextProcess->name == "Billing") {
            // Generate Unique temporary token to enable the user automatically sign in to make payment
            $this->generateAndShortenSignedUrl();

            // Generate Invoice Record for the user
            $invoice = Invoice::create([
                'user_id' => $this->customer->id,
                'order_id' => $this->order->id,
                'track_id' => (string) Uuid::uuid4(),
                'invoice_status_id' => 1, // 1 Represents Unpaid
                'description' => 'Invoice for Order ' . $this->order->name
            ]);
        }

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

        // Send Push Notification to members of the targetted team
        $this->sendPush();

        $this->order->order_status_id = $this->nextProcess->id;
        $this->order->save();

        if ($this->nextProcess->name == 'Billing') {
            return redirect(route('order.view', [$request->orderId]))->with('note', 'Invoice link Sent');
        }
        return redirect(route('process.completed', [$request->orderId]))->with('note', 'Order moved to ' . $this->nextProcess->name);
    }

    private function sendTeamSms()
    {
        if ($this->team->count() > 0) {
            $teamSms = $this->nextProcess->smsTemplate->template;
            $contacts = [];
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
        if (count($recipient) > 0) {
            $message = $this->prepareMessage($smsTemplate);

            $syncAccount = new SyncAccount();
            // dd($syncAccount->getCeculaBalance());
            // TODO: If balance is low, notify Admins that the balance on Cecula Sync is low.

            $syncSms = new SyncSms();
            $response = $syncSms->sendSMS($message, $recipient);
            // TODO: Do something with the response - like maintaining a log for auditing purpose
        }
    }

    private function sendTeamEmail()
    {
        if ($this->team->count() > 0) {
            $teamEmailTemplate = $this->nextProcess->emailTemplate->template;
            $emails = [];
            foreach($this->team as $teamMember)
            {
                array_push($emails, $teamMember->email);
            }

            $this->sendEmail($teamEmailTemplate, $emails);
        }
    }

    private function sendCustomerEmail()
    {
        $customerEmailTemplate = $this->nextProcess->customerEmailTemplate->template;
        $this->sendEmail($customerEmailTemplate, [$this->customer->email]);
    }

    private function sendEmail(String $emailTemplate, Array $emails)
    {
        if (count($emails)> 0) {
            $message = $this->prepareMessage($emailTemplate);

            foreach ($emails as $email)
            {
                // TODO: Implement sending of email to each recipient
            }
        }
    }

    private function sendPush()
    {
        $message = sprintf(
            "Job %s is now %s%s."
            , $this->order->name
            , ($this->nextProcess->name == 'Completed' ? '' : 'ready for ')
            , $this->nextProcess->name
        );
        broadcast(new JobReceived($message, $this->order->branch_id))->toOthers();
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

    private function prepareMessage(String $template)
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

    private function generateSignedUrl()
    {
        $user = User::where('id', $this->customer->id)->first();
        $token = Str::random(60);
        $user->auto_login_token = $token;
        $user->auto_login_token_expires_at = now()->addMinutes(10);
        $user->save();

        // Generate Signed URL
        return URL::temporarySignedRoute(
            'auto.login'
            , now()->addMinutes(10)
            , [
                'token' => $token
            ]
        );
    }

    private function shortenUrl($autoSigninUrl)
    {
        // Shorten the URL
        $shortUrlObj = ShortURL::destinationUrl($autoSigninUrl)->make();
        return $shortUrlObj->default_short_url;
    }

    private function generateAndShortenSignedUrl()
    {
        $this->autoSignInUrl = $this->shortenUrl($this->generateSignedUrl());
    }
}
