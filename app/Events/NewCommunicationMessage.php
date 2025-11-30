<?php

namespace App\Events;

use App\Models\OrderConversation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewCommunicationMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $orderConversation;

    /**
     * Create a new event instance.
     */
    public function __construct(OrderConversation $orderConversation)
    {
        $this->orderConversation = $orderConversation;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('order-chat.' . $this->orderConversation->order_id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'new-message';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->orderConversation->id,
            'message' => $this->orderConversation->message,
            'created_at' => $this->orderConversation->created_at,
            'user' => [
                'id' => $this->orderConversation->user->id,
                'name' => $this->orderConversation->user->name,
                'mobile' => $this->orderConversation->user->mobile,
            ],
        ];
    }
}
