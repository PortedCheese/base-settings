@extends('layouts.boot')

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                @include("base-settings::profile.menu")
            </div>

            <div class="card-body">
                @yield('profile')
            </div>
        </div>
    </div>
@endsection
