<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WbReportForms extends Model
{
    protected $fillable = [
        'id_report',
        'writer'
    ];
    
    public function report()
    {
        return $this->belongsTo(WbReport::class, 'id_report');
    }

    public function metadata()
    {
        return $this->hasMany(WbReportFormMeta::class, 'id_form');
    }

    public function writer_user()
    {
        return $this->belongsTo(User::class, 'writer');
    }

}
