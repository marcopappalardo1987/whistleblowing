<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyDataRequest extends FormRequest
{
    /**
     * Determina se l'utente è autorizzato a effettuare questa richiesta.
     */
    public function authorize(): bool
    {
        return true; // Cambia in false se vuoi aggiungere restrizioni
    }

    /**
     * Definisce le regole di validazione.
     */
    public function rules(): array
    {
        return [
            'legal_name' => 'required|string|max:255',
            'vat_number' => 'required|string|max:50|unique:company_data,vat_number,' . $this->route('id') . ',user_id',
            'tax_code' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'full_address' => 'required|string|max:500',
            'iban' => 'nullable|string|max:34',
            'terms_conditions' => 'required|accepted',
            'sdi_code' => 'nullable|string|max:7',
            'country' => 'nullable|string|max:100',
            'rea_number' => 'nullable|string|max:255',
            'registration_number' => 'nullable|string|max:255',
            'administrative_contact' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'data_processing_consent' => 'nullable|boolean',
        ];
    }

    /**
     * Messaggi di errore personalizzati.
     */
    public function messages(): array
    {
        return [
            'legal_name.required' => 'Il nome legale è obbligatorio.',
            'vat_number.required' => 'La partita IVA è obbligatoria.',
            'vat_number.unique' => 'La partita IVA è già stata registrata.',
            'tax_code.string' => 'Il codice fiscale deve essere una stringa.',
            'email.email' => 'Inserisci un indirizzo email valido.',
            'phone_number.max' => 'Il numero di telefono non può superare i 20 caratteri.',
            'terms_conditions.accepted' => 'Devi accettare i termini e condizioni.',
            'sdi_code.max' => 'Il codice SDI non può superare i 7 caratteri.',
            'full_address.required' => 'L\'indirizzo completo è obbligatorio.',
            'full_address.string' => 'L\'indirizzo completo deve essere una stringa.',
            'full_address.max' => 'L\'indirizzo completo non può superare i 500 caratteri.',
        ];
    }
}
