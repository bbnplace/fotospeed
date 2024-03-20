<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class StatesController extends Controller
{
    protected $rules = [
        'name' => 'required|string|unique:states,name'
    ];

    public function index()
    {
        return Inertia::render('Backend/State/List', [
            'endpoint' => route('state.records')
        ]);
    }

    public function records(Request $request)
    {
        $states = [];
        $statesCount = 0;

        $page = $request->page;
        $itemsPerPage = $request->itemsPerPage;
        $sortBys = $request->sortBy;
        $search = $request->search;

        $query = State::query();

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

        $statesCount = $query->count();
        $states = $query->take($itemsPerPage)
            ->skip($itemsPerPage * ($page - 1))
            ->get([
                'id',
                'name'
            ]);

        return [
            'records' => $states,
            'totalRecords' => $statesCount,
        ];
    }

    public function add()
    {
        return Inertia::render('Backend/State/Add', [
        ]);
    }

    public function store(Request $request) {
        $validated = $request->validate($this->rules);

        State::create($validated);

        return redirect()->route('states')->with('status', 'New State Added');
    }

    private function getState($id)
    {
        $state = State::where('id', $id)->first();
        return [
            'state' => $state
        ];
    }

    public function edit($id)
    {
        return Inertia::render('Backend/State/Edit', $this->getState($id));
    }

    public function view($id)
    {
        return Inertia::render('Backend/State/Detail', $this->getState($id));
    }

    public function update(Request $request, $ref)
    {
        $state = State::where('id', $ref)->first();
        if (empty($state)) {
            return redirect()->route('states');
        }

        $validated = $request->validate($this->rules);

        $state->name = $request->name;
        $state->save();

        return redirect()->route('states')->with('status', 'State Updated');
    }


    public function delete(Request $request)
    {
        dd($request->all());
    }
}
