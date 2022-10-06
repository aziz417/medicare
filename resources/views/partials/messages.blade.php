{{-- @if ($errors->any())
    @foreach ($errors->all() as $error)
    <div class="alert alert-danger">
        {{ $error }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
    @endforeach
@endif --}}

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true" aria-label="Close"><span class="icofont-close-line"></span></button>
    </div>
@endif

@if (session('info'))
    <div class="alert alert-info alert-dismissible fade show mb-2" role="alert">
        {{ session('info') }}
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true" aria-label="Close"><span class="icofont-close-line"></span></button>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show mb-2" role="alert">
        {{ session('warning') }}
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true" aria-label="Close"><span class="icofont-close-line"></span></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true" aria-label="Close"><span class="icofont-close-line"></span></button>
    </div>
@endif

@if (session('status'))
    <div class="alert alert-info alert-dismissible fade show mb-2" role="alert">
        {{ session('status') }}
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true" aria-label="Close"><span class="icofont-close-line"></span></button>
    </div>
@endif