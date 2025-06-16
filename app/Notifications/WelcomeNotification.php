<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    use Queueable;

     protected $user ;
     protected $title ;
     protected $message ;
    public function __construct($user,$title,$message)
    {
        $this->user = $user;
        $this->title = $title;
        $this->message = $message;
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
    public function toArray(object $notifiable): array
    {
        return [

        ];
    }

public function toDatabase($notifiable): DatabaseMessage
{
    return new DatabaseMessage([
        'title' => 'Welcome to our application',
         'message' => "Hello {$this->user->name} ,let's get started and achieve your goals together! We're here to support you every step of the way."
    ]);
}
}

