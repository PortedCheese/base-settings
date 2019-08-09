@extends('admin.layout')

@section('header-title', 'Редактировать ' . $user->full_name)

@section('admin')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post"
                      action="{{ route('admin.users.update', ['user' => $user]) }}"
                      enctype="multipart/form-data"
                      class="col-12">
                    @csrf
                    @method('put')

                    <div class="row">
                        <div class="col-md-6">
                            @if($image)
                                <div class="form-group">
                                    <img src="{{ route('imagecache', ['template' => 'avatar', 'filename' => $image->file_name]) }}"
                                         class="img-thumbnail rounded-circle"
                                         alt="{{ $image->name }}">
                                </div>
                            @endif
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile" name="avatar">
                                <label class="custom-file-label" for="customFile">Изображение</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="login">Login</label>
                                <input type="text"
                                       id="login"
                                       name="login"
                                       value="{{ old('login') ? old('login') : $user->login }}"
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
                                       value="{{ old('email') ? old('email') : $user->email }}"
                                       required
                                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="surname">Фамилия</label>
                                <input type="text"
                                       id="surname"
                                       name="surname"
                                       value="{{ old('surname') ? old('surname') : $user->surname }}"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="firstname">Имя</label>
                                <input type="text"
                                       id="firstname"
                                       name="firstname"
                                       value="{{ old('firstname') ? old('firstname') : $user->firstname }}"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="fathername">Отчество</label>
                                <input type="text"
                                       id="fathername"
                                       name="fathername"
                                       value="{{ old('fathername') ? old('fathername') : $user->fathername }}"
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
                                                @elseif(($key == $user->sex) && !old('sex'))
                                                selected
                                                @endif>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Роли</label>
                                @foreach($roles as $role)
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input"
                                               type="checkbox"
                                               @if ($user->hasRole($role->name) or old('check-' . $role->id))
                                               checked
                                               @if ($auth->id == $user->id and $role->name == 'admin')
                                               disabled
                                               @endif
                                               @endif
                                               value="{{ $role->id }}"
                                               id="check-{{ $role->name }}"
                                               name="check-{{ $role->id }}">
                                        <label class="custom-control-label" for="check-{{ $role->name }}">
                                            {{ $role->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="btn-group"
                                 role="group">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">К списку пользователей</a>
                                <button type="submit" class="btn btn-success">Обновить</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
