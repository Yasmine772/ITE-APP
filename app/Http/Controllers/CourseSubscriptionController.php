<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\User;
use App\Models\CourseSubscription;
class CourseSubscriptionController extends Controller
{
    use ApiResponseTrait;

    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    // Web methods

    public function index()
    {
        $user = auth()->user();
        $subscriptions = $this->subscriptionService->getUserSubscriptions($user);

        return view('subscriptions.index', compact('subscriptions'));
    }

   public function subscribe(Request $request)
{
    $request->validate([
        'course_id' => 'required|exists:courses,id',
    ]);

    $user = auth()->user();

    try {
        $course = Course::findOrFail($request->course_id);

        if ($this->subscriptionService->isSubscribed($user, $course)) {
            return redirect()->back()->with('error', 'You are already subscribed to this course');
        }

        $this->subscriptionService->subscribe($user, $course);

        return redirect()->back()->with('success', 'Subscribed successfully');
    } catch (ModelNotFoundException $e) {
        return redirect()->back()->with('error', 'Course not found');
    }
}

    public function unsubscribe(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $user = auth()->user();

        try {
            $course = Course::findOrFail($request->course_id);

            $deleted = $this->subscriptionService->unsubscribe($user, $course);

            if (!$deleted) {
                return redirect()->back()->with('error', 'Subscription not found for this course');
            }

            return redirect()->back()->with('success', 'Unsubscribed successfully');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Course not found');
        }
    }

    public function markAsPaid(Request $request)
{
    $request->validate([
        'course_id' => 'required|exists:courses,id',
    ]);

    $user = auth()->user();

    try {
        $course = Course::findOrFail($request->course_id);

        $updated = $this->subscriptionService->markAsPaid($user, $course);

        if (!$updated) {
            return redirect()->back()->with('error', 'Failed to update payment status');
        }

        $subscription = $user->subscriptions()->where('course_id', $course->id)->first();

        return redirect()->back()->with('success', 'Payment status updated successfully')->with('subscription', $subscription);
    } catch (ModelNotFoundException $e) {
        return redirect()->back()->with('error', 'Course not found');
    }
}

    // API methods

    public function apiIndex()
    {
        $user = auth()->user();
        $subscriptions = $this->subscriptionService->getUserSubscriptions($user);

        return $this->successResponse(['subscriptions' => $subscriptions], 'Subscriptions retrieved successfully');
    }

   public function apiSubscribe(Request $request)
{
    $request->validate([
        'course_id' => 'required|exists:courses,id',
    ]);

    $user = auth()->user();

    try {
        $course = Course::findOrFail($request->course_id);

        if ($this->subscriptionService->isSubscribed($user, $course)) {
            return $this->errorResponse('You are already subscribed to this course', null, 409);
        }

        // لا نمرر is_paid بعد الآن
        $subscription = $this->subscriptionService->subscribe($user, $course);

        return $this->successResponse([
            'subscription' => $subscription
        ], 'Successfully subscribed to the course');
    } catch (ModelNotFoundException $e) {
        return $this->errorResponse('Course not found', null, 404);
    }
}


    public function apiUnsubscribe(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $user = auth()->user();

        try {
            $course = Course::findOrFail($request->course_id);

            $deleted = $this->subscriptionService->unsubscribe($user, $course);

            if (!$deleted) {
                return $this->errorResponse('Subscription not found for this course', null, 404);
            }

            return $this->successResponse(null, 'Successfully unsubscribed from the course');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Course not found', null, 404);
        }
    }

   public function apiMarkAsPaid(Request $request)
{
    $request->validate([
        'course_id' => 'required|exists:courses,id',
    ]);

    $user = auth()->user();

    try {
        $course = Course::findOrFail($request->course_id);

        $updated = $this->subscriptionService->markAsPaid($user, $course);

        if (!$updated) {
            return $this->errorResponse('Failed to update payment status', null, 400);
        }

        $subscription = CourseSubscription::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        return $this->successResponse([
            'subscription' => $subscription
        ], 'Payment status updated successfully');
    } catch (ModelNotFoundException $e) {
        return $this->errorResponse('Course not found', null, 404);
    }
}

}
