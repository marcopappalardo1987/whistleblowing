<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Affiliate;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Services\EmailService;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;

class RegisteredUserController extends Controller
{
    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'referral_id' => ['nullable', 'exists:users,id'], // Validate referral_id if provided
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assegna il ruolo di default "azienda"
        $user->assignRole('azienda');

        // Memorizza il referral se esiste
        if ($request->filled('referral_id')) {
            Affiliate::createAffiliate($user->id, $request->referral_id);
        }elseif(isset($_COOKIE['wbt_referral_id'])){
            Affiliate::createAffiliate($user->id, $_COOKIE['wbt_referral_id']);
        }

        event(new Registered($user));

        // Invia email di benvenuto
        try{
            $this->emailService->send(
                'welcome',
                [
                    'user_name' => $user->name,
                ],
                $user->email
            );
        }catch(\Exception $e){
            Log::error('Errore nell\'invio dell\'email di benvenuto all\'utente: ' . $e->getMessage());
        }

        try{
            $this->emailService->send(
                'admin_welcome',
                [
                    'company_name' => $user->name,
                    'company_email' => $user->email
                ],
                config('mail.admin_email')
            );
        }catch(\Exception $e){
            Log::error('Errore nell\'invio dell\'email di benvenuto all\'amministratore: ' . $e->getMessage());
        }

        Auth::login($user);

        return redirect()->route(app()->getLocale() . '.plans', ['locale' => app()->getLocale()])
            ->with('success', 'Registrazione completata con successo! È necessario acquistare un piano per iniziare a utilizzare il gestore di whistleblowing.');
    }
}
