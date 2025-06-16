<?php

namespace App\Http\Controllers;

use App\Services\RoadmapProgressService;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;

class RoadmapProgressController extends Controller
{
    use ApiResponseTrait;

    protected $roadmapProgressService;

    public function __construct(RoadmapProgressService $roadmapProgressService)
    {
        $this->roadmapProgressService = $roadmapProgressService;
    }

    // حساب التقدم في الخطة الدراسية
    public function recalculate(Request $request, $roadmapId)
    {
        $userId = Auth::id();  // الحصول على معرّف المستخدم الحالي

        try {
            $this->roadmapProgressService->recalculate($userId, $roadmapId);

            return $this->successResponse(null, 'Progress recalculated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error: ' . $e->getMessage(), null, 500);
        }
    }

    // عرض التقدم في الخطة الدراسية
    public function showProgress(Request $request, $roadmapId)
    {
        $userId = Auth::id();  // الحصول على معرّف المستخدم الحالي

        try {
            $progress = $this->roadmapProgressService->getProgress($userId, $roadmapId);

            return $this->successResponse($progress, 'Progress retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Unexpected error: ' . $e->getMessage(), null, 500);
        }
    }
}
