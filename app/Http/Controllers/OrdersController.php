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
use App\Notifications\OrderHoldNotification;
use App\Notifications\OrderReactivatedNotification;
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
use Illuminate\Support\Facades\DB;
use App\Http\Traits\HandlesRewardPointRedemption;
use App\Messaging\WhatsAppClient;
use App\Events\InvoicePaymentVerified;

class OrdersController extends Controller
{
    use HandlesRewardPointRedemption;

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
            'note' => session('note'),
            'products' => \App\Models\Item::select('id', 'name')->get(),
            'statuses' => \App\Models\OrderStatus::select('id', 'name')->get()
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
        // If user is not from Administrative Branch, apply branch-based filtering
        if (!auth()->user()->isFromAdministrativeBranch())
        {
            $query->where(function($q) {
                // Part 1: Orders from user's branch (origin branch - where order was created)
                $q->where('order_branch_id', auth()->user()->branch_id);
                
                // Part 2: Check if user's role is authorized for expanded visibility
                $settings = Setting::first();
                $authorizedRoles = !empty($settings->order_view_roles) 
                    ? json_decode($settings->order_view_roles) 
                    : [];
                
                if (in_array(auth()->user()->role->name, $authorizedRoles)) {
                    // Add orders where user's branch is the processing branch
                    $q->orWhere('branch_id', auth()->user()->branch_id);
                }
            });
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
        // Handle Vue ref object if passed directly
        $filters = isset($search['_value']) ? $search['_value'] : $search;

        if (is_array($filters)) {
            if (!empty($filters['order_number'])) {
                $query->where('order_number', 'like', '%' . $filters['order_number'] . '%');
            }
            if (!empty($filters['customer_name'])) {
                $query->whereHas('user', function ($q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['customer_name'] . '%');
                });
            }
            if (!empty($filters['product'])) {
                $query->whereHas('item', function ($q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['product'] . '%');
                });
            }
            if (!empty($filters['status'])) {
                $query->whereHas('orderStatus', function ($q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['status'] . '%');
                });
            }
        } elseif (is_string($filters) && !empty($filters)) {
             // Fallback for single search string if needed in future
             $query->where(function($q) use ($filters) {
                $q->where('order_number', 'like', '%' . $filters . '%')
                  ->orWhereHas('user', function($sq) use ($filters) {
                      $sq->where('name', 'like', '%' . $filters . '%');
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
            'branches' => Branch::getBranchesWithAddress(),
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
                $existingUser->is_temporary_password = true;
                $existingUser->state_id = auth()->user()->state_id;
                $existingUser->branch_id = auth()->user()->branch_id;
                $existingUser->account_status = User::STATUS_ACTIVE;
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
                    'is_temporary_password' => true,
                    'branch_id' => auth()->user()->branch_id,
                    'account_status' => User::STATUS_ACTIVE,
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
        // $customer = auth()->user()->isCustomer() ? auth()->user() : $customerData;
        // $waConfig = [
        //     'order' => $order,
        //     'customer' => $customer,
        // ];
        // $waClient = new WhatsAppClient($waConfig);
        // $waClient->sendCustomerMessage('order_management_6');

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

        // If user is not from Administrative branch, apply branch-based filtering
        if (!$isFromAdministrativeBranch)
        {
            $query->where(function($q) {
                // Part 1: Orders from user's origin branch
                $q->where('order_branch_id', auth()->user()->branch_id);
                
                // Part 2: Check if user's role is authorized for expanded visibility
                $settings = Setting::first();
                $authorizedRoles = !empty($settings->order_view_roles) 
                    ? json_decode($settings->order_view_roles) 
                    : [];
                
                if (in_array(auth()->user()->role->name, $authorizedRoles)) {
                    // Add orders where user's branch is the processing branch
                    $q->orWhere('branch_id', auth()->user()->branch_id);
                }
            });
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

        // Get settings for order cancellation roles
        $settings = Setting::first();
        $authorizedCancelRoles = !empty($settings->order_cancel_roles) 
            ? json_decode($settings->order_cancel_roles) 
            : ['Administrator', 'Reception'];
        $userCanCancelByRole = in_array(auth()->user()->role->name, $authorizedCancelRoles);

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
            if ($approverRole) {
                $offlinePaymentApprover = $approverRole->id;
            }
        }
        $canApproveOfflinePayment = $isFromAdministrativeBranch && $hasInvoice && !$invoicePaid && $settings->support_offline_payment && (auth()->user()->isAdmin() || auth()->user()->role_id == $offlinePaymentApprover);
        
        // Check if user has tasks for this order (for cross-branch access)
        $userHasTasksForOrder = TaskModel::where('order_id', $order->id)
            ->where('user_id', auth()->id())
            ->exists();
        
        // Determine if user is viewing from processing branch (not origin branch)
        $isViewingFromProcessingBranch = !$isFromAdministrativeBranch && 
                                         $order->branch_id == auth()->user()->branch_id && 
                                         $order->order_branch_id != auth()->user()->branch_id;
        
        // Get processing branch privacy settings
        $showPriceToProcessingBranch = $settings->processing_branch_show_price == 1;
        $showInvoiceToProcessingBranch = $settings->processing_branch_show_invoice == 1;
        
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
            'canCancelOrder' => $userCanCancelByRole && in_array($order->order_status_id, [
                OrderStatus::PENDING, OrderStatus::ORDER_CONFIRMED, OrderStatus::AWAITING_PAYMENT, OrderStatus::PAYMENT_CONFIRMED
            ]),
            'canReactivateOrder' => $userCanCancelByRole && $order->paused,
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
            'isViewingFromProcessingBranch' => $isViewingFromProcessingBranch,
            'showPriceToProcessingBranch' => $showPriceToProcessingBranch,
            'showInvoiceToProcessingBranch' => $showInvoiceToProcessingBranch,
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
            'pointsRedeemed' => $invoice->points_redeemed ?? 0,
            'pointsDiscount' => $invoice->points_discount_amount ?? 0,
        ]);
        $invoice->save();

        // Award loyalty reward points when offline payment is approved
        if ($request->status == 'Paid') {
            $order = Order::find($request->orderId);
            $settings = Setting::first();
            
            if ($order && $settings && !empty($settings->loyalty_reward_multiplier)) {
                $this->saveLoyaltyRewardPoints($order, $invoice, $settings->loyalty_reward_multiplier);
            }
        }

        // Dispatch event if paid
        if ($request->status == 'Paid') {
            event(new InvoicePaymentVerified($invoice->id));
        }

        return [
            'status' => 'success',
            'message' => 'Successfully Updated'
        ];

    }

    private function saveLoyaltyRewardPoints($order, $invoice, $rewardMultiplier)
    {
        // Secure calculation without eval()
        if (!empty($rewardMultiplier) && is_numeric($rewardMultiplier) && $rewardMultiplier > 0) {
            // Get the actual cash amount paid from payment data
            $paymentData = json_decode($invoice->offline_payment_data ?? $invoice->customer_payment_proof, true);
            $cashPaid = $paymentData['amountPaid'] ?? $order->total_cost;
            
            // Calculate points based ONLY on the cash amount paid (not on points used)
            // This encourages customers to use their points without penalty
            $rewardPoints = round($cashPaid * $rewardMultiplier, 2);
            
            // Get expiration date from settings
            $settings = Setting::first();
            $expiryMonths = $settings->points_expiry_months ?? 12;
            $expiresAt = $expiryMonths > 0 ? now()->addMonths($expiryMonths) : null;
            
            \App\Models\RewardPoint::create([
                'user_id' => $order->user_id,
                'invoice_id' => $invoice->id,
                'points' => $rewardPoints,
                'transaction_type' => \App\Models\RewardPoint::TYPE_EARNED,
                'description' => "Earned from Invoice #{$invoice->id} (Offline Payment)",
                'expires_at' => $expiresAt,
            ]);
        }
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
        // Validate cancellation reason is required
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $order = Order::find($orderId);
        
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Authorization: Check if user's role is allowed to cancel orders
        $settings = Setting::first();
        $authorizedRoles = !empty($settings->order_cancel_roles) 
            ? json_decode($settings->order_cancel_roles) 
            : ['Administrator', 'Reception']; // Fallback to defaults
        
        $isAuthorized = in_array(auth()->user()->role->name, $authorizedRoles);
        
        if (!$isAuthorized) {
            return response()->json([
                'error' => 'Unauthorized. You do not have permission to cancel orders.',
                'authorizedRoles' => $authorizedRoles
            ], 403);
        }

        // Check if order can be cancelled based on process configuration
        if (!$this->canCancelOrder($order)) {
            return [
                'response' => sprintf('Cannot cancel %s order during %s.', $order->item->name, $order->process->name),
                'orderStatus' => $order->process->name,
            ];
        }

        // Use database transaction for safety
        DB::beginTransaction();
        try {
            // Set Order status to Cancelled
            $order->order_status_id = OrderStatus::CANCELLED;
            
            // Track audit fields
            $order->cancelled_by = auth()->id();
            $order->cancelled_at = now();
            $order->cancellation_reason = $request->reason;
            
            $order->save();

            // Update invoice status to CANCELLED if invoice exists
            // Store original status first for refund notification check
            $invoiceWasPaid = false;
            $invoice = Invoice::where('order_id', $order->id)->first();
            if ($invoice) {
                $invoiceWasPaid = $invoice->invoice_status_id == InvoiceStatus::STATUS_PAID;
                $invoice->invoice_status_id = InvoiceStatus::STATUS_CANCELLED;
                $invoice->save();
            }

            // Update report that order is cancelled
            ReportBuilder::build('cancelled', $order->quantity);

            // Send notification to all team members with uncompleted tasks that order has been cancelled
            $tasks = $this->getOrderTasksInProcess($order);
            if (!empty($tasks)) {
                foreach ($tasks as $task) {
                    $message = sprintf('Stop Work. Order %s has been cancelled. Reason: %s', 
                        $order->order_number ?? $order->name,
                        $request->reason
                    );
                    $task->user->notify(new GenericNotification([
                        'message' => $message,
                        'type' => ['broadcast'],
                        'user' => $task->user,
                        'url' => route('order.view', $order->id)
                    ]));
                    
                    // Mark task as cancelled
                    $task->task_status_id = TaskStatus::STATUS_CANCELLED;
                    $task->save();
                }
            }

            // Send WhatsApp notification to customer if template is configured
            if (!empty($settings->order_cancellation_whatsapp_template)) {
                try {
                    $waClient = new WhatsAppClient([
                        'customer' => $order->user,
                        'order' => $order,
                        'team' => collect(),
                        'nextProcess' => null
                    ]);
                    
                    $waClient->sendMessage(
                        $settings->order_cancellation_whatsapp_template,
                        [$order->user->mobile]
                    );
                } catch (\Exception $e) {
                    Log::error('Failed to send WhatsApp cancellation notification: ' . $e->getMessage());
                    // Don't fail the cancellation if WhatsApp fails
                }
            }

            // Notify refund handlers if invoice was paid before cancellation
            if ($invoiceWasPaid && $invoice) {
                // Get users with refund handler role
                if (!empty($settings->who_handles_refunds)) {
                    $refundHandlerRole = Role::where('name', $settings->who_handles_refunds)->first();
                    
                    if ($refundHandlerRole) {
                        $refundHandlers = User::where('role_id', $refundHandlerRole->id)->get();
                        
                        foreach ($refundHandlers as $handler) {
                            $invoiceUrl = route('invoice', $invoice->id);
                            
                            $message = sprintf(
                                'Order %s has been cancelled and requires refund processing.<br><br><strong>Customer:</strong> %s<br><strong>Amount:</strong> ₦%s<br><strong>Reason:</strong> %s<br><br><a href="%s" class="btn bg-primary text-white px-4 py-2 rounded" style="display: inline-block; text-decoration: none; background-color: #1976d2; color: white; padding: 8px 16px; border-radius: 4px; font-weight: 500;">Process Refund</a>',
                                $order->order_number ?? $order->name,
                                $order->user->name,
                                number_format($order->total_cost, 2),
                                $request->reason,
                                $invoiceUrl
                            );
                            
                            // Create persistent notification in custom_notifications table
                            \App\Models\Notification::create([
                                'user_id' => $handler->id,
                                'title' => 'Refund Required',
                                'message' => $message,
                                'url' => $invoiceUrl,
                            ]);
                            
                            // Also send browser notification
                            $handler->notify(new GenericNotification([
                                'message' => strip_tags($message), // Remove HTML for browser notification
                                'type' => ['broadcast'],
                                'user' => $handler,
                                'url' => $invoiceUrl,
                            ]));
                        }
                    }
                }
            }
            
            DB::commit();

            return [
                'response' => 'Success',
                'orderStatus' => 'Cancelled',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order cancellation failed: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Cancellation failed. Please try again.'
            ], 500);
        }
    }

    public function hold(Request $request, $orderId)
    {
        $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);
        
        // Authorization: Check if user's role is allowed to hold orders
        $settings = Setting::first();
        $authorizedRoles = !empty($settings->order_cancel_roles) 
            ? json_decode($settings->order_cancel_roles) 
            : ['Administrator', 'Reception']; // Same roles that can cancel
        
        $isAuthorized = in_array(auth()->user()->role->name, $authorizedRoles);
        
        if (!$isAuthorized) {
            return response()->json([
                'error' => 'Unauthorized. You do not have permission to hold orders.',
                'authorizedRoles' => $authorizedRoles
            ], 403);
        }
        
        $order = Order::find($orderId);
        $order->paused = true;
        $order->hold_reason = $request->reason;
        $order->save();

        // Get all tasks for this order and move them to HELD status
        $tasks = $this->getOrderTasksInProcess($order);
        if ($tasks->isNotEmpty()) {
            foreach ($tasks as $task) {
                // Save current status before holding
                $task->previous_status_id = $task->task_status_id;
                $task->task_status_id = TaskStatus::STATUS_HELD;
                $task->save();
                
                // Send stop work notice
                $task->user->notify(new OrderHoldNotification($order, $request->reason, $task->user));
            }
        }

        return [
            'status' => 'success',
            'response' => 'Saved!',
        ];
    }

    public function reactivate(Request $request, $orderId)
    {
        // Authorization: Check if user's role is allowed to reactivate orders
        $settings = Setting::first();
        $authorizedRoles = !empty($settings->order_cancel_roles) 
            ? json_decode($settings->order_cancel_roles) 
            : ['Administrator', 'Reception']; // Same roles that can cancel
        
        $isAuthorized = in_array(auth()->user()->role->name, $authorizedRoles);
        
        if (!$isAuthorized) {
            return response()->json([
                'error' => 'Unauthorized. You do not have permission to reactivate orders.',
                'authorizedRoles' => $authorizedRoles
            ], 403);
        }
        
        $order = Order::find($orderId);
        $order->paused = false;
        $order->hold_reason = null;
        $order->save();

        // Get all held tasks for this order and restore to previous status
        $tasks = TaskModel::where('order_id', $order->id)
            ->where('task_status_id', TaskStatus::STATUS_HELD)
            ->whereNotNull('user_id')
            ->get();
            
        if ($tasks->isNotEmpty()) {
            foreach ($tasks as $task) {
                // Restore to previous status
                if ($task->previous_status_id) {
                    $task->task_status_id = $task->previous_status_id;
                    $task->previous_status_id = null;
                } else {
                    // If no previous status (legacy held tasks), default to Todo
                    $task->task_status_id = TaskStatus::STATUS_TODO;
                }
                $task->save();
                
                // Send reactivation notice
                $task->user->notify(new OrderReactivatedNotification($order, $task->user));
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
            'waybillNumber' => 'required|string|max:32'
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
