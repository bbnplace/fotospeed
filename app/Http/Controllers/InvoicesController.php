<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceStatus;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Inertia\Inertia;

class InvoicesController extends Controller
{
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
            $invoice = Invoice::create([
                'user_id' => $order->user_id,
                'order_id' => $order_id,
                'track_id' => (string) Uuid::uuid4(),
                'invoice_status_id' => InvoiceStatus::STATUS_NEW,
                'description' => 'Invoice for Order ' . $order->name
            ]);

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
            'payment_method' => 'required|string|in:Transfer,USSD,Bank Deposit',
            'customer_bank' => 'required|string|max:128',
            'depositor_name' => 'required|string|max:128',
            'transaction_reference' => 'nullable|string|max:128',
            'payment_date' => 'required|date|before_or_equal:today',
        ]);

        // Check if invoice exists
        $invoice = Invoice::findOrFail($id);

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
            'organizationAccountName' => $settings->account_name ?? $settings->org_name,
            'status' => 'Pending Verification',
            'submitted_at' => now()->toDateTimeString(),
            'submitted_by' => auth()->user()->name . ' (Staff)',
        ];

        // Update invoice
        $invoice->payment_method = 'Bank Transfer';
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
                foreach ($eligibleApprovers as $approver) {
                    $approver->notify(new \App\Notifications\GenericNotification([
                        'message' => sprintf(
                            'Staff %s submitted bank transfer payment for Invoice #%s. Amount: ₦%s, Bank: %s, Reference: %s. View Details.',
                            auth()->user()->name,
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
            'message' => 'Payment submitted successfully! The payment is pending verification.'
        ]);
    }
}
