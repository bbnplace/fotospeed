<?php

namespace App\Http\Controllers;

use App\Http\Traits\HandlesRewardPointRedemption;
use App\Models\Invoice;
use App\Models\InvoiceStatus;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Inertia\Inertia;

class InvoicesController extends Controller
{
    use HandlesRewardPointRedemption;
    public function index()
    {
        $settings = Setting::first();
        return Inertia::render('Backend/Invoice/List', [
            'endpoint' => route('invoice.records'),
            'invoice_no_src' => $settings->invoice_no_src,
            'note' => session('note'),
            'invoice_statuses' => InvoiceStatus::select('name')->get()
        ]);
    }

    public function create(Request $request)
    {
        // Create from Cart

        // Create from single Order
        $order_id = $request->orderId;
        $order = Order::find($order_id);
        
        if (!empty($order)) {
            // Check if order_number is numeric and use it as invoice ID
            $invoiceData = [
                'user_id' => $order->user_id,
                'order_id' => $order_id,
                'track_id' => (string) Uuid::uuid4(),
                'invoice_status_id' => InvoiceStatus::STATUS_NEW,
                'description' => 'Invoice for Order ' . $order->name
            ];
            
            // If order_number is numeric, use it as the invoice ID
            if (is_numeric($order->order_number)) {
                $invoiceData['id'] = (int) $order->order_number;
            }
            
            $invoice = Invoice::create($invoiceData);

            return redirect(route('invoice', [$invoice->id]))->with('note','Invoice Successfully Generated');
        }
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
            if (is_array($search)) {
                if (!empty($search['invoice_number'])) {
                    $query->where('id', 'like', '%' . $search['invoice_number'] . '%');
                }
                if (!empty($search['order_name'])) {
                    $query->whereHas('order', function($q) use ($search) {
                        $q->where('name', 'like', '%' . $search['order_name'] . '%');
                    });
                }
                if (!empty($search['amount'])) {
                    $query->whereHas('order', function($q) use ($search) {
                        $q->where('total_cost', 'like', '%' . $search['amount'] . '%');
                    });
                }
                if (!empty($search['status'])) {
                    $query->whereHas('invoiceStatus', function($q) use ($search) {
                        $q->where('name', $search['status']);
                    });
                }
                // Fallback for generic search if it exists in the array (e.g. from legacy or mixed usage)
                if (!empty($search['_value'])) {
                    $query->where('id', $search['_value']);
                }
            } else {
                // Handle string search (legacy behavior)
                $query->where('id', $search);
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

    private function getInvoice($id, $isNewlyGeneratedInvoice = false)
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

        // Check if user can handle refunds
        $canHandleRefunds = false;
        if (!empty($settings->who_handles_refunds)) {
            $canHandleRefunds = auth()->user()->role->name === $settings->who_handles_refunds;
        }

        // Get customer account info from payment proof for pre-filling
        $customerAccountInfo = null;
        if (!empty($invoice->customer_payment_proof)) {
            $paymentData = json_decode($invoice->customer_payment_proof, true);
            if (isset($paymentData['customerBank']) && isset($paymentData['customerAccountName'])) {
                $customerAccountInfo = [
                    'bank_name' => $paymentData['customerBank'],
                    'account_name' => $paymentData['customerAccountName'],
                    'account_number' => $paymentData['customerAccountNumber'] ?? null,
                ];
            }
        }

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
            'newlyGeneratedInvoice' => $isNewlyGeneratedInvoice,
            'bank_account' => [
                'bank_name' => $settings->bank_name,
                'account_number' => $settings->account_number,
                'account_name' => $settings->account_name,
            ],
            'banks' => \App\Models\Bank::pluck('name')->toArray(),
            'approverRole' => $settings->who_approves_offline_payment ?? 'Administrator',
            'userRole' => auth()->user()->role->name ?? '',
            'availablePoints' => \App\Models\RewardPoint::getAvailablePoints($invoice->user_id),
            'settings' => $settings,
            'canHandleRefunds' => $canHandleRefunds,
            'customerAccountInfo' => $customerAccountInfo,
        ];
    }

    public function view($id)
    {
        $sessionNote = session('note');
        return Inertia::render('Backend/Invoice/Detail', $this->getInvoice($id, $sessionNote != null));
    }

