<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;
use Stripe\Product as StripeProduct;
use Stripe\Price as StripePrice;
use Stripe\Stripe;

class ProductsController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    private function createStripeProduct($data)
    {
        try {
            // Crea il prodotto su Stripe
            $stripeProduct = StripeProduct::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? '',
                'active' => true,
            ]);

            // Crea il prezzo base se non ci sono varianti
            /*if (!$data['has_variants']) {*/
                $stripePrice = StripePrice::create([
                    'product' => $stripeProduct->id,
                    'unit_amount' => $data['price'] * 100,
                    'currency' => 'eur',
                    'recurring' => [
                        'interval' => $data['subscription_interval'],
                    ],
                ]);
                
                return [
                    'product_id' => $stripeProduct->id,
                    'price_id' => $stripePrice->id
                ];
            /*}

            return [
                'product_id' => $stripeProduct->id,
                'price_id' => null
            ];*/

        } catch (Exception $e) {
            Log::error('Errore nella creazione del prodotto su Stripe: ' . $e->getMessage());
            throw new Exception('Errore nella creazione del prodotto su Stripe: ' . $e->getMessage());
        }
    }

    /*private function createStripeVariantPrice($stripeProductId, $variant, $interval)
    {
        try {
            $stripePrice = StripePrice::create([
                'product' => $stripeProductId,
                'unit_amount' => $variant['price'] * 100,
                'currency' => 'eur',
                'recurring' => [
                    'interval' => $interval,
                ],
                'nickname' => $variant['name'],
            ]);

            return $stripePrice->id;

        } catch (Exception $e) {
            Log::error('Errore nella creazione della variante su Stripe: ' . $e->getMessage());
            throw new Exception('Errore nella creazione della variante su Stripe: ' . $e->getMessage());
        }
    }*/

    /**
     * Mostra il form per creare un nuovo prodotto
     */
    public function create()
    {
        return view('products.single.add');
    }

    /**
     * Salva un nuovo prodotto
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'type' => 'required|in:one_time,subscription',
                'subscription_interval' => 'required_if:type,subscription|nullable|in:day,week,month,year',
                'features' => 'nullable|array',
                'features.*.name' => 'string|max:255',
            ]);

            /*$validated['has_variants'] = !empty($validated['variants']);

            if (!$validated['has_variants']) {*/
                $validated['price'] = (int) ($validated['price']);
            /*}*/

            // Crea il prodotto su Stripe
            $stripeData = $this->createStripeProduct($validated);
            
            // Aggiungi gli ID di Stripe ai dati validati
            $validated['stripe_product_id'] = $stripeData['product_id'];
            $validated['stripe_price_id'] = $stripeData['price_id'];

            // Crea il prodotto nel database
            $product = Product::createProduct($validated);

            // Gestisci le caratteristiche
            if (isset($validated['features'])) {
                foreach ($validated['features'] as $feature) {
                    $product->features()->create(['name' => $feature['name']]);
                }
            }

            return redirect()
                ->route('products')
                ->with('success', 'Prodotto creato con successo');

        } catch (Exception $e) {
            Log::error('Errore nella creazione del prodotto: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Si è verificato un errore: ' . $e->getMessage());
        }
    }

    /**
     * Mostra la lista dei prodotti
     */
    public function index()
    {
        $products = new Product();
        $products = $products->getAllProducts();
        
        return view('products.index', compact('products'));
    }

    /**
     * Mostra un singolo prodotto
     */
    public function show(Product $product)
    {
        return view('products.single.show', compact('product'));
    }

    /**
     * Mostra il form per modificare un prodotto
     */
    public function edit(Product $product)
    {
        $product->load(/* 'variants',  */'features');
        return view('products.single.edit', compact('product'));
    }

    private function updateOrCreateStripeProduct($data, $product)
    {
        try {
            // Se il prodotto non esiste su Stripe, crealo
            if (!$product->stripe_product_id) {
                return $this->createStripeProduct($data);
            }

            // Aggiorna il prodotto esistente su Stripe
            $stripeProduct = StripeProduct::update($product->stripe_product_id, [
                'name' => $data['name'],
                'description' => $data['description'] ?? '',
                'active' => true,
            ]);


            // Se il prezzo è cambiato o non esiste, crea un nuovo prezzo
            if (!$product->stripe_price_id || $product->price !== $data['price'] || 
                $product->subscription_interval !== $data['subscription_interval']) {
                
                $stripePrice = StripePrice::create([
                    'product' => $stripeProduct->id,
                    'unit_amount' => $data['price'] * 100, // Converti in centesimi per Stripe
                    'currency' => 'eur',
                    'recurring' => [
                        'interval' => $data['subscription_interval'],
                    ],
                ]);

                // Aggiorna gli abbonamenti attivi con il nuovo prezzo
                if ($product->type === 'subscription') {
                    $activeSubscriptions = \App\Models\Subscription::where('stripe_price', $product->stripe_price_id)
                        ->where('stripe_status', 'active')
                        ->get();

                    foreach ($activeSubscriptions as $subscription) {
                        try {
                            // Recupera l'abbonamento Stripe
                            $stripeSubscription = \Stripe\Subscription::retrieve($subscription->stripe_id);
                            
                            // Aggiorna l'abbonamento con il nuovo prezzo
                            \Stripe\Subscription::update($subscription->stripe_id, [
                                'items' => [
                                    [
                                        'id' => $stripeSubscription->items->data[0]->id,
                                        'price' => $stripePrice->id,
                                    ],
                                ],
                                'proration_behavior' => 'always_invoice',
                            ]);

                            // Aggiorna il record dell'abbonamento nel database
                            $subscription->update([
                                'stripe_price' => $stripePrice->id
                            ]);

                            Log::info('Abbonamento aggiornato con successo', [
                                'subscription_id' => $subscription->id,
                                'new_price_id' => $stripePrice->id
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Errore nell\'aggiornamento dell\'abbonamento', [
                                'subscription_id' => $subscription->id,
                                'error' => $e->getMessage()
                            ]);
                        }
                    }
                }

                return [
                    'product_id' => $stripeProduct->id,
                    'price_id' => $stripePrice->id
                ];
            }

            return [
                'product_id' => $stripeProduct->id,
                'price_id' => $product->stripe_price_id
            ];

        } catch (Exception $e) {
            Log::error('Errore nell\'aggiornamento del prodotto su Stripe: ' . $e->getMessage());
            throw new Exception('Errore nell\'aggiornamento del prodotto su Stripe: ' . $e->getMessage());
        }
    }

    /**
     * Aggiorna un prodotto esistente
     */
    public function update(Request $request, Product $product)
    {   
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0|regex:/^\d*\.?\d{0,2}$/',
                'type' => 'required|in:one_time,subscription',
                'subscription_interval' => 'required_if:type,subscription|nullable|in:day,week,month,year',
                'features' => 'nullable|array',
                'features.*.name' => 'string|max:255',
            ]);

            // Aggiorna o crea il prodotto su Stripe
            $stripeData = $this->updateOrCreateStripeProduct($validated, $product);
            
            // Aggiorna gli ID di Stripe nei dati validati
            $validated['stripe_product_id'] = $stripeData['product_id'];
            $validated['stripe_price_id'] = $stripeData['price_id'];

            // Aggiorna il prodotto nel database
            $product->updateProduct($validated);

            // Gestisci le caratteristiche
            $product->features()->delete();
            if (isset($validated['features'])) {
                foreach ($validated['features'] as $feature) {
                    $product->features()->create(['name' => $feature['name']]);
                }
            }

            return redirect()
                ->route('products')
                ->with('success', 'Prodotto aggiornato con successo');

        } catch (Exception $e) {
            Log::error('Errore nell\'aggiornamento del prodotto: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Si è verificato un errore: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un prodotto
     */
    public function destroy(Product $product)
    {
        try {
            // Elimina solo dal database locale
            $product->deleteProduct();
            
            return redirect()
                ->route('products')
                ->with('success', 'Prodotto eliminato con successo');

        } catch (Exception $e) {
            Log::error('Errore nell\'eliminazione del prodotto: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Si è verificato un errore durante l\'eliminazione del prodotto: ' . $e->getMessage());
        }
    }
}
