<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResourceRequest;
use App\Models\Course;
use App\Models\Resource;
use App\Models\Subject;
use App\Services\NotificationService;
use App\Services\ResourceService;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;

class ResourceController extends Controller
{
    use ApiResponseTrait;
    private ResourceService $resourceService;
    private NotificationService $notificationService;
    public function __construct(ResourceService $resourceService, NotificationService $notificationService)
    {
        $this->resourceService = $resourceService;
        $this->notificationService = $notificationService;
    }
    public function index()
    {
       $teacher = auth()->user()->teacher;
       $resources = $teacher->resources;
        return $this->successResponse(['resources' => $resources]);

    }
    public function store(ResourceRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $resource = $this->resourceService->store($data);
        return $this->successResponse($resource,'Resource created successfully.',200);
    }
    public function update(ResourceRequest $request, int $id): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $resource = $this->resourceService->update($data, $id);
        return $this->successResponse($resource, 'Resource updated successfully', 200);
    }
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        $this->resourceService->destroy($id);
        return $this->successResponse(null,'Resource deleted successfully',200);
    }
    public function destroyAll(): \Illuminate\Http\JsonResponse
    {
        $this->resourceService->destroyAll();
        return $this->successResponse(null,'Resources deleted successfully',200);
    }
    public function showAll(): \Illuminate\Http\JsonResponse
    {
        $resources = Resource::get();
        return $this->successResponse(['resources' => $resources]);

    }

}

