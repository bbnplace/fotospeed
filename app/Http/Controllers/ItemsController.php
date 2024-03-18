<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ItemsController extends Controller
{
    public function index()
    {
        return Inertia::render('Backend/Item/List', [

        ]);
    }

    public function add()
    {
        return Inertia::render('Backend/Item/Add', [
            'categories' => Category::getCategoriesArray()
        ]);
    }


    public function edit($ref)
    {
        $item = Item::where('id', $ref)->first();

        return Inertia::render('Backend/Item/Edit', [
            'item' => $item,
            'categories' => Category::getCategoriesArray(),
        ]);
    }
}
