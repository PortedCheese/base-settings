@extends('admin.layout')

@section('header-title', 'Пользователи')

@section('admin')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="get" action="{{ route($currentRoute) }}">
                    <div class="form-row align-items-center">
                        <div class="col-auto">
                            <label class="sr-only" for="login">Login</label>
                            <div class="input-group mb-2">
                                <input type="text"
                                       value="{{ $query->get('login') }}"
                                       class="form-control"
                                       name="login"
                                       id="login"
                                       placeholder="Login">
                            </div>
                        </div>
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
                            <label class="sr-only" for="surname">Фамилия</label>
                            <div class="input-group mb-2">
                                <input type="text"
                                       value="{{ $query->get('surname') }}"
                                       class="form-control"
                                       name="surname"
                                       id="surname"
                                       placeholder="Фамилия">
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
                            <button type="submit" class="btn btn-primary mb-2">Поиск</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Login</th>
                            <th>E-mail</th>
                            <th>ФИО</th>
                            <th>Пол</th>
                            <th>Роли</th>
                            <th>Подтвержден</th>
                            <th class="text-center">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    {{ $page * $per + $loop->iteration }}
                                </td>
                                <td>{{ $user->login }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->full_name }}</td>
                                <td>{{ $user->sex_text }}</td>
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
                                <td class="text-center">
                                    <confirm-delete-model-button model-id="{{ $user->id }}">
                                        <template slot="edit">
                                            <a href="{{ route('admin.users.edit', ['user' => $user]) }}" class="btn btn-primary">
                                                <i class="far fa-edit"></i>
                                            </a>
                                        </template>
                                        <template slot="delete">
                                            <form action="{{ route('admin.users.destroy', ['user' => $user]) }}"
                                                  id="delete-{{ $user->id }}"
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

    <div class="col-12">
        {{ $users->links() }}
    </div>
@endsection
