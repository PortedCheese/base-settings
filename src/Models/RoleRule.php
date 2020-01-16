<?php

namespace PortedCheese\BaseSettings\Models;

use Illuminate\Database\Eloquent\Model;
use PortedCheese\BaseSettings\Traits\HasSlug;

class RoleRule extends Model
{
    use HasSlug;

    protected $fillable = [
        "title",
        "slug",
        "policy",
    ];

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
}
