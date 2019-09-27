@extends('base-settings::profile.layout')

@section('profile')
    <form method="post"
          action="{{ route('profile.update') }}"
          class="row"
          enctype="multipart/form-data">
        @csrf

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
                <label for="email">E-mail</label>
                <input type="text"
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
            @if($image)
                <div class="form-group">
                    <img src="{{ route('imagecache', ['template' => 'avatar', 'filename' => $image->file_name]) }}"
                         class="img-thumbnail rounded-circle"
                         alt="{{ $image->name }}">
                </div>
            @endif
            <div class="form-group">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"
                              id="inputGroupAvatar">
                            Аватар
                        </span>
                    </div>
                    <div class="custom-file">
                        <input type="file"
                               class="custom-file-input"
                               id="custom-file-input"
                               lang="ru"
                               name="avatar"
                               aria-describedby="inputGroupAvatar">
                        <label class="custom-file-label"
                               for="custom-file-input">
                            Выберите файл
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Обновить</button>
        </div>
    </form>
@endsection