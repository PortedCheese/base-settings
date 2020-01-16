@can("viewAny", \App\User::class)
    <li class="nav-item">
        <a href="{{ route('admin.users.index') }}"
           class="nav-link{{ strstr($currentRoute, 'admin.users') !== false ? ' active' : '' }}">
            <i class="fas fa-users"></i> Пользователи
        </a>
    </li>
@endcan

@can("settings-management")
    <li class="nav-item">
        <a href="{{ route("admin.settings.index") }}"
           class="nav-link{{ strstr($currentRoute, "admin.settings") !== false ? ' active' : "" }}">
            <i class="fas fa-cogs"></i> Настройки
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route("admin.roles.index") }}"
           class="nav-link{{ strstr($currentRoute, "admin.roles") !== false ? ' active' : "" }}">
            <i class="fas fa-project-diagram"></i> Роли
        </a>
    </li>
@endcan

@includeIf('admin-site-menu::layouts.index', ['menu' => $adminMenu])
