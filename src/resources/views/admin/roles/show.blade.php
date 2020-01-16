@extends("admin.layout")

@section("page-title", "Просмотр {$role->title} - ")

@section('header-title', "Просмотр {$role->title}")

@section('admin')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @include("base-settings::admin.roles.pills", ['role' => $role, "rule" => false])
            </div>
        </div>
    </div>
@endsection