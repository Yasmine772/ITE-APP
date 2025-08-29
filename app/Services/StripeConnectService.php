<?php

namespace App\Services;

use App\Models\User;
use Stripe\Stripe;
use Stripe\Account;
use Stripe\AccountLink;
use App\Models\Teacher;
class StripeConnectService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    
    public function createConnectedAccount(User $user): string
    {
        $teacher = $user->teacher;

        if (!$teacher) {
            throw new \Exception("This user does not have a teacher profile.");
        }

        $account = Account::create([
            'type' => 'express',
            'country' => 'US', 
            'email' => $user->email,
            'capabilities' => [
                'card_payments' => ['requested' => true],
                'transfers' => ['requested' => true],
            ],
        ]);

        $teacher->update([
            'stripe_account_id' => $account->id,
        ]);

        return $account->id;
    }

    
    public function generateAccountLink(string $accountId): string
    {
        $accountLink = AccountLink::create([
            'account' => $accountId,
            'refresh_url' => route('stripe.connect.refresh'),
            'return_url' => route('stripe.connect.done'),
            'type' => 'account_onboarding',
        ]);

        return $accountLink->url;
    }

    
    public function onboardTeacher(User $user): string
    {
        $teacher = $user->teacher;

        if (!$teacher) {
            throw new \Exception("This user does not have a teacher profile.");
        }

        $accountId = $teacher->stripe_account_id;

        if (!$accountId) {
            $accountId = $this->createConnectedAccount($user);
        }

        return $this->generateAccountLink($accountId);
    }


    public function getAccountStatus(string $accountId)
    {
        return Account::retrieve($accountId);
    }
    public function sendPayout(Teacher $teacher, int $amountInCents)
    {
        \Stripe\Transfer::create([
            'amount' => $amountInCents,
            'currency' => 'usd',
            'destination' => $teacher->stripe_account_id,
            'description' => 'Monthly payout',
        ]);
    }
}
