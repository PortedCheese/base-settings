@extends('layouts.boot')

@section("page-title", "Профиль - ")

@section("header-title", "Профиль")

@section('contents')
    <div class="row profile-page">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-2">
                            @if($image)
                                @pic([
                                    "image" => $image,
                                    "template" => "profile-image",
                                    "grid" => [],
                                    "imgClass" => "img-fluid profile-page__img rounded-circle",
                                ])
                            @else
                                <div class="profile-page__empty">
                                    <svg class="profile-page__empty-ico">
                                        <use xlink:href="#profile-ico"></use>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="col-12 col-md-6 col-lg-8">
                            <h3 class="profile-page__name">{{ $user->full_name }}</h3>
                            <ul class="list-inline">
                                <li class="list-inline-item profile-page__list-item">
                                    <div class="profile-page__label">
                                        E-mail
                                    </div>
                                    <div class="profile-page__value">
                                        {{ empty($user->email) ? "-" : $user->email }}
                                    </div>
                                </li>

                                <li class="list-inline-item profile-page__list-item">
                                    <div class="profile-page__label">
                                        Телефон
                                    </div>
                                    <div class="profile-page__value">
                                        {{ empty($user->phone_number) ? "-" : $user->phone_number }}
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-4 col-lg-2">
                            <a href="{{ route('profile.edit') }}">
                                Редактировать
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include("base-settings::profile.expand")
@endsection