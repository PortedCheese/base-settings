<ul class="nav nav-pills">
    <li class="nav-item">
        <a class="nav-link {{ $currentRoute == 'profile.show' ? 'active' : '' }}"
           href="{{ route('profile.show') }}">
            Профиль
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $currentRoute == 'profile.edit' ? 'active' : '' }}"
           href="{{ route('profile.edit') }}">
            Редактировать
        </a>
    </li>
</ul>