<x-app-layout>
    
    <x-slot name="header">
        {{ __('Imposta il Branch di Default') }}
    </x-slot>

<!-- Start Generation Here -->
<div class="content-page">
    <div class="row">
        <div class="col-12">
            @include('layouts.alert-message')
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('branch.default.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="branch_id" class="form-label">{{ __('Seleziona un Branch') }}</label>
                            <select id="branch_id" name="branch_id" class="form-select" required>
                                <option value="">{{ __('Seleziona un Branch') }}</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ $branch->default ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('Imposta come Default') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Generation Here -->    
    
</x-app-layout>
