<?php

namespace App\Observers;

use App\Models\CourseContent;

class CourseContentObserver
{
    /**
     * Handle the CourseContent "created" event.
     */
    public function created(CourseContent $courseContent): void
    {
        //
    }

    /**
     * Handle the CourseContent "updated" event.
     */
  
    public function updated(CourseContent $content)
    {
        if ($content->isDirty('average_rating')) {
            $course = $content->course;

            $average = $course->contents()
                ->whereNotNull('average_rating')
                ->avg('average_rating');

            $course->update([
                'average_rating' => round($average ?? 0.0, 1)
            ]);
        }
    }
    /**
     * Handle the CourseContent "deleted" event.
     */
    public function deleted(CourseContent $courseContent): void
    {
        //
    }

    /**
     * Handle the CourseContent "restored" event.
     */
    public function restored(CourseContent $courseContent): void
    {
        //
    }

    /**
     * Handle the CourseContent "force deleted" event.
     */
    public function forceDeleted(CourseContent $courseContent): void
    {
        //
    }
}
