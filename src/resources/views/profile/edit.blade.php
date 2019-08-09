@extends('base-settings::profile.layout')

@section('profile')
    <form method="post"
          action="{{ route('profile.update') }}"
          class="row"
          enctype="multipart/form-data">
        @csrf
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
                       name="email"
                       id="email"
                       value="{{ old('email') ? old('email') : $user->email }}"
                       required
                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="surname">Фимилия</label>
                <input type="text"
                       name="surname"
                       id="surname"
                       value="{{ old('surname') ? old('surname') : $user->surname }}"
                       class="form-control">
            </div>
            <div class="form-group">
                <label for="firstname">Имя</label>
                <input type="text"
                       name="firstname"
                       id="firstname"
                       value="{{ old('firstname') ? old('firstname') : $user->firstname }}"
                       class="form-control">
            </div>
            <div class="form-group">
                <label for="fathername">Отчество</label>
                <input type="text"
                       name="fathername"
                       id="fathername"
                       value="{{ old('fathername') ? old('fathername') : $user->fathername }}"
                       class="form-control">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="sex">Пол</label>
                <select name="sex"
                        id="sex"
                        class="form-control">
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
                <label for="password">Пароль</label>

                <input id="password"
                       type="password"
                       id="password"
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
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Обновить</button>
        </div>
    </form>
@endsection