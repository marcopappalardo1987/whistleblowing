<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\WbReport;
use App\Models\CompanyData;
use App\Models\Investigator;
use Illuminate\Http\Request;
use App\Models\CompanySetting;
use App\Models\WbUserFormsBuilder;
use App\Models\AffiliateCommission;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first();
        
        switch ($role) {
            case 'investigatore':
                return $this->dashboardInvestigator();
            case 'azienda':
                return $this->dashboardCompany();
            case 'affiliato':
                return $this->dashboardAffiliate();
            case 'owner':
                return $this->dashboardOwner();
            default:
                return $this->dashboardAdmin();
        }
    }

    public function dashboardInvestigator()
    {
        $investigator = Investigator::where('investigator_id', Auth::user()->id)->first();
        
        $reports = WbReport::where('company_id', $investigator->company_id)
            ->where('branch_id', $investigator->branch_id)
            ->get();

        
        return view('investigator.index', compact('investigator', 'reports'));
    }

    public function dashboardCompany()
    {
        $user = Auth::user();
        $investigators = Investigator::where('company_id', $user->id)->get();
        $branches = Branch::where('company_id', $user->id)->get();
        $userFormBuilder = WbUserFormsBuilder::where('user_id', $user->id)->get();
        $logo = CompanySetting::where('user_id', $user->id)->first();

        $steps = array();
        $data = array();

        if($userFormBuilder->count() == 0) {
            $message = __('Configura i tuoi <strong>moduli di segnalazione</strong>!');
            $message .= ' ' . __('Per iniziare a <em>ricevere segnalazioni</em>, devi prima creare almeno un <strong>modulo di segnalazione</strong> se il tuo piano lo prevede, altrimenti puoi utilizzare quelli di <em>default</em>.');
            $message .= ' <div class="mt-3 mb-2"><a href="' . route('company.users-forms-relations') . '" class="btn btn-primary">' . __('Configura il modulo di segnalazione') . '</a> <a class="btn btn-primary" href="'.route('form.builder.new').'">' . __('Crea un modulo personalizzato') . '</a></div>';
            $steps[] = $message;
        }

        if($branches->count() == 1 && $branches[0]->name == 'Default') {
            $message = __('WhistleblowingTool offre la possibilità di dividere le segnalazioni per uno o più <strong>branch</strong> (es. filiali, dipartimenti, etc.).'). '<br>';
            $message .= ' ' . __('Se il tuo piano lo prevede, puoi aggiungere anche più di un <strong>branch</strong> per la tua azienda.'). '<br>';
            $message .= ' ' . __('Momentaneamente, ne abbiamo creato automaticamente uno di default, ma per una corretta gestione delle segnalazioni, ti invitiamo a modificarne il nome e/o aggiungerne altri.');
            $message .= ' <div class="mt-3 mb-2"><a href="' . route('branch.list') . '" class="btn btn-primary">' . __('Gestisci i branch') . '</a></div>';
            $steps[] = $message;
        }
        
        if($investigators->count() == 0) {
            $message = __('Non hai ancora aggiunto nessun investigatore.');
            $message .= ' ' . __('Per consentire al tuo personale di accedere al software, devi prima aggiungerne almeno uno.');
            $message .= ' <div class="mt-3 mb-2"><a href="' . route('investigator.invite') . '" class="btn btn-primary">' . __('Invita il primo investigatore') . '</a></div>';
            $steps[] = $message;
        }

        if($logo == null) {
            $message = __('Inserisci il <strong>logo</strong> della tua azienda!');
            $message .= ' ' . __('Utilizzeremo il <strong>logo</strong> della tua azienda nella pagina relativa alle <em>segnalazioni</em>.');
            $message .= ' ' . __('Dopo l\'inserimento del <strong>logo</strong>, otterrai un <em>link univoco</em> per la tua pagina delle <strong>segnalazioni</strong> uno per ogni branch.');
            $message .= ' ' . __('Potrai condividere questi link nei tuoi canali di comunicazione e/o sui tuoi siti web.');
            $message .= ' <div class="mt-3 mb-2"><a href="' . route('company.settings') . '" class="btn btn-primary">' . __('Configura il logo') . '</a></div>';
            $steps[] = $message;
        }

        if(empty($steps)) {
            $steps = false;

            $data['numero_investigatori'] = $investigators->count();
            $data['numero_branch'] = $branches->count();
            
        }
        
        return view('company.dashboard', compact('steps', 'data'));
    }

    public function dashboardAdmin()
    {
        return view('dashboard');
    }

    public function dashboardAffiliate()
    {
        $commissions = AffiliateCommission::all();
        return view('affiliate.dashboard', compact('commissions'));
    }

    public function dashboardOwner()
    {
        return view('dashboard');
    }
}
