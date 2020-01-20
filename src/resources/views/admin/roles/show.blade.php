@extends("admin.layout")

@section("page-title", "Просмотр {$role->title} - ")

@section('header-title', "Просмотр {$role->title}")

@section('admin')
    @include("base-settings::admin.roles.pills", ['role' => $role, "rule" => false])
@endsection