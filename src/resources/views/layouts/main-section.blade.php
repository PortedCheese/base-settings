@hasSection('breadcrumb')
    <div class="container">
        <div class="row">
            @yield('breadcrumb')
        </div>
    </div>
@else
    @isset($siteBreadcrumb)
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">Главная</a>
                            </li>
                            @foreach ($siteBreadcrumb as $item)
                                <li class="breadcrumb-item{{ $item->active ? ' active' : '' }}">
                                    @if ($item->active)
                                        {{ $item->title }}
                                    @else
                                        <a href="{{ $item->url }}">
                                            {{ $item->title }}
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    @endisset
@endif
@hasSection('content')
    <div class="container">
        <div class="row">
            @hasSection('sidebar')
                <aside class="d-none d-lg-block col-lg-3">
                    <div class="row">
                        @yield('sidebar')
                    </div>
                </aside>
                <section class="col-12 col-lg-9">
                    <div class="row">
                        @include('base-settings::layouts.content')
                    </div>
                </section>
            @else
                <section class="col-12">
                    <div class="row">
                        @include('base-settings::layouts.content')
                    </div>
                </section>
            @endif
        </div>
    </div>
@endif
@hasSection('rawContent')
    @yield('rawContent')
@endif