<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Invoice;
use App\Models\InvoiceStatus;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\OrderLog;
use App\Models\Role;
use App\Models\User;
use App\Models\Setting;
use App\Report\ReportBuilder;
use App\Tasks\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class OrdersController extends Controller
{
    protected $rules = [
        'item' => 'string|required|exists:items,name|min:2|max:64',
        'files' => 'nullable|array',
        'customerMobile' => 'nullable|string|min:7|max:14|exists:users,mobile',
        'price' => 'nullable|integer',
        'name' => 'string|required|min:3|max:32',
        'note' => 'string|nullable|max:2000',
        'date' => 'string|nullable|max:64',
        'delivery_date' => 'string',
        'deliveryAddress' => 'string|required|max:200',
        'orderNumber' => 'integer|nullable|digits_between:1,16',
        'quantity' => 'integer|digits_between:1,7',
    ];

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

        // ADMINISTRATORS CAN VIEW ORDERS ACROSS BRANCHES.
        // If the user is not an Administrator, Limit their view to orders from their branch.
        if (!auth()->user()->isAdmin())
        {
            $query->where('branch_id', auth()->user()->branch_id);
        }

        // MANAGERS SHOULD BE ABLE TO VIEW ALL ORDERS WITHIN THEIR BRANCH

        // If the user is not one that can view all orders, fetch orders for their specific role
        if (!auth()->user()->canViewAllOrders())
        {
            $userProcesses = OrderStatus::where('role_id', auth()->user()->role_id)->get(['id']);
            if($userProcesses->count() == 0)
            {
                return [
                    'records' => [],
                    'totalRecords' => 0,
                ];
            }

            $order_status_ids = [];
            foreach($userProcesses as $userProcess)
            {
                array_push($order_status_ids, $userProcess->id);
            }
            $query->whereIn('order_status_id', $order_status_ids);
        }

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
        $settings = Setting::first();
        $minDeliveryDate = 3600 * 24 * $settings->min_order_processing_days;
        $maxDeliveryDate = 3600 * 24 * $settings->max_order_processing_days;

        return [
            'min' => date("Y-m-d", (time() + $minDeliveryDate)),
            'max' => $maxDeliveryDate < 1 ? -1 : date("Y-m-d", time() + $maxDeliveryDate),
        ];
    }

    public function add()
    {
        return Inertia::render('Backend/Order/Add', [
            'items' => Item::getItemsArray(),
            'stkn' => csrf_token(),
            'endpoint' => route('customer.find'),
            'deliveryDate' => $this->getMinAndMaxDeliveryDate(),
            'branches' => Branch::getBranchesArray(),
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate($this->rules);

        // Get Item ID
        $item = Item::where('name', $request->item)->first();

        if (!auth()->user()->isCustomer()) {
            $customerData = User::where('mobile', $request->customerMobile)->first();
        }

        $branch = Branch::where('name', $request->branch)->first();

        $order = Order::create([
            'name' => $request->name,
            'note' => $request->note,
            'user_id' => auth()->user()->isCustomer() ? auth()->user()->id :  $customerData->id,
            'item_id' => $item->id,
            'branch_id' => $branch->id,
            'order_status_id' => 1,
            'detail' => json_encode($request->all()),
            'total_cost' => auth()->user()->isCustomer() ? 0 :  $request->price,
            'date' => date("d"),
            'month' => date("n"),
            'year' => date("Y"),
            'delivery_date' => $request->date,
            'delivery_address' => $request->deliveryAddress,
            'order_number' => $request->orderNumber,
            'quantity' => $request->quantity,
        ]);

        // Generate Tasks and send notifications
        Task::generate($item, $order, 'New');

        $additionalMsg = auth()->user()->isCustomer() ? "You will receive invoice shortly." :  "";
        return redirect(route((auth()->user()->isCustomer() ? 'customer.my-orders' : 'orders')))
            ->with('note', 'Order Submitted.' . $additionalMsg);
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
        $nextProcess = OrderStatus::where('id', $order->orderStatus->next_process)->first(['name']);

        // Get Invoice
        $invoice = Invoice::where('order_id', $order->id)->first();
        $hasInvoice = !empty($invoice);
        $invoicePaid = $hasInvoice && $invoice->invoice_status_id == InvoiceStatus::STATUS_PAID;
        $canEditOrder = auth()->user()->isAdmin() || auth()->user()->isReception();
        $canGenerateInvoice = !$hasInvoice && ($canEditOrder || auth()->user()->isCashier());
        $canRegenerateInvoice = $hasInvoice && !$invoicePaid;

        return [
            'order' => $order,
            'nextProcess' => $nextProcess->name ?? null,
            'orderDetail' => $orderDetail,
            'items' => Item::getItemsArray(),
            'branches' => Branch::getBranchesArray(),
            'orderStatuses' => OrderStatus::getOrderStatusesArray(),
            'stkn' => csrf_token(),
            'endpoint' => route('customer.find'),
            'deliveryDate' => $this->getMinAndMaxDeliveryDate(),
            'activities' => $this->getOrderActivityLog($id),
            'hasInvoice' => $hasInvoice,
            'canGenerateInvoice' => $canGenerateInvoice,
            'invoicePaid' => $invoicePaid,
            'canEditOrder' => $canEditOrder,
            'canRegenerateInvoice' => $canRegenerateInvoice,
        ];
    }

    public function edit($id)
    {
        return Inertia::render('Backend/Order/Edit', $this->getOrder($id));
    }

    public function view($id)
    {
        return Inertia::render('Backend/Order/Detail', $this->getOrder($id));
    }

    public function update(Request $request, $id)
    {
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

        $order = Order::where('id', $id)->first();

        if (empty($order)) {
            return redirect(route('orders'));
        }

        $branch = Branch::where('name', $request->branch)->first();

        // TODO: If this update is made by receptionist or management, check for price change and notify customer of the bill
        $order->name = $request->name;
        $order->note = $request->note;
        $order->item_id = $item->id;
        $order->branch_id = $branch->id;
        $order->detail = json_encode($request->all());
        $order->delivery_date = $request->date;
        $order->delivery_address = $request->deliveryAddress;
        $order->quantity = $request->quantity;

        if ((string) $order->order_number !== (string) $request->orderNumber){
            $order->order_number = $request->orderNumber;
        }

        if (!in_array($role->name, ['Customer', 'Production'])) {
            $order->total_cost = $request->price;
        }

        $order->save();

        return redirect($role->name == 'Customer' ? route('customer.my-orders') : route('order.view', [$order->id]))->with('note', 'Order Updated');
    }

    public function delete(Request $request)
    {
        if (!empty($request->ids)) {
            Order::whereIn('id', $request->ids)->delete();

            return redirect()->route('orders')->with('note', 'Selected orders have been deleted');
        }
    }

    private function canCancelOrder(Order $order): bool
    {
        $canCancel = false;
        $processData = json_decode($order->item->process_data);
        $itemProcesses = $processData->processes;
        foreach ($itemProcesses as $itemProcess) {
            if (!empty($order->orderStatus->name) && strtolower($itemProcess->name) == strtolower($order->orderStatus->name)) {
                $canCancel = $itemProcess->canCancelOrder ?? false;
                break;
            }
        }
        return $canCancel;
    }

    public function cancel(Request $request, $orderId)
    {
        $order = Order::find($orderId);
       
        if ($this->canCancelOrder($order)) {
            // Set Order status to Cancelled
            $order->order_status_id = OrderStatus::STATUS_CANCELLED;
            $order->save();

            // Update report that order is cancelled
            ReportBuilder::build('cancelled');

            // Todo: Log the ID of the user that cancelled the order
            // Todo: Send notification to all team members with uncompleted tasks that order has been cancelled
            // Todo: Send notification to customer that order has been cancelled.

            return [
                'response' => 'Success',
                'orderStatus' => 'Cancelled',
            ];
        } else {
            return [
                'response' => 'Cannot cancel',
                'orderStatus' => $order->orderStatus->name,
            ];
        }
    }

    private function getOrderActivityLog($orderId)
    {
        // Fetch the Activity Log for the order. This shows who worked on what.
        $query = OrderLog::query();
        $query->where('order_id', $orderId);

        $query->with('staff', function ($query){
            $query->select('id', 'name');
        });

        $query->with('process', function ($query){
            $query->select('id', 'name');
        });

        return $query->get();
    }

}
