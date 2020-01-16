<?php

namespace PortedCheese\BaseSettings\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use PortedCheese\BaseSettings\Traits\InitPolicy;

class BasePolicy
{
    use HandlesAuthorization;
    use InitPolicy {
        InitPolicy::__construct as private __ipoConstruct;
    }

    const SITE_MANAGEMENT = 2;

    public function __construct()
    {
        $this->__ipoConstruct("BasePolicy");
    }

    /**
     * Получить список доступов.
     *
     * @return array
     */
    public static function getPermissions()
    {
        return [
            self::SITE_MANAGEMENT => "Доступ к админке",
        ];
    }

    /**
     * Стандартные права.
     *
     * @return int
     */
    public static function defaultRules()
    {
        return self::SITE_MANAGEMENT;
    }

    /**
     * Управление сайтом.
     *
     * @param User $user
     * @return bool
     */
    public function siteManagement(User $user)
    {
        return $user->hasPermission($this->model, self::SITE_MANAGEMENT);
    }

    /**
     * Управление настройками.
     *
     * @param User $user
     * @return bool
     */
    public function settingsManagement(User $user)
    {
        return $user->isSuperUser();
    }
}
