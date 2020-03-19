<div class="col-12">
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-pills">
                @can("viewAny", \App\User::class)
                    <li class="nav-item">
                        <a href="{{ route("admin.users.index") }}"
                           class="nav-link{{ $currentRoute === "admin.users.index" ? " active" : "" }}">
                            Список
                        </a>
                    </li>
                @endcan
                @can("create", \App\User::class)
                    <li class="nav-item">
                        <a href="{{ route("admin.users.create") }}"
                           class="nav-link{{ $currentRoute === "admin.users.create" ? " active" : "" }}">
                            Добавить
                        </a>
                    </li>
                @endcan
                @if (! empty($user))
                    @can("update", \App\User::class)
                        <a href="{{ route("admin.users.edit", ["user" => $user]) }}"
                           class="nav-link{{ $currentRoute === "admin.users.edit" ? " active" : "" }}">
                            Редактировать
                        </a>
                    @endcan
                @endif
            </ul>
        </div>
    </div>
</div>