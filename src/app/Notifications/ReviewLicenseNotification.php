<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ReviewLicenseNotification extends Notification
{
    use Queueable;

    protected $link;

    /**
     * Create a new notification instance.
     */
    public function __construct($link)
    {
        $this->link = $link;
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
            ->cc([
                'reychanbernaldez1019@gmail.com',
                'jaztinpuma@gmail.com',
                'dlibor.dev@gmail.com',
            ])
            ->subject('Please Verify License - ' . date('d-m-Y'))
            ->greeting('Hello Admin!')
            ->line(new HtmlString('Once done, send back the file to this email: <a href="mailto:dlibor.dev@gmail.com">dlibor.dev@gmail.com</a>'))
            ->action('Download Here', $this->link)
            // ->attach($this->link)
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
