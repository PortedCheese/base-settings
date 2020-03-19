@extends('admin.layout')

@section('header-title', 'Добавить пользователя')

@section('admin')
    @include("base-settings::admin.user.includes.pills")

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post"
                      class="row"
                      action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   class="form-control @error("email") is-invalid @enderror">
                            @error("email")
                                <div class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Роли</label>
                            @foreach($roles as $role)
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input"
                                           type="checkbox"
                                           {{ in_array($role->id, old("roles", [])) ? "checked" : "" }}
                                           value="{{ $role->id }}"
                                           id="check-{{ $role->id }}"
                                           name="roles[]">
                                    <label class="custom-control-label" for="check-{{ $role->id }}">
                                        {{ $role->title }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Имя</label>
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
                            <label for="last_name">Фамилия</label>
                            <input type="text"
                                   id="last_name"
                                   name="last_name"
                                   value="{{ old('last_name') }}"
                                   class="form-control @error("last_name") is-invalid @enderror">
                            @error("last_name")
                                <div class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="middle_name">Отчество</label>
                            <input type="text"
                                   id="middle_name"
                                   name="middle_name"
                                   value="{{ old('middle_name') }}"
                                   class="form-control @error("middle_name") is-invalid @enderror">
                            @error("middle_name")
                                <div class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Пароль</label>

                            <input id="password"
                                   type="password"
                                   class="form-control @error("password") is-invalid @enderror"
                                   name="password">

                            @error("password")
                                <div class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password-confirm">Повторите пароль</label>
                            <input id="password-confirm"
                                   type="password"
                                   class="form-control @error("password_confirmation") is-invalid @enderror"
                                   name="password_confirmation">
                            @error("password_confirmation")
                                <div class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="btn-group"
                             role="group">
                            <button type="submit" class="btn btn-success">Добавить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
