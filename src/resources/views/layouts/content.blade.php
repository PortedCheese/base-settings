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

@hasSection("content")
    <div class="row @yield('content-cover-class', 'content-cover')">
        @yield('content')
    </div>
@endif

@yield("contents")

@hasSection("links")
    <div class="row @yield("links-cover-class", "justify-content-center")">
        @yield('links')
    </div>
@endif