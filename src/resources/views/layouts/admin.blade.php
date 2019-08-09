<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page-title'){{ config('app.name', 'Laravel') }}</title>
    <meta content="@yield('page-title'){{ config('app.name', 'Laravel') }}" property="og:title" >

    <!-- Scripts -->
    @stack('js-lib')
    <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script src="{{ asset('js/admin.js') }}" defer></script>
    @stack('more-scripts')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('more-css')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button
                        class="navbar-toggler"
                        type="button"
                        data-toggle="collapse"
                        data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent"
                        aria-expanded="false"
                        aria-label="{{ __('Toggle navigation') }}"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @include('base-settings::admin.menu')
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        @include('base-settings::layouts.user-menu')
                    </ul>
                </div>
            </div>
        </nav>

        @include('base-settings::layouts.messages')

        <main class="py-4">
            <div class="container">
                <div class="row">
                    @hasSection('sidebar')
                        <aside class="col-3">
                            <div class="row">
                                @yield('sidebar')
                            </div>
                        </aside>
                        <section class="col-9">
                            <div class="row">
                                <div class="col-12">
                                    <h1>@yield('header-title')</h1>
                                </div>
                                @yield('content')
                                @yield('links')
                            </div>
                        </section>
                    @else
                        <section class="col-12">
                            <div class="row">
                                <div class="col-12">
                                    <h1>@yield('header-title')</h1>
                                </div>
                                @yield('content')
                                @yield('links')
                            </div>
                        </section>
                    @endif
                </div>
            </div>
        </main>
    </div>
</body>
</html>