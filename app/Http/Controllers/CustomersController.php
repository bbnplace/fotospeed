<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class CustomersController extends Controller
{

    public function index()
    {
        return Inertia::render('Backend/Customer/List', [
            'endpoint' => route('customers.records'),
            'note' => session('note')
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
        $rules = [
            'name' => 'required|string|min:5|max:64',
            'mobile' => 'required|numeric|digits_between:7,16|exists:users,mobile',
            'email' => 'required|string|email:rfc,dns|exists:users,email',
            'state' => 'required|string|min:1|max:64|exists:states,name',
            'password' => 'nullable|string|min:8|max:64|confirmed',
            'password_confirmation' => 'nullable',
            'role' => 'required|string|min:5|max:64|exists:roles,name',
        ];

        $request->validate($rules);

        $role = Role::where('name', $request->role)->first();
        $state = State::where('name', $request->state)->first();

        $user = User::where('id', $id)->where('mobile', $request->mobile)->first();
        $user->role_id = $role->id;
        $user->state_id = $state->id;
        $user->name = $request->name;

        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // TODO: Send login link to the customer's mobile number and email.

        return redirect()->route('customer.view', [$user->id])->with('status', 'Customer Data Updated');
    }

    public function delete(Request $request)
    {
        if (!empty($request->ids)) {
            User::whereIn('id', $request->ids)->delete();

            return redirect()->route('customers')->with('note', 'Selected customers have been deleted');
        }
    }
}
