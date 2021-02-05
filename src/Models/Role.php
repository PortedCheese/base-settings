<?php

namespace PortedCheese\BaseSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Role extends Model
{

    const SUPER = "admin";
    const EDITOR = "editor";

    protected $fillable = [
        'name',
        'default',
        'title',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (\App\Role $role) {
            $role->users()->sync([]);
        });
    }

    /**
     * Пользователи.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users() {
        return $this->belongsToMany('App\User');
    }

    /**
     * Права доступа.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rules()
    {
        return $this->belongsToMany(\App\RoleRule::class)
            ->withPivot("rights");
    }

    /**
     * Получить редактирование для админа.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getForAdmin()
    {
        $roles = self::query();
        if (! Auth::user()->hasRole(\App\Role::SUPER)) {
            $roles->where('name', '!=', \App\Role::SUPER);
        }
        return $roles->get();
    }

    /**
     * Получить id главной роли.
     *
     * @return bool|mixed
     */
    public static function getSuperId() {
        $id = self::query()
            ->select("id")
            ->where("name", \App\Role::SUPER)
            ->first();
        if (empty($id)) return false;
        return $id->id;

    }
}
