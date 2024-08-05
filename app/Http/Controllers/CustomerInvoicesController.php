<?php

namespace App\Http\Controllers;

use App\Messaging\EmailClient;
use App\Messaging\SMSClient;
use App\Models\EmailTemplate;
use App\Models\SmsTemplate;
use App\Models\Setting;
use App\Models\Invoice;
use App\Models\InvoiceStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CustomerInvoicesController extends Controller
{
    public function index()
    {
        return Inertia::render('Client/Invoice/List', [
            'endpoint' => route('customer.invoice-records'),
            'note' => session('note')
        ]);
    }

    public function records(Request $request)
    {
        $invoices = [];
        $invoicesCount = 0;

        $page = $request->page;
        $invoicesPerPage = $request->itemsPerPage;
        $sortBys = $request->sortBy;
        $search = $request->search;

        $query = Invoice::query();
        $query->where('user_id', auth()->user()->id);
        $query->with(['user' => function ($query){
            $query->select('id', 'name');
        }]);
        $query->with(['invoiceStatus' => function ($query){
            $query->select('id', 'name');
        }]);
        $query->with(['order' => function ($query){
            $query->select('id', 'name', 'total_cost');
        }]);

        if (!empty($search)) {
            $searchTerm = $search['_value'];
            if (!empty($searchTerm)) {
            }
        }

        if (!empty($sortBys)) {
            foreach ($sortBys as $sortBy) {
                $query->orderBy($sortBy['key'], $sortBy['order']);
            }
        }else{
            $query->orderBy('id', 'desc');
        }

        $invoicesCount = $query->count();
        $invoices = $query->take($invoicesPerPage)
            ->skip($invoicesPerPage * ($page - 1))
            ->get();

        return [
            'records' => $invoices,
            'totalRecords' => $invoicesCount,
        ];
    }

    private function getInvoice($id)
    {
        $query = Invoice::query();
        $query->where('id', $id);
        $query->with(['user' => function ($query){
            $query->select('id', 'name', 'email', 'mobile');
        }]);
        $query->with(['invoiceStatus' => function ($query){
            $query->select('id', 'name');
        }]);
        $query->with(['order' => function ($query){
            $query->select('id', 'name', 'total_cost', 'delivery_address', 'order_number');
        }]);

        $invoice = $query->first();

        $settings = Setting::first();
        $paystack= [
            'key'=> $settings->paystack_public_key,
            'reference' => $invoice->track_id, // Get the reference for the invoice
            'email' => auth()->user()->email,
            'callback' => '',
            'close' => true,
            'amount' => $invoice->order->total_cost,
        ];

        return [
            'invoice' => $invoice,
            'invoice_no_src' => $settings->invoice_no_src,
            'endpoint' => route('customer.find'),
            'paystack' => $paystack,
            'company' => [
                'name' => $settings->org_name,
                'address' => $settings->org_address,
                'email' => $settings->org_email,
                'phone' => $settings->org_phone,
                'url' => $settings->org_url,
            ]
        ];
    }

    public function view($id)
    {
        return Inertia::render('Client/Invoice/Detail', $this->getInvoice($id));
    }

    public function paymentCompleted(Request $request)
    {
        Log::info($request);
        if ($request->event == 'charge.success') {
            # code...
            $data = $request->data;
            $customer = $data['customer'];
            $authorization = $data['authorization'];
            $reference = $data['reference'];
            $status = $data['status'];

            // Get the Invoice
            $invoice = Invoice::where('track_id', $reference)->first();
            if ($invoice) {
                if ($status == 'success')
                {
                    $invoiceStatus = InvoiceStatus::where('name', 'Paid')->first();
                    if (empty($invoiceStatus)) {
                        // TODO: Send mail to site administrator notifying that the status for flagging invoice to paid doe not exist
                    } else {
                        $invoice->invoice_status_id = $invoiceStatus->id; // Update the status ID
                        $invoice->paystack_response = json_encode($request);
                        $invoice->save();

                        $ordersCount = 1; // TODO: Update to reflect the number of Orders the invoice covers

                        // TODO: Send a push notification to receptionist to notify them that payment has been received.

                        $settings = Setting::first();
                        $order = Order::where('id', $invoice->order_id)->first();
                        $nextProcess = $order->orderStatus->nextProcess;
                        $team = User::where('branch_id', $order->branch_id)->where('role_id', $nextProcess->role_id)->get();

                        $messagingData = [
                            'customer' => User::where('id', $invoice->user_id)->first(),
                            'order' => $order,
                            'nextProcess' => $nextProcess,
                            'team' => $team,
                            'url' => '',
                        ];

                        // Send email receipt to customer
                        if(!empty($settings->payment_email_temp))
                        {
                            // Fetch the email template
                            $emailTemplate = EmailTemplate::where('name', $settings->payment_email_temp)->first();
                            if(!empty($emailTemplate))
                            {
                                $emailClient = new EmailClient($messagingData);
                                $emailClient->sendCustomerEmail($emailTemplate->template);
                            }
                        }

                        // Send sms receipt to customer
                        if(!empty($settings->payment_sms_temp))
                        {
                            // Fetch the sms template
                            $smsTemplate = SmsTemplate::where('name', $settings->payment_sms_temp)->first();
                            if(!empty($smsTemplate))
                            {
                                $smsClient = new SMSClient($messagingData);
                                $smsClient->sendCustomerSms($smsTemplate->template);
                            }
                        }
                    }
                }
            }
        }
    }

    public function receipt(Request $request, $id)
    {
        return Inertia::render('Client/Invoice/Receipt',$this->getInvoice($id));
    }
}
