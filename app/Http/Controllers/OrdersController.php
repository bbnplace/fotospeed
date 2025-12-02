<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Invoice;
use App\Models\InvoiceStatus;
use App\Models\Item;
use App\Models\Media;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\OrderLog;
use App\Models\Process;
use App\Models\Role;
use App\Models\TaskStatus;
use App\Models\User;
use App\Models\Setting;
use App\Notifications\GenericNotification;
use App\Report\ReportBuilder;
use App\Tasks\Task;
use App\Models\Task as TaskModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Messaging\WhatsAppClient;

class OrdersController extends Controller
{
    protected $rules = [
        'item' => 'string|required|exists:items,name|min:2|max:64',
        'files' => 'nullable|array',
        'customerMobile' => 'nullable|string|min:7|max:14|exists:users,mobile',
        'price' => 'nullable|integer',
        'name' => 'string|required|min:3|max:32',
        'note' => 'string|nullable|max:2000',
        'date' => 'string|required|max:64',
        // 'delivery_date' => 'required|string',
        'deliveryAddress' => 'string|required|max:200',
        'orderNumber' => 'integer|nullable|digits_between:1,16',
        'quantity' => 'integer|digits_between:1,7',
        'newCustomer' => 'required|boolean',
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
        // If user is not from Administrative Branch, only fetch order from their branch
        if (!auth()->user()->isFromAdministrativeBranch())
        {
            $query->where('order_branch_id', auth()->user()->branch_id);
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
        $query->with(['processingBranch' => function ($query){
            $query->select('id', 'name');
        }]);
        $query->with(['sourceBranch' => function ($query){
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
        if ($request->newCustomer) {
            $this->rules['customerMobile'] = 'required|string|min:7|max:14|unique:users,mobile,NULL,id,deleted_at,NULL';
            $this->rules['customerName'] = 'required|string|min:5|max:64';
            $this->rules['customerEmail'] = 'nullable|max:124|email:rfc,dns|unique:users,email,NULL,id,deleted_at,NULL';
            $this->rules['password'] = 'required|string|min:6|max:64';
        }
        $request->validate($this->rules);

        // Get Item ID
        $item = Item::where('name', $request->item)->first();
        $processData = json_decode($item->process_data);

        if (empty($processData->processes)) {
            return redirect()->back()->with('error', 'This product cannot be ordered as it has no production process defined. Please contact support.');
        }

        $firstProcess = $processData->processes[0];
        $process = Process::where('name', $firstProcess->name)->first();

        if (empty($process)) {
            return redirect()->back()->with('error', 'The initial process for this product is invalid. Please contact support.');
        }

        // Register customer if this is a new customer OR restore soft-deleted customer
        if ($request->newCustomer) {
            // Check if a soft-deleted user exists with this mobile number
            $existingUser = User::withTrashed()->where('mobile', $request->customerMobile)->first();

            if ($existingUser && $existingUser->trashed()) {
                // Restore the soft-deleted user
                $existingUser->restore();
                
                // Update user information
                $existingUser->name = $request->customerName;
                $existingUser->email = $request->customerEmail;
                $existingUser->password = Hash::make($request->password);
                $existingUser->state_id = auth()->user()->state_id;
                $existingUser->branch_id = auth()->user()->branch_id;
                $existingUser->save();
                
                $customer = $existingUser;
            } else {
                // Create new customer
                $customer = User::create([
                    'role_id' => Role::CUSTOMER,
                    'name' => $request->customerName,
                    'email' => $request->customerEmail,
                    'mobile' => $request->customerMobile,
                    'state_id' => auth()->user()->state_id,
                    'password' => Hash::make($request->password),
                    'branch_id' => auth()->user()->branch_id,
                ]);
            }

            // Send Welcome WhatsApp Message
            $settings = Setting::first();
            if (!empty($settings->customer_creation_whatsapp_template)) {
                $waConfig = [
                    'customer' => $customer,
                    'password' => $request->password,
                ];
                $waClient = new WhatsAppClient($waConfig);
                $waClient->sendCustomerMessage($settings->customer_creation_whatsapp_template);
            }
        }


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
            'order_branch_id' => auth()->user()->branch_id ?? $branch->id,
            'process_id' => $process->id,
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

        // Link order to uploaded media
        if (!empty($request['files'])) {
            foreach ($request['files'] as $mediaRecord) {
                Media::linkOrder($mediaRecord['file']['mediaId'], $order->id);
            }
        }

        // Send WhatsApp Confirmation
        $customer = auth()->user()->isCustomer() ? auth()->user() : $customerData;
        $waConfig = [
            'order' => $order,
            'customer' => $customer,
        ];
        $waClient = new WhatsAppClient($waConfig);
        $waClient->sendCustomerMessage('order_management_6');

        // Trigger the first task for this order
        Task::assignProcessTasks($item, $order, $firstProcess->name);
        

        $additionalMsg = auth()->user()->isCustomer() ? "You will receive invoice shortly." :  "";
        return redirect(route((auth()->user()->isCustomer() ? 'customer.my-orders' : 'orders')))
            ->with('note', 'Order Submitted.' . $additionalMsg);
    }

    private function getOrderProcessCoordinatorRole(Order $order): Role | null
    {
        $processCoordinatorRole = null;
        if (!empty($order->current_coordinator_role)) {
            $processCoordinatorRole = Role::where('name', $order->current_coordinator_role)->first();
        }

        return $processCoordinatorRole;
    }

    private function getOrderTasksInProcess(Order $order)
    {
        return TaskModel::where('order_id', $order->id)
            ->whereNot('task_status_id', TaskStatus::STATUS_DONE)
            ->whereNotNull('user_id')
            ->get();
    }

    private function getOrder($id)
    {
        $isFromAdministrativeBranch = auth()->user()->isFromAdministrativeBranch();
        $query = Order::query();

        // If user is not from Administrative branch only show order from their branch
        if (!$isFromAdministrativeBranch)
        {
            $query->where('order_branch_id', auth()->user()->branch_id);
        }

        $query->where('id', $id);
        $query->with(['item' => function($query){
            $query->select('id', 'name', 'primary_order_processing_branch');
        }]);
        $query->with(['user' => function ($query){
            $query->select('id', 'name', 'mobile');
        }]);
        $query->with(['processingBranch' => function ($query){
            $query->select('id', 'name');
        }]);
        $query->with(['sourceBranch' => function ($query){
            $query->select('id', 'name');
        }]);
        $query->with(['orderStatus' => function ($query){
            $query->select('id', 'name');
        }]);
        $query->with(['process' => function ($query){
            $query->select('id', 'name');
        }]);

        $order = $query->first();
        
        // If order not found and user is not from administrative branch, check if they have a task for this order
        if (empty($order) && !$isFromAdministrativeBranch) {
            // Try to find the order without branch restriction
            $order = Order::where('id', $id)
                ->with(['item' => function($query){ $query->select('id', 'name', 'primary_order_processing_branch'); }])
                ->with(['user' => function ($query){ $query->select('id', 'name', 'mobile'); }])
                ->with(['processingBranch' => function ($query){ $query->select('id', 'name'); }])
                ->with(['sourceBranch' => function ($query){ $query->select('id', 'name'); }])
                ->with(['orderStatus' => function ($query){ $query->select('id', 'name'); }])
                ->with(['process' => function ($query){ $query->select('id', 'name'); }])
                ->first();
            
            // If order exists, check if user has a task for it
            if (!empty($order)) {
                $userHasTask = TaskModel::where('order_id', $order->id)
                    ->where('user_id', auth()->id())
                    ->exists();
                
                // If user doesn't have a task for this order, deny access
                if (!$userHasTask) {
                    return null;
                }
            }
        }
        
        if (empty($order)) {
            return null;
        }

        $orderDetail = json_decode($order->detail);
        $nextProcess = OrderStatus::where('id', $order->orderStatus->next_process)->first(['name']);

        // Get Invoice
        $invoice = Invoice::where('order_id', $order->id)->first();
        $hasInvoice = !empty($invoice);
        $invoicePaid = $hasInvoice && $invoice->invoice_status_id == InvoiceStatus::STATUS_PAID;
        $canEditOrder = $isFromAdministrativeBranch && (auth()->user()->isAdmin() || auth()->user()->isReception());
        $canGenerateInvoice = $isFromAdministrativeBranch && (!$hasInvoice && ($canEditOrder || auth()->user()->isCashier()));
        $canRegenerateInvoice = $isFromAdministrativeBranch && $hasInvoice && !$invoicePaid;

        $orderProcessCoordinatorRole = $this->getOrderProcessCoordinatorRole($order);
        
        // Check if all tasks for current process are complete
        $allTasksComplete = true;
        if ($order->process_id) {
            $incompleteTasksCount = TaskModel::where('order_id', $order->id)
                ->where('process_id', $order->process_id)
                ->where('task_status_id', '!=', TaskStatus::STATUS_DONE)
                ->count();
            
            $allTasksComplete = $incompleteTasksCount === 0;
        }
        
        // Check if user is at product's primary processing branch
        $atPrimaryBranch = false;
        if ($order->item && $order->item->primary_order_processing_branch) {
            $primaryBranch = Branch::where('name', $order->item->primary_order_processing_branch)->first();
            $atPrimaryBranch = $primaryBranch && auth()->user()->branch_id === $primaryBranch->id;
        }
        

        
        // Only allow forwarding if:
        // 1. Order has human_forwarding flag set (manual forward required)
        // 2. All tasks are complete
        // 3. User is at product's primary processing branch
        // 4. User is coordinator or admin
        $canForwardToNextProcess = $order->human_forwarding &&
            $allTasksComplete &&
            $atPrimaryBranch &&
            $isFromAdministrativeBranch && 
            (auth()->user()->isAdmin() || 
             (!empty($orderProcessCoordinatorRole) && 
              auth()->user()->role_id == $orderProcessCoordinatorRole->id));
        


        $settings = Setting::first();
        $offlinePaymentApprover = null;
        if(!empty($settings->who_approves_offline_payment)){
            $approverRole = Role::where('name', $settings->who_approves_offline_payment)->first();
            $offlinePaymentApprover = $approverRole->id;
        }
        $canApproveOfflinePayment = $isFromAdministrativeBranch && $hasInvoice && !$invoicePaid && $settings->support_offline_payment && (auth()->user()->isAdmin() || auth()->user()->role_id == $offlinePaymentApprover);
        
        // Check if user has tasks for this order (for cross-branch access)
        $userHasTasksForOrder = TaskModel::where('order_id', $order->id)
            ->where('user_id', auth()->id())
            ->exists();
        
        // dd($canApproveOfflinePayment);
        return [
            'order' => $order,
            'customer' => User::find($order->user_id),
            'nextProcess' => $nextProcess->name ?? null,
            'orderDetail' => $orderDetail,
            'items' => Item::getItemsArray(),
            'paymentMethods' => ['Bank Transfer', 'Cash'],
            'paymentStatuses' => ['Paid', 'Unpaid'],
            'branches' => Branch::getBranchesArray(),
            'orderStatuses' => OrderStatus::getOrderStatusesArray(),
            'stkn' => csrf_token(),
            'endpoint' => route('customer.find'),
            'deliveryDate' => $this->getMinAndMaxDeliveryDate(),
            'activities' => $this->getOrderActivityLog($id),
            'hasInvoice' => $hasInvoice,
            'invoice' => $invoice,
            'canForwardToNextProcess' => $canForwardToNextProcess,
            'canApproveOfflinePayment' => $canApproveOfflinePayment,
            'userHasTasksForOrder' => $userHasTasksForOrder,
            'canGenerateInvoice' => $canGenerateInvoice,
            'invoicePaid' => $invoicePaid,
            'canEditOrder' => $canEditOrder,
            'canRegenerateInvoice' => $canRegenerateInvoice,
            'isCancelled' => $order->order_status_id === OrderStatus::CANCELLED,
            'isDelivered' => $order->order_status_id === OrderStatus::DELIVERED,
            'canHoldOrder' => $canEditOrder && !in_array($order->order_status_id, [
                OrderStatus::DELIVERED, OrderStatus::CANCELLED, OrderStatus::DELIVERY_FAILED
            ]),
            'canCancelOrder' => $canEditOrder && in_array($order->order_status_id, [
                OrderStatus::PENDING, OrderStatus::ORDER_CONFIRMED, OrderStatus::AWAITING_PAYMENT, OrderStatus::PAYMENT_CONFIRMED
            ]),
            'canEditReferenceNumber' => $canEditOrder && in_array($order->order_status_id, [
                OrderStatus::PENDING, OrderStatus::ORDER_CONFIRMED
            ]),
            'canEditPrice' => $canGenerateInvoice && !$hasInvoice && in_array($order->order_status_id, [
                OrderStatus::PENDING, OrderStatus::ORDER_CONFIRMED
            ]),
            'canEditWaybill' => $canEditOrder && !in_array($order->order_status_id, [
                OrderStatus::ON_HOLD, OrderStatus::DISPATCHED, OrderStatus::CANCELLED
                , OrderStatus::DELIVERY_FAILED, OrderStatus::DELIVERED, OrderStatus::SHIPPING, OrderStatus::IN_TRANSIT,
            ]),
            'canPrintOrderCard' => $canGenerateInvoice && !empty($order->order_number) && !empty($order->total_cost),
            'banks' => \App\Models\Bank::all(),
            'settings' => $settings,
        ];
    }

    public function updatePaymentStatus(Request $request)
    {
        $paymentMethods= ['Bank Transfer', 'Cash'];
        $paymentStatuses = ['Paid', 'Unpaid'];
        $request->validate([
            'orderId' => 'required|integer|exists:orders,id|exists:invoices,order_id',
            'status' => ['required', Rule::in($paymentStatuses)],
            'paymentMethod' => ['required', Rule::in($paymentMethods)],
            'amountPaid' => 'nullable|required_if:status,Paid|integer|digits_between:1,9',
            'transactionReference' => 'nullable|string|max:64',
            'customerAccountName' => 'nullable|string|max:64',
            'customerAccountNumber' => 'nullable|string|max:64',
            'customerBank' => 'nullable|string|max:64',
            'paymentDate' => 'required|string|max:64',
            'organizationBank' => 'nullable|required_if:paymentMethod,Bank Transfer|string|max:64',
            'organizationAccountName' => 'nullable|required_if:paymentMethod,Bank Transfer|string|max:64',
            'organizationAccountNumber' => 'nullable|required_if:paymentMethod,Bank Transfer|string|max:64',
            'whoReceivedCash' => 'nullable|required_if:paymentMethod,Cash|string|max:300',
        ]);

        $invoice = Invoice::where('order_id', $request->orderId)->first();
        $invoice->invoice_status_id = $request->status == 'Paid' ? InvoiceStatus::STATUS_PAID : InvoiceStatus::STATUS_NEW;
        $invoice->payment_method = $request->paymentMethod;
        $invoice->offline_payment_data = json_encode([
            'status' => $request->status,
            'paymentMethod' => $request->paymentMethod,
            'amountPaid' => $request->amountPaid,
            'transactionReference' => $request->transactionReference,
            'customerAccountName' => $request->customerAccountName,
            'customerAccountNumber' => $request->customerAccountNumber,
            'customerBank' => $request->customerBank,
            'paymentDate' => $request->paymentDate,
            'organizationBank' => $request->organizationBank,
            'organizationAccountName' => $request->organizationAccountName,
            'organizationAccountNumber' => $request->organizationAccountNumber,
            'whoReceivedCash' => $request->whoReceivedCash,
            'currency' => $request->currency ?? 'NGN',
        ]);
        $invoice->save();

        return [
            'status' => 'success',
            'message' => 'Successfully Updated'
        ];

    }

    public function edit($id)
    {
        $data = $this->getOrder($id);
        if (empty($data)) {
            abort(404);
        }
        return Inertia::render('Backend/Order/Edit', $data);
    }

    public function view($id)
    {
        $data = $this->getOrder($id);
        if (empty($data)) {
            abort(404);
        }
        return Inertia::render('Backend/Order/Detail', $data);
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
            if (!empty($order->process->name) && strtolower($itemProcess->name) == strtolower($order->process->name)) {
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
            $order->order_status_id = OrderStatus::CANCELLED;
            $order->save();

            // Update report that order is cancelled
            ReportBuilder::build('cancelled', $order->quantity);

            // Todo: Log the ID of the user that cancelled the order

            // Send notification to all team members with uncompleted tasks that order has been cancelled
            $tasks = $this->getOrderTasksInProcess($order);
            if (!empty($tasks)) {
                foreach ($tasks as $task) {
                    $message = sprintf('Stop Work. Order %s has been cancelled. See details.', $order->order_number ?? $order->name);
                    $task->user->notify(new GenericNotification([
                        'message' => $message,
                        'type' => ['broadcast'],
                        'user' => $task->user,
                        'url' => route('order.view', $order->id)
                    ]));
                }
            }

            // if (!$tasks->isEmpty()) {
            //     $tasks->each(function ($task) use ($order) {
            //         $user = $task->user;
            //         Notification::create([
            //             'user_id'=> $user->id,
            //             'title' => sprintf('%s[%s] is Cancelled', $order->name, $order->order_number),
            //             'message' => sprintf('Hello %s,<br /> this is to inform you that order[%s] has been cancelled.', $order->name, $order->order_number),
            //         ]);
            //     });
            // }
            // Todo: Send notification to customer that order has been cancelled.
            

            return [
                'response' => 'Success',
                'orderStatus' => 'Cancelled',
            ];
        } else {
            return [
                'response' => sprintf('Cannot cancel %s order during %s.', $order->item->name, $order->process->name),
                'orderStatus' => $order->process->name,
            ];
        }
    }

    public function hold(Request $request, $orderId)
    {
        $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);
        
        $order = Order::find($orderId);
        $order->paused = true;
        $order->hold_reason = $request->reason;
        $order->save();

        // Send stop work notice to all team members that have task for this order
        $tasks = $this->getOrderTasksInProcess($order);
        if (!empty($tasks)) {
            foreach ($tasks as $task) {
                $message = sprintf('Stop Work. Order %s has been placed on hold. Click to see reason.', $order->order_number ?? $order->name);
                $task->user->notify(new GenericNotification([
                    'message' => $message,
                    'type' => ['broadcast'],
                    'user' => $task->user,
                    'url' => route('order.view', $order->id)
                ]));
            }
        }

        return [
            'status' => 'success',
            'response' => 'Saved!',
        ];
    }

    public function reactivate(Request $request, $orderId)
    {
        $order = Order::find($orderId);
        $order->paused = false;
        $order->hold_reason = null;
        $order->save();

        // Send order reactivation notice to all team members that have task for this order
        $tasks = $this->getOrderTasksInProcess($order);
        if (!empty($tasks)) {
            foreach ($tasks as $task) {
                $message = sprintf('Resume Work. Order %s has been reactivated. Open Order.', $order->order_number ?? $order->name);
                $task->user->notify(new GenericNotification([
                    'message' => $message,
                    'type' => ['broadcast'],
                    'user' => $task->user,
                    'url' => route('order.view', $order->id)
                ]));
            }
        }

        return [
            'status' => 'success',
            'response' => 'Saved!',
        ];
    }

    public function setWaybillNumber(Request $request, $orderId)
    {
        // Log::info('Order ID: '. $orderId .', Waybill No: '. $request->waybillNo );
        $request->validate([
            'waybillNumber' => 'required|unique:orders,waybill_number|string|max:32'
        ]);

        $order = Order::find($orderId);
        $order->waybill_number = $request->waybillNumber;
        $order->save();

        return [
            'status' => 'success',
            'response' => 'Saved!',
        ];
    }

    public function editPrice(Request $request, $orderId)
    {
        $request->validate([
            'price' => 'required|integer|digits_between:1,9'
        ]);
        // Log::info('Order ID: '. $orderId .', Price: '. $request->price );
        $order = Order::find($orderId);
        $order->total_cost = $request->price;
        $order->save();

        return [
            'status' => 'success',
            'response' => 'Saved!',
        ];
    }

    public function editReferenceNumber(Request $request, $orderId)
    {
        $request->validate([
            'orderNumber' => 'required|unique:orders,order_number|string|max:16'
        ]);

        $order = Order::find($orderId);
        $order->order_number = $request->orderNumber;
        $order->save();

        return [
            'status' => 'success',
            'response' => 'Saved!',
        ];
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
