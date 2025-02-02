<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    protected $fillable = ['user_id', 'parent_id'];

    public static function createAffiliate($userId, $referralId = null)
    {
        self::create([
            'user_id' => $userId,
            'parent_id' => $referralId,
        ]);
    }

    public static function getAffiliatesByParentId($parentId, $perPage = 10)
    {
        return self::where('parent_id', $parentId)
            ->join('users', 'affiliates.user_id', '=', 'users.id')
            ->select('users.name', 'users.email', 'affiliates.created_at')
            ->paginate($perPage);
    }

    
    
}
