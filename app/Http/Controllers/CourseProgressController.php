<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProgressService;
use App\Traits\ApiResponseTrait;

class CourseProgressController extends Controller
{
    use ApiResponseTrait;

    protected ProgressService $progressService;

    public function __construct(ProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    // تحديث تقدم مشاهدة محتوى الدورة
    public function updateProgress(Request $request)
    {
        $validated = $request->validate([
            'course_content_id' => 'required|exists:course_contents,id',
            'position' => 'required|numeric|min:0',
        ]);

        $userId = auth()->id();
        if (!$userId) {
            return $this->errorResponse('Unauthorized', 401);
        }

        $progress = $this->progressService->updateContentProgress(
            $userId,
            $validated['course_content_id'],
            $validated['position']
        );

        return $this->successResponse($progress, 'Progress updated successfully');
    }

    // استرجاع آخر نقطة مشاهدة للمستخدم في محتوى الدورة
    public function getLastPosition(Request $request)
    {
        $validated = $request->validate([
            'course_content_id' => 'required|exists:course_contents,id',
        ]);

        $userId = auth()->id();
        if (!$userId) {
            return $this->errorResponse('Unauthorized', 401);
        }

        $position = $this->progressService->getLastWatchedPosition(
            $userId,
            $validated['course_content_id']
        );

        return $this->successResponse(['last_watched_position' => $position], 'Last watched position retrieved');
    }

    // إعادة حساب تقدم الدورة
    public function recalculateProgress(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $userId = auth()->id();
        if (!$userId) {
            return $this->errorResponse('Unauthorized', 401);
        }

        $this->progressService->recalculateCourseProgress(
            $validated['course_id'],
            $userId
        );

        return $this->successResponse(null, 'Course progress recalculated successfully');
    }

    public function getCourseProgress(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $userId = auth()->id();
        if (!$userId) {
            return $this->errorResponse('Unauthorized', 401);
        }

        $data = $this->progressService->getCourseWithProgressForUser(
            $validated['course_id'],
            $userId
        );

        return $this->successResponse($data, 'Course with progress retrieved successfully');
    }
}
