<x-app-layout>
    <x-slot name="header">
        <h1 class="h3 mb-0">{{ __('Dati Aziendali') }}</h1>
    </x-slot>

    <div class="content-page py-4">
        <div class="row h-100">
            <div class="col-12">

                @if(session('warning'))
                    <div class="alert alert-warning">
                        {{ session('warning') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <form method="post" action="{{ route('company.update', ['user' => auth()->id()]) }}" class="mt-4">
                            @csrf
                            @method('patch')
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <x-input-label for="legal_name" :value="__('Nome Legale')" />
                                    <x-text-input id="legal_name" name="legal_name" type="text" class="form-control" :value="old('legal_name', $companyData->legal_name ?? '')" required />
                                    @error('legal_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <x-input-label for="vat_number" :value="__('Numero di Partita IVA')" />
                                    <x-text-input id="vat_number" name="vat_number" type="text" class="form-control" :value="old('vat_number', $companyData->vat_number ?? '')" required />
                                    @error('vat_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <x-input-label for="tax_code" :value="__('Codice Fiscale')" />
                                    <x-text-input id="tax_code" name="tax_code" type="text" class="form-control" :value="old('tax_code', $companyData->tax_code ?? '')" />
                                    @error('tax_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <x-input-label for="sdi_code" :value="__('Codice SDI')" />
                                    <x-text-input id="sdi_code" name="sdi_code" type="text" class="form-control" :value="old('sdi_code', $companyData->sdi_code ?? '')" />
                                    @error('sdi_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <x-input-label for="full_address" :value="__('Indirizzo Completo')" />
                                    <x-text-input id="full_address" name="full_address" type="text" class="form-control" :value="old('full_address', $companyData->full_address ?? '')" required />
                                    @error('full_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <x-input-label for="country" :value="__('Paese')" />
                                    <x-text-input id="country" name="country" type="text" class="form-control" :value="old('country', $companyData->country ?? '')" />
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <x-input-label for="rea_number" :value="__('Numero REA')" />
                                    <x-text-input id="rea_number" name="rea_number" type="text" class="form-control" :value="old('rea_number', $companyData->rea_number ?? '')" />
                                    @error('rea_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <x-input-label for="registration_number" :value="__('Numero di Registrazione')" />
                                    <x-text-input id="registration_number" name="registration_number" type="text" class="form-control" :value="old('registration_number', $companyData->registration_number ?? '')" />
                                    @error('registration_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" name="email" type="email" class="form-control" :value="old('email', $companyData->email ?? '')" />
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <x-input-label for="phone_number" :value="__('Numero di Telefono')" />
                                    <x-text-input id="phone_number" name="phone_number" type="text" class="form-control" :value="old('phone_number', $companyData->phone_number ?? '')" />
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <x-input-label for="administrative_contact" :value="__('Contatto Amministrativo')" />
                                    <x-text-input id="administrative_contact" name="administrative_contact" type="text" class="form-control" :value="old('administrative_contact', $companyData->administrative_contact ?? '')" />
                                    @error('administrative_contact')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <x-input-label for="iban" :value="__('IBAN')" />
                                    <x-text-input id="iban" name="iban" type="text" class="form-control" :value="old('iban', $companyData->iban ?? '')" />
                                    @error('iban')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <x-input-label for="bank_name" :value="__('Nome della Banca')" />
                                    <x-text-input id="bank_name" name="bank_name" type="text" class="form-control" :value="old('bank_name', $companyData->bank_name ?? '')" />
                                    @error('bank_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" id="terms_conditions" name="terms_conditions" class="form-check-input @error('terms_conditions') is-invalid @enderror" value="1" {{ old('terms_conditions', $companyData->terms_conditions ?? '') ? 'checked' : '' }} required>
                                <label class="form-check-label" for="terms_conditions">
                                    {{ __('Accetto i termini e le condizioni') }}
                                </label>
                                @error('terms_conditions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" id="data_processing_consent" name="data_processing_consent" class="form-check-input @error('data_processing_consent') is-invalid @enderror" value="1" {{ old('data_processing_consent', $companyData->data_processing_consent ?? '') ? 'checked' : '' }}>
                                <label class="form-check-label" for="data_processing_consent">
                                    {{ __('Acconsento al trattamento dei dati') }}
                                </label>
                                @error('data_processing_consent')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex align-items-center">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Salva') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>