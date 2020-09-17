@if (strstr($currentRoute, "admin") === false)
@includeIf("catalog::site.cart.cart-state")
@includeIf("category-product::site.includes.favorite-state")
@includeIf("variation-cart::site.includes.cart-state")
@endif
<!-- Authentication Links -->
@guest
    <li class="nav-item">
        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
    </li>
    <li class="nav-item">
        @if (Route::has('register'))
            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
        @endif
    </li>
@else
    <li class="nav-item dropdown">
        <a id="navbarDropdown"
           class="nav-link dropdown-toggle"
           href="#"
           role="button"
           data-toggle="dropdown"
           aria-haspopup="true"
           aria-expanded="false"
           v-pre
        >
            {{ !empty(Auth::user()->name) ? Auth::user()->name : Auth::user()->email }}
        </a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            @can('site-management')
                <a href="{{ route('admin') }}" class="dropdown-item">
                    Dashboard
                </a>
            @endcan
            @can('settings-management')
                <a class="dropdown-item" target="_blank" href="{{ route('admin.logs') }}">
                    Логи
                </a>
            @endcan
            <a class="dropdown-item"
               href="{{ route('profile.show') }}">
                Профиль
            </a>
            <a class="dropdown-item"
               href="{{ route('logout') }}"
               onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                Выход
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </li>
@endguest