<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;

class SubscriptionProfileController extends ProfileController
{
    public function subscription()
    {
        // Imposta la chiave API di Stripe
        Stripe::setApiKey(config('services.stripe.secret'));

        $subscription = Subscription::where('user_id', Auth::id())->first();

        if (!$subscription) {
            return redirect()->route('profile.edit')->with('error', 'Abbonamento non trovato.');
        }

        $stripeSubscription = \Stripe\Subscription::retrieve($subscription->stripe_id);
        $subscriptionDetails = [
            'id' => $subscription->id,
            'stripe_id' => $subscription->stripe_id,
            'user_id' => $subscription->user_id,
            'user_name' => $subscription->user->name ?? 'N/A',
            'user_email' => $subscription->user->email ?? 'N/A',
            'status' => $stripeSubscription->status,
            'current_period_start' => date('Y-m-d H:i:s', $stripeSubscription->current_period_start),
            'current_period_end' => date('Y-m-d H:i:s', $stripeSubscription->current_period_end),
            'created_at' => date('Y-m-d H:i:s', $stripeSubscription->created),
            'items' => $stripeSubscription->items->data,
        ];

        $invoices = \Stripe\Invoice::all(['limit' => 100, 'customer' => $subscription->user->stripe_id ?? null, 'subscription' => $subscription->stripe_id]);
        $subscriptionDetails['invoices'] = $invoices->data;

        return view('profile.subscription', compact('subscriptionDetails', 'invoices'));
    }
}
