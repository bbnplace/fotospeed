<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ItemsController extends Controller
{
    protected $rules = [
        'name' => 'string|required|unique:items,name|min:2|max:64',
        'category' => 'string|required|exists:categories,name|min:2|max:64',
        'description' => 'required|string|min:24|max:1000',
        'height' => 'required|string|min:3|max:12',
        'width' => 'required|string|min:3|max:12',
        'weight' => 'required|string|min:3|max:12',
        'print_price' => 'required|string|min:3|max:12',
        'sheet_price' => 'required|string|min:3|max:12',
        'cover_print_price' => 'required|string|min:3|max:12',
    ];

    public function index()
    {
        return Inertia::render('Backend/Item/List', [
            'endpoint' => route('items.records')
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
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);
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
        ]);

        return redirect()->route('items')->with('status', 'Item Registered');
    }


    public function edit($ref)
    {
        $query = Item::query();
        $query->where('id', $ref);
        $query->with(['category' => function($query){
            $query->select('id', 'name');
        }]);

        $item = $query->first();

        return Inertia::render('Backend/Item/Edit', [
            'item' => $item,
            'categories' => Category::getCategoriesArray(),
        ]);
    }
}
