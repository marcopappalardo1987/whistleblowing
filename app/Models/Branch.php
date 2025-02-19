<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'company_id',
        'name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
