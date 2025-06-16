<?php

namespace App\Services;

use App\Models\Roadmap;
use App\Models\RoadmapProgress;
use App\Models\CourseProgress;

class RoadmapProgressService
{
    public function recalculate(int $userId, int $roadmapId): void
    {
        $roadmap = Roadmap::with('steps.courses')->findOrFail($roadmapId);

        $completedCourseIds = CourseProgress::where('user_id', $userId)
            ->where('completed', true)
            ->pluck('course_id')
            ->toArray();

        $totalSteps = $roadmap->steps->count();
        $completedSteps = 0;

        foreach ($roadmap->steps as $step) {
            $stepCourseIds = $step->courses->pluck('id')->toArray();
            if (!empty(array_intersect($stepCourseIds, $completedCourseIds))) {
                $completedSteps++;
            }
        }

        $progressPercentage = $totalSteps > 0
            ? round(($completedSteps / $totalSteps) * 100)
            : 0;

        $isCompleted = $completedSteps === $totalSteps;

        RoadmapProgress::updateOrCreate(
            [
                'user_id' => $userId,
                'roadmap_id' => $roadmapId,
            ],
            [
                'completed_steps' => $completedSteps,
                'total_steps' => $totalSteps,
                'progress_percentage' => $progressPercentage,
                'completed' => $isCompleted,
                'completed_at' => $isCompleted ? now() : null,
            ]
        );
    }

    public function getProgress(int $userId, int $roadmapId): array
    {
        $roadmap = Roadmap::with('steps.courses')->findOrFail($roadmapId);
        $progress = RoadmapProgress::where('user_id', $userId)
            ->where('roadmap_id', $roadmapId)
            ->first();

        $currentStep = null;

        $completedCourseIds = CourseProgress::where('user_id', $userId)
            ->where('completed', true)
            ->pluck('course_id')
            ->toArray();

        foreach ($roadmap->steps as $step) {
            $stepCourseIds = $step->courses->pluck('id')->toArray();
            if (empty(array_intersect($stepCourseIds, $completedCourseIds))) {
                $currentStep = $step->order;
                break;
            }
        }

        return [
            'roadmap_id' => $roadmap->id,
            'user_id' => $userId,
            'total_steps' => $progress?->total_steps ?? $roadmap->steps->count(),
            'completed_steps' => $progress?->completed_steps ?? 0,
            'progress_percentage' => $progress?->progress_percentage ?? 0,
            'completed' => $progress?->completed ?? false,
            'completed_at' => $progress?->completed_at,
            'current_step' => $currentStep,
        ];
    }
}
