<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CategoriesController extends Controller
{
    protected $rules = [
        'name' => 'required|string|unique:categories,name|min:2|max:64'
    ];

    public function index()
    {
        return Inertia::render('Backend/Category/List', [
            'endpoint' => route('categories.records')
        ]);
    }


    public function records(Request $request)
    {
        $categories = [];
        $categoriesCount = 0;

        $page = $request->page;
        $itemsPerPage = $request->itemsPerPage;
        $sortBys = $request->sortBy;
        $search = $request->search;

        $query = Category::query();

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

        $categoriesCount = $query->count();
        $categories = $query->take($itemsPerPage)
            ->skip($itemsPerPage * ($page - 1))
            ->get([
                'id',
                'name',
            ]);

        return [
            'records' => $categories,
            'totalRecords' => $categoriesCount,
        ];
    }

    public function delete(Request $request)
    {
        $categories = $request->categories;

        if (!empty($categories)) {
            foreach ($categories as $categoryId) {
                $category = Category::where('id', $categoryId)->first();
                if (!empty($category)) {
                    $category->delete();
                }
            }
        }

        $confirmationMessage = [
            'type' => 'success',
            'title' => 'Item Category Deleted',
            'text' => "The Item Category have been permanently deleted.",
            'continueTo' => route('categories')
        ];

        return Redirect::route('contacts.feedback')->with('event', $confirmationMessage);
    }

    public function detail($ref)
    {
        $category = Category::where('id', $ref)->first();

        return Inertia::render('Messages/Identity', [
            'identity' => $category
        ]);
    }

    public function add()
    {
        return Inertia::render('Backend/Category/Add', []);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);

        Category::create($validated);

        return redirect()->route('categories');
    }

    public function edit($ref)
    {
        return Inertia::render('Backend/Category/Edit', [
            'category' => Category::where('id', $ref)->first()
        ]);
    }

    public function update(Request $request, $ref)
    {
        $validated = $request->validate($this->rules);

        $category = Category::where('id', $ref)->first();
    }
}
