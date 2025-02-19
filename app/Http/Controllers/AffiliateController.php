<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\EmailService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\Affiliate; // Importa il modello Affiliate

class AffiliateController extends Controller
{
    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function registerAffiliate(Request $request)
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

        $emailData = [
            'affiliate_name' => $request->name,
            'affiliate_id' => $user->id,
            'affiliate_link' => url('/affiliate/private-area/referal-link'), // Assuming this is the link to the affiliate's private area
            'commission_rate' => 10 // Set a default commission rate or retrieve it from a config or database
        ];

        // Invia email di benvenuto
        $this->emailService->send(
            'welcome_affiliate',
            $emailData,
            $user->email
        );

        return redirect()->route('login')
            ->with('success', 'Registrazione completata con successo! Ora puoi effettuare il login.');
    }

    public function ownAffiliates()
    {
        // Utilizza il metodo del modello per recuperare gli affiliati con paginazione
        $affiliates = Affiliate::getAffiliatesByParentId(Auth::id());

        return view('affiliate.private-area.affiliates-list', [
            'affiliates' => $affiliates,
        ]);
    }
    
}
