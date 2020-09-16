@extends('layouts.boot')

@section("page-title", "Профиль - ")

@section("header-title", "Редактировать профиль")

@section('content')
    <div class="col-12">
        <div class="card profile-page">
            <div class="card-body">
                <form method="post"
                      action="{{ route('profile.update') }}"
                      enctype="multipart/form-data">
                    @csrf
                    @method("put")
                    <div class="form-row">
                        <div class="form-group upper-label col-md-4">
                            <input type="text"
                                   id="name"
                                   name="name"
                                   required
                                   value="{{ old("name", $user->name) }}"
                                   class="form-control @error("name") is-invalid @enderror">
                            <label for="name">Имя <span class="text-danger">*</span></label>
                            @error("name")
                                <div class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group upper-label col-md-4">
                            <input type="text"
                                   id="last_name"
                                   name="last_name"
                                   value="{{ old("last_name", $user->last_name) }}"
                                   class="form-control @error("last_name") is-invalid @enderror">
                            <label for="last_name">Фамилия</label>
                            @error("last_name")
                                <div class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group upper-label col-md-4">
                            <input type="text"
                                   id="middle_name"
                                   name="middle_name"
                                   value="{{ old("middle_name", $user->middle_name) }}"
                                   class="form-control @error("middle_name") is-invalid @enderror">
                            <label for="middle_name">Отчество</label>
                            @error("middle_name")
                                <div class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group upper-label col-md-4">
                            <input type="text"
                                   id="email"
                                   name="email"
                                   value="{{ old("email", $user->email) }}"
                                   class="form-control @error("email") is-invalid @enderror">
                            <label for="email">E-mail <span class="text-danger">*</span></label>
                            @error("email")
                                <div class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group upper-label col-md-4">
                            <input type="text"
                                   id="phone_number"
                                   name="phone_number"
                                   value="{{ old("phone_number", $user->phone_number) }}"
                                   class="form-control @error("phone_number") is-invalid @enderror">
                            <label for="phone_number">Номер телефона</label>
                            @error("phone_number")
                                <div class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group upper-label col-md-4">
                            <div class="custom-file">
                                <input type="file"
                                       class="custom-file-input"
                                       id="custom-file-input"
                                       lang="ru"
                                       name="image"
                                       aria-describedby="inputGroupAvatar">
                                <label class="custom-file-label"
                                       for="custom-file-input">
                                    Выберите файл
                                </label>
                            </div>
                            <label for="custom-file-input">Изображение</label>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group upper-label">
                                <input id="password"
                                       type="password"
                                       class="form-control @error("password") is-invalid @enderror"
                                       name="password">
                                <label for="password">Пароль</label>
                                @error("password")
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group upper-label">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                                <label for="password-confirm">Повторите пароль</label>
                            </div>
                        </div>
                    </div>

                    <div role="group">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                        <a href="{{ route("profile.show") }}" class="btn btn-link">Отменить изменения</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection