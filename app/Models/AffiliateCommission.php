<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateCommission extends Model
{
    protected $fillable = ['level', 'commission'];

    public static function createOrUpdateCommission($data)
    {
        // Disabilita la protezione per l'assegnazione di massa
        self::unguard();

        // Verifica se esiste già una commissione per il livello specificato
        $existingCommission = self::where('level', $data['level'])->first();

        if ($existingCommission) {
            // Aggiorna la commissione esistente
            $existingCommission->update($data);
        } else {
            // Crea una nuova commissione
            self::create($data);
        }

        // Riabilita la protezione per l'assegnazione di massa
        self::reguard();
    }

    public static function getCommissionById($id)
    {
        return self::find($id);
    }

    public static function deleteCommissionById($id)
    {
        $commission = self::find($id);
        if ($commission) {
            $commission->delete();
            return true; // Commissione eliminata con successo
        }
        return false; // Commissione non trovata
    }
    
}
