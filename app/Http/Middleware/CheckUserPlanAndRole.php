<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\CompanyData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserPlanAndRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        // Controlla se l'utente ha un piano e se il ruolo è 'azienda'
        if (!$user->subscribed('default') && $user->getRoleNames()->first() === 'azienda') {
            return redirect()->route('plans')
            ->with('error', 'Per accedere alla dashboard, è necessario avere un piano attivo.');
        }
        
        // Check if the user is subscribed and has the role 'azienda'
        if ($user->subscribed('default') && $user->getRoleNames()->first() === 'azienda') {
            if (!CompanyData::getCompanyData($user->id)) {
                return redirect()->route('company.edit', ['user' => $user->id])
                    ->with('warning', 'Per favore, completa i dati aziendali prima di accedere alla dashboard.');
            }
        }

        return $next($request);
    }
}
