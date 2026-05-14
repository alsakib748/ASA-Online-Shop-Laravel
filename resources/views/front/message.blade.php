@if (Session::has('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius: var(--radius-lg);">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <strong><i class="fas fa-exclamation-circle me-2"></i>Error!</strong> {{ Session::get('error') }}
    </div>
@endif

@if(Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius: var(--radius-lg);">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <strong><i class="fas fa-check-circle me-2"></i>Success!</strong> {{ Session::get('success') }}
    </div>
@endif