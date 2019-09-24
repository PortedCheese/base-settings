<li class="nav-item">
    <a href="{{ route('admin.users.index') }}"
       class="nav-link{{ strstr($currentRoute, 'admin.users') !== false ? ' active' : '' }}">
        <i class="fas fa-users"></i> Пользователи
    </a>
</li>

@role("admin")
<li class="nav-item">
    <a href="{{ route("admin.settings.index") }}"
       class="nav-link{{ strstr($currentRoute, "admin.settings") !== false ? ' active' : "" }}">
        <i class="fas fa-cogs"></i> Настройки
    </a>
</li>
@endrole

@includeIf('admin-site-menu::layouts.index', ['menu' => $adminMenu])
