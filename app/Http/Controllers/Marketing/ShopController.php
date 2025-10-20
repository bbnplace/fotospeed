<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Category as ProductCategory;
use App\Models\Item;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $search = null;
        $category = null;
        $categories = ProductCategory::withCount([
            'items as items_count' => function ($query) {
                $query->whereNotNull('process_data');
            }
        ])->get();
        $perPage = 12; // Set items per page

        if ($request->has('category') && $request->has('search')) {
            $slug = $request->input('category');
            $search = $request->input('search');
            $category = ProductCategory::where('slug', $slug)->firstOrFail();
            $products = Item::where('category_id', $category->id)
                ->where(function($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('description', 'like', "%{$search}%");
                })->paginate($perPage);
        }
        elseif ($request->has('category')) {
            $slug = $request->input('category');
            $category = ProductCategory::where('slug', $slug)->firstOrFail();
            $products = Item::where('category_id', $category->id)->whereNotNull('process_data')->paginate($perPage);
        }
        elseif ($request->has('search')) {
            $search = $request->input('search');
            $products = Item::whereNotNull('process_data')
                ->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->paginate($perPage);
        }
        else {
            $products = Item::whereNotNull('process_data')->paginate($perPage);
        }

        return Inertia::render('Shop', [
            'title' => 'Shop',
            'description' => 'Explore our products and make your purchase online.',
            'page' => 'shop',
            'categories' => $categories,
            'products' => $products, // Paginated result
            'category' => $category,
            'search' => $search,
            'filters' => $request->only(['search', 'category']),
        ]);
    }

    // public function category($slug)
    // {
    //     $categories = ProductCategory::withCount('items')->get();
        

    //     return Inertia::render('Shop', [
    //         'title' => 'Shop Category',
    //         'description' => 'Explore products in this category.',
    //         'page' => 'shop-category',
    //         'categories' => ProductCategory::withCount('items')->get(),
    //         'category' => $category,
    //         'products' => $products,
    //     ]);
    // }

    public function details($slug)
    {
        $product = Item::where('slug', $slug)->firstOrFail();
        Item::where('category_id', $product->category_id)->get();

        return Inertia::render('ProductDetails', [
            'title' => 'Product Details',
            'description' => 'Detailed view of the product.',
            'page' => 'shop-details',
            'categories' => ProductCategory::withCount('items')->get(),
            'product' => $product,
        ]);
        
    }
}
