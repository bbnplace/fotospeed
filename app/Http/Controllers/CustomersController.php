<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomersController extends Controller
{
    public function index()
    {
        return Inertia::render('Backend/Customer/List', [
            'endpoint' => route('customers.records')
        ]);
    }

    public function records(Request $request)
    {
        $customer = [];
        $customerCount = 0;

        $page = $request->page;
        $customerPerPage = $request->itemsPerPage;
        $sortBys = $request->sortBy;
        $search = $request->search;

        $query = User::query();
        $query->where('role_id', 1);
        $query->with(['role' => function ($query) {
            $query->select('id', 'name');
        }]);
        $query->with(['state' => function ($query) {
            $query->select('id', 'name');
        }]);


        if (!empty($search)) {
            $searchTerm = $search['_value'];
            if (!empty($searchTerm)) {
               $query->where('name', 'LIKE', sprintf('%%%s%%', $searchTerm));
               $query->where('mobile', 'LIKE', sprintf('%%%s%%', $searchTerm));
               $query->where('email', 'LIKE', sprintf('%%%s%%', $searchTerm));
            }
        }

        if (!empty($sortBys)) {
            foreach ($sortBys as $sortBy) {
                $query->orderBy($sortBy['key'], $sortBy['order']);
            }
        }else{
            $query->orderBy('id', 'desc');
        }

        $customerCount = $query->count();
        $customer = $query->take($customerPerPage)
            ->skip($customerPerPage * ($page - 1))
            ->get();

        return [
            'records' => $customer,
            'totalRecords' => $customerCount,
        ];
    }

    public function add()
    {
        return Inertia::render('Backend/Customer/Add', [
            'states' => State::getStatesArray()
        ]);
    }

    private function getCustomerInfo($customerId)
    {
        $query = User::query();
        $query->where('id', $customerId);
        $query->with(['role' => function ($query) {
            $query->select('id', 'name');
        }]);
        $query->with(['state' => function ($query) {
            $query->select('id', 'name');
        }]);
        $user = $query->first();

        return [
            'states' => State::getStatesArray(),
            'customer' => $user
        ];
    }

    public function edit($id)
    {
        return Inertia::render('Backend/Customer/Edit', $this->getCustomerInfo($id));
    }

    public function view($id)
    {
        return Inertia::render('Backend/Customer/Detail', $this->getCustomerInfo($id));
    }

    public function update(Request $request, $id)
    {

    }

    public function delete(Request $request)
    {
        dd($request->all());
    }
}
