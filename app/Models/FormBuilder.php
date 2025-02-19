<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormBuilder extends Model
{
    use SoftDeletes;
    
    protected $table = 'wb_form_builder';
    
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'location',
        'is_public',
        'status',
    ];

    public function fields()
    {
        return $this->hasMany(FormBuilderField::class, 'form_id');
    }
}
