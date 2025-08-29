<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Purchase;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }
    
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $user = $request->user();
        $course = Course::findOrFail($request->course_id);

        $existing = Purchase::where('user_id', $user->id)
                            ->where('course_id', $course->id)
                            ->where('payment_status', 'paid')
                            ->first();

        if ($existing) {
            return response()->json(['message' => 'You have already purchased this course.'], 409);
        }

        if ($course->is_free) {
            return response()->json(['message' => 'This course is free.'], 400);
        }

        $amount = $course->price * 100; 
        $applicationFee = round($amount * 0.05); 
        $teacherAccountId = $course->teacher->user->teacher->stripe_account_id;

        if (!$teacherAccountId) {
            return response()->json(['message' => 'The teacher does not have a Stripe account.'], 500);
        }

        $paymentIntent = PaymentIntent::create([
            'amount' => $amount,
            'currency' => $course->currency_code,
            'application_fee_amount' => $applicationFee,
            'metadata' => [
                'user_id' => $user->id,
                'course_id' => $course->id,
            ],
            'automatic_payment_methods' => [
                'enabled' => true,
                'allow_redirects' => 'never', 
            ],
        ], [
            'stripe_account' => $teacherAccountId,
        ]);

        Purchase::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'amount_paid' => $course->price,
            'currency' => $course->currency_code,
            'payment_status' => 'pending',
            'stripe_payment_intent_id' => $paymentIntent->id,
        ]);

        return response()->json([
            'client_secret' => $paymentIntent->client_secret,
        ]);
    }

public function confirmPayment(Request $request)
{
    $request->validate([
        'payment_intent_id' => 'required|string',
        'payment_method_id' => 'required|string',
    ]);

    try {
        $purchase = Purchase::where('stripe_payment_intent_id', $request->payment_intent_id)->first();

        if (!$purchase) {
            return response()->json([
                'success' => false,
                'message' => 'Purchase record not found.',
            ], 404);
        }

        $teacherAccountId = $purchase->course->teacher->user->teacher->stripe_account_id;

        if (!$teacherAccountId) {
            return response()->json([
                'success' => false,
                'message' => 'The teachers Stripe account is not available.',
            ], 500);
        }

        $paymentIntent = PaymentIntent::retrieve(
            $request->payment_intent_id,
            ['stripe_account' => $teacherAccountId]
        );

        $confirmedIntent = $paymentIntent->confirm([
            'payment_method' => $request->payment_method_id,
        ]);

        if ($confirmedIntent->status === 'succeeded') {
            $purchase->payment_status = 'paid';

            \App\Models\CourseSubscription::updateOrCreate(
                [
                    'user_id' => $purchase->user_id,
                    'course_id' => $purchase->course_id,
                ],
                [
                    'status' => 'active',
                    'is_paid' => true,
                    'paid_at' => now(),
                ]
            );

        } elseif (in_array($confirmedIntent->status, ['processing', 'requires_capture'])) {
            $purchase->payment_status = 'processing';
        } else {
            $purchase->payment_status = 'failed';
        }

        $purchase->save();

        return response()->json([
            'success' => true,
            'payment_status' => $purchase->payment_status,
            'payment_intent' => $confirmedIntent,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 400);
    }
}

}
