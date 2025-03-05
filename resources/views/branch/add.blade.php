<x-app-layout>
    
    <x-slot name="header">
        {{ __('Aggiungi un Branch') }}
    </x-slot>

<!-- Start Generation Here -->
<div class="content-page mt-4">
    <div class="row">
        <div class="col-12">
            @include('layouts.alert-message')
            @if($canAddBranch)
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('branch.form.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3 align-items-end">
                                <div class="col-sm-12 mb-3">
                                    <x-input-label for="branch_name" :value="__('Nome del Branch')" />
                                    <x-text-input type="text" id="branch_name" name="branch_name" required class="form-control d-inline" />
                                    @error('branch_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Aggiungi Branch') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                @if($maxBranches - $branchesCount <= 0)
                    <div class="alert alert-danger">
                        <p>{{ __('Hai raggiunto il numero massimo di branch consentito.') }}</p>
                        <a href="{{ route(app()->getLocale() . '.plans', ['locale' => app()->getLocale()]) }}" class="btn btn-primary">{{ __('Vedi i piani disponibili') }}</a>
                    </div>
                @else
                    <div class="alert alert-danger">
                        <p>{{ __('Il tuo piano non consente di creare branch.') }}</p>
                        <a href="{{ route(app()->getLocale() . '.plans', ['locale' => app()->getLocale()]) }}" class="btn btn-primary">{{ __('Vedi i piani disponibili') }}</a>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
<!-- End Generation Here -->    
    
</x-app-layout>
