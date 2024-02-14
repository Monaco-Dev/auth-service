<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LicenseNotification extends Notification
{
    use Queueable;

    protected $user, $isVerified;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $isVerified)
    {
        $this->user = $user;
        $this->isVerified = $isVerified;
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
        $name = $this->user->full_name;

        return (new MailMessage)
            ->subject('License Verification Result')
            ->greeting("Hello $name!")
            ->lineIf($this->isVerified, 'Congratulations, Your license has been approved!')
            ->lineIf(!$this->isVerified, 'Sorry, unfortunately your license has been denied. Please double check your license before submitting.')
            ->action('Log in', config('services.web_url'))
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
