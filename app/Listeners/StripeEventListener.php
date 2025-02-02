<?php

namespace App\Listeners;

use App\Models\Affiliate;
use App\Models\AffiliateCommission;
use App\Models\AffiliateEarning;
use App\Models\Product;
use App\Models\User;
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
            
            $userEmail = $event->payload['data']['object']['customer_email'];
            $user = User::where('email', $userEmail)->first();
            $userId = $user->id;
            $costoAbbonamento = $event->payload['data']['object']['amount_paid'];
            $ivaPercentuale = 22; // Percentuale IVA
            $costoAbbonamentoSenzaIva = $costoAbbonamento / (1 + ($ivaPercentuale / 100));
            $costoAbbonamentoSenzaIvaEuro = $costoAbbonamentoSenzaIva / 100; // Convertito in euro
            $costoAbbonamentoEuro = $costoAbbonamento / 100;

            $livelliCalcolati = 3;

            Log::info('Costo Imponibile: ' . $costoAbbonamentoSenzaIva);
            Log::info('Costo Netto: ' . $costoAbbonamento);
            Log::info('Totale in Euro: ' . $costoAbbonamentoEuro);

            $affiliateId = $userId;
            for($i = 1; $i <= $livelliCalcolati; $i++){
                $affiliate = Affiliate::where('user_id', $affiliateId)->first();
                if($affiliate){ 
                    $affiliateId = $affiliate->parent_id;
                    $affiliateCommissionPercentage = AffiliateCommission::where('level', $i)->first()->commission;
                    $commissioneAffiliate = number_format(($costoAbbonamentoSenzaIva * ($affiliateCommissionPercentage / 100)) / 100, 2); // Convertito in euro
                    
                    $affiliateEarning = new AffiliateEarning([
                        'affiliate_id' => $affiliateId,
                        'user_id' => $userId,
                        'stripe_invoice_url' => $event->payload['data']['object']['hosted_invoice_url'],
                        'taxable' => $costoAbbonamentoSenzaIvaEuro,
                        'net' => $costoAbbonamentoEuro,
                        'commission_percentage' => $affiliateCommissionPercentage,
                        'commission' => $commissioneAffiliate,
                    ]);
                    $affiliateEarning->save();

                    Log::info('Affiliate' . $i . ' Id: ' . $affiliateId);
                    Log::info('AffiliateCommissionPercentage' . $i . ': ' . number_format($affiliateCommissionPercentage, 2));
                    Log::info('Commissione Affiliate ' . $i . ': ' . $commissioneAffiliate);
                } else {
                    break; // Esci dal ciclo se non ci sono più affiliati
                }
            }
            
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

