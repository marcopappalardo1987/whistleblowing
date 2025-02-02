<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\EmailService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

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

        // Assegna il ruolo di default "user"
        $user->assignRole('azienda');

        // Memorizza il referral se esiste
        if ($request->filled('referral_id')) {
            Affiliate::createAffiliate($user->id, $request->referral_id);
        }elseif(isset($_COOKIE['wbt_referral_id'])){
            Affiliate::createAffiliate($user->id, $_COOKIE['wbt_referral_id']);
        }

        event(new Registered($user));

        // Invia email di benvenuto
        $this->emailService->send(
            'welcome',
            [
                'user_name' => $user->name,
            ],
            $user->email
        );

        Auth::login($user);

        return redirect()->route('plans')
            ->with('success', 'Registrazione completata con successo! È necessario acquistare un piano per iniziare a utilizzare il gestore di whistleblowing.');
    }
}
