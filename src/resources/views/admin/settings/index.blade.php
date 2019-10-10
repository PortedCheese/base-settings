@extends("admin.layout")

@section("page-title", "Settings - ")

@section('header-title')
    Settings
@endsection

@section('admin')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
{{--                <form action="{{ route("admin.settings.favicon") }}" method="post" enctype="multipart/form-data">--}}
{{--                    @csrf--}}
{{--                    @method("put")--}}
{{--                    <div class="form-group">--}}
{{--                        <div class="custom-file">--}}
{{--                            <input type="file"--}}
{{--                                   class="custom-file-input{{ $errors->has('favicon') ? ' is-invalid' : '' }}"--}}
{{--                                   id="custom-file-input"--}}
{{--                                   lang="ru"--}}
{{--                                   name="favicon"--}}
{{--                                   aria-describedby="inputGroupFavicon">--}}
{{--                            <label class="custom-file-label"--}}
{{--                                   for="custom-file-input">--}}
{{--                                Выберите файл иконки--}}
{{--                            </label>--}}
{{--                            @if ($errors->has('favicon'))--}}
{{--                                <div class="invalid-feedback">--}}
{{--                                    <strong>{{ $errors->first('favicon') }}</strong>--}}
{{--                                </div>--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="btn-group"--}}
{{--                         role="group">--}}
{{--                        <button type="submit" class="btn btn-primary">Обновить иконку</button>--}}
{{--                        <a href="{{ route("admin.settings.create") }}" class="btn btn-success">Добавить конфиг</a>--}}
{{--                    </div>--}}
{{--                </form>--}}
                <a href="{{ route("admin.settings.create") }}" class="btn btn-success">Добавить конфиг</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Заголовок</th>
                            <th>Name</th>
                            <th>Template</th>
                            <th>Из пакета</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($settings as $setting)
                            <tr>
                                <td>{{ $setting->title }}</td>
                                <td>{{ $setting->name }}</td>
                                <td>{{ $setting->template }}</td>
                                <td>{{ $setting->package ? "Да" : "Нет" }}</td>
                                <td>
                                    <div role="toolbar" class="btn-toolbar">
                                        <div class="btn-group btn-group-sm mr-1">
                                            <a href="{{ route("admin.settings.edit", ["setting" => $setting]) }}" class="btn btn-primary">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" data-confirm="{{ "delete-form-{$setting->id}" }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <confirm-form :id="'{{ "delete-form-{$setting->id}" }}'">
                                        <template>
                                            <form action="{{ route('admin.settings.destroy', ['setting' => $setting]) }}"
                                                  id="delete-form-{{ $setting->id }}"
                                                  class="btn-group"
                                                  method="post">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE">
                                            </form>
                                        </template>
                                    </confirm-form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection