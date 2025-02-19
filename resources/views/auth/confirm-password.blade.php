<x-guest-layout>
    <div class="mb-4 small text-muted">
        {{ __('Questa è un\'area sicura dell\'applicazione. Per favore, conferma la tua password prima di continuare.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            
            <input id="password" 
                   type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   name="password"
                   required 
                   autocomplete="current-password">

            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary">
                {{ __('Conferma') }}
            </button>
        </div>
    </form>
</x-guest-layout>
