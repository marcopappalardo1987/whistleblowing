<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Exception;
use Stripe\Exception\CardException;
use Stripe\Customer;
use Stripe\Stripe;

/**
 * Controller per la gestione degli abbonamenti
 */
class SubscriptionController extends Controller
{
    /**
     * Costruttore del controller
     * Imposta la chiave API di Stripe
     */
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Trova o crea un cliente Stripe per l'utente
     *
     * @param User $user L'utente per cui cercare/creare il cliente Stripe
     * @return Customer Il cliente Stripe
     * @throws Exception Se si verifica un errore nella sincronizzazione con Stripe
     */
    private function findOrCreateStripeCustomer($user)
    {
        try {
            // Se l'utente ha già uno stripe_id, lo usiamo
            if ($user->stripe_id) {
                return Customer::retrieve($user->stripe_id);
            }

            // Cerca il cliente su Stripe tramite email
            $customers = Customer::all([
                'email' => $user->email,
                'limit' => 1
            ]);

            if (!empty($customers->data)) {
                $stripeCustomer = $customers->data[0];
                
                // Aggiorna l'utente con i dati Stripe
                $user->stripe_id = $stripeCustomer->id;
                
                // Se il cliente ha un metodo di pagamento predefinito
                if ($stripeCustomer->default_source || $stripeCustomer->invoice_settings->default_payment_method) {
                    $paymentMethod = $user->defaultPaymentMethod();
                    if ($paymentMethod) {
                        $user->pm_type = $paymentMethod->card->brand;
                        $user->pm_last_four = $paymentMethod->card->last4;
                    }
                }
                
                $user->save();
                
                return $stripeCustomer;
            }

            // Se non esiste, crea un nuovo cliente
            return $user->createAsStripeCustomer();

        } catch (Exception $e) {
            throw new Exception('Errore nella sincronizzazione con Stripe: ' . $e->getMessage());
        }
    }

