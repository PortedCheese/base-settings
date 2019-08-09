<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle{{ strstr($currentRoute, 'admin.users') !== FALSE ? ' active' : '' }}"
       href="#"
       id="user-dropdown"
       role="button"
       data-toggle="dropdown"
       aria-haspopup="true"
       aria-expanded="false">
        <i class="fas fa-users"></i> Пользователи
    </a>
    <div class="dropdown-menu" aria-labelledby="user-dropdown">
        <a href="{{ route('admin.users.index') }}"
           class="dropdown-item">
            Список
        </a>
        <a href="{{ route('admin.users.create') }}"
           class="dropdown-item">
            Создать
        </a>
    </div>
</li>

@includeIf('layouts.menu.index', ['menu' => $adminMenu])
