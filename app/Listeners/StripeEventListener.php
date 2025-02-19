<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Affiliate;
use App\Services\EmailService;
use App\Models\AffiliateEarning;
use App\Models\AffiliateCommission;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Events\WebhookReceived;
use Illuminate\Support\Facades\Config;

class StripeEventListener 
{

    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

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

        switch ($event->payload['type']) {
            case 'customer.subscription.created':
                self::handleCustomerSubscriptionCreated($event);
                break;
            case 'customer.subscription.updated':
                self::handleCustomerSubscriptionUpdated($event);
                break;
            case 'customer.subscription.deleted':
                self::handleCustomerSubscriptionDeleted($event);
                break;
            case 'customer.updated':
                self::handleCustomerUpdated($event);
                break;
            case 'customer.deleted':
                self::handleCustomerDeleted($event);
                break;
            case 'payment_method.automatically_updated':
                self::handlePaymentMethodAutomaticallyUpdated($event);
                break;
            case 'payment_method.card_automatically_updated':
                self::handlePaymentMethodCardAutomaticallyUpdated($event);
                break;
            case 'invoice.payment_action_required':
                self::handleInvoicePaymentActionRequired($event);
                break;
            case 'invoice.payment_succeeded':
                self::handlePaymentSucceeded($event);
                break;

            default:
                Log::warning('Unhandled event type', ['type' => $event->payload['type']]);
                break;
        }
    }

    private function handleCustomerSubscriptionCreated($event){
        Log::info('handleCustomerSubscriptionCreated');
        Log::info($event->payload);
    }

    private function handleCustomerSubscriptionUpdated($event){
        Log::info('handleCustomerSubscriptionUpdated');
        Log::info($event->payload);
    }   

    private function handleCustomerSubscriptionDeleted($event){
        Log::info('handleCustomerSubscriptionDeleted');
        Log::info($event->payload);

        try {
            //Stripe Datas
            $StripeCustomerId = $event->payload['data']['object']['customer'];
            $user = User::where('stripe_id', $StripeCustomerId)->first();
            
            if($user){
                // Ottieni la data di fine dell'abbonamento
                $endDate = \Carbon\Carbon::createFromTimestamp(
                    $event->payload['data']['object']['ended_at']
                )->format('d/m/Y');

                $this->emailService->send(
                    'subscription_cancelled',
                    [
                        'user_name' => $user->name,
                        'end_date' => $endDate
                    ],
                    $user->email
                );

                Log::info('Subscription cancellation email sent to: ' . $user->email);
            }

        } catch (\Exception $e) {
            Log::error('Error handling customer subscription deleted', ['error' => $e->getMessage()]);
        }
    }   

    private function handleCustomerUpdated($event){
        Log::info('handleCustomerUpdated');
        Log::info($event->payload);
    }      

    private function handleCustomerDeleted($event){
        Log::info('handleCustomerDeleted');
        Log::info($event->payload);
    }          

    private function handlePaymentMethodAutomaticallyUpdated($event){
        Log::info('handlePaymentMethodAutomaticallyUpdated');
        Log::info($event->payload);
    }      

    private function handlePaymentMethodCardAutomaticallyUpdated($event){
        Log::info('handlePaymentMethodCardAutomaticallyUpdated');
        Log::info($event->payload);
    }         

    private function handleInvoicePaymentActionRequired($event){
        Log::info('handleInvoicePaymentActionRequired');
        Log::info($event->payload);
    }            

    private function handleInvoicePaymentSucceeded($event){
        Log::info('handleInvoicePaymentSucceeded');
        Log::info($event->payload);
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

            $livelliCalcolati = Config::get('affiliate.levels', 3);
            
            $affiliateId = $userId;
            for($i = 1; $i <= $livelliCalcolati; $i++){
                $affiliate = Affiliate::where('user_id', $affiliateId)->first();
                
                if($affiliate){ 
                    $affiliateId = $affiliate->parent_id;
                    $affiliateCommissionPercentage = AffiliateCommission::where('level', $i)->first()->commission;
                    $commissioneAffiliate = number_format(($costoAbbonamentoSenzaIva * ($affiliateCommissionPercentage / 100)) / 100, 2); // Convertito in euro

                    if ($affiliateId !== null) {
                        $stripeInvoiceUrl = $event->payload['data']['object']['hosted_invoice_url'];
                        $existingEarning = AffiliateEarning::where('stripe_invoice_url', $stripeInvoiceUrl)
                            ->where('affiliate_id', $affiliateId)
                            ->where('user_id', $userId)
                            ->first();
                        
                        if ($existingEarning) {
                            Log::info('Il pagamento è già stato registrato per l\'invoice URL: ' . $stripeInvoiceUrl);
                        } else {
                            $affiliateEarning = new AffiliateEarning([
                                'affiliate_id' => $affiliateId,
                                'user_id' => $userId,
                                'stripe_invoice_url' => $stripeInvoiceUrl,
                                'taxable' => $costoAbbonamentoSenzaIvaEuro,
                                'net' => $costoAbbonamentoEuro,
                                'commission_percentage' => $affiliateCommissionPercentage,
                                'commission' => $commissioneAffiliate,
                            ]);
                            $affiliateEarning->save();
                        }
                    }

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

            $userBranchesCount = $user->branchesCount();
            if($userBranchesCount == 0){
                $branch = Branch::create([
                    'company_id' => $userId,
                    'name' => 'Default',
                    'default' => true,
                ]);
            } else {
                Log::info('No branch created. User ID: ' . $userId . ' already has ' . $userBranchesCount . ' branches.');
            }
            
            // Invio email di benvenuto
            $this->emailService->send(
                'payment_success',
                [
                    'user_name' => $user->name,
                    'order_id' => $product->id, // Assuming product ID is used as order ID
                    'product_name' => $product->name,
                    'amount' => $costoAbbonamentoEuro, // Assuming product price is available
                    'payment_date' => now()->toDateString(), // Current date as payment date
                ],
                $user->email // Added recipient email address
            );


        } catch (\Exception $e) {
            Log::error('Error processing Stripe event', ['message' => $e->getMessage(), 'event' => $event->payload]);
        }
    }

    
}

