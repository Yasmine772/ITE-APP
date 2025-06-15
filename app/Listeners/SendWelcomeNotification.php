<?php

namespace App\Listeners;

use App\Services\NotificationService;
use App\Traits\ApiResponseTrait;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendWelcomeNotification
{
    use ApiResponseTrait;
    protected notificationService $notificationService;

    public function __construct(notificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the event.
     */
    public function handle(Verified $event): void
    {
        $user = $event->user;
        $this->notificationService->sendToUser($user,'Welcome to our application','We hope you have a great experience,Best regards .  ');
    }
}






