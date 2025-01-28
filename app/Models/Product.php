<?php

namespace App\Models;

use Exception;
// use App\Models\ProductVariant;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

/**
 * Modello per la gestione dei prodotti e abbonamenti.
 * Gestisce prodotti one-time e in abbonamento.
 * Integra la sincronizzazione automatica con Stripe.
 */
class Product extends Model
{
    /**
     * Gli attributi che possono essere assegnati in massa.
     * 
     * @var array
     * 
     * - name: Nome del prodotto
     * - description: Descrizione del prodotto
     * - price: Prezzo in centesimi
     * - stripe_product_id: ID del prodotto su Stripe
     * - stripe_price_id: ID del prezzo su Stripe
     * - type: Tipo di prodotto ('one_time' o 'subscription')
     * - subscription_duration: Durata dell'abbonamento in giorni
     * - subscription_interval: Intervallo di ricorrenza ('day', 'week', 'month', 'year')
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'stripe_product_id',
        'stripe_price_id',
        'type',
        //'has_variants',
        'subscription_duration',
        'subscription_interval',
        'features',
        'credits'
    ];

    /**
     * Conversione automatica dei tipi di attributi.
     * 
     * @var array
     */
    protected $casts = [
        //'has_variants' => 'boolean',
        'subscription_duration' => 'integer',
        'credits' => 'integer',
    ];

    /**
     * Relazione con le varianti del prodotto.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    /*public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }*/

    /**
     * Crea un nuovo prodotto nel database.
     * 
     * @param array $data Dati del prodotto
     * @return Product
     * @throws Exception Se la creazione fallisce
     */
    public static function createProduct(array $data)
    {
        try {
            // Crea il prodotto nel database senza interagire con Stripe
            $product = self::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'type' => $data['type'],
                //'has_variants' => $data['has_variants'],
                'subscription_duration' => $data['subscription_duration'] ?? null,
                'subscription_interval' => $data['subscription_interval'] ?? null,
                'stripe_product_id' => $data['stripe_product_id'] ?? null,
                'stripe_price_id' => $data['stripe_price_id'] ?? null,
                'credits' => $data['credits'] ?? null,
            ]);

            /* // Crea le varianti se presenti
            if (isset($data['variants']) && !empty($data['variants'])) {
                foreach ($data['variants'] as $variant) {
                    $product->variants()->create([
                        'name' => $variant['name'],
                        'price' => $variant['price'],
                        'stripe_price_id' => null,
                        'credits' => $variant['credits'] ?? null,
                    ]);
                }
            }*/

            return $product;

        } catch (Exception $e) {
            Log::error('Errore nella creazione del prodotto: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Aggiorna un prodotto esistente nel database.
     * 
     * @param array $data Dati da aggiornare
     * @return Product
     * @throws Exception Se l'aggiornamento fallisce
     */
    public function updateProduct(array $data)
    {
        try {
            // Converti il prezzo in float e assicurati che abbia 2 decimali
            $price = floatval(str_replace(',', '.', $data['price']));

            // Controlla se il prezzo è cambiato e aggiorna stripe_price_id
            $priceChanged = $this->price != $price;
            $stripePriceId = $priceChanged ? $data['stripe_price_id'] : $this->stripe_price_id;
            $priceChanged = $this->price != $price;
            
            // 1. Aggiorna il prodotto nel database
            $this->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'price' => $price,
                'subscription_duration' => $data['subscription_duration'] ?? $this->subscription_duration,
                'subscription_interval' => $data['subscription_interval'] ?? $this->subscription_interval,
                'stripe_product_id' => $data['stripe_product_id'] ?? $this->stripe_product_id,
                'stripe_price_id' => $stripePriceId,
                'credits' => $data['credits'] ?? $this->credits,
            ]);

            /* // 2. Gestisci le varianti
            if (isset($data['variants'])) {
                // Rimuovi le varianti non presenti nell'aggiornamento
                $currentVariantIds = collect($data['variants'])->pluck('id')->filter();
                $this->variants()->whereNotIn('id', $currentVariantIds)->delete();

                foreach ($data['variants'] as $variant) {
                    if (isset($variant['id'])) {
                        // Aggiorna variante esistente
                        $existingVariant = $this->variants()->find($variant['id']);
                        if ($existingVariant) {
                            // Aggiorna stripe_price_id solo se il prezzo è cambiato
                            $variantPriceChanged = $existingVariant->price != $variant['price'];
                            $variant['stripe_price_id'] = $variantPriceChanged ? 
                                $variant['stripe_price_id'] : 
                                $existingVariant->stripe_price_id;
                                
                            $existingVariant->update($variant);
                        }
                    } else {
                        // Crea nuova variante
                        $this->variants()->create([
                            'name' => $variant['name'],
                            'price' => $variant['price'],
                            'stripe_price_id' => $variant['stripe_price_id'] ?? null,
                            'credits' => $variant['credits'] ?? null,
                        ]);
                    }
                }
            }*/

            return $this;

        } catch (Exception $e) {
            Log::error('Errore nell\'aggiornamento del prodotto: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Elimina un prodotto dal database.
     * 
     * @return bool
     * @throws Exception Se l'eliminazione fallisce
     */
    public function deleteProduct()
    {
        try {
            // Elimina il prodotto dal database
            return $this->delete();

        } catch (Exception $e) {
            Log::error('Errore nell\'eliminazione del prodotto: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Ottiene il prezzo formattato con valuta.
     * 
     * @return string
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2, ',', '.') . ' €';
    }

    public function getAllProducts()
    {
        return self::all();
    }

    public function features()
    {
        return $this->hasMany(Feature::class);
    }

    /*public function getHasVariantsAttribute()
    {
        return $this->variants()->exists();
    }*/
}
