<x-app-layout>
    
    <x-slot name="header">
        {{ __('Form Builder') }}
    </x-slot>

    <!-- Start Generation Here -->
    <div class="content-page mt-4">
        <div class="row">
            <div class="col-12">
                @include('layouts.alert-message')
                @if($canAddForm)
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('form.builder.store') }}" method="POST">
                                @csrf
                                
                                {{-- Sezione principale del form --}}
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <x-input-label for="title" :value="__('Titolo Form')" />
                                        <x-text-input type="text" id="title" name="title" :value="old('title')" required />
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-6">
                                        <x-input-label for="location" :value="__('Tipo')" />
                                        <select id="location" name="location" class="form-select" required>
                                            <option value="" disabled {{ old('location') ? '' : 'selected' }}>{{ __('Seleziona il tipo di utilizzo') }}</option>
                                            <option value="notice" {{ old('location') == 'notice' ? 'selected' : '' }}>{{ __('Segnalazione') }}</option>
                                            <option value="appointment" {{ old('location') == 'appointment' ? 'selected' : '' }}>{{ __('Appuntamento') }}</option>
                                        </select>
                                        @error('location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-12">
                                        <x-input-label for="description" :value="__('Descrizione')" />
                                        <textarea id="description" name="description" class="form-control">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-8">
                                        @can('view ownerdata')
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="is_public" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_public">{{ __('Pubblico') }}</label>
                                            </div>
                                        @endcan
                                    </div>
                                    <div class="col-md-4">
                                        <select name="status" id="status" class="form-select">
                                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>{{ __('Bozza') }}</option>
                                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>{{ __('Pubblicato') }}</option>
                                            <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>{{ __('Archiviato') }}</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Ripetitore per i campi del form --}}
                                <div class="form-fields-container">
                                    <h4 class="mb-3">{{ __('Campi del Form') }}</h4>
                                    <div class="fields-repeater">
                                        <template id="field-template">
                                            <div class="field-item border p-3 mb-3">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <x-input-label :value="__('Etichetta')" />
                                                        <x-text-input type="text" name="fields[%index%][label]" class="field-label" maxlength="255" required />
                                                    </div>
                                                    <div class="col-md-3">
                                                        <x-input-label :value="__('Tipo')" />
                                                        <select name="fields[%index%][type]" class="form-select field-type">
                                                            <option value="text">Text</option>
                                                            <option value="textarea">Textarea</option>
                                                            <option value="select">Select</option>
                                                            <option value="radio">Radio</option>
                                                            <option value="checkbox">Checkbox</option>
                                                            <option value="file">File</option>
                                                            <option value="date">Data</option>
                                                            <option value="date-time">Data e Ora</option>
                                                            <option value="public-data">Dati Utente Pubblici</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <x-input-label :value="__('Ordine')" />
                                                        <x-text-input type="number" name="fields[%index%][order]" class="field-order" value="0" />
                                                    </div>
                                                    <div class="col-md-3">
                                                        <x-input-label :value="__('Lunghezza')" />
                                                        <select name="fields[%index%][column_length]" class="form-select field-length">
                                                            <option value="100" selected>100</option>
                                                            <option value="75">75</option>
                                                            <option value="50">50</option>
                                                            <option value="25">25</option>
                                                            <option value="20">20</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-danger remove-field mt-4" onclick="removeField(this)">
                                                            <x-bi-trash />
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-md-6">
                                                        <x-input-label :value="__('Testo di aiuto')" />
                                                        <textarea name="fields[%index%][help_text]" class="form-control field-help"></textarea>
                                                    </div>
                                                    <div class="col-md-6 options-container" style="display: none;">
                                                        <x-input-label :value="__('Opzioni (una per riga)')" />
                                                        <textarea name="fields[%index%][options]" class="form-control field-options"></textarea>
                                                    </div>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" name="fields[%index%][required]" value="1">
                                                            <label class="form-check-label">{{ __('Campo obbligatorio') }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    <button type="button" class="btn btn-success add-field mb-4">
                                        {{ __('Aggiungi Campo') }}
                                    </button>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Salva Form') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    @if($maxForms > 0)
                        <div class="alert alert-danger">
                            <p>{{__('Non puoi creare più form. Hai raggiunto il limite massimo di form consentito con il tuo piano.')}}</p>
                            <a href="{{ route('plans') }}" class="btn btn-primary">{{__('Vai alla pagina dei piani')}}</a>
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <p>{{__('Il tuo piano non consente di creare form personalizzati.')}}</p>
                            <a href="{{ route('plans') }}" class="btn btn-primary">{{__('Vedi i piani disponibili')}}</a>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <!-- End Generation Here -->

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let fieldIndex = 0;
            const container = document.querySelector('.fields-repeater');
            const template = document.querySelector('#field-template');

            // Funzione per gestire la visualizzazione delle opzioni e dei campi
            function handleTypeChange(typeSelect) {
                const fieldItem = typeSelect.closest('.field-item');
                const optionsContainer = fieldItem.querySelector('.options-container');
                
                // Gestione options-container
                optionsContainer.style.display = 
                    ['select', 'radio', 'checkbox'].includes(typeSelect.value) ? 'block' : 'none';
                
                if (typeSelect.value === 'date') {
                    optionsContainer.style.display = 'none';
                }

                // Gestione campi per public-data
                const labelInput = fieldItem.querySelector('.field-label').closest('.col-md-3');
                const nameInput = fieldItem.querySelector('.field-name').closest('.col-md-3');
                const orderInput = fieldItem.querySelector('.field-order').closest('.col-md-2');
                const lengthInput = fieldItem.querySelector('.field-length').closest('.col-md-2');
                const helpText = fieldItem.querySelector('.field-help').closest('.col-md-5'); // Modificato per col-md-5
                const requiredCheckbox = fieldItem.querySelector('input[type="checkbox"]').closest('.col-md-6');
                const typeSelectCol = typeSelect.closest('.col-md-3');

                if (typeSelect.value === 'public-data') {
                    // Nascondi i campi non necessari
                    labelInput.style.display = 'none';
                    nameInput.style.display = 'none';
                    orderInput.style.display = 'none';
                    lengthInput.style.display = 'none';
                    helpText.style.display = 'none'; // Assicurati che il campo di aiuto sia nascosto
                    requiredCheckbox.style.display = 'none';
                    // Espandi la select a tutta la larghezza
                    if (typeSelectCol) {
                        typeSelectCol.className = 'col-md-11';
                    }
                } else {
                    // Mostra tutti i campi
                    labelInput.style.display = '';
                    nameInput.style.display = '';
                    orderInput.style.display = '';
                    lengthInput.style.display = '';
                    helpText.style.display = ''; // Assicurati che il campo di aiuto sia visibile
                    requiredCheckbox.style.display = '';
                    // Ripristina la larghezza originale della select
                    if (typeSelectCol) {
                        typeSelectCol.className = 'col-md-3';
                    }
                }
            }

            // Funzione per aggiungere un nuovo campo
            document.querySelector('.add-field').addEventListener('click', function() {
                // Clona il contenuto del template
                const clone = document.importNode(template.content, true);
                
                // Sostituisce tutti i placeholder %index% con l'indice corrente
                clone.querySelectorAll('input, select, textarea').forEach(element => {
                    if (element.name) {
                        element.name = element.name.replace(/%index%/g, fieldIndex);
                    }
                });

                // Aggiunge il nuovo campo al container
                container.appendChild(clone);

                // Gestione della visualizzazione delle opzioni
                const newFieldElement = container.lastElementChild;
                const typeSelect = newFieldElement.querySelector('.field-type');

                typeSelect.addEventListener('change', function() {
                    handleTypeChange(this);
                });

                // Gestione rimozione campo
                newFieldElement.querySelector('.remove-field').addEventListener('click', function() {
                    newFieldElement.remove();
                });

                // Incrementa l'indice per il prossimo campo
                fieldIndex++;
            });

            // Generazione automatica dello slug dal titolo
            const titleInput = document.querySelector('#title');

            titleInput.addEventListener('input', function() {
                // Slug generation logic removed as per instructions
            });
        });
    </script>
    @endpush

    
    
</x-app-layout>
