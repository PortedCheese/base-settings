@can("viewAny", \App\User::class)
    <li class="nav-item{{ strstr($currentRoute, "admin.users") !== false ? ' active' : "" }}">
        <a href="{{ route('admin.users.index') }}"
           class="nav-link{{ strstr($currentRoute, 'admin.users') !== false ? ' active' : '' }}">
            <i class="fas fa-users"></i> <span>Пользователи</span>
        </a>
    </li>
@endcan

@can("settings-management")
    <li class="nav-item{{ strstr($currentRoute, "admin.settings") !== false ? ' active' : "" }}">
        <a href="{{ route("admin.settings.index") }}"
           class="nav-link{{ strstr($currentRoute, "admin.settings") !== false ? ' active' : "" }}">
            <i class="fas fa-cogs"></i> <span>Настройки</span>
        </a>
    </li>

    <li class="nav-item{{ strstr($currentRoute, "admin.roles") !== false ? ' active' : "" }}">
        <a href="{{ route("admin.roles.index") }}"
           class="nav-link{{ strstr($currentRoute, "admin.roles") !== false ? ' active' : "" }}">
            <i class="fas fa-project-diagram"></i> <span>Роли</span>
        </a>
    </li>
@endcan

@if ($theme == "sb-admin")
    @includeIf('admin-site-menu::sb-admin.index', ['menu' => $adminMenu ?? []])
@else
    @includeIf('admin-site-menu::layouts.index', ['menu' => $adminMenu ?? []])
@endif
