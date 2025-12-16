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
            if (is_array($search)) {
                // Apply individual filters
                if (!empty($search['status'])) {
                    $query->whereHas('orderStatus', function ($q) use ($search) {
                        $q->where('name', $search['status']);
                    });
                }
                if (!empty($search['order_number'])) {
                    $query->where('id', 'like', "%{$search['order_number']}%");
                }
                if (!empty($search['item_name'])) {
                    $query->whereHas('item', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search['item_name']}%");
                    });
                }
                if (!empty($search['start_date'])) {
                    $query->whereDate('created_at', '>=', $search['start_date']);
                }
                if (!empty($search['end_date'])) {
                    $query->whereDate('created_at', '<=', $search['end_date']);
                }
                if (!empty($search['min_amount'])) {
                    $query->where('total_cost', '>=', $search['min_amount']);
                }
                if (!empty($search['max_amount'])) {
                    $query->where('total_cost', '<=', $search['max_amount']);
                }
            } else {
                // Simple string search
                $searchTerm = $search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('id', 'like', "%{$searchTerm}%")
                      ->orWhereHas('item', function ($subQ) use ($searchTerm) {
                          $subQ->where('name', 'like', "%{$searchTerm}%");
                      });
                });
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
