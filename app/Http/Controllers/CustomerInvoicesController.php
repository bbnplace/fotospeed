<?php

namespace App\Http\Controllers;

use App\Messaging\EmailClient;
use App\Messaging\SMSClient;
use App\Models\EmailTemplate;
use App\Models\RewardPoint;
use App\Models\Role;
use App\Models\SmsTemplate;
use App\Models\Setting;
use App\Models\Invoice;
use App\Models\InvoiceStatus;
use App\Models\Order;
use App\Models\User;
use App\Notifications\GenericNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CustomerInvoicesController extends Controller
{
    public function index()
    {
        return Inertia::render('Client/Invoice/List', [
            'endpoint' => route('customer.invoice-records'),
            'note' => session('note'),
            'theme' => 'fotospeed', // Assuming you want to pass the theme here
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
            $query->select('id', 'name', 'total_cost', 'order_number');
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
            'theme' => 'fotospeed', // Assuming you want to pass the theme here
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
            'email' => $invoice->user->email,
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
            ],
            'bank_account' => [
                'bank_name' => $settings->bank_name,
                'account_number' => $settings->account_number,
            ],
            'banks' => \App\Models\Bank::pluck('name')->toArray(),
            'theme' => 'fotospeed',
        ];
    }

    public function view($id)
    {
        return Inertia::render('Client/Invoice/Detail', $this->getInvoice($id));
    }

    public function paymentCompleted(Request $request)
    {
        if ($request->event == 'charge.success') {
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
                        $invoice->paystack_response = json_encode($data);
                        $invoice->save();

                        $ordersCount = 1; // TODO: Update to reflect the number of Orders the invoice covers

                        $settings = Setting::first();
                        $order = Order::where('id', $invoice->order_id)->first();


                        $nextProcess = $order->orderStatus->nextProcess;
                        if (!empty($nextProcess)) {
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
                        

                        
                        // Send a push notification to receptionist to notify them that payment has been received.
                        $approvalRoleName = $settings->who_approves_offline_payment ?? 'Administrator';
                        $approvalRole = Role::where('name', $approvalRoleName)->first();
                        if (!empty($approvalRole)) {
                            $eligiblePaymentValidators = User::where('role_id', $approvalRole->id)->where('branch_id', $order->branch_id)->get();
                            if (!empty($eligiblePaymentValidators)) {
                                foreach ($eligiblePaymentValidators as $eligiblePaymentValidator) {
                                    $eligiblePaymentValidator->notify(new GenericNotification([
                                        'message' => sprintf('%s has paid for Invoice# %s via PayStack. View Details.', $order->user->name, $invoice->id),
                                        'url' => route('invoice', $invoice->id),
                                        'user' => $eligiblePaymentValidator,
                                        'type' => ['broadcast']
                                    ]));
                                }
                            }
                        }
                        

                        // Save Loyalty Reward
                        $this->saveLoyaltyRewardPoints($order, $invoice, $settings->loyalty_reward_formula);
                    }
                }
            }
        }
    }

    private function saveLoyaltyRewardPoints($order, $invoice, $rewardPointsFormula)
    {
        $invoiceAmountPlaceholder = '[invoice_amount]';
        if(!empty($rewardPointsFormula) && strstr($rewardPointsFormula, $invoiceAmountPlaceholder))
        {
            $calculation = str_replace($invoiceAmountPlaceholder, $order->total_cost, $rewardPointsFormula);
            $rewardPoint = eval('return '. $calculation .';');
            
            RewardPoint::create([
                'user_id' => $order->user_id,
                'invoice_id' => $invoice->id,
                'points' => $rewardPoint,
            ]);
        }
    }

    public function receipt(Request $request, $id)
    {
        return Inertia::render('Client/Invoice/Receipt',$this->getInvoice($id));
    }

    public function submitBankPayment(Request $request, $id)
    {
        // Validate the request
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:Transfer,USSD,Bank Deposit',
            'customer_bank' => 'required|string|max:128',
            'depositor_name' => 'required|string|max:128',
            'transaction_reference' => 'nullable|string|max:128',
            'payment_date' => 'required|date|before_or_equal:today',
        ]);

        // Check if invoice exists and belongs to user
        $invoice = Invoice::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($invoice->invoiceStatus->name != 'Unpaid') {
            return response()->json(['message' => 'Invoice is already paid or cancelled.'], 403);
        }

        $settings = Setting::first();

        // Prepare payment data
        $paymentData = [
            'amountPaid' => $validated['amount'],
            'paymentMethod' => 'Bank Transfer', // Normalized for admin view check
            'subMethod' => $validated['payment_method'], // Store specific method (Transfer, USSD, etc)
            'customerBank' => $validated['customer_bank'],
            'customerAccountName' => $validated['depositor_name'],
            'customerAccountNumber' => null, // Not collected
            'transactionReference' => $validated['transaction_reference'],
            'paymentDate' => $validated['payment_date'],
            'organizationBank' => $settings->bank_name,
            'organizationAccountNumber' => $settings->account_number,
            'organizationAccountName' => $settings->org_name,
            'status' => 'Pending Verification',
            'submitted_at' => now()->toDateTimeString(),
            'submitted_by' => auth()->user()->name,
        ];

        // Update invoice
        $invoice->payment_method = 'Bank Transfer';
        $invoice->customer_payment_proof = json_encode($paymentData);
        $invoice->save();

        // Send notification to approvers
        $settings = Setting::first();
        $order = $invoice->order;
        $approvalRoleName = $settings->who_approves_offline_payment ?? 'Administrator';
        $approvalRole = Role::where('name', $approvalRoleName)->first();
        
        if (!empty($approvalRole)) {
            $eligibleApprovers = User::where('role_id', $approvalRole->id)
                ->where('branch_id', $order->branch_id)
                ->get();
            
            if (!empty($eligibleApprovers)) {
                foreach ($eligibleApprovers as $approver) {
                    $approver->notify(new GenericNotification([
                        'message' => sprintf(
                            '%s submitted bank transfer payment for Invoice #%s. Amount: ₦%s, Bank: %s, Reference: %s. View Details.',
                            $invoice->user->name,
                            $invoice->id,
                            number_format($validated['amount'], 2),
                            $validated['customer_bank'],
                            $validated['transaction_reference']
                        ),
                        'url' => route('invoice', $invoice->id),
                        'user' => $approver,
                        'type' => ['broadcast', 'database']
                    ]));
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Payment submitted successfully! Your payment is pending verification.'
        ]);
    }
}
