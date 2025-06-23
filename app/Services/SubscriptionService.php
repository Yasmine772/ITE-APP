<?php

namespace App\Services;

use App\Models\Course;
use App\Models\User;
use App\Models\CourseSubscription;
use Illuminate\Support\Carbon;

class SubscriptionService
{
public function subscribe(User $user, Course $course): CourseSubscription
{
    $isFree = $course->is_free || $course->price == 0;

    return CourseSubscription::updateOrCreate(
        [
            'user_id' => $user->id,
            'course_id' => $course->id,
        ],
        [
            'status' => $isFree ? 'active' : 'pending',
            'is_paid' => $isFree,
            'paid_at' => $isFree ? Carbon::now() : null,
        ]
    );
}


    public function unsubscribe(User $user, Course $course): bool
    {
        return CourseSubscription::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->delete();
    }

    public function isSubscribed(User $user, Course $course): bool
    {
        return CourseSubscription::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();
    }

    public function getUserSubscriptions(User $user)
    {
        return $user->subscriptions()->with('course')->get();
    }

public function markAsPaid(User $user, Course $course): bool
{
    if ($course->is_free || $course->price == 0) {
        return false; 
    }

    return CourseSubscription::where('user_id', $user->id)
        ->where('course_id', $course->id)
        ->update([
            'is_paid' => true,
            'paid_at' => Carbon::now(),
            'status' => 'active',
        ]);
}

}
