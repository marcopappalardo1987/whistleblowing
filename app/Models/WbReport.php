<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WbReport extends Model
{
    protected $table = 'wb_report';

    protected $fillable = [
        'company_id',
        'password',
        'status',
        'branch_id',
    ];

    public function forms()
    {
        return $this->hasMany(WbReportForms::class, 'id_report');
    }

}
