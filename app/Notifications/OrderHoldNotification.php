<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class OrderHoldNotification extends Notification
{
    use Queueable;

    protected $order;
    protected $holdReason;
    protected $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($order, $holdReason = null, $user = null)
    {
        $this->order = $order;
        $this->holdReason = $holdReason;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function broadcastOn()
    {
        $channels = ['private-order.' . $this->order->id];
        
        if ($this->user) {
            $channels[] = 'private-App.Models.User.' . $this->user->id;
        }
        
        return $channels;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => sprintf(
                'Stop Work. Order %s has been placed on hold. Click to see reason.',
                $this->order->order_number ?? $this->order->name
            ),
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'hold_reason' => $this->holdReason,
            'url' => route('order.view', $this->order->id)
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'message' => sprintf(
                'Stop Work. Order %s has been placed on hold. Click to see reason.',
                $this->order->order_number ?? $this->order->name
            ),
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'hold_reason' => $this->holdReason,
            'url' => route('order.view', $this->order->id)
        ]);
    }
}
