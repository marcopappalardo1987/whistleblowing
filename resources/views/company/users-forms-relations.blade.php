<x-app-layout>
    
    <x-slot name="header">
        {{ __('Form associati') }}
    </x-slot>

    <div class="content-page mt-4">
        <div class="row">
            <div class="col-12">
                @include('layouts.alert-message')
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <form action="{{-- {{ route('company.forms.store') }} --}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <x-input-label for="wb_form_builder_id" :value="__('Form per le segnalazioni')" />
                            <select id="wb_form_builder_id" name="wb_form_notice_id" required class="form-select">
                                <option value="" disabled>{{ __('Seleziona un form') }}</option>
                                @foreach($forms_notice as $form)
                                    <option value="{{ $form->id }}" {{ old('wb_form_notice_id', $selectedNoticeId) == $form->id ? 'selected' : '' }}>{{ $form->title }}</option>
                                @endforeach
                            </select>
                            @error('wb_form_notice_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-sm-6 mb-3">
                            <x-input-label for="wb_form_appointment_id" :value="__('Form per gli appuntamenti')" />
                            <select id="wb_form_appointment_id" name="wb_form_appointment_id" required class="form-select">
                                <option value="" disabled selected>{{ __('Seleziona un form') }}</option>
                                @foreach($forms_appointment as $form)
                                    <option value="{{ $form->id }}" {{ old('wb_form_appointment_id', $selectedAppointmentId) == $form->id ? 'selected' : '' }}>{{ $form->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="d-flex align-items-end mb-3">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Salva') }}
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <small>*{{ __('Se non hai un form per le segnalazioni o per gli appuntamenti, puoi crearlo nella sezione "Form", "Nuovo form"') }} {{ __('o') }} <a class="text-decoration-none" href="{{ route('form.builder.new') }}">{{ __('clicca qui') }}</a> {{ __('per crearlo') }}</small>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>