@include("base-settings::layouts.breadcrumb")

@if (View::hasSection("content") || View::hasSection("contents"))
    <div class="container">
        <div class="row">
            <div class="col-12">
                @include('base-settings::layouts.messages')
            </div>
        </div>

        <div class="row">
            @hasSection('sidebar')
                <aside class="d-none d-lg-block col-lg-3 sidebar-section">
                    @yield('sidebar')
                </aside>
                <section class="col-12 col-lg-9 content-section">
                    @include('base-settings::layouts.content')
                </section>
            @else
                <section class="col-12 content-section">
                    @include('base-settings::layouts.content')
                </section>
            @endif
        </div>
    </div>
@endif

@hasSection('rawContent')
    @yield('rawContent')
@endif