@if(isset($messages['success']) || session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ isset($messages['success']) ? $messages['success'] : session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(isset($messages['error']) || session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ isset($messages['error']) ? $messages['error'] : session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif