<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class OrderReactivatedNotification extends Notification
{
    use Queueable;

    protected $order;
    protected $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($order, $user = null)
    {
        $this->order = $order;
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
                'Resume Work. Order %s has been reactivated. Open Order.',
                $this->order->order_number ?? $this->order->name
            ),
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
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
                'Resume Work. Order %s has been reactivated. Open Order.',
                $this->order->order_number ?? $this->order->name
            ),
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'url' => route('order.view', $this->order->id)
        ]);
    }
}
