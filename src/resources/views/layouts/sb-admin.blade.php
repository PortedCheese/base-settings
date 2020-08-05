<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @include('base-settings::layouts.favicon')

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page-title'){{ config('app.name', 'Laravel') }}</title>
    <meta content="@yield('page-title'){{ config('app.name', 'Laravel') }}" property="og:title" >

    <!-- Scripts -->
    @stack('js-lib')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ mix("css/admin.css") }}" rel="stylesheet">
    @stack('more-css')
</head>

<body id="page-top">
<div id="app">
    {{--Page Wrapper--}}
    <div id="wrapper">

        {{--Sidebar--}}
        <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

            {{--Sidebar - Brand--}}
            <li class="nav-item">
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/') }}">
                    <div class="sidebar-brand-icon rotate-n-15">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="sidebar-brand-text mx-3">
                        @inject('string', 'Illuminate\Support\Str')
                        {{ $string->limit(config('app.name', 'Laravel'), 20) }}
                    </div>
                </a>
                <hr class="sidebar-divider my-0">
            </li>

            {{--Menu--}}
            @include('base-settings::admin.menu')
        </ul>
        {{--End of Sidebar--}}

        {{--Content Wrapper--}}
        <div id="content-wrapper" class="d-flex flex-column">
            {{--Main Content--}}
            <div id="content">

                {{--Topbar--}}
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    {{--Sidebar Toggle (Topbar)--}}
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    {{--Topbar Navbar--}}
                    <ul class="navbar-nav ml-auto user-menu-list">
                        @include('base-settings::layouts.user-menu')
                    </ul>

                </nav>
                {{--End of Topbar--}}

                {{--Begin Page Content--}}
                <div class="container-fluid">
                    @include('base-settings::layouts.messages')
                    {{--Page Heading--}}
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">@yield('header-title')</h1>
                    </div>
                    {{--Content--}}
                    <div class="row">
                        @hasSection('sidebar')
                            <aside class="col-3">
                                <div class="row">
                                    @yield('sidebar')
                                </div>
                            </aside>
                            <section class="col-9">
                                @hasSection("content")
                                    <div class="row">
                                        @yield('content')
                                    </div>
                                @endif

                                @yield("contents")

                                @hasSection("links")
                                    <div class="row">
                                        @yield('links')
                                    </div>
                                @endif
                            </section>
                        @else
                            <section class="col-12">
                                @hasSection("content")
                                    <div class="row">
                                        @yield('content')
                                    </div>
                                @endif

                                @yield("contents")

                                @hasSection("links")
                                    <div class="row">
                                        @yield('links')
                                    </div>
                                @endif
                            </section>
                        @endif
                    </div>
                </div>
                {{-- /.container-fluid --}}

            </div>
            {{-- End of Main Content --}}

        </div>
        {{-- End of Content Wrapper --}}

    </div>
    {{-- End of Page Wrapper --}}

    {{-- Scroll to Top Button --}}
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
</div>

<!-- Bootstrap core JavaScript-->
<script src="{{ mix("js/admin.js") }}"></script>

</body>

</html>
