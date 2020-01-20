<?php

namespace PortedCheese\BaseSettings\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use App\RoleRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RuleController extends Controller
{
    /**
     * Просмотр Правила.
     *
     * @param Role $role
     * @param RoleRule $rule
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Role $role, RoleRule $rule)
    {
        $rules = RoleRule::query()
            ->select("slug", "title")
            ->orderBy("title")
            ->get();

        $rights = DB::table("role_role_rule")
            ->select("rights")
            ->where("role_id", $role->id)
            ->where("role_rule_id", $rule->id)
            ->first();

        $rights = empty($rights) ? 0 : $rights->rights;

        return view("base-settings::admin.roles.rule", [
            "role" => $role,
            "rule" => $rule,
            "rules" => $rules,
            "rights" => $rights,
        ]);
    }

    /**
     * Обновить права доступа.
     *
     * @param Request $request
     * @param Role $role
     * @param RoleRule $rule
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Role $role, RoleRule $rule)
    {
        $permissions = $request->get("permissions", []);
        $rights = 0;
        $values = array_values($permissions);
        foreach ($values as $value) {
            $rights += $value;
        }
        $exist = false;
        foreach ($role->rules as $item) {
            if ($item->slug == $rule->slug) {
                $exist = true;
                break;
            }
        }
        if (! $exist) {
            $role->rules()->save($rule, [
                'rights' => $rights,
            ]);
        }
        else {
            $role->rules()->updateExistingPivot($rule->id, [
                'rights' => $rights,
            ]);
        }
        $rule->forgetCacheByRole($role->id);
        return redirect()
            ->back()
            ->with('success', 'Обновлено');
    }
}