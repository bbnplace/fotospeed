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
            'note' => session('note')
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
            $searchTerm = $search['_value'];
            if (!empty($searchTerm)) {
                $query->where('id', $searchTerm); // Can only search by Invoice Number
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
        return Inertia::render('Backend/Invoice/Detail', $this->getInvoice($id));
    }

    public function cancel($id)
    {

    }
}
