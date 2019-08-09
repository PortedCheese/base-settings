@extends('base-settings::profile.layout')

@section('profile')
    <div class="row">
        <div class="col-8">
            <dl class="row">
                <dt class="col-sm-3">Login</dt>
                <dd class="col-sm-9">{{ $user->login }}</dd>

                <dt class="col-sm-3">E-mail</dt>
                <dd class="col-sm-9">{{ $user->email }}</dd>

                <dt class="col-sm-3">ФИО</dt>
                <dd class="col-sm-9">{{ $user->full_name }}</dd>

                <dt class="col-sm-3">Пол</dt>
                <dd class="col-sm-9">{{ $user->sex_text }}</dd>
            </dl>
        </div>
        <div class="col-4">
            @if($image)
                <img src="{{ route('imagecache', ['template' => 'avatar', 'filename' => $image->file_name]) }}"
                     class="img-thumbnail rounded-circle"
                     alt="{{ $image->name }}">
            @endif
        </div>
    </div>
@endsection