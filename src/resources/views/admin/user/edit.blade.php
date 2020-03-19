@extends('admin.layout')

@section('header-title', 'Редактировать ' . $user->full_name)

@section('admin')
    @include("base-settings::admin.user.includes.pills")

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

                        <div class="form-group">
                            <label>Роли</label>
                            @foreach($roles as $role)
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input"
                                           type="checkbox"
                                           {{ (! count($errors->all()) && in_array($role->id, $user->getRoleIds())) || in_array($role->id, old("roles", [])) ? "checked" : "" }}
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
                                   value="{{ old("name", $user->name) }}"
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
                                   value="{{ old("last_name", $user->last_name) }}"
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
                                   value="{{ old("middle_name", $user->middle_name) }}"
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
                            <label for="customFile">Изображение</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile" name="avatar">
                                <label class="custom-file-label" for="customFile">Изображение</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="btn-group"
                             role="group">
                            <button type="submit" class="btn btn-success">Обновить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
