@props(['type' => 'success', 'message'])

@if ($message)
    <div class="alert alert-{{ $type }} alert-dismissible fade show mb-4" role="alert">
        <strong>{{ $type === 'danger' ? 'Si è verificato un errore durante l\'operazione.' : 'Operazione completata con successo.' }}</strong>
        <p class="mb-0 small">{{ $message }}</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('Chiudi') }}"></button>
    </div>
@endif