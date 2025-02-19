<?php

namespace App\Http\Controllers;

use App\Models\CompanyData;
use App\Services\EmailService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CompanyDataRequest;

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
    public function createOrUpdate(CompanyDataRequest $request, $id)
    {
        try {
            // Trova o crea i dati aziendali
            $companyData = CompanyData::firstOrNew(['user_id' => $id]);
            
            // Aggiorna i dati con quelli validati
            $companyData->fill($request->validated());
            $companyData->save();

            // Invia email di conferma
            $this->emailService->send(
                'company_data_updated',
                [
                    'user_name' => Auth::user()->name,
                    'updated_fields' => $request->validated()
                ],
                Auth::user()->email
            );

            return redirect()->route('company.edit', ['user' => $id])->with('success', 'I dati aziendali sono stati aggiornati correttamente.');
        } catch (\Exception $e) {
            Log::error('Error updating company data for user ID ' . $id . ': ' . $e->getMessage());
            return redirect()->route('company.edit', ['user' => $id])->with('error', 'Si è verificato un errore durante il salvataggio dei dati.');
        }
    }
}
