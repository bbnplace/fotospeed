<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Item;
use App\Models\OrderStatus;
use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ItemsController extends Controller
{

    private function getRules()
    {
        return [
            'name' => 'string|required|unique:items,name|min:2|max:64',
            'category' => 'string|required|exists:categories,name|min:2|max:64',
            'description' => 'required|string|min:24|max:1000',
            'height' => 'required|string|min:3|max:12',
            'width' => 'required|string|min:3|max:12',
            'weight' => 'nullable|string|min:3|max:12',
            'print_price' => 'nullable|integer|digits_between:2,9',
            'sheet_price' => 'nullable|integer|digits_between:2,9',
            'cover_print_price' => 'nullable|integer|digits_between:2,9',
            'primary_production_branch' => 'required|string|min:2|max:124|exists:branches,name',
            'production_branches'=> 'required|array',
            'production_branches.*' => sprintf('in:%s', implode(',', Branch::getBranchesArray())),
        ];
    }

    public function index()
    {
        return Inertia::render('Backend/Item/List', [
            'endpoint' => route('items.records'),
            'note' => session('note'),
        ]);
    }

    public function records(Request $request)
    {
        $items = [];
        $itemsCount = 0;

        $page = $request->page;
        $itemsPerPage = $request->itemsPerPage;
        $sortBys = $request->sortBy;
        $search = $request->search;

        $query = Item::query();
        $query->with(['category' => function ($query) {
            $query->select('id', 'name');
        }]);

        if (!empty($search)) {
            $searchTerm = $search['_value'];
            if (!empty($searchTerm)) {
               $query->where('name', 'LIKE', sprintf('%%%s%%', $searchTerm));
               $query->where('height', 'LIKE', sprintf('%%%s%%', $searchTerm));
               $query->where('width', 'LIKE', sprintf('%%%s%%', $searchTerm));
               $query->where('weight', 'LIKE', sprintf('%%%s%%', $searchTerm));
               $query->where('print_price', 'LIKE', sprintf('%%%s%%', $searchTerm));
               $query->where('cover_print_price', 'LIKE', sprintf('%%%s%%', $searchTerm));
               $query->where('sheet_price', 'LIKE', sprintf('%%%s%%', $searchTerm));
            }
        }

        if (!empty($sortBys)) {
            foreach ($sortBys as $sortBy) {
                $query->orderBy($sortBy['key'], $sortBy['order']);
            }
        }else{
            $query->orderBy('id', 'desc');
        }

        $itemsCount = $query->count();
        $items = $query->take($itemsPerPage)
            ->skip($itemsPerPage * ($page - 1))
            ->get();

        return [
            'records' => $items,
            'totalRecords' => $itemsCount,
        ];
    }

    public function add()
    {
        return Inertia::render('Backend/Item/Add', [
            'categories' => Category::getCategoriesArray(),
            'branches' => Branch::getBranchesArray(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->getRules());
        $category = Category::where('name', $request->category)->first();

        Item::create([
            'category_id' => $category->id,
            'name' => $request->name,
            'description' => $request->description,
            'height' => $request->height,
            'width' => $request->width,
            'weight' => $request->weight,
            'print_price' => $request->print_price,
            'sheet_price' => $request->sheet_price,
            'cover_print_price' => $request->cover_print_price,
            'primary_order_processing_branch' => $request->primary_production_branch,
            'order_processing_branches' => json_encode($request->production_branches),
        ]);

        return redirect()->route('items')->with('note', 'Item Registered');
    }

    private function getItem($id)
    {
        $query = Item::query();
        $query->where('id', $id);
        $query->with(['category' => function($query){
            $query->select('id', 'name');
        }]);

        $item = $query->first();

        return [
            'item' => $item,
            'categories' => Category::getCategoriesArray(),
            'processes' => OrderStatus::getOrderStatusesArray(),
            'teams' => Role::getRolesArray(),
            'branches' => Branch::getBranchesArray(),
        ];
    }

    public function edit($id)
    {
        return Inertia::render('Backend/Item/Edit', $this->getItem($id));
    }

    public function view($id)
    {
        return Inertia::render('Backend/Item/Detail', $this->getItem($id));
    }

    public function update(Request $request, $id)
    {
        $item = Item::where('id', $id)->first();
        if (empty($item)) {
            return redirect()->route('items')->with('note', 'Select an item to edit.');
        }

        // Validate the submitted data
        $rules = $this->getRules();
        if ($item->name == $request->name) {
            $rules['name'] = 'string|required|min:2|max:64';
        }
        $request->validate($rules);

        $category = Category::where('name', $request->category)->first();

        // Save changes
        $item->category_id = $category->id;
        $item->name = $request->name;
        $item->description = $request->description;
        $item->height = $request->height;
        $item->width = $request->width;
        $item->weight = $request->weight;
        $item->print_price = $request->print_price;
        $item->sheet_price = $request->sheet_price;
        $item->cover_print_price = $request->cover_print_price;
        $item->primary_order_processing_branch = $request->primary_production_branch;
        $item->order_processing_branches = $request->production_branches;
        $item->save();

        return redirect()->route('item.view', [$item->id])->with('note', 'Updated.');
    }

    public function delete(Request $request)
    {
        if (!empty($request->ids)) {
            Item::whereIn('id', $request->ids)->delete();

            return redirect()->route('items')->with('note', 'Selected items have been deleted');
        }
    }

    public function saveProcessData(Request $request, $id)
    {
        $item = Item::find($id);
        $item->process_data = $request->data;
        $item->save();

        return [
            'status' => 'Saved'
        ];
    }
}
