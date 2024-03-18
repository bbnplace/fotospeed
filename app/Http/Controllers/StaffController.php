<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StaffController extends Controller
{
    public function index()
    {
        return Inertia::render('Backend/Staff/List', [
            'endpoint' => route('staff.records')
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
            'states' => State::getStatesArray()
        ]);
    }


    public function edit($ref)
    {
        $query = User::query();
        $query->where('id', $ref);
        $query->with(['role' => function ($query) {
            $query->select('id', 'name');
        }]);
        $query->with(['state' => function ($query) {
            $query->select('id', 'name');
        }]);
        $user = $query->first();

        return Inertia::render('Backend/Staff/Edit', [
            'states' => State::getStatesArray(),
            'staff' => $user
        ]);
    }
}
