<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskClaimedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $task;

    /**
     * Create a new event instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('task-claims.' . $this->task->role_id . '.' . $this->task->branch_id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'task-claimed';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        // Debug: Log the channel and broadcast data
        \Log::info('TaskClaimedEvent Broadcasting', [
            'channel' => 'task-claims.' . $this->task->role_id . '.' . $this->task->branch_id,
            'task_id' => $this->task->id,
            'task_name' => $this->task->name,
            'role_id' => $this->task->role_id,
            'branch_id' => $this->task->branch_id,
            'claimed_by' => $this->task->user->name,
        ]);

        return [
            'task_id' => $this->task->id,
            'task_name' => $this->task->name,
            'claimed_by' => [
                'id' => $this->task->user->id,
                'name' => $this->task->user->name,
            ],
            'order_id' => $this->task->order_id,
            'role_id' => $this->task->role_id,
            'branch_id' => $this->task->branch_id,
        ];
    }
}
