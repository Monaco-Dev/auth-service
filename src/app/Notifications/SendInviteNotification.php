<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendInviteNotification extends Notification
{
    use Queueable;

    protected $user, $message;

    /**
     * Create a new notification instance.
     *
     * @param App\Models\User $user
     * @param string|null $message
     */
    public function __construct($user, $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Let\'s connect!')
            ->markdown('mail.send-invite', [
                'name' => $this->user->full_name,
                'message' => $this->message,
                'url' => url(config('services.web_url') . '/profile/' . $this->user->uuid)
            ]);
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