    /**
     * Mostra il form di checkout per l'abbonamento
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showCheckoutForm(Request $request)
    {
        // Ottieni il prodotto selezionato
        $product = Product::findOrFail($request->product_id);
        
        // Verifica che sia un abbonamento
        if ($product->type !== 'subscription') {
            return redirect()->back()->with('error', 'Questo prodotto non è un abbonamento');
        }

        $user = Auth::user();

        try {
            // Trova o crea il cliente Stripe
            $stripeCustomer = $this->findOrCreateStripeCustomer($user);

            return view('frontend.checkout', [
                'product' => $product,
                'intent' => $user->createSetupIntent(),
                'stripeKey' => config('services.stripe.key'),
                'hasDefaultPaymentMethod' => $user->hasDefaultPaymentMethod(),
                'defaultPaymentMethod' => $user->hasDefaultPaymentMethod() ? $user->defaultPaymentMethod() : null,
                'stripeCustomer' => $stripeCustomer
            ]);

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Errore nella configurazione del pagamento: ' . $e->getMessage());
        }
    }

    /**
     * Processa il checkout dell'abbonamento
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processCheckout(Request $request)
    {
        $request->validate([
            'payment_method' => 'required',
            'product_id' => 'required|exists:products,id'
        ]);
        
        $user = Auth::user();
        $product = Product::findOrFail($request->product_id);
        $stripePriceId = $product->stripe_price_id;

        try {
            // Verifica se l'utente ha già un abbonamento attivo
            $activeSubscription = $user->subscriptions()->where('stripe_status', 'active')->first();
            if ($activeSubscription) {
                return redirect()->back()->with('error', 'Hai già un abbonamento attivo.');
            }

            // Trova o crea il cliente Stripe
            $stripeCustomer = $this->findOrCreateStripeCustomer($user);

            // Gestione del metodo di pagamento
            try {
                if ($user->hasDefaultPaymentMethod()) {
                    // Aggiorna il metodo di pagamento se è stato fornito uno nuovo
                    if ($request->payment_method !== $user->defaultPaymentMethod()->id) {
                        $user->updateDefaultPaymentMethod($request->payment_method);
                        
                        // Aggiorna i dettagli della carta nel database
                        $paymentMethod = $user->defaultPaymentMethod();
                        $user->pm_type = $paymentMethod->card->brand;
                        $user->pm_last_four = $paymentMethod->card->last4;
                        $user->save();
                    }
                } else {
                    // Imposta il nuovo metodo di pagamento
                    $user->addPaymentMethod($request->payment_method);
                    $user->updateDefaultPaymentMethod($request->payment_method);
                    
                    // Salva i dettagli della carta
                    $paymentMethod = $user->defaultPaymentMethod();
                    $user->pm_type = $paymentMethod->card->brand;
                    $user->pm_last_four = $paymentMethod->card->last4;
                    $user->save();
                }

            // Crea l'abbonamento
            $subscription = $user->newSubscription('default', $stripePriceId)
                ->trialDays(1)
                ->create($request->payment_method);
            
            return redirect()
                ->route('dashboard')
                ->with('success', 'Abbonamento attivato con successo!');

            } catch (CardException $e) {
                return redirect()
                    ->route('checkout', ['product_id' => $request->product_id])
                    ->withInput()
                    ->with('error', 'Errore con la carta: ' . $e->getMessage());
            }

        } catch (IncompletePayment $exception) {
            return redirect()->route('cashier.payment', [
                $exception->payment->id,
                'redirect' => route('dashboard')
            ]);
        } catch (Exception $e) {
            return redirect()
                ->route('checkout', ['product_id' => $request->product_id])
                ->withInput()
                ->with('error', 'Si è verificato un errore: ' . $e->getMessage());
        }
    }

    /**
     * Mostra tutti gli abbonamenti di tutti gli utenti con filtri
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showAllSubscriptionsOfAllUsers(Request $request)
    {
        // Ottieni i parametri di filtro
        $perPage = $request->input('per_page', 10);
        $userEmail = $request->input('user_email');
        $userName = $request->input('user_name');
        $status = $request->input('status', 'active'); // Default a 'active'
        $subscriptionName = $request->input('subscription_name');
        
        // Query base con eager loading
        $query = \App\Models\Subscription::with('user');

        // Applica i filtri
        if ($userEmail) {
            $query->whereHas('user', function($q) use ($userEmail) {
                $q->where('email', 'like', "%{$userEmail}%");
            });
        }

        if ($userName) {
            $query->whereHas('user', function($q) use ($userName) {
                $q->where('name', 'like', "%{$userName}%");
            });
        }

        if ($status !== 'all') {
            $query->where('stripe_status', $status);
        }

        // Recupera gli abbonamenti paginati
        $subscriptions = $query->orderByRaw('CASE WHEN ends_at IS NULL THEN 1 ELSE 0 END')
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage);
        
        // Array delle opzioni per elementi per pagina e stati
        $perPageOptions = [1, 5, 10, 25, 50, 100, 200, 500, 1000];
        $statusOptions = [
            'all' => 'Tutti',
            'active' => 'Attivo',
            'trialing' => 'In prova',
            'past_due' => 'Scaduto',
            'canceled' => 'Cancellato',
            'unpaid' => 'Non pagato',
            'incomplete' => 'Incompleto',
            'incomplete_expired' => 'Scaduto incompleto'
        ];

        // Prepara i dati per la vista
        $subscriptionData = collect();
        $subscriptionNames = collect(); // Per raccogliere i nomi degli abbonamenti
        
        foreach ($subscriptions as $subscription) {
            try {
                $stripePrice = \Stripe\Price::retrieve($subscription->stripe_price);
                $amount = $stripePrice->unit_amount / 100;
                $stripeProduct = \Stripe\Product::retrieve($stripePrice->product);
                
                // Filtra per nome abbonamento se specificato
                if ($subscriptionName && !str_contains(strtolower($stripeProduct->name), strtolower($subscriptionName))) {
                    continue;
                }
                
                $subscriptionNames->push($stripeProduct->name);
                
                $subscriptionData->push([
                    'id' => $subscription->id,
                    'user_id' => $subscription->user_id,
                    'stripe_id' => $subscription->stripe_id,
                    'user_name' => $subscription->user->name,
                    'user_email' => $subscription->user->email,
                    'subscription_name' => $stripeProduct->name,
                    'stripe_status' => $subscription->stripe_status,
                    'stripe_price' => $subscription->stripe_price,
                    'amount' => number_format($amount, 2, ',', '.') . ' ' . config('cashier.currency_symbol', '€'),
                    'created_at' => $subscription->created_at,
                    'updated_at' => $subscription->updated_at,
                    'ends_at' => $subscription->ends_at
                ]);
            } catch (\Exception $e) {
                \Log::error('Errore nel recupero dati Stripe', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return view('subscriptions.all', [
            'subscriptions' => $subscriptionData,
            'listContent' => $subscriptions,
            'perPage' => $perPage,
            'perPageOptions' => $perPageOptions,
            'statusOptions' => $statusOptions,
            'currentStatus' => $status,
            'currentEmail' => $userEmail,
            'currentName' => $userName,
            'currentSubscriptionName' => $subscriptionName,
            'subscriptionNames' => $subscriptionNames->unique()->sort()->values()
        ]);
    }

    public function showEditForm(Request $request)
    {
        $subscription = Subscription::findOrFail($request->id);
        $subscriptionData = $subscription;
        $subscriptionData['user'] = $subscription->user;
        $subscriptionData['product'] = $subscription->product;
        return view('subscriptions.edit', compact('subscriptionData'));
    }

    public function showSubscriptionByStripeId($id)
    {
        $subscription = Subscription::where('id', $id)->firstOrFail();
        $stripeSubscription = \Stripe\Subscription::retrieve($subscription->stripe_id);
        $subscriptionDetails = [
            'id' => $subscription->id,
            'stripe_id' => $subscription->stripe_id,
            'user_id' => $subscription->user_id,
            'user_name' => $subscription->user->name,
            'user_email' => $subscription->user->email,
            'status' => $stripeSubscription->status,
            'current_period_start' => date('Y-m-d H:i:s', $stripeSubscription->current_period_start),
            'current_period_end' => date('Y-m-d H:i:s', $stripeSubscription->current_period_end),
            'created_at' => date('Y-m-d H:i:s', $stripeSubscription->created),
            'items' => $stripeSubscription->items->data,
        ];

        $invoices = \Stripe\Invoice::all(['limit' => 100, 'customer' => $subscription->user->stripe_id, 'subscription' => $subscription->stripe_id]);
        $subscriptionDetails['invoices'] = $invoices->data;

        return view('subscriptions.view', compact('subscriptionDetails', 'invoices'));
    }

    public function updateSubscription(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|string',
            'new_plan' => 'nullable|string',
            'payment_method' => 'nullable|string',
        ]);

        $subscription = Subscription::findOrFail($id);
        $user = $subscription->user;

       /*  dd($user->subscription()->first()); */

