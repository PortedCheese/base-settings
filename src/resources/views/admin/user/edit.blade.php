@extends('admin.layout')

@section('header-title', 'Редактировать ' . $user->full_name)

@section('admin')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post"
                      action="{{ route('admin.users.update', ['user' => $user]) }}"
                      enctype="multipart/form-data"
                      class="row">
                    @csrf
                    @method('put')

                    <div class="col-md-6">
                        @if($image)
                            <div class="form-group">
                                <img src="{{ route('imagecache', ['template' => 'avatar', 'filename' => $image->file_name]) }}"
                                     class="img-thumbnail rounded-circle"
                                     alt="{{ $image->name }}">
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="customFile">Изображение</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile" name="avatar">
                                <label class="custom-file-label" for="customFile">Изображение</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="login">Login</label>
                            <input type="text"
                                   id="login"
                                   name="login"
                                   value="{{ old("login", $user->login) }}"
                                   class="form-control @error("login") is-invalid @enderror">
                            @error("login")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old("email", $user->email) }}"
                                   class="form-control @error("email") is-invalid @enderror">
                            @error("email")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="surname">Фамилия</label>
                            <input type="text"
                                   id="surname"
                                   name="surname"
                                   value="{{ old("surname", $user->surname) }}"
                                   class="form-control @error("surname") is-invalid @enderror">
                            @error("surname")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="firstname">Имя</label>
                            <input type="text"
                                   id="firstname"
                                   name="firstname"
                                   value="{{ old("firstname", $user->firstname) }}"
                                   class="form-control @error("firstname") is-invalid @enderror">
                            @error("firstname")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="fathername">Отчество</label>
                            <input type="text"
                                   id="fathername"
                                   name="fathername"
                                   value="{{ old("fathername", $user->fathername) }}"
                                   class="form-control @error("fathername") is-invalid @enderror">
                            @error("fathername")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sex">Пол</label>
                            <select name="sex"
                                    id="sex"
                                    class="form-control custom-select @error("sex") is-invalid @enderror">
                                <option value="">Выберите...</option>
                                @foreach($sex as $key => $value)
                                    <option value="{{ $key }}"
                                            {{ old("sex", $user->sex) == $key ? "selected" : "" }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error("sex")
                            <div class="invalid-feedback" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Роли</label>
                            @foreach ($roles as $role)
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input"
                                           value="{{ $role->id }}"
                                           {{ (! count($errors->all()) && $user->hasRole($role->name)) || old("check-{$role->id}") ? "checked" : "" }}
                                           {{ ($auth->id == $user->id) && $role->name == "admin" ? "disabled" : "" }}
                                           id="check-{{ $role->name }}"
                                           name="check-{{ $role->id }}"
                                           type="checkbox">
                                    <label class="custom-control-label" for="check-{{ $role->name }}">
                                        {{ empty($role->title) ? $role->name : $role->title }}
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
                </form>
            </div>
        </div>
    </div>
@endsection
