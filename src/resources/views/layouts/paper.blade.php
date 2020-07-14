<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('paper/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('paper/img/favicon.png') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page-title'){{ config('app.name', 'Laravel') }}</title>
    <meta content="@yield('page-title'){{ config('app.name', 'Laravel') }}" property="og:title" >

    <!-- Scripts -->
    @stack('js-lib')
    @stack('more-scripts')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/admin.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('paper/css/paper-dashboard.css') }}">

    @stack('more-css')
</head>
<body>
    <div class="wrapper" id="app">
        {{--Sidebar--}}
        <div class="sidebar" data-color="black" data-active-color="success">
            <div class="logo text-center">
                <a href="{{ url('/') }}" class="simple-text logo-normal">
                    @inject('string', 'Illuminate\Support\Str')
                    {{ $string->limit(config('app.name', 'Laravel'), 20) }}
                </a>
            </div>
            <div class="sidebar-wrapper">
                <ul class="nav">
                    @include('base-settings::admin.menu')
                </ul>
            </div>
        </div>
        {{--Main--}}
        <div class="main-panel">
            {{--Navbar--}}
            <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
                <div class="container-fluid">
                    <div class="navbar-wrapper">
                        <div class="navbar-toggle">
                            <button type="button" class="navbar-toggler">
                                <span class="navbar-toggler-bar bar1"></span>
                                <span class="navbar-toggler-bar bar2"></span>
                                <span class="navbar-toggler-bar bar3"></span>
                            </button>
                        </div>
                        <a class="navbar-brand" href="{{ url('/admin') }}">@yield('header-title')</a>
                    </div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navigation">
                        <ul class="navbar-nav">
                            @include('base-settings::layouts.user-menu')
                        </ul>
                    </div>
                </div>
            </nav>
            {{--End Navbar--}}

            <div class="content">
                @include('base-settings::layouts.messages')
                <div class="row">
                    @hasSection('sidebar')
                        <aside class="col-3">
                            <div class="row">
                                @yield('sidebar')
                            </div>
                        </aside>
                        <section class="col-9">
                            <div class="row">
                                @yield('content')
                                @yield('links')
                            </div>
                        </section>
                    @else
                        <section class="col-12">
                            <div class="row">
                                @yield('content')
                                @yield('links')
                            </div>
                        </section>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="{{ mix('js/admin.js') }}" defer></script>
</body>
</html>