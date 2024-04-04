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
            'note' => session('note')
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
            'stkn' => csrf_token()
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'item' => 'string|required|exists:items,name|min:2|max:64',
            'files' => 'required|array',
        ]);

        // Get Item ID
        $item = Item::where('name', $request->item)->first();
        Order::create([
            'user_id' =>  auth()->user()->id,
            'item_id' => $item->id,
            'order_status_id' => 1,
            'detail' => json_encode($request->all()),
            'total_cost' => 0,
        ]);

        return redirect(route('orders'))->with('note', 'Order Submitted');
    }


    public function delete(Request $request)
    {
        if (!empty($request->ids)) {
            Order::whereIn('id', $request->ids)->delete();

            return redirect()->route('orders')->with('note', 'Selected orders have been deleted');
        }
    }
}
