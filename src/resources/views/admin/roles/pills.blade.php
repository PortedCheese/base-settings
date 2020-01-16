<ul class="nav nav-pills">
    @foreach ($rules as $item)
        <li class="nav-item">
            <a href="{{ route("admin.roles.rules.show", ['role' => $role, 'rule' => $item]) }}"
               class="nav-link{{ !empty($rule) && $rule->slug === $item->slug ? " active" : "" }}">
                {{ $item->title }}
            </a>
        </li>
    @endforeach
</ul>