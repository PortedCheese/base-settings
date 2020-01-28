<?php

namespace PortedCheese\BaseSettings\Traits;

use App\RoleRule;
use Illuminate\Support\Facades\Cache;

trait InitPolicy
{
    public $model;

    public function __construct($policy)
    {
        $this->model = Cache::rememberForever(RoleRule::CACHE_KEY . ":{$policy}", function () use ($policy) {
            try {
                return RoleRule::query()
                    ->select(["id"])
                    ->where("policy", "LIKE", "%$policy")
                    ->firstOrFail();
            }
            catch (\Exception $exception) {
                return null;
            }
        });
    }

    public function before($user, $ability)
    {
        if (! $this->model) {
            return false;
        }
    }

    public static function getPermissions()
    {
        return [];
    }

    public static function defaultRules()
    {
        return 0;
    }
}