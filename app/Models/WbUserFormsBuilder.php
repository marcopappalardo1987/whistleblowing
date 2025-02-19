<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WbUserFormsBuilder extends Model
{
    protected $table = 'wb_user_forms_builder'; // Specify the table name

    protected $fillable = ['user_id', 'location', 'wb_form_builder_id'];
    
    public function formBuilder()
    {
        return $this->belongsTo(WbFormBuilder::class, 'wb_form_builder_id');
    }

    public function formFields()
    {
        return $this->hasMany(FormBuilderField::class, 'form_id');
    }

}
