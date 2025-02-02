<?php

namespace App\Http\Controllers;

use App\Models\CompanyData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\EmailService;

/**
 * Class CompanyDataController
 * This controller handles the management of company data, including editing and creating/updating company information.
 */
class CompanyDataController extends Controller
{
    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Show the form for editing the specified company data.
     *
     * @param int $id The ID of the company data to edit.
     * @return \Illuminate\View\View The view for editing company data.
     */
    public function edit($id)
    {
        // Get company data for the authenticated user
        $companyData = CompanyData::where('user_id', $id)->first();
        return view('profile.edit-company-data', compact('companyData'));
    }

    /**
     * Create a new company data entry or update an existing one.
     *
     * @param \Illuminate\Http\Request $request The incoming request containing company data.
     * @param int $id The ID of the user associated with the company data.
     * @return \Illuminate\Http\RedirectResponse A redirect response with a status message.
     */
    public function createOrUpdate(Request $request, $id)
    {
        $validatedData = $request->validate([
            'legal_name' => 'required|string|max:255', // Legal name of the company
            'vat_number' => 'required|string|max:255', // VAT number of the company
            'tax_code' => 'nullable|string|max:255', // Tax code, if applicable
            'sdi_code' => 'nullable|string|max:255', // SDI code for electronic invoicing
            'full_address' => 'required|string|max:255', // Full address of the company
            'country' => 'nullable|string|max:255', // Country of the company
            'rea_number' => 'nullable|string|max:255', // REA number, if applicable
            'registration_number' => 'nullable|string|max:255', // Registration number, if required
            'email' => 'nullable|email|max:255', // Contact email of the company
            'phone_number' => 'nullable|string|max:255', // Contact phone number of the company
            'administrative_contact' => 'nullable|string|max:255', // Name of the administrative contact
            'iban' => 'nullable|string|max:34', // IBAN for bank transactions (max length adjusted)
            'bank_name' => 'nullable|string|max:255', // Name of the bank
            'terms_conditions' => 'required|boolean', // Acceptance of terms and conditions (changed to required)
            'data_processing_consent' => 'nullable|boolean', // Consent for data processing
        ]);

        try {
            // Find or create company data
            $companyData = CompanyData::firstOrNew(['user_id' => $id]);
            
            // Update the model with validated data
            $companyData->fill($validatedData);
            $companyData->save();

            // Invia email di conferma
            $this->emailService->send(
                'company_data_updated',
                [
                    'user_name' => auth()->user()->name,
                    'updated_fields' => $validatedData
                ],
                auth()->user()->email
            );

            return redirect()->route('company.edit', ['user' => $id])->with('success', 'I dati aziendali sono stati aggiornati correttamente.');
        } catch (\Exception $e) {
            Log::error('Error updating company data for user ID ' . $id . ': ' . $e->getMessage());
            return redirect()->route('company.edit', ['user' => $id])->with('error', 'Si è verificato un errore durante il salvataggio dei dati.');
        }
    }
}
