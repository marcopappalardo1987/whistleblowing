<x-app-layout>
    
    <x-slot name="header">
        {{ __('Modifica commissione') }}
    </x-slot>

    @include('layouts.alert-message')

    <div class="content-page">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{-- <h3 class="card-title mb-3">{{ __('Prodotti') }}</h3> --}}
                        <form action="{{ route('affiliate.settings.commissions.store') }}" method="POST">
                            @csrf
                            
                            <div class="row d-none d-sm-flex">
                                <div class="col-sm-5">
                                    <x-input-label for="level" :value="__('Livello')" />
                                    <x-text-input type="number"
                                                 id="level"
                                                 name="level"
                                                 min="0"
                                                 value="{{ isset($affiliateCommission) ? $affiliateCommission->level : '' }}"
                                                 required />
                                    @error('level')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-5">
                                    <x-input-label for="commission" :value="__('Commissione')" />
                                    <x-text-input type="number"
                                                 id="commission"
                                                 name="commission"
                                                 min="0"
                                                 step="0.01"
                                                 value="{{ isset($affiliateCommission) ? $affiliateCommission->commission : '' }}"
                                                 required />
                                    @error('commission')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-2 d-flex align-items-end">
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

</x-app-layout>