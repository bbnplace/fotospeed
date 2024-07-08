<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
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
            $query->select('id', 'name', 'total_cost', 'delivery_address');
        }]);

        $invoice = $query->first();

        return [
            'invoice' => $invoice,
            'endpoint' => route('customer.find'),
        ];
    }

    public function view($id)
    {
        return Inertia::render('Client/Invoice/Detail', $this->getInvoice($id));
    }
}
