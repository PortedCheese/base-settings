<?php

namespace PortedCheese\BaseSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use PortedCheese\BaseSettings\Traits\HasSlug;

class RoleRule extends Model
{
    use HasSlug;

    const CACHE_KEY = "roleRule";

    protected $fillable = [
        "title",
        "slug",
        "policy",
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (\App\RoleRule $model) {
            $model->forgetCache();
        });

        static::updating(function (\App\RoleRule $model) {
            $model->forgetCache();
        });

        static::deleting(function (\App\RoleRule $model) {
            $model->roles()->sync([]);
            $model->forgetCache();
        });
    }

    /**
     * Роли по правам.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(\App\Role::class)
            ->withPivot("rights");
    }

    /**
     * Получить права доступа.
     *
     * @return array
     */
    public function getPermissions()
    {
        $class = "App\Policies\\" . $this->policy;
        if (class_exists($class)) {
            if (method_exists($class, "getPermissions")) {
                return $class::getPermissions();
            }
        }
        return [];
    }

    /**
     * Получить права по ролям.
     *
     * @param array $roleIds
     * @return array
     */
    public function getPermissionsByRoles(array $roleIds)
    {
        $rules = [];
        $rule = $this;
        foreach ($roleIds as $roleId) {
            $rules[] = Cache::rememberForever("rules:{$roleId}_{$rule->id}", function () use ($roleId, $rule) {
                $rights = DB::table("role_role_rule")
                    ->select("rights")
                    ->where("role_rule_id", $rule->id)
                    ->where("role_id", $roleId)
                    ->first();
                if (! empty($rights)) {
                    return $rights->rights;
                }
                else {
                    return 0;
                }
            });
        }
        return $rules;
    }

    /**
     * Забыть кэш по роли.
     *
     * @param $roleId
     */
    public function forgetCacheByRole($roleId)
    {
        Cache::forget("rules:{$roleId}_{$this->id}");
    }

    /**
     * Очистить кэш.
     */
    public function forgetCache()
    {
        Cache::forget(self::CACHE_KEY . ":{$this->policy}");
    }
}
