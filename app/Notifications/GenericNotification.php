<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class GenericNotification extends Notification
{
    use Queueable;

    protected $url;
    protected $message;
    protected $user;
    protected $type;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $config)
    {
        $this->message = $config['message'];
        $this->url = $config['url'];
        $this->user = $config['user'];
        $this->type = $config['type'];
    }

    public function toBroadcast(object $notifiable)
    {
        return new BroadcastMessage([
            'message' => $this->message,
            'url' => $this->url,
        ]);
    }

    public function broadcastOn(): array
    {
        return ['private-App.Models.User.' . $this->user->id];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return $this->type;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
