@extends("admin.layout")

@section("page-title", "Edit setting - ")

@section('header-title')
    Edit setting
@endsection

@section('admin')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route("admin.settings.update", ['setting' => $config]) }}" method="post">
                    @csrf
                    @method("put")

                    <div class="form-group">
                        <label for="title">Заголовок</label>
                        <input type="text"
                               id="title"
                               name="title"
                               value="{{ old("title", $config->title) }}"
                               class="form-control @error("title") is-invalid @enderror">
                        @error("title")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        @if ($config->package)
                            <input type="hidden" name="template" value="{{ $config->template }}">
                        @endif
                        <label for="template">Шаблон</label>
                        <input type="text"
                               id="template"
                               name="template"
                               {{ $config->package ? "disabled" : "" }}
                               value="{{ old("template", $config->template) }}"
                               class="form-control @error("template") is-invalid @enderror">
                        @error("template")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    @includeIf($config->template, ['config' => $config])

                    <div class="btn-group"
                         role="group">
                        <a href="{{ route("admin.settings.index") }}" class="btn btn-dark">К списку</a>
                        <button type="submit" class="btn btn-success">Обновить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection