<?php

namespace App\Services;

use App\Notifications\AdminNotification;
use App\Notifications\BasicNotification;
use App\Models\User;
use App\Notifications\NewAdvertisementNotification;
use App\Notifications\NewResourceNotification;
use App\Notifications\sendNotificationToStudent;
use App\Notifications\SendRequestToAdminToAddArticleNotification;
use App\Notifications\SendRequestToAdminToEditArticleNotification;
use App\Notifications\WelcomeNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class NotificationService
{

    public function index()
    {
        return auth()->user()->notifications;
    }
    public function sendFCMNotification(string $fcmToken, string $title, string $message): array
    {
        if(! $fcmToken)
        {
            return [
                'status' => false,
                'message' =>'Fcm token is required',
            ];
        }
        try {
            $factory = (new Factory)
                ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')));

            $messaging = $factory->createMessaging();
            $message = CloudMessage::withTarget('token', $fcmToken)
                ->withNotification(FirebaseNotification::create($title, $message))
                ->withData([
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'sound' => 'default',
                ]);

            $messaging->send($message);
            return[
                'status'=>'true',
                'message'=> 'Notification Sent successfully'
            ];

        } catch (\Throwable $e) {
            return[ 'message'=>$e->getMessage()];
        }
    }
    public function sendToAdmin($admin, string $title, string $message, string $content ,string $information): void
    {
        Notification::send($admin, new AdminNotification($title,  $message, $content , $information));
    }
    public function sendAdvertToUsers($users, string $title, string $message, int $advertisementId, string $teacherInfo): void
    {
        Notification::send($users, new NewAdvertisementNotification($title, $message, $advertisementId, $teacherInfo));
      foreach ($users as $user) {
            $this->sendFCMNotification($user->fcm_token, $title, $message);
        }
    }

    public function sendResourceToUsers($users, string $title, string $message, int $resourceId): void
    {
        Notification::send($users, new NewResourceNotification($title, $message, $resourceId));
        foreach ($users as $user) {
            $this->sendFCMNotification($user->fcm_token, $title, $message);
        }

    }
    public function sendToUser($user, string $title, string $message): void
    {
        Notification::send($user, new WelcomeNotification($user , $title, $message));
        $this->sendFCMNotification($user->fcm_token, $title, $message);
    }
    public function sendToUserForArticle($user, string $title, string $message): void
    {
        Notification::send($user, new SendRequestToAdminToAddArticleNotification($title, $message));
        $this->sendFCMNotification($user->fcm_token, $title, $message);
    }
    public function sendToUserForEditArticle($user, string $title, string $message): void
    {
        Notification::send($user, new SendRequestToAdminToEditArticleNotification($title, $message));
        $this->sendFCMNotification($user->fcm_token, $title, $message);
    }
    public function sendToStudents($students, string $title, string $message): void
    {
        foreach ($students as $student) {
            Notification::send ($student,new sendNotificationToStudent($title, $message));
            //$this->sendFCMNotification($student->fcm_token, $title, $message);
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

