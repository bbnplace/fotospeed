<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Role;
use App\Models\SmsTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProcessesController extends Controller
{
    protected $rules = [
        'name' => 'string|required|unique:items,name|min:2|max:64',
        'role' => 'string|required|exists:roles,name|min:2|max:64',
        'description' => 'nullable|string|min:24|max:1000',
        'nextProcess' => 'nullable|string|exists:order_statuses,name|min:2|max:64',
        'smsTeam' => 'nullable|boolean',
        'smsTemplate' => 'nullable|string|exists:sms_templates,name|min:2|max:64',
        'emailTeam' => 'nullable|boolean',
        'emailTemplate' => 'nullable|string|exists:email_templates,name|min:2|max:64',
        'smsCustomer' => 'nullable|boolean',
        'customerSmsTemplate' => 'nullable|string|exists:sms_templates,name|min:2|max:64',
        'emailCustomer' => 'nullable|boolean',
        'customerEmailTemplate' => 'nullable|string|exists:email_templates,name|min:2|max:64',
    ];

    public function index(){
        return Inertia::render('Backend/Process/List', [
            'endpoint' => route('processes.records'),
            'note' => session('note')
        ]);
    }

    public function records(Request $request)
    {
        $items = [];
        $itemsCount = 0;

        $page = $request->page;
        $itemsPerPage = $request->itemsPerPage;
        $sortBys = $request->sortBy;
        $search = $request->search;

        $query = OrderStatus::query();
        $query->with(['role' => function ($query) {
            $query->select('id', 'name');
        }]);
        // $query->with(['smsTemplate' => function ($query) {
        //     $query->select('id', 'name');
        // }]);
        // $query->with(['emailTemplate' => function ($query) {
        //     $query->select('id', 'name');
        // }]);
        $query->with(['nextProcess' => function ($query) {
            $query->select('id', 'name');
        }]);

        if (!empty($search)) {
            $searchTerm = $search['_value'];
            if (!empty($searchTerm)) {
            //    $query->where('name', 'LIKE', sprintf('%%%s%%', $searchTerm));
            //    $query->where('height', 'LIKE', sprintf('%%%s%%', $searchTerm));
            //    $query->where('width', 'LIKE', sprintf('%%%s%%', $searchTerm));
            //    $query->where('weight', 'LIKE', sprintf('%%%s%%', $searchTerm));
            //    $query->where('print_price', 'LIKE', sprintf('%%%s%%', $searchTerm));
            //    $query->where('cover_print_price', 'LIKE', sprintf('%%%s%%', $searchTerm));
            //    $query->where('sheet_price', 'LIKE', sprintf('%%%s%%', $searchTerm));
            }
        }

        if (!empty($sortBys)) {
            foreach ($sortBys as $sortBy) {
                $query->orderBy($sortBy['key'], $sortBy['order']);
            }
        }else{
            $query->orderBy('id', 'desc');
        }

        $itemsCount = $query->count();
        $items = $query->take($itemsPerPage)
            ->skip($itemsPerPage * ($page - 1))
            ->get();

        return [
            'records' => $items,
            'totalRecords' => $itemsCount,
        ];
    }

    public function add(){
        return Inertia::render('Backend/Process/Add', [
            'processes' => OrderStatus::getOrderStatusesArray(),
            'roles' => Role::getRolesArray(),
            'smsTemplates' => SmsTemplate::getSmsTemplatesArray(),
            'emailTemplates' => EmailTemplate::getEmailTemplatesArray(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);

        $nextProcess = OrderStatus::where('name', $request->nextProcess)->first();
        $role = Role::where('name', $request->role)->first();
        $teamSmsTemplate = SmsTemplate::where('name', $request->smsTemplate)->first();
        $teamEmailTemplate = EmailTemplate::where('name', $request->emailTemplate)->first();
        $customerSmsTemplate = SmsTemplate::where('name', $request->customerSmsTemplate)->first();
        $customerEmailTemplate = EmailTemplate::where('name', $request->customerEmailTemplate)->first();

        OrderStatus::create([
            'role_id' => $role->id,
            'name' => $request->name,
            'description' => $request->description,
            'sms_template_id' => $teamSmsTemplate->id ?? null,
            'email_template_id' => $teamEmailTemplate->id ?? null,
            'next_process' => $nextProcess->id ?? null,
            'sms_team' => $request->smsTeam,
            'email_team' => $request->emailTeam,
            'sms_customer' => $request->smsCustomer,
            'email_customer' => $request->emailCustomer,
            'customer_sms_template_id' => $customerSmsTemplate->id ?? null,
            'customer_email_template_id' => $customerEmailTemplate->id ?? null,
        ]);

        return redirect()->route('processes')->with('note', $request->name . ' Process Registered');
    }

    private function getProcess($id)
    {
        $query = OrderStatus::query();
        $query->where('id', $id);
        $query->with(['role' => function($query){
            $query->select('id', 'name');
        }]);
        $query->with(['smsTemplate' => function($query){
            $query->select('id', 'name');
        }]);
        $query->with(['emailTemplate' => function($query){
            $query->select('id', 'name');
        }]);
        $query->with(['customerSmsTemplate' => function($query){
            $query->select('id', 'name');
        }]);
        $query->with(['customerEmailTemplate' => function($query){
            $query->select('id', 'name');
        }]);
        $query->with(['nextProcess' => function($query){
            $query->select('id', 'name');
        }]);

        $process = $query->first();

        return [
            'process' => $process,
            'processes' => OrderStatus::getOrderStatusesArray(),
            'roles' => Role::getRolesArray(),
            'smsTemplates' => SmsTemplate::getSmsTemplatesArray(),
            'emailTemplates' => EmailTemplate::getEmailTemplatesArray(),
        ];
    }

    public function edit($id)
    {
        return Inertia::render('Backend/Process/Edit', $this->getProcess($id));
    }

    public function view($id)
    {
        return Inertia::render('Backend/Process/Detail', $this->getProcess($id));
    }

    public function update(Request $request, $id)
    {
        $process = OrderStatus::where('id', $id)->first();
        if (empty($process)) {
            return redirect()->route('processes')->with('note', 'Select a process to edit.');
        }

        // Validate the submitted data
        if ($process->name == $request->name) {
            $this->rules['name'] = 'string|required|min:2|max:64';
        }
        $request->validate($this->rules);

        $nextProcess = OrderStatus::where('name', $request->nextProcess)->first();
        $role = Role::where('name', $request->role)->first();
        $smsTemplate = SmsTemplate::where('name', $request->smsTemplate)->first();
        $emailTemplate = EmailTemplate::where('name', $request->emailTemplate)->first();
        $customerSmsTemplate = SmsTemplate::where('name', $request->customerSmsTemplate)->first();
        $customerEmailTemplate = EmailTemplate::where('name', $request->customerEmailTemplate)->first();


        // Save changes
        $process->name = $request->name;
        $process->description = $request->description;
        $process->role_id = $role->id;
        $process->sms_template_id = $smsTemplate->id ?? null;
        $process->email_template_id = $emailTemplate->id ?? null;
        $process->next_process = $nextProcess->id ?? null;
        $process->sms_team = $request->smsTeam;
        $process->email_team = $request->emailTeam;
        $process->sms_customer = $request->smsCustomer;
        $process->email_customer = $request->emailCustomer;
        $process->customer_sms_template_id = $customerSmsTemplate->id ?? null;
        $process->customer_email_template_id = $customerEmailTemplate->id ?? null;
        $process->save();

        return redirect()->route('process.view', $process->id)->with('note', 'Updated.');
    }

    public function delete(Request $request)
    {
        if (!empty($request->ids)) {
            OrderStatus::whereIn('id', $request->ids)->delete();

            return redirect()->route('processes')->with('note', 'Selected processes have been deleted');
        }
    }

    

    public function cancel(Request $request)
    {
        $order = Order::where('id', $request->orderId)->first();
        $orderStatus = OrderStatus::where('name', 'Cancelled')->first();

        $order->order_status_id = $orderStatus->id;
        $order->save();

        return [
            'status' => 'Cancelled and Closed',
        ];
    }
}
