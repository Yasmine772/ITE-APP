<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Purchase;
use App\Models\CourseSubscription;
use Stripe\Webhook;
use Stripe\Stripe;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $endpointSecret = config('services.stripe.webhook_secret');

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $event = null;

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $event = Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch(\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object; 
                $this->handleSuccessfulPayment($paymentIntent);
                break;

            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                $this->handleFailedPayment($paymentIntent);
                break;


            default:
                Log::info('Unhandled Stripe event type: ' . $event->type);
        }

        return response('Webhook handled', 200);
    }

    protected function handleSuccessfulPayment($paymentIntent)
    {
        $purchase = Purchase::where('stripe_payment_intent_id', $paymentIntent->id)->first();

        if (!$purchase) {
            Log::error("Purchase not found for PaymentIntent ID: " . $paymentIntent->id);
            return;
        }

        $purchase->payment_status = 'paid';
        $purchase->save();

        $subscription = CourseSubscription::where('user_id', $purchase->user_id)
            ->where('course_id', $purchase->course_id)
            ->first();

        if ($subscription) {
            $subscription->status = 'active';
            $subscription->is_paid = true;
            $subscription->paid_at = now();
            $subscription->save();
        } else {
            Log::warning("Subscription not found for user {$purchase->user_id} and course {$purchase->course_id}");
        }
    }

    protected function handleFailedPayment($paymentIntent)
    {
        $purchase = Purchase::where('stripe_payment_intent_id', $paymentIntent->id)->first();

        if ($purchase) {
            $purchase->payment_status = 'failed';
            $purchase->save();
        }
    }
}
