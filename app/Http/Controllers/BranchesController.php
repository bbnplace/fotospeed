<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\State;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BranchesController extends Controller
{
    protected $rules = [
        'name' => 'required|string|unique:branches,name|min:2|max:64',
        'address' => 'required|string|min:10|max:200',
        'state' => 'required|string|exists:states,name|min:2|max:64',
    ];

    public function index()
    {
        return Inertia::render('Backend/Branch/List', [
            'endpoint' => route('branches.records')
        ]);
    }

    public function records(Request $request)
    {
        $branches = [];
        $branchesCount = 0;

        $page = $request->page;
        $itemsPerPage = $request->itemsPerPage;
        $sortBys = $request->sortBy;
        $search = $request->search;

        $query = Branch::query();
        $query->with(['state' => function ($query) {
            $query->select('id', 'name');
        }]);

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

        $branchesCount = $query->count();
        $branches = $query->take($itemsPerPage)
            ->skip($itemsPerPage * ($page - 1))
            ->get();

        return [
            'records' => $branches,
            'totalRecords' => $branchesCount,
        ];
    }

    public function add()
    {
        return Inertia::render('Backend/Branch/Add', [
            'states' => State::getStatesArray()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->rules);

        $state = State::where('name', $request->state)->first();
        Branch::create([
            'name' => $request->name,
            'address' => $request->address,
            'state_id' => $state->id
        ]);

        return redirect()->route('branches')->with('status', 'Successful');
    }


    public function edit($ref)
    {
        $query = Branch::query();
        $query->where('id', $ref);
        $query->with(['state' => function ($query) {
            $query->select('id', 'name');
        }]);

        $branch = $query->first();

        return Inertia::render('Backend/Branch/Edit', [
            'states' => State::getStatesArray(),
            'branch' => $branch
        ]);
    }

    public function update(Request $request, $ref)
    {

    }
}
