@extends("admin.layout")

@section("page-title", "Add settings - ")

@section('header-title')
    Add settings
@endsection

@section('admin')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route("admin.settings.store") }}" method="post">
                    @csrf

                    <div class="form-group">
                        <label for="title">Заголовок</label>
                        <input type="text"
                               id="title"
                               name="title"
                               value="{{ old('title') }}"
                               class="form-control @error("title") is-invalid @enderror">
                        @error("title")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               class="form-control @error("name") is-invalid @enderror">
                        @error("name")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="template">Шаблон</label>
                        <input type="text"
                               id="template"
                               name="template"
                               value="{{ old('template') }}"
                               class="form-control @error("template") is-invalid @enderror">
                        @error("template")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="btn-group"
                         role="group">
                        <a href="{{ route("admin.settings.index") }}" class="btn btn-dark">К списку</a>
                        <button type="submit" class="btn btn-success">Добавить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection