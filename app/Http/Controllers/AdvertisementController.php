<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdvertisementRequest;
use App\Http\Requests\StoreAdvertisementRequest;
use App\Http\Requests\UpdateAdvertisementRequest;
use App\Models\Advertisement;
use App\Models\User;
use App\Notifications\NewAdvertisementNotification;
use App\Services\NotificationService;
use App\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;


class AdvertisementController extends Controller
{
    use ApiResponseTrait;
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function index(): JsonResponse
    {
        $advertisements = auth()->user()->advertisements()->get();
        return $this->successResponse(['Your Advertisements' => $advertisements]);
    }
    public function store(StoreAdvertisementRequest $request): JsonResponse
    {
        $user = auth()->user();
        $advertisementData = $request->validated();
        $advertisementData['user_id'] = $user->id;
        $advertisement = Advertisement::create($advertisementData);

        $title = $advertisementData['title'];
        $message = $advertisementData['description'];
        $advertisementId = $advertisement->id;
        $teacherInformation = "{$user->name} ";

        $students = User::role('student')->get();
        $advertisement->recipients()->attach($students->pluck('id'));
        $this->notificationService->sendAdvertToUsers($students, $title, $message, $advertisementId, $teacherInformation);

        return $this->successResponse([
            'advertisement' => $advertisement,
            'TeacherInformation' => $teacherInformation
        ], 'Saved Successfully', 201);
    }

    public function update(UpdateAdvertisementRequest $request, int $id): JsonResponse
    {
        $user_id = Auth::user()->id;
        $advertisement = Advertisement::findOrFail($id);
        if ($advertisement->user_id != $user_id) {
            return $this->errorResponse('You are not authorized to perform this action', 403);
        }
        $advertisementData = $request->validated();
        $advertisement->update($advertisementData);
        return $this->successResponse(['advertisement' => $advertisement], 'Updated Successfully', 200);
    }
    public function destroy(int $id): JsonResponse
    {
        $advertisement = Advertisement::findOrFail($id);
        if (!$advertisement) {
            return $this->errorResponse('Advertisement not found', 404);
        }
        $advertisement->delete();
        return $this->successResponse([], 'Advertisement deleted Successfully', 200);
    }
    public function destroyAll(): JsonResponse
    {
        auth()->user()->advertisements()->delete();
        return $this->successResponse([], 'All Advertisements deleted Successfully', 200);
    }
    public function destroyAdmin(int $id): JsonResponse
    {
        $advertisement = Advertisement::findOrFail($id);
        if (!$advertisement) {
            return $this->errorResponse('Advertisement not found', 404);
        }
        $advertisement->delete();
        return $this->successResponse([], 'Advertisement deleted Successfully', 200);
    }
    public function destroyAllAdmin(): JsonResponse
    {
        Advertisement::query()->delete();
        return $this->successResponse([], 'All Advertisements deleted Successfully', 200);
    }
    public function showAll(): JsonResponse
    {
        $allAdvertisements = Advertisement::get();
        return $this->successResponse(['allAdvertisements' => $allAdvertisements]);
    }
    public function showDetails(int $id): JsonResponse
    {
        try {
            $student = auth()->user();
            $advertisement = $student->receivedAdvertisements()->where('advertisement_id', $id)->first();
        if (!$advertisement) {
            return $this->errorResponse('Advertisement not found or not accessible.', 404);
        }
        return $this->successResponse(['advertisement' => $advertisement]);
        }
        catch (ModelNotFoundException $e)
        {
            $this->errorResponse($e->getMessage(), 404);
        }

    }
    public function showAllStudent(): JsonResponse
    {
        $student = auth()->user();
        $adverts = $student->receivedAdvertisements()->latest()->get();
        return $this->successResponse(['receivedAdvertisements' => $adverts]);

    }
}
