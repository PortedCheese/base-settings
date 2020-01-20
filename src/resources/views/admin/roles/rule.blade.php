@extends("admin.layout")

@section("page-title", "Просмотр {$role->title} - ")

@section('header-title', "Просмотр {$role->title} - {$rule->title}")

@section('admin')
    @include("base-settings::admin.roles.pills", ['role' => $role, "rule" => $rule])

    <div class="col-md-9 col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route("admin.roles.rules.update", ['role' => $role, "rule" => $rule]) }}" method="post">
                    @csrf
                    @method("put")
                    
                    <label>Доступы</label>
                    @foreach($rule->getPermissions() as $permission => $title)
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input"
                                   type="checkbox"
                                   {{ ($rights & $permission) || in_array($permission, old("permissions", [])) ? "checked" : "" }}
                                   value="{{ $permission }}"
                                   id="check-{{ $permission }}"
                                   name="permissions[]">
                            <label class="custom-control-label" for="check-{{ $permission }}">
                                {{ $title }}
                            </label>
                        </div>
                    @endforeach

                    <div class="btn-group mt-3"
                         role="group">
                        <button type="submit" class="btn btn-success">Обновить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection