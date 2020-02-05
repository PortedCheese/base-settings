<div class="row @yield('header-title-cover-class', 'header-title-cover')">
    @hasSection('header-title')
        <div class="col-12">
            <h1>@yield('header-title')</h1>
        </div>
    @endif
    @hasSection("raw-header-title")
        @yield("raw-header-title")
    @endif
</div>

<div class="row @yield('content-cover-class', 'content-cover')">
    @yield('content')
</div>

<div class="row">
    @yield('links')
</div>