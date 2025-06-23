<?php

// namespace App\Services;

// use App\Models\Rating;

// class RatingService
// {
//     public function rateContent(int $userId, int $contentId, int $ratingValue): Rating
//     {
//         $rating = Rating::where('user_id', $userId)
//                         ->where('course_content_id', $contentId)
//                         ->first();

//         if ($rating) {
//             $rating->update(['rating' => $ratingValue]);
//         } else {
//             $rating = Rating::create([
//                 'user_id' => $userId,
//                 'course_content_id' => $contentId,
//                 'rating' => $ratingValue,
//             ]);
//         }

//         return $rating;
//     }
// }

namespace App\Services;

use App\Models\Rating;

class RatingService
{
    public function rateContent(int $userId, int $contentId, int $ratingValue): Rating
    {
        $rating = Rating::where('user_id', $userId)
                        ->where('course_content_id', $contentId)
                        ->first();

        if ($rating) {
            $rating->update(['rating' => $ratingValue]);
        } else {
            $rating = Rating::create([
                'user_id' => $userId,
                'course_content_id' => $contentId,
                'rating' => $ratingValue,
            ]);
        }

        $content = $rating->content;
        $contentAvg = $content->ratings()->avg('rating');
        $content->update([
            'average_rating' => round($contentAvg, 1)
        ]);

        $course = $content->course;
        $courseAvg = $course->contents()
            ->whereNotNull('average_rating')
            ->avg('average_rating');

        $course->update([
            'average_rating' => round($courseAvg ?? 0.0, 1)
        ]);

        return $rating;
    }
}
