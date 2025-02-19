<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Subscription as CashierSubscription;
use Stripe\Price;

/**
 * Class Subscription
 *
 * This model represents a subscription for a user, including details such as
 * the subscription type, status, pricing information, and trial periods.
 *
 * @property int $user_id The ID of the user associated with the subscription.
 * @property string $type The type of subscription.
 * @property string $stripe_id The unique identifier for the subscription in Stripe.
 * @property string $stripe_status The current status of the subscription in Stripe.
 * @property string $stripe_price The price identifier for the subscription in Stripe.
 * @property int $quantity The quantity of the subscription.
 * @property \Illuminate\Support\Carbon|null $trial_ends_at The date and time when the trial ends.
 * @property \Illuminate\Support\Carbon|null $ends_at The date and time when the subscription ends.
 */
class Subscription extends CashierSubscription
{
    protected $fillable = [
        'user_id',
        'type',
        'stripe_id',
        'stripe_status',
        'stripe_price',
        'quantity',
        'trial_ends_at',
        'ends_at'
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /**
     * Get the user associated with the subscription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product associated with the subscription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'stripe_price', 'stripe_price_id');
    }

} 
