@if(session('danger'))
    <div class="alert alert-danger d-flex justify-content-between" role="alert">
        {{ session('danger') }}
        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success d-flex justify-content-between" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
@if(session('status'))
    <div class="alert alert-primary d-flex justify-content-between" role="alert">
        {{ session('status') }}
        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif