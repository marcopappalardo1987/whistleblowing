<?php

namespace App\Listeners;

use App\Models\Product;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Events\WebhookReceived;

class StripeEventListener 
{
    /**
     * Handle the event.
     */
    public function handle(WebhookReceived $event): void
    {
        /**
         * Lista dei webhook attivi
         * customer.subscription.created
         * customer.subscription.updated
         * customer.subscription.deleted
         * customer.updated
         * customer.deleted
         * payment_method.automatically_updated
         * payment_method.card_automatically_updated
         * invoice.payment_action_required
         * invoice.payment_succeeded
         */

        Log::info('Stripe Event Listener', ['event' => $event->payload]);

        switch ($event->payload['type']) {
            case 'customer.subscription.created':
                break;
            case 'customer.subscription.updated':
                break;
            case 'customer.subscription.deleted':
                break;
            case 'customer.updated':
                break;
            case 'customer.deleted':
                break;
            case 'payment_method.automatically_updated':
                break;
            case 'payment_method.card_automatically_updated':
                break;
            case 'invoice.payment_action_required':
                break;
            case 'invoice.payment_succeeded':

                self::handlePaymentSucceeded($event);
                break;

            default:
                Log::warning('Unhandled event type', ['type' => $event->payload['type']]);
                break;
        }
    }

    private function handlePaymentSucceeded($event)
    {
        try {
            //Stripe Datas
            $StripeCustomerId = $event->payload['data']['object']['customer'];
            $StripePriceId = $event->payload['data']['object']['lines']['data'][0]['price']['id'];
            $createdAt = $event->payload['data']['object']['created'];
            $interval = $event->payload['data']['object']['lines']['data'][0]['plan']['interval'];
            $intervalCount = $event->payload['data']['object']['lines']['data'][0]['plan']['interval_count'];
            
            $expiresAt = Carbon::createFromTimestamp($createdAt)->add($interval, $intervalCount);

            $user = User::where('stripe_id', $StripeCustomerId)->first();
            if (!$user) {
                Log::error('User not found for Stripe ID', ['stripe_id' => $StripeCustomerId]);
                return;
            }
            $userId = $user->id;

            $product = Product::where('stripe_price_id', $StripePriceId)->first();
            if (!$product) {
                Log::error('Product not found for Stripe Price ID', ['stripe_price_id' => $StripePriceId]);
                return;
            }
        } catch (\Exception $e) {
            Log::error('Error processing Stripe event', ['message' => $e->getMessage(), 'event' => $event->payload]);
        }
    }

    
}

