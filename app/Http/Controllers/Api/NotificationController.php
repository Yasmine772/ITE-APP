<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\NotificationService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Illuminate\Support\Facades\Log;

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

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'fcm_token' => 'required|string',
        ]);
        $user = User::find($request->user_id);
        $user->fcm_token = $request->fcm_token;
        $user->save();
        return response()->json(['message' => 'Token stored successfully.']);
    }


public function send(Request $request): \Illuminate\Http\JsonResponse
    {
        $input = $request->validate([
            'title' => 'required|string|max:50',
            'message' => 'required|string|max:255',
        ]);

        $user = auth()->user();
        $this->notificationService->sendToStudents($user, $input['title'], $input['message']);

        return response()->json([
            'message' => 'Notification sent successfully',
        ], 200);
    }
    public function markAsRead($notificationId): \Illuminate\Http\JsonResponse
    {
        $this->notificationService->markAsRead($notificationId);
        return response()->json(['message' => 'Notification read successfully'], 200);
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
