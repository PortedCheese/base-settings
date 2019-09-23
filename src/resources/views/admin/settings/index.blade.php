@extends("admin.layout")

@section("page-title", "Settings - ")

@section('header-title')
    Settings
@endsection

@section('admin')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route("admin.settings.create") }}" class="btn btn-success">Добавить</a>
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
                                    <confirm-delete-model-button model-id="{{ $setting->id }}">
                                        <template slot="edit">
                                            <a href="{{ route('admin.settings.edit', ['setting' => $setting]) }}" class="btn btn-primary">
                                                <i class="far fa-edit"></i>
                                            </a>
                                        </template>
                                        <template slot="delete">
                                            <form action="{{ route('admin.settings.destroy', ['setting' => $setting]) }}"
                                                  id="delete-{{ $setting->id }}"
                                                  class="btn-group"
                                                  method="post">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE">
                                            </form>
                                        </template>
                                    </confirm-delete-model-button>
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