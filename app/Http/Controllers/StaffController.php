<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Role;
use App\Models\State;
use App\Models\User;
use App\Models\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class StaffController extends Controller
{
    protected $rules = [
        'name' => 'required|string|min:5|max:64',
        'mobile' => 'required|numeric|digits_between:7,16|unique:users,mobile',
        'email' => 'nullable|string|email:rfc,dns|unique:users,email',
        'branch' => 'required|string|min:5|max:64|exists:branches,name',
        'role' => 'required|string|min:5|max:64|exists:roles,name',
        'password' => 'required|string|min:8|max:64|confirmed',
        'password_confirmation' => 'required',
    ];

    public function index()
    {
        return Inertia::render('Backend/Staff/List', [
            'endpoint' => route('staff.records'),
            'note' => session('note'),
            'roles' => Role::where('name', '!=', 'Customer')->get(['name']),
            'branches' => Branch::all(['name'])
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
        $customerRole = Role::where('name','Customer')->first();
        $systemAdminRole = Role::where('name', 'System Admin')->first();
        $query = User::query();
        $query->whereNot('role_id', $customerRole->id);
        // $query->whereNot('role_id', $systemAdminRole->id);

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
                if (!empty($filters['role'])) {
                    $query->whereHas('role', function ($q) use ($filters) {
                        $q->where('name', $filters['role']);
                    });
                }
                if (!empty($filters['branch'])) {
                    $query->whereHas('branch', function ($q) use ($filters) {
                        $q->where('name', $filters['branch']);
                    });
                }
            } elseif (is_string($filters) && !empty($filters)) {
                // Fallback for single search string
               $query->where(function($query) use($filters){
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
        $query->with(['branch' => function ($query) {
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
            'account_status' => User::STATUS_ACTIVE,
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
            'logins' => $this->getStaffLogins($id),
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
            'email' => 'nullable|string|email:rfc,dns',
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
        $user->email = $request->email;

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

    public function getStaffLogins($staffId)
    {
        return Login::where('user_id', $staffId)->orderBy('id', 'desc')->limit(20)->get([
            'ip_address',
            'created_at',
            'updated_at',
            'logged_out'
        ]);
    }

    public function filter(Request $request)
    {
        $staffList = [];
        $request->validate([
            'keyword' => 'nullable|string|max:64',
            'branch_id' => 'nullable|integer|exists:branches,id',
            'allow_cross_branch' => 'nullable|boolean'
        ]);

        $keyword = $request->keyword;
        $branchId = $request->branch_id;
        $allowCrossBranch = $request->allow_cross_branch ?? false;
        $customerRole = Role::where('name','Customer')->first();

        $query = User::query();

        // If user is not from Administrative Branch, select their branch (unless cross-branch is allowed)
        if (!auth()->user()->isFromAdministrativeBranch() && !$allowCrossBranch) {
            $query->where('branch_id', auth()->user()->branch_id);
        }

        // If branch_id is provided and cross-branch is not allowed, filter by it
        if ($branchId && !$allowCrossBranch) {
            $query->where('branch_id', $branchId);
        }
        
        $query->with(['branch' => function ($query) {
            $query->select('id', 'name');
        }]);

        $query->whereNot('role_id', $customerRole->id);
        $query->whereNot('id', auth()->id());

        if (!empty($keyword)) {
               $query->where(function($query) use($keyword){
                    $query->where('name', 'LIKE', sprintf('%%%s%%', $keyword))
                         ->orWhere('mobile', 'LIKE', sprintf('%%%s%%', $keyword))
                         ->orWhere('email', 'LIKE', sprintf('%%%s%%', $keyword));
               });
        }

        $records = $query->limit(20)->get(['id', 'name', 'branch_id']);
        // if (!empty($records)) {
        //     foreach ($records as $staff) {
        //         array_push($staffList, $staff->name);
        //     }
        // }

        return $records;
    }
}
