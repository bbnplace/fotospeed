<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RolesController extends Controller
{
    public function index()
    {
        return Inertia::render('Backend/Role/List', [
            'endpoint' => route('roles.records'),
            'note' => session('note')
        ]);
    }


    public function records(Request $request)
    {
        $roles = [];
        $rolesCount = 0;

        $page = $request->page;
        $itemsPerPage = $request->itemsPerPage;
        $sortBys = $request->sortBy;
        $search = $request->search;

        $query = Role::query();

        if (!empty($search)) {
            $searchTerm = $search['_value'];
            if (!empty($searchTerm)) {
               $query->where('name', 'LIKE', sprintf('%%%s%%', $searchTerm));
            }
        }

        if (!empty($sortBys)) {
            foreach ($sortBys as $sortBy) {
                $query->orderBy($sortBy['key'], $sortBy['order']);
            }
        }else{
            $query->orderBy('id', 'desc');
        }

        $rolesCount = $query->count();
        $roles = $query->take($itemsPerPage)
            ->skip($itemsPerPage * ($page - 1))
            ->get([
                'id',
                'name',
            ]);

        return [
            'records' => $roles,
            'totalRecords' => $rolesCount,
        ];
    }

    public function detail($ref)
    {
        $role = Role::where('id', $ref)->first();

        return Inertia::render('Messages/Identity', [
            'identity' => $role
        ]);
    }

    public function add()
    {
        return Inertia::render('Backend/Role/Add', []);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name|min:2|max:64'
        ]);

        Role::create($validated);

        return redirect()->route('roles')->with('note', $request->name . ' has been created.');
    }

    private function getRole($roleId)
    {
        return [
            'role' => Role::where('id', $roleId)->first()
        ];
    }

    public function edit($ref)
    {
        return Inertia::render('Backend/Role/Edit', $this->getRole($ref));
    }

    public function view($ref)
    {
        return Inertia::render('Backend/Role/Detail', $this->getRole($ref));
    }

    public function update(Request $request, $ref)
    {
        $role = Role::where('id', $ref)->first();

        if (empty($role)) {
            return redirect()->route('roles')->with('note', 'Select a role to edit');
        }

        $request->validate([
            'name' => $request->name != $role->name ? 'required|string|unique:roles,name|min:2|max:64' : 'required|string|min:2|max:64',
        ]);

        $role->name = $request->name;
        $role->save();

        return redirect()->route('role.view', [$ref])->with('note', 'Updated.');
    }

    public function delete(Request $request)
    {
        if (!empty($request->ids)) {
            Role::whereIn('id', $request->ids)->delete();

            return redirect()->route('roles')->with('note', 'Selected roles have been deleted');
        }
    }
}
