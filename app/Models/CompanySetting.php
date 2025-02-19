<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    protected $fillable = [
        'user_id',   // ID dell'utente a cui appartiene la configurazione
        'slug',      // Slug unico per la configurazione
        'logo_url',  // URL del logo dell'azienda
    ];

    /**
     * Relazione con il modello User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
