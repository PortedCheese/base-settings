@extends('admin.layout')

@section('header-title', 'Пользователи')

@section('admin')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="get" action="{{ route($currentRoute) }}">
                    <div class="form-row align-items-center">
                        <div class="col-auto">
                            <label class="sr-only" for="email">E-mail</label>
                            <div class="input-group mb-2">
                                <input type="text"
                                       value="{{ $query->get('email') }}"
                                       class="form-control"
                                       name="email"
                                       id="email"
                                       placeholder="E-mail">
                            </div>
                        </div>
                        <div class="col-auto">
                            <label class="sr-only" for="surname">ФИО</label>
                            <div class="input-group mb-2">
                                <input type="text"
                                       value="{{ $query->get('surname') }}"
                                       class="form-control"
                                       name="surname"
                                       id="surname"
                                       placeholder="ФИО">
                            </div>
                        </div>
                        <div class="col-auto">
                            <label for="verified" class="sr-only">Подтвержден</label>
                            <div class="input-group mb-2">
                                <select class="form-control"
                                        id="verified"
                                        name="verified">
                                    <option value="all" {{ $query->get('verified') == 'all' ? 'selected' : '' }}>
                                        -Любой-
                                    </option>
                                    <option value="1" {{ $query->get('verified') == '1' ? 'selected' : '' }}>
                                        Подтвержден
                                    </option>
                                    <option value="0" {{ $query->get('verified') == '0' ? 'selected' : '' }}>
                                        Ожидает
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group mb-2"
                                 role="group">
                                <button type="submit" class="btn btn-primary">Применить</button>
                                <a href="{{ route($currentRoute) }}" class="btn btn-secondary">Сбросить</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route("admin.users.create") }}" class="btn btn-success">Добавить</a>
            </div>
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
                            <th>Действия</th>
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
                                <td>{{ datehelper()->format($user->verified) }}</td>
                                <td class="text-center">
                                    <div role="toolbar" class="btn-toolbar">
                                        <div class="btn-group btn-group-sm mr-1">
                                            <a href="{{ route("admin.users.edit", ["user" => $user]) }}" class="btn btn-primary">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" data-confirm="{{ "delete-form-{$user->id}" }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <confirm-form :id="'{{ "delete-form-{$user->id}" }}'">
                                        <template>
                                            <form action="{{ route('admin.users.destroy', ['user' => $user]) }}"
                                                  id="delete-form-{{ $user->id }}"
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

    <div class="col-12">
        {{ $users->links() }}
    </div>
@endsection
