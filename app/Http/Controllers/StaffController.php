<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Role;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class StaffController extends Controller
{
    protected $rules = [
        'name' => 'required|string|min:5|max:64',
        'mobile' => 'required|numeric|digits_between:7,16|unique:users,mobile',
        'email' => 'required|string|email:rfc,dns|unique:users,email',
        'branch' => 'required|string|min:5|max:64|exists:branches,name',
        'role' => 'required|string|min:5|max:64|exists:roles,name',
        'password' => 'required|string|min:8|max:64|confirmed',
        'password_confirmation' => 'required',
    ];

    public function index()
    {
        return Inertia::render('Backend/Staff/List', [
            'endpoint' => route('staff.records'),
            'note' => session('note')
        ]);
    }

    public function records(Request $request)
    {
        $staff = [];
        $staffCount = 0;

        $page = $request->page;
        $staffPerPage = $request->itemsPerPage;
        $sortBys = $request->sortBy;
        $search = $request->search;

        $query = User::query();
        $query->whereNot('role_id', 6);
        $query->whereNot('role_id', 1);

        if (!empty($search)) {
            $searchTerm = $search['_value'];
            if (!empty($searchTerm)) {
               $query->where(function($query) use($searchTerm){
                    $query->where('name', 'LIKE', sprintf('%%%s%%', $searchTerm))
                         ->orWhere('mobile', 'LIKE', sprintf('%%%s%%', $searchTerm))
                         ->orWhere('email', 'LIKE', sprintf('%%%s%%', $searchTerm));
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

        $staffCount = $query->count();
        $staff = $query->take($staffPerPage)
            ->skip($staffPerPage * ($page - 1))
            ->get();

        return [
            'records' => $staff,
            'totalRecords' => $staffCount,
        ];
    }

    public function add()
    {
        return Inertia::render('Backend/Staff/Add', [
            'states' => State::getStatesArray(),
            'branches' => Branch::getBranchesArray(),
            'roles' => Role::getRolesArray(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);

        $role = Role::where('name', $request->role)->first();
        $branch = Branch::where('name', $request->branch)->first();
        // $state = State::where('name', $request->state)->first();

        User::create([
            'role_id' => $role->id,
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'state_id' => $branch->state_id,
            'password' => Hash::make($request->password),
            'branch_id' => $branch->id,
        ]);

        // TODO: Send login link to the customer's mobile number and email.

        return redirect()->route('staff')->with('status', 'Account Created');
    }

    private function getStaffInfo($id)
    {
        $query = User::query();
        $query->where('id', $id);
        $query->with(['role' => function ($query) {
            $query->select('id', 'name');
        }]);
        $query->with(['state' => function ($query) {
            $query->select('id', 'name');
        }]);
        $query->with(['branch' => function ($query) {
            $query->select('id', 'name');
        }]);
        $user = $query->first();

        return [
            'states' => State::getStatesArray(),
            'staff' => $user,
            'branches' => Branch::getBranchesArray(),
            'roles' => Role::getRolesArray(),
        ];
    }

    public function edit($ref)
    {
        return Inertia::render('Backend/Staff/Edit', $this->getStaffInfo($ref));
    }

    public function view($ref)
    {
        return Inertia::render('Backend/Staff/Detail', $this->getStaffInfo($ref));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string|min:5|max:64',
            'mobile' => 'required|numeric|digits_between:7,16|exists:users,mobile',
            'email' => 'required|string|email:rfc,dns|exists:users,email',
            'branch' => 'required|string|min:2|max:64|exists:branches,name',
            'role' => 'required|string|min:5|max:64|exists:roles,name',
            'password' => 'nullable|string|min:8|max:64|confirmed',
            'password_confirmation' => 'nullable',
        ];

        $request->validate($rules);

        // Find user with the submitted ID
        $role = Role::where('name', $request->role)->first();
        $branch = Branch::where('name', $request->branch)->first();

        $user = User::where('id', $id)->where('mobile', $request->mobile)->first();
        $user->role_id = $role->id;
        $user->branch_id = $branch->id;
        $user->state_id = $branch->state_id;
        $user->name = $request->name;

        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // TODO: Send login link to the customer's mobile number and email.

        return redirect()->route('staff.view', [$user->id])->with('status', 'Staff Data Updated');

    }


    public function delete(Request $request)
    {
        if (!empty($request->ids)) {
            User::whereIn('id', $request->ids)->delete();

            return redirect()->route('staff')->with('note', 'Selected staff have been deleted');
        }
    }
}
