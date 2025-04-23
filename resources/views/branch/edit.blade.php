<x-app-layout>
    
    <x-slot name="header">
        {{ __('Modifica il Branch') }}
    </x-slot>

<!-- Start Generation Here -->
<div class="content-page">
    <div class="row">
        <div class="col-12">
            @include('layouts.alert-message')
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('branch.update', $branch->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3 align-items-end">
                            <div class="col-sm-12 mb-3">
                                <x-input-label for="branch_name" :value="__('Nome del Branch')" />
                                <x-text-input type="text" id="branch_name" name="branch_name" required class="form-control d-inline" value="{{ $branch->name }}" />
                                @error('branch_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Modifica Branch') }}
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
    
</x-app-layout>
