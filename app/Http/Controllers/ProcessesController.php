<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Process;
use App\Models\Role;
use App\Models\SmsTemplate;
use App\Models\TaskStatus;
use App\Models\User;
use App\Report\ReportBuilder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class ProcessesController extends Controller
{
    protected $rules = [
        'name' => 'string|required|unique:processes,name|min:2|max:64',
        'description' => 'nullable|string|min:24|max:1000',
    ];

    public function index(){
        return Inertia::render('Backend/Process/List', [
            'endpoint' => route('processes.records'),
            'note' => session('note')
        ]);
    }

    public function records(Request $request)
    {
        $processes = [];
        $processesCount = 0;

        $page = $request->page;
        $processesPerPage = $request->processesPerPage ?? 25;
        $sortBys = $request->sortBy;
        $search = $request->search;

        // $query = Process::query();
        
        // if (!empty($search)) {
        //     $searchTerm = $search['_value'];
        //     if (!empty($searchTerm)) {
        //        $query->where('name', 'LIKE', sprintf('%%%s%%', $searchTerm));
        //        $query->where('description', 'LIKE', sprintf('%%%s%%', $searchTerm));
        //     }
        // }

        // if (!empty($sortBys)) {
        //     foreach ($sortBys as $sortBy) {
        //         $query->orderBy($sortBy['key'], $sortBy['order']);
        //     }
        // }else{
        //     $query->orderBy('id', 'desc');
        // }

        // $processesCount = $query->count();
        // $processes = $query->take($processesPerPage)
        //     ->skip($processesPerPage * ($page - 1))
        //     ->get();

        $query = Process::withCount([
            'orders as todo_count' => function ($query) {
                $query->whereHas('tasks', function ($query) {
                    $query->where('task_status_id', TaskStatus::STATUS_TODO)
                        ->whereColumn('tasks.process_id', 'processes.id');
                });
            },
            'orders as doing_count' => function ($query) {
                $query->whereHas('tasks', function ($query) {
                    $query->where('task_status_id', TaskStatus::STATUS_DOING)
                        ->whereColumn('tasks.process_id', 'processes.id');
                });
            },
            'orders as done_count' => function ($query) {
                $query->whereHas('tasks', function ($query) {
                    $query->where('task_status_id', TaskStatus::STATUS_DONE)
                        ->whereColumn('tasks.process_id', 'processes.id');
                });
            },
            'orders as unclaimed_count' => function ($query) {
                $query->whereHas('tasks', function ($query) {
                    $query->whereNull('task_status_id')
                        ->whereColumn('tasks.process_id', 'processes.id');
                });
            }
        ]);
        
        if (!empty($search)) {
            $searchTerm = $search['_value'];
            if (!empty($searchTerm)) {
                $query->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'LIKE', sprintf('%%%s%%', $searchTerm))
                          ->orWhere('description', 'LIKE', sprintf('%%%s%%', $searchTerm));
                });
            }
        }
        
        if (!empty($sortBys)) {
            foreach ($sortBys as $sortBy) {
                $query->orderBy($sortBy['key'], $sortBy['order']);
            }
        } else {
            $query->orderBy('id', 'desc');
        }
        
        $processesCount = $query->count();
        $processes = $query->take($processesPerPage)
            ->skip($processesPerPage * ($page - 1))
            ->get();
        

        return [
            'records' => $processes,
            'totalRecords' => $processesCount,
        ];
    }

    public function add(){
        return Inertia::render('Backend/Process/Add', [
            'processes' => OrderStatus::getOrderStatusesArray(),
            'roles' => Role::getRolesArray(),
            'smsTemplates' => SmsTemplate::getSmsTemplatesArray(),
            'emailTemplates' => EmailTemplate::getEmailTemplatesArray(),
            'reportStates' => ReportBuilder::getReportStates(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->rules);

        Process::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('processes')->with('note', $request->name . ' Process Registered');
    }

    private function getProcess($id)
    {
        // $process = Process::find($id);

        $query = Process::withCount([
            'orders as todo_count' => function ($query) {
                $query->whereHas('tasks', function ($query) {
                    $query->where('task_status_id', TaskStatus::STATUS_TODO)
                        ->whereColumn('tasks.process_id', 'processes.id');
                });
            },
            'orders as doing_count' => function ($query) {
                $query->whereHas('tasks', function ($query) {
                    $query->where('task_status_id', TaskStatus::STATUS_DOING)
                        ->whereColumn('tasks.process_id', 'processes.id');
                });
            },
            'orders as done_count' => function ($query) {
                $query->whereHas('tasks', function ($query) {
                    $query->where('task_status_id', TaskStatus::STATUS_DONE)
                        ->whereColumn('tasks.process_id', 'processes.id');
                });
            },
            'orders as unclaimed_count' => function ($query) {
                $query->whereHas('tasks', function ($query) {
                    $query->whereNull('task_status_id')
                        ->whereColumn('tasks.process_id', 'processes.id');
                });
            }
        ]);
        
        $process = $query->where('id', $id)->first();

        // Get the Orders at this process [This should link to the orders page and filter for a specific process]
        // Get the t

        return [
            'process' => $process,
            'ordersEndpoint' => route('process.orders', $id),
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
        $process = Process::find($id);
        if (empty($process)) {
            return redirect()->route('processes')->with('note', 'Select a process to edit.');
        }

        // Validate the submitted data
        if ($process->name == $request->name) {
            $this->rules['name'] = 'string|required|min:2|max:64';
        }
        
        $request->validate($this->rules);

        // Save changes
        $process->name = $request->name;
        $process->description = $request->description;
        $process->save();

        return redirect()->route('process.view', $process->id)->with('note', 'Updated.');
    }

    public function delete(Request $request)
    {
        if (!empty($request->ids)) {
            Process::whereIn('id', $request->ids)->delete();

            return redirect()->route('processes')->with('note', 'Selected processes have been deleted');
        }
    }

    public function orders(Request $request, $processId)
    {
        // $processesCount = 0;

        $page = $request->page;
        $ordersPerPage = $request->ordersPerPage ?? 25;
        $sortBys = $request->sortBy;
        $search = $request->search;

       // The State of the Tasks for the Order, item Name, Order Number, Current State
       $query = Order::with([
            'tasks' => function ($query) use ($processId) {
                $query->where('process_id', $processId)  // Filter tasks by the current process_id
                    ->select('id', 'order_id', 'task_status_id', 'process_id')
                    ->with(['taskStatus' => function ($query) {
                        $query->select('id', 'name');  // Assuming 'name' is the status name
                    }]);
            },
            'item' => function ($query) {
                $query->select('id', 'name');
            }
        ])
        ->where('process_id', $processId)  // Filter orders by the current process_id
        ->select('id', 'name', 'order_number', 'item_id', 'process_id', 'updated_at');
        
        // Search functionality
        if (!empty($search)) {
            $searchTerm = $search['_value'];
            if (!empty($searchTerm)) {
                $query->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'LIKE', sprintf('%%%s%%', $searchTerm))
                        ->orWhere('order_number', 'LIKE', sprintf('%%%s%%', $searchTerm))
                        ->orWhereHas('item', function ($query) use ($searchTerm) {
                            $query->where('name', 'LIKE', sprintf('%%%s%%', $searchTerm));
                        })
                        ->orWhereHas('tasks.taskStatus', function ($query) use ($searchTerm) {
                            $query->where('name', 'LIKE', sprintf('%%%s%%', $searchTerm));
                        });
                });
            }
        }
        
        // Sort functionality
        if (!empty($sortBys)) {
            foreach ($sortBys as $sortBy) {
                if ($sortBy['key'] === 'item_name') {
                    // Sort by item name
                    $query->join('items', 'orders.item_id', '=', 'items.id')
                        ->orderBy('items.name', $sortBy['order']);
                } elseif ($sortBy['key'] === 'task_status') {
                    // Sort by task status name
                    $query->join('tasks', 'orders.id', '=', 'tasks.order_id')
                        ->join('task_statuses', 'tasks.task_status_id', '=', 'task_statuses.id')
                        ->where('tasks.process_id', $processId)  // Ensure tasks are filtered by process_id
                        ->orderBy('task_statuses.name', $sortBy['order']);
                } else {
                    // Sort by order fields (name, order_number, etc.)
                    $query->orderBy($sortBy['key'], $sortBy['order']);
                }
            }
        } else {
            // Default sorting
            $query->orderBy('id', 'desc');
        }

        // $orders = $query->get();

        $ordersCount = $query->count();
        $orders = $query->take($ordersPerPage)
            ->skip($ordersPerPage * ($page - 1))
            ->get();
        

        // Prepare a comma-separated list of task statuses for each order
        $orders->each(function ($order) {
            $order->task_statuses = $order->tasks->map(function ($task) {
                return $task->taskStatus ? $task->taskStatus->name : 'Unclaimed';
            })->unique()->implode(', ');
            
            $order->task_count = $order->tasks->count();
        });

        // Now you can access the task statuses for each order as a comma-separated list
        // foreach ($orders as $order) {
        //     echo $order->name . ': ' . $order->task_statuses . PHP_EOL;
        // }

        return [
            'records' => $orders,
            'totalRecords' => $ordersCount,
        ];


    }

}
