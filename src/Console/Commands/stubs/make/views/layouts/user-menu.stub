@includeIf("catalog::site.cart.cart-state")
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
            {{ Auth::user()->login }}
        </a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            @role('admin|editor')
            <a href="{{ route('admin') }}" class="dropdown-item">
                Dashboard
            </a>
            @endrole
            @role('admin')
            <a class="dropdown-item" target="_blank" href="{{ route('admin.logs') }}">
                Логи
            </a>
            @endrole
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