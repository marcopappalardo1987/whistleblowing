<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\WbReport;
use App\Models\CompanyData;
use Illuminate\Support\Str;
use App\Models\Investigator;
use Illuminate\Http\Request;
use App\Models\WbReportForms;
use App\Models\CompanySetting;
use App\Services\EmailService;
use App\Models\WbReportFormMeta;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function reportStore(Request $request)
    {
        $passwordNotHashed = Str::random(12);
        $hashedPassword = Hash::make($passwordNotHashed);

        $slug = request()->route('slug');
        $companySetting = CompanySetting::where('slug', $slug)->first();

        if (!$companySetting) {
            Log::error('Company setting not found for slug: ' . $slug);
            return redirect()->route('page-whistleblowing', ['slug' => $slug])
                             ->withErrors(['company' => __('Azienda non trovata.')]);
        }

        $companyId = $companySetting->user_id;
        $status = 'submitted';
        if($request->branch_id) {
            $branchId = $request->branch_id;
        } else {
            Log::error('Non è stato selezionato un branch valido.');
            return redirect()->route('page-whistleblowing', ['slug' => $slug, 'branch_id' => $request->branch_id])->with('error', 'Non è stato selezionato un branch valido.');
        }

        try {
            $reportId = WbReport::create([
                'company_id' => $companyId,
                'password' => $hashedPassword,
                'status' => $status,
                'branch_id' => $branchId,
            ])->id;

            $formId = WbReportForms::create([
                'id_report' => $reportId,
                'writer' => $request->writer,
            ])->id;

            $files = $request->allFiles();
            // Ottieni tutti i dati del form inclusi gli attributi data-*
            $formData = $request->all();
            
            foreach ($formData as $key => $value) {
                
                $originalName = $request->input($key . '_label') ?? $key;

                if($value != null && $key != '_token' && !str_contains($key, '_label') && !in_array($key, array_keys($request->allFiles()))) {
                    WbReportFormMeta::create([
                        'id_form' => $formId,
                        'meta_key' => encrypt($originalName),
                        'meta_value' => encrypt($value),
                    ]);
                }
            }
            
            // Gestisci il salvataggio dei file
            foreach ($files as $key => $file) {
                $path = $file->store('reports', 'public'); // Salva il file sul server
                $publicPath = Storage::url($path); // Ottieni il percorso pubblico
                
                // Usa la chiave originale ricevuta nella request
                $originalName = $request->input($key . '_label') ?? $key;
            
                WbReportFormMeta::create([
                    'id_form' => $formId,
                    'meta_key' => encrypt($originalName),
                    'meta_value' => encrypt($publicPath), // Salva l'URL del file nel DB
                    'is_file' => 1,
                ]);
            }

            // Store data in session and redirect instead of returning view directly
            session([
                'report_settings' => $companySetting,
                'report_password' => $passwordNotHashed,
                'report_id' => $reportId,
                'branch_id' => $branchId,
            ]);

            // Notifica gli investigatori del branch
            $investigators = Investigator::where('branch_id', $branchId)
                ->where('status', 'active')
                ->get();

            $company = CompanyData::where('user_id', $companySetting->user_id)->first();
            Log::info("Company: " . $company);

            foreach ($investigators as $investigator) {
                try {
                    $emailData = [
                        'investigator_name' => $investigator->name,
                        'report_id' => $reportId,
                        'branch_name' => Branch::find($branchId)->name,
                        'company_name' => $company->legal_name,
                        'created_at' => now()->format('d/m/Y H:i'),
                    ];

                    $this->emailService->send(
                        'new_report_notification',
                        $emailData,
                        $investigator->email
                    );

                    Log::info("Email notification sent to investigator {$investigator->email} for Report #{$reportId}");
                } catch (\Exception $e) {
                    Log::error("Failed to send email notification to investigator {$investigator->email} for Report #{$reportId}: " . $e->getMessage());
                }
            }

            return redirect()->route('report.success-submitted', ['slug' => $slug]);
        } catch (\Exception $e) {
            Log::error('Error storing report: ' . $e->getMessage());
            return redirect()->route('page-whistleblowing', ['slug' => $slug, 'branch_id' => $request->branch_id])
                ->with('error', 'Si è verificato un errore durante l\'invio del report. Riprova più tardi.');
        }
    }

    public function successSubmitted(Request $request)
    {
        // Get data from session and clear it
        $settings = session('report_settings');
        $passwordNotHashed = session('report_password'); 
        $reportId = session('report_id');
        $branchId = session('branch_id');
        
        if (!$settings || !$passwordNotHashed || !$reportId) {
            return redirect()->route('page-whistleblowing', ['slug' => request()->route('slug'), 'branch_id' => $branchId])
                             ->withErrors(['session' => __('Dati di report non trovati. Riprova a inviare il report.')]);
        }

        session()->forget(['report_settings', 'report_password', 'report_id', 'branch_id']);

        return view('frontend.report.success-submitted', compact('settings', 'passwordNotHashed', 'reportId'));
    }

    public function viewReport(Request $request)
    {
        $slug = request()->route('slug');
        $settings = CompanySetting::where('slug', $slug)->first();
        
        $reportId = $request->report_id;
        $password = $request->password;

        $report = WbReport::where('id', $reportId)
            ->where('company_id', $settings->user_id)
            ->first();

        if ($report && Hash::check($password, $report->password)) {
            $formData = [
                'report' => [
                    'id' => $report->id,
                    'company_id' => $report->company_id,
                    'status' => $report->status,
                    'branch_id' => $report->branch_id,
                    'created_at' => $report->created_at,
                    'updated_at' => $report->updated_at
                ],
                'forms' => []
            ];

            $forms = WbReportForms::where('id_report', $reportId)->get();

            foreach ($forms as $form) {
                $form_id = $form->id;
                $formData['forms'][$form_id] = [
                    'writer' => !empty($form->writer) ? $form->writer : 'whistleblower',
                ];

                $formMeta = WbReportFormMeta::where('id_form', $form_id)->get();
                foreach ($formMeta as $meta) {
                    if($meta->meta_key != '_token') {
                        $keyDecrypted = decrypt($meta->meta_key);
                        $valueDecrypted = decrypt($meta->meta_value);
                        if($keyDecrypted != '_token') {
                            $formData['forms'][$form_id][$keyDecrypted] = $valueDecrypted;
                        }
                    }
                }
            }

            return view('frontend.report.view', compact('settings', 'formData'));

        } else {

            return redirect()->route('page-whistleblowing', ['slug' => $slug, 'branch_id' => $report->branch_id])->with('error', 'Password non valida.');
         
        }
    }

    public function replyReport(Request $request)
    {
        $reportId = $request->route('id');
        
        $report = WbReport::where('id', $reportId)
            ->firstOrFail();

        $reportUpdated = $report->update(['status' => 'user_replied']);

        if(!$reportUpdated){
            Log::error("Failed to update status for Report #{$report->id} to 'user_replied'");
        }

        $newForm = WbReportForms::create([
            'id_report' => $report->id,
            'writer' => 'whistleblower',
        ]);

        $newForm->metadata()->create([
            'id_form' => $newForm->id,
            'meta_key' => encrypt('Risposta'),
            'meta_value' => encrypt($request->message),
        ]);

        // Gestisci il salvataggio del file se presente
        if ($request->hasFile('allegato')) {
            $path = $request->file('allegato')->store('reports', 'public');
            $publicPath = Storage::url($path);
            $newForm->metadata()->create([
                'id_form' => $newForm->id,
                'meta_key' => encrypt('allegato'),
                'meta_value' => encrypt($publicPath),
                'is_file' => true
            ]);
        }

        // Notifica gli investigatori del branch
        $investigators = Investigator::where('branch_id', $report->branch_id)
            ->where('status', 'active')
            ->get();

        $branch = Branch::find($report->branch_id);
        $company = CompanyData::where('user_id', $report->company_id)->first();

        foreach ($investigators as $investigator) {
            try {
                $emailData = [
                    'investigator_name' => $investigator->name,
                    'report_id' => $reportId,
                    'branch_name' => $branch->name,
                    'company_name' => $company->legal_name,
                    'created_at' => now()->format('d/m/Y H:i'),
                ];

                $this->emailService->send(
                    'report_reply_notification',
                    $emailData,
                    $investigator->email
                );

                Log::info("Email notification sent to investigator {$investigator->email} for Report reply #{$reportId}");
            } catch (\Exception $e) {
                Log::error("Failed to send email notification to investigator {$investigator->email} for Report reply #{$reportId}: " . $e->getMessage());
            }
        }

        $slug = CompanySetting::where('user_id', $report->company_id)->first()->slug;
        return redirect()->route('page-whistleblowing', ['slug' => $slug, 'branch_id' => $report->branch_id])
            ->with('success', __('Risposta inviata con successo'));
    }

}
