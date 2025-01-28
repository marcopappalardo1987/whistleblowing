<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class UserCredits extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_credits';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'credits',
        'expires_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'credits' => 'integer'
    ];

    /**
     * Get the user that owns the credits.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if credits are expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Get active credits for a user
     */
    public static function getActiveCredits(int $userId): int
    {
        return self::where('user_id', $userId)
            ->where('expires_at', '>', Carbon::now())
            ->first()
            ->credits ?? 0;
    }

    /**
     * Add or update credits for a user
     */
    public static function addCredits(int $userId, int $credits, Carbon $expiresAt): self
    {
        $userCredits = self::firstOrCreate(
            ['user_id' => $userId],
            [
                'credits' => $credits,
                'expires_at' => $expiresAt
            ]
        );

        return $userCredits;
    }

    /**
     * Use credits for a user
     */
    public static function useCredits(int $userId, int $creditsToUse): bool
    {
        $userCredit = self::where('user_id', $userId)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$userCredit || $userCredit->credits < $creditsToUse) {
            return false;
        }

        $userCredit->credits -= $creditsToUse;
        $userCredit->save();

        if ($userCredit->credits === 0) {
            $userCredit->delete();
        }

        return true;
    }


    /**
     * Check if user has enough credits
     */
    public static function hasEnoughCredits(int $userId, int $requiredCredits): bool
    {
        $activeCredits = self::getActiveCredits($userId);
        return $activeCredits >= $requiredCredits;
    }

    /**
     * Get expiring credits for a user
     */
    public static function getExpiringCredits(int $userId, int $daysThreshold = 7)
    {
        $thresholdDate = Carbon::now()->addDays($daysThreshold);
        
        return self::where('user_id', $userId)
            ->where('credits', '>', 0)
            ->where('expires_at', '<=', $thresholdDate)
            ->where('expires_at', '>', Carbon::now())
            ->first();
    }
}
