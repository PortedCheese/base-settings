<?php

namespace PortedCheese\BaseSettings\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use App\RoleRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Список ролей.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $roles = Role::query()
            ->orderBy("title")
            ->get();
        return view("base-settings::admin.roles.index", [
            "roles" => $roles,
        ]);
    }

    /**
     * Создание роли.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view("base-settings::admin.roles.create");
    }

    /**
     * Создать роль.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validateStore($request->all());
        $role = Role::create($request->all());
        return redirect()
            ->route("admin.roles.show", ["role" => $role])
            ->with("success", "Роль добавлена");
    }

    /**
     * Валидация создания.
     *
     * @param $data
     */
    private function validateStore($data)
    {
        Validator::make($data, [
            "title" => ["required", "min:3", "max:20", "unique:roles,title"],
            "name" => ["required", "min:3", "max:20", "unique:roles,name"],
        ], [], [
            "title" => "Загловок",
            "name" => "Name",
        ])->validate();
    }

    /**
     * Просмотр роли.
     *
     * @param Role $role
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Role $role)
    {
        $rules = RoleRule::query()
            ->select("slug", "title")
            ->orderBy("title")
            ->get();

        if ($rules->count()) {
            $rule = $rules->first();
            return redirect()
                ->route("admin.roles.rules.show", ['role' => $role, "rule" => $rule]);
        }
        return view("base-settings::admin.roles.show", [
            "role" => $role,
            "rules" => $rules,
        ]);
    }

    /**
     * Редактирование роли.
     *
     * @param Role $role
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Role $role)
    {
        return view("base-settings::admin.roles.edit", [
            "role" => $role,
        ]);
    }

    /**
     * Обновление роли.
     *
     * @param Request $request
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Role $role)
    {
        $this->validateUpdate($request->all(), $role);
        $role->update($request->all());
        return redirect()
            ->route("admin.roles.show", ['role' => $role])
            ->with("success", "Успешно обновлено");
    }

    /**
     * Валидация обновления.
     *
     * @param $data
     * @param Role $role
     */
    private function validateUpdate($data, Role $role)
    {
        $id = $role->id;
        Validator::make($data, [
            "title" => ["required", "min:3", "max:20", "unique:roles,title,{$id}"],
            "name" => ["required_if:is_default,0", "min:3", "max:20", "unique:roles,name,{$id}"],
        ], [], [
            "title" => "Заголовок",
            "name" => "Name",
        ])->validate();
    }

    /**
     * Удаление.
     *
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Role $role)
    {
        if ($role->default) {
            return redirect()
                ->back()
                ->with("danger", "Невозможно удалить стандартную роль");
        }
        $role->delete();
        return redirect()
            ->route("admin.roles.index")
            ->with("success", "Роль удалена");
    }
}