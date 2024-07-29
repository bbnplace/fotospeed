<?php

namespace App\Http\Controllers;

use App\Events\JobReceived;
use App\Helper\URLGenerator;
use App\Messaging\EmailClient;
use App\Messaging\SMSClient;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\User;
use Illuminate\Http\Request;
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
            'order' => $order
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
            
            // Generate Invoice Record for the user
            $invoice = Invoice::create([
                'user_id' => $this->customer->id,
                'order_id' => $this->order->id,
                'track_id' => (string) Uuid::uuid4(),
                'invoice_status_id' => 1, // 1 Represents Unpaid
                'description' => 'Invoice for Order ' . $this->order->name
            ]);
        }

        $messagingData = [
            'customer' => $this->customer,
            'order' => $this->order,
            'nextProcess' => $this->nextProcess,
            'team' => $this->team,
            'url' => URLGenerator::generateAndShortenSignedUrl($this->customer->id, 60 * 60 * 24),
        ];

        // Get Team SMS for the Next Process
        if ($this->nextProcess->sms_team) {
            $smsClient = new SMSClient($messagingData);
            $smsClient->sendTeamSms();
        }

        if ($this->nextProcess->email_team) {
            $emailClient = new EmailClient($messagingData);
            $emailClient->sendTeamEmail();
        }

        if ($this->nextProcess->sms_customer) {
            $smsClient = new SMSClient($messagingData);
            $smsClient->sendCustomerSms();
        }

        if ($this->nextProcess->email_customer) {
            $emailClient = new EmailClient($messagingData);
            $emailClient->sendCustomerEmail();
        }

        // Log the just completed order process [This enables system know who worked on what task]
        $this->logOrderProcess();

        // Send Push Notification to members of the targetted team
        $this->sendPush();

        $this->order->order_status_id = $this->nextProcess->id;
        $this->order->save();

        if ($this->nextProcess->name == 'Billing') {
            return redirect(route('order.view', [$request->orderId]))->with('note', 'Invoice link Sent');
        }
        return redirect(route('process.completed', [$request->orderId]))->with('note', 'Order moved to ' . $this->nextProcess->name);
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

    private function logOrderProcess()
    {
        OrderLog::create([
            'order_id' => $this->order->id,
            'staff_id' => auth()->user()->id,
            'process_id' => $this->order->order_status_id,
        ]);
    }
}
