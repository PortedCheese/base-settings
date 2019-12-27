<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @section('tag-title')
        @empty($pageMetas['title'])
            <title>@yield('page-title'){{ config('app.name', 'Laravel') }}</title>
        @endempty
        @empty($pageMetas['og:title'])
            <meta content="@yield('page-title'){{ config('app.name', 'Laravel') }}" property="og:title" >
        @endempty
    @show

    @isset($pageMetas)
        @foreach($pageMetas as $key => $meta)
            {!! $meta !!}
        @endforeach
    @endisset

    @stack('more-meta')

    @stack('js-lib')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="https://kit-free.fontawesome.com/releases/latest/css/free.min.css" crossorigin="anonymous">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}?{{ siteconf()->get("base-settings", "frontendDate", "") }}" rel="stylesheet">
    @stack('more-css')
</head>
<body>
    <div id="app">
        @include('base-settings::layouts.nav')

        @include('base-settings::layouts.messages')

        <main class="py-4">
            @include('base-settings::layouts.main-section')
        </main>

        <footer>
            @hasSection('footer')
                <div class="container">
                    <div class="row">
                        @yield('footer')
                    </div>
                </div>
            @else
                @include('base-settings::layouts.footer')
            @endif
        </footer>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}?{{ siteconf()->get("base-settings", "frontendDate", "") }}" defer></script>
    @stack('more-scripts')
</body>
</html>
