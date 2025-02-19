<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormBuilder;
use Illuminate\Support\Facades\Auth;

class FormBuilderController extends Controller
{
    
    public function store(Request $request)
    {
        
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|in:notice,appointment',
            'is_public' => 'nullable|boolean',
            'status' => 'required|string|in:draft,published,archived',
            'fields' => 'nullable|array',
            'fields.*.label' => 'nullable|string|max:255',
            'fields.*.name' => 'nullable|string|max:255',
            'fields.*.type' => 'nullable|string|in:text,textarea,select,radio,checkbox,file,date,public-data',
            'fields.*.order' => 'nullable|integer',
            'fields.*.column_length' => 'required|in:20,25,50,75,100',
            'fields.*.options' => 'nullable|string',
            'fields.*.required' => 'nullable|boolean',
            'fields.*.value' => 'nullable|string',
        ]);

        // Salva il form nel database
        $form = FormBuilder::create([
            'user_id' => Auth::id(),
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'location' => $validatedData['location'],
            'is_public' => $validatedData['is_public'] ?? false, // Modifica qui per gestire l'array key undefined
            'status' => $validatedData['status'],
        ]);

        // Salva i campi del form solo se $validatedData['fields'] è stato impostato
        if (isset($validatedData['fields'])) {
            foreach ($validatedData['fields'] as $field) {
                $form->fields()->create([
                    'label' => $field['label'],
                    'name' => $field['name'],
                    'type' => $field['type'],
                    'order' => $field['order'],
                    'help_text' => $field['help_text'] ?? null,
                    'column_length' => $field['column_length'],
                    'options' => $field['options'] ?? null,
                    'required' => $field['required'] ?? false,
                ]);
            }
        }

        return redirect()->route('form.builder.list')->with('success', 'Form salvato con successo!');
    }

    public function new()
    {
        $user = Auth::user();
        $canAddForm = $user->canAddForm();
        $maxForms = $user->maxForms();
        $formsCount = $user->formsCount();
        
        return view('form.builder.new', compact('user', 'canAddForm', 'maxForms', 'formsCount'));
    }

    public function list()
    {
        $user = Auth::user();
        $forms = FormBuilder::where('user_id', $user->id)
            ->get();
        $canAddForm = $user->canAddForm();
        $maxForms = $user->maxForms();
        $formsCount = $user->formsCount();

        return view('form.list', compact('forms', 'user', 'canAddForm', 'maxForms', 'formsCount'));
    }

    public function edit($id)
    {
        $form = FormBuilder::findOrFail($id);
        return view('form.builder.edit', compact('form'));
    }   

    public function update(Request $request, $id)
    {
        //dd($request->location);
        //$is_public = $request->is_public ?? false; // Modifica qui per gestire l'array key undefined

        // Validazione dei dati in ingresso
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|in:notice,appointment',
            'is_public' => 'nullable|boolean',
            'status' => 'required|string|in:draft,published,archived',
            'fields' => 'nullable|array',
            'fields.*.label' => 'nullable|string|max:255',
            'fields.*.name' => 'nullable|string|max:255',
            'fields.*.type' => 'nullable|string|in:text,textarea,select,radio,checkbox,file,date,date-time,public-data',
            'fields.*.order' => 'nullable|integer',
            'fields.*.column_length' => 'required|in:20,25,50,75,100',
            'fields.*.help_text' => 'nullable|string',
            'fields.*.options' => 'nullable|string',
            'fields.*.required' => 'nullable|boolean',
        ]);
        
        // Trova il form esistente
        $form = FormBuilder::findOrFail($id);

        // Aggiorna il form nel database
        $form->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'location' => $validatedData['location'],
            'is_public' => $validatedData['is_public'] ?? false, // Modifica qui per gestire l'array key undefined
            'status' => $validatedData['status'],
        ]);

        // Aggiorna i campi del form
        $existingFields = $form->fields()->pluck('id')->toArray(); // Ottieni gli ID dei campi esistenti
        $newFieldIds = []; // Array per tenere traccia dei nuovi campi creati

        // Salva i campi del form solo se $validatedData['fields'] è stato impostato
        if (isset($validatedData['fields'])) {
            foreach ($validatedData['fields'] as $field) {
                $newField = $form->fields()->updateOrCreate(
                    ['name' => $field['name']], // Usa il nome del campo come identificatore
                    [
                        'label' => $field['label'],
                        'type' => $field['type'],
                        'order' => $field['order'],
                        'help_text' => $field['help_text'] ?? null,
                        'column_length' => $field['column_length'],
                        'options' => $field['options'] ?? null,
                        'required' => $field['required'] ?? false,
                    ]
                );

                $newFieldIds[] = $newField->id; // Aggiungi l'ID del nuovo campo all'array
            }
        }

        // Elimina i campi che non sono più presenti
        $fieldsToDelete = array_diff($existingFields, $newFieldIds);
        if (!empty($fieldsToDelete)) {
            $form->fields()->whereIn('id', $fieldsToDelete)->delete();
        }

        return redirect()->route('form.builder.list')->with('success', 'Form aggiornato con successo!');
    }

    public function destroy($id)
    {
        $form = FormBuilder::findOrFail($id);
        $form->fields()->delete(); // Elimina i campi associati al form
        $form->forceDelete(); // Rimuove fisicamente il form dal database
        return redirect()->route('form.builder.list')->with('success', 'Form eliminato con successo!');
    }

}
