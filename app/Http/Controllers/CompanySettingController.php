<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Branch;
use App\Models\CompanyData;
use Illuminate\Http\Request;
use App\Models\WbFormBuilder;
use App\Models\CompanySetting;
use App\Models\FormBuilderField;
use App\Models\WbUserFormsBuilder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

/**
 * Class CompanySettingController
 *
 * Questo controller gestisce le impostazioni aziendali.
 * Fornisce metodi per visualizzare le impostazioni dell'azienda.
 */
class CompanySettingController extends Controller
{
    /**
     * Mostra le impostazioni aziendali.
     *
     * Questo metodo recupera tutte le impostazioni dalla tabella company_settings
     * e le passa alla vista 'company.settings'.
     *
     * @return \Illuminate\View\View La vista delle impostazioni aziendali.
     */
    public function show()
    {
        $settings = CompanySetting::where('user_id', Auth::id())->get(); // Ottieni le impostazioni per l'utente attualmente loggato
        $branches = Branch::where('company_id', Auth::id())->get();
        return view('company.settings', compact('settings', 'branches')); // Passa i dati alla vista settings.blade.php
    }

    public function store(Request $request)
    {

        if($request->hasFile('logo_url')) {
            $fileSize = $request->file('logo_url')->getSize();

            if( $request->file('logo_url')->getMimeType() != 'image/jpeg' && 
                $request->file('logo_url')->getMimeType() != 'image/png' &&
                $request->file('logo_url')->getMimeType() != 'image/jpg' &&
                $request->file('logo_url')->getMimeType() != 'image/gif' &&
                $request->file('logo_url')->getMimeType() != 'image/svg+xml' &&
                $request->file('logo_url')->getMimeType() != 'image/webp'
            ) {
                return redirect()->route('company.settings')->with('error', 'Il formato del logo non è supportato.');
            }

            if( $fileSize > 2 * 1024 * 1024 ) {
                return redirect()->route('company.settings')->with('error', 'Il file è troppo grande.');
            }
        }

        if( $request->slug == '' ) {
            return redirect()->route('company.settings')->with('error', 'Il campo slug è obbligatorio.');
        }

        if( strlen($request->slug) > 255 ) {
            return redirect()->route('company.settings')->with('error', 'Il campo slug è troppo lungo.');
        }

        if( strlen($request->slug) < 3 ) {
            return redirect()->route('company.settings')->with('error', 'Il campo slug è troppo corto.');
        }

        if( !preg_match('/^[a-zA-Z0-9_-]+$/', $request->slug) ) {
            return redirect()->route('company.settings')->with('error', 'Il campo slug può contenere solo lettere, numeri, trattini e underscore.');
        }

        if( CompanySetting::where('slug', $request->slug)->exists() ) {
            if (!CompanySetting::where('slug', $request->slug)->where('user_id', Auth::id())->exists()) {
                return redirect()->route('company.settings')->with('error', 'Il campo slug è già presente.');
            }
        }

        $request->validate([
            'slug' => 'required|string|max:255',
            'logo_url' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048', // Make logo_url not required
        ]);

        $companySetting = CompanySetting::where('user_id', Auth::id())->first();

        if ($companySetting) {
            $companySetting->slug = $request->slug;

            if ($request->hasFile('logo_url')) {
                // Elimina il file precedente se esiste
                if ($companySetting->logo_url) {
                    $previousLogoPath = public_path($companySetting->logo_url);
                    if (file_exists($previousLogoPath)) {
                        unlink($previousLogoPath);
                    }
                }
                $fileName = time() . '_' . $request->file('logo_url')->getClientOriginalName();
                $request->file('logo_url')->move(
                    public_path('/uploads/img'),
                    $fileName
                );
                $companySetting->logo_url = '/uploads/img/' . $fileName;
            }
        } else {
            $companySetting = new CompanySetting();
            $companySetting->user_id = Auth::id();
            $companySetting->slug = $request->slug;

            if ($request->hasFile('logo_url')) {
                $fileName = time() . '_' . $request->file('logo_url')->getClientOriginalName();
                $request->file('logo_url')->move(
                    public_path('/uploads/img'),
                    $fileName
                );
                $companySetting->logo_url = '/uploads/img/' . $fileName;
            }
        }

        try {
            $companySetting->save();
            return redirect()->route('company.settings')->with('success', 'Impostazioni salvate con successo.');
        } catch (Exception $e) {
            Log::error('Errore durante il salvataggio delle impostazioni: ' . $e->getMessage());
            return redirect()->route('company.settings')->with('error', 'Si è verificato un errore durante il salvataggio delle impostazioni.');
        }
    }

