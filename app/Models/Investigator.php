<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Investigator extends Model
{
    protected $fillable = [
        'id',
        'company_id',
        'investigator_id',
        'name',
        'email',
        'branch_id',
        'status',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function company()
    {
        return $this->belongsTo(CompanyData::class);
    }

    public static function countInvestigators()
    {
        return self::where('company_id', Auth::user()->id)->count();
    }

    public function loginLogs()
    {
        return $this->hasMany(LoginLog::class, 'user_id', 'investigator_id');
    }

    public function lastLoginDate()
    {
        return $this->loginLogs()->latest()->first()->created_at;
    }
    
}
