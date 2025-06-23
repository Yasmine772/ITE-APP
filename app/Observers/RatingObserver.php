<?php

namespace App\Observers;

use App\Models\Rating;

class RatingObserver
{
    public function saved(Rating $rating): void
    {
        $content = $rating->content;

        if ($content) {
            // تحديث متوسط التقييم لمحتوى الكورس
            $average = round($content->ratings()->avg('rating') ?? 0, 1);
            $content->update(['average_rating' => $average]);

            // تحديث متوسط التقييم للكورس نفسه
            $course = $content->course;

            if ($course) {
                $courseAverage = round(
                    $course->contents()
                        ->whereNotNull('average_rating')
                        ->avg('average_rating') ?? 0,
                    1
                );
                $course->update(['average_rating' => $courseAverage]);
            }
        }
    }

    public function deleted(Rating $rating): void
    {
        // عند حذف التقييم، أعد احتساب المعدلات أيضًا
        $this->saved($rating);
    }
}
