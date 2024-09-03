<?php

namespace App\Http\Controllers;

use App\Helper\URLGenerator;
use App\Messaging\TemplateManager;
use App\Models\Process;
use App\Models\User;
use App\Report\ReportBuilder;
use App\Tasks\Task as TaskAssigner;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Role;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Tasks\TaskAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TasksController extends Controller
{
    public function loadUnassignedTeamTasks()
    {
        $query = Task::query();
        $query->where('role_id', auth()->user()->role_id); // Ensures user is from the target team
        $query->where('branch_id', auth()->user()->branch_id); // Ensure user is from the production branch
        $query->whereNull('user_id'); // Task has not been claimed
        $query->orderBy('id', 'desc');
        $unclaimedTasks = $query->get([
            'id', 'name', 'description', 'created_at'                
        ]);

        return [
            'unclaimedTasks' => $unclaimedTasks,
        ];
    }

    public function loadUnassignedOrderTasks(int $orderId)
    {
        $query = Task::query();
        $query->where('order_id', $orderId); // Ensures user is from the target team
        $query->whereNull('user_id'); // Task has not been claimed
        $query->orderBy('id', 'desc');
        $unclaimedTasks = $query->get([
            'id', 'name', 'description', 'created_at'                
        ]);

        return [
            'unclaimedTasks' => $unclaimedTasks,
        ];
    }
    
    public function loadTasks()
    {
        $tasks = [
            'Todo' => [],
            'Doing' => [],
            'Done'=> [],
        ];

        $query = Task::query();
        $query->where('user_id', auth()->user()->id);
        $query->with('order', function ($query){
            $query->select('id','name','order_number','paused');
        });
        $query->orderBy('id','desc');
        // $records = Task::where('user_id', auth()->user()->id)->get();
        $records = $query->get();

        if (!empty($records)) {
            foreach ($records as $record) {
                // dd($record->taskStatus);
                array_push($tasks[$record->taskStatus->name], $record);
            }
        }

        return $tasks;
    }

    public function loadOrderTasks(int $orderId)
    {
        $tasks = [
            'Todo' => [],
            'Doing' => [],
            'Done'=> [],
        ];

        $query =  Task::query();
        $query->where('order_id', $orderId);
        $query->with('taskStatus', function ($query){
            $query->select('id', 'name');
        });
        $query->with('user', function ($query){
            $query->select('id', 'name');
        });
        $query->with('order', function ($query){
            $query->select('id','order_number','name','paused');
        });

        // $records = Task::where('order_id', $orderId)->get();
        $records = $query->orderBy('id', 'desc')->get();

        if (!empty($records)) {
            foreach ($records as $record) {
                if($record->taskStatus !== null)
                {
                    array_push($tasks[$record->taskStatus->name], $record);
                }
            }
        }

        return $tasks;
    }

    public function pickTask(Request $request)
    {
        $task = $request->task;
        $taskId = $task['id'];
        $task = Task::find($taskId);
        if (!empty($task)) {
            $task->task_status_id = TaskStatus::STATUS_TODO;
            $task->user_id = auth()->user()->id;
            $task->save();

            // TODO: Notify Controller that the task has been accepted. Show the user that accepted the task

            return [
                'status' => 'success'
            ];
        }
    }

    private function getTaskAudits($task, $taskName): array | bool
    {
        $processData = json_decode($task->order->item->process_data, true);
        $currentProcess = $task->process->name;
        if (!empty($processData)) {
            $currentProcessTasks = $processData['tasks'][$currentProcess];
            $tm = new TemplateManager([
                'order' => $task->order
            ]);

            $targetTask = [];
            foreach ($currentProcessTasks as $currentProcessTask) {
                if ($taskName == $tm->prepareText($currentProcessTask['name'])) {
                    $targetTask = $currentProcessTask;
                    break;
                }
            }

            if (empty($targetTask)) {
                return false; // No Audit
            }
            
            if (isset($targetTask['audit']) && $targetTask['audit']) {
                return $targetTask['checks'] ?? [];
            } else {
                return false; // No Audit
            }
        } else {
            return false;
        }
    }

    public function updateTasks(Request $request)
    {
        $task = $request->task;
        if (!isset($task['id'])) {
            return [
                'status' => 'Failed'
            ];
        }
        $taskId = $task['id'];
        $taskName = $task['name'];
        $newStatus = TaskStatus::getTaskStatusId($request->toStatus);
        
        $task = Task::find($taskId);
        if ($newStatus === TaskStatus::STATUS_DONE) {
            $auditables = $this->getTaskAudits($task, $taskName);
            if (is_array($auditables)) {
                $auditReport = TaskAudit::checkAll($auditables, $task->order);
                $failedAudits = [];
                foreach ($auditReport as $key => $value) {
                    if (!$value) {
                        array_push($failedAudits, $key);
                    }
                }
                
                if (!empty($failedAudits)) {
                    return [
                        'status' => 'Failed',
                        'message' => 'The following online activities for this task have not been completed:',
                        'incompleteTasks' => $failedAudits,
                        'customer' => [
                            'id' => $task->order->user_id
                        ]
                    ];
                }
            }
        }

        if (!empty($task) && $newStatus !== 0) {
            $task->task_status_id = $newStatus;
            $task->save();

            // Todo: Notify Coordinator of the change in the status of the task.
            $order = Order::find($task->order_id);

            // Check if order has been cancelled or placed on Hold, stop further action.
            if ($order->order_status_id == OrderStatus::CANCELLED || $order->order_status_id == OrderStatus::ON_HOLD || $order->paused) {
                return [
                    'status' => 'success'
                ];
            }

            // Check if all tasks with the same orderId have been completed.
            $orderTasks = Task::where('order_id', $task->order_id)->get();
            if ($orderTasks->count() == 0) {
                return [
                    'status' => 'success'
                ];
            }

            $undoneTasks = [];
            foreach ($orderTasks as $orderTask) {
                if($orderTask->task_status_id != TaskStatus::STATUS_DONE)
                {
                    array_push($undoneTasks, $orderTask);
                }
            }

            if (empty($undoneTasks)) {
                $currentProcess = $task->order->process;
                // Get the Item Process Data and confirm whether to trigger the next process or to notify administrator
                $processData = $this->getOrderProcesses($task->order);
                $currentProcessName = $currentProcess->name;
                $currentProcessData = $this->getCurrentProcessData($processData->processes, $currentProcessName);

                if (!empty($currentProcessData)) {
                    // Update Order Status
                    if (property_exists($currentProcessData, 'orderStatus') && $currentProcessData->orderStatus){
                        $this->updateOrderStatus($order, $currentProcessData);
                    }

                    $autoStartNextProcess = $currentProcessData->autoStartNextProcess;
                    $nextProcess = $this->getNextProcess($processData->processes, $currentProcessName);
                    
                    // Notify Project Coordinator that all tasks have been completed
                    if (property_exists($currentProcessData, 'whoCoordinates') && !empty($currentProcessData->whoCoordinates) && $currentProcessData->whoCoordinates != 'None') {
                        $projectCoordinatorRole = Role::where('name', $currentProcessData->whoCoordinates)->first();
                        TaskAssigner::generateTaskCompletionNotice($order->branch, $projectCoordinatorRole, $currentProcessName, $nextProcess->name ?? null, $autoStartNextProcess);
                    }

                    $nextProcessRecord = $nextProcess ? Process::where('name', $nextProcess->name)->first() : null;

                    // Send Communication to Customer
                    $linkExpirationMinutes = 60 * 60 * 24;
                    $config = [
                        'order' => $order,
                        'customer' => User::where('id', $order->user_id)->first(),
                        'nextProcess' => $nextProcessRecord,
                        'url' => URLGenerator::generateAndShortenSignedUrl($order->user_id, $linkExpirationMinutes),
                    ];

                    TaskAssigner::sendCustomerCommunication($currentProcessData, 'Completion', $config);

                    // If there is a next process, set the status of the order to the next process' and trigger task for the next process
                    if (!empty($nextProcess)) {
                        if ($autoStartNextProcess) {
                            $this->initiateNextProcess($order, $nextProcess);
                        } else {
                            // Flag Order so that an Admin or the Coordinator of the current process can manually forward order to the process
                            $order->human_forwarding = true;
                            $order->current_coordinator_role = $currentProcessData->whoCoordinates;
                            $order->save();

                            // Todo: Send Signal to Team responsible for task forwarding on client so that they can review and forward the task
                        }
                    }
                }
            }
        }

        return [
            'status' => 'success'
        ];
    }

    private function updateOrderStatus($order, $currentProcessData): void
    {
        $orderStatus = OrderStatus::where('name', $currentProcessData->orderStatus)->first();
        if (!empty($orderStatus)) {
            $order->order_status_id = $orderStatus->id;
            $order->save();

            ReportBuilder::build($orderStatus->name, $order->quantity);
        }
        
    }

    private function getNextProcess(array $processes, $currentProcessName)
    {
        $currentProcessIndex = -1;
        foreach ($processes as $process) {
            $currentProcessIndex++;
            if ($process->name == $currentProcessName) {
                break;
            }
        }

        return $processes[$currentProcessIndex + 1] ?? null;
    }

    private function getCurrentProcessData(array $processes, String $currentProcessName)
    {
        $currentProcessData = null;
        foreach ($processes as $process) {
            if ($process->name == $currentProcessName) {
                $currentProcessData = $process;
                break;
            }
        }

        return $currentProcessData;
    }

    
    /**
     * Initiate Next Process
     * @param \App\Models\Order $order
     * @return void
     */
    private function initiateNextProcess(Order $order, $nextProcess): void
    {
        $nextProcessRecord = Process::where('name', $nextProcess->name)->first();

        if (!empty($nextProcessRecord)) {
            $order->process_id = $nextProcessRecord->id;
            $order->human_forwarding = false; // Remove the link for human to forward the process
            $order->save();

            // Trigger the distribution of tasks for the next process
            TaskAssigner::assignProcessTasks($order->item, $order, $nextProcess->name);
        }
    }

    public function humanInitiateNextProcess(Request $request)
    {
        $order = Order::find($request->orderId);
        if (!empty($order)) {
            $currentProcess = $order->process->name;
            $orderProcesses = $this->getOrderProcesses($order);
            $nextProcess = $this->getNextProcess($orderProcesses->processes, $currentProcess);
            $this->initiateNextProcess($order, $nextProcess);

            return [
                'status' => 'success',
                'message' => sprintf('%s process successfully initialized', $nextProcess->name),
                'currentProcess' => $nextProcess->name,
            ];
        } else {
            return [
                'status' => 'failed',
                'message' => 'Unable to identify the order'
            ];
        }
    }

    private function getOrderProcesses(Order $order)
    {
        $item = $order->item;
        return json_decode($item->process_data);
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'taskId' => 'required|integer|exists:tasks,id',
            'receiverId' => 'required|integer|exists:users,id',
        ]);

        $task = Task::find($request->taskId);
        $task->user_id = $request->receiverId;
        $task->save();

        $receiver = User::find($request->receiverId);
        // Todo: Send push message to notify the staff that a new task has been received.
        

        return [
            'status' => 'success',
            'message' => sprintf('Task %s has been transferred to %s', $task->name, $receiver->name)
        ];
    }
}
