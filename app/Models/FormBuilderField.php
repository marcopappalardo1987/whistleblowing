<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormBuilderField extends Model
{
    protected $table = 'wb_form_builder_fields';
    
    protected $fillable = [
        'form_id',
        'label',
        'type',
        'column_length',
        'options',
        'validation_rules',
        'help_text',
        'required',
        'order',
        'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'validation_rules' => 'array',
        'required' => 'boolean',
    ];

    public function form()
    {
        return $this->belongsTo(FormBuilder::class, 'form_id');
    }
}
