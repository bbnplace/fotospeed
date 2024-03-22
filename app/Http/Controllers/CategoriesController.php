<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CategoriesController extends Controller
{

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
        $validated = $request->validate([
            'name' => 'required|string|unique:categories,name|min:2|max:64'
        ]);

        Category::create($validated);

        return redirect()->route('categories');
    }

    private function getCategory($categoryId)
    {
        return [
            'category' => Category::where('id', $categoryId)->first()
        ];
    }

    public function edit($ref)
    {
        return Inertia::render('Backend/Category/Edit', $this->getCategory($ref));
    }

    public function view($ref)
    {
        return Inertia::render('Backend/Category/Detail', $this->getCategory($ref));
    }

    public function update(Request $request, $ref)
    {
        $category = Category::where('id', $ref)->first();

        if (empty($category)) {
            return redirect()->route('branches')->with('note', 'Select a branch to edit');
        }

        $request->validate([
            'name' => $request->name != $category->name ? 'required|string|unique:categories,name|min:2|max:64' : 'required|string|min:2|max:64',
        ]);

        $category->name = $request->name;
        $category->save();

        return redirect()->route('category.view', [$ref])->with('note', 'Updated.');
    }

    public function delete(Request $request)
    {
        if (!empty($request->ids)) {
            Category::whereIn('id', $request->ids)->delete();

            return redirect()->route('categories')->with('note', 'Selected categories have been deleted');
        }
    }
}
