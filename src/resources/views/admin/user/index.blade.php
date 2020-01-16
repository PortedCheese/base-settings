@extends('admin.layout')

@section('header-title', 'Пользователи')

@section('admin')
    @include("base-settings::admin.user.search-form")
    <div class="col-12">
        <div class="card">
            @can("create", \App\User::class)
                <div class="card-header">
                    <a href="{{ route("admin.users.create") }}" class="btn btn-success">Добавить</a>
                </div>
            @endcan
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>E-mail</th>
                            <th>ФИО</th>
                            <th>Роли</th>
                            <th>Подтвержден</th>
                            @canany(["update", "delete"], \App\User::class)
                                <th>Действия</th>
                            @endcanany
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    {{ $page * $per + $loop->iteration }}
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->full_name }}</td>
                                <td>
                                    <ul class="list-unstyled">
                                        @foreach($user->roles as $role)
                                            <li>
                                                {{ empty($role->title) ? $role->name : $role->title }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>{{ $user->verified }}</td>
                                @canany(["update", "delete"], \App\User::class)
                                    <td class="text-center">
                                        <div role="toolbar" class="btn-toolbar">
                                            <div class="btn-group btn-group-sm mr-1">
                                                @can("update", \App\User::class)
                                                    <a href="{{ route("admin.users.edit", ["user" => $user]) }}" class="btn btn-primary">
                                                        <i class="far fa-edit"></i>
                                                    </a>
                                                @endcan
                                                @can("delete", \App\User::class)
                                                    <button type="button" class="btn btn-danger" data-confirm="{{ "delete-form-{$user->id}" }}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                @endcan
                                            </div>

                                            @can("settings-management")
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-warning" data-confirm="{{ "user-link-form-{$user->id}" }}">
                                                        <i class="fas fa-link"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger" data-confirm="{{ "user-send-link-form-{$user->id}" }}">
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </button>
                                                </div>
                                            @endcan
                                        </div>
                                        @can("delete", \App\User::class)
                                            <confirm-form :id="'{{ "delete-form-{$user->id}" }}'">
                                                <template>
                                                    <form action="{{ route('admin.users.destroy', ['user' => $user]) }}"
                                                          id="delete-form-{{ $user->id }}"
                                                          method="post">
                                                        @csrf
                                                        @method("delete")
                                                    </form>
                                                </template>
                                            </confirm-form>
                                        @endcan
                                        @can("settings-management")
                                            <confirm-form :id="'{{ "user-link-form-{$user->id}" }}'"
                                                          confirm-text="Сгенерировать!"
                                                          text="Будет сгенерирована одноразовая ссылка на вход">
                                                <template>
                                                    <form action="{{ route("admin.users.auth.get-login", ['user' => $user]) }}"
                                                          id="user-link-form-{{ $user->id }}"
                                                          method="post">
                                                        @csrf
                                                    </form>
                                                </template>
                                            </confirm-form>
                                            <confirm-form :id="'{{ "user-send-link-form-{$user->id}" }}'"
                                                          confirm-text="Отправить!"
                                                          text="Будет отправлена одноразовая ссылка на вход">
                                                <template>
                                                    <form action="{{ route("admin.users.auth.send-login", ['user' => $user]) }}"
                                                          id="user-send-link-form-{{ $user->id }}"
                                                          method="post">
                                                        @csrf
                                                    </form>
                                                </template>
                                            </confirm-form>
                                        @endcan
                                    </td>
                                @endcanany
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        {{ $users->links() }}
    </div>
@endsection
