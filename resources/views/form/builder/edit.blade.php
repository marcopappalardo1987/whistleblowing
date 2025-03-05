<x-app-layout>
    
    <x-slot name="header">
        {{ __('Modifica Form Builder') }}
    </x-slot>

    <!-- Start Generation Here -->
    <div class="content-page mt-4">
        <div class="row">
            <div class="col-12">
                @include('layouts.alert-message')
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('form.builder.update', $form->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            {{-- Sezione principale del form --}}
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <x-input-label for="title" :value="__('Titolo Form')" />
                                    <x-text-input type="text" id="title" name="title" :value="old('title', $form->title)" required />
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-6">
                                    <x-input-label for="location" :value="__('Tipo')" />
                                    <select id="location" name="location" class="form-select" required>
                                        <option value="" disabled {{ old('location') ? '' : 'selected' }}>{{ __('Seleziona il tipo di utilizzo') }}</option>
                                        <option value="notice" {{ old('location', $form->location) == 'notice' ? 'selected' : '' }}>{{ __('Segnalazione') }}</option>
                                        <option value="appointment" {{ old('location', $form->location) == 'appointment' ? 'selected' : '' }}>{{ __('Appuntamento') }}</option>
                                    </select>
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <x-input-label for="description" :value="__('Descrizione')" />
                                    <textarea id="description" name="description" class="form-control">{{ old('description', $form->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-8">
                                    @can('view ownerdata')
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="is_public" name="is_public" value="1" {{ old('is_public', $form->is_public) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_public">{{ __('Pubblico') }}</label>
                                        </div>
                                    @endcan
                                </div>
                                <div class="col-md-4">
                                    <select name="status" id="status" class="form-select">
                                        <option value="draft" {{ old('status', $form->status) == 'draft' ? 'selected' : '' }}>{{ __('Bozza') }}</option>
                                        <option value="published" {{ old('status', $form->status) == 'published' ? 'selected' : '' }}>{{ __('Pubblicato') }}</option>
                                        <option value="archived" {{ old('status', $form->status) == 'archived' ? 'selected' : '' }}>{{ __('Archiviato') }}</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Ripetitore per i campi del form --}}
                            <div class="form-fields-container">
                                <h4 class="mb-3">{{ __('Campi del Form') }}</h4>
                                <div class="fields-repeater">
                                    {{-- <template id="field-template">
                                        <div class="field-item border p-3 mb-3">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <x-input-label :value="__('Etichetta')" />
                                                    <x-text-input type="text" name="fields[%index%][label]" class="field-label" maxlength="255" required />
                                                </div>
                                                <div class="col-md-3">
                                                    <x-input-label :value="__('Tipo')" />
                                                    <select name="fields[%index%][type]" class="form-select field-type">
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
                                                <div class="col-md-2">
                                                    <x-input-label :value="__('Ordine')" />
                                                    <x-text-input type="number" name="fields[%index%][order]" class="field-order" value="0" />
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
                                    </template> --}}
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
                                                <div class="col-md-2">
                                                    <x-input-label :value="__('Ordine')" />
                                                    <x-text-input type="number" name="fields[%index%][order]" class="field-order" value="0" />
                                                </div>
                                                <div class="col-md-3">
                                                    <x-input-label :value="__('Lunghezza')" />
                                                    <select name="fields[%index%][column_length]" class="form-select field-length">
                                                        <option value="100" selected>{{ __('100') }}</option>
                                                        <option value="75">{{ __('75') }}</option>
                                                        <option value="50">{{ __('50') }}</option>
                                                        <option value="25">{{ __('25') }}</option>
                                                        <option value="20">{{ __('20') }}</option>
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
                                    @foreach($form->fields as $index => $field)
                                        <div class="field-item border p-3 mb-3">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <x-input-label :value="__('Etichetta')" />
                                                    <x-text-input type="text" name="fields[{{ $index }}][label]" class="field-label" value="{{ old('fields.'.$index.'.label', $field->label) }}" maxlength="255" required />
                                                </div>
                                                <div class="col-md-3">
                                                    <x-input-label :value="__('Tipo')" />
                                                    <select name="fields[{{ $index }}][type]" class="form-select field-type">
                                                        <option value="text" {{ old('fields.'.$index.'.type', $field->type) == 'text' ? 'selected' : '' }}>{{ __('Text') }}</option>
                                                        <option value="textarea" {{ old('fields.'.$index.'.type', $field->type) == 'textarea' ? 'selected' : '' }}>{{ __('Textarea') }}</option>
                                                        <option value="select" {{ old('fields.'.$index.'.type', $field->type) == 'select' ? 'selected' : '' }}>{{ __('Select') }}</option>
                                                        <option value="radio" {{ old('fields.'.$index.'.type', $field->type) == 'radio' ? 'selected' : '' }}>{{ __('Radio') }}</option>
                                                        <option value="checkbox" {{ old('fields.'.$index.'.type', $field->type) == 'checkbox' ? 'selected' : '' }}>{{ __('Checkbox') }}</option>
                                                        <option value="file" {{ old('fields.'.$index.'.type', $field->type) == 'file' ? 'selected' : '' }}>{{ __('File') }}</option>
                                                        <option value="date" {{ old('fields.'.$index.'.type', $field->type) == 'date' ? 'selected' : '' }}>{{ __('Data') }}</option>
                                                        <option value="date-time" {{ old('fields.'.$index.'.type', $field->type) == 'date-time' ? 'selected' : '' }}>{{ __('Data e Ora') }}</option>
                                                        <option value="public-data" {{ old('fields.'.$index.'.type', $field->type) == 'public-data' ? 'selected' : '' }}>{{ __('Dati Utente Pubblici') }}</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <x-input-label :value="__('Ordine')" />
                                                    <x-text-input type="number" name="fields[{{ $index }}][order]" class="field-order" value="{{ old('fields.'.$index.'.order', $field->order) }}" />
                                                </div>
                                                <div class="col-md-3">
                                                    <x-input-label :value="__('Lunghezza')" />
                                                    <select name="fields[{{ $index }}][column_length]" class="form-select field-length">
                                                        <option value="100" {{ old('fields.'.$index.'.column_length', $field->column_length ?? 100) == '100' ? 'selected' : '' }}>100</option>
                                                        <option value="75" {{ old('fields.'.$index.'.column_length', $field->column_length ?? 100) == '75' ? 'selected' : '' }}>75</option>
                                                        <option value="50" {{ old('fields.'.$index.'.column_length', $field->column_length ?? 100) == '50' ? 'selected' : '' }}>50</option>
                                                        <option value="25" {{ old('fields.'.$index.'.column_length', $field->column_length ?? 100) == '25' ? 'selected' : '' }}>25</option>
                                                        <option value="20" {{ old('fields.'.$index.'.column_length', $field->column_length ?? 100) == '20' ? 'selected' : '' }}>20</option>
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
                                                    <textarea name="fields[{{ $index }}][help_text]" class="form-control field-help">{{ old('fields.'.$index.'.help_text', $field->help_text) }}</textarea>
                                                </div>
                                                <div class="col-md-6 options-container" style="display: {{ in_array($field->type, ['select', 'radio', 'checkbox']) ? 'block' : 'none' }};">
                                                    <x-input-label :value="__('Opzioni (una per riga)')" />
                                                    <textarea name="fields[{{ $index }}][options]" class="form-control field-options">{{ old('fields.'.$index.'.options', $field->options) }}</textarea>
                                                </div>

                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" name="fields[{{ $index }}][required]" value="1" {{ old('fields.'.$index.'.required', $field->required) ? 'checked' : '' }}>
                                                        <label class="form-check-label">{{ __('Campo obbligatorio') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
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
            </div>
        </div>
    </div>
    <!-- End Generation Here -->

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let fieldIndex = {{ count($form->fields) }};
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
                /* const nameInput = fieldItem.querySelector('.field-name').closest('.col-md-3'); */
                const orderInput = fieldItem.querySelector('.field-order').closest('.col-md-2');
                const helpText = fieldItem.querySelector('.field-help')?.closest('.col-md-5');
                if (helpText) {
                    helpText.style.display = 'none';
                }
                const lengthInput = fieldItem.querySelector('.field-length')?.closest('.col-md-2');
                if (lengthInput) {
                    lengthInput.style.display = 'none';
                }
                const requiredCheckbox = fieldItem.querySelector('input[type="checkbox"]').closest('.col-md-6');
                const typeSelectCol = typeSelect.closest('.col-md-3, .col-md-11'); // Cerca entrambe le classi

                if (typeSelect.value === 'public-data') {
                    // Nascondi i campi non necessari
                    labelInput.style.display = 'none';
                    nameInput.style.display = 'none';
                    orderInput.style.display = 'none';
                    helpText.style.display = 'none';
                    requiredCheckbox.style.display = 'none';
                    // Espandi la select a tutta la larghezza
                    if (typeSelectCol) {
                        typeSelectCol.className = 'col-md-11';
                    }
                } else {
                    // Mostra tutti i campi
                    labelInput.style.display = '';
                    /* nameInput.style.display = ''; */
                    orderInput.style.display = '';
                    /* helpText.style.display = ''; */
                    requiredCheckbox.style.display = '';
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
    </script>
    @endpush

    
    
</x-app-layout>
