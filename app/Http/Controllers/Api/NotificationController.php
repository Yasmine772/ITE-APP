<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\NotificationService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use ApiResponseTrait;
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

     public function index()
     {
         return $this->notificationService->index();
     }
    public function send(Request $request): \Illuminate\Http\JsonResponse
    {
        $input = $request->validate([
            'title' => 'required|string|max:50',
            'message' => 'required|string|max:255',
        ]);

        $user = auth()->user();
        $this->notificationService->send($user, $input['title'], $input['message']);

        return response()->json([
            'message' => 'Notification sent successfully',
        ], 200);
    }


    public function markAllAsRead(): \Illuminate\Http\JsonResponse
    {
         $this->notificationService->markAllAsRead();
        return $this->successResponse([],'All notifications marked as read',200);
    }

    public function destroy($notificationId): \Illuminate\Http\JsonResponse
    {
        $this->notificationService->destroy($notificationId);
        return $this->successResponse([],'Notification deleted Successfully',200);
    }

    public function destroyAll(): \Illuminate\Http\JsonResponse
    {
        $this->notificationService->destroyAll();
        return $this->successResponse([],'All notifications deleted successfully',200);
    }

    public function countUnreadNotifications(): \Illuminate\Http\JsonResponse
    {
        $response = $this->notificationService->countUnreadNotifications();
        return $this->successResponse(['unreadNotificationNumber'=>$response],'',200);
    }

    public function unreadNotification(): \Illuminate\Http\JsonResponse
    {
        $response = $this->notificationService->countUnreadNotifications();
        return $this->successResponse(['unreadNotification'=> $response],'Notifications Unread',200);
    }
}
