@hasSection('header-title')
    <div class="col-12">
        <h1>@yield('header-title')</h1>
    </div>
@endif
@yield('content')
@yield('links')