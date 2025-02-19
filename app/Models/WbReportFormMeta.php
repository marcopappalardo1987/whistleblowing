<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WbReportFormMeta extends Model
{
    protected $table = 'wb_report_form_meta'; // Specify the table name

    protected $fillable = [
        'id_form',
        'meta_key',
        'meta_value',
        'is_file'
    ];

    protected $casts = [
        'is_file' => 'boolean'
    ];

    public function form()
    {
        return $this->belongsTo(WbReportForms::class, 'id_form');
    }

}
