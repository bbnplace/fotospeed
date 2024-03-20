<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrdersController extends Controller
{
    public function index()
    {
        return Inertia::render('Backend/Order/List', [
            'endpoint' => route('order.records'),
        ]);
    }

    public function records(Request $request)
    {
        $orders = [];
        $ordersCount = 0;

        $page = $request->page;
        $ordersPerPage = $request->itemsPerPage;
        $sortBys = $request->sortBy;
        $search = $request->search;

        $query = Order::query();
        $query->with(['user' => function ($query){
            $query->select('id', 'name');
        }]);
        $query->with(['orderStatus' => function ($query){
            $query->select('id', 'name');
        }]);

        if (!empty($search)) {
            $searchTerm = $search['_value'];
            if (!empty($searchTerm)) {
            //    $query->where('source', 'LIKE', sprintf('%%%s%%', $searchTerm));
            //    $query->where('receiver', 'LIKE', sprintf('%%%s%%', $searchTerm));
            //    $query->where('order', 'LIKE', sprintf('%%%s%%', $searchTerm));
            }
        }

        if (!empty($sortBys)) {
            foreach ($sortBys as $sortBy) {
                $query->orderBy($sortBy['key'], $sortBy['order']);
            }
        }else{
            $query->orderBy('id', 'desc');
        }

        $ordersCount = $query->count();
        $orders = $query->take($ordersPerPage)
            ->skip($ordersPerPage * ($page - 1))
            ->get();

        return [
            'records' => $orders,
            'totalRecords' => $ordersCount,
        ];
    }

    public function add()
    {
        return Inertia::render('Backend/OrderCreate', [
            'items' => Item::getItemsArray(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([

        ]);
    }


    public function delete(Request $request)
    {
        dd($request->all());
    }
}
