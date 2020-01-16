<?php

namespace PortedCheese\BaseSettings\Policies;

use App\RoleRule;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BasePolicy
{
    use HandlesAuthorization;

    const SITE_MANAGEMENT = 2;

    protected $model;

    public function __construct()
    {
        try {
            $this->model = RoleRule::query()
                ->where("policy", "LIKE", "%BasePolicy")
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
            self::SITE_MANAGEMENT => "Доступ к админке",
        ];
    }

    public function siteManagement(User $user)
    {
        return $user->hasPermission($this->model, self::SITE_MANAGEMENT);
    }
}
