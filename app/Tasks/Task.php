<?php

namespace App\Tasks;

use App\Helper\URLGenerator;
use App\Messaging\EmailClient;
use App\Messaging\SMSClient;
use App\Messaging\TemplateManager;
use App\Messaging\WhatsAppClient;
use App\Models\Branch;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Process;
use App\Models\Role;
use App\Models\TaskStatus;
use App\Models\User;
use App\Models\Task as TaskModel;
use App\Models\Notification;
use App\Report\ReportBuilder;
use Illuminate\Support\Facades\Log as FacadesLog;
use Log;
use PhpParser\Node\Stmt\TryCatch;

class Task
{


    public static function assignProcessTasks(Item $item, Order $order, string $processName)
    {
        $processesData = json_decode($item->process_data);
        if (!self::isSupportedProcess($processesData, $processName)) {
            return false;
        }

        $productionBranches = json_decode($item->order_processing_branches); // Array of production branches
        $primaryBranch = $item->primary_order_processing_branch; // Principal Production Branch
        $branchName = $primaryBranch; // Always use primary processing center

        $processData = json_decode($item->process_data); // Get data for each of the processes

        $tasks = is_array($processData->tasks) ? $processData->tasks[$processName] : $processData->tasks->$processName; // Get tasks for new Process
        
        if (is_array($tasks)) {
            self::showTasksToTeams($tasks, $order, $processName, $branchName);
        }

        self::sendStartProcessCommunication($order, $processesData->processes, $processName);

        return true;
    }

    private static function showTasksToTeams($tasks, $order, $processName, $targetBranch)
    {
        // Loop through task and drop notification for all teams that should work on the task.
        foreach ($tasks as $task) {
            $taskTeam = $task->team;

            // Use Primary Branch if order cannot be processed at branch selected by the customer
            $team = Role::where('name', $taskTeam)->first();
            $branch = Branch::where('name', $targetBranch)->first();

            if (empty($team)) {
                continue;
            }

            if (empty($branch)) {
                continue;
            }

            $templateManager = new TemplateManager([
                'customer' => $order->user,
                'order' => $order,
                'team' => $team,
            ]);

            $taskName = $templateManager->prepareText($task->name);
            $taskDescription = $templateManager->prepareText($task->description);

            // Drop task for all team members in the category
            self::createTask($taskName, $taskDescription, $order, $processName, $branch, $team);

            // Broadcast Push Notification to team members within the selected branch
            self::sendTeamNotification($team, $branch, 'App\Notifications\NewTaskNotification', $taskName, $order);


            // Save Notifications for each staff member on the team
            self::generateNotification($branch, $team, $processName, $taskName);
        }
    }

    /**
     * Send Team Notification
     * Send notification to team members
     * @param \App\Models\Role $team     The team to send the message to
     * @param \App\Models\Branch $branch The branch the teams must belong to
     * @param string $notification       The notification to send to the team
     * @param string $message            The notification message
     * @param \App\Models\Order $order   The Order the notification is related to
     * @param array $exceptUsers         An array of ids of users to exempt from the broadcast
     * @param array $types               The types of notifications to send. Supported values ['broadcast','mail','sms']
     * @return void
     */
    public static function sendTeamNotification(Role $team, Branch $branch, string $notification,  string $message, Order $order = null, array $exceptUsers = [], array $types = ['broadcast'])
    {
        $teamMembers = User::where('role_id', $team->id)
            ->where('branch_id', $branch->id)->get();

        if (!empty($teamMembers)) {
            foreach ($teamMembers as $teamMember) {
                if (empty($exceptUsers) || !in_array($teamMember->id, $exceptUsers)) {
                    $config = [
                        'message' => $message,
                        'user' => $teamMember,
                        'types' => $types,
                        'order' => $order,
                    ];

                    $teamMember->notify(new $notification($config));
                }
            }
        }
    }

    private static function sendStartProcessCommunication(Order $order, $processes, string $processName): void
    {
        foreach ($processes as $object) {
            if ($object->name == $processName) {
                // Check if there's message to send to customer at the beginning of the process and send them
                $linkExpirationMinutes = 60 * 60 * 24; // Link valid for 1 Day
                $config = [
                    'order' => $order,
                    'customer' => User::where('id', $order->user_id)->first(),
                    'nextProcess' => property_exists($object, 'nextProcess') ? OrderStatus::where('name', $object->nextProcess)->first() : null,
                    'url' => URLGenerator::generateAndShortenSignedUrl($order->user_id, $linkExpirationMinutes),
                ];

                self::sendCustomerCommunication($object, 'Start', $config);

            }
        }
    }

    private static function isSupportedProcess($processesData, string $processName) : bool
    {
        $isSupportedProcess = false;
        // Check that the process is supported for the product
        foreach ($processesData->processes as $processObject) {
            if ($processName == $processObject->name) {
                $isSupportedProcess = true;
                break;
            }
        }

        return $isSupportedProcess;
    }

