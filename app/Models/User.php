<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getSubscription()
    {
        $subscriptionObject = $this->hasOne(Subscription::class)->first(); // Include gli items
        if($subscriptionObject) {
            $subscriptionObject->product = $this->hasOne(Subscription::class)->with('product')->first();
        }

        return $subscriptionObject;
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function subscriptionStatus()
    {
        if($this->hasRole('owner')) {
            return true;
        }

        if(!$this->subscription) {
            return false;
        }

        if($this->subscription->stripe_status === 'trialing') {
            return true;
        }else{
            return "active" == $this->subscription->stripe_status ? true : false;
        }
        
    }

    /**
     * Get the branches associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function branches()
    {
        return $this->hasMany(Branch::class, 'company_id', 'id');
    }

    public function maxBranches()
    {
        if ($this->hasRole('owner')) {
            return PHP_INT_MAX; // Unlimited branches for owner role
        }

        $subscription = $this->subscription;
        $subscriptionActive = $this->subscriptionStatus();
        
        if (!$subscription || !$subscriptionActive) {
            return 0;
        }

        $stripeProductId = $subscription->product->stripe_product_id;
        $limits = config("subscriptions.plans.$stripeProductId.max_branches", 0);

        return $limits;
    }

    public function branchesCount()
    {
        return $this->branches()->count();
    }

    public function canAddBranch() : bool
    {
        return (bool) ($this->maxBranches() > $this->branchesCount());
    }

    public function forms() : HasMany
    {
        return $this->hasMany(FormBuilder::class, 'user_id', 'id');
    }

    public function maxForms() : int
    {
        if ($this->hasRole('owner')) {
            return PHP_INT_MAX; // Unlimited branches for owner role
        }
        
        $subscription = $this->subscription;
        $subscriptionActive = $this->subscriptionStatus();
        
        if(!$subscription || !$subscriptionActive) {
            return 0;
        }

        $stripeProductId = $subscription->product->stripe_product_id;
        $limits = config("subscriptions.plans.$stripeProductId.max_forms", 0);

        return $limits;
    }

    public function formsCount() : int
    {
        return $this->forms()->count();
    }

    public function canAddForm() : bool
    {
        return (bool) ($this->maxForms() > $this->formsCount());
    }

    public function loginLogs()
    {
        return $this->hasMany(LoginLog::class, 'user_id', 'id');
    }

    public function lastLoginDate()
    {
        return $this->lastLogin()->created_at;
    }

}
