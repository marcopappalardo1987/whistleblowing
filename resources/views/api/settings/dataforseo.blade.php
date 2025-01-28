<x-app-layout>
    <x-slot name="header">
        {{ __('DataForSEO') }}
    </x-slot>

    @include('layouts.alert-message')

    @include('layouts.navigation-api-settings')

    <div class="card mt-4">
        <div class="card-body">
            <h3 class="h5 mb-4">DataForSEO</h3>
            
            <form method="POST" action="{{ route('api.settings.dataforseo.store') }}">
                @csrf
                <div class="container">
                    <div class="row mb-4">
                        <div class="col-12 col-sm-6 col-lg-4 mb-3">
                            <label for="api_dataforseo_username" class="form-label">
                                {{ __('API Username') }}
                            </label>
                            <input type="text" 
                                   name="api_dataforseo_username" 
                                   id="api_dataforseo_username" 
                                   class="form-control"
                                   value="{{ config('services.dataforseo.username') }}" 
                                   required>
                        </div>
                        
                        <div class="col-12 col-sm-6 col-lg-4 mb-3">
                            <label for="api_dataforseo_password" class="form-label">
                                {{ __('API Password') }}
                            </label>
                            <input type="password" 
                                   name="api_dataforseo_password" 
                                   id="api_key" 
                                   class="form-control"
                                   value="{{ config('services.dataforseo.password') }}" 
                                   required>
                        </div>
                        
                        <div class="col-12 col-sm-6 col-lg-4 mb-3">
                            <label class="form-label">
                                {{ __('Environment') }}
                            </label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" 
                                           name="api_dataforseo_environment" 
                                           id="sandbox"
                                           value="sandbox" 
                                           class="form-check-input"
                                           {{ config('services.dataforseo.environment') == 'sandbox' ? 'checked' : '' }} 
                                           required>
                                    <label class="form-check-label" for="sandbox">{{ __('Sandbox') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" 
                                           name="api_dataforseo_environment" 
                                           id="production"
                                           value="api" 
                                           class="form-check-input"
                                           {{ config('services.dataforseo.environment') == 'api' ? 'checked' : '' }} 
                                           required>
                                    <label class="form-check-label" for="production">{{ __('Production') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-dark text-uppercase">
                            {{ __('Submit') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>