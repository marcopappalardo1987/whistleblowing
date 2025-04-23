<x-app-layout>
    
    <x-slot name="header">
        {{ __('Form Builder') }}
    </x-slot>

    <!-- Start Generation Here -->
    <div class="content-page">
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
                                        <x-input-label for="location" :value="__('Tipologia di utilizzo')" />
                                        <div type="button" class="btn tooltip-btn" data-toggle="tooltip" data-placement="top" title="{{__('Puoi creare due tipologia di form, uno per la richiesta degli appuntamenti e uno per l\'invio delle segnalazioni')}}">?</div>
                                        <select id="location" name="location" class="form-select" required>
                                            <option value="" disabled {{ old('location') ? '' : 'selected' }}>{{ __('Seleziona il tipo di utilizzo') }}</option>
                                            <option value="notice" {{ old('location') == 'notice' ? 'selected' : '' }}>{{ __('Per la gestione delle segnalazioni') }}</option>
                                            <option value="appointment" {{ old('location') == 'appointment' ? 'selected' : '' }}>{{ __('Per la gestione degli appuntamenti') }}</option>
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
                                                        <x-input-label :value="__('Nome del campo')" />
                                                        <div type="button" class="btn tooltip-btn" data-toggle="tooltip" data-placement="top" title="{{__('Questo è il nome del campo che apparirà al segnalatore durante la compilazione del form')}}">?</div>
                                                        <x-text-input type="text" name="fields[%index%][label]" class="field-label etichetta" maxlength="255" required />
                                                    </div>
                                                    <div class="col-md-3">
                                                        <x-input-label :value="__('Tipo di campo')" />
                                                        <select name="fields[%index%][type]" class="form-select field-type tipo-campo">
                                                            <option value="text">{{ __('Text') }}</option>
                                                            <option value="textarea">{{ __('Textarea') }}</option>
                                                            <option value="select">{{ __('Select') }}</option>
                                                            <option value="radio">{{ __('Radio') }}</option>
                                                            <option value="checkbox">{{ __('Checkbox') }}</option>
                                                            <option value="file">{{ __('File') }}</option>
                                                            <option value="date">{{ __('Data') }}</option>
                                                            <option value="date-time">{{ __('Data e Ora') }}</option>
                                                            <option value="public-data">{{ __('Dati Utente Pubblici') }}</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <x-input-label :value="__('Ordine di visualizzazione')" />
                                                        <div type="button" class="btn tooltip-btn" data-toggle="tooltip" data-placement="top" title="{{__('Questo campo determina l\'ordine di visualizzazione dei campi del form')}}">?</div>
                                                        <x-text-input type="number" name="fields[%index%][order]" class="field-order" value="0" />
                                                    </div>
                                                    <div class="col-md-2">
                                                        <x-input-label :value="__('Lunghezza della colonna')" />
                                                        <select name="fields[%index%][column_length]" class="form-select field-length">
                                                            <option value="100" selected>{{ __('100') }}%</option>
                                                            <option value="75">{{ __('75') }}%</option>
                                                            <option value="50">{{ __('50') }}%</option>
                                                            <option value="25">{{ __('25') }}%</option>
                                                            <option value="20">{{ __('20') }}%</option>
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
                                                        <div type="button" class="btn tooltip-btn" data-toggle="tooltip" data-placement="top" title="{{__('Questo è il testo di aiuto che apparirà al segnalatore durante la compilazione del form')}}">?</div>
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
                                                            <label class="form-check-label">{{ __('Rendi questo campo obbligatorio da compilare quando un utente compila i dati') }}</label>
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
                            <p>{{ __('Non puoi creare più form. Hai raggiunto il limite massimo di form consentito con il tuo piano.') }}</p>
                            <a href="{{ route(app()->getLocale() . '.plans', ['locale' => app()->getLocale()]) }}" class="btn btn-primary">{{ __('Vai alla pagina dei piani') }}</a>
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <p>{{ __('Il tuo piano non consente di creare form personalizzati.') }}</p>
                            <a href="{{ route(app()->getLocale() . '.plans', ['locale' => app()->getLocale()]) }}" class="btn btn-primary">{{ __('Vedi i piani disponibili') }}</a>
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
                const orderInput = fieldItem.querySelector('.field-order').closest('.col-md-3');
                const helpText = fieldItem.querySelector('.field-help').closest('.col-md-6');
                const lengthInput = fieldItem.querySelector('.field-length').closest('.col-md-2');
                const requiredCheckbox = fieldItem.querySelector('input[type="checkbox"]').closest('.col-md-6');
                const requiredLabel = fieldItem.querySelector('.field-label').closest('.col-md-3');
                const typeSelectCol = typeSelect.closest('.col-md-3, .col-md-8'); // Cerca entrambe le classi

                if (typeSelect.value === 'public-data') {
                    // Nascondi i campi non necessari
                    /* labelInput.style.display = 'none'; */
                    orderInput.style.display = 'none';
                    lengthInput.style.display = 'none';
                    helpText.style.display = 'none';
                    requiredCheckbox.style.display = 'none';
                    // Rimuove l'attributo required dal campo label quando il tipo è public-data
                    const labelInput = fieldItem.querySelector('.field-label');
                    if (labelInput) {
                        labelInput.removeAttribute('required');
                    }
                    // Espandi la select a tutta la larghezza
                    if (typeSelectCol) {
                        typeSelectCol.className = 'col-md-8';
                    }
                } else {
                    // Mostra tutti i campi
                    /* labelInput.style.display = ''; */
                    orderInput.style.display = '';
                    lengthInput.style.display = '';
                    helpText.style.display = '';
                    requiredCheckbox.style.display = '';
                    requiredLabel.setAttribute('required', 'required');
                    // Ripristina la larghezza originale della select
                    if (typeSelectCol) {
                        typeSelectCol.className = 'col-md-3';
                    }
                }
            }

            // Aggiungi l'evento change ai campi esistenti
            document.querySelectorAll('.field-type').forEach(select => {
                select.addEventListener('change', function() {
                    handleTypeChange(this);
                });
                // Trigger iniziale per impostare lo stato corretto
                handleTypeChange(select);
            });

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

            // Funzione per rimuovere un campo
            window.removeField = function(button) {
                const fieldItem = button.closest('.field-item');
                if (fieldItem) {
                    fieldItem.remove();
                }
            };

            // Generazione automatica dello slug dal titolo
            const titleInput = document.querySelector('#title');

            titleInput.addEventListener('input', function() {
                // Slug generation logic removed as slug field is eliminated
            });
            
        });
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
    @endpush

    
    
</x-app-layout>
