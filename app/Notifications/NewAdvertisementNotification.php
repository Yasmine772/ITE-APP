<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

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
        return ['database', FcmChannel::class];
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

    public function toFcm($notifiable): FcmMessage
    {
        return FcmMessage::create()
            ->setData([
                'title' => $this->title,
                'message' => $this->message,
                'advertisement_id' => (string) $this->advertisementId,
                'teacher_info' => $this->teacherInfo,
            ])
            ->setNotification(
                FcmNotification::create()
                    ->setTitle($this->title)
                    ->setBody($this->message . "\n" . $this->teacherInfo)
            );
    }
}
