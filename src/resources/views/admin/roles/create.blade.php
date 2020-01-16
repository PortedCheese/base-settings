@extends("admin.layout")

@section("page-title", "Добавить роль - ")

@section('header-title', "Добавить роль")

@section('admin')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route("admin.roles.store") }}" method="post">
                    @csrf
                    <input type="hidden" value="0" name="default">

                    <div class="form-group">
                        <label for="title">Заголовок <span class="text-danger">*</span></label>
                        <input type="text"
                               id="title"
                               name="title"
                               required
                               value="{{ old('title') }}"
                               class="form-control @error("title") is-invalid @enderror">
                        @error("title")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text"
                               id="name"
                               name="name"
                               required
                               value="{{ old('name') }}"
                               class="form-control @error("name") is-invalid @enderror">
                        @error("name")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="btn-group"
                         role="group">
                        <button type="submit" class="btn btn-success">Добавить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection