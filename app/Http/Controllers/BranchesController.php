<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\State;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BranchesController extends Controller
{
    public function index()
    {
        return Inertia::render('Backend/Branch/List', [
            'endpoint' => route('branches.records'),
            'status' => session('status')
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
        $request->validate([
            'name' => 'required|string|unique:branches,name|min:2|max:64',
            'address' => 'required|string|min:10|max:200',
            'state' => 'required|string|exists:states,name|min:2|max:64',
        ]);

        $state = State::where('name', $request->state)->first();
        Branch::create([
            'name' => $request->name,
            'address' => $request->address,
            'state_id' => $state->id
        ]);

        return redirect()->route('branches')->with('status', $request->name . ' branch has been registered.');
    }


    public function edit($id)
    {
        return Inertia::render('Backend/Branch/Edit', $this->getBranchInfo($id));
    }

    public function update(Request $request, $id)
    {
        $state = State::where('name', $request->state)->first();

        $branch = Branch::where('id', $id)->first();
        if (empty($branch)) {
            return redirect()->route('branches')->with('note', 'Select a branch to edit');
        }

        $request->validate([
            'name' => $branch->name != $request->name ? 'required|string|unique:branches,name|min:2|max:64' : 'required|string|min:2|max:64',
            'address' => 'required|string|min:10|max:200',
            'state' => 'required|string|exists:states,name|min:2|max:64',
        ]);

        $branch->name = $request->name;
        $branch->address = $request->address;
        $branch->state_id = $state->id;
        $branch->save();

        return redirect()->route('branch.view', [$id])->with('note', 'Updated.');
    }

    public function view($id)
    {
        return Inertia::render('Backend/Branch/Detail', $this->getBranchInfo($id));
    }


    private function getBranchInfo($branchId)
    {
        $query = Branch::query();
        $query->where('id', $branchId);
        $query->with(['state' => function ($query) {
            $query->select('id', 'name');
        }]);

        $branch = $query->first();

        return [
            'states' => State::getStatesArray(),
            'branch' => $branch
        ];
    }

    public function delete(Request $request)
    {
        if (!empty($request->ids)) {
            // Branch::whereIn('id', $request->ids)->delete();

            return redirect()->route('branches', [], 303)->with('status', 'Selected branches have been deleted');
        }
    }

}
