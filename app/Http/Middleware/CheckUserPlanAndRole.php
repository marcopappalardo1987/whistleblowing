<?php

namespace App\Http\Middleware;

use DB;
use Closure;
use App\Models\User;
use App\Models\CompanyData;
use App\Models\Investigator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class CheckUserPlanAndRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    { 
        $user = Auth::user();

        if(!$user) {
            return redirect()->route(app()->getLocale() . '.login', ['locale' => app()->getLocale()]);
        }
        
        // Controlla se l'utente ha un piano e se il ruolo è 'azienda'
        $subscription = $user->subscription;
        $isActive = $user->subscription && $user->subscription->stripe_status === 'active';
        $isTrial = $user->subscription && $user->subscription->stripe_status === 'trialing';

        if (!$subscription && $user->getRoleNames()->first() === 'azienda' || $user->getRoleNames()->first() === 'azienda' && !$isActive && !$isTrial) {
            //dd($user->subscription);
            return redirect()->route(app()->getLocale() . '.plans', ['locale' => app()->getLocale()])
                ->with('error', 'Per accedere alla dashboard, è necessario avere un piano attivo.');
        }

        if($user->getRoleNames()->first() === 'investigatore') {
            $investigator = Investigator::where('investigator_id', $user->id)->first();
            $company = User::where('id', $investigator->company_id)->first();
            $subscription = $company->subscription;
            
            if (!$subscription->stripe_status) {
                return redirect()->route(app()->getLocale() . '.plans', ['locale' => app()->getLocale()])
                    ->with('error', 'L\'azienda non ha un piano attivo. Contatta l\'amministratore dell\'azienda.');
            }
            return $next($request);
        }
        
        // Check if the user is subscribed and has the role 'azienda'
        if ($subscription && $user->getRoleNames()->first() === 'azienda' && $user->getRoleNames()->first() !== 'owner') {
            if (!CompanyData::getCompanyData($user->id)) {
                return redirect()->route('company.edit', ['user' => $user->id])
                    ->with('warning', 'Per favore, completa i dati aziendali prima di accedere alla dashboard.');
            }
        }
        
        // Recupera i dettagli dell'abbonamento
        $subscription = $user->subscription; // Usa il metodo per ottenere l'abbonamento

        // Condividi i dettagli dell'abbonamento con tutte le viste
        View::share('subscription', $subscription);

        return $next($request);
    }
}
