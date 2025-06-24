<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CourseSubscription;

class EnsureActiveSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $courseId = $request->route('courseId') ?? $request->route('content')?->course_id;

        if (!$courseId || !$user) {
            return response()->json(['message' => 'Unauthorized or invalid course'], 403);
        }

        $subscription = CourseSubscription::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->where('status', 'active')
            ->first();

        if (!$subscription) {
            return response()->json(['message' => 'You are not subscribed to this course or your subscription is inactive'], 403);
        }

        return $next($request);
    }
}

