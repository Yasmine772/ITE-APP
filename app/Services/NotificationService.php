<?php

namespace App\Services;

use App\Notifications\BasicNotification;
use App\Models\User;
use App\Notifications\NewAdvertisementNotification;
use App\Notifications\NewResourceNotification;
use App\Notifications\sendNotificationToStudent;
use App\Notifications\sendNotificationToStudentNotification;
use App\Notifications\SendRequestToAdminToAddArticleNotification;
use App\Notifications\SendRequestToAdminToEditArticleNotification;
use App\Notifications\WelcomeNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class NotificationService
{

    public function index()
    {
        return auth()->user()->notifications;
    }
    public function sendAdvertToUsers($users, string $title, string $message, int $advertisementId, string $teacherInfo): void
    {
        Notification::send($users, new NewAdvertisementNotification($title, $message, $advertisementId, $teacherInfo));
    }

    public function sendResourceToUsers($users, string $title, string $message, int $resourceId): void
    {
        Notification::send($users, new NewResourceNotification($title, $message, $resourceId));
    }
    public function sendToUser($user, string $title, string $message): void
    {
        Notification::send($user, new WelcomeNotification($user , $title, $message));
    }
    public function sendToUserForArticle($user, string $title, string $message): void
    {
        Notification::send($user, new SendRequestToAdminToAddArticleNotification($title, $message));
    }
    public function sendToUserForEditArticle($user, string $title, string $message): void
    {
        Notification::send($user, new SendRequestToAdminToEditArticleNotification($title, $message));
    }
    public function sendToStudents($user, string $title, string $message): void
    {
        $students = User::role('student')->get();
        foreach ($students as $student) {
            $student->notify(new sendNotificationToStudent($title, $message));
        }

    }

    public function markAsRead($notificationId): bool
    {
        $notification = auth()->user()->notifications()->findOrFail($notificationId);

        if($notification)
        {
            $notification->markAsRead();
            return true;
        }
        else return false;
    }
     public function markAllAsRead(): true
     {
         auth()->user()->unreadNotifications->markAsRead();
         return true;

    }
    public function destroy($notificationId): bool
    {
        $notification = auth()->user()->notifications()->findOrFail($notificationId);

        if($notification)
        {
            $notification->delete();
            return true;
        }
        else return false;
    }
    public function destroyAll()
    {
       return auth()->user()->notifications()->delete();
    }
    public function countUnreadNotifications(): int
    {
        return auth()->user()->unreadNotifications()->count();

    }

    public function unreadNotifications()
    {
       return  auth()->user()->unreadNotifications()->get();
    }



}