    public function submitPayment(Request $request, $id)
    {
        // Validate the request
    $validated = $request->validate([
        'amount' => 'required|numeric|min:0',
        'payment_method' => 'required|string|in:Transfer,USSD,Bank Deposit,Cash',
        'customer_bank' => 'required_unless:payment_method,Cash|nullable|string|max:128',
        'depositor_name' => 'required_unless:payment_method,Cash|nullable|string|max:128',
        'transaction_reference' => 'nullable|string|max:128',
        'payment_date' => 'required|date|before_or_equal:today',
        'who_received_cash' => 'required_if:payment_method,Cash|nullable|string|max:128',
        'points_to_redeem' => 'nullable|integer|min:0',
    ]);

    // Check if invoice exists
    $invoice = Invoice::findOrFail($id);

    if ($invoice->invoiceStatus->name != 'Unpaid') {
        return response()->json(['message' => 'Invoice is already paid or cancelled.'], 403);
    }

    $settings = Setting::first();
    $invoiceAmount = $invoice->order->total_cost;

    // Process points redemption if requested (pass null for amount paid to validate raw input first)
    $redemption = $this->processPointsRedemption(
        $invoice->user_id,
        $invoice->id,
        $invoiceAmount,
        $validated['points_to_redeem'] ?? null,
        null 
    );

    if ($redemption['error']) {
        return response()->json(['message' => $redemption['error']], 400);
    }

    // Ensure total payment (Cash/Transfer + Points Discount) covers the invoice amount
    $totalPaymentValue = $validated['amount'] + $redemption['discount_amount'];
    
    // Use a small epsilon for float comparison
    if ($totalPaymentValue < ($invoiceAmount - 0.01)) {
        return response()->json([
            'message' => sprintf(
                'The payment amount (₦%s) plus points discount (₦%s) is insufficient. Total needed: ₦%s.',
                number_format($validated['amount'], 2),
                number_format($redemption['discount_amount'], 2),
                number_format($invoiceAmount, 2)
            )
        ], 422);
    }

    // Calculate tolerance: if points are used, allow rounding variance up to the value of 1 point
    $pointsRatio = $settings->points_to_currency_ratio ?? 1.0;
    $tolerance = ($validated['points_to_redeem'] ?? 0) > 0 ? $pointsRatio : 0.01;

    if ($totalPaymentValue > ($invoiceAmount + $tolerance)) {
        return response()->json([
            'message' => sprintf(
                'The total payment (₦%s) exceeds the invoice amount (₦%s). Please input the exact amount.',
                number_format($totalPaymentValue, 2),
                number_format($invoiceAmount, 2)
            )
        ], 422);
    }

    // Update invoice with points redemption
    if ($redemption['points_redeemed'] > 0) {
        $invoice->points_redeemed = $redemption['points_redeemed'];
        $invoice->points_discount_amount = $redemption['discount_amount'];
        $invoice->save();
    }

    // Prepare payment data
    $isCash = $validated['payment_method'] === 'Cash';
    
    $paymentData = [
        'amountPaid' => $validated['amount'],
        'paymentMethod' => $isCash ? 'Cash' : 'Bank Transfer',
        'subMethod' => $validated['payment_method'],
        'customerBank' => $isCash ? null : $validated['customer_bank'],
        'customerAccountName' => $isCash ? null : $validated['depositor_name'],
        'customerAccountNumber' => null,
        'transactionReference' => $validated['transaction_reference'],
        'paymentDate' => $validated['payment_date'],
        'organizationBank' => $settings->bank_name,
        'organizationAccountNumber' => $settings->account_number,
        'organizationAccountName' => $settings->account_name ?? $settings->org_name,
        'whoReceivedCash' => $isCash ? $validated['who_received_cash'] : null,
        'pointsRedeemed' => $redemption['points_redeemed'],
        'pointsDiscount' => $redemption['discount_amount'],
        'status' => 'Pending Verification',
        'submitted_at' => now()->toDateTimeString(),
        'submitted_by' => auth()->user()->name . ' (Staff)',
    ];

    // Update invoice
    $invoice->payment_method = $isCash ? 'Cash' : 'Bank Transfer';
    $invoice->customer_payment_proof = json_encode($paymentData);
    $invoice->save();

    // Send notification to approvers
    $order = $invoice->order;
    $approvalRoleName = $settings->who_approves_offline_payment ?? 'Administrator';
    $approvalRole = \App\Models\Role::where('name', $approvalRoleName)->first();
    
    if (!empty($approvalRole)) {
        $eligibleApprovers = \App\Models\User::where('role_id', $approvalRole->id)
            ->where('branch_id', $order->branch_id)
            ->get();
        
        if (!empty($eligibleApprovers)) {
            $message = $isCash 
                ? sprintf(
                    'Staff %s submitted CASH payment for Invoice #%s. Amount: ₦%s, Received By: %s. View Details.',
                    auth()->user()->name,
                    $invoice->id,
                    number_format($validated['amount'], 2),
                    $validated['who_received_cash']
                )
                : sprintf(
                    'Staff %s submitted bank transfer payment for Invoice #%s. Amount: ₦%s, Bank: %s, Reference: %s. View Details.',
                    auth()->user()->name,
                    $invoice->id,
                    number_format($validated['amount'], 2),
                    $validated['customer_bank'],
                    $validated['transaction_reference']
                );

            foreach ($eligibleApprovers as $approver) {
                $approver->notify(new \App\Notifications\GenericNotification([
                    'message' => $message,
                    'url' => route('invoice', $invoice->id),
                    'user' => $approver,
                    'type' => ['broadcast', 'database']
                ]));
            }
        }
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Payment submitted successfully! The payment is pending verification.'
    ]);
}

    public function processRefund(Request $request, $id)
    {
        // Validate request
        $validated = $request->validate([
            'refund_amount' => 'required|numeric|min:0',
            'refund_points' => 'boolean',
            'refund_account_name' => 'required|string|max:128',
            'refund_account_number' => 'required|string|max:20',
            'refund_bank_name' => 'required|string|max:128',
            'refund_transaction_reference' => 'required|string|max:128',
        ]);

        $invoice = Invoice::findOrFail($id);

        // Check if invoice is cancelled
        if ($invoice->invoice_status_id != InvoiceStatus::STATUS_CANCELLED) {
            return response()->json(['message' => 'Only cancelled invoices can be refunded.'], 403);
        }

        // Check if already refunded
        if ($invoice->refunded) {
            return response()->json(['message' => 'This invoice has already been refunded.'], 403);
        }

        // Check authorization
        $settings = Setting::first();
        if (!empty($settings->who_handles_refunds)) {
            if (auth()->user()->role->name !== $settings->who_handles_refunds) {
                return response()->json(['message' => 'You are not authorized to process refunds.'], 403);
            }
        }

        // Update invoice with refund details
        $invoice->refunded = true;
        $invoice->refund_amount = $validated['refund_amount'];
        $invoice->refund_points = $validated['refund_points'] ?? false;
        $invoice->refund_account_name = $validated['refund_account_name'];
        $invoice->refund_account_number = $validated['refund_account_number'];
        $invoice->refund_bank_name = $validated['refund_bank_name'];
        $invoice->refund_transaction_reference = $validated['refund_transaction_reference'];
        $invoice->refunded_by = auth()->id();
        $invoice->refunded_at = now();
        
        // Handle points refund if requested
        $pointsRefunded = 0;
        if ($invoice->refund_points) {
            // Get points used from payment data
            $paymentProof = json_decode($invoice->customer_payment_proof, true);
            if ($paymentProof && isset($paymentProof['pointsRedeemed']) && $paymentProof['pointsRedeemed'] > 0) {
                $pointsRefunded = $paymentProof['pointsRedeemed'];
                
                // Restore points to customer balance by creating a new earned transaction
                \App\Models\RewardPoint::create([
                    'user_id' => $invoice->user_id,
                    'invoice_id' => $invoice->id,
                    'points' => $pointsRefunded,
                    'transaction_type' => 'earned', // Treat refund as earning back the points
                    'description' => 'Refund for cancelled invoice #' . $invoice->id,
                    // No expiry for refunded points? Or should it inherit old expiry? defaulting to null (never) or new expiry is safer.
                ]);
                
                $invoice->refunded_points = $pointsRefunded;
            }
        }
        
        $invoice->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Refund processed successfully!',
            'invoice' => $invoice
        ]);
    }
}
