<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CompanyData
 *
 * Rappresenta i dati di un'azienda nel sistema.
 * Questa classe gestisce le operazioni relative ai dati aziendali, inclusa la creazione,
 * il recupero, l'aggiornamento e la cancellazione dei dati.
 */
class CompanyData extends Model
{
    use HasFactory;

    // Gli attributi che possono essere assegnati in massa.
    protected $fillable = [
        'user_id',
        'legal_name',            // Il nome legale dell'azienda
        'vat_number',            // Il numero di partita IVA dell'azienda
        'tax_code',              // Il codice fiscale dell'azienda
        'sdi_code',              // Il codice SDI per la fatturazione elettronica
        'full_address',          // L'indirizzo completo dell'azienda
        'country',               // Il paese in cui si trova l'azienda
        'rea_number',            // Il numero REA per la registrazione dell'azienda
        'registration_number',    // Il numero di registrazione dell'azienda
        'email',                 // L'email di contatto dell'azienda
        'phone_number',          // Il numero di telefono di contatto dell'azienda
        'administrative_contact', // Il nome della persona di contatto amministrativa
        'iban',                  // L'IBAN per le transazioni bancarie
        'bank_name',             // Il nome della banca in cui l'azienda ha un conto
        'terms_conditions',      // Indica se i termini e le condizioni sono accettati
        'data_processing_consent', // Indica se è stato dato il consenso per il trattamento dei dati
    ];

    protected $casts = [
        'terms_conditions' => 'boolean',
        'data_processing_consent' => 'boolean',
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

    /**
     * Crea una nuova voce di dati aziendali.
     *
     * @param array $data I dati per creare la voce aziendale.
     * @return \App\Models\CompanyData L'istanza dei dati aziendali creata.
     */
    public static function createCompanyData(array $data)
    {
        return self::create($data); // Crea e restituisce i nuovi dati aziendali
    }

    /**
     * Recupera i dati aziendali per ID.
     *
     * @param int $id L'ID dei dati aziendali da recuperare.
     * @return \App\Models\CompanyData L'istanza dei dati aziendali.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Se non vengono trovati dati aziendali.
     */
    public static function getCompanyData($id)
    {
        return self::where('user_id', $id)->first(); // Trova i dati aziendali o fallisce se non trovati
    }

    /**
     * Aggiorna i dati aziendali esistenti per ID.
     *
     * @param int $id L'ID dei dati aziendali da aggiornare.
     * @param array $data I dati per aggiornare la voce aziendale.
     * @return \App\Models\CompanyData L'istanza dei dati aziendali aggiornati.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Se non vengono trovati dati aziendali.
     */
    public static function updateCompanyData($id, array $data)
    {
        $companyData = self::where('user_id', $id)->first(); // Trova i dati aziendali o fallisce se non trovati
        $companyData->update($data); // Aggiorna i dati aziendali con i dati forniti
        return $companyData; // Restituisce i dati aziendali aggiornati
    }

    /**
     * Elimina i dati aziendali per ID.
     *
     * @param int $id L'ID dei dati aziendali da eliminare.
     * @return bool True se l'eliminazione è avvenuta con successo, false altrimenti.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Se non vengono trovati dati aziendali.
     */
    public static function deleteCompanyData($id)
    {
        $companyData = self::where('user_id', $id)->first(); // Trova i dati aziendali o fallisce se non trovati
        return $companyData->delete(); // Elimina i dati aziendali e restituisce il risultato
    }

    /**
     * Recupera tutte le voci di dati aziendali.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\CompanyData[] Una collezione di tutte le voci di dati aziendali.
     */
    public static function getAllCompanyData()
    {
        return self::all(); // Restituisce tutte le voci di dati aziendali
    }
}
