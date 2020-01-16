<?php

namespace PortedCheese\BaseSettings\Traits;

use App\RoleRule;

trait InitPolicy
{
    public $model;

    public function __construct($policy)
    {
        try {
            $this->model = RoleRule::query()
                ->where("policy", "LIKE", "%$policy")
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
}