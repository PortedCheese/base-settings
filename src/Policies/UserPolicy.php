<?php

namespace PortedCheese\BaseSettings\Policies;

use App\RoleRule;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    const VIEW_ALL = 2;
    const CREATE = 4;
    const UPDATE = 8;
    const DELETE = 16;

    protected $model;

    public function __construct()
    {
        try {
            $this->model = RoleRule::query()
                ->where("policy", "LIKE", "%UserPolicy")
                ->firstOrFail();
        }
        catch (\Exception $exception) {
            $this->model = null;
        }
    }

    public function before($user, $ability)
    {
        if (! $this->model) {
            return false;
        }
    }

    public static function getPermissions()
    {
        return [
            self::VIEW_ALL => "Просмотр всех",
            self::CREATE => "Добавление",
            self::UPDATE => "Обновление",
            self::DELETE => "Удаление",
        ];
    }

    /**
     * Determine whether the user can view any tasks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission($this->model, self::VIEW_ALL);
    }

    /**
     * Determine whether the user can create tasks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission($this->model, self::CREATE);
    }

    /**
     * Обновление пользователей.
     *
     * @param User $user
     * @return bool
     */
    public function update(User $user)
    {
        return $user->hasPermission($this->model, self::UPDATE);
    }

    /**
     * Удаление пользователей.
     *
     * @param User $user
     * @return bool
     */
    public function delete(User $user)
    {
        return $user->hasPermission($this->model, self::DELETE);
    }
}