    public function companyPageWhistleblowing($slug)
    {
        $settings = CompanySetting::where('slug', $slug)->first();
        $company = CompanyData::where('user_id', $settings->user_id)->first();
        $branch = Branch::where('id', request()->route('branch_id'))->first();
        $countBranches = Branch::where('company_id', $settings->user_id)->count();
        $user = User::where('id', $settings->user_id)->first();
        $subscriptionIsActive = $user->subscriptionStatus();

        return view('frontend.page-whistleblowing', compact('settings', 'company', 'branch', 'countBranches', 'user', 'subscriptionIsActive'));
    }

    public function companyPageWhistleblowingForm($slug)
    {
        $settings = CompanySetting::where('slug', $slug)->first();
        $company = CompanyData::where('user_id', $settings->user_id)->first();
        $branch = Branch::where('id', request()->route('branch_id'))->first();
        $countBranches = Branch::where('company_id', $settings->user_id)->count();
        $user = User::where('id', $settings->user_id)->first();
        $subscriptionIsActive = $user->subscriptionStatus();

        $forms_notice_id = WbUserFormsBuilder::where('user_id', $settings->user_id)
            ->where('location', 'notice')
            ->pluck('wb_form_builder_id');
        $forms_notice = WbFormBuilder::whereIn('id', $forms_notice_id)->get();
        $forms_notice_fields = FormBuilderField::whereIn('form_id', $forms_notice_id)->orderBy('order', 'asc')->get();

        return view('frontend.page-whistleblowing-form-segnalazioni', compact('settings', 'company', 'branch', 'countBranches', 'user', 'subscriptionIsActive', 'forms_notice_fields'));
    }

    public function companyPageWhistleblowingCercaSegnalazioni($slug)
    { 
        $settings = CompanySetting::where('slug', $slug)->first();
        $company = CompanyData::where('user_id', $settings->user_id)->first();
        $user = User::where('id', $settings->user_id)->first();
        $subscriptionIsActive = $user->subscriptionStatus();
        $countBranches = Branch::where('company_id', $settings->user_id)->count();
        $branch = Branch::where('id', request()->route('branch_id'))->first();
        
        return view('frontend.page-whistleblowing-cerca-segnalazioni', compact('settings', 'company', 'subscriptionIsActive', 'countBranches', 'user', 'branch'));
    }   

    public function companyPageWhistleblowingRichiediAppuntamento($slug)
    {
        $settings = CompanySetting::where('slug', $slug)->first();
        $company = CompanyData::where('user_id', $settings->user_id)->first();
        $branch = Branch::where('id', request()->route('branch_id'))->first();
        $countBranches = Branch::where('company_id', $settings->user_id)->count();
        $user = User::where('id', $settings->user_id)->first();
        $subscriptionIsActive = $user->subscriptionStatus();
        
        $forms_appointment_id = WbUserFormsBuilder::where('user_id', $settings->user_id)    
            ->where('location', 'appointment')
            ->pluck('wb_form_builder_id');
        $forms_appointment = WbFormBuilder::whereIn('id', $forms_appointment_id)->get();
        $forms_appointment_fields = FormBuilderField::whereIn('form_id', $forms_appointment_id)->orderBy('order', 'asc')->get();

        return view('frontend.page-whistleblowing-richiedi-appuntamento', compact('settings', 'company', 'branch', 'countBranches', 'user', 'subscriptionIsActive', 'forms_appointment_fields'));
    }

}
