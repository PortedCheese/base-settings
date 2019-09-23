<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle{{ strstr($currentRoute, 'admin.users') !== false ? ' active' : '' }}"
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

@role("admin")
<li class="nav-item">
    <a href="{{ route("admin.settings.index") }}" class="nav-link{{ strstr($currentRoute, "admin.settings") !== false ? ' active' : "" }}">
        <i class="fas fa-cogs"></i> Настройки
    </a>
</li>
@endrole

@includeIf('admin-site-menu::layouts.index', ['menu' => $adminMenu])
