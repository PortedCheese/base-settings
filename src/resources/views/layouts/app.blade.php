<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('base-settings::layouts.favicon')

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
    @include('base-settings::layouts.fonts')

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}?{{ siteconf()->get("base-settings", "frontendDate", "") }}" rel="stylesheet">
    @stack('more-css')
</head>
<body>
    <div id="app">
        @include('base-settings::layouts.nav')

        <main class="main-section">
            @include('base-settings::layouts.main-section')
        </main>

        <footer class="footer-section">
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
