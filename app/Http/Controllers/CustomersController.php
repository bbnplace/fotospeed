<?php

namespace App\Http\Controllers;

use App\Models\CustomerGroup;
use App\Models\Group;
use App\Models\RewardPoint;
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
        if (auth()->user()->isAdmin()) {
            return Inertia::render('Backend/Customer/List', [
                'endpoint' => route('customers.records'),
                'note' => session('note'),
                'states' => State::all(['name'])
            ]);
        } else {
            return Inertia::render('Backend/Customer/Finder', [
                'endpoint' => route('customer.search')
            ]);
        }
    }

    public function records(Request $request)
    {
        $customer = [];
        $customerCount = 0;

        $page = $request->page;
        $customerPerPage = $request->itemsPerPage;
        $sortBys = $request->sortBy;
        $search = $request->search;

        $customerRole = Role::where('name','Customer')->first();
        $query = User::query();
        $query->where('role_id', $customerRole->id);

        // If query is not from an administrative branch, only customers from that branch should appear in resultset
        if (!auth()->user()->isFromAdministrativeBranch())
        {
            $query->where('branch_id', auth()->user()->branch_id);
        }

        if (!empty($search)) {
            // Handle Vue ref object if passed directly
            $filters = isset($search['_value']) ? $search['_value'] : $search;

            if (is_array($filters)) {
                if (!empty($filters['name'])) {
                    $query->where('name', 'LIKE', '%' . $filters['name'] . '%');
                }
                if (!empty($filters['mobile'])) {
                    $query->where('mobile', 'LIKE', '%' . $filters['mobile'] . '%');
                }
                if (!empty($filters['email'])) {
                    $query->where('email', 'LIKE', '%' . $filters['email'] . '%');
                }
                if (!empty($filters['state'])) {
                    $query->whereHas('state', function ($q) use ($filters) {
                        $q->where('name', $filters['state']);
                    });
                }
                if (!empty($filters['account_status'])) {
                    $query->where('account_status', $filters['account_status']);
                }
            } elseif (is_string($filters) && !empty($filters)) {
                // Fallback for single search string
                $query->where(function($query) use ($filters){
                    $query->where('name', 'LIKE', sprintf('%%%s%%', $filters))
                        ->orWhere('mobile', 'LIKE', sprintf('%%%s%%', $filters))
                        ->orWhere('email', 'LIKE', sprintf('%%%s%%', $filters));
                });
            }
        }

        $query->with(['role' => function ($query) {
            $query->select('id', 'name');
        }]);
        $query->with(['state' => function ($query) {
            $query->select('id', 'name');
        }]);

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
            'states' => State::getStatesArray(),
            'groups' => Group::getGroupsArray(),
        ]);
    }

    private function getCustomerInfo($customerId)
    {
        $query = User::query();
        $query->where('id', $customerId);
        // If query is not from an administrative branch, only customers from that branch should appear in resultset
        if (!auth()->user()->isFromAdministrativeBranch())
        {
            $query->where('branch_id', auth()->user()->branch_id);
        }

        $query->with(['role' => function ($query) {
            $query->select('id', 'name');
        }]);
        $query->with(['state' => function ($query) {
            $query->select('id', 'name');
        }]);
        $user = $query->first();

        // Loyalty Reward Points
        $points = RewardPoint::where('user_id', $user->id)->sum('points');

        return [
            'states' => State::getStatesArray(),
            'groups' => Group::getGroupsArray(),
            'customerGroups' => CustomerGroup::getCustomerGroupsArray($customerId),
            'customer' => $user,
            'loyaltyPoints' => $points,
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
            'email' => 'nullable|string|email:rfc,dns',
            'state' => 'required|string|min:1|max:64|exists:states,name',
            'password' => 'nullable|string|min:8|max:64|confirmed',
            'password_confirmation' => 'nullable',
            'role' => 'required|string|min:5|max:64|exists:roles,name',
            'groups' => 'nullable|array',
            'groups.*' => sprintf('in:%s', implode(',', Group::getGroupsArray())),
            'account_status' => 'required|string|in:Active,Inactive,Temporarily Suspended,Permanently Suspended',
            'intended_use' => 'nullable|string|max:1000',
        ];

        $request->validate($rules);

        $role = Role::where('name', $request->role)->first();
        $state = State::where('name', $request->state)->first();

        $user = User::where('id', $id)->where('mobile', $request->mobile)->first();
        $user->role_id = $role->id;
        $user->state_id = $state->id;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->account_status = $request->account_status;
        $user->intended_use = $request->intended_use;

        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
            $user->is_temporary_password = true;
        }

        $user->save();

        if (!empty($request->groups)) {
            CustomerGroup::saveCustomerToGroups($user->id, $request->groups);
        }

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


    public function findByMobile(Request $request)
    {
        $customer = User::where('mobile', $request->mobile)->first([
            'name',
            'email',
            'mobile'
        ]);

        return [
            'status' => 'success',
            'customer' => $customer,
        ];
    }

    public function findCustomerByMobileOrName(Request $request)
    {
        $query = User::query();
        // If query is not from an administrative branch, only customers from that branch should appear in resultset
        if (!auth()->user()->isFromAdministrativeBranch())
        {
            $query->where('branch_id', auth()->user()->branch_id);
        }
        
        $query->where('role_id', (Role::where('name', 'Customer')->first())->id);
        $query->where('mobile', $request->keyphrase);
        $query->with('state', function ($query){
            $query->select('id', 'name');
        });
        $customer =  $query->first();

        return [
            'customer' => $customer
        ];
    }
}
