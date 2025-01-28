<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Payment;
use App\Models\UserCredits;

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

    public function credits()
    {
        return $this->hasMany(UserCredits::class);
    }

    /**
     * Get total active credits for user
     */
    public function getActiveCredits(): int
    {
        return UserCredits::getActiveCredits($this->id);
    }

    /**
     * Check if user has enough active credits
     */
    public function hasEnoughCredits(int $required): bool
    {
        return $this->getActiveCredits() >= $required;
    }

}
