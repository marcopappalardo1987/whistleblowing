<x-app-layout>
    
    <x-slot name="header">
        {{ __('Impostazioni Whistleblowing Tool') }}
    </x-slot>

<!-- Start Generation Here -->
<div class="content-page">
    <div class="row">
        <div class="col-12">
            @include('layouts.alert-message')
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('company.settings.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-sm-4 mb-3">
                                <x-input-label for="slug" :value="__('Slug')" />
                                <x-text-input type="text"
                                             id="slug"
                                             name="slug"
                                             value="{{ old('slug', $settings->first()->slug ?? '') }}"
                                             />
                                @error('slug')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-sm-4 mb-3">
                                <x-input-label for="logo_url" :value="__('Carica Logo')" />
                                <x-text-input type="file"
                                             id="logo_url"
                                             name="logo_url"
                                            />
                                @error('logo_url')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-sm-2 mb-3">
                                @if(isset($settings) && $settings->first() && $settings->first()->logo_url)
                                    <div class="mt-2">
                                        <img src="{{ $settings->first()->logo_url }}" alt="{{ __('Logo') }}" style="max-width: 200px; display: block;">
                                    </div>
                                @endif
                            </div>

                            <div class="col-sm-2 d-flex align-items-end mb-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    {{ __('Salva') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Generation Here -->

@if(isset($settings) && $settings->first() && $settings->first()->slug)
        <div class="row mt-5">
            <div class="col-12">
                <h5>{{ __('Link alla tua pagina di Whistleblowing') }}</h5>
            <p class="mb-3">
                {{ __('Puoi visualizzare il tuo link dedicato al whistleblowing qui sotto.') }} {{ __('Clicca sul pulsante per aprirlo in una nuova finestra e copia il link per utilizzarlo nel tuo sito.') }}
                </p>
            </div>
        </div>

        @foreach($branches as $branch)
            <div class="row mb-5">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="font-weight-bold">{{ __('Branch') }} {{ $branch->name }}</h5>
                            <p class="mb-1">
                                <strong>{{ __('Link:') }}</strong> 
                        <a href="{{ route('page-whistleblowing', ['slug' => $settings->first()->slug, 'branch_id' => $branch->id]) }}" target="_blank" class="text-primary">
                            <small>{{ route('page-whistleblowing', ['slug' => $settings->first()->slug, 'branch_id' => $branch->id]) }}</small>
                        </a>
                            </p>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-secondary" onclick="copyToClipboard('{{ route('page-whistleblowing', ['slug' => $settings->first()->slug, 'branch_id' => $branch->id]) }}')">
                                {{ __('Copia Link') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="alert alert-warning mt-4">
        <h5>{{ __('Nessun link disponibile') }}</h5>
        <p>
            {{ __('Per ottenere il tuo link dedicato al whistleblowing, devi prima configurare uno slug nelle impostazioni qui sopra.') }}
        </p>
    </div>
@endif

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('{{ __('Link copiato negli appunti!') }}');
        }, function(err) {
            console.error('{{ __('Errore nella copia: ') }}', err);
        });
    }
</script>
</x-app-layout>
