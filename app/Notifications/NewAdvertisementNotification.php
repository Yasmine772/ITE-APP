<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use NotificationChannels\Fcm\FcmMessage;
//use NotificationChannels\Fcm\Resources\Notification as FcmNotification;



class NewAdvertisementNotification extends Notification
{
    use Queueable;

    protected string $title;
    protected string $message;
    protected int $advertisementId;
    protected string $teacherInfo;

    public function __construct(string $title, string $message, int $advertisementId, string $teacherInfo)
    {
        $this->title = $title;
        $this->message = $message;
        $this->advertisementId = $advertisementId;
        $this->teacherInfo = $teacherInfo;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'title' => $this->title,
            'message' => $this->message,
            'advertisement_id' => $this->advertisementId,
            'teacher_info' => $this->teacherInfo,
        ]);
    }


}

