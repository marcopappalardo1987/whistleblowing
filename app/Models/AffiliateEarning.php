<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateEarning extends Model
{
    protected $fillable = [
        'affiliate_id',
        'user_id',
        'stripe_invoice_url',
        'taxable',
        'net',
        'commission_percentage',
        'commission',
    ];

    public function affiliate()
    {
        return $this->belongsTo(User::class, 'affiliate_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function createEarning(array $data)
    {
        return self::create($data);
    }

    public function getEarning($id)
    {
        return self::find($id);
    }

    public function updateEarning($id, array $data)
    {
        $earning = self::find($id);
        if ($earning) {
            $earning->update($data);
            return $earning;
        }
        return null;
    }

    public function deleteEarning($id)
    {
        $earning = self::find($id);
        if ($earning) {
            $earning->delete();
            return true;
        }
        return false;
    }
}
