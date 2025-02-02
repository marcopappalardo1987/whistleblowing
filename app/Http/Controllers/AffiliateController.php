<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Affiliate; // Importa il modello Affiliate
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AffiliateController extends Controller
{
    public static function registerAffiliate(Request $request)
    {
        // Controlla se l'utente esiste già
        if (User::where('email', $request->email)->exists()) {
            return redirect()->route('login')
                ->with('info', 'Un account con questa email esiste già. Effettua il login.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
            'referral_id' => ['nullable', 'exists:users,id'], // Validate referral_id if provided
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
 
        $user->assignRole('affiliato');

        // Memorizza il referral se esiste
        if ($request->filled('referral_id')) {
            Affiliate::createAffiliate($user->id, $request->referral_id);
        }elseif(isset($_COOKIE['wbt_referral_id'])){
            Affiliate::createAffiliate($user->id, $_COOKIE['wbt_referral_id']);
        }

        return redirect()->route('login')
            ->with('success', 'Registrazione completata con successo! Ora puoi effettuare il login.');
    }

    public function ownAffiliates()
    {
        // Utilizza il metodo del modello per recuperare gli affiliati con paginazione
        $affiliates = Affiliate::getAffiliatesByParentId(auth()->id());

        return view('affiliate.private-area.affiliates-list', [
            'affiliates' => $affiliates,
        ]);
    }
    
}