    public static function generate(Item $item, Order $order, string $process): bool
    {
        // Generate report for the new process.
        ReportBuilder::build($process, $order->quantity);

        // Check that the process is an allowed process;
        $orderStatuses = OrderStatus::getOrderStatusesArray();
        if(!in_array($process, $orderStatuses)) {
            return false;
        }

        $productionBranches = json_decode($item->order_processing_branches); // Array of production branches
        $primaryBranch = $item->primary_order_processing_branch; // Principal Production Branch
        $branchName = in_array($order->branch->name, $productionBranches) ? $order->branch->name : $primaryBranch;
        $processData = json_decode($item->process_data); // Get data for each of the processes

        $tasks = is_array($processData->tasks) ? $processData->tasks[$process] : $processData->tasks->$process; // Get tasks for new Process

        if (is_array($tasks)) {
            self::showTasksToTeams($tasks, $order, $process, $branchName);
        }

        // Check for communications that are meant to be sent at the start of this process
        self::sendStartProcessCommunication($order, $processData->processes, $process);

        return true;
    }

    public static function sendCustomerCommunication($process, $sendTime, array $config)
    {
        // Get Invoice for the Order

        try {
            if ($process->emailTemplate != 'None' && !empty($process->emailTemplate) && $process->sendEmailAt == $sendTime) {
                $emailClient = new EmailClient($config);
                 $emailClient->sendCustomerEmail($process->emailTemplate);
             }
        } catch (\Throwable $th) {
            FacadesLog::error($th->getMessage());
        }

        try {
            if ($process->smsTemplate != 'None' && !empty($process->smsTemplate) && $process->sendSmsAt == $sendTime) {
                $smsClient = new SMSClient($config);
                $smsClient->sendCustomerSms($process->smsTemplate);
            }
        } catch (\Throwable $th) {
            FacadesLog::error($th->getMessage());
        }


        try {
            if ($process->whatsappTemplate != 'None' && !empty($process->whatsappTemplate) && $process->sendWhatsappAt == $sendTime) {
                $whatsappClient = new WhatsAppClient($config);
                $whatsappClient->sendCustomerMessage($process->whatsappTemplate);
            }
        } catch (\Throwable $th) {
            FacadesLog::error($th->getMessage());
        }
    }


    /**
     * Create New Task
     * @param mixed $taskName
     * @param mixed $taskDescription
     * @param \App\Models\Order $order
     * @param \App\Models\Branch $branch
     * @param \App\Models\Role $team
     * @return void
     */
    private static function createTask($taskName, $taskDescription, Order $order, string $processName, Branch $branch, Role $team): void
    {
        $process = Process::where('name', $processName)->first(['id']);
        
        if (empty($process)) {
            return;
        }

        TaskModel::create([
            'name'=> $taskName,
            'description'=> $taskDescription,
            'process_id' => $process->id,
            'order_id'=> $order->id,
            'branch_id'=> $branch->id,
            'role_id'=> $team->id
        ]);
    }

    /**
     * Generate Notification
     * @param \App\Models\Branch $branch
     * @param \App\Models\Role $role
     * @param string $process
     * @return void
     */
    public static function generateNotification(Branch $branch, Role $role, string $process, string $taskName): void
    {
        $staff = User::where('branch_id', $branch->id)->where('role_id', $role->id)->get();
        if ($staff->count() > 0) {
            foreach ($staff as $worker) {
                Notification::create([
                    'user_id'=> $worker->id,
                    'title' => sprintf('%s, Claim New %s Task', $worker->name, $process),
                    'message' => sprintf('Your new task %s awaits. The rest of the team is counting on you.', $taskName)
                ]);
            }
        }
    }

    public static function generateTaskCompletionNotice(Branch $branch, Role $role, string $process, string $nextProcess = null, bool $autoStartNextProcess = false): void
    {
        $message = sprintf('Please team members have completed all tasks in the %s process. ', $process);
        if ($autoStartNextProcess && !empty($nextProcess)) {
            $message .= sprintf('Tasks for %s process has been automatically created.', $nextProcess);
        }

        // Todo: If there is a Next Process but is not to start automatically, the coordinator should be notified to start
        if (!$autoStartNextProcess && !empty($nextProcess)) {
            # code...
        }

        // Create Notification for coordinators
        $staff = User::where('branch_id', $branch->id)->where('role_id', $role->id)->get();
        if ($staff->count() > 0) {
            foreach ($staff as $worker) {
                Notification::create([
                    'user_id'=> $worker->id,
                    'title' => sprintf('All %s Tasks Done', $process),
                    'message' => $message
                ]);
            }
        }

        // Todo: Send Push Notification to Coordinators

    }
}
