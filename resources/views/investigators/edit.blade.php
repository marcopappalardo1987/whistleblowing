<!-- Start Generation Here -->
<x-app-layout>
    
    <x-slot name="header">
        {{ __('Modifica Investigatore') }}
    </x-slot>

    @include('layouts.alert-message')  

    <div class="card">
        <div class="card-body">
            <div class="container-fluid">
                <div class="row">
                    <form action="{{ route('investigator.update', $investigator->id) }}" method="POST" class="mt-4">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="name" class="form-label">{{ __('Nome') }}</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ $investigator->name }}" required disabled>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ $investigator->email }}" required disabled>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="branch_id" class="form-label">{{ __('Branch') }}</label>
                                <select name="branch_id" id="branch_id" class="form-control">
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ $branch->id == $investigator->branch_id ? 'selected' : '' }}>{{ $branch->name }}</option>  
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('Salva') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>
<!-- End Generation Here -->
