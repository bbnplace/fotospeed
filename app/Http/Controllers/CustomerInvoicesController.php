<?php

namespace App\Http\Controllers;

use App\Messaging\EmailClient;
use App\Messaging\SMSClient;
use App\Models\EmailTemplate;
use App\Models\RewardPoint;
use App\Http\Traits\HandlesRewardPointRedemption;
use App\Models\Role;
use App\Models\SmsTemplate;
use App\Models\Setting;
use App\Models\Invoice;
use App\Models\InvoiceStatus;
use App\Models\Order;
use App\Models\User;
use App\Notifications\GenericNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CustomerInvoicesController extends Controller
{
    use HandlesRewardPointRedemption;
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

        // Check if invoice exists
        if (!$invoice) {
            abort(404, 'Invoice not found');
        }

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
                'account_name' => $settings->account_name,
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

    public function handlePaystackPaymentCompletion(Request $request, $invoiceId)
    {
        // This method is called after successful Paystack payment
        $invoice = Invoice::with('order')->findOrFail($invoiceId);
        
        // Verify payment with Paystack
        $reference = $request->reference;
        
        // Award loyalty points based on ACTUAL amount paid to Paystack
        // Not on any discount from points redemption
        $settings = Setting::first();
        if ($settings && !empty($settings->loyalty_reward_multiplier)) {
            // For Paystack, the actual amount paid is stored in the transaction
            // This is the amount AFTER points discount was applied
            $order = $invoice->order;
            
            // Get the actual Paystack payment amount (this is finalAmount after points discount)
            $paystackAmount = $invoice->order->total_cost - ($invoice->points_discount_amount ?? 0);
            
            // Calculate points on the cash amount paid to Paystack
            $rewardPoints = round($paystackAmount * $settings->loyalty_reward_multiplier, 2);
            
            $expiryMonths = $settings->points_expiry_months ?? 12;
            $expiresAt = $expiryMonths > 0 ? now()->addMonths($expiryMonths) : null;
            
            \App\Models\RewardPoint::create([
                'user_id' => $order->user_id,
                'invoice_id' => $invoice->id,
                'points' => $rewardPoints,
                'transaction_type' => \App\Models\RewardPoint::TYPE_EARNED,
                'description' => "Earned from Invoice #{$invoice->id} (Paystack Payment)",
                'expires_at' => $expiresAt,
            ]);
        }
        
        return response()->json(['status' => 'success']);
    }

    private function saveLoyaltyRewardPoints($order, $invoice, $rewardMultiplier)
    {
        // Secure calculation without eval()
        if (!empty($rewardMultiplier) && is_numeric($rewardMultiplier) && $rewardMultiplier > 0) {
            $rewardPoints = round($order->total_cost * $rewardMultiplier, 2);
            
            // Get expiration date from settings
            $settings = Setting::first();
            $expiryMonths = $settings->points_expiry_months ?? 12;
            $expiresAt = $expiryMonths > 0 ? now()->addMonths($expiryMonths) : null;
            
            RewardPoint::create([
                'user_id' => $order->user_id,
                'invoice_id' => $invoice->id,
                'points' => $rewardPoints,
                'transaction_type' => RewardPoint::TYPE_EARNED,
                'description' => "Earned from Invoice #{$invoice->id}",
                'expires_at' => $expiresAt,
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
            'points_to_redeem' => 'nullable|integer|min:0',
        ]);

        // Check if invoice exists and belongs to user
        $invoice = Invoice::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($invoice->invoiceStatus->name != 'Unpaid') {
            return response()->json(['message' => 'Invoice is already paid or cancelled.'], 403);
        }

        $settings = Setting::first();
        $invoiceAmount = $invoice->order->total_cost;

        // Process points redemption if requested (pass amount paid for overpayment check)
        $redemption = $this->processPointsRedemption(
            auth()->id(),
            $invoice->id,
            $invoiceAmount,
            $validated['points_to_redeem'] ?? null,
            $validated['amount'] ?? null
        );

        if ($redemption['error']) {
            return response()->json(['message' => $redemption['error']], 400);
        }

        // Update invoice with points redemption
        if ($redemption['points_redeemed'] > 0) {
            $invoice->points_redeemed = $redemption['points_redeemed'];
            $invoice->points_discount_amount = $redemption['discount_amount'];
            $invoice->save();
        }

        // Prepare payment data (using final amount after discount)
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
            'organizationAccountName' => $settings->account_name ?? $settings->org_name,
            'pointsRedeemed' => $redemption['points_redeemed'],
            'pointsDiscount' => $redemption['discount_amount'],
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
                $pointsInfo = $redemption['points_redeemed'] > 0 
                    ? sprintf(' (₦%s after ₦%s points discount)', number_format($redemption['final_amount'], 2), number_format($redemption['discount_amount'], 2))
                    : '';

                foreach ($eligibleApprovers as $approver) {
                    $approver->notify(new GenericNotification([
                        'message' => sprintf(
                            '%s submitted bank transfer payment for Invoice #%s. Amount: ₦%s%s, Bank: %s, Reference: %s. View Details.',
                            $invoice->user->name,
                            $invoice->id,
                            number_format($validated['amount'], 2),
                            $pointsInfo,
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
