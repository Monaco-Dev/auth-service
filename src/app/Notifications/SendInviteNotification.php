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
        $mail = (new MailMessage)
            ->subject('New Connect Invitation')
            ->line($this->user->full_name . ' just sent you an invite!');

        if ($this->message) {
            $mail = $mail->line('Message:')
                ->line($this->message);
        }

        $mail = $mail->action('See profile', url(config('services.web_url') . $this->user->url))
            ->line('Thank you for using our application!');

        return $mail;
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
