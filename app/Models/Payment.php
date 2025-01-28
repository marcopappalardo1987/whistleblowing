<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // Impostazioni della tabella
    protected $table = 'payments'; // Se la tabella si chiama 'payments', non è necessario, ma può essere utile

    // Attributi che possono essere assegnati in massa
    protected $fillable = [
        'user_id',
        'stripe_payment_id',
        'stripe_invoice_id',
        'amount',
        'currency',
        'status',
        'paid_at',
    ];

    // Definizione della relazione con User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
