<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GroupsController extends Controller
{

    protected $rules = [
        'name' => 'required|string|unique:groups,name|min:2|max:64',
        'description' => 'nullable|string|min:36|max:1000'
    ];

    public function index()
    {
        return Inertia::render('Backend/Group/List', [
            'endpoint' => route('group.records')
        ]);
    }

    public function records(Request $request)
        {
            $groups = [];
            $groupsCount = 0;

            $page = $request->page;
            $groupsPerPage = $request->itemsPerPage;
            $sortBys = $request->sortBy;
            $search = $request->search;

            $query = Group::query();

            if (!empty($search)) {
                $searchTerm = $search['_value'];
                if (!empty($searchTerm)) {
                   $query->where('name', 'LIKE', sprintf('%%%s%%', $searchTerm));
                   $query->where('description', 'LIKE', sprintf('%%%s%%', $searchTerm));
                }
            }

            if (!empty($sortBys)) {
                foreach ($sortBys as $sortBy) {
                    $query->orderBy($sortBy['key'], $sortBy['order']);
                }
            }else{
                $query->orderBy('id', 'desc');
            }

            $groupsCount = $query->count();
            $groups = $query->take($groupsPerPage)
                ->skip($groupsPerPage * ($page - 1))
                ->get();

            return [
                'records' => $groups,
                'totalRecords' => $groupsCount,
            ];
        }

    public function add()
    {
        return Inertia::render('Backend/Group/Add', [
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);

        // Save the record to database
        Group::create($validated);

        return redirect()->route('groups')->with('status', 'Group Created');
    }


    public function edit($ref)
    {
        $group = Group::where('id', $ref)->first();
        return Inertia::render('Backend/Group/Edit', [
            'group' => $group,
        ]);
    }
}
