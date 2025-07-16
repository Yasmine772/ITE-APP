<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;

class SendRequestToAdminToEditArticleNotification extends Notification
{
    use Queueable;

    protected string $title;
    protected string $message;
    public function __construct(string $title, string $message)
    {
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
        return ['database',FcmChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'title' => $this->title,
            'message' => $this->message,

        ]);
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toFcm(object $notifiable): FcmMessage
    {
        return FcmMessage::create()
            ->setNotification(
                FcmNotification::create()
                    ->setTitle($this->title)
                    ->setBody($this->message)
            )
            ->setData([
                'resource_title' => $this->title,
                'resource_message' => $this->message,

                'resource_id' => (string) $this->resourceId ?? '',
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

