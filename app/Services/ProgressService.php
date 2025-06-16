<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseProgress;
use App\Models\CourseContent;
use App\Models\CourseContentProgress;
use App\Events\CourseCompleted; // ✅ استدعاء الحدث

class ProgressService
{
    public function updateContentProgress($userId, $courseContentId, $positionInSeconds)
    {
        $content = CourseContent::findOrFail($courseContentId);

        $isCompleted = $positionInSeconds >= ($content->duration * 0.95); 

        $progress = CourseContentProgress::updateOrCreate(
            [
                'user_id' => $userId,
                'course_content_id' => $courseContentId,
            ],
            [
                'last_watched_position' => $positionInSeconds,
                'is_completed' => $isCompleted,
                'completed_at' => $isCompleted ? now() : null,
            ]
        );

        $this->recalculateCourseProgress($content->course_id, $userId);

        return $progress;
    }

    public function recalculateCourseProgress($courseId, $userId)
    {
        $total = CourseContent::where('course_id', $courseId)->count();

        if ($total === 0) {
            return;
        }

        $completed = CourseContentProgress::where('user_id', $userId)
            ->whereHas('courseContent', fn ($q) => $q->where('course_id', $courseId))
            ->where('is_completed', true)
            ->count();

        $percentage = round(($completed / $total) * 100);

        $courseProgress = CourseProgress::updateOrCreate(
            [
                'user_id' => $userId,
                'course_id' => $courseId,
            ],
            [
                'completed' => $percentage === 100,
                'progress_percentage' => $percentage,
                'last_accessed_at' => now(),
            ]
        );

        if ($percentage === 100 && !$courseProgress->wasRecentlyCreated && !$courseProgress->completed) {
            event(new CourseCompleted($userId, $courseId));
        }
    }

    public function getLastWatchedPosition($userId, $courseContentId)
    {
        return CourseContentProgress::where('user_id', $userId)
            ->where('course_content_id', $courseContentId)
            ->value('last_watched_position') ?? 0;
    }

    public function getCourseWithProgressForUser(int $courseId, int $userId)
    {
        $course = Course::with([
            'teacher.user',
            'category',
            'subject',
            'contents' => fn ($q) => $q->orderBy('order')
        ])->findOrFail($courseId);

        $progress = $course->progresses()->where('user_id', $userId)->first();
        $contentProgress = $userId
            ? $course->contents->load(['progresses' => fn ($q) => $q->where('user_id', $userId)])
            : [];

        $contents = $course->contents->map(function ($content) use ($userId) {
            $progress = $content->progresses->firstWhere('user_id', $userId);

            return [
                'id' => $content->id,
                'title' => $content->title,
                'description' => $content->description,
                'video_path' => $content->video_path,
                'duration' => $content->duration,
                'duration_hms' => $content->duration_hms,
                'order' => $content->order,
                'attachment' => $content->attachment,
                'average_rating' => $content->average_rating,
                'is_completed' => $progress?->is_completed ?? false,
                'last_watched_position' => $progress?->last_watched_position ?? 0,
                'completed_at' => $progress?->completed_at,
            ];
        });

        return [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'is_free' => $course->is_free,
                'price' => $course->price,
                'currency_code' => $course->currency_code,
                'cover_image' => $course->cover_image,
                'duration' => $course->duration,
                'average_rating' => $course->average_rating,
                'teacher' => $course->teacher->user->name ?? null,
                'category' => $course->category->name ?? null,
                'subject' => $course->subject->name ?? null,
                'completed' => $progress?->completed ?? false,
                'progress_percentage' => $progress?->progress_percentage ?? 0,
                'last_accessed_at' => $progress?->last_accessed_at,
            ],
            'contents' => $contents,
        ];
    }
}
