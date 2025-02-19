<x-guest-layout>
    <div class="mb-4 small text-muted">
        {{ __('Hai dimenticato la tua password? Nessun problema. Facci sapere il tuo indirizzo email e ti invieremo un link per reimpostare la password che ti permetterà di sceglierne una nuova.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="text-end mt-4">
            <x-primary-button>
                {{ __('Invia link per reimpostare la password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
