@extends('admin.layout')

@section('header-title', 'Добавить пользователя')

@section('admin')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post"
                      class="col-12"
                      action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="login">Login</label>
                                <input type="text"
                                       id="login"
                                       name="login"
                                       value="{{ old('login') }}"
                                       required
                                       class="form-control{{ $errors->has('login') ? ' is-invalid' : '' }}">
                                @if ($errors->has('login'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('login') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       required
                                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="surname">Фимилия</label>
                                <input type="text"
                                       id="surname"
                                       name="surname"
                                       value="{{ old('surname') }}"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="firstname">Имя</label>
                                <input type="text"
                                       id="firstname"
                                       name="firstname"
                                       value="{{ old('firstname') }}"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="fathername">Отчество</label>
                                <input type="text"
                                       id="fathername"
                                       name="fathername"
                                       value="{{ old('fathername') }}"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sex">Пол</label>
                                <select name="sex"
                                        id="sex"
                                        class="custom-select">
                                    @foreach($sex as $key => $value)
                                        <option value="{{ $key }}"
                                                @if(old('sex'))
                                                selected
                                                @endif>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="password">Пароль</label>

                                <input id="password"
                                       type="password"
                                       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                       name="password">

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="password-confirm">Повторите пароль</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Роли</label>
                            @foreach($roles as $role)
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input"
                                           type="checkbox"
                                           @if (old('check-' . $role->id))
                                           checked
                                           @endif
                                           value="{{ $role->id }}"
                                           id="check-{{ $role->name }}"
                                           name="check-{{ $role->id }}">
                                    <label class="custom-control-label" for="check-{{ $role->name }}">
                                        {{ empty($role->title) ? $role->name : $role->title }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-12">
                            <div class="btn-group"
                                 role="group">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">К списку пользователей</a>
                                <button type="submit" class="btn btn-success">Создать</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
