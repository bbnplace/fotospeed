<?php

namespace App\Http\Controllers;

use App\Report\ReportBuilder;
use App\Tasks\Task as TaskAssigner;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Role;
use App\Models\Task;
use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TasksController extends Controller
{
    public function loadTeamTasks()
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
    
    public function loadTasks()
    {
        $tasks = [
            'Todo' => [],
            'Doing' => [],
            'Done'=> [],
        ];

        $records = Task::where('user_id', auth()->user()->id)->get();

        if (!empty($records)) {
            foreach ($records as $record) {
                // dd($record->taskStatus);
                array_push($tasks[$record->taskStatus->name], $record);
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

    public function updateTasks(Request $request)
    {
        $task = $request->task;
        $taskId = $task['id'];
        $taskName = $task['name'];
        $newStatus = TaskStatus::getTaskStatusId($request->toStatus);
        
        $task = Task::find($taskId);
        if (!empty($task) && $newStatus !== 0) {
            $task->task_status_id = $newStatus;
            $task->save();

            // Todo: Notify Controller of the change in the status of the task.

            $order = Order::find($task->order_id);

            // Check if all tasks with the same orderId have been completed.
            $undoneTask = Task::where('order_id', $task->order_id)->whereNot('task_status_id', TaskStatus::STATUS_DONE)->count();
            if ($undoneTask == 0) {
                $currentProcess = $task->order->orderStatus;
                // Get the Item Process Data and confirm whether to trigger the next process or to notify administrator
                $item = $task->order->item;
                $processData = json_decode($item->process_data);
                $currentProcessName = $currentProcess->name;

                $processes = $processData->processes;
                $currentProcessData = null;
                foreach ($processes as $process) {
                    if ($process->name == $currentProcessName) {
                        $currentProcessData = $process;
                    }
                }

                if (!empty($currentProcessData)) {
                    $autoStartNextProcess = $currentProcessData->autoStartNextProcess;
                    $nextProcess = $currentProcessData->nextProcess ?? null;
                    
                    // Notify Project Coordinator that all tasks have been completed
                    if (!empty($currentProcessData->whoCoordinates)) {
                        $projectCoordinatorRole = Role::where('name', $currentProcessData->whoCoordinates)->first();
                        TaskAssigner::generateTaskCompletionNotice($order->branch, $projectCoordinatorRole, $currentProcessName, $nextProcess, $autoStartNextProcess);
                    }

                    // If there is a next process, set the status of the order to the next process' and trigger task for the next process
                    if ($autoStartNextProcess && !empty($nextProcess)) {
                        $this->initiateNextProcess($order, $nextProcess);
                    }
                }
            }
        }

        return [
            'status' => 'success'
        ];
    }

    
    /**
     * Initiate Next Process
     * @param \App\Models\Order $order
     * @param string $nextProcess
     * @return void
     */
    private function initiateNextProcess(Order $order, string $nextProcess): void
    {
        
        // Generate report for the new process.
        ReportBuilder::build($nextProcess);

        $nextProcessRecord = OrderStatus::where('name', $nextProcess)->first();

        if (!empty($nextProcessRecord)) {
            $order->order_status_id = $nextProcessRecord->id;
            $order->save();

            // Trigger the distribution of tasks for the next process
            TaskAssigner::generate($order->item, $order, $nextProcess);
        }
    }
}
