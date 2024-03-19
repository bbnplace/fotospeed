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

    public function getGroup($groupId)
    {
        $group = Group::where('id', $groupId)->first();

        return [
            'group' => $group,
        ];
    }


    public function edit($id)
    {
        return Inertia::render('Backend/Group/Edit', $this->getGroup($id));
    }

    public function view($id)
    {
        return Inertia::render('Backend/Group/Detail', $this->getGroup($id));
    }

    public function update(Request $request, $id)
    {
        $group = Group::where('id', $id)->first();

        if(empty($group)){
            return redirect()->route('groups')->with('note', 'Select the group you want to edit');
        }

        $request->validate([
            'name' => $group->name != $request->name ? 'required|string|unique:groups,name|min:2|max:64' : 'required|string|min:2|max:64',
            'description' => 'nullable|string|min:36|max:1000'
        ]);

        $group->name = $request->name;
        $group->description = $request->description;
        $group->save();

        return redirect()->route('group.view', [$id])->with('note', 'Updated.');
    }
}
