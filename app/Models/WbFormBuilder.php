<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WbFormBuilder extends Model
{
    protected $table = 'wb_form_builder'; // Specify the table name

    protected $fillable = [
        'name',
        'description',
        'created_at',
        'updated_at',
        'cats', // Add 'cats' as a fillable attribute
    ];

    public function userForms()
    {
        return $this->hasMany(WbUserFormsBuilder::class, 'wb_form_builder_id');
    }
}
