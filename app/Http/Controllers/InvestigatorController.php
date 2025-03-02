<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\WbReport;
use App\Models\CompanyData;
use App\Models\Investigator;
use Illuminate\Http\Request;
use App\Models\WbReportForms;
use App\Services\EmailService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class InvestigatorController extends Controller
{
    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function invite()
    {
        $branches = Branch::where('company_id', Auth::user()->id)->get();
        $countInvestigators = Investigator::countInvestigators();
        return view('investigators.invite', compact('branches', 'countInvestigators'));
    }

    public function store(Request $request)
    {
        $email = $request->input('email');
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->route('investigator.invite')->with('error', __('Email non valida.'));
        }

        if (Investigator::where('email', $email)->exists()) {
            return redirect()->route('investigator.invite')->with('error', __('L\'email è già in uso, non è possibile invitarlo.'));
        }

        $userId = Auth::user()->id;
        $company = CompanyData::where('user_id', $userId)->first();
        
        
        $investigator = Investigator::create([
            'company_id' => $userId,
            'email' => $request->email,
            'name' => $request->name,
            'branch_id' => $request->branch_id,
            'status' => 'pending',
        ]);
        
        $branch = Branch::find($request->branch_id);
        /* dd($investigator->email, $request->name, $branch->name); */
        try {
            // Invia email di invito
            $emailData = [
                'company_name' => $company->legal_name,
                'investigator_email' => $request->email,
                'investigator_name' => $request->name,
                'branch_name' => $branch->name,
                'registration_url' => config('app.url') . route('register.investigator', [], false),
            ];
            

            $this->emailService->send(
                'investigator_invitation',
                $emailData,
                $investigator->email
            );

            return redirect()->route('investigator.invite')
                ->with('success', __('Investigatore invitato con successo'));

        } catch (\Exception $e) {
            Log::error('Errore nell\'invio dell\'email di invito: ' . $e->getMessage());
            return redirect()->route('investigator.invite')
                ->with('warning', __('Investigatore creato ma si è verificato un errore nell\'invio dell\'email'));
        }
    }

    public function register(Request $request)
    {
        if(Auth::check()) {
            return redirect()->route('dashboard')->with('error', __('Sei già loggato, per registrarti come investigatore devi prima disconnetterti.'));
        }
        $investigator = Investigator::where('email', $request->email)->first();
        return view('auth.register-investigators', compact('investigator'));
    }

    public function registerStore(Request $request)
    {
        $userExists = User::where('email', $request->email)->exists();
        if ($userExists) {
            $userRole = User::where('email', $request->email)->first()->roles->first()->name;
            return redirect()->route('register.investigator')
                ->with('error', __('L\'email è già in uso come ' . $userRole . ', non è possibile registrarsi.'));
        }
        
        $investigator = Investigator::where('email', $request->email)->first();

        if (!$investigator) {
            return redirect()->route('register.investigator')
                ->with('error', __('Questa email non è stata invitata come investigatore.'));
        }

        if($request->password != $request->password_confirmation) {
            return redirect()->route('register.investigator')
                ->with('error', __('Registrazione non riuscita, perchè le password non corrispondono.'));
        }

        $user = User::create([
            'name' => $investigator->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('investigatore');

        $investigator->update(['investigator_id' => $user->id, 'status' => 'active']);
        
        $company = CompanyData::where('user_id', $investigator->company_id)->first();
        $branch = Branch::where('company_id', $investigator->company_id)->first();

        // Invia email di invito
        $emailData = [
            'company_name' => $company->legal_name,
            'investigator_email' => $request->email,
            'investigator_name' => $investigator->name,
            'branch_name' => $branch->name
        ];
        

        $this->emailService->send(
            'investigator_registration',
            $emailData,
            $investigator->email
        );
        
        return redirect()->route(app()->getLocale().'.login', ['locale' => app()->getLocale()])->with('success', __('Registrazione completata con successo. Da questo momento puoi accedere alla piattaforma con le tue credenziali.'));
    }

    public function list()
    {
        $investigators = Investigator::where('company_id', Auth::user()->id)->get();
        $branches = Branch::where('company_id', Auth::user()->id)->get();
        $investigators->load('loginLogs');
        $lastLogin = $investigators->map(function ($investigator) {
            return $investigator->loginLogs->last() ? $investigator->loginLogs->last() : null;
        });
        
        return view('investigators.list', compact('investigators', 'branches', 'lastLogin'));
    }

    public function edit($id)
    {
        $investigator = Investigator::find($id);
        $branches = Branch::where('company_id', Auth::user()->id)->get();
        return view('investigators.edit', compact('investigator', 'branches'));
    }

    public function update(Request $request, $id)
    {
        $investigator = Investigator::find($id);
        $investigator->update(['branch_id' => $request->branch_id]);
        return redirect()->route('investigator.list')->with('success', __('Investigatore aggiornato con successo'));
    }

    public function destroy($id)
    {
        $investigator = Investigator::find($id);
        if ($investigator->user) {
            $investigator->user->delete();
        }
        $investigator->delete();
        return redirect()->route('investigator.list');
    } 

    public function reportsList($status)
    {
        $investigator = Investigator::where('investigator_id', Auth::user()->id)->first();
        $companyId = $investigator->company_id;
        $branchId = $investigator->branch_id;

        $reports = WbReport::where('company_id', $companyId)->where('branch_id', $branchId)->where('status', $status)->get();
        $reports->load('forms.metadata');
        
        return view('investigator.reports-list', compact('investigator', 'reports'));
    }

    public function viewReport($id)
    {
        $investigator = Investigator::where('investigator_id', Auth::user()->id)->first();
        $report = WbReport::where('id', $id)
            ->where('company_id', $investigator->company_id)
            ->where('branch_id', $investigator->branch_id)
            ->firstOrFail();

        $report->load('forms.metadata');

        $report->status = 'read';
        $reportUpdated = $report->save();
        if(!$reportUpdated){
            Log::error("Failed to update status for Report #{$report->id} to 'read'");
        }
        
        return view('investigator.view-report', compact('report'));
    }

    public function replyReport(Request $request, $id)
    {

        $investigator = Investigator::where('investigator_id', Auth::user()->id)->first();
        $report = WbReport::where('id', $id)
            ->where('company_id', $investigator->company_id)
            ->where('branch_id', $investigator->branch_id)
            ->firstOrFail();

        $reportUpdated = $report->update(['status' => 'replied']);
        if(!$reportUpdated){
            Log::error("Failed to update status for Report #{$report->id} to 'replied'");
        }

        $newForm = WbReportForms::create([
            'id_report' => $report->id,
            'writer' => Auth::user()->id,
        ]);

        $newForm->metadata()->create([
            'id_form' => $newForm->id,
            'meta_key' => encrypt('Risposta'),
            'meta_value' => encrypt($request->message),
        ]);

        return redirect()->route('investigator.report.change-status', ['id' => $report->id])->with('success', __('Risposta inviata con successo'));
    }

    public function changeStatus($id)
    {
        $investigator = Investigator::where('investigator_id', Auth::user()->id)->first();
        $report = WbReport::where('id', $id)
            ->where('company_id', $investigator->company_id)
            ->where('branch_id', $investigator->branch_id)
            ->firstOrFail();

        return view('investigator.reports-change-status', compact('report'));
    }

    public function statusUpdate(Request $request, $id)
    {
        /* dd($request->all()); */
        $investigator = Investigator::where('investigator_id', Auth::user()->id)->first();
        $report = WbReport::where('id', $id)
            ->where('company_id', $investigator->company_id)
            ->where('branch_id', $investigator->branch_id)
            ->firstOrFail();

        $report->update(['status' => $request->report_status]);

        return redirect()
            ->route('dashboard')
            ->with('success', __('Stato della segnalazione aggiornato con successo'));
    }

}