        try {
            switch ($request->action) {
                case 'cancel':
                    if ($request->cancelation_option === 'immediate') {
                        $user->subscription()->first()->cancelNow();/* 
                        $subscription->cancelNow(); */
                    } else {
                        $user->subscription()->first()->cancel();/* 
                        $subscription->cancel();
                    }
                    return redirect()->back()->with('success', 'Abbonamento annullato con successo.');

                case 'resume':
                    $user->subscription()->resume();
                    return redirect()->back()->with('success', 'Abbonamento ripreso con successo.');

                case 'swap':
                    if ($request->new_plan) {
                        /* $user->subscription($subscription->name)->swap($request->new_plan); */
                        return redirect()->back()->with('success', 'Piano cambiato con successo.');
                    }
                    return redirect()->back()->with('error', 'ID del nuovo piano richiesto.');

                case 'update_payment':
                    if ($request->payment_method) {
                        /* $user->updateDefaultPaymentMethod($request->payment_method); */
                        return redirect()->back()->with('success', 'Metodo di pagamento aggiornato con successo.');
                    }
                    return redirect()->back()->with('error', 'ID del metodo di pagamento richiesto.');

                default:
                    return redirect()->back()->with('error', 'Azione non valida.');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Errore: ' . $e->getMessage());
        }
    }
}
