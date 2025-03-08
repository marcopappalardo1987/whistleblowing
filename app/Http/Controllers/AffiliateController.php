<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\EmailService;
use Illuminate\Support\Facades\Log;
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
            return redirect()->route(app()->getLocale().'.login', ['locale' => app()->getLocale()])
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
            'affiliate_name' => $user->name,
            'affiliate_id' => $user->id,
            'affiliate_link' => url('/area-privata/referal-link'), // Assuming this is the link to the affiliate's private area
            'affiliate_email' => $user->email
        ];

        // Invia email di benvenuto
        try{
            $sentUserEmail = $this->emailService->send(
                'welcome_affiliate',
                $emailData,
                $user->email
            );
        }catch(\Exception $e){
            Log::error('Errore nell\'invio dell\'email di benvenuto all\'affiliato: ' . $e->getMessage());
        }

        try{
            $sentAdminEmail = $this->emailService->send(
                'admin_welcome_affiliate',
                $emailData,
                config('mail.admin_email')
            );
        }catch(\Exception $e){
            Log::error('Errore nell\'invio dell\'email di benvenuto all\'amministratore: ' . $e->getMessage());
        }

        return redirect()->route(app()->getLocale().'.login', ['locale' => app()->getLocale()])
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
