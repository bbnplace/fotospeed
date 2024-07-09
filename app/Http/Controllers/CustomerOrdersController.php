<?php

namespace App\Http\Controllers;

use App\Events\AnnounceNewOrder;
use App\Models\Branch;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CustomerOrdersController extends Controller
{
    protected $rules = [
        'item' => 'string|required|exists:items,name|min:2|max:64',
        'files' => 'required|array',
        'customerMobile' => 'nullable|string|min:7|max:14|exists:users,mobile',
        'price' => 'nullable|integer',
        'name' => 'string|required|min:3|max:32',
        'note' => 'string|nullable|max:2000',
        'date' => 'string|nullable|max:64',
        'delivery_date' => 'string',
        'branch' => 'string|required|exists:branches,name',
        'deliveryAddress' => 'string|nullable|max:200',
    ];

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


    private function getMinAndMaxDeliveryDate ()
    {
        $twoDaysTime = 3600 * 24 * 2;
        $thirtyDaysTime = 3600 * 24 * 30;

        return [
            'min' => date("Y-m-d", time() + $twoDaysTime),
            'max' => date("Y-m-d", time() + $thirtyDaysTime),
        ];
    }

    public function add()
    {
        // TODO: Define a setting that will allow Administrator to setup the minimum delivery date.
        return Inertia::render('Client/Order/Add', [
            'items' => Item::getItemsArray(),
            'branches' => Branch::getBranchesArray(),
            'stkn' => csrf_token(),
            'endpoint' => route('customer.find'),
            'deliveryDate' => $this->getMinAndMaxDeliveryDate(),
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate($this->rules);

        // Get Item ID
        $item = Item::where('name', $request->item)->first();
        // Get user role
        $role = Role::where('id', auth()->user()->role_id)->first();
        if (!empty($request->customerMobile) && $role->name != 'Customer') {
            $customerData = User::where('mobile', $request->customerData)->first();
            if (empty($customerData)) {
                // Return with validation error
            }
        }

        // Get Branch
        $branch = Branch::where('name', $request->branch)->first();

        Order::create([
            'name' => $request->name,
            'note' => $request->note,
            'user_id' => $role->name == 'Customer' ? auth()->user()->id :  $customerData->id,
            'item_id' => $item->id,
            'branch_id' => $branch->id,
            'order_status_id' => 1,
            'detail' => json_encode($request->all()),
            'total_cost' => $role->name == 'Customer' ? 0 :  $request->price,
            'date' => date("d"),
            'month' => date("n"),
            'year' => date("Y"),
            'delivery_date' => $request->date,
            'delivery_address' => $request->deliveryAddress,
        ]);

        $this->sendOrderNotification();

        return redirect(route('customer.my-orders'))->with('note', 'Order Submitted');
    }

    // This method will broadcast a notification about this order to  the Reception at the target branch
    private function sendOrderNotification()
    {
        $message = sprintf("You just received a new Order");
        broadcast(new AnnounceNewOrder($message, auth()->user()->branch_id))->toOthers();
    }


    private function getOrder($id)
    {
        $query = Order::query();
        $query->where('id', $id);
        $query->with(['item' => function($query){
            $query->select('id', 'name');
        }]);
        $query->with(['user' => function ($query){
            $query->select('id', 'name', 'mobile');
        }]);
        $query->with(['branch' => function ($query){
            $query->select('id', 'name');
        }]);
        $query->with(['orderStatus' => function ($query){
            $query->select('id', 'name', 'next_process');
        }]);

        $order = $query->first();
        $orderDetail = json_decode($order->detail);
        if(!empty($orderDetail->files)) {
            for($i=0; $i<count($orderDetail->files); $i++)
            {
                $data = Storage::get($orderDetail->files[$i]->file->uploadedFile);
                $orderDetail->files[$i]->file->dataURL = sprintf('data:%s;base64,%s', $orderDetail->files[$i]->file->fileInfo->type, base64_encode($data));
            }
        }

        return [
            'order' => $order,
            'orderDetail' => $orderDetail,
            'items' => Item::getItemsArray(),
            'orderStatuses' => OrderStatus::getOrderStatusesArray(),
            'stkn' => csrf_token(),
            'endpoint' => route('customer.find'),
            'deliveryDate' => $this->getMinAndMaxDeliveryDate(),
        ];
    }

    public function edit($id)
    {
        return Inertia::render('Client/Order/Edit', $this->getOrder($id));
    }

    public function view($id)
    {
        return Inertia::render('Client/Order/Detail', $this->getOrder($id));
    }
}
