@extends("admin.layout")

@section("page-title", "Редактировать {$role->title} - ")

@section('header-title', "Редактировать {$role->title}")

@section('admin')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route("admin.roles.update", ["role" => $role]) }}" method="post">
                    @csrf
                    @method("put")
                    <input type="hidden" name="is_default" value="{{ $role->default }}">

                    <div class="form-group">
                        <label for="title">Заголовок <span class="text-danger">*</span></label>
                        <input type="text"
                               id="title"
                               name="title"
                               required
                               value="{{ old("title", $role->title) }}"
                               class="form-control @error("title") is-invalid @enderror">
                        @error("title")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    @if (! $role->default)
                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   required
                                   value="{{ old("name", $role->name) }}"
                                   class="form-control @error("name") is-invalid @enderror">
                            @error("name")
                                <div class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    @endif

                    <div class="btn-group"
                         role="group">
                        <a href="{{ route("admin.roles.index") }}" class="btn btn-dark">Список</a>
                        <a href="{{ route("admin.roles.show", ['role' => $role]) }}" class="btn btn-secondary">Просмотр</a>
                        <button type="submit" class="btn btn-success">Обновить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection