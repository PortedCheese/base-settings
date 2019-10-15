@extends("admin.layout")

@section("page-title", "Settings - ")

@section('header-title')
    Settings
@endsection

@section('admin')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
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
            <div class="card-footer">
                <form action="{{ route("admin.settings.token", ['user' => $user]) }}" method="post">
                    @csrf
                    @method("put")
                    <div class="btn-group"
                         role="group">
                        <button type="submit" class="btn btn-warning">Обновить токен пользователя</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection