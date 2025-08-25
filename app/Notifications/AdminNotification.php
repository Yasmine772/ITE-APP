<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminNotification extends Notification
{
    use Queueable;

     protected string $title ;
     protected string $message ;
     protected string  $content ;
     protected string  $information ;
    public function __construct(string $title,string $message , string $content , string $information)
    {
       $this->title = $title;
       $this->message = $message;
       $this->content  = $content ;
       $this->information = $information ;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'title' => $this->title ,
            'message' => $this->message,
             'content' => $this->content,
             'Information' => $this->information,

        ]);
    }
}
