<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerDashboardController extends Controller
{
    public function home()
    {
        return Inertia::render('Client/Home');
    }

    public function add()
    {
        return Inertia::render('Client/Order/Add', [
            'items' => Item::getItemsArray(),
            'stkn' => csrf_token(),
        ]);
    }

    public function index()
    {
        return Inertia::render('Client/Order/List', [
            'endpoint' => route('customer.order-records'),
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
        $query->where('user_id', auth()->user()->id);
        $query->with(['user' => function ($query){
            $query->select('id', 'name');
        }]);
        $query->with(['item' => function ($query){
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
    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . auth()->id(),
        ]);

        $user = auth()->user();
        $user->email = $request->email;
        $user->save();

        return [
            'status' => 'success',
            'message' => 'Email updated successfully',
            'user' => $user
        ];
    }
}
