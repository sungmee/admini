{{-- primary / secondary / success / danger / warning / info / light / dark --}}

@if (request()->session()->has('alert')) {
<div class="alert alert-{{ session('alert-contextual') ?? 'primary' }} alert-dismissible fade show" role="alert">
    {{ session('alert') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif