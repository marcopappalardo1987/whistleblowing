@if(isset($messages['owner_error']) || session()->has('owner_error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ isset($messages['owner_error']) ? $messages['owner_error'] : session('owner_error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(isset($messages['owner_success']) || session()->has('owner_success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ isset($messages['owner_success']) ? $messages['owner_success'] : session('owner_success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif